<?php

namespace App\Services\File;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class FileService implements FileServiceInterface
{
    public function uploadImage($path, $image)
    {
        $imageName = $this->getFileNameWithoutType($image) . "-" .Carbon::now()->getTimestampMs() . "." . $this->getFileType($image);
        try {
            return Storage::putFileAs($path, $image, $imageName);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function getFileNameWithoutType($file)
    {
        return str_replace(' ', '', pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME));
    }

    private function getFileType($file)
    {
        return pathinfo($file->getClientOriginalName(),PATHINFO_EXTENSION);
    }

    public function exportPrescriptionPdf($path, $data)
    {
        if (Storage::exists($path)) {
            return true;
        }
        $pdf = Pdf::loadView('pdf/prescription', $data, [], 'UTF-8');
        try {
            Storage::put($path, $pdf->output());
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
