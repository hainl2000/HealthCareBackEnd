<?php

namespace App\Services\Specializations;

use App\Models\Specialization;
use Illuminate\Support\Arr;

class SpecializationService implements SpecializationServiceInterface
{
    public function __construct()
    {

    }

    public function getListSpecializations($isIncludeDetail = null)
    {
        if ($isIncludeDetail) {
            $listSpecialization = Specialization::all('id','name','image','slug','description');
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
        return Specialization::create([
            'name' => Arr::get($specializationData, 'name'),
            'description' => Arr::get($specializationData, 'description'),
            'image' => Arr::get($specializationData, 'image'),
            'slug' => $this->generateSlug(Arr::get($specializationData, 'name'))
        ]);
    }

    public function updateSpecialization($specializationData)
    {

    }

    private function generateSlug($str)
    {
        // Chuyển hết sang chữ thường
        $str = mb_strtolower($str, 'UTF-8');

        // Xóa dấu
        $str = preg_replace('/[\pM]/u', '', normalizer_normalize($str, Normalizer::FORM_D));

        // Thay ký tự đĐ
        $str = preg_replace('/[đĐ]/u', 'd', $str);

        // Xóa ký tự đặc biệt
        $str = preg_replace('/([^0-9a-z-\s])/u', '', $str);

        // Xóa khoảng trắng thay bằng ký tự -
        $str = preg_replace('/(\s+)/u', '-', $str);

        // Xóa ký tự - liên tiếp
        $str = preg_replace('/-+/u', '-', $str);

        // Xóa phần dư - ở đầu & cuối
        $str = trim($str, '-');

        // Return
        return $str;
    }
}
