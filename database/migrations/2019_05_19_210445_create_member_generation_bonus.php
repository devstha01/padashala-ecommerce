<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberGenerationBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_generation_bonus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id');
            $table->string('generation');
            $table->string('bonus_value');
            $table->string('created_member_id');
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
        Schema::dropIfExists('member_generation_bonus');
    }
}
