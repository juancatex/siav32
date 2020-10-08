<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;

class RrhhBiometrico extends Controller
{
    public function get()
    { 
        $zk = new ZKTeco(  '192.168.100.110' ); 
       return $zk->connect().'ok';
    // return app_path();
    }
}
