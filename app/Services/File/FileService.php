<?php

namespace App\Services\File;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileService implements FileServiceInterface
{
    public function uploadImage($path, $image)
    {
        $imageName = $this->getFileNameWithoutType($image) . "-" .Carbon::now()->getTimestampMs() . "." . $this->getFileType($image);
        try {
            return Storage::disk('s3')->putFileAs($path, $image, $imageName, 'public');
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

    public function getFileUrl($filePath)
    {
        return Storage::disk('s3')->url($filePath);
    }

    public function exportPrescriptionPdf($path, $data)
    {
        if (Storage::disk('s3')->exists($path)) {
            return true;
        }

        $pdf = Pdf::loadView('pdf/prescription', compact('data'), [], 'UTF-8');
        try {
            Storage::disk('s3')->put($path, $pdf->output(), 'public');
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
