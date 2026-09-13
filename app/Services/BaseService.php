<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class BaseService
{
    /**
     * Constructor with property promotion (PHP 8.4 feature)
     */
    public function __construct(
        protected readonly string $modelClass,
        protected readonly bool $useTransactions = true,
        protected readonly bool $enableLogging = true,
    ) {
        if (!class_exists($this->modelClass)) {
            throw new \InvalidArgumentException("Model class {$this->modelClass} does not exist");
        }
    }

    /**
     * Get all records with optional filtering
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->modelClass::query();

        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        return $query->get();
    }

    /**
     * Get paginated records
     */
    public function getPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->modelClass::query();

        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        return $query->paginate($perPage);
    }

    /**
     * Find record by ID
     */
    public function findById(int|string $id): ?Model
    {
        return $this->modelClass::find($id);
    }

    /**
     * Find record by ID or fail
     */
    public function findByIdOrFail(int|string $id): Model
    {
        return $this->modelClass::findOrFail($id);
    }

    /**
     * Create new record with transaction support
     */
    public function create(array $data): Model
    {
        return $this->executeWithTransaction(function () use ($data) {
            $model = $this->modelClass::create($data);
            
            $this->logAction('created', $model);
            
            return $model;
        });
    }

    /**
     * Update record with transaction support
     */
    public function update(int|string $id, array $data): Model
    {
        return $this->executeWithTransaction(function () use ($id, $data) {
            $model = $this->findByIdOrFail($id);
            $model->update($data);
            
            $this->logAction('updated', $model);
            
            return $model->fresh();
        });
    }

    /**
     * Delete record (soft delete if supported)
     */
    public function delete(int|string $id): bool
    {
        return $this->executeWithTransaction(function () use ($id) {
            $model = $this->findByIdOrFail($id);
            $result = $model->delete();
            
            $this->logAction('deleted', $model);
            
            return $result;
        });
    }

    /**
     * Apply filters to query (override in child services)
     */
    protected function applyFilters($query, array $filters)
    {
        foreach ($filters as $field => $value) {
            if (!is_null($value)) {
                $query->where($field, $value);
            }
        }

        return $query;
    }

    /**
     * Execute operation with transaction support
     */
    protected function executeWithTransaction(callable $operation)
    {
        if (!$this->useTransactions) {
            return $operation();
        }

        return DB::transaction($operation);
    }

    /**
     * Log service actions
     */
    protected function logAction(string $action, Model $model): void
    {
        if (!$this->enableLogging) {
            return;
        }

        Log::info("Service Action: {$action}", [
            'service' => static::class,
            'model' => get_class($model),
            'model_id' => $model->getKey(),
            'action' => $action,
        ]);
    }

    /**
     * Get model class name
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }
}
