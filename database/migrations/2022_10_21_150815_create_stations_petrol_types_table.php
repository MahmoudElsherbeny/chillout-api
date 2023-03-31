<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsPetrolTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations_petrol_types', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('petrol_type');
            $table->tinyInteger('storage_num');
            $table->integer('storage_capacity');
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
        Schema::dropIfExists('stations_petrol_types');
    }
}
