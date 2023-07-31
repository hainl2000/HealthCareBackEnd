<?php

namespace App\Services\Specializations;

use App\Enums\PaginationParams;
use App\Models\Specialization;
use App\Services\File\FileServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class SpecializationService implements SpecializationServiceInterface
{
    private $fileService;
    public function __construct(FileServiceInterface $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getListSpecializations($paginationParams, $isIncludeDetail = null)
    {
        if ($isIncludeDetail) {
            $query = Specialization::query()->select([
                'id','name','image','slug','description'
            ]);
            if (!empty($paginationParams['name'])) {
                $query = $query->whereLike('name', "%{$paginationParams['name']}%");
            }
            if ($paginationParams['itemsPerPage'] == PaginationParams::GetAllItems) {
                $listSpecialization = $query->get();
            } else {
                $listSpecialization = $query->paginate($paginationParams['itemsPerPage']);
            }
        } else {
            $listSpecialization = Specialization::all('id','name','image','slug');
        }
        return $listSpecialization;
    }

    public function getSpecializationDetail($slug)
    {
        $specializationDetail = Specialization::select('id','name','description','image','slug')
                                    ->where('slug', $slug)
                                    ->first();
        $specializationDetail->description = htmlspecialchars_decode($specializationDetail->description);
        return $specializationDetail;
    }

    public function createSpecialization($specializationData)
    {
        $uploadPath = Config::get("constants.UPLOAD_FOLDER.SPECIALIZATION");
        $imageLink = $this->fileService->uploadImage($uploadPath, Arr::get($specializationData, 'image'));
        return Specialization::create([
            'name' => Arr::get($specializationData, 'name'),
            'description' => Arr::get($specializationData, 'description'),
            'image' => $imageLink,
            'slug' => $this->generateSlug(Arr::get($specializationData, 'name'))
        ]);
    }

    public function updateSpecialization($specializationData)
    {
        $uploadPath = Config::get("constants.UPLOAD_FOLDER.SPECIALIZATION");
        $imageLink = $this->fileService->uploadImage($uploadPath, Arr::get($specializationData, 'image'));
        return Specialization::where('id', Arr::get($specializationData, 'id'))->update([
            'description' => Arr::get($specializationData, 'description'),
            'image' => $imageLink
        ]);
    }

    private function generateSlug($str)
    {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $str);
        $string = preg_replace('/(-)+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }
}
