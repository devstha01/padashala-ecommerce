<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('vat', 16, 2)->default(0);
            $table->decimal('tax', 16, 2)->default(0);
            $table->decimal('excise', 16, 2)->default(0);
            $table->boolean('standard_product')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('vat');
            $table->dropColumn('tax');
            $table->dropColumn('excise');
            $table->dropColumn('standard_product');
        });
    }
}
