<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaaDevolucionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daa__devolucions', function (Blueprint $table) {
            $table->increments('iddevolucion');
            $table->integer('idsocio')->unsigned();
            $table->integer('idtipodevolucion')->unsigned();
            $table->integer('idestudiomatematico')->unsigned();
            // $table->integer('iddescuento')->default(1);
            $table->float('totalparcial');
            $table->float('totaldevolucion');
            $table->float('totalaporte');
            $table->float('rendimiento');
            $table->integer('capitalizacionmensual');
            $table->float('anualnominal');
            $table->integer('idperfilcuentamaestro')->unsigned();
            $table->string('tipodocumento');
            $table->string('numerodocumento');
            $table->string('glosa')->nullable();
            $table->integer('idasientomaestro');
            $table->boolean('validadocontabilidad');
            // $table->boolean('cobro1')->default(0)->comment('0=no cobro cump; 1=cobro cump');  
            // $table->boolean('cobro2')->default(0)->comment('0=no cobro jub; 1=cobro jub');  
            $table->string('idusuario');
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
        Schema::dropIfExists('daa__devolucions');
    }
}
