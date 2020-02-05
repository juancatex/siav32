<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConCuentanivel5Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__cuentanivel5', function (Blueprint $table) {
            $table->increments('idcuentanivel5');
            $table->string('codn4',10);
            $table->string('codn5',10);
            $table->string('valorn5',2);
            $table->string('nomcuentan5',100);
            $table->boolean('activo')->default(1);
            $table->timestamps();
            $table->unique(['codn5']);

            $table->foreign('codn4')->references('codn4')->on('con__cuentanivel4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('con__cuentanivel5');
    }
}
