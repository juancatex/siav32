<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__modulos', function (Blueprint $table) {
            $table->tinyIncrements('idmodulo');
            $table->string('nommodulo',40);
            $table->boolean('activo')->default(1);
            $table->boolean('contabilizable')->default(0);
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
        Schema::dropIfExists('par__modulos');
    }
}
