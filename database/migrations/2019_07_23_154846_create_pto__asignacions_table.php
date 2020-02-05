<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtoAsignacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pto__asignacions', function (Blueprint $table) {
            $table->increments('idasignacion');
            $table->integer('idpei')->unsigned();            
            $table->integer('idestructuraprog')->unsigned();
            $table->integer('idobjgestion')->unsigned();
            $table->string('partidas');
            $table->string('reparticiones');                        
            $table->text('observacion');
            $table->string('iduser')->default('user');
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
        Schema::dropIfExists('pto__asignacions');
    }
}
