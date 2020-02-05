<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtoPeisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pto__peis', function (Blueprint $table) {
            $table->increments('idpei');
            $table->integer('gestion')->comment('anio');
            $table->string('mision');
            $table->string('vision');            
            $table->string('descripcion');
            $table->string('idusuario');
            $table->boolean('activo')->default(1);
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
        Schema::dropIfExists('pto__peis');
    }
}
