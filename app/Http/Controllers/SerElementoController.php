<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ser_Elemento;

class SerElementoController extends Controller
{
    public function elementosServicio(Request $request)
    {   
        $elemento=Ser_Elemento::where('idservicio','=',$request->idservicio)->get();
        return ['elemento'=>$elemento];
    }
}
