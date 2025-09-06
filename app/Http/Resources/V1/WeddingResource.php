<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeddingResource extends JsonResource
{
    /**
     * Disable default 'data' wrapping for this resource.
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'event_date' => optional($this->event_date)->toDateString(),
            'location' => $this->location,
            'description' => $this->description,

            'couple' => $this->whenLoaded('users', function () {
                return $this->users->take(2)->map(function ($u) {
                    return [
                        'id' => $u->id,
                        'name' => $u->name,
                        'last_name' => $u->last_name,
                        'email' => $u->email,
                    ];
                })->values();
            }),

            'guests_count' => $this->whenCounted('guests'),
            'vendors_count' => $this->whenCounted('vendors'),
            'timeline_items_count' => $this->whenCounted('timelineItems'),

            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}

