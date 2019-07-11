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
            $table->integer('merchant_id')->unsigned();
            $table->integer('admin_id')->unsigned()->nullable();
            $table->decimal('total', 16, 4);
            $table->decimal('merchant', 16, 4)->nullable();
            $table->decimal('shopping_bonus', 16, 4)->nullable();
            $table->decimal('administration', 16, 4)->nullable();
            $table->decimal('bonus', 16, 4)->nullable();
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
