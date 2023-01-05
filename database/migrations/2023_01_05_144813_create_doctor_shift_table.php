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
        Schema::create('doctor_shift', function (Blueprint $table) {
            $table->id();
            $table->integer("doctor_id");
            $table->integer("shift_id");
            $table->date("date");
            $table->integer("status")->comment("0: not register, 1:no patient, 2:had patient");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_shift');
    }
};
