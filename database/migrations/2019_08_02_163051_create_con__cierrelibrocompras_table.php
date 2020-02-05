<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConCierrelibrocomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__cierrelibrocompras', function (Blueprint $table) {
            $table->Increments('idcierrelibrocompra');
            $table->tinyInteger('mes');
            $table->smallInteger('anio');
            //$table->comment('en esta tabla se almacena los meses cerrados, si no esta en esta tablas el mes esta abierto');
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
        Schema::dropIfExists('con__cierrelibrocompras');
    }
}
