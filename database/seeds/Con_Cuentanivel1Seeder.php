<?php

use Illuminate\Database\Seeder;

class Con_Cuentanivel1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__cuentanivel1')->insert(['codn1'=>'1','nomcuentan1'=>'Activo']);
        DB::table('con__cuentanivel1')->insert(['codn1'=>'2','nomcuentan1'=>'Pasivo']);
        DB::table('con__cuentanivel1')->insert(['codn1'=>'3','nomcuentan1'=>'Patrimonio']);
        DB::table('con__cuentanivel1')->insert(['codn1'=>'5','nomcuentan1'=>'Ingresos']);
    }
}
