<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "prescriptions";

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

    public $timestamps = true;

    public function prescriptionDrugs()
    {
        return $this->hasMany(PrescriptionDrugs::class, 'prescription_id', 'id');
    }
}
