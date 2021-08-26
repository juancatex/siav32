<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConFirmaautorizadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('con__firmaautorizadas', function (Blueprint $table) {
            $table->Increments('idfirmaautorizada');
            $table->integer('idpersona')->unsigned();
            $table->tinyInteger('tipo_persona')->unsigned()->comment('1->sociodirectivo,2->personal');
            $table->boolean('activo')->default(true);
            $table->tinyInteger('orden')->comment('orden de aparicion en el comprobante');
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
        Schema::dropIfExists('con__firmaautorizadas');
    }
}
