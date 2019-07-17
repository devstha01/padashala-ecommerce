<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->decimal('ecash_wallet', 16, 5)->default(0);
//            $table->decimal('evoucher_wallet', 16, 4)->default(0);
//            $table->decimal('r_point', 16, 4)->default(0);
//            $table->integer('chip')->unsigned();
//            $table->decimal('capital_amount', 16, 4)->default(0);
//            $table->decimal('dividend', 16, 4)->default(0);
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
        Schema::dropIfExists('member_assets');
    }
}
