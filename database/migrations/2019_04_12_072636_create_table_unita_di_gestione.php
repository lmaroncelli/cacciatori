<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUnitaDiGestione extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblUnitaGestione', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('distretto_id')->unsigned()->default(0);
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
        Schema::dropIfExists('tblUnitaGestione');
    }
}
