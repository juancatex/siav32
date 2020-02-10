<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConAsientomaestrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__asientomaestros', function (Blueprint $table) {
            $table->increments('idasientomaestro');
            $table->integer('idtipocomprobante');
            $table->integer('cont_comprobante')->unsigned();//
            $table->string('cod_comprobante',20)->nullable()->comment('codificacion del comprobante, mes-tipocomprobante+numerodecomprobante');
            $table->tinyInteger('idperfilcuentamaestro')->unsigned()->nullable();
            $table->dateTime('fecharegistro');
            $table->dateTime('fechavalidado')->nullable();
            $table->string('tipodocumento')->nullable();
            $table->string('numdocumento')->nullable();
            $table->string('glosa',1000)->nullable();
            $table->integer('idfilial');
            $table->tinyInteger('idmodulo')->unsigned();
            $table->tinyInteger('estado')->default(0)->unsigned()->comment('0=no validado, 1=validado y para comprobantes agrupados verificar que id agrupacion sea diferente de null, 2=eliminacion logica, 3=error de datos-observado,4=reversion,5=borrador,6=borrador eliminado');
            $table->integer('idagrupacion')->unsigned()->nullable()->comment('idasientomaestro del comprobante que resulte de la agrupacion de comprobantes por parte de contabilidad ');
            $table->tinyInteger('desembolso')->unsigned()->nullable()->default(0)->comment('solo para cuentas en las que interviene el desembolso por tesoreria 0->no desembolsado,1->desembolsado');
            $table->dateTime('fechahora_desembolso')->nullable();
            $table->integer('idrevertido')->unsigned()->nullable();
            $table->integer('loteprestamos')->unisgned()->nullable();
            $table->string('observaciones',500)->nullable();
            $table->integer('agrupacion')->unsigned()->default(0)->comment('para agrupar los desembolsos y cobranzas de prestamos');
            $table->integer('segcobranza')->nullable()->unsigned()->comment('idasientomaestro de la cobranza al que este prestamo esta asociado en adelante y sirve para verificar si se permite o no la reversion de este comprobante');
            $table->integer('u_registro')->nullable()->comment('id del usuario que registra o envia el asiento');
            $table->integer('u_obs_val')->nullable()->comment('usuario de contabilidad que observa un asiento contable o que valida el asiento contable');
            $table->integer('u_eliminacion')->nullable()->comment('usuario que elimina (cambia de estado) un asiento contable');
            $table->integer('u_modifica')->nullable()->comment('usuario que edita la cabecera del comprobante contable');
            $table->boolean('fact_modificada')->default(0)->comment('0->factura asociada no modificada,1->factura asociada modificada');
            $table->integer('idsolccuenta')->nullable()->comment('si registra el id del asiento maestro a los que corresponde el descargo de cargo de cuenta');
            $table->smallInteger('gestion')->unsigned()->default(0)->comment('si esta en 0 indica que es la gestion activa');
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
        Schema::dropIfExists('con__asientomaestros');
    }
}
