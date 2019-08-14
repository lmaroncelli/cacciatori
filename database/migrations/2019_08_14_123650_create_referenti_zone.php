<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferentiZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblReferentiZone', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('referente_id')->unsigned();
            $table->integer('zona_id')->unsigned();
            $table->index('referente_id');
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
        Schema::dropIfExists('tblReferentiZone');
    }
}
