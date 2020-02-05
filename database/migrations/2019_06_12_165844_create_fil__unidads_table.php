<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilUnidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fil__unidads', function (Blueprint $table) {
            $table->increments('idunidad');
            $table->string('codunidad',2)->comment('nivel jerárquico');
            $table->string('abrev',5)->nullable();
            $table->string('nomunidad',60);
            $table->string('nomcargo',60)->nullable();
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
        Schema::dropIfExists('fil__unidads');
    }
}
