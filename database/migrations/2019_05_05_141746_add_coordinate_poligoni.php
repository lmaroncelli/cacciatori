<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoordinatePoligoni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblCoordinatePoligoni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('poligono_id')->unsigned()->default(0);
            $table->string('lat')->default(null)->nullable();
            $table->string('long')->default(null)->nullable();
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
        Schema::dropIfExists('tblCoordinatepoligoni');
    }
}
