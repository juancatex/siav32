<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConCuentanivel2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__cuentanivel2', function (Blueprint $table) {
            $table->increments('idcuentanivel2');
            $table->string('codn1',10);
            $table->string('codn2',10);
            $table->string('valorn2',2);
            $table->string('nomcuentan2',100);
            $table->boolean('activo')->default(1);
            $table->timestamps();
           
            $table->unique(['codn2']);
            $table->foreign('codn1')->references('codn1')->on('con__cuentanivel1');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('con__cuentanivel2');
    }
}
