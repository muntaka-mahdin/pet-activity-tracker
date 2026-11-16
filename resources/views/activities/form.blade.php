@php($editing = $activity->exists)
<x-layout :title="$editing ? 'Edit activity' : 'Log an activity'">
    <h1>{{ $editing ? 'Edit activity' : 'Log an activity' }}</h1>

    <form action="{{ $editing ? route('activities.update', $activity) : route('activities.store') }}" method="POST" class="card form">
        @csrf
        @if ($editing) @method('PATCH') @endif
        <x-errors />

        <div class="form-row">
            <label>Pet
                <select name="pet_id" required>
                    <option value="">Choose…</option>
                    @foreach ($pets as $pet)
                        <option value="{{ $pet->id }}" @selected(old('pet_id', $activity->pet_id) == $pet->id)>{{ $pet->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Date
                <input type="date" name="activity_date" required
                       min="{{ now()->subYears(2)->toDateString() }}" max="{{ now()->addYear()->toDateString() }}"
                       value="{{ old('activity_date', $activity->activity_date?->toDateString()) }}">
            </label>
        </div>

        <label>Activity
            <input type="text" name="activity_type" list="activity-types" maxlength="255" required
                   value="{{ old('activity_type', $activity->activity_type) }}" placeholder="e.g. Morning Walk">
            <datalist id="activity-types">
                @foreach (\App\Models\Activity::TYPES as $type)
                    <option value="{{ $type }}">
                @endforeach
            </datalist>
        </label>
        <p class="muted small">Pick a suggestion or type your own. Use a future date to plan something, like a vet visit.</p>

        <label>Notes <span class="muted">(optional)</span>
            <textarea name="description" rows="3" maxlength="2000">{{ old('description', $activity->description) }}</textarea>
        </label>

        <div class="form-actions">
            <a href="{{ $editing ? route('activities.show', $activity) : route('activities.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn">{{ $editing ? 'Save changes' : 'Save activity' }}</button>
        </div>
    </form>
</x-layout>
