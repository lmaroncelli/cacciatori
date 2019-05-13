<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoligonoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblPoligoni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('zona_id')->unsigned()->nullable()->default(null);
            $table->integer('distretto_id')->unsigned()->nullable()->default(null);
            $table->string('name')->default('')->nullable();
            $table->string('strokeColor', 7)->default('#FF0000')->nullable();
            $table->string('fillColor', 7)->default('#FF0000')->nullable();
            $table->string('strokeOpacity')->default('0.8')->nullable();
            $table->string('strokeWeight')->default('2')->nullable();
            $table->string('fillOpacity')->default('0.35')->nullable();
            $table->boolean('editable')->default(true)->nullable();
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
        Schema::dropIfExists('tblPoligoni');
    }
}
