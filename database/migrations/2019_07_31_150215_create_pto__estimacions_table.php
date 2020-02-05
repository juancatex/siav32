<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePtoEstimacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pto__estimacions', function (Blueprint $table) {
            $table->increments('idestimacion');
            $table->integer('idasignacion');
            $table->integer('idoficina')->unsigned();
            $table->integer('idmunicipio')->unsigned();
            $table->text('descripcion');
            $table->float('monto');
            $table->string('iduser',20);
            $table->boolean('activo');
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
        Schema::dropIfExists('pto__estimacions');
    }
}
