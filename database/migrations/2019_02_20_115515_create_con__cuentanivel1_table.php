<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConCuentanivel1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__cuentanivel1', function (Blueprint $table) {
            $table->increments('idcuentanivel1');
            $table->string('codn1',10);
            $table->string('nomcuentan1',100);
            $table->boolean('activo')->default(1);
            $table->unique(['codn1']);
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
        Schema::dropIfExists('con__cuentanivel1');
    }
}
