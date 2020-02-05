<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAportesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW apo__aportesview AS (

            SELECT `apo__aportes`.`idaporte`,
                                        `apo__aportes`.`fechaaporte`,
                                        `socios`.`numpapeleta`,
                                        `apo__aportes`.`idlote`,
                                        `apo__aportes`.`aporte`,
                                        `apo__tipoaportes`.`descripcion`,
                                        `apo__aportes`.`metodoaporte`,
                                        `apo__aportes`.`idperfilcuentamaestro`,
                                        apo__aportes.idtipoaporte,
                                        
                                        apo__aportes.aporte as sumatotal
                                        
                                from `apo__aportes` 
                                inner join `apo__tipoaportes` 
                                on `apo__aportes`.`idtipoaporte` = `apo__tipoaportes`.`idtipoaporte` 
                                inner join `socios` 
                                on `apo__aportes`.`numpapeleta` = `socios`.`numpapeleta` 
                                inner join `con__perfilcuentamaestros` 
                                on `apo__aportes`.`idperfilcuentamaestro` = `con__perfilcuentamaestros`.`idperfilcuentamaestro`  
                                JOIN apo__idsaportes a 
                                ON apo__aportes.idaporte=a.ididsaportes AND apo__aportes.activo=1 AND a.tipodevolucion=0
                                                     
                                group by `apo__aportes`.`idaporte` 
                                order by `apo__aportes`.`fechaaporte`)");

        DB::statement("CREATE OR REPLACE VIEW apo__aportesview_devolucion
        AS (

           SELECT `apo__aportes`.`idaporte`,
                                       `apo__aportes`.`fechaaporte`,
                                       `socios`.`numpapeleta`,
                                       `apo__aportes`.`idlote`,
                                       `apo__aportes`.`aporte`,
                                       `apo__tipoaportes`.`descripcion`,
                                       `apo__aportes`.`metodoaporte`,
                                       `apo__aportes`.`idperfilcuentamaestro`,
                                       `apo__aportes`.`idtipoaporte`,
                                       a.tipodevolucion,
                                       `apo__aportes`.`aporte` as sumatotal
                                        
                                       
                               from `apo__aportes` 
                               inner join `apo__tipoaportes` 
                               on `apo__aportes`.`idtipoaporte` = `apo__tipoaportes`.`idtipoaporte` 
                               inner join `socios` 
                               on `apo__aportes`.`numpapeleta` = `socios`.`numpapeleta` 
                               inner join `con__perfilcuentamaestros` 
                               on `apo__aportes`.`idperfilcuentamaestro` = `con__perfilcuentamaestros`.`idperfilcuentamaestro`  
                               JOIN `apo__idsaportes` a 
                               ON `apo__aportes`.`idaporte`=a.ididsaportes AND apo__aportes.activo=1 AND a.tipodevolucion<>0
                                                    
                               group by `apo__aportes`.`idaporte` 
                               order by `apo__aportes`.`fechaaporte`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS apo__aportesview');
    }
}
