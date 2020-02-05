<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreCobranzasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre__cobranzas', function (Blueprint $table) {
            $table->increments('idcobranza');
            $table->integer('idprestamo')->unsigned();
            $table->integer('idperfilcuenta')->unsigned();
            $table->string('numdocumento');
            $table->string('detalle');
            $table->date('fechatransaccion');
            $table->float('monto');
            $table->boolean('activo')->default(1);
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
        Schema::dropIfExists('pre__cobranzas');
    }
}
