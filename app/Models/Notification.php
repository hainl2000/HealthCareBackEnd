<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'direct_id',
        'direct_object',
        'receiver_id',
        'receive_actor',
        'title',
        'description',
        'is_seen'
    ];
}
