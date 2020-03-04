<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParPrestamosPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__prestamos__plans', function (Blueprint $table) {
            $table->bigIncrements('idplan')->index();
            $table->bigInteger('idprestamo')->unsigned()->index();
            $table->integer('pe')->index()->comment('periodo'); 
            $table->date('fp')->index()->comment('fecha de pago');
            $table->integer('di')->comment('dias');
            $table->float('am')->index()->comment('amortizacion');
            $table->float('in')->default(0)->comment('interes');
            $table->float('indi')->default(0)->comment('intereses diferidos');
            $table->float('car')->default(0)->comment('cargos');
            $table->float('cut')->default(0)->comment('cuota final');
            $table->float('ca')->default(0)->comment('capital');
            $table->float('ca_an')->default(0)->comment('capital anterior');
            $table->float('cuota')->default(0)->comment('cuota inicial');
            $table->dateTime('fecharegistro');
            $table->integer('tipo')->default(0)->comment('0=normal, 1=Recalculado por amortizacion, 2=Recalculado por mora, 3=Recalculado por refinanciamiento,10=No activo'); 
            $table->integer('idestado')->unsigned()->default(2)->comment('2=vigente, 11=Cobrado por plataforma, 12=Cobrado por Ascii, 13=Cobrado por Refinanciamiento, 14=Cobrado por Liquidacion del prestamo, 15=Cobrado por Amortizacion, 16=Cobrado por Alta Garante, 20=Desembolso'); 
            $table->float('send_ascii')->default(0); 
            $table->float('tipocambio')->default(0); 
            $table->string('file')->nullable();

            $table->string('idtransaccionC')->nullable();
            $table->string('numDocC')->nullable()->comment('En caso de ser una cobranza por ascii, tenga un codigo de grupo generado por el mes-año');
            $table->string('glosa')->nullable();// detalle de la cobranza.. 
            $table->dateTime('fechaCobranza')->nullable();
            $table->float('importe')->default(0); 
            $table->integer('idusuario')->nullable()->comment('usuario quien hizo la cobranza');  

            $table->dateTime('fecharde_apro_conta')->nullable(); 
            $table->integer('apro_conta')->default(0)->index();// cambia de estado 1 cuanto contabilidad lo aprueba
            $table->integer('idasiento')->nullable()->index();  

            $table->timestamps();

            $table->foreign('idestado')->references('idestado')->on('par__prestamos__estados');
            $table->foreign('idprestamo')->references('idprestamo')->on('par__prestamos'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__prestamos__plans');
    }
}
