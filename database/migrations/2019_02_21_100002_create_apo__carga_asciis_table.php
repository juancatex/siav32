<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoCargaAsciisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apo__carga_asciis', function (Blueprint $table) {
            $table->increments('idcargaascii');
            $table->string('vacio',1)->nullable();
            $table->smallInteger('gestion');
            $table->smallInteger('codaporte');
            $table->tinyInteger('codfuerza');
            $table->string('fuerza',30);
            $table->string('coddestino',6);
            $table->string('destino',60);
            $table->smallInteger('codaportedestino');
            $table->string('descaporte',30);
            $table->string('cuentaasscinals',20);
            $table->string('numpapeleta',8)->index();
            $table->string('cisocio',15);
            $table->string('grado',20);
            $table->string('especialidad',50)->nullable();
            $table->string('nombres',100);
            $table->decimal('aporte',5,2);
            $table->string('aporte2',10);
            $table->tinyInteger('comision');
            $table->integer('idlote')->unsigned();
            $table->tinyInteger('idtipoaporte')->unsigned();
            $table->integer('idperfilcuentamaestro')->unsigned();
            $table->date('fechaaporte');
            $table->string('observaciones',255)->nullable();

            
            //$table->foreign('idlote')->references('idcsvdata')->on('csv_data');
            $table->foreign('idperfilcuentamaestro')->references('idperfilcuentamaestro')->on('con__perfilcuentamaestros');
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
        Schema::dropIfExists('apo__carga_asciis');
    }
}
