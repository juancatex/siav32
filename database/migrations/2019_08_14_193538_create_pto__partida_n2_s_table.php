<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtoPartidaN2STable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pto__partida_n2_s', function (Blueprint $table) {
            $table->increments('idpartidan2');
            $table->integer('idpartidan1')->unsigned();
            $table->integer('codigon2');
            $table->string('nombren2');
            $table->string('idcuenta');
            $table->string('tipocuenta',20);
            $table->float('monto');
            $table->string('tipo');
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
        Schema::dropIfExists('pto__partida_n2_s');
    }
}
