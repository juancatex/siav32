<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActGruposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('act__grupos', function (Blueprint $table) {
            $table->increments('idgrupo');
            $table->string('codgrupo',2);
            $table->string('nomgrupo',50);
            $table->tinyInteger('vida')->nullable();
            $table->integer('idcuentaconta')->nullable();
            $table->integer('idcuentadepre')->nullable();
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
        Schema::dropIfExists('act__grupos');
    }
}
