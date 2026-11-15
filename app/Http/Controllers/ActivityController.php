<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // Display a listing of activities
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Activity::query();

        if ($search) {
            $query->where('activity_type', 'like', '%' . $search . '%');
        }

        $activities = $query->orderBy('activity_date', 'desc')->simplePaginate(6);

        return view('activities.index', compact('activities', 'search'));
    }


    // Show the form for creating a new activity
    public function create()
    {
        return view('activities.create');
    }

    // Store a newly created activity in storage
    public function store(Request $request)
    {
        $request->validate([
            'activity_type' => 'required|string|max:255',
            'activity_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->subMonths(5)->toDateString(),
                'before_or_equal:' . now()->addYear()->toDateString(),
            ],
            'description'   => 'nullable|string',
        ]);

        Activity::create($request->all());

        return redirect()->route('activities.index')->with('success', 'Activity created successfully.');
    }


    // Display the specified activity
    public function show($id)
    {
        $activity = Activity::findOrFail($id);
        return view('activities.show', compact('activity'));
    }

    // Show the form for editing the specified activity
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('activities.edit', compact('activity'));
    }

    // Update the specified activity in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'activity_type' => 'required|string|max:255',
            'activity_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->subMonths(5)->toDateString(),
                'before_or_equal:' . now()->addYear()->toDateString(),
            ],
            'description'   => 'nullable|string',
        ]);

        $activity = Activity::findOrFail($id);
        $activity->update($request->all());

        return redirect()->route('activities.index')->with('success', 'Activity updated successfully.');
    }

    // Remove the specified activity from storage
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activity deleted successfully.');
    }

    // Display the 'About' page
    public function about()
    {
        return view('about');
    }
}
