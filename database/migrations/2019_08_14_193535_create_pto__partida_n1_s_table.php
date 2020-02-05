<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtoPartidaN1STable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pto__partida_n1_s', function (Blueprint $table) {
            $table->increments('idpartidan1');
            $table->integer('idpartida')->unsigned();
            $table->integer('codigon1');
            $table->string('nombren1');
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
        Schema::dropIfExists('pto__partida_n1_s');
    }
}
