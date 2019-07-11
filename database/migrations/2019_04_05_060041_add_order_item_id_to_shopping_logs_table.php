<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderItemIdToShoppingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopping_logs', function (Blueprint $table) {
            $table->integer('order_item_id')->unsigned();
//            $table->string('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shopping_logs', function (Blueprint $table) {
            $table->dropColumn('order_item_id');
//            $table->dropColumn('payment_method');
        });
    }
}
