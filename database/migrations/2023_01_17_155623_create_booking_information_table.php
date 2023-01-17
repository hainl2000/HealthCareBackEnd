<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_information', function (Blueprint $table) {
            $table->id();
            $table->integer("shift_id");
            $table->string("name");
            $table->string("email");
            $table->string("booker_email")->nullable();
            $table->string("gender");
            $table->string("address");
            $table->longText("symptom");
            $table->longText("anamnesis");
            $table->longText("prev_information")->nullable();
            $table->longText("image")->nullable();
            $table->integer("status")->comment("0: not start, 1:end, 2:cancel");
            $table->longText("video_link")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_information');
    }
};
