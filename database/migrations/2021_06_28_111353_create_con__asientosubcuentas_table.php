<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConAsientosubcuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__asientosubcuentas', function (Blueprint $table) {
            $table->bigIncrements('idasientosubcuenta');
            $table->integer('idasientomaestro')->unsigned();
            $table->tinyInteger('tipo_subcuenta')->unsigned()->comment('1->socio,2->empleado,3->externo,4->cuenta general asscinalss');
            $table->integer('idsubcuenta')->unsigned()->comment('columna id de las tablas proveedor, socios, empleados');
            $table->integer('subcuenta')->unsigned()->comment('ci->empleado,numpapeleta->socios,nit->proveedores');
            $table->integer('idcuenta')->unsigned()->comment('id de la cuenta contable');
            $table->string('subdetalle',);
            $table->float('subdebe',10,2)->nullable();
            $table->float('subhaber',10,2)->nullable();
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('con__asientosubcuentas');
    }
}
