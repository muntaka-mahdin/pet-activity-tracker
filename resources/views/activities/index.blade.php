<x-layout title="Activities List">
    <h2>Activities List</h2>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <div class="scrollable-content item-list">
        @forelse ($activities as $activity)
            <a href="{{ route('activities.show', $activity->id) }}" class="item-link">
                <div class="item-entry">
                    <p class="item-name">
                        {{ $activity->activity_type }} on {{ $activity->activity_date->format('Y-m-d') }}
                    </p>
                </div>
            </a>
        @empty
            <p>No activities found.</p>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="pagination">
        {{ $activities->appends(['search' => $search])->links('pagination::simple-default') }}
    </div>
</x-layout>