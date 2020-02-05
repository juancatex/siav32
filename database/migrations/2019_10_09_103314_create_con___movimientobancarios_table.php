<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConMovimientobancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con___movimientobancarios', function (Blueprint $table) {
            $table->Increments('idmovimiento');
            $table->integer('idasientomaestro')->unsigned()->nullable();
            $table->integer('idcuenta')->unsigned()->comment('id de la cuenta contable para su seguimiento');
            $table->string('nomportador',100)->comment('nombre del portador al que ha sido entregado el cheque')->nullable();
            $table->string('numdocumento',50);
            $table->string('detalledocumento')->nullable();
            $table->float('importe',15,2);
            $table->string('tipocargo',1)->comment('d->debe,h->haber');
            $table->tinyInteger('estado')->unsigned()->default(0)->comment('0->cheques girados pero no cobrados o depositos no efectuados,1->cheques cobrados o depsitos efectuados,2->cheques entregados al portador');
            $table->dateTime('fecha_entrega')->nullable();
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
        Schema::dropIfExists('con___movimientobancarios');
    }
}
