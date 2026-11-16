<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Activity extends Model
{
    use HasFactory;

    /** Suggested types; users can still type their own. */
    public const TYPES = [
        'Morning Walk', 'Evening Walk', 'Feeding', 'Vet Visit', 'Grooming',
        'Training', 'Playtime', 'Bath', 'Medication', 'Playdate',
    ];

    protected $fillable = ['pet_id', 'activity_type', 'activity_date', 'description'];

    protected function casts(): array
    {
        return ['activity_date' => 'date'];
    }

    /** Store as Y-m-d (the date cast would add 00:00:00, which breaks date comparisons in SQLite). */
    protected function activityDate(): Attribute
    {
        return Attribute::set(fn ($value) => $value ? Carbon::parse($value)->toDateString() : null);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /** Apply the list filters from the query string: free-text search, pet and type. */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function (Builder $q, string $search) {
                $q->where(function (Builder $q) use ($search) {
                    $q->where('activity_type', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($filters['pet'] ?? null, fn (Builder $q, $pet) => $q->where('pet_id', $pet))
            ->when($filters['type'] ?? null, fn (Builder $q, $type) => $q->where('activity_type', $type));
    }
}
