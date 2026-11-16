<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Models\Pet;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::withCount('activities')
            ->withMax(['activities as last_activity_date' => fn ($q) => $q->where('activity_date', '<=', today()->toDateString())], 'activity_date')
            ->orderBy('name')
            ->get();

        return view('pets.index', compact('pets'));
    }

    public function create()
    {
        return view('pets.form', ['pet' => new Pet]);
    }

    public function store(PetRequest $request)
    {
        $pet = Pet::create($request->validated());

        return redirect()->route('pets.show', $pet)->with('success', "{$pet->name} added.");
    }

    public function show(Pet $pet)
    {
        $activities = $pet->activities()
            ->orderByDesc('activity_date')
            ->orderByDesc('id')
            ->paginate(10);

        $byType = $pet->activities()
            ->whereBetween('activity_date', [today()->subDays(29)->toDateString(), today()->toDateString()])
            ->selectRaw('activity_type, count(*) as total')
            ->groupBy('activity_type')
            ->orderByDesc('total')
            ->pluck('total', 'activity_type');

        return view('pets.show', compact('pet', 'activities', 'byType'));
    }

    public function edit(Pet $pet)
    {
        return view('pets.form', compact('pet'));
    }

    public function update(PetRequest $request, Pet $pet)
    {
        $pet->update($request->validated());

        return redirect()->route('pets.show', $pet)->with('success', 'Pet details updated.');
    }

    public function destroy(Pet $pet)
    {
        // Activities are removed with the pet by the foreign key's cascade.
        $pet->delete();

        return redirect()->route('pets.index')->with('success', "{$pet->name} and their activities were deleted.");
    }
}
