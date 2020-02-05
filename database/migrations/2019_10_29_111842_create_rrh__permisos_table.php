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
            $table->integer('idempleado');
            $table->integer('idmotivo');
            $table->boolean('gocehaberes')->nullable();
            $table->boolean('cargovacacion')->nullable();
            $table->date('fechasolicitud');
            $table->date('fechaini')->nullable();
            $table->date('fechafin')->nullable();
            $table->time('horaini')->nullable();
            $table->time('horafin')->nullable();
            $table->string('obs',100)->nullable();
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
