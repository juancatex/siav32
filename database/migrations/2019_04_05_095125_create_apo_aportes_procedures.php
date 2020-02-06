<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoAportesProcedures extends Migration
{
    public function up()
    {     
             /* procedimiento que carga aportes faltantes en apo__observados despues de carga ascii*/
            $setobservados = "CREATE or replace PROCEDURE insertar_aportes_observados (IN fechaap DATE)
                BEGIN
            DECLARE mes varchar(2);
            DECLARE anio varchar(4);
            SET mes=MONTH(fechaap);
            SET anio=YEAR(fechaap);
            INSERT INTO apo__observados (numpapeleta,fecha_noaporte,created_at)
            select a.numpapeleta,fechaap ,fechaap FROM socios a 
            JOIN apo__total_aportes b 
            on a.numpapeleta=b.numpapeleta
            AND (b.obligatorios=0 OR b.jubilados=0)
            WHERE a.idestdaaro=0 AND a.numpapeleta NOT IN(SELECT numpapeleta FROM apo__aportes  WHERE MONTH(fechaaporte)= mes AND YEAR(fechaaporte)=anio);
            END";
            DB::unprepared($setobservados);

            $setsociosobservados=" CREATE or replace PROCEDURE actualizar_socio_observado(IN fecha_ap DATE)
                BEGIN
                    update socios a
                    inner join apo__observados b on a.numpapeleta = b.numpapeleta
                    set a.idestaportes=2
                    WHERE b.fecha_noaporte=fecha_ap;
                END";
            DB::unprepared($setsociosobservados);

            $agregarasientomaestro="CREATE or replace PROCEDURE agregarasientomaestro(IN idmaestro INT UNSIGNED, IN lote INT UNSIGNED, IN aporte INT UNSIGNED)
                BEGIN
                        IF aporte=0 THEN
                            UPDATE apo__aportes set idasientomaestro=idmaestro where apo__aportes.idlote=lote;
                        ELSE
                            UPDATE apo__aportes set idasientomaestro=idmaestro where apo__aportes.idaporte=aporte;
                        END IF;
                END";
            
            DB::unprepared($agregarasientomaestro);

           $asientomaestrodebito="CREATE or replace PROCEDURE `asientomaestrodebitoaporte`(IN `idmaestro` INT, IN `lote` INT, IN `aporte` INT)
           BEGIN
                IF aporte=0 THEN
                    UPDATE apo__debitos set idasientomaestro=idmaestro where apo__debitos.idlotecargaascii=lote;
                ELSE
                    UPDATE apo__debitos set idasientomaestro=idmaestro where apo__debitos.idaporte=aporte;
                END IF;
            END";
            DB::unprepared($asientomaestrodebito);

            $validadoconta_aportes="CREATE or replace PROCEDURE `validadoconta_aportes`(IN `idmaestro` INT)
                BEGIN
                    UPDATE apo__aportes set validadoconta=1 where apo__aportes.idasientomaestro=idmaestro;
                END";
                
            DB::unprepared($validadoconta_aportes);

            $detalles_socio_empleado="CREATE or replace PROCEDURE `detalles_socio_empleado`(IN `tiposubcuenta` INT, IN `idmaestro` INT)
    
            BEGIN
            if tiposubcuenta=1 THEN
                select 	sum(debe) as sdebe,
                            sum(haber) as shaber, 
                            concat(apaterno,' ',amaterno,' ',nombre) as nombres,
                            numpapeleta,
                            numdocumento
            from con__asientodetalles a join con__asientomaestros b
            on a.idasientomaestro=b.idasientomaestro 
            join socios c
            on a.subcuenta=c.numpapeleta
            where b.idagrupacion=idmaestro or a.idasientomaestro=idmaestro
            GROUP BY a.idasientomaestro
            ORDER BY fechahora_desembolso DESC;
            ELSE
            select 	sum(debe) as sdebe,
                            sum(haber) as shaber, 
                            concat(apaterno,' ',amaterno,' ',nombre) as nombres,
                            '-' as numpapeleta,
                            numdocumento
            from con__asientodetalles a join con__asientomaestros b
            on a.idasientomaestro=b.idasientomaestro 
            join rrh__empleados c
            on a.subcuenta=c.idempleado
            where b.idagrupacion=idmaestro or a.idasientomaestro=idmaestro
            GROUP BY a.idasientomaestro
            ORDER BY fechahora_desembolso DESC;

            end IF;
            END";
            DB::unprepared($detalles_socio_empleado);
            
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insertar_aportes_observados;');
        DB::unprepared('DROP PROCEDURE IF EXISTS actualizar_socio_observado;');
        DB::unprepared('DROP PROCEDURE IF EXISTS agregarasientomaestro;');
        DB::unprepared('DROP PROCEDURE IF EXISTS asientomaestrodebitoaporte;');
        DB::unprepared('DROP PROCEDURE IF EXISTS validadoconta_aportes;');
        DB::unprepared('DROP PROCEDURE IF EXISTS detalles_socio_empleado;');
        
    }
}
