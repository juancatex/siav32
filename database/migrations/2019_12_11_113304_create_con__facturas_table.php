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
        Schema::dropIfExists('con__facturas');
    }
}
