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
            'register_code' => Arr::get($drugData, 'register_code'),
            'properties' => Arr::get($drugData, 'properties'),
            'unit' => Arr::get($drugData, 'unit'),
        ]);
    }

    public function getListDrugs($paginationParams)
    {
        $query = Drug::query();
        if (!empty($paginationParams['name'])) {
            $query = $query->whereLike('name', "%{$paginationParams['name']}%");
        }
        if ($paginationParams['itemsPerPage'] == PaginationParams::GetAllItems) {
            $records = $query->get();
        } else {
            $records = $query->paginate($paginationParams['itemsPerPage']);
        }
        return $records;
    }
}
