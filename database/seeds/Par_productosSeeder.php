<?php

use Illuminate\Database\Seeder;

class Par_productosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__productos')->insert(['idescala'=>'2','nomproducto'=>'PRESTAMOS DE EMERGENCIA MN','moneda'=>'1','plazominimo'=>'12','plazomaximo'=>'12','codproducto'=>'G','tasa'=>'24','idfactor'=>'1','cancelarprestamos'=>'0','max_prestamos'=>'0','lote'=>'1','blockauto'=>'0','garantes'=>'0','cancelarprestamos'=>'0','tasa'=>'24.00','plazominimo'=>'1','plazomaximo'=>'12','activo'=>'2']);
        DB::table('par__productos')->insert(['idescala'=>'3','nomproducto'=>'PRESTAMOS REGULARES NUEVOS','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'F','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'2','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'12','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'3','nomproducto'=>'PRESTAMOS REGULARES NUEVOS','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'H','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'2','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'12','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'4','nomproducto'=>'PRESTAMOS REGULARES SIN GARANTES','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'F-SGAR','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'0','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'120','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'4','nomproducto'=>'PRESTAMOS REGULARES SIN GARANTES','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'F-REF','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'0','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'120','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'4','nomproducto'=>'PRESTAMOS REGULARES SIN GARANTES','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'F-INI','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'0','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'120','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'4','nomproducto'=>'PRESTAMOS REGULARES SIN GARANTES','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'F-EJE-TIT','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'0','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'120','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'4','nomproducto'=>'PRESTAMOS REGULARES SIN GARANTES','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'F-REP','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'0','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'120','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'4','nomproducto'=>'PRESTAMOS REGULARES SIN GARANTES','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'F-ESP','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'0','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'120','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'4','nomproducto'=>'PRESTAMOS REGULARES SIN GARANTES','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'F-EJE-GAR','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'0','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'120','activo'=>'2']); 
        DB::table('par__productos')->insert(['idescala'=>'4','nomproducto'=>'PRESTAMOS REGULARES SIN GARANTES','moneda'=>'2','plazominimo'=>'1','plazomaximo'=>'120','codproducto'=>'OBS00','tasa'=>'9.6','idfactor'=>'2','cancelarprestamos'=>'1','max_prestamos'=>'1','lote'=>'0','blockauto'=>'1','garantes'=>'0','cancelarprestamos'=>'1','tasa'=>'9.60','plazominimo'=>'1','plazomaximo'=>'120','activo'=>'2']); 

    }
}
