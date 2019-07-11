<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname');
            $table->string('name');
            $table->string('user_name');
            $table->string('email');
            $table->string('password');
            $table->string('transaction_password');
            $table->rememberToken();
            $table->enum('identification_type',['citizenship','passport']);
            $table->string('identification_number');
            $table->integer('country_id')->unsigned();
            $table->text('address');
            $table->string('contact_number');
            $table->date('dob');
            $table->string('gender');
            $table->enum('marital_status',['yes','no']);
            $table->date('joining_date');
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
        Schema::dropIfExists('merchants');
    }
}
