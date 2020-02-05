<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmRolePermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm__rolepermisos', function (Blueprint $table) {
            $table->increments('idrolepermiso');
            $table->integer('idrole')->unsigned();
            $table->integer('idpermiso')->unsigned();
            $table->string('permisos',250);
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
        Schema::dropIfExists('adm__rolepermisos');
    }
}
