<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerAsignacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser__asignacions', function (Blueprint $table) {
            $table->bigincrements('idasignacion');
            $table->integer('idcliente');
            $table->tinyInteger('tipocliente')->default(1)->comment('1->socio,2->civil');
            $table->boolean('estado')->default(0)->comment('0->desocupado,1->ocupado pagado,2->ocupado sin pagar,3->reservado');
            $table->integer('idambiente');
            $table->string('nrasignacion',10)->nullable();
            $table->date('fechasolicitud')->nullable();
            $table->string('mesboleta',10)->nullable();
            $table->integer('ocupantes')->nullable();
            $table->string('iddocumentos',20)->nullable();
            $table->string('idimplementos',20)->nullable();
            $table->date('fechareserva')->nullable()->comment('fecha de la reserva, para servicios eventuales');
            $table->time('horainicioreserva')->nullable()->comment('hora de la inicio de la reserva');
            $table->time('horafinreserva')->nullable()->comment('hora fin de la reserva');
            $table->dateTime('fechaentrada')->nullable();
            $table->dateTime('fechasalida')->nullable();
            $table->date('fechadefuncion')->nullable();
            $table->integer('idresponsable')->nullable();
            $table->string('obs1')->nullable();            
            $table->integer('idrepresentante')->nullable();
            $table->boolean('activo')->default(1)->comment('0->eliminado,1->activo');
            $table->integer('idfactura')->nullable();
            $table->integer('idasientomaestro')->nullable();
            $table->float('monto',15,2);
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
        Schema::dropIfExists('ser__asignacions');
    }
}
