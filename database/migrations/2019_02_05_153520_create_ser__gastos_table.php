<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__gastos', function (Blueprint $table) {
            $table->increments('idgasto');
            $table->integer('idfilial');
            $table->integer('idasientomaestro')->default(0);
            $table->string('nrdocumento',10)->nullable();
            $table->string('detalle',100)->nullable();
            $table->date('fechagasto');
            $table->decimal('importe',6,2);
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
        Schema::dropIfExists('ser__gastos');
    }
}
