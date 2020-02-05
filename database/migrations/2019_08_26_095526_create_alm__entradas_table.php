<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlmEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alm__entradas', function (Blueprint $table) {
            $table->increments('identrada');
            $table->integer('idcuenta');
            $table->integer('idsuministro');
            $table->integer('nrlote');
            $table->integer('cantentrada');
            $table->date('fechaentrada');
            $table->integer('usado')->default(0);
            $table->integer('idproveedor')->nullable();
            $table->string('nrfactura',10)->nullable();
            $table->float('costo');
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
        Schema::dropIfExists('alm__entradas');
    }
}
