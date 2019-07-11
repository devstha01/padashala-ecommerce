<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantBankinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_bankinfos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->unsigned();
            $table->string('bank_name');
            $table->string('acc_name');
            $table->string('acc_number');
            $table->string('contact_number');
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
        Schema::dropIfExists('merchant_bankinfos');
    }
}
