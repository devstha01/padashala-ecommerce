<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommisionToProductCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('share_percentage', 10, 4)->default(0);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('share_percentage', 10, 4)->default(0);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('category_share', 10, 4)->default(0);
            $table->decimal('product_share', 10, 4)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('share_percentage');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('share_percentage');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('category_share');
            $table->dropColumn('product_share');
        });
    }
}
