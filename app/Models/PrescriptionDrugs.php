<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDrugs extends Model
{
    use HasFactory;

    protected $table = 'prescription_drugs';

    protected $casts = [
        'times' => 'array',
    ];

    public $timestamps = false;

    public function drug()
    {
        return $this->hasOne(Drug::class, 'id', 'drug_id');
    }

}
