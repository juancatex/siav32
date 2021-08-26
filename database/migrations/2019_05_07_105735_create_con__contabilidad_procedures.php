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

        //para que el evento funcione correctamente se debe comprobar que el even_scheduler este en on
        // set global event_scheduler=on  por consola o en el phpmyadmin
        
        DB::unprepared("CREATE OR REPLACE EVENT calculardias
                            ON SCHEDULE EVERY 1 DAY STARTS '2021-06-08 02:00:00' ON COMPLETION PRESERVE 
                            DO
                                BEGIN
                            SET @hoy = '';
                            SELECT DAYNAME(NOW()) INTO @hoy;
                        
                            IF @hoy!='Sunday' AND @hoy!='Saturday' THEN
                                UPDATE glo__solicitud_cargo_cuentas set cant_dias=cant_dias + 1 where activo=1;
                            END IF;
                        END");
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
