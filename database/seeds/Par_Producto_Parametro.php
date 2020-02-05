<?php

use Illuminate\Database\Seeder;

class Par_Producto_Parametro extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__productos__parametros')->insert(['idfactor'=>'1','valorparametro'=>'30','idf'=>'1']);
        DB::table('par__productos__parametros')->insert(['idfactor'=>'1','valorparametro'=>'10','idf'=>'2']); 
        DB::table('par__productos__parametros')->insert(['idfactor'=>'1','valorparametro'=>'60','idf'=>'3']); 

        DB::table('par__productos__parametros')->insert(['idfactor'=>'2','valorparametro'=>'20','idf'=>'1']);
        DB::table('par__productos__parametros')->insert(['idfactor'=>'2','valorparametro'=>'10','idf'=>'2']); 
        DB::table('par__productos__parametros')->insert(['idfactor'=>'2','valorparametro'=>'70','idf'=>'3']); 

        DB::table('par__productos__parametros')->insert(['idfactor'=>'3','valorparametro'=>'30','idf'=>'1']);
        DB::table('par__productos__parametros')->insert(['idfactor'=>'3','valorparametro'=>'10','idf'=>'2']);
        DB::table('par__productos__parametros')->insert(['idfactor'=>'3','valorparametro'=>'60','idf'=>'3']); 

        DB::table('par__productos__parametros')->insert(['idfactor'=>'4','valorparametro'=>'80','idf'=>'1']);
        DB::table('par__productos__parametros')->insert(['idfactor'=>'4','valorparametro'=>'20','idf'=>'3']); 
        
   }
}
