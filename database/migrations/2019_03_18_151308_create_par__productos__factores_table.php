<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParProductosFactoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('par__productos__factores', function (Blueprint $table) {
            $table->increments('idfactor');
            $table->string('nombrefactor');
            $table->string('descripcion');
            $table->float('refvalor')->default(0);
            $table->float('aprobacion')->default(0);
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
        Schema::dropIfExists('par__productos__factores');
    }
}
