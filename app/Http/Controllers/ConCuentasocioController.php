<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Con_Cuentasocio;

class ConCuentasocioController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');  //quitar y ver el objeçto json

        $idsocio=$request->idsocio;
        
        /*
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        /*
        $cuentasocios =Con_Cuentasocio::all();
        return ['cuentasocios' => $cuentasocios];
        */
        /*
        if ($buscar==''){
            $cuentasocios = Con_Cuentasocio::join('par_departamentos','par_ciudades.iddepartamento','=','par_departamentos.iddepartamento')
            ->select('par_ciudades.idciudad','par_ciudades.iddepartamento','par_ciudades.nomciudad','par_departamentos.nomdepartamento','par_ciudades.activo')
            ->orderBy('par_ciudades.iddepartamento', 'desc')->paginate(10);
        }
        else{
            */
            /////////////////ejemplo join////////////
            /*
            $users = DB::table('users')
            ->join('contacts', 'users.id', '=', 'contacts.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();
            */
            ////////////////////////////////////////
            $cuentasocios = Con_Cuentasocio::join('con__entidadbancarias','con__cuentasocios.identidadbancaria','=','con__entidadbancarias.identidadbancaria')
                                            ->join('socios','con__cuentasocios.idsocio','=','socios.idsocio')
            ->select('con__entidadbancarias.identidadbancaria',
                        'con__entidadbancarias.nomentidadbancaria',
                        'socios.idsocio',
                        'socios.nombre',
                        'socios.apaterno',
                        'socios.amaterno',
                        'con__cuentasocios.idcuentasocio',
                        'con__cuentasocios.numcuentasocio',
                        'con__cuentasocios.activo')
            ->where('socios.idsocio','=', $idsocio )
            ->orderBy('socios.idsocio','desc')
            ->orderBy('con__entidadbancarias.nomentidadbancaria','desc')
            ->paginate(10);
        //}*/
        

        return [
            
            'pagination' => [
                'total'        => $cuentasocios->total(),
                'current_page' => $cuentasocios->currentPage(),
                'per_page'     => $cuentasocios->perPage(),
                'last_page'    => $cuentasocios->lastPage(),
                'from'         => $cuentasocios->firstItem(),
                'to'           => $cuentasocios->lastItem(),
            ],
            'cuentasocios' => $cuentasocios
        ];
    }

    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        /*$validator = Validator::make($request->all(), [
            'nume'=>'unique:par_ciudades'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
        }
        */
        
        $cuentasocio = new Con_Cuentasocio();
        $cuentasocio->idsocio=$request->idsocio;
        $cuentasocio->identidadbancaria=$request->identidadbancaria;
        $cuentasocio->numcuentasocio=$request->numcuentasocio;
        $cuentasocio->activo='1';
        $cuentasocio->save();

        
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        /*
        $validator = Validator::make($request->all(), [
            'nomciudad'=>'unique:par_ciudades'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        */

        $cuentasocio = Con_Cuentasocio::findOrFail($request->idcuentasocio);
        //$cuentasocio->idsocio=$request->idsocio;
        $cuentasocio->identidadbancaria=$request->identidadbancaria;
        $cuentasocio->numcuentasocio=$request->numcuentasocio;
        $cuentasocio->activo='1';
        $cuentasocio->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cuentasocio = Con_Cuentasocio::findOrFail($request->idcuentasocio);
        $cuentasocio->activo = '0';
        $cuentasocio->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cuentasocio = Con_Cuentasocio::findOrFail($request->idcuentasocio);
        $cuentasocio->activo = '1';
        $cuentasocio->save();
    }
            
       
    
}
