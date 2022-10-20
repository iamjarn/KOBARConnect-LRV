<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string("invoice_number");
            $table->date("visit_date");
            $table->string("name");
            $table->string("identity_number");
            $table->string("email");
            $table->string("phone_number");
            $table->string("address")->nullable();
            $table->integer("adult_prices")->default(0);
            $table->integer("adult_quantity")->default(0);
            $table->integer("adult_total_prices")->default(0);
            $table->integer("kid_prices")->default(0);
            $table->integer("kid_quantity")->default(0);
            $table->integer("kid_total_prices")->default(0);
            $table->integer("transport_prices")->default(0);
            $table->integer("total_prices");
            $table->string("bank")->nullable();
            $table->string("virtual_account_number")->nullable();
            $table->datetime("payment_at")->nullable();
            $table->datetime("expired_at")->nullable();
            $table->enum("status", ["PENDING", "WAITING_PAYMENT", "PAID", "EXPIRED", "CANCELED", "REJECTED"]);
            $table->foreignId('id_tour_place');
            $table->text("payment_url")->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
