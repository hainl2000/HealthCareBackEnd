<?php

namespace Database\Seeders;

use App\Models\Drug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Composer\Autoload\includeFile;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

//        DB::table('specializations')->insert([
//            [
//                'name'=>'Vật lý trị liệu',
//                'description' => htmlspecialchars('<p><b>Chuyên gia tư vấn Tâm lý giỏi&nbsp;</b></p><ul><li>BookingCare là Nền tảng Y tế chăm sóc Sức khỏe toàn diện, trong đó có cung cấp dịch vụ tư vấn tâm lý từ xa.</li><li>Chuyên gia được đào tạo bài bản về chuyên ngành Tâm lý tại các trường đại học trong nước và quốc tế.</li><li>Nhà Tâm lý học là những người có nhiều kinh nghiệm trong lĩnh vực tâm lý, chăm sóc sức khỏe tinh thần.</li><li>Các nhà chuyên môn nghiên cứu, tư vấn và trị liệu theo các phương pháp tiếp cận mới, hiệu quả.</li><li>Lắng nghe và thấu hiểu khách hàng để giúp họ vượt qua khó khăn của bản thân.</li></ul><p><b>Tư vấn và trị liệu</b></p><ul><li>Khó khăn, rối nhiễu tâm lý</li><li>Phát triển cá nhân</li><li>Mâu thuẫn, lạm dụng và tổn thương tâm lý</li><li>Tái hòa nhập xã hội</li><li>Vấn đề khuyết tật và nhóm yếu thế</li><li>Những vấn đề của vị thành niên</li><li>Giới tính và tình dục</li><li>Những vấn đề trong mối quan hệ gia đình</li></ul><p>Ngoài những vấn đề nêu trên, khách hàng có thể liên hệ với chúng tôi để được hỗ trợ, sắp xếp lịch tư vấn phù hợp</p><br>'),
//                'image' => "https://cdn.bookingcare.vn/fo/2020/12/20/111237-tam-ly-2.jpg",
//                'slug' => 'vatlytrilieu',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'name'=>'Tâm lý',
//                'description'=> htmlspecialchars('<p><b>Chuyên gia tư vấn Tâm lý giỏi&nbsp;</b></p><ul><li>BookingCare là Nền tảng Y tế chăm sóc Sức khỏe toàn diện, trong đó có cung cấp dịch vụ tư vấn tâm lý từ xa.</li><li>Chuyên gia được đào tạo bài bản về chuyên ngành Tâm lý tại các trường đại học trong nước và quốc tế.</li><li>Nhà Tâm lý học là những người có nhiều kinh nghiệm trong lĩnh vực tâm lý, chăm sóc sức khỏe tinh thần.</li><li>Các nhà chuyên môn nghiên cứu, tư vấn và trị liệu theo các phương pháp tiếp cận mới, hiệu quả.</li><li>Lắng nghe và thấu hiểu khách hàng để giúp họ vượt qua khó khăn của bản thân.</li></ul><p><b>Tư vấn và trị liệu</b></p><ul><li>Khó khăn, rối nhiễu tâm lý</li><li>Phát triển cá nhân</li><li>Mâu thuẫn, lạm dụng và tổn thương tâm lý</li><li>Tái hòa nhập xã hội</li><li>Vấn đề khuyết tật và nhóm yếu thế</li><li>Những vấn đề của vị thành niên</li><li>Giới tính và tình dục</li><li>Những vấn đề trong mối quan hệ gia đình</li></ul><p>Ngoài những vấn đề nêu trên, khách hàng có thể liên hệ với chúng tôi để được hỗ trợ, sắp xếp lịch tư vấn phù hợp</p><br>'),
//                'image' => "https://cdn.bookingcare.vn/fo/2020/12/20/111237-tam-ly-2.jpg",
//                'slug' => 'tamly',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'name'=>'Sức khỏe tâm thần',
//                'description' => htmlspecialchars('<p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><span style="font-family: Arial, sans-serif;"><b>Tư vấn, khám chữa bệnh từ xa</b></span></p><p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><span style="font-family: Arial, sans-serif;">Để đáp ứng nhu cầu chăm sóc sức khỏe Hậu COVID-19, BookingCare triển khai dịch vụ tư vấn khám chữa bệnh từ xa thông qua cuộc gọi Video.</span><br></p><p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><b><span style="font-family: Arial, sans-serif;">Bác sĩ chuyên khoa Sức khỏe tâm thần từ xa</span></b></p><ul type="disc" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Chăm sóc sức khỏe ban đầu</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Chẩn đoán định hướng bệnh</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Định hướng phương pháp điều trị</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Tư vấn sử dụng thuốc</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Tư vấn xét nghiệm, chụp chiếu</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Người gặp các bệnh mãn tính</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Bệnh nhân cũ cần tái khám với bác sĩ từ xa</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li></ul><p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><b><span style="font-family: Arial, sans-serif;">Các bệnh chuyên khoa Tâm thần (Tâm bệnh)</span></b></p><ul><li><font color="#333333" face="Arial, sans-serif">Rối loạn lo âu, hoa mắt chóng mặt</font></li><li><font color="#333333" face="Arial, sans-serif">Rối loạn tiền đình;&nbsp;</font>Rối loạn thần kinh thực vật</li><li><font color="#333333" face="Arial, sans-serif">Trầm cảm</font></li><li><font color="#333333" face="Arial, sans-serif">Mất ngủ, khó ngủ, khó duy trì giấc ngủ, dậy quá sớm, ngủ dậy vẫn thấy mệt, tỉnh dậy nhiều lần trong giấc ngủ.</font></li><li><font color="#333333" face="Arial, sans-serif">Đau đầu, đau lưng, đau mỏi vai gáy, mệt mỏi mạn tính, mất ngủ, bồn chồn, dễ cáu kỉnh, tự đánh giá thấp bản thân</font></li><li><span style="color: rgb(51, 51, 51); font-family: Arial, sans-serif;">Căng thẳng tâm lý, suy nghĩ tiêu cực, bi quan và tuyệt vọng kéo dài.</span><br></li><li><font color="#333333" face="Arial, sans-serif">Rối loạn lưỡng cực cảm xúc</font></li><li><font color="#333333" face="Arial, sans-serif">Tâm thần phân liệt, có thể kích động, khả năng học tập lao động giảm dần, ngày càng thờ ơ, vô cảm.</font></li><li><span style="color: rgb(51, 51, 51); font-family: Arial, sans-serif;">Rối loạn tăng động giảm chú ý (ADHD)</span><br></li><li><font color="#333333" face="Arial, sans-serif">Nghiện game,&nbsp;</font><font color="#333333" face="Arial, sans-serif">Nghiện rượu, thuốc lá,&nbsp;</font>Nghiện ma túy tổng hợp...</li></ul><p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><b><span style="font-family: Arial, sans-serif;">Lợi ích khám tư vấn từ xa</span></b></p><p style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"></p><ul type="disc" style="margin-top: 0in; color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Qui trình, thao tác đơn giản, nhanh gọn<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Đội ngũ bác sĩ chuyên khoa giàu kinh nghiệm và trách nhiệm cao<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Khám, tư vấn và điều trị hiệu quả chuyên sâu<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Kết nối mạng lưới nhiều bệnh viện, phòng khám chuyên khoa sâu, rộng<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Tiện lợi, nhanh chóng, bệnh nhân tại nhà gặp bác sĩ từ xa.<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">An toàn mùa Covid-19 cho bệnh nhân và gia đình<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Tiết kiệm chi phí, giảm tàu xe, ăn ở, thời gian chờ đợi.</span></li></ul>'),
//                'image' => 'https://cdn.bookingcare.vn/fo/2020/12/09/100650-doctor-57101521920.jpg',
//                'slug' => 'suckhoetamthan',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//        ]);

