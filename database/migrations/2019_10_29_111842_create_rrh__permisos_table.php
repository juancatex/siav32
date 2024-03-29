<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRrhPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__permisos', function (Blueprint $table) {
            $table->increments('idpermiso');
            $table->string('nrpermiso',10)->nullable();
            $table->integer('idempleado');
            $table->integer('idmotivo');
            $table->boolean('gocehaberes')->nullable();
            $table->boolean('cargovacacion')->nullable();
            $table->date('fechasolicitud');
            $table->date('fechasalida')->nullable();
            $table->time('horasalida')->nullable();
            $table->tinyInteger('cantidad')->nullable();
            $table->string('obs',100)->nullable();
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
        Schema::dropIfExists('rrh__permisos');
    }
}
