<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberNomineeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_nominee_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nominee_name')->nullable();
            $table->integer('member_id')->nullable();
            $table->enum('identification_type',['citizenship','passport'])->nullable();;
            $table->string('identification_number')->nullable();;
            $table->string('contact_number')->nullable();;
            $table->string('relationship')->nullable();;
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
        Schema::dropIfExists('member_nominee_details');
    }
}
