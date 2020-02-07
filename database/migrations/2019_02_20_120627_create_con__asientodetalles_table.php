<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConAsientodetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__asientodetalles', function (Blueprint $table) {
            $table->increments('idasientodetalle');
            $table->integer('idasientomaestro')->unsigned();
            $table->integer('idcuenta')->unsigned();
            $table->tinyInteger('tiposubcuenta')->unsigned()->default(1)->comment('1=>num papeleta socio,2=>idempleado');
            $table->float('monto',15,2);
            $table->float('debe',15,2)->nullable();
            $table->float('haber',15,2)->nullable();
            $table->string('moneda',5)->default('bs');
            $table->string('documento',200)->nullable();
            $table->string('subcuenta',20)->nullable();
            $table->string('usuarioregistro',5);
            $table->string('usuariomodifica',5);

            $table->timestamps();

            $table->foreign('idasientomaestro')->references('idasientomaestro')->on('con__asientomaestros');
            $table->foreign('idcuenta')->references('idcuenta')->on('con__cuentas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('con__asientodetalles');
    }
}
