<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCenterCoordToUtg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblUnitaGestione', function (Blueprint $table) {
            $table->string('center_lat')->default('44.060921')->nullable();
            $table->string('center_long')->default('12.566300')->nullable();
            $table->integer('zoom')->default(13);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblUnitaGestione', function (Blueprint $table) {
            $table->dropColumn(['center_lat', 'center_long', 'zoom']);
        });
    }
}
