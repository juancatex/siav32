<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\cuentacontable;

class CuentacontableController extends Controller
{
    public function selectCuenta(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $cuentas = cuentacontable::where('activo','=','1')
        ->select('idcuenta','descripcion')->orderBy('cuenta', 'asc')->get();
        return ['cuentas' => $cuentas];
    }
}