<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use App\par_prestamos_lista;

class ParPrestamosListaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function registrarlista(Request $request)
    { if (!$request->ajax()) return redirect('/');
        $fecha=(DB::select("select getfecha() as total"))[0]->total;

        $prestamo = new par_prestamos_lista(); 
        $prestamo->idsocio_beneficiario=$request->idsocio; 
        $prestamo->idcuentasocio_beneficiario=$request->cuenta; 
        $prestamo->fecharegistro=$fecha; 
        $prestamo->idusuario_reg=Auth::id(); 
        $prestamo->save();  

        return response()->json(array('id' => $prestamo->idlista), 200);
    }
}
