<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConSubcuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__subcuentas', function (Blueprint $table) {
            $table->Increments('idsubcuenta');
            $table->integer('idasientomaestro')->unsigned();
            $table->string('subcuenta',20);
            $table->string('tiposubcuenta')->comment('socio,proveedor,empleado,institucion,etc');//;//socio,proveedor,empleado
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
        Schema::dropIfExists('con__subcuentas');
    }
}
