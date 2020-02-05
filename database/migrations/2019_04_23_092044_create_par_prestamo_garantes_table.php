<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParPrestamoGarantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__prestamos__garantes', function (Blueprint $table) {
            $table->increments('idgarante');
            $table->integer('idsocio')->unsigned();
            $table->float('factor')->default(0);
            $table->integer('idprestamo')->unsigned();
            $table->dateTime('fecharegistro');
            $table->boolean('activo')->default(1); 
            $table->timestamps();

            $table->foreign('idsocio')->references('idsocio')->on('socios');  
            $table->foreign('idprestamo')->references('idprestamo')->on('par__prestamos');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__prestamos__garantes');
    }
}
