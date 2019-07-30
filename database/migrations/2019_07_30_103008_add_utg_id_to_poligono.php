<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUtgIdToPoligono extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblPoligoni', function (Blueprint $table) {
          $table->integer('unita_gestione_id')->unsigned()->nullable()->default(null)->after('distretto_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblPoligoni', function (Blueprint $table) {
            $table->dropColumn(['unita_gestione_id']);
        });
    }
}
