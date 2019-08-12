<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCacciatore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblCacciatori', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->string('nome')->default('')->nullable();
            $table->string('cognome')->default('')->nullable();
            $table->string('telefono')->default('')->nullable();
            $table->string('registro')->nullable()->default(null);
            $table->date('data_nascita')->nullable()->default(null);
            $table->text('note')->nullable()->default(null);
            $table->softDeletes();
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
        Schema::dropIfExists('tblCacciatori');
    }
}
