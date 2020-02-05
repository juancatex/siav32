<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParEspecialidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par_especialidades', function (Blueprint $table) {
            $table->Increments('idespecialidad');
            $table->integer('idfuerza');
            $table->string('nomespecialidad',50);
            $table->string('abrvesp',20)->nullable();
            $table->boolean('activo')->default(1);
            $table->timestamps();

            //$table->foreign('idfuerza')->references('idfuerza')->on('par_fuerzas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par_especialidades');
    }
}
