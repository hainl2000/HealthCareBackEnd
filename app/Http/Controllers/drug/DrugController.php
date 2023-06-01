<?php

namespace App\Http\Controllers\drug;

use App\Enums\PaginationParams;
use App\Http\Controllers\ApiController;
use App\Services\Drug\DrugServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class DrugController extends ApiController
{
    public $drugService;

    public function __construct(
        DrugServiceInterface $drugService
    )
    {
        $this->drugService = $drugService;
    }

    public function getListDrugs(Request $request)
    {
        $paginationParams = [];
        $paginationParams['itemsPerPage'] = $request->input('itemsPerPage', PaginationParams::RecordsPerPage);
        $paginationParams['currentPage'] = $request->input('currentPage', PaginationParams::FirstPage);
        try {
            $records = $this->drugService->getListDrugs($paginationParams);
            $resp = $this->respond($records);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $resp;
    }
}
