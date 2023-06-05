<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'doctor';

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'type',
        'specialization_id',
        'image',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'updated_at',
        'deleted_at'
    ];

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'doctor_shift', 'doctor_id', 'shift_id')
            ->withPivot('id', 'date', 'status')->withTimestamps();
    }

    public function specializations()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id', 'id');
    }

    public function doctor_information()
    {
        return $this->hasOne(DoctorInformation::class, 'doctor_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }
}
