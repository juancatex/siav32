<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerEstablecimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__establecimientos', function (Blueprint $table) {
            $table->increments('idestablecimiento');
            $table->integer('idfilial');
            $table->integer('idservicio');
            $table->string('nomestablecimiento',100);
            $table->string('direccion',100)->nullable();
            $table->string('telefonos',20)->nullable();
            $table->string('descripcion',100)->nullable();
            $table->tinyInteger('tipogrupos')->nullable();
            $table->string('cantgrupos',30)->nullable();
            //$table->string('configuracion',20)->nullable(); 
            $table->string('idimplementos',30)->default(0); 
            $table->string('iddocumentos',30)->default(0);
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
        Schema::dropIfExists('ser__establecimientos');
    }
}
