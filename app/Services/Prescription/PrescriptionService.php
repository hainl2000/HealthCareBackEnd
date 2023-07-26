<?php

namespace App\Services\Prescription;

use App\Models\BookingInformation;
use App\Models\Prescription;
use App\Models\PrescriptionDrugs;
use Illuminate\Support\Arr;

class PrescriptionService implements PrescriptionServiceInterface
{
    public function createPrescription($bookingId, $prescriptionData)
    {
        try {
            $newPrescription =  Prescription::create([
                'booking_id' => $bookingId,
                'diagnose' => Arr::get($prescriptionData, 'diagnose'),
                'additional_direction' => Arr::get($prescriptionData, 'additional_direction'),
            ]);
            if (!$newPrescription) {
                throw new \Exception('create new prescription fail');
            }
            $insertPrescriptionDrugsDatas = $this->handlePrescriptionDrugsData($newPrescription->id , Arr::get($prescriptionData, 'prescriptionDrugs'));
            $isCreatedPrescriptionDrugs = PrescriptionDrugs::insert($insertPrescriptionDrugsDatas);

            if (!$isCreatedPrescriptionDrugs) {
                throw new \Exception('create list prescription drugs fail');
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    private function handlePrescriptionDrugsData($prescriptionId, $prescriptionDrugs)
    {
        $insertPrescriptionDrugsDatas = [];
        try {
            foreach ($prescriptionDrugs as $drug) {
                $drugData = [];
                $drugData['prescription_id'] = $prescriptionId;
                $drugData['drug_id'] = Arr::get($drug, 'is_other') ? null : $drug['id'];
                $drugData['other_drug_name'] = Arr::get($drug, 'is_other') ? Arr::get($drug, 'name') : null;
                $drugData['other_drug_unit'] = Arr::get($drug, 'is_other') ? Arr::get($drug, 'unit') : null;
                $drugData['dosages'] = $drug['dosages'];
                $drugData['number_per_time'] = 2;
                $drugData['times'] = json_encode($drug['times']);
                $drugData['meals'] = $drug['meals'];
                $drugData['note'] = Arr::get($drug, 'note') ?? "";
                $insertPrescriptionDrugsDatas[] = $drugData;
            }
            return $insertPrescriptionDrugsDatas;
        } catch (\Exception $e ) {
            dd($e->getMessage());
        }
    }

}
