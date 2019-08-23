<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxToOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('vat', 18, 6)->default(0);
            $table->decimal('tax', 18, 6)->default(0);
            $table->decimal('excise', 18, 6)->default(0);
            $table->decimal('net_tax', 18, 6)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('vat');
            $table->dropColumn('tax');
            $table->dropColumn('excise');
            $table->dropColumn('net_tax');
        });
    }
}
