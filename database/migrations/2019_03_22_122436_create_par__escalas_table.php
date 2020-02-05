<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParEscalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__escalas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idescala')->unsigned();
            $table->integer('minmonto')->default(0); 
            $table->integer('maxmonto')->default(0); 
            $table->integer('minanios')->default(0)->comment('expresado en meses');
            $table->integer('maxanios')->default(0)->comment('expresado en meses');
            $table->timestamps();
            $table->foreign('idescala')->references('idescala')->on('par__prestamos__escalas'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__escalas');
    }
}
