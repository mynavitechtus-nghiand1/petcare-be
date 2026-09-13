<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // By default, return all resource attributes
        return parent::toArray($request);
    }
    
    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with(Request $request): array
    {
        return [
            'success' => true,
            'timestamp' => now()->toISOString(),
        ];
    }
    
    /**
     * Customize the response for a request.
     */
    public function withResponse(Request $request, $response): void
    {
        $response->header('X-API-Version', '1.0');
    }
    
    /**
     * Apply conditional transformations
     */
    protected function when($condition, $value, $default = null)
    {
        return parent::when($condition, $value, $default);
    }
    
    /**
     * Apply conditional merge
     */
    protected function mergeWhen($condition, $value, $default = [])
    {
        return parent::mergeWhen($condition, $value, $default);
    }
    
    /**
     * Include timestamps if available
     */
    protected function withTimestamps(): array
    {
        $timestamps = $this->mergeWhen(
            $this->resource && method_exists($this->resource, 'getAttributes'),
            function () {
                $attributes = $this->resource->getAttributes();
                return [
                    'created_at' => $this->when(
                        isset($attributes['created_at']),
                        fn() => $this->resource->created_at?->toISOString()
                    ),
                    'updated_at' => $this->when(
                        isset($attributes['updated_at']),
                        fn() => $this->resource->updated_at?->toISOString()
                    ),
                ];
            },
            []
        );
        
        // Convert MergeValue to array for type safety
        return $timestamps instanceof \Illuminate\Http\Resources\MergeValue 
            ? $timestamps->data 
            : (is_array($timestamps) ? $timestamps : []);
    }
    
    /**
     * Include soft delete information if available
     */
    protected function withSoftDeletes(): array
    {
        return $this->mergeWhen(
            $this->resource && method_exists($this->resource, 'trashed'),
            [
                'is_deleted' => $this->resource->trashed(),
                'deleted_at' => $this->when(
                    $this->resource->trashed(),
                    fn() => $this->resource->deleted_at?->toISOString()
                ),
            ]
        );
    }
    
    /**
     * Include pagination meta data for collections
     */
    public static function collection($resource)
    {
        $collection = parent::collection($resource);
        
        if ($resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return $collection->additional([
                'meta' => [
                    'pagination' => [
                        'current_page' => $resource->currentPage(),
                        'per_page' => $resource->perPage(),
                        'total' => $resource->total(),
                        'last_page' => $resource->lastPage(),
                        'from' => $resource->firstItem(),
                        'to' => $resource->lastItem(),
                        'has_more_pages' => $resource->hasMorePages(),
                        'path' => $resource->path(),
                        'next_page_url' => $resource->nextPageUrl(),
                        'prev_page_url' => $resource->previousPageUrl(),
                    ]
                ]
            ]);
        }
        
        return $collection;
    }
}
