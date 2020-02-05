<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConCuentanivel3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__cuentanivel3', function (Blueprint $table) {
            $table->increments('idcuentanivel3');
            $table->string('codn2',10);
            $table->string('codn3',10);
            $table->string('valorn3',2);
            $table->string('nomcuentan3',100);
            $table->boolean('activo')->default(1);
            $table->timestamps();
            
            $table->unique(['codn3']);
            $table->foreign('codn2')->references('codn2')->on('con__cuentanivel2');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('con__cuentanivel3');
    }
}
