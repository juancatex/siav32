<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlmGruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alm__grupos', function (Blueprint $table) {
            $table->increments('idgrupo');
            $table->string('codgrupo',10);
            $table->string('nomgrupo',50);
            $table->integer('idcuentaconta')->nullable();
            $table->integer('idcuentagasto')->nullable();
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
        Schema::dropIfExists('alm__grupos');
    }
}
