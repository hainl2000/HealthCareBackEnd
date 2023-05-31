<?php

namespace App\Services\Drug;

use App\Enums\PaginationParams;
use App\Models\Drug;
use Illuminate\Support\Arr;

class DrugService implements DrugServiceInterface
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

    public function getListDrugs($paginationParams)
    {
        if ($paginationParams['itemsPerPage'] == PaginationParams::GetAllItems) {
            $records = Drug::all();
        } else {
            $records = Drug::paginate($paginationParams['itemsPerPage']);
        }
        return $records;
    }
}
