<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConPerfilcuentadetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__perfilcuentadetalles', function (Blueprint $table) {
            $table->increments('idperfilcuentadetalle');
            $table->integer('idperfilcuentamaestro')->unsigned();
            $table->integer('idcuenta')->unsigned();
            $table->string('tipocargo',1); //d-debe h-haber
            $table->decimal('porcentaje')->nullable();
            $table->tinyInteger('relacionfuerza')->nullable()->comment('relacion de la cuenta con la fuerza a la que pertenece para realizar los calculos si el perfil es uno a uno no se necesita esta verificacion ');
            $table->integer('idusuario')->nullable(); 
            $table->timestamps();
            $table->foreign('idperfilcuentamaestro')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
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
        Schema::dropIfExists('con__perfilcuentadetalles');
    }
}
