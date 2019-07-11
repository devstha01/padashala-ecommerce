<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTokenToMemberWalletTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_wallet_transfers', function (Blueprint $table) {
            $table->string('qr_token')->nullable();
            $table->integer('wallet_id')->unsigned();
            $table->boolean('flag')->default(0);
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_wallet_transfers', function (Blueprint $table) {
            $table->dropColumn('qr_token');
            $table->dropColumn('wallet_id');
            $table->dropColumn('flag');
            $table->dropColumn('status');
        });
    }
}
