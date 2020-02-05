<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRrhEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__empleados', function (Blueprint $table) {
            $table->increments('idempleado');
            $table->string('nombre',30);
            $table->string('apaterno',30);
            $table->string('amaterno',30)->nullable();
            $table->string('sexo',1);
            $table->string('gruposangre',5)->nullable();
            $table->string('ci',12)->nullable();
            $table->integer('iddepartamento')->unsigned();
            $table->string('foto',30)->nullable();
            $table->date('fechanacimiento')->nullable();
            $table->integer('idestadocivil')->nullable();
            $table->integer('idformacion')->nullable(); 
            $table->integer('idprofesion')->nullable();
            $table->string('domicilio',50)->nullable();
            $table->string('zona',30)->nullable();
            $table->string('telfijo',10)->nullable();
            $table->string('telcelular',10)->nullable();
            $table->string('email',50)->nullable();
            $table->integer('idfilial')->default(1);
            $table->integer('idoficina')->nullable();
            $table->integer('idcargo')->nullable();
            $table->date('fechaingreso')->nullable();
            $table->date('fecharetiro')->nullable();
            $table->integer('idbanco')->nullable();
            $table->string('nrcuenta',20)->nullable();
            $table->integer('codbiom')->nullable();
            $table->tinyInteger('ssocial')->nullable();
            $table->string('codssocial',20)->nullable();
            $table->tinyInteger('ssalud')->nullable();
            $table->string('codssalud',20)->nullable();
            $table->string('obs',150)->nullable();
            $table->boolean('activo')->default(1)->comment('0-notrabajan, 1-vigentes, 2-superadmin sistema');
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
        Schema::dropIfExists('rrh__empleados');
    }
}
