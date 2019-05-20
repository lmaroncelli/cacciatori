<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAzioneDiCaccia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblAzioniCaccia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('squadra_id')->unsigned()->default(0);
            $table->integer('distretto_id')->unsigned()->default(0);
            $table->integer('unita_gestione_id')->unsigned()->default(0);
            $table->integer('zona_id')->unsigned()->default(0);
            $table->integer('comune_id')->unsigned()->default(0);
            $table->dateTime('dalle')->nullable()->default(null);
            $table->dateTime('alle')->nullable()->default(null);
            $table->text('note')->nullable()->default(null);
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
        Schema::dropIfExists('tblAzioniCaccia');
    }
}
