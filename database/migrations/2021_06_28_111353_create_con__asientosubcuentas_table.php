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
            $table->bigIncrements('id');
            $table->integer('idasientomaestro')->unsigned();
            $table->tinyInteger('tipo_subcuenta')->unsigned()->comment('1->socio,2->empleado,3->externo');
            $table->integer('idsubcuenta')->unsigned();
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
