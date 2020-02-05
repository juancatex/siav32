<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->increments('idsocio');
            $table->string('codsocio',15)->nullable();
            $table->string('numpapeleta',8)->index();
            $table->string('nombre',50);
            $table->string('apaterno',50);
            $table->string('amaterno',50);
            $table->string('aesposo',50)->nullable();
            $table->string('sexo',1)->nullable();
            $table->string('carnetmilitar',20)->nullable();
            $table->string('ci',12);
            
            $table->integer('iddepartamentoexpedido')->unsigned(); //expedido
            $table->date('fechanacimiento')->nullable();
            $table->date('fechaegreso')->nullable();
            $table->date('fechainscripcion')->nullable();
            $table->date('fechaincorporacion')->nullable()->index();
            $table->integer('idmunicipionacimiento')->unsigned()->nullable();  //de la tabla municipio para lugar mac

            $table->integer('idgrado')->unsigned();           
            $table->integer('idfuerza')->unsigned();           
            $table->integer('iddestino')->unsigned();  //de la tabla destino // por crear           
            $table->integer('idescalafon')->unsigned();
            $table->integer('idespecialidad')->unsigned()->nullable();
            $table->integer('idestadocivil')->unsigned();
            $table->string('direcciondomicilio',255)->nullable();
            $table->string('telfijo',50)->nullable();
            $table->string('telcelular',50)->nullable();
            $table->string('email',50)->nullable(); 
            $table->string('observaciones',255)->nullable();
            $table->integer('idtiposocio')->unsigned();
            
            
            
            $table->tinyInteger('idestafiliaciones')->default(1);  //activo, pasivo. jubilado  //crar tabla
            $table->tinyInteger('idestaportes')->default(1); //activo
            $table->tinyInteger('idestservicios')->default(1);
            $table->tinyInteger('idestprestamos')->default(0)->comment('0=Sin observaciones, 1=Bloqueado (observado)'); 
            $table->tinyInteger('idestdaaro')->default(0);

            $table->float('liquidopagable_papeleta')->default(0);
            $table->date('fecha_papeleta_pago')->nullable();

            $table->string('rutafoto',100)->nullable();            
            $table->integer('idusuarioregistro')->unsigned();
            $table->integer('idusuariomodificacion')->unsigned();
            
            $table->boolean('activo')->default(1);  //borarod logico
			$table->boolean('carnetestado')->default(0);  //estado para la generacion del carnet Socio , falta indexar con contabilidad
            
            $table->timestamps();

            //$table->foreign('idgrado')->references('idgrado')->on('par_grados'); 
            //$table->foreign('idfuerza')->references('idfuerza')->on('par_fuerzas');
            //$table->foreign('idescalafon')->references('idescalafon')->on('par_escalafones');
            //$table->foreign('idespecialidad')->references('idespecialidad')->on('par_especialidades');
            //$table->foreign('idestadocivil')->references('idestadocivil')->on('par_estadocivil');
            //$table->foreign('idusuarioregistro')->references('id')->on('personas');
            //$table->foreign('iddepartamentoexpedido')->references('iddepartamento')->on('par_departamentos');
            //$table->foreign('idtiposocio')->references('idtiposocio')->on('par__tiposocios');
			//$table->foreign('iddestino')->references('iddestino')->on('par__destinos');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socios');
    }
}
