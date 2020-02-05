<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConContabilidadProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setcompleto=" CREATE or replace PROCEDURE actualizar_perfilmaestro(IN idmaestro INT) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN
                           
                        SET @totald=0;
                        SET @totalh=0;
                        
                        SELECT SUM(porcentaje) FROM con__perfilcuentadetalles WHERE tipocargo='h' and idperfilcuentamaestro=idmaestro INTO @totalh;
                        SELECT SUM(porcentaje) FROM con__perfilcuentadetalles WHERE tipocargo='d' and idperfilcuentamaestro=idmaestro INTO @totald;
                        
                        
                        IF(@totalh<100 OR @totald<100 OR @totalh IS NULL OR @totald IS NULL) THEN
                            UPDATE con__perfilcuentamaestros SET completo=0 where idperfilcuentamaestro=idmaestro;
                        ELSE
                            UPDATE con__perfilcuentamaestros SET completo=1 where idperfilcuentamaestro=idmaestro;
                        END IF;
                    END";
        
        DB::unprepared($setcompleto);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS actualizar_perfilmaestro;');
    }
}
