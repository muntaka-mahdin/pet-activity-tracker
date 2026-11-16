@php($editing = $pet->exists)
<x-layout :title="$editing ? 'Edit '.$pet->name : 'Add a pet'">
    <h1>{{ $editing ? 'Edit '.$pet->name : 'Add a pet' }}</h1>

    <form action="{{ $editing ? route('pets.update', $pet) : route('pets.store') }}" method="POST" class="card form">
        @csrf
        @if ($editing) @method('PATCH') @endif
        <x-errors />

        <label>Name
            <input type="text" name="name" value="{{ old('name', $pet->name) }}" maxlength="100" required>
        </label>

        <div class="form-row">
            <label>Species
                <select name="species" required>
                    <option value="">Choose…</option>
                    @foreach (\App\Models\Pet::SPECIES as $species)
                        <option value="{{ $species }}" @selected(old('species', $pet->species) === $species)>{{ $species }}</option>
                    @endforeach
                </select>
            </label>
            <label>Breed <span class="muted">(optional)</span>
                <input type="text" name="breed" value="{{ old('breed', $pet->breed) }}" maxlength="100">
            </label>
        </div>

        <label>Date of birth <span class="muted">(optional)</span>
            <input type="date" name="date_of_birth" max="{{ today()->toDateString() }}"
                   value="{{ old('date_of_birth', $pet->date_of_birth?->toDateString()) }}">
        </label>

        <label>Notes <span class="muted">(optional, e.g. allergies or routines)</span>
            <textarea name="notes" rows="3" maxlength="2000">{{ old('notes', $pet->notes) }}</textarea>
        </label>

        <div class="form-actions">
            <a href="{{ $editing ? route('pets.show', $pet) : route('pets.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn">{{ $editing ? 'Save changes' : 'Add pet' }}</button>
        </div>
    </form>
</x-layout>
