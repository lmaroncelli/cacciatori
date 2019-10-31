<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitaZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblUnitaZone', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unita_id')->unsigned();
            $table->integer('zona_id')->unsigned();
            $table->index('unita_id');
            $table->index('zona_id');
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
        Schema::dropIfExists('tblUnitaZone');
    }
}
