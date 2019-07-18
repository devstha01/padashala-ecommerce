<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_item_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('merchant_id')->unsigned();
            $table->decimal('total',16,4)->default(0);
            $table->decimal('merchant_amount',16,4)->default(0);
            $table->decimal('admin_amount',16,4)->default(0);
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('shopping_logs');
    }
}
