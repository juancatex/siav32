<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConPerfilcuentamaestrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__perfilcuentamaestros', function (Blueprint $table) {
            $table->increments('idperfilcuentamaestro');
            $table->integer('idtipocomprobante')->nullable();
            $table->string('nomperfil',50);
            $table->string('descripcion',100)->nullable();
            $table->boolean('activo')->default(1);
            $table->boolean('completo')->default(0);
            $table->boolean('siporcentaje')->default(1);
            $table->tinyInteger('idmodulo')->unsigned();
            $table->boolean('actualizado')->default(0);
            $table->integer('idusuario')->nullable(); 
            $table->timestamps();
            
            
            $table->foreign('idmodulo')->references('idmodulo')->on('par__modulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('con__perfilcuentamaestros');
    }
}
