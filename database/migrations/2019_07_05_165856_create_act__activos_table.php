<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActActivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('act__activos', function (Blueprint $table) {
            $table->increments('idactivo');
            $table->string('codactivo',20);
            $table->integer('idfilial');
            $table->integer('idambiente');
            $table->integer('idgrupo');
            $table->integer('idauxiliar');
            $table->string('descripcion',150)->nullable();
            $table->string('marca',20)->nullable();
            $table->string('serie',20)->nullable();
            $table->string('imagen',20)->nullable();
            $table->date('fechaingreso');
            $table->float('costo');
            $table->integer('idcompra')->nullable();
            $table->float('residual')->default(0)->nullable();
            $table->string('obs',100)->nullable();
            $table->boolean('activo')->default(1);
            //$table->boolean('baja')->default(0);
            $table->date('fechabaja')->nullable();
            $table->string('nrorden',10)->nullable();
            $table->tinyInteger('idmotivo')->nullable();
            $table->string('obsbaja',100)->nullable();
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
        Schema::dropIfExists('act__activos');
    }
}
