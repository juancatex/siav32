<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParEstadoModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par_estadomodulos', function (Blueprint $table) {
        $table->increments('idestadomodulo');
            $table->string('nomestado',100);
            $table->integer('idmodulo')->unsigned();
            $table->integer('idestado')->unsigned();
            $table->boolean('activo')->default(1);
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
        Schema::dropIfExists('afi_estados');
    }
}
