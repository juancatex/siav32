<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConFacturaParametrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__factura_parametros', function (Blueprint $table) {
            $table->increments('idfacturaparametro');
            $table->string('nombreinstitucion',100);
            $table->string('propietario',200);
            $table->string('direccion',200);
            $table->string('telefono',50);
            $table->string('ciudad',50);
            $table->string('actividad',100);
            $table->string('nit',20);
            $table->string('numeroautorizacion',20);
            $table->string('numerodosificacion',20);
            $table->integer('rangofactura1');
            $table->integer('rangofactura2');
            $table->date('fechalimite');
            $table->string('texto1',500);
            $table->string('texto2',500);            
            $table->string('llave',100);            
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
        Schema::dropIfExists('con__factura_parametros');
    }
}
