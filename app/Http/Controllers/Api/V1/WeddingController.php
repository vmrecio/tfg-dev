<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WeddingIndexRequest;
use App\Http\Resources\V1\WeddingCollection;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Builder;

class WeddingController extends Controller
{
    public function index(WeddingIndexRequest $request): WeddingCollection
    {
        $query = Wedding::query()
            ->withCount(['guests', 'vendors', 'timelineItems']);

        $this->applyFilters($query, $request->validated());

        $perPage = (int) ($request->validated()['per_page'] ?? 15);

        $weddings = $query
            ->orderBy('event_date')
            ->paginate($perPage)
            ->withQueryString();

        return new WeddingCollection($weddings);
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $eventDate = $filters['event_date'] ?? null;
        $fromDate = $filters['event_date_from'] ?? null;
        $toDate = $filters['event_date_to'] ?? null;
        $location = isset($filters['location']) ? trim((string) $filters['location']) : null;

        $query
            ->when($eventDate, function (Builder $q) use ($eventDate) {
                $q->whereDate('event_date', $eventDate);
            })
            ->when(!$eventDate && $fromDate, function (Builder $q) use ($fromDate) {
                $q->whereDate('event_date', '>=', $fromDate);
            })
            ->when(!$eventDate && $toDate, function (Builder $q) use ($toDate) {
                $q->whereDate('event_date', '<=', $toDate);
            })
            ->when($location !== null && $location !== '', function (Builder $q) use ($location) {
                $q->where('location', 'like', "%{$location}%");
            });
    }
}
