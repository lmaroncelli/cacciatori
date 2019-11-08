<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustTblZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblZone', function (Blueprint $table) {
            $table->dropColumn(['unita_gestione_id', 'tipo']);
        });

        Schema::table('tblAzioniCaccia', function (Blueprint $table) {
            $table->dropColumn(['zona_id','unita_gestione_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblZone', function (Blueprint $table) {
            $table->integer('unita_gestione_id')->unsigned()->default(0);
            $table->enum('tipo', ['particella', 'zona'])->default('zona');
        });

        Schema::table('tblAzioniCaccia', function (Blueprint $table) {
            $table->integer('unita_gestione_id')->unsigned()->default(0);
            $table->integer('zona_id')->unsigned()->default(0);
        });
                
    }
}
