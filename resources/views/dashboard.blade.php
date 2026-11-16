<x-layout title="Dashboard">
    <h1>Dashboard</h1>

    <section class="stats">
        <div class="stat card"><strong>{{ $petCount }}</strong><span>{{ str('pet')->plural($petCount) }}</span></div>
        <div class="stat card"><strong>{{ $thisWeek }}</strong><span>activities in the last 7 days</span></div>
        <div class="stat card"><strong>{{ $upcoming->count() }}</strong><span>planned</span></div>
    </section>

    @if ($petCount === 0)
        <div class="card empty">
            <p>No pets yet. Add your first pet to start logging walks, meals and vet visits.</p>
            <a href="{{ route('pets.create') }}" class="btn">Add a pet</a>
        </div>
    @else
        <div class="two-col">
            <section class="card">
                <h2>Recent activity</h2>
                <ul class="activity-list">
                    @forelse ($recent as $activity)
                        <x-activity-row :activity="$activity" />
                    @empty
                        <li class="muted">Nothing logged yet.</li>
                    @endforelse
                </ul>
                <a href="{{ route('activities.index') }}" class="link-more">All activities →</a>
            </section>

            <div class="stack">
                <section class="card">
                    <h2>Coming up</h2>
                    <ul class="activity-list">
                        @forelse ($upcoming as $activity)
                            <x-activity-row :activity="$activity" />
                        @empty
                            <li class="muted">Nothing planned. Log a future date to plan a vet visit.</li>
                        @endforelse
                    </ul>
                </section>

                <section class="card">
                    <h2>This week by pet</h2>
                    <ul class="bars">
                        @php($max = max(1, $pets->max('week_count')))
                        @foreach ($pets as $pet)
                            <li>
                                <a href="{{ route('pets.show', $pet) }}">{{ $pet->name }}</a>
                                <span class="bar"><span style="width: {{ round($pet->week_count / $max * 100) }}%"></span></span>
                                <span class="muted">{{ $pet->week_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>
        </div>
    @endif
</x-layout>
