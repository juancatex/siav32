<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtoEstructuraprogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pto__estructuraprogs', function (Blueprint $table) {
            $table->increments('idestructuraprog');
            $table->integer('idpei')->unsigned();
            $table->integer('idobjestrategico')->unsigned();
            $table->boolean('activo')->default(1);
            $table->string('iduser',50)->default(1);
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
        Schema::dropIfExists('pto__estructuraprogs');
    }
}
