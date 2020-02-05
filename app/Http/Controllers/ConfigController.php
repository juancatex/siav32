<?php

namespace App\Http\Controllers;
use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
  
    public function getColumnsFileInput(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        return ['columns'=>(DB::select("SELECT fileColumns FROM configs WHERE codigo=?",array($request->col)))[0]->fileColumns];
    }
    public function sumatotal()
    {
        if (!$request->ajax()) return redirect('/');

        $totalaportes = Config::where('codigo','=','AO')->orwhere('codigo','=','AJ')
        ->sum('valor');
        return ['totalaportes' => $totalaportes];

    }
    
}