//        DB::table('shifts')->insert([
//            [
//                'start_time' => '08:00:00',
//                'end_time' => '08:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '08:30:00',
//                'end_time' => '08:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '09:00:00',
//                'end_time' => '09:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '09:30:00',
//                'end_time' => '09:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '10:00:00',
//                'end_time' => '10:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '10:30:00',
//                'end_time' => '10:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '11:00:00',
//                'end_time' => '11:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '11:30:00',
//                'end_time' => '11:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '13:00:00',
//                'end_time' => '13:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '13:30:00',
//                'end_time' => '13:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '14:00:00',
//                'end_time' => '14:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '14:30:00',
//                'end_time' => '14:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '15:00:00',
//                'end_time' => '15:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '15:30:00',
//                'end_time' => '15:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '16:00:00',
//                'end_time' => '16:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '16:30:00',
//                'end_time' => '16:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],            [
//                'start_time' => '17:00:00',
//                'end_time' => '17:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '17:30:00',
//                'end_time' => '17:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '18:00:00',
//                'end_time' => '18:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '18:30:00',
//                'end_time' => '18:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '19:00:00',
//                'end_time' => '19:25:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'start_time' => '19:30:00',
//                'end_time' => '19:55:00',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//        ]);

//        DB::table('doctor_information')->insert([
//            [
//                'doctor_id' => 1,
//                'short_introduction' => htmlspecialchars('Bác sĩ đang công tác Khoa Thần kinh, Bệnh viện Quân y 103<br>Bác sĩ từng công tác tại Khoa Thần kinh Tâm thần, Khoa Nội Tiêu hóa và Bệnh máu, Bệnh viện Quân y 5<br>Bác sĩ nhận tư vấn bệnh nhân từ 12 tuổi trở lên'),
//                'introduction' => htmlspecialchars('<li><span lang="VI">Phó giáo sư, Tiến sĩ, Bác sĩ cao cấp chuyên khoa Da liễu</span></li><li><span lang="VI">Bác sĩ từng công tác tại Bệnh viện Da liễu Trung ương</span></li><li><span lang="VI">Nguyên Trưởng phòng chỉ đạo tuyến tại Bệnh viện Da liễu Trung ương</span></li><li><span lang="VI">Đạt chứng chỉ Diploma về Da liễu tại Viện da liễu Băng Cốc - Thái Lan</span></li>'),
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now(),
//            ]
//        ]);

//        DB::table('admins')->insert([
//            [
//                'name' => 'Admin 1',
//                'email' => 'admin1@gmail.com',
//                'password' => Hash::make('123456'),
//                'image' => 'data:image/png;base64,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',
//            ]
//        ]);
        $drugs = include __DIR__.'/../seeders/drug_seed.php';
        $array = json_decode($drugs, true);

        $listDrugs = array_map(function($item) {
            return $item;
        }, $array);

        foreach ($listDrugs as $drug) {
            Drug::create([
                'name' => $drug['drugName'],
                'register_code' => $drug['registerCode'],
                'properties' => empty($drug['drugProperties']) ? $drug['drugPropertyNames'] : $drug['drugProperties'],
                'unit' => 'N/A'
            ]);
        }

    }
}

