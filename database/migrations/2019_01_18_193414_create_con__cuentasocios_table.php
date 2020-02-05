<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConCuentasociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__cuentasocios', function (Blueprint $table) {
            $table->increments('idcuentasocio');
            $table->integer('idsocio')->unsigned();
            $table->integer('identidadbancaria')->unsigned();
            $table->string('numcuentasocio',30);
            $table->boolean('activo')->default(1);
            /*
            $table->integer('idpersonalregistro')->unsigned();
            $table->integer('idpersonalmodif')->unsigned();
            */

            $table->timestamps();


            $table->foreign('idsocio')->references('idsocio')->on('socios');
            $table->foreign('identidadbancaria')->references('identidadbancaria')->on('con__entidadbancarias');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('con__cuentasocios');
    }
}
