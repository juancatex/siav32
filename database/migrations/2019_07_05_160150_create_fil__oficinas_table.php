<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilOficinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fil__oficinas', function (Blueprint $table) {
            $table->increments('idoficina');
            $table->integer('idfilial');
            $table->integer('idunidad');
            $table->string('codoficina',2);
            $table->string('nomoficina',50);
            $table->integer('idresponsable')->nullable();
            $table->string('tiporesponsable',1)->comment('s-socio,c-civil')->nullable();
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
        Schema::dropIfExists('fil__oficinas');
    }
}
