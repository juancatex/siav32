<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGloSolicitudCargoCuentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glo__solicitud_cargo_cuentas', function (Blueprint $table) {
            $table->Increments('idsolccuenta');
            $table->string('subcuenta',20)->comment('id del personal de ascinalss que recibio el cargo de cuenta')->nullable();
            $table->boolean('sidirectorio')->comment('0=personal ascinalss,1=socio que es parte del directorio central y filiales,2=sin designacion seleccionado desde solicitud');
            $table->integer('idusuario')->unsigned();
            $table->integer('idrole')->unsigned();
            $table->string('glosa',500);
            $table->float('monto',10,2)->unsigned();
            //$table->string('tipocargo',15)->default('interno')->comment('interno-> para cargos de cuenta al personal, externo->para cargos de cuenta a personal externo');
            $table->tinyInteger('estado_aprobado')->unsigned()->comment('0->no aprobada,1->aprobada-validada por conta,2->observado por contabilidad,3->desembolsada por tesoreria,4->estado borrador contabilidad');
            $table->smallInteger('idcuentadesembolso')->unsigned()->nullable()->comment('id de la cuenta contable por la que ha sido desembolsado'); //TODO:ALTER TABLE `glo__solicitud_cargo_cuentas` ADD `idcuentadesembolso` SMALLINT UNSIGNED NULL COMMENT 'id de la cuenta contable por la que ha sido desembolsado' AFTER `estado_aprobado`;
            $table->dateTime('fecha_desembolso')->nullable()->comment('fecha del desembolso por tesoreria');
            $table->date('fecha_apertura_cuenta')->nullable()->comment('fecha de apertura de cuenta');
            $table->string('tipo_desembolso',100)->nullable()->comment('se registra el tipo de desembolso ya sea web o cheque u otro');
            $table->integer('idmovbancario')->unsigned()->nullable()->comment('se registra el id de movimiento bancario para asociarlo al numero de cheque'); //TODO:ALTER TABLE `glo__solicitud_cargo_cuentas` CHANGE `num_desembolso` `idmovbancario` INT UNSIGNED NULL DEFAULT NULL COMMENT 'se registra el id de movimiento bancario para asociarlo al numero de cheque';
            $table->integer('idasientomaestro')->unisigned()->nullable()->comment('idasientomaestro del cargo de cuenta aprobado');
            $table->boolean('modificado')->default(0)->comment('0->no se modifico o edito en el modulo de solicitud, 1->si se modifico y hay que verificar');
            $table->string('observacion_conta',500)->nullable();
            $table->boolean('activo')->default(1);
            $table->tinyInteger('seg_descargoccuenta')->default(0)->comment('0->sin desacargo,1->con saldo pendiente,2->finalizado saldo 0');
            $table->float('saldo_descargo');
            $table->integer('numdocobligacion')->unsigned()->nullable()->comment('numero correlativo de documento obligatorio de cargo de cuenta');
            $table->smallInteger('tipo_filial')->nullable()->comment('1->oficina central, 2->oficina regional');
            $table->smallInteger('cant_dias')->nullable()->comment('contador de dias para el control de descargo se actualiza con EVENT');
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
        Schema::dropIfExists('glo__solicitud_cargo_cuentas');
    }
}
