<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChineseNameToSubChildCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_child_categories', function (Blueprint $table) {
            $table->string('ch_name')->nullable();
            $table->string('trch_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_child_categories', function (Blueprint $table) {
            $table->dropColumn('ch_name');
            $table->dropColumn('trch_name');
        });
    }
}
