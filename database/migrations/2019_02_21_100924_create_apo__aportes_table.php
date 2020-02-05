<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoAportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apo__aportes', function (Blueprint $table) {
            $table->increments('idaporte');
            $table->integer('idlote')->unsigned()->nullable();
            $table->string('numpapeleta',8)->index();
            $table->integer('idtipoaporte')->unsigned();
            $table->decimal('aporte',7,2);
            $table->decimal('totalganado',8,2)->unsigned();
            $table->date('fechaaporte')->index();
            $table->string('metodoaporte',20)->default('ascii-mindef');//ascii mensual , ascii-ascinals=>ascii generado por ascinalss, formulario-aporte=>ingreso manual formulario
            $table->boolean('fecharendimiento')->default(1)->comment('1->fechaaporte; 0->created_at  para el calculo de rendimiento');//
            //$table->tinyInteger('tipodevolucion')->default(0)->comment('0->no devolucion,1->obligatoria->2->jubilacion');
            $table->boolean('activo')->default(1);
            //$table->integer('idfuerza')->unsigned();
            $table->integer('idperfilcuentamaestro')->unsigned();
            $table->boolean('validadoconta')->default(0);
            $table->string('obsaporte',256)->nullable();
            $table->string('tipodocumento',150)->nullable();
            $table->string('numdocumento',50)->nullable();
            
            $table->integer('idasientomaestro')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('idperfilcuentamaestro')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
            //$table->foreign('idfuerza')->references('idfuerza')->on('par_fuerzas');
        });
    }

    /**
     * Reverse the migrations.git 
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apo__aportes');
    }
}
