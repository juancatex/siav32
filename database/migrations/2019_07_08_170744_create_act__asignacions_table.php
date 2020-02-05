<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActAsignacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('act__asignacions', function (Blueprint $table) {
            $table->increments('idasignacion');
            $table->integer('idactivo');
            $table->integer('idresponsable');
            $table->string('tiporesponsable',1);
            $table->date('fechaini');
            $table->date('fechafin')->nullable();
            $table->integer('estadoini')->comment('1-bueno, 2-regular, 3-malo');
            $table->integer('estadofin')->nullable();
            $table->string('obs',100)->nullable();
            $table->boolean('vigente')->default(0);
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
        Schema::dropIfExists('act__asignacions');
    }
}
