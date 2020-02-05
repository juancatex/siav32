<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm__permisos', function (Blueprint $table) {
            $table->increments('idpermiso');
            $table->integer('idventanamodulo')->unsigned();
            $table->string('nompermiso');
            $table->string('descripcion')->nullable();
            $table->string('metodo')->nullable();
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
        Schema::dropIfExists('adm__permisos');
    }
}
