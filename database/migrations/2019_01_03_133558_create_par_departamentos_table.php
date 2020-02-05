<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par_departamentos', function (Blueprint $table) {
            $table->increments('iddepartamento');
            $table->string('nomdepartamento',50);
            $table->string('abrvdep',10)->nullable();
            $table->string('abrvdep2',10)->nullable();
            $table->integer('orden');
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
        Schema::dropIfExists('par_departamentos');
    }
}
