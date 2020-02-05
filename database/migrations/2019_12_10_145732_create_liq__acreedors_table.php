<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiqAcreedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liq__acreedors', function (Blueprint $table) {
            $table->increments('idacreedor');
            $table->integer('idsocio');
            $table->string('numpapeleta',50);
            $table->decimal('importe',7,2);                        
            $table->integer('moneda');                        
            $table->string('documento',250);
            $table->string('detalle',500);
            $table->string('tipo',50);
            $table->integer('idprestamo')->default(0);
            $table->integer('idasientomaestro')->default(0);
            $table->integer('idperfilcuenta')->default(0);            
            $table->date('fechaproceso');
            $table->integer('estado')->default(0)->comment('0=nuevo, 1=cobrado, 2=devuelto, 99=liquidado'); ;
            $table->integer('iduser');             
            $table->integer('migrado')->default(0);              
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
        Schema::dropIfExists('liq__acreedors');
    }
}
