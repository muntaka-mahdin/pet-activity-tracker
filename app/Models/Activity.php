<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    // Define the fillable attributes
    protected $fillable = [
        'activity_type',
        'activity_date',
        'description',
    ];

    // Use $casts to make 'activity_date' a date
    protected $casts = [
        'activity_date' => 'date',
    ];
}
