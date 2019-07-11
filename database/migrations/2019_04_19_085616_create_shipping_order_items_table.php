<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice');
            $table->string('tracking_id')->nullable();
            $table->string('carrier')->nullable();
            $table->string('weight')->nullable();
            $table->string('url')->nullable();
            $table->boolean('notify')->default(0);
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
        Schema::dropIfExists('shipping_order_items');
    }
}
