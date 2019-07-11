<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddingHistroyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bidding_histroy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bidding_id');
            $table->integer('user_id');
            $table->integer('bidding_chips');
            $table->boolean('bidding_winner')->default(0);
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
        Schema::dropIfExists('bidding_histroy');
    }
}
