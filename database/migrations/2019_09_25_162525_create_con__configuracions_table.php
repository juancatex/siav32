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
            $table->string('codigo',10); 
            $table->string('descripcion'); 
            $table->integer('valor')->default(0);
            $table->string('descripcion2')->comment('descripcion de un valor complementario vinculado al valor uno ej. valor->idcuenta debito fiscal,valor2->%de debito')->nullable(); 
            $table->integer('valor2')->default(0);
            $table->tinyInteger('tipoconfiguracion')->comment('1->libro de compras,2->libro de ventas,3->conciliacion bancaria,4->cargo de cuenta,5->relacion de la cuenta con la fuera para el perfil de carga de ascii a una cuenta por fuerza,6->subcuentageneral ascinalss');
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
