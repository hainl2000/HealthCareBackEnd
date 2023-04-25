<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'short_introduction',
        'introduction'
    ];

    protected $hidden = [
        'id',
        'doctor_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
