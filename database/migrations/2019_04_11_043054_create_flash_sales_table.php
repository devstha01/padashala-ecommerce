<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlashSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('admin_id')->deafault(1);
            $table->integer('variant_id')->unsigned()->nullable();
            $table->decimal('marked_price', 16, 2)->nullable();
            $table->decimal('sell_price', 16, 2)->nullable();
            $table->decimal('discount', 16, 2)->nullable();
            $table->string('quantity')->nullable();
            $table->date('feature_from');
            $table->date('feature_till');
            $table->string('offer')->nullable();
            $table->boolean('flag')->default(0);
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
        Schema::dropIfExists('flash_sales');
    }
}
