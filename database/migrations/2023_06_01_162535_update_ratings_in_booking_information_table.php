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
        Schema::table('booking_information', function (Blueprint $table) {
            $table->float('rating')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('patient_finish')->nullable()->default(false);
            $table->boolean('doctor_finish')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_information', function (Blueprint $table) {
            $table->dropColumn('rate');
            $table->dropColumn('comment');
            $table->dropColumn('patient_finish');
            $table->dropColumn('doctor_finish');
        });
    }
};
