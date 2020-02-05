<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConCuentanivel4Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
        Schema::create('con__cuentanivel4', function (Blueprint $table) {
            $table->increments('idcuentanivel4');
            $table->string('codn3',10);
            $table->string('codn4',10);
            $table->string('valorn4',2);
            $table->string('nomcuentan4',100);
            $table->boolean('activo')->default(1);
            $table->timestamps();
            //$table->unique(['valorn']);
            $table->unique(['codn4']);

            $table->foreign('codn3')->references('codn3')->on('con__cuentanivel3');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('con__cuentanivel4');
    }
}
