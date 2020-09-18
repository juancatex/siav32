<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__pagos', function (Blueprint $table) {
            $table->increments('idpago');
            $table->integer('idasignacion');
            $table->string('concepto',500)->nullable();
            $table->string('periodo',20)->nullable();
            $table->tinyInteger('modopago')->comment('1-efectivo,2-deposito,3-descuento');
            $table->string('nrdocumento',20)->nullable();
            $table->string('nit',20)->nullable();
            $table->string('razon',20)->nullable();
            $table->integer('idasientomaestro')->nullable();
            $table->float('importe');
            $table->date('fecha');
            $table->integer('idresponsable')->nullable();
            $table->integer('idusuario');
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
        Schema::dropIfExists('ser__pagos');
    }
}
