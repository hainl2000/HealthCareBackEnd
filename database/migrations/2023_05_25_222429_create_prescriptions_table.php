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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id');
            $table->string('diagnose', 512);
            $table->string('additional_direction', 512)->nullable();
            $table->integer('drug_id');
            $table->integer('dosages');
            $table->integer('number_per_time');
            $table->boolean('in_morning')->nullable();
            $table->boolean('in_afternoon')->nullable();
            $table->boolean('in_evening')->nullable();
            $table->integer('meals');
            $table->string('note', 512)->nullable();
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
        Schema::dropIfExists('prescriptions');
    }
};
