<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('specializations')->insert([
            [
                'name'=>'Vật lý trị liệu',
                'description' => htmlspecialchars('<p><b>Chuyên gia tư vấn Tâm lý giỏi&nbsp;</b></p><ul><li>BookingCare là Nền tảng Y tế chăm sóc Sức khỏe toàn diện, trong đó có cung cấp dịch vụ tư vấn tâm lý từ xa.</li><li>Chuyên gia được đào tạo bài bản về chuyên ngành Tâm lý tại các trường đại học trong nước và quốc tế.</li><li>Nhà Tâm lý học là những người có nhiều kinh nghiệm trong lĩnh vực tâm lý, chăm sóc sức khỏe tinh thần.</li><li>Các nhà chuyên môn nghiên cứu, tư vấn và trị liệu theo các phương pháp tiếp cận mới, hiệu quả.</li><li>Lắng nghe và thấu hiểu khách hàng để giúp họ vượt qua khó khăn của bản thân.</li></ul><p><b>Tư vấn và trị liệu</b></p><ul><li>Khó khăn, rối nhiễu tâm lý</li><li>Phát triển cá nhân</li><li>Mâu thuẫn, lạm dụng và tổn thương tâm lý</li><li>Tái hòa nhập xã hội</li><li>Vấn đề khuyết tật và nhóm yếu thế</li><li>Những vấn đề của vị thành niên</li><li>Giới tính và tình dục</li><li>Những vấn đề trong mối quan hệ gia đình</li></ul><p>Ngoài những vấn đề nêu trên, khách hàng có thể liên hệ với chúng tôi để được hỗ trợ, sắp xếp lịch tư vấn phù hợp</p><br>'),
                'image' => "https://cdn.bookingcare.vn/fo/2020/12/20/111237-tam-ly-2.jpg",
                'slug' => 'vatlytrilieu',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name'=>'Tâm lý',
                'description'=> htmlspecialchars('<p><b>Chuyên gia tư vấn Tâm lý giỏi&nbsp;</b></p><ul><li>BookingCare là Nền tảng Y tế chăm sóc Sức khỏe toàn diện, trong đó có cung cấp dịch vụ tư vấn tâm lý từ xa.</li><li>Chuyên gia được đào tạo bài bản về chuyên ngành Tâm lý tại các trường đại học trong nước và quốc tế.</li><li>Nhà Tâm lý học là những người có nhiều kinh nghiệm trong lĩnh vực tâm lý, chăm sóc sức khỏe tinh thần.</li><li>Các nhà chuyên môn nghiên cứu, tư vấn và trị liệu theo các phương pháp tiếp cận mới, hiệu quả.</li><li>Lắng nghe và thấu hiểu khách hàng để giúp họ vượt qua khó khăn của bản thân.</li></ul><p><b>Tư vấn và trị liệu</b></p><ul><li>Khó khăn, rối nhiễu tâm lý</li><li>Phát triển cá nhân</li><li>Mâu thuẫn, lạm dụng và tổn thương tâm lý</li><li>Tái hòa nhập xã hội</li><li>Vấn đề khuyết tật và nhóm yếu thế</li><li>Những vấn đề của vị thành niên</li><li>Giới tính và tình dục</li><li>Những vấn đề trong mối quan hệ gia đình</li></ul><p>Ngoài những vấn đề nêu trên, khách hàng có thể liên hệ với chúng tôi để được hỗ trợ, sắp xếp lịch tư vấn phù hợp</p><br>'),
                'image' => "https://cdn.bookingcare.vn/fo/2020/12/20/111237-tam-ly-2.jpg",
                'slug' => 'tamly',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()],
            [
                'name'=>'Sức khỏe tâm thần',
                'description' => htmlspecialchars('<p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><span style="font-family: Arial, sans-serif;"><b>Tư vấn, khám chữa bệnh từ xa</b></span></p><p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><span style="font-family: Arial, sans-serif;">Để đáp ứng nhu cầu chăm sóc sức khỏe Hậu COVID-19, BookingCare triển khai dịch vụ tư vấn khám chữa bệnh từ xa thông qua cuộc gọi Video.</span><br></p><p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><b><span style="font-family: Arial, sans-serif;">Bác sĩ chuyên khoa Sức khỏe tâm thần từ xa</span></b></p><ul type="disc" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Chăm sóc sức khỏe ban đầu</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Chẩn đoán định hướng bệnh</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Định hướng phương pháp điều trị</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Tư vấn sử dụng thuốc</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Tư vấn xét nghiệm, chụp chiếu</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Người gặp các bệnh mãn tính</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li><li class="MsoNormal" style="line-height: normal;"><span style="font-size: 10.5pt; font-family: Arial, sans-serif;">Bệnh nhân cũ cần tái khám với bác sĩ từ xa</span><span style="font-size: 10.5pt; font-family: Helvetica, sans-serif;"><o:p></o:p></span></li></ul><p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><b><span style="font-family: Arial, sans-serif;">Các bệnh chuyên khoa Tâm thần (Tâm bệnh)</span></b></p><ul><li><font color="#333333" face="Arial, sans-serif">Rối loạn lo âu, hoa mắt chóng mặt</font></li><li><font color="#333333" face="Arial, sans-serif">Rối loạn tiền đình;&nbsp;</font>Rối loạn thần kinh thực vật</li><li><font color="#333333" face="Arial, sans-serif">Trầm cảm</font></li><li><font color="#333333" face="Arial, sans-serif">Mất ngủ, khó ngủ, khó duy trì giấc ngủ, dậy quá sớm, ngủ dậy vẫn thấy mệt, tỉnh dậy nhiều lần trong giấc ngủ.</font></li><li><font color="#333333" face="Arial, sans-serif">Đau đầu, đau lưng, đau mỏi vai gáy, mệt mỏi mạn tính, mất ngủ, bồn chồn, dễ cáu kỉnh, tự đánh giá thấp bản thân</font></li><li><span style="color: rgb(51, 51, 51); font-family: Arial, sans-serif;">Căng thẳng tâm lý, suy nghĩ tiêu cực, bi quan và tuyệt vọng kéo dài.</span><br></li><li><font color="#333333" face="Arial, sans-serif">Rối loạn lưỡng cực cảm xúc</font></li><li><font color="#333333" face="Arial, sans-serif">Tâm thần phân liệt, có thể kích động, khả năng học tập lao động giảm dần, ngày càng thờ ơ, vô cảm.</font></li><li><span style="color: rgb(51, 51, 51); font-family: Arial, sans-serif;">Rối loạn tăng động giảm chú ý (ADHD)</span><br></li><li><font color="#333333" face="Arial, sans-serif">Nghiện game,&nbsp;</font><font color="#333333" face="Arial, sans-serif">Nghiện rượu, thuốc lá,&nbsp;</font>Nghiện ma túy tổng hợp...</li></ul><p class="MsoNormal" style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><b><span style="font-family: Arial, sans-serif;">Lợi ích khám tư vấn từ xa</span></b></p><p style="color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"></p><ul type="disc" style="margin-top: 0in; color: rgb(51, 51, 51); font-family: Montserrat, sans-serif;"><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Qui trình, thao tác đơn giản, nhanh gọn<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Đội ngũ bác sĩ chuyên khoa giàu kinh nghiệm và trách nhiệm cao<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Khám, tư vấn và điều trị hiệu quả chuyên sâu<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Kết nối mạng lưới nhiều bệnh viện, phòng khám chuyên khoa sâu, rộng<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Tiện lợi, nhanh chóng, bệnh nhân tại nhà gặp bác sĩ từ xa.<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">An toàn mùa Covid-19 cho bệnh nhân và gia đình<o:p></o:p></span></li><li class="MsoNormal"><span style="font-family: Arial, sans-serif;">Tiết kiệm chi phí, giảm tàu xe, ăn ở, thời gian chờ đợi.</span></li></ul>'),
                'image' => 'https://cdn.bookingcare.vn/fo/2020/12/09/100650-doctor-57101521920.jpg',
                'slug' => 'suckhoetamthan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}

