<x-layout title="Add New Activity">
    <h2>Add New Activity</h2>

    @if ($errors->any())
        <div class="error">
            <p><strong>Errors:</strong></p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('activities.store') }}" method="POST">
        @csrf

        <label for="activity_type">Activity Type:</label>
        <input type="text" name="activity_type" id="activity_type" value="{{ old('activity_type') }}" required>

        <label for="activity_date">Activity Date:</label>
        <input type="date" name="activity_date" id="activity_date" value="{{ old('activity_date', isset($activity) ? $activity->activity_date->format('Y-m-d') : '') }}" required>
        <small>Please enter a date within the last 5 months and not in the future.</small>

        <label for="description">Description:</label>
        <textarea name="description" id="description">{{ old('description') }}</textarea>

        <button type="submit" class="btn">Save Activity</button>
    </form>

    <a href="{{ route('activities.index') }}" class="btn">Back to List</a>
</x-layout>
