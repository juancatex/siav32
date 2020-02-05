<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtoObjgestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pto__objgestions', function (Blueprint $table) {
            $table->increments('idobjgestion');          
            $table->integer('idestructuraprog')->unsigned();  
            $table->integer('idpei')->unsigned();
            $table->string('objgestion');
            $table->string('resultado');
            $table->string('idusuario');
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
        Schema::dropIfExists('pto__objgestions');
    }
}
