<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Pet;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Dates are stored as Y-m-d, so compare with plain date strings.
        $today = today()->toDateString();
        $weekStart = today()->subDays(6)->toDateString();

        return view('dashboard', [
            'petCount' => Pet::count(),
            'thisWeek' => Activity::whereBetween('activity_date', [$weekStart, $today])->count(),
            'upcoming' => Activity::with('pet')
                ->where('activity_date', '>', $today)
                ->orderBy('activity_date')
                ->limit(5)
                ->get(),
            'recent' => Activity::with('pet')
                ->where('activity_date', '<=', $today)
                ->orderByDesc('activity_date')
                ->orderByDesc('id')
                ->limit(8)
                ->get(),
            'pets' => Pet::withCount(['activities as week_count' => fn ($q) => $q->whereBetween('activity_date', [$weekStart, $today])])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
