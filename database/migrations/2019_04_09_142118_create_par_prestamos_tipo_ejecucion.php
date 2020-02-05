<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParPrestamosTipoEjecucion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__prestamos__tipo__ejecucion', function (Blueprint $table) {
            $table->increments('idejecucion');
            $table->string('Codigo');
            $table->string('Detalle');
            $table->string('nombre');
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
        Schema::dropIfExists('par__prestamos__tipo__ejecucion');
    }
}
