<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WeddingIndexRequest;
use App\Http\Requests\V1\WeddingStoreRequest;
use App\Http\Requests\V1\WeddingUpdateRequest;
use App\Http\Resources\V1\WeddingResource;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class WeddingController extends Controller
{
    public function index(WeddingIndexRequest $request): JsonResponse
    {
        $query = Wedding::query()
            ->withCount(['guests', 'vendors', 'timelineItems']);

        $this->applyFilters($query, $request->validated());

        $perPage = (int) ($request->validated()['per_page'] ?? 15);

        $weddings = $query
            ->orderBy('event_date')
            ->paginate($perPage)
            ->withQueryString();

        $items = $weddings->getCollection()
            ->map(fn ($item) => (new WeddingResource($item))->toArray($request))
            ->all();

        return response()->json([
            'items' => $items,
            'meta' => [
                'current_page' => $weddings->currentPage(),
                'per_page' => $weddings->perPage(),
                'from' => $weddings->firstItem(),
                'to' => $weddings->lastItem(),
                'total' => $weddings->total(),
                'last_page' => $weddings->lastPage(),
            ],
            'links' => [
                'first' => $weddings->url(1),
                'last' => $weddings->url($weddings->lastPage()),
                'prev' => $weddings->previousPageUrl(),
                'next' => $weddings->nextPageUrl(),
            ],
        ]);
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

    public function store(WeddingStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $base = Str::slug($data['name']);
            $slug = $base ?: Str::random(8);
            $i = 1;
            while (Wedding::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i;
                $i++;
            }
            $data['slug'] = $slug;
        }

        $wedding = Wedding::create($data);

        return response()->json((new WeddingResource($wedding))->toArray($request), 201);
    }

    public function show(Wedding $wedding): JsonResponse
    {
        return response()->json((new WeddingResource($wedding))->toArray(request()));
    }

    public function update(WeddingUpdateRequest $request, Wedding $wedding): JsonResponse
    {
        $data = $request->validated();

        // If slug provided empty string, regenerate
        if (array_key_exists('slug', $data) && empty($data['slug']) && !empty($data['name'])) {
            $base = Str::slug($data['name']);
            $slug = $base ?: Str::random(8);
            $i = 1;
            while (Wedding::where('slug', $slug)->where('id', '!=', $wedding->id)->exists()) {
                $slug = $base . '-' . $i;
                $i++;
            }
            $data['slug'] = $slug;
        }

        $wedding->update($data);

        return response()->json((new WeddingResource($wedding))->toArray($request));
    }

    public function destroy(Wedding $wedding): JsonResponse
    {
        $wedding->delete();
        return response()->json(null, 204);
    }
}
