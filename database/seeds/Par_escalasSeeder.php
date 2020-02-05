<?php

use Illuminate\Database\Seeder;

class Par_EscalasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('par__escalas')->insert(['idescala'=>'1','minmonto'=>'0','maxmonto'=>'10000','minanios'=>'6','maxanios'=>'420']);  
        DB::table('par__escalas')->insert(['idescala'=>'2','minmonto'=>'1000','maxmonto'=>'7000','minanios'=>'6','maxanios'=>'420']);  

        DB::table('par__escalas')->insert(['idescala'=>'3','minmonto'=>'0','maxmonto'=>'6000','minanios'=>'12','maxanios'=>'48']);  
        DB::table('par__escalas')->insert(['idescala'=>'3','minmonto'=>'0','maxmonto'=>'10000','minanios'=>'60','maxanios'=>'108']);
        DB::table('par__escalas')->insert(['idescala'=>'3','minmonto'=>'0','maxmonto'=>'15000','minanios'=>'120','maxanios'=>'300']); 
        DB::table('par__escalas')->insert(['idescala'=>'3','minmonto'=>'0','maxmonto'=>'10000','minanios'=>'312','maxanios'=>'420']);   

        DB::table('par__escalas')->insert(['idescala'=>'4','minmonto'=>'0','maxmonto'=>'1000','minanios'=>'48','maxanios'=>'72']); 
        DB::table('par__escalas')->insert(['idescala'=>'4','minmonto'=>'0','maxmonto'=>'3000','minanios'=>'84','maxanios'=>'120']); 
        DB::table('par__escalas')->insert(['idescala'=>'4','minmonto'=>'0','maxmonto'=>'4000','minanios'=>'132','maxanios'=>'180']); 
        DB::table('par__escalas')->insert(['idescala'=>'4','minmonto'=>'0','maxmonto'=>'6000','minanios'=>'192','maxanios'=>'216']); 
        DB::table('par__escalas')->insert(['idescala'=>'4','minmonto'=>'0','maxmonto'=>'7000','minanios'=>'228','maxanios'=>'252']); 
        DB::table('par__escalas')->insert(['idescala'=>'4','minmonto'=>'0','maxmonto'=>'8000','minanios'=>'252','maxanios'=>'300']); 
        DB::table('par__escalas')->insert(['idescala'=>'4','minmonto'=>'0','maxmonto'=>'0','minanios'=>'312','maxanios'=>'420']); 
        //se pone maxmonto=0 cuando se evalua segun  los aportes que tiene el socio.
    }
}
