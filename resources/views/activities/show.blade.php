<x-layout :title="$activity->activity_type">
    <div class="page-head">
        <div>
            <h1>{{ $activity->activity_type }}</h1>
            <p class="muted">
                <a href="{{ route('pets.show', $activity->pet) }}">{{ $activity->pet->name }}</a>
                · {{ $activity->activity_date->format('l j F Y') }}
                @if ($activity->activity_date->isFuture()) <span class="tag">Planned</span> @endif
            </p>
        </div>
        <div class="actions">
            <a href="{{ route('activities.edit', $activity) }}" class="btn btn-secondary">Edit</a>
            <form action="{{ route('activities.destroy', $activity) }}" method="POST"
                  onsubmit="return confirm('Delete this activity?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <section class="card">
        <h2>Notes</h2>
        <p>{{ $activity->description ?: 'No notes.' }}</p>
        <p class="muted small">Logged {{ $activity->created_at->format('j M Y, H:i') }}@if ($activity->updated_at->ne($activity->created_at)) · edited {{ $activity->updated_at->format('j M Y, H:i') }}@endif</p>
    </section>

    <p><a href="{{ route('activities.index') }}">← All activities</a></p>
</x-layout>
