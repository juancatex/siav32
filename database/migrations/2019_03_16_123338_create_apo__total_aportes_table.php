<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoTotalAportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apo__total_aportes', function (Blueprint $table) {
            $table->increments('idtotalaporte');
            $table->string('numpapeleta',10)->index();
            $table->integer('cantobligados')->unisgned()->nullable();
            $table->decimal('totalobligados',10,2)->nullable();
            $table->date('fecha_primeraporte_obligados')->nullable();
            $table->date('fecha_ultimoaporte_obligados')->nullable();
            $table->boolean('obligatorios')->default(0);
            $table->integer('cantjubilacion')->unisgned()->nullable()->default(0);
            $table->decimal('totaljubilacion',10,2)->nullable()->default(0);
            $table->date('fecha_primeraporte_jubilacion')->nullable();
            $table->date('fecha_ultimoaporte_jubilacion')->nullable();
            $table->boolean('jubilados')->default(0);
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
        Schema::dropIfExists('apo__total_aportes');
    }
}
