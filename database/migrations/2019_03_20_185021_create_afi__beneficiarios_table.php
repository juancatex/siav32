<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfiBeneficiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afi__beneficiarios', function (Blueprint $table) {
            $table->increments('idbeneficiario');
            $table->integer('idsocio');
            $table->string('parentesco',10);
            $table->string('nombre',20);
            $table->string('apaterno',20);
            $table->string('amaterno',20)->nullable();
            $table->date('fechanac')->nullable();
            $table->string('sexo',1);
            $table->string('ci',10)->nullable();
            $table->integer('iddepartamento');
            $table->string('telcelular')->nullable();
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
        Schema::dropIfExists('afi__beneficiarios');
    }
}
