<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRrhSueldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__sueldos', function (Blueprint $table) {
            $table->increments('idsueldo');
            $table->integer('idplanilla');
            $table->integer('idempleado');
            $table->decimal('basico',6,2);
            $table->tinyInteger('antig');
            $table->decimal('bonoantig',6,2)->default(0);
            $table->tinyInteger('diastrab');
            $table->decimal('tganado',6,2)->default(0);
            $table->decimal('afp',6,2)->default(0);
            $table->decimal('atrasos',6,2);
            $table->decimal('singoce',6,2);
            $table->decimal('tdescuento',6,2)->default(0);
            $table->decimal('liquido',6,2);
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
        Schema::dropIfExists('rrh__sueldos');
    }
}
