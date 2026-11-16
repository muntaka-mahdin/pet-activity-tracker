<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Pet;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'pet', 'type']);

        $activities = Activity::with('pet')
            ->filter($filters)
            ->orderByDesc('activity_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('activities.index', [
            'activities' => $activities,
            'filters' => $filters,
            'pets' => Pet::orderBy('name')->get(['id', 'name']),
            'types' => Activity::query()->distinct()->orderBy('activity_type')->pluck('activity_type'),
        ]);
    }

    public function create(Request $request)
    {
        if (Pet::doesntExist()) {
            return redirect()->route('pets.create')->with('success', 'Add a pet first, then you can log its activities.');
        }

        return view('activities.form', [
            'activity' => new Activity([
                'pet_id' => $request->integer('pet') ?: null,
                'activity_date' => now(),
            ]),
            'pets' => Pet::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(ActivityRequest $request)
    {
        $activity = Activity::create($request->validated());

        return redirect()->route('activities.show', $activity)->with('success', 'Activity added.');
    }

    public function show(Activity $activity)
    {
        return view('activities.show', ['activity' => $activity->load('pet')]);
    }

    public function edit(Activity $activity)
    {
        return view('activities.form', [
            'activity' => $activity,
            'pets' => Pet::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        $activity->update($request->validated());

        return redirect()->route('activities.show', $activity)->with('success', 'Activity updated.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activity deleted.');
    }
}
