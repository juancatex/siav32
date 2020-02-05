<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtoPartidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pto__partidas', function (Blueprint $table) {
            $table->increments('idpartida');
            $table->integer('codigo');
            $table->string('nompartida');
            $table->string('descripcion');
            $table->integer('idpei')->unsigned();                       
            $table->float('ptoinicial');
            $table->float('ptototal');
            $table->boolean('valida1')->default(1);;
            $table->boolean('valida2')->default(1);;
            $table->boolean('activo')->default(1);;
            $table->string('user');
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
        Schema::dropIfExists('pto__partidas');
    }
}
