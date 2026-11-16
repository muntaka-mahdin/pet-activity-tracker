<x-layout :title="$pet->name">
    <div class="page-head">
        <div class="pet-heading">
            <span class="pet-avatar large">{{ mb_substr($pet->name, 0, 1) }}</span>
            <div>
                <h1>{{ $pet->name }}</h1>
                <p class="muted">{{ $pet->species }}@if ($pet->breed) · {{ $pet->breed }}@endif @if ($pet->ageLabel()) · {{ $pet->ageLabel() }} old @endif</p>
            </div>
        </div>
        <div class="actions">
            <a href="{{ route('activities.create', ['pet' => $pet->id]) }}" class="btn">+ Log activity</a>
            <a href="{{ route('pets.edit', $pet) }}" class="btn btn-secondary">Edit</a>
            <form action="{{ route('pets.destroy', $pet) }}" method="POST"
                  onsubmit="return confirm('Delete {{ e($pet->name) }} and all their activities?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="two-col">
        <section class="card">
            <h2>Activity history</h2>
            <ul class="activity-list">
                @forelse ($activities as $activity)
                    <x-activity-row :activity="$activity" :show-pet="false" />
                @empty
                    <li class="muted">No activities logged for {{ $pet->name }} yet.</li>
                @endforelse
            </ul>
            {{ $activities->links('pagination::simple-default') }}
        </section>

        <div class="stack">
            @if ($pet->notes)
                <section class="card">
                    <h2>Notes</h2>
                    <p>{{ $pet->notes }}</p>
                </section>
            @endif
            <section class="card">
                <h2>Last 30 days</h2>
                @if ($byType->isEmpty())
                    <p class="muted">Nothing in the last 30 days.</p>
                @else
                    <ul class="bars">
                        @php($max = $byType->max())
                        @foreach ($byType as $type => $total)
                            <li>
                                <a href="{{ route('activities.index', ['pet' => $pet->id, 'type' => $type]) }}">{{ $type }}</a>
                                <span class="bar"><span style="width: {{ round($total / $max * 100) }}%"></span></span>
                                <span class="muted">{{ $total }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
</x-layout>
