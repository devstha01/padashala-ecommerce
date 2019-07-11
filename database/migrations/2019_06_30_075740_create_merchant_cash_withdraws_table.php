<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantCashWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_cash_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->dateTime('withdraw_date')->nullable();
            $table->string('contact_number')->nullable();
            $table->decimal('amount', 16, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('acc_name')->nullable();
            $table->string('acc_number')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('flag')->default(0);
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
        Schema::dropIfExists('merchant_cash_withdraws');
    }
}
