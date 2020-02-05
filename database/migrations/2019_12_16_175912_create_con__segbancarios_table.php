<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConSegbancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__segbancarios', function (Blueprint $table) {
            $table->increments('idsegbancario');
            $table->integer('idcuenta')->unsigned();
            $table->date('fecha_movimiento')->comment('fecha del movimiento bancario en extracto');
            $table->string('concepto',200);
            $table->integer('iddepartamento');
            $table->integer('num_operacion')->unsigned();
            $table->float('monto',15,2);
            $table->tinyInteger('tipomovimiento')->comment('1->creditos no registrados por la empresa, 2->debitos no registrados por la empresa,3->debitos no registrados por el banco');
            $table->boolean('activo')->default(1)->comment('1->registros no regularizados, 0->registros regularizados');
            $table->integer('idasientomaestro')->nullable()->comment('para asociar el asientomaestro con el movimiento si es necesario');
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
        Schema::dropIfExists('con__segbancarios');
    }
}
