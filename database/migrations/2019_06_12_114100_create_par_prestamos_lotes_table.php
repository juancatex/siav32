<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParPrestamosLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__prestamos__lotes', function (Blueprint $table) {
            $table->increments('idlote');
            $table->integer('min')->default(0);
            $table->integer('max')->default(0);
            $table->integer('close')->default(0);
            $table->dateTime('fecha')->nullable();
            $table->integer('activo')->default(1);

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
        Schema::dropIfExists('par__prestamos__lotes');
    }
}
