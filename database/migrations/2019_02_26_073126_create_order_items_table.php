<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->integer('product_variant_id')->nullable();
            $table->integer('quantity');
            $table->date('deliver_date')->nullable();
            $table->decimal('marked_price', 16, 2)->nullable();
            $table->decimal('sell_price', 16, 2)->nullable();
            $table->decimal('discount', 16, 2)->nullable();
            $table->string('order_status_id')->default('process');
            $table->string('invoice');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('order_items');
    }
}
