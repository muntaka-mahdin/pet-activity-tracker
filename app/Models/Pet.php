<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Pet extends Model
{
    use HasFactory;

    public const SPECIES = ['Dog', 'Cat', 'Rabbit', 'Bird', 'Fish', 'Reptile', 'Other'];

    protected $fillable = ['name', 'species', 'breed', 'date_of_birth', 'notes'];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    /** Store as Y-m-d, like Activity::activityDate(). */
    protected function dateOfBirth(): Attribute
    {
        return Attribute::set(fn ($value) => $value ? Carbon::parse($value)->toDateString() : null);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /** Age as a short label, e.g. "3 years" or "5 months"; null if the birth date is unknown. */
    public function ageLabel(): ?string
    {
        if (! $this->date_of_birth) {
            return null;
        }
        $years = (int) $this->date_of_birth->diffInYears(now());
        if ($years >= 1) {
            return $years.' '.str('year')->plural($years);
        }
        $months = max(0, (int) $this->date_of_birth->diffInMonths(now()));

        return $months.' '.str('month')->plural($months);
    }
}
