<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerAsignacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__asignacions', function (Blueprint $table) {
            $table->increments('idasignacion');
            $table->integer('idcliente');
            $table->string('tipocliente',1);
            $table->boolean('vigente')->default(0);
            $table->integer('idambiente');
            $table->string('nrasignacion',10)->nullable();
            $table->date('fechasolicitud')->nullable();
            $table->string('mesboleta',10)->nullable();
            $table->integer('ocupantes')->nullable();
            $table->string('iddocumentos',20)->nullable();
            $table->string('idimplementos',20)->nullable();
            $table->date('fechaentrada')->nullable();
            $table->date('fechasalida')->nullable();
            $table->time('horaentrada')->nullable();
            $table->time('horasalida')->nullable();
            $table->date('fechadefuncion')->nullable();
            $table->integer('idresponsable')->nullable();
            $table->string('obs1',100)->nullable();            
            //$table->string('obs2',100)->nullable();
            $table->integer('idrepresentante')->nullable();
            $table->integer('activo')->default(0);
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
        Schema::dropIfExists('ser__asignacions');
    }
}
