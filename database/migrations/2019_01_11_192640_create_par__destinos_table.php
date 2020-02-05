<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParDestinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__destinos', function (Blueprint $table) {
            $table->increments('iddestino');
            $table->integer('idfuerza');
            $table->string('coddestino',10);
            $table->string('nomdestino',50);
            $table->integer('idmunicipio')->nullable();
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
        Schema::dropIfExists('par__destinos');
    }
}
