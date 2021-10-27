<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__facturas', function (Blueprint $table) {
            $table->increments('idfactura');
            $table->integer('idfacturaparametro');
            $table->integer('numerofactura');
            $table->string('codigocontrol',15);
            $table->string('razonsocial',200);
            $table->string('nit',15);
            $table->string('detalle',500);
            $table->decimal('importetotal',7,2);
            $table->decimal('importecf',7,2);
            $table->integer('idasientomaestro');
            $table->boolean('validadoconta')->default(0)->comment('0->no validado por contabilidad, 1->validado por contabilidad,2->comprobante borrador,3->comprobante eliminado');
            $table->integer('registrado_por');
            $table->double('debfiscal')->unsigned()->default(0);
            $table->double('restoimporte')->unsigned()->default(0);
            $table->double('it')->unsigned()->default(0);
            $table->boolean('activo')->default(1)->comment('0->factura eliminada,1->activo,2->factura anulada');
            $table->boolean('mescerrado')->default(0)->comment('para verificar si el mes establoqueado por contabilidad 0->abierto,1->bloqueado');
            $table->string('numautorizacion',20)->comment('registra el numero de autorizacion');
            $table->date('fechafactura');
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
        Schema::dropIfExists('con__facturas');
    }
}
