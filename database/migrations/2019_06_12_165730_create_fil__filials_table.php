<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilFilialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fil__filials', function (Blueprint $table) {
            $table->increments('idfilial');
            $table->integer('iddepartamento');
            $table->string('codfilial',3);
            $table->integer('idmunicipio');
            $table->string('sigla',2)->nullable();
            $table->string('direccion',70)->nullable();
            $table->string('telfijo',10)->nullable();
            $table->string('telcelular',10)->nullable();
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
        Schema::dropIfExists('fil__filials');
    }
}
