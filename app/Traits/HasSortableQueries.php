<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSortableQueries
{
    /**
     * Apply sorting to the query with support for table joins and dot notation
     * 
     * Supports:
     * - Single sort: "field" or "company.name" (dot notation for related fields)
     * - Multiple sort: "field1,field2" or "full_name,company.name"
     * - Dot notation: "company.name" for sorting by related table columns
     * 
     * Note: Sort order is specified via separate $sortOrder parameter (not prefix notation).
     * This maintains backward compatibility with existing API format.
     * 
     * @param Builder $query The query builder instance
     * @param string|null $sortField Sort field (can be simple column or dot notation like "company.name")
     * @param string $sortOrder Sort order: 'asc' or 'desc'
     * @param array $sortConfig Configuration array mapping sort fields to columns or join configs
     * @param string|null $defaultSort Default sort field if $sortField is null
     * @return void
     */
    protected function applySorting(
        Builder $query,
        ?string $sortField,
        string $sortOrder = 'asc',
        array $sortConfig = [],
        ?string $defaultSort = null
    ): void {
        // Use default sort if no sort field provided
        $sortField = $sortField ?? $defaultSort;
        
        // Validate sort order
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';
        
        // If no sort field, do nothing
        if (empty($sortField) || !isset($sortConfig[$sortField])) {
            return;
        }
        
        $sortDefinition = $sortConfig[$sortField];
        
        if (is_array($sortDefinition)) {
            // Raw SQL sort: 'raw_asc' / 'raw_desc' (full expression per direction) or 'raw' (expression + append order)
            if (isset($sortDefinition['raw_asc'], $sortDefinition['raw_desc'])) {
                $query->orderByRaw($sortOrder === 'asc' ? $sortDefinition['raw_asc'] : $sortDefinition['raw_desc']);
                return;
            }
            if (isset($sortDefinition['raw'])) {
                $query->orderByRaw($sortDefinition['raw'] . ' ' . $sortOrder);
                return;
            }
            // Join config
            $this->applySortingWithJoin($query, $sortDefinition, $sortOrder);
        } else {
            // Simple column sort
            $query->orderBy($sortDefinition, $sortOrder);
        }
    }
    
    /**
     * Apply sorting with table join
     * 
     * Simple mapping: field => [join, column]
     * Supports both inner join and left join dynamically
     * 
     * @param Builder $query The query builder instance
     * @param array $joinConfig Join configuration with keys:
     *                         - 'join': Array [leftColumn, operator, rightColumn]
     *                         - 'column': Column to sort by (with table prefix, e.g., 'companies.name')
     *                         - 'table': (optional) Table name, auto-detected from column if not provided
     *                         - 'type': (optional) Join type: 'left', 'inner', 'right', 'outer'. Default: 'left'
     * @param string $sortOrder Sort order
     * @return void
     */
    protected function applySortingWithJoin(Builder $query, array $joinConfig, string $sortOrder): void
    {
        $join = $joinConfig['join'] ?? null;
        $column = $joinConfig['column'] ?? null;
        $joinType = strtolower($joinConfig['type'] ?? 'left');

        if (!$join || !$column || !is_array($join) || count($join) !== 3) {
            throw new \InvalidArgumentException('Join config must have join [leftColumn, operator, rightColumn] and column keys');
        }
        
        // Apply join dynamically based on type
        [$leftColumn, $operator, $rightColumn] = $join;
        $table = $joinConfig['table'] ?? $this->getTableFromColumn($column);
        
        match ($joinType) {
            'left' => $query->leftJoin($table, $leftColumn, $operator, $rightColumn),
            'inner' => $query->join($table, $leftColumn, $operator, $rightColumn),
            'right' => $query->rightJoin($table, $leftColumn, $operator, $rightColumn),
            'outer' => $query->outerJoin($table, $leftColumn, $operator, $rightColumn),
            default => throw new \InvalidArgumentException("Invalid join type: {$joinType}. Allowed: left, inner, right, outer")
        };
        
        // Apply sorting
        $query->orderBy($column, $sortOrder);
    }
    
    /**
     * Extract table name from column (e.g., 'companies.name' -> 'companies')
     * 
     * @param string $column Column with table prefix
     * @return string Table name
     */
    protected function getTableFromColumn(string $column): string
    {
        if (str_contains($column, '.')) {
            return explode('.', $column)[0];
        }
        throw new \InvalidArgumentException("Cannot extract table from column: {$column}");
    }
    
    /**
     * Get sort configuration for the repository
     * 
     * This method should be overridden in the repository class to define
     * available sort fields and their configurations.
     * 
     * @return array Sort configuration mapping
     */
    protected function getSortConfig(): array
    {
        return [];
    }
}

