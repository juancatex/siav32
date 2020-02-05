<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParProductosParametrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__productos__parametros', function (Blueprint $table) {
            $table->increments('idprodparam');
            $table->integer('idfactor')->unsigned();
            $table->integer('idf')->unsigned();
            $table->float('valorparametro')->default(0);  
            $table->integer('orden')->default(1); 
            $table->timestamps(); 
            $table->foreign('idfactor')->references('idfactor')->on('par__productos__factores');
            $table->foreign('idf')->references('idf')->on('par__factors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__productos__parametros');
    }
}
