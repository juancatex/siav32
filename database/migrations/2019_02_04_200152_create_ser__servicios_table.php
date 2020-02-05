<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if (!Schema::hasTable('ser__servicios')) {
        Schema::create('ser__servicios', function (Blueprint $table) {
            $table->increments('idservicio');
            $table->string('nomservicio',30);
            $table->string('codservicio',5);
            $table->string('descripcion',50)->nullable();
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
        //}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ser__servicios');
    }
}
