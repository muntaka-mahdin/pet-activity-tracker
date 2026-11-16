@props(['activity', 'showPet' => true])
@php($upcoming = $activity->activity_date->isFuture())
<li class="activity-row">
    <div class="date-badge @if($upcoming) upcoming @endif">
        <span class="day">{{ $activity->activity_date->format('j') }}</span>
        <span class="month">{{ $activity->activity_date->format('M') }}</span>
    </div>
    <div class="activity-body">
        <a href="{{ route('activities.show', $activity) }}" class="activity-title">{{ $activity->activity_type }}</a>
        @if ($showPet && $activity->pet)
            <span class="muted">· <a href="{{ route('pets.show', $activity->pet) }}">{{ $activity->pet->name }}</a></span>
        @endif
        @if ($upcoming)
            <span class="tag">Planned</span>
        @endif
        @if ($activity->description)
            <p class="muted small">{{ \Illuminate\Support\Str::limit($activity->description, 90) }}</p>
        @endif
    </div>
</li>
