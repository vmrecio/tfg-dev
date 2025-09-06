<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;

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
        // Return the items directly to avoid an inner 'data' key
        return $this->collection->toArray();
    }
}
