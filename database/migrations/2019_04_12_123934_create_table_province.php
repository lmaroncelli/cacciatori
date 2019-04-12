<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProvince extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblProvince', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome')->default('');
            $table->timestamps();
        });

        DB::table('tblProvince')->insert(['id' => 1, 'nome' => 'Rimini']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblProvince');
    }
}
