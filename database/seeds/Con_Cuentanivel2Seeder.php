<?php

use Illuminate\Database\Seeder;

class Con_Cuentanivel2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__cuentanivel2')->insert(['codn1'=>'1','codn2'=>'11','valorn2'=>1,'nomcuentan2'=>'Activo Corriente']);
        DB::table('con__cuentanivel2')->insert(['codn1'=>'1','codn2'=>'12','valorn2'=>2,'nomcuentan2'=>'Activo No Corriente']);
        DB::table('con__cuentanivel2')->insert(['codn1'=>'2','codn2'=>'21','valorn2'=>1,'nomcuentan2'=>'Pasivo Corriente']);
        DB::table('con__cuentanivel2')->insert(['codn1'=>'2','codn2'=>'22','valorn2'=>2,'nomcuentan2'=>'Pasivo No Corriente']);
        DB::table('con__cuentanivel2')->insert(['codn1'=>'3','codn2'=>'31','valorn2'=>1,'nomcuentan2'=>'Patrimonio Institucional']);
        DB::table('con__cuentanivel2')->insert(['codn1'=>'5','codn2'=>'51','valorn2'=>1,'nomcuentan2'=>'Ingresos Financieros']);
        DB::table('con__cuentanivel2')->insert(['codn1'=>'5','codn2'=>'52','valorn2'=>2,'nomcuentan2'=>'Ingresos Operativos']);
        DB::table('con__cuentanivel2')->insert(['codn1'=>'5','codn2'=>'53','valorn2'=>3,'nomcuentan2'=>'Otros Ingresos']);
        
    }

}
