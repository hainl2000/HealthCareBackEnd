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
            $table->timestamps();
        });

        Schema::create('prescription_drugs', function (Blueprint $table) {
            $table->integer('prescription_id');
            $table->integer('drug_id')->nullable();
            $table->string('other_drug_name', 256)->nullable();
            $table->string('other_drug_unit')->nullable();
            $table->integer('dosages');
            $table->integer('number_per_time');
            $table->json('times');
            $table->integer('meals');
            $table->string('note', 512)->nullable();
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
        Schema::dropIfExists('prescription_drugs');
    }
};
