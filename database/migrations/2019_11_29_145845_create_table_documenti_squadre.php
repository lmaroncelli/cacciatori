<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDocumentiSquadre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblDocumentiSquadre', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('documento_id')->unsigned();
            $table->integer('squadra_id')->unsigned();
            $table->index('documento_id');
            $table->index('squadra_id');
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
        Schema::dropIfExists('tblDocumentiSquadre');
    }
}
