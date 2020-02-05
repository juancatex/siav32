<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilDirectivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fil__directivos', function (Blueprint $table) {
            $table->increments('iddirectivo');
            $table->integer('idcargo');
            $table->integer('idfilial');
            $table->integer('idsocio');
            $table->date('fechaini')->nullable();
            $table->date('fechafin')->nullable();
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
        Schema::dropIfExists('fil__directivos');
    }
}
