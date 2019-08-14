<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblReferenti', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome')->nullable()->default('');
            $table->enum('dipartimento',['Carabinieri','Forestale','Municipale','Polizia'])->default('Forestale');
            $table->string('telefono')->nullable()->default('');
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
        Schema::dropIfExists('tblReferenti');
    }
}
