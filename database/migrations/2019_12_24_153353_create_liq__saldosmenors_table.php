<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiqSaldosmenorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liq__saldosmenors', function (Blueprint $table) {
            $table->increments('idliquidarsaldo');
            $table->integer('idproducto');
            $table->integer('idasientomaestro');
            $table->string('nro_prestamo',15);
            $table->string('numpapeleta',15);
            $table->string('nombre',200);
            $table->decimal('saldo',8,2);
            $table->string('moneda',20);
            $table->date('fec_liquidacion');
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
        Schema::dropIfExists('liq__saldosmenors');
    }
}
