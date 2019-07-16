<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer('sub_category_id')->unsigned()->nullable();
            $table->integer('sub_child_category_id')->unsigned()->nullable();
            $table->integer('merchant_business_id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->text('detail')->nullable();
            $table->string('featured_image')->nullable();
            $table->decimal('marked_price', 16, 2)->default(0);
            $table->decimal('sell_price', 16, 2)->default(0);
            $table->decimal('discount', 16, 2)->default(0);
            $table->string('quantity')->default(0);

//            admin controlled
            $table->boolean('admin_flag')->default(0);
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
        Schema::dropIfExists('products');
    }
}
