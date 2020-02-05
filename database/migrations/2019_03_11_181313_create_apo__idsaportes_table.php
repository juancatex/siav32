<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoIdsaportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apo__idsaportes', function (Blueprint $table) {
            $table->increments('ididsaportes');
            $table->integer('idaporte');
            $table->string('numpapeleta',10)->index();
            $table->tinyInteger('tipodevolucion')->default(0)->comment('0->no devolucion,1->obligatoria->2->jubilacion,3->excedente');;
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
        Schema::dropIfExists('apo__idsaportes');
    }
}
