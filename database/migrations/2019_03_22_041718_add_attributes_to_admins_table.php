<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributesToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('surname')->nullable();
            $table->string('transaction_password')->nullable();
            $table->enum('identification_type',['citizenship','passport'])->nullable();
            $table->string('identification_number')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->enum('marital_status',['yes','no'])->nullable();
            $table->date('joining_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('surname');
            $table->dropColumn('transaction_password');
            $table->dropColumn('identification_type');
            $table->dropColumn('identification_number');
            $table->dropColumn('country_id');
            $table->dropColumn('address');
            $table->dropColumn('contact_number');
            $table->dropColumn('dob');
            $table->dropColumn('gender');
            $table->dropcolumn('marital_status');
            $table->dropColumn('joining_date');
        });
    }
}
