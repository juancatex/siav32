<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConConfiguracionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__configuracions', function (Blueprint $table) {
            $table->increments('idconconfig');
            $table->string('codigo'); 
            $table->string('descripcion'); 
            $table->integer('valor')->default(0);  
            $table->tinyInteger('tipoconfiguracion')->comment('1->libro de compras,2->libro de ventas,3->conciliacion bancaria,4->cargo de cuenta');
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
        Schema::dropIfExists('con__configuracions');
    }
}
