<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAmbitiTerritorialiCaccia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblAmbitiTerritorialiCaccia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',6)->default('');
            $table->integer('provincia_id')->unsigned()->default(0);
            $table->timestamps();
        });

        DB::table('tblAmbitiTerritorialiCaccia')->insert(['id' => 1, 'code' => 'ATCRN1', 'provincia_id' => 1]);
        DB::table('tblAmbitiTerritorialiCaccia')->insert(['id' => 2, 'code' => 'ATCRN2', 'provincia_id' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblAmbitiTerritorialiCaccia');
    }
}
