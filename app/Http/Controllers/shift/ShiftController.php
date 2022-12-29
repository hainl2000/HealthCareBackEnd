<?php

namespace App\Http\Controllers\shift;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Services\Shifts\ShiftServiceInterface;

class ShiftController extends ApiController
{
    private $shiftService;

    public function __construct(ShiftServiceInterface $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function getAllShifts(Request $request)
    {
        $allShifts = $this->shiftService->getAllShifts();
        return $this->respondSuccess($allShifts);
    }
}
