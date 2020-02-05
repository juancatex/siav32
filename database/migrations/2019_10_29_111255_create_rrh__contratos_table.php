<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRrhContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__contratos', function (Blueprint $table) {
            $table->increments('idcontrato');
            $table->integer('idempleado');            
            $table->string('nrcontrato',10)->nullable();
            $table->tinyInteger('idtipocontrato')->nullable(); 
            $table->date('inicontrato')->nullable();
            $table->date('fincontrato')->nullable();
            $table->decimal('salario',6,2)->default(0);
            $table->string('obs',100)->nullable();
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
        Schema::dropIfExists('rrh__contratos');
    }
}
