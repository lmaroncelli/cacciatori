<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableZone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblZone', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unita_gestione_id')->unsigned()->default(0);
            $table->integer('numero')->default(0);
            $table->string('nome')->default('');
            $table->decimal('superficie', 8, 1)->nullable()->default(null);
            $table->enum('tipo', ['particella', 'zona'])->default('zona');
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
        Schema::dropIfExists('tblZone');
    }
}
