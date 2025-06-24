<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTerrainRequest;
use App\Http\Requests\UpdateTerrainRequest;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TerrainController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Terrain::class, 'terrain');
    }

    public function index(Request $request)
    {
        $query = Terrain::with(['owner', 'images', 'reviews']);

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        if ($request->has('available')) {
            $query->where('is_available', true);
        }

        $terrains = $query->paginate(15);

        return response()->json($terrains);
    }

    public function store(StoreTerrainRequest $request)
    {
        $validated = $request->validated();
        $validated['owner_id'] = auth()->id();

        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('terrains', 'public');
        }

        $terrain = Terrain::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $terrain->images()->create([
                    'image_path' => $image->store('terrains', 'public'),
                ]);
            }
        }

        return response()->json($terrain->load(['owner', 'images']), 201);
    }

    public function show(Terrain $terrain)
    {
        return response()->json($terrain->load(['owner', 'images', 'reviews.user', 'bookings']));
    }

    public function update(UpdateTerrainRequest $request, Terrain $terrain)
    {
        $validated = $request->validated();

        if ($request->hasFile('main_image')) {
            if ($terrain->main_image) {
                Storage::disk('public')->delete($terrain->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('terrains', 'public');
        }

        $terrain->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $terrain->images()->create([
                    'image_path' => $image->store('terrains', 'public'),
                ]);
            }
        }

        return response()->json($terrain->load(['owner', 'images']));
    }

    public function destroy(Terrain $terrain)
    {
        if ($terrain->main_image) {
            Storage::disk('public')->delete($terrain->main_image);
        }

        foreach ($terrain->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $terrain->delete();

        return response()->json(['message' => 'Terrain deleted successfully']);
    }
}