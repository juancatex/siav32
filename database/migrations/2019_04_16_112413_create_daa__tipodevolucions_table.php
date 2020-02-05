<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaaTipodevolucionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daa__tipodevolucions', function (Blueprint $table) {
            $table->increments('idtipodevolucion');
            $table->string('nomtipodevolucion');
            $table->integer('aporteminimo');
            $table->integer('porcentaje')->comment('1=calcula descuento %, 0=no calcula descuento %');
            $table->integer('valido')->comment('1=valida cant aportes, 0=no valida cant aportes');
            $table->boolean('activo')->default(1);
            //$table->foreign('idconfig')->references('idconfig')->on('configs');
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
        Schema::dropIfExists('daa__tipodevolucions');
    }
}
