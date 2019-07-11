<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWithdrawDateToCashWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_cash_withdraws', function (Blueprint $table) {
            $table->integer('updated_by')->unsigned()->default(1);
            $table->dateTime('withdraw_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_cash_withdraws', function (Blueprint $table) {
            $table->dropColumn('updated_by');
            $table->dropColumn('withdraw_date');
        });
    }
}
