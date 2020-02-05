<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlmProveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alm__proveedors', function (Blueprint $table) {
            $table->increments('idproveedor');
            $table->string('nomproveedor',50)->nullable();
            $table->string('nit',20)->nullable();
            $table->string('nomcontacto',50)->nullable();
            $table->string('direccion',50)->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('celular',10)->nullable();
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
        Schema::dropIfExists('alm__proveedors');
    }
}
