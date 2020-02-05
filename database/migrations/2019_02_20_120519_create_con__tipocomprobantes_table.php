<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConTipocomprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__tipocomprobantes', function (Blueprint $table) {
            $table->increments('idtipocomprobante');
            $table->string('nomtipocomprobante');
            $table->string('descripcion');
            $table->string('prefijo',5);
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
        Schema::dropIfExists('con__tipocomprobantes');
    }
}
