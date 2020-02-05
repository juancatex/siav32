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
///////////////////////////////Desembolso Prestamos Regulares////////////////////////////////////////////
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'4',
            'idcuenta'=>'113',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);

        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'4',
            'idcuenta'=>'395',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'4',
            'idcuenta'=>'158',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'4',
            'idcuenta'=>'543',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
   ////////////////////////////////Desembolso Prestamos Emergencia//////////////////////////////////////////////     
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'5',
            'idcuenta'=>'58',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);
        
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'5',
            'idcuenta'=>'543',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]); 
        
///////////////////////////////Cobranza Prestamos Emergencia///////////////////////////////////////
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'6',
            'idcuenta'=>'543',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'6',
            'idcuenta'=>'401',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'6',
            'idcuenta'=>'395',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'6',
            'idcuenta'=>'58',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'6',
            'idcuenta'=>'297',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'6',
            'idcuenta'=>'404',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);  
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'6',
            'idcuenta'=>'534',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'6',
            'idcuenta'=>'511',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        ///////////////////////////////////Cobranza Prestamos Regulares//////////////////////////////////////////////
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'7',
            'idcuenta'=>'543',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);
      
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'7',
            'idcuenta'=>'401',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'7',
            'idcuenta'=>'113',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'7',
            'idcuenta'=>'296',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]); 
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'7',
            'idcuenta'=>'404',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'7',
            'idcuenta'=>'280',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'7',
            'idcuenta'=>'534',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'7',
            'idcuenta'=>'511',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);
 /////////////////////////////////////////////////////////////////////////////////

        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'8',
            'idcuenta'=>'36',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'8',
            'idcuenta'=>'3',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);


        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'9',
            'idcuenta'=>'3',
            'tipocargo'=>'d',
            'porcentaje'=>'100'
        ]);
        DB::table('con__perfilcuentadetalles')->insert([
            'idperfilcuentamaestro'=>'9',
            'idcuenta'=>'36',
            'tipocargo'=>'h',
            'porcentaje'=>'100'
        ]);


    }

} 