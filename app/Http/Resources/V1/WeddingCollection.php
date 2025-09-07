<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class WeddingCollection extends ResourceCollection
{
    /**
     * Disable default 'data' wrapping for collections.
     */
    public static $wrap = null;

    /**
     * Specify the resource that this resource collects.
     */
    public $collects = WeddingResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int, array<string, mixed>>
     */
    public function toArray(Request $request): array
    {
        // When paginated, include items with meta and links, without inner 'data' key
        if ($this->resource instanceof LengthAwarePaginator || $this->resource instanceof Paginator) {
            $paginator = $this->resource;

            return [
                'items' => $this->collection->map(function ($item) use ($request) {
                    return (new WeddingResource($item))->toArray($request);
                })->all(),
                'meta' => [
                    'current_page' => method_exists($paginator, 'currentPage') ? $paginator->currentPage() : null,
                    'per_page' => method_exists($paginator, 'perPage') ? $paginator->perPage() : null,
                    'from' => method_exists($paginator, 'firstItem') ? $paginator->firstItem() : null,
                    'to' => method_exists($paginator, 'lastItem') ? $paginator->lastItem() : null,
                    'total' => $paginator instanceof LengthAwarePaginator ? $paginator->total() : null,
                    'last_page' => method_exists($paginator, 'lastPage') ? $paginator->lastPage() : null,
                ],
                'links' => [
                    'first' => method_exists($paginator, 'url') ? $paginator->url(1) : null,
                    'last' => method_exists($paginator, 'lastPage') && method_exists($paginator, 'url') ? $paginator->url($paginator->lastPage()) : null,
                    'prev' => method_exists($paginator, 'previousPageUrl') ? $paginator->previousPageUrl() : null,
                    'next' => method_exists($paginator, 'nextPageUrl') ? $paginator->nextPageUrl() : null,
                ],
            ];
        }

        // Non-paginated: return items directly
        return $this->collection->map(function ($item) use ($request) {
            return (new WeddingResource($item))->toArray($request);
        })->all();
    }
}
