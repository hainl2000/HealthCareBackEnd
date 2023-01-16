<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorShift extends Model
{
    use HasFactory;

    protected $table = "doctor_shift";

    public function doctor() {
        return $this->belongsTo(Doctor::class, "doctor_id", "id");
    }

    public function shift() {
        return $this->belongsTo(Shift::class, "shift_id", "id");
    }
}
