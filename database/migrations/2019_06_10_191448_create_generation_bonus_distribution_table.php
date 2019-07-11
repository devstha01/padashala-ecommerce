<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenerationBonusDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generation_bonus_distribution', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('ecash_percentage', 16, 2)->default(90);
            $table->decimal('evoucher_percentage', 16, 2)->default(0);
            $table->decimal('chip_percentage', 16, 2)->default(0);
            $table->decimal('rpoint_percentage', 16, 2)->default(0);
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
        Schema::dropIfExists('generation_bonus_distribution');
    }
}
