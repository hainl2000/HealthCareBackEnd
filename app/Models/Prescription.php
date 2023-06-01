<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'diagnose',
        'additional_direction',
        'drug_id',
        'dosages',
        'dosages',
        'number_per_time',
        'in_morning',
        'in_afternoon',
        'in_evening',
        'meals',
        'note',
        'is_other'
    ];
}
