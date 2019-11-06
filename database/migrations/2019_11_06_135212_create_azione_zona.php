<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAzioneZona extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblAzioneZona', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('azione_id')->unsigned();
            $table->integer('zona_id')->unsigned();
            $table->index('azione_id');
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
        Schema::dropIfExists('tblAzioneZona');
    }
}
