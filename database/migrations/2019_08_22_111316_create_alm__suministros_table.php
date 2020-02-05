<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlmSuministrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alm__suministros', function (Blueprint $table) {
            $table->increments('idsuministro');
            $table->integer('idgrupo');
            $table->string('codsuministro',10);
            $table->string('nomsuministro',60);
            $table->tinyInteger('idmedida')->nullable();
            $table->integer('maximo')->nullable();
            $table->integer('minimo')->nullable();
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
        Schema::dropIfExists('alm__suministros');
    }
}
