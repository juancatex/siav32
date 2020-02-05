<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParProductosPerfilcuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__productos__perfilcuentas', function (Blueprint $table) {
            $table->increments('idproductoperfil');
            $table->integer('idproducto')->unsigned();
            $table->integer('idperfilcuentadetalle')->unsigned();
            $table->integer('idperfilcuentamaestro')->unsigned();
            $table->string('valor_abc');
          //  $table->string('key_abc');
            $table->boolean('iscargo')->default(0);
            $table->string('formula'); 
           // $table->text('html_value');  
            $table->dateTime('fecharegistro');
            $table->boolean('activo')->default(1); 
            $table->timestamps();

            $table->foreign('idproducto')->references('idproducto')->on('par__productos'); 
            $table->foreign('idperfilcuentadetalle')->references('idperfilcuentadetalle')->on('con__perfilcuentadetalles'); 
            $table->foreign('idperfilcuentamaestro')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par_productos_perfilcuentas');
    }
}
