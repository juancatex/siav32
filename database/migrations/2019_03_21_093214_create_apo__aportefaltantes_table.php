<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoAportefaltantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apo__aportefaltantes', function (Blueprint $table) {
            $table->Increments('idaportefaltante');
            $table->string('numpapeleta',20)->index();
            $table->integer('faltantes_obligatorio')->unsigned()->default(0)->nullable()->comment('numero de aportes faltantes desde su primer aporte');
            $table->decimal('aporte_ultimomes_obligatorio',7,2)->nullable()->unsigned();
            $table->decimal('suma_aportes_obligatorio',7,2)->unsigned()->nullable()->comment('suma total de los aportes faltantes en base al aporte del mes anterior');
            $table->integer('faltantes_jubilacion')->unsigned()->default(0)->nullable()->comment('numero de aportes faltantes desde su primer aporte');
            $table->decimal('aporte_ultimomes_jubilacion',7,2)->nullable()->unsigned();
            $table->decimal('suma_aportes_jubilacion',7,2)->unsigned()->nullable()->comment('suma total de los aportes faltantes en base al aporte del mes anterior');
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
        Schema::dropIfExists('apo__aportefaltantes');
    }
}
