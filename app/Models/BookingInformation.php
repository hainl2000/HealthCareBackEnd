<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingInformation extends Model
{
    use HasFactory;

    protected $table = "booking_information";

    protected $fillable = [
        "shift_id",
        "name",
        "email",
        "booker_email",
        "phone_number",
        "gender",
        "address",
        "symptom",
        "anamnesis",
        "prev_information",
        "image",
        "status",
        "video_link",
        "created_by",
        "rating",
        "comment",
        "patient_finish",
        "doctor_finish"
    ];

    public function prescription()
    {
        return $this->hasOne(Prescription::class, 'booking_id', 'id');
    }
}
