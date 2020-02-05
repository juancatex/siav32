<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerCivilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__civils', function (Blueprint $table) {
            $table->increments('idcivil');
            $table->string('nombre',20);
            $table->string('apaterno',20);
            $table->string('amaterno',20);
            $table->date('fechanac')->nullable();
            $table->string('sexo',1)->nullable();
            $table->string('ci',10);
            $table->integer('iddepartamento');
            $table->string('telcelular',10)->nullable();
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
        Schema::dropIfExists('ser__civils');
    }
}
