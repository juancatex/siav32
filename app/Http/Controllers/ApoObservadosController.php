<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Socio;
use App\Apo_Aporte;
use App\Apo_Observados;
use Illuminate\Support\Facades\DB;

class ApoObservadosController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $fechaaporte=$request->fecha_aporte;
        DB::unprepared('CALL insertar_aportes_observados("'.$fechaaporte.'")');
        DB::unprepared('CALL actualizar_socio_observado("'.$fechaaporte.'")');
        //DB::select('call dmiento("5")');
        //DB::unprepared('CALL insertfecha("2019-01-'.$dia.'")');

        /*

        $observados = CsvData::join('apo__tipoaportes','csv_data.tipoaporte','=','apo__tipoaportes.idtipoaporte')
                            ->select('csv_data.idlote',
                                        'csv_data.csv_filename',
                                        'apo__tipoaportes.descripcion',
                                        'csv_data.fecha_archivo',
                                        'csv_data.created_at') 
                            ->orderBy('csv_data.created_at', 'desc')
                            ->limit(6)->get();
        
                            
        $cabeceras=config('app.db_fields');
        return ['csvdatas' => $csvdatas,
                'cabeceras'=> $cabeceras];
        */
        return $fechaaporte;
        //return 'correcto';
    }
}
