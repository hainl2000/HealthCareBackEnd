<?php

namespace App\Services\Drug;

interface DrugServiceInterface
{
    public function createDrug($drugData);
    public function getListDrugs($paginationParams);
}
