<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantWalletTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_wallet_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_merchant_id')->unsigned();
            $table->integer('to_member_id')->unsigned();
            $table->decimal('amount', 16, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->string('qr_token')->nullable();
            $table->integer('wallet_id')->unsigned();
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
        Schema::dropIfExists('merchant_wallet_transfers');
    }
}
