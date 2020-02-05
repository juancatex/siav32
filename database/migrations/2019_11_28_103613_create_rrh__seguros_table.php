<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRrhSegurosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__seguros', function (Blueprint $table) {
            $table->increments('idseguro');
            $table->tinyInteger('tipo')->comment('1:pensiones, 2:salud');
            $table->string('sigla',10);
            $table->string('nomseguro',50)->nullable();
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
        Schema::dropIfExists('rrh__seguros');
    }
}
