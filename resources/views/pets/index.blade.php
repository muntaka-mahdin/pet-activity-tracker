<x-layout title="Pets">
    <div class="page-head">
        <h1>Pets</h1>
        <a href="{{ route('pets.create') }}" class="btn">+ Add a pet</a>
    </div>

    <div class="pet-grid">
        @forelse ($pets as $pet)
            <a href="{{ route('pets.show', $pet) }}" class="card pet-card">
                <span class="pet-avatar">{{ mb_substr($pet->name, 0, 1) }}</span>
                <div>
                    <h2>{{ $pet->name }}</h2>
                    <p class="muted">{{ $pet->species }}@if ($pet->breed) · {{ $pet->breed }}@endif @if ($pet->ageLabel()) · {{ $pet->ageLabel() }}@endif</p>
                    <p class="small">
                        {{ $pet->activities_count }} {{ str('activity')->plural($pet->activities_count) }}
                        @if ($pet->last_activity_date)
                            · last on {{ \Illuminate\Support\Carbon::parse($pet->last_activity_date)->format('j M') }}
                        @endif
                    </p>
                </div>
            </a>
        @empty
            <div class="card empty">
                <p>No pets yet.</p>
                <a href="{{ route('pets.create') }}" class="btn">Add your first pet</a>
            </div>
        @endforelse
    </div>
</x-layout>
