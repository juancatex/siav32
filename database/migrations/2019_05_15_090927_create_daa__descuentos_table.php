<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaaDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daa__descuentos', function (Blueprint $table) {
            $table->increments('iddescuentos');
            $table->integer('iddevolucion')->unsigned();
            $table->float('emergencia');
            $table->float('regular');
            $table->float('servicio');
            $table->float('juridica');
            $table->float('daaro');
            $table->float('retencion');
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
        Schema::dropIfExists('daa__descuentos');
    }
}
