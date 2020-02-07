<?php

use Illuminate\Database\Seeder;

class Con_PerfilcuentadetallesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'1',
            'idcuenta'=>'1',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);

        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'1',
            'idcuenta'=>'2',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);

        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'2',
            'idcuenta'=>'1',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);

        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'2',
            'idcuenta'=>'3',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);

        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'3',
            'idcuenta'=>'2',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);

        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'3',
            'idcuenta'=>'3',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
      

    }

} 