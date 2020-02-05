<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRrhReferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__referencias', function (Blueprint $table) {
            $table->increments('idreferencia');
            $table->integer('idempleado');
            $table->tinyInteger('tiporef')->comment('1-personal,2-laboral');
            $table->string('nombre',50);
            $table->string('celular',10)->nullable();
            $table->string('telefono',10)->nullable();
            $table->string('direccion',50)->nullable();
            $table->string('relacion',50)->nullable();
            $table->string('cargo',30)->nullable();
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
        Schema::dropIfExists('rrh__referencias');
    }
}
