<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConLibrocomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__librocompras', function (Blueprint $table) {
            $table->Increments('idlibrocompra');
            $table->smallinteger('numeracion')->unsigned();
            $table->date('fecha_factura');
            $table->integer('idproveedor')->unsigned();
            $table->integer('numfactura')->unsigned();
            $table->string('numautorizacion',20);
            $table->double('importe')->unsigned();
            $table->double('impnocredfiscal')->unsigned()->default(0);
            $table->double('subtotal')->unsigned();
            $table->double('descuentos')->unsigned()->default(0);
            $table->double('impcredfiscal')->unisgned();
            $table->double('credfiscal')->unsigned();
            $table->double('restoimporte')->unsigned();
            $table->string('codigocontrol',20)->nullable();
            $table->string('detalle_fac',200)->comment('registra el detalle de la factura o una descripcion del porque de la factura');
            $table->integer('idasientomaestro')->unsigned()->nullable();
            $table->boolean('validadoconta')->default(0);
            $table->integer('lote')->unsigned()->nullable();
            $table->tinyInteger('activo')->default(1)->comment('0->comprobante eliminado,1->activo,2->comprobante revertido,3->eliminado desde menu libro comporas');
            $table->smallInteger('registrado_por');
            $table->smallInteger('editado_por')->nullable();
            $table->smallInteger('idfilial');
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
        Schema::dropIfExists('con__librocompras');
    }
}
