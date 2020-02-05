<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRrhPresentadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrh__presentados', function (Blueprint $table) {
            $table->increments('idpresentado');
            $table->integer('idempleado');
            $table->integer('iddocumento');
            $table->date('fechapres')->nullable();
            $table->tinyInteger('idformato')->nullable()->comment('1:original, 2.copia legal, 3:fot simple');
            $table->string('obs',100)->nullable();
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
        Schema::dropIfExists('rrh__presentados');
    }
}
