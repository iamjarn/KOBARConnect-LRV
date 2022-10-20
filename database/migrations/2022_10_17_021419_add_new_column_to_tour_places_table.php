<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToTourPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tour_places', function (Blueprint $table) {
            $table->integer("kid_prices")->default(0);
            $table->integer("adult_prices")->default(0);
            $table->integer("transport_prices")->default(0);
            $table->text("address")->nullable();
            $table->string("operational_days")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tour_places', function (Blueprint $table) {
            $table->dropColumn("kid_prices");
            $table->dropColumn("adult_prices");
            $table->dropColumn("transport_prices");
            $table->dropColumn("address");
            $table->dropColumn("operational_days");
        });
    }
}
