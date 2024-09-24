<?php

namespace App\Http\Controllers\Api;

use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\PresenceResource;
use App\Http\Resources\PresenceCollection;
use App\Http\Requests\PresenceStoreRequest;
use App\Http\Requests\PresenceUpdateRequest;

class PresenceController extends Controller
{
    public function index(Request $request): PresenceCollection
    {
        $search = $request->get('search', '');

        $presences = $this->getSearchQuery($search)
            ->latest()
            ->paginate();

        return new PresenceCollection($presences);
    }

    public function store(PresenceStoreRequest $request): PresenceResource
    {
        $validated = $request->validated();

        $presence = Presence::create($validated);

        return new PresenceResource($presence);
    }

    public function show(Request $request, Presence $presence): PresenceResource
    {
        return new PresenceResource($presence);
    }

    public function update(
        PresenceUpdateRequest $request,
        Presence $presence
    ): PresenceResource {
        $validated = $request->validated();

        $presence->update($validated);

        return new PresenceResource($presence);
    }

    public function destroy(Request $request, Presence $presence): Response
    {
        $presence->delete();

        return response()->noContent();
    }

    public function getSearchQuery(string $search)
    {
        return Presence::query()->where('image_in', 'like', "%{$search}%");
    }
}
