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
           
            $table->integer('garantes')->default(0);  
            $table->integer('linea')->default(0); 

            $table->float('tasa')->default(0);  
            $table->integer('plazominimo');
            $table->integer('plazomaximo');
            $table->integer('activo')->default(2)->comment('0=Desactivado, 1=Activo, 2=No consolidado, 3=Inhabilitado por Contabilidad'); 
            $table->integer('desembolso_perfil')->default(0)->comment('desembolso normal');

            
            $table->integer('desembolso_perfil_refi')->default(0)->comment('desembolso por refinanciamiento'); 
            $table->integer('desembolso_perfil_garante')->default(0)->comment('desembolso por activar garante/titular'); 
            $table->integer('cobranza_perfil_refi')->default(0)->comment('cobranza refinanciamiento'); 
            $table->integer('cobranza_perfil_garante')->default(0)->comment('cobranza activar garante/titular'); 


            $table->integer('cobranza_perfil')->unsigned()->comment('cobranza manual'); 
            $table->integer('cobranza_perfil_ascii')->unsigned()->comment('cobranza ascii'); 
            $table->integer('cobranza_perfil_acreedor')->unsigned()->comment('cobranza acreedor'); 
            $table->integer('cobranza_perfil_daro')->unsigned()->comment('cobranza daro'); 
            
            $table->integer('cancelarprestamos')->default(0)->comment('refinanciamiento');  
            $table->integer('activar_garante')->default(0)->comment('activar a garante/titular'); 

            $table->text('serializedmap'); 
            $table->dateTime('fecharegistro');
            $table->timestamps();

            $table->foreign('moneda')->references('idmoneda')->on('par__monedas');
            $table->foreign('idfactor')->references('idfactor')->on('par__productos__factores'); 
            $table->foreign('idescala')->references('idescala')->on('par__prestamos__escalas'); 
           // $table->foreign('desembolso_perfil')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
            $table->foreign('cobranza_perfil')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
            $table->foreign('cobranza_perfil_ascii')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
            $table->foreign('cobranza_perfil_acreedor')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
            $table->foreign('cobranza_perfil_daro')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
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
