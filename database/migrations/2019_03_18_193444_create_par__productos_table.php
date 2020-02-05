<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__productos', function (Blueprint $table) {
            $table->increments('idproducto');
            $table->integer('moneda')->unsigned();  
            $table->integer('idfactor')->unsigned(); 
            $table->integer('idescala')->unsigned();
            $table->string('nomproducto');

            $table->string('codproducto'); 
            $table->integer('max_prestamos')->default(0); 
            $table->integer('lote')->default(0); 
            $table->integer('blockauto')->default(0);
            $table->integer('garantes')->default(0);  
            $table->integer('cancelarprestamos')->default(0); 
            $table->integer('linea')->default(0); 

            $table->float('tasa')->default(0);  
            $table->integer('plazominimo');
            $table->integer('plazomaximo');
            $table->integer('activo')->default(2)->comment('0=Desactivado, 1=Activo, 2=No consolidado, 3=Inhabilitado por Contabilidad'); 
            

            $table->integer('desembolso_perfil')->unsigned();
            $table->integer('cobranza_perfil')->unsigned();
            $table->text('serializedmap'); 

            $table->dateTime('fecharegistro');
            $table->timestamps();

            $table->foreign('moneda')->references('idmoneda')->on('par__monedas');
            $table->foreign('idfactor')->references('idfactor')->on('par__productos__factores'); 
            $table->foreign('idescala')->references('idescala')->on('par__prestamos__escalas'); 
            $table->foreign('desembolso_perfil')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
            $table->foreign('cobranza_perfil')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__productos');
    }
}
