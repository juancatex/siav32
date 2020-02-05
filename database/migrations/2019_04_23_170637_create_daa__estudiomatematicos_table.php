<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaaEstudiomatematicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daa__estudiomatematicos', function (Blueprint $table) {
            $table->increments('idestudiomatematico');
            $table->string('nomestudiomatematico');
            $table->string('formula');
            $table->integer('anioinicio');
            $table->integer('aniofinal');
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
        Schema::dropIfExists('daa__estudiomatematicos');
    }
}
