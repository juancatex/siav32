<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlmSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alm__salidas', function (Blueprint $table) {
            $table->increments('idsalida');
            $table->integer('identrada');
            $table->integer('idsuministro');
            $table->date('fechasalida');
            $table->integer('cantsalida');
            $table->integer('idoficina');
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
        Schema::dropIfExists('alm__salidas');
    }
}
