<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConCuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__cuentas', function (Blueprint $table) {
            $table->increments('idcuenta');
            $table->string('codn5',10);
            $table->string('valorcuenta',3);
            $table->string('nomcuenta',200);
            $table->string('codcuenta',20)->index();
            $table->string('descripcion',200);
            $table->boolean('activo')->default(1);
            $table->integer('idmoneda')->unsigned()->default(1);
            $table->timestamps();
            $table->unique(['codcuenta']);
            $table->foreign('codn5')->references('codn5')->on('con__cuentanivel5');
            $table->foreign('idmoneda')->references('idmoneda')->on('par__monedas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('con__cuentas');
    }
}
