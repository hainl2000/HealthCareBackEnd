<?php

namespace App\Services\File;

interface FileServiceInterface
{
    public function uploadImage($path, $image);
    public function exportPrescriptionPdf($path, $data);
    public function getFileUrl($filePath);
}
