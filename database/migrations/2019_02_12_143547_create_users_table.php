<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname')->nullable();
            $table->string('name');
            $table->string('user_name');
            $table->string('email');
            $table->string('password');
//            $table->string('transaction_password')->nullable();
            $table->rememberToken();
            $table->boolean('is_member')->default(0);
            $table->enum('identification_type',['citizenship','passport'])->nullable();
            $table->string('identification_number')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->enum('marital_status',['yes','no'])->nullable();
            $table->date('joining_date')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('users');
    }
}
