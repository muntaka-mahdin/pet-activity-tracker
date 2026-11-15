<x-layout title="Activity Details">
    <h2>Activity Details</h2>

    <div class="activity-details">
        <p><strong>Activity Type:</strong> {{ $activity->activity_type }}</p>
        <p><strong>Activity Date:</strong> {{ $activity->activity_date->format('Y-m-d') }}</p>
        <p><strong>Description:</strong> {{ $activity->description }}</p>
    </div>

    <div class="action-buttons">
        <a href="{{ route('activities.edit', $activity->id) }}" class="btn">Edit</a>

        <form action="{{ route('activities.destroy', $activity->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn">Delete</button>
        </form>

        <a href="{{ route('activities.index') }}" class="btn">Back to List</a>
    </div>
</x-layout>
