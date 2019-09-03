<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPanVatToMerchantBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_business', function (Blueprint $table) {
            $table->string('pan')->nullable();
            $table->string('vat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_business', function (Blueprint $table) {
            $table->dropColumn('pan');
            $table->dropColumn('vat');
        });
    }
}
