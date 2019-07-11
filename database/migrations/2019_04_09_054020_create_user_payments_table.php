<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_member_id')->unsigned();
            $table->integer('to_member_id')->unsigned()->nullable();
            $table->integer('to_merchant_id')->unsigned()->nullable();
            $table->decimal('amount', 16, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->string('qr_token')->nullable();

//            defaut 1 for ecash_wallet
            $table->integer('wallet_id')->unsigned()->default(1);
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
        Schema::dropIfExists('user_payments');
    }
}
