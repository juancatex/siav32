<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par_municipios', function (Blueprint $table) {
            $table->increments('idmunicipio');
            $table->integer('iddepartamento');
            $table->string('nommunicipio',30);
            $table->string('codmunicipio',10);
            $table->boolean('activo')->default(1);
            $table->timestamps();
            //$table->foreign('iddepartamento')->references('iddepartamento')->on('par_departamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par_municipios');
    }
}
