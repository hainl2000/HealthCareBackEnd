<?php

namespace App\Services\Drug;

use App\Models\Drug;
use Illuminate\Support\Arr;

class DrugService
{
    public function createDrug($drugData)
    {
        return Drug::create([
            'name' => Arr::get($drugData, 'name'),
            'register_code' => Arr::get($drugData, 'registerCode'),
            'properties' => Arr::get($drugData, 'properties'),
            'unit' => Arr::get($drugData, 'unit'),
        ]);
    }
}
