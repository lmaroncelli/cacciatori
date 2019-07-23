<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCacciatoriSquadre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblCacciatoriSquadre', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cacciatore_id')->unsigned();
            $table->integer('squadra_id')->unsigned();
            $table->boolean('capo_squadra')->default(false);
            $table->index('cacciatore_id');
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
        Schema::dropIfExists('tblCacciatoriSquadre');
    }
}
