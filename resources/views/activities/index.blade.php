<x-layout title="Activities">
    <div class="page-head">
        <h1>Activities</h1>
        <a href="{{ route('activities.create') }}" class="btn">+ Log activity</a>
    </div>

    <form action="{{ route('activities.index') }}" method="GET" class="card filters">
        <label>Search
            <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Type or notes…">
        </label>
        <label>Pet
            <select name="pet">
                <option value="">All pets</option>
                @foreach ($pets as $pet)
                    <option value="{{ $pet->id }}" @selected(($filters['pet'] ?? '') == $pet->id)>{{ $pet->name }}</option>
                @endforeach
            </select>
        </label>
        <label>Type
            <select name="type">
                <option value="">All types</option>
                @foreach ($types as $type)
                    <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </label>
        <div class="filter-actions">
            <button type="submit" class="btn">Filter</button>
            @if (array_filter($filters))
                <a href="{{ route('activities.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </div>
    </form>

    <section class="card">
        <p class="muted small">{{ $activities->total() }} {{ str('activity')->plural($activities->total()) }}</p>
        <ul class="activity-list">
            @forelse ($activities as $activity)
                <x-activity-row :activity="$activity" />
            @empty
                <li class="muted">No activities match these filters.</li>
            @endforelse
        </ul>
        {{ $activities->links('pagination::simple-default') }}
    </section>
</x-layout>
