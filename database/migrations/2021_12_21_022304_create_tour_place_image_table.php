<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourPlaceImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_place_image', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->foreignId('id_tour_place');
            $table->datetime('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('id_tour_place')->references('id')->on('tour_places');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_place_image');
    }
}
