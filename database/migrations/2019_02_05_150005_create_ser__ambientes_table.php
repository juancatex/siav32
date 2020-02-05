<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerAmbientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('ser__ambientes', function (Blueprint $table) {
            $table->increments('idambiente');
            $table->integer('idestablecimiento');
            $table->string('codambiente',10);
            $table->tinyInteger('piso')->nullable();
            $table->string('tipo',30)->nullable();
            $table->boolean('ocupado')->default(0);
            $table->tinyInteger('capacidad')->nullable()->default(0);
            $table->integer('garantia')->nullable();
            $table->integer('tarifasocio')->nullable();
            $table->integer('tarifareal')->nullable();
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
        Schema::dropIfExists('ser__ambientes');
    }
}
