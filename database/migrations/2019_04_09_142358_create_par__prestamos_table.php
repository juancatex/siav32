<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParPrestamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__prestamos', function (Blueprint $table) {
            $table->bigIncrements('idprestamo');
            $table->string('idprestamo_antiguo',30)->nullable()->comment('id de prestamos de la migracion');//id de prestamos de la migracion
            $table->integer('idref')->nullable();
            $table->string('idrefaux')->nullable();
            $table->integer('idejecucion')->unsigned()->default(1);  //viene de la tabla par_prestamos_tipo_ejecucion

            $table->integer('idproducto')->unsigned();  
            $table->integer('idsocio')->unsigned();
            $table->integer('idcuentasocio')->nullable();//se registra la cuenta bancaria en la que se abonara el monto del prestamo  
            $table->integer('idestado')->unsigned()->default(1);// se registra el estado del prestamo ( 1=pendiente de dessembolso, 2=vigente, 3=mora, 4=cancelado, 5=liquidado(pagado))
            
            $table->integer('idsupervisor')->nullable(); //ejecutivo autorizado (quien esta a cargo del area)
            $table->integer('idoperario')->nullable(); //ejecutivo responsable (quien realiza la operacion)
            
            $table->float('monto')->default(0); 
            $table->float('cuota')->default(0); 
            $table->integer('plazo')->default(0);
            $table->integer('interesdiferido')->default(0);
            $table->float('factor')->default(0); 
            $table->string('obs')->nullable();
            $table->string('no_prestamo')->nullable(); 
            $table->dateTime('fecharegistro')->nullable(); 

            $table->string('idtransaccionD')->nullable();
            $table->string('numDocD')->nullable();
            $table->dateTime('fechardesembolso')->nullable(); 
            $table->string('detalle_desembolso')->nullable()->comment('glosa');  //glosa
            $table->integer('idusuario')->nullable()->comment('usuario quien hizo el desembolso');  

            $table->integer('lote')->nullable(); 

            $table->dateTime('fecharde_apro_conta')->nullable(); 
            $table->integer('apro_conta')->default(0)->index();// cambia de estado 1 cuanto contabilidad lo aprueba
            $table->integer('idasiento')->nullable()->index();  

            $table->float('liquidopagable')->default(0); 
            $table->float('liquidocomputable')->default(0); 
            $table->float('cuotasvigentes')->default(0); 
            $table->float('b_frontera')->default(0); 
            $table->float('b_prolibro')->default(0); 
            $table->float('b_familiar')->default(0); 
            $table->float('b_riesgo')->default(0); 
            $table->float('cuota_aprox')->default(0); 
            $table->text('planPagosMap')->default('[]'); 
            $table->bigInteger('idprestamo_lista')->nullable()->index();
            $table->string('obs_lista')->nullable()->comment('observaciones para el registro del socio en la lista, ejemplo: que le falte liquido pagable, que tenga varias deudas y aun asi sea autorizado');  
            $table->timestamps();

            $table->foreign('idproducto')->references('idproducto')->on('par__productos');
            $table->foreign('idsocio')->references('idsocio')->on('socios');  
            $table->foreign('idejecucion')->references('idejecucion')->on('par__prestamos__tipo__ejecucion'); 
            $table->foreign('idestado')->references('idestado')->on('par__prestamos__estados'); 


            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__prestamos');
    }
}
