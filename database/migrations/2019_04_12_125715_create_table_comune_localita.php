<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableComuneLocalita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblComuneLocalita', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('comune_id')->unsigned();
            $table->integer('localita_id')->unsigned();
            $table->index('comune_id');
            $table->index('localita_id');
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
        Schema::dropIfExists('tblComuneLocalita');
    }
}
