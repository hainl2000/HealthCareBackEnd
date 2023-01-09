<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function toArray()
    {
        $attributes = $this->attributesToArray();
        $attributes = array_merge($attributes, $this->relationsToArray());
        unset($attributes['pivot']['updated_at']);
        return $attributes;
    }
}
