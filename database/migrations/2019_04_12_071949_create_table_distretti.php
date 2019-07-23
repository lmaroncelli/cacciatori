<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDistretti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblDistretti', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('atc_id')->unsigned()->default(1);
            $table->string('nome')->default('');
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
        Schema::dropIfExists('tblDistretti');
    }
}
