<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParProductosCriteriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__productos__criterios', function (Blueprint $table) {
            $table->increments('idcriterio');
            $table->integer('idprodparam')->unsigned();
            $table->integer('valornumerico');
            $table->integer('valorporcentual')->default(0);
            $table->integer('idaux')->default(0);
            $table->timestamps();
            $table->foreign('idprodparam')->references('idprodparam')->on('par__productos__parametros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('par__productos__criterios');
    }
}
