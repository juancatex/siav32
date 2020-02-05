<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CsvData;
use App\Apo_Tipoaporte;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\DB; 
use App\Apo_Aporte;
use App\Apo_Debito;
use App\Con_Asientomaestro;
use App\AsinalssClass\AsientoMaestroClass;

class CsvDataController extends Controller
{
    public function index()
    {
        //if (!$request->ajax()) return redirect('/');

        $csvdatas = CsvData::join('apo__tipoaportes','csv_data.tipoaporte','=','apo__tipoaportes.idtipoaporte')
                            ->select('idcsvdata',
                                        'csv_data.idlote',
                                        'csv_data.csv_filename',
                                        'apo__tipoaportes.descripcion',
                                        'csv_data.fecha_archivo',
                                        'csv_data.created_at',
                                        'csv_data.activo') 
                            ->where('csv_data.activo','=',true)
                            ->orderBy('csv_data.created_at', 'desc')
                            ->limit(6)->get();
        
                            
        $cabeceras=config('app.db_fields');
        return ['csvdatas' => $csvdatas,
                'cabeceras'=> $cabeceras];
        
    }
    public function revisarfechaaporte(Request $request)
    {
        $rawmes="MONTH(fecha_archivo)";
        $rawanio="YEAR(fecha_archivo)";
        $estado=0;
        $fecha_aporte=$request->fecha_aporte;
        $date = strtotime($fecha_aporte);
        $anio=date("Y", $date); // Year (2003)
        $mes =date("m", $date); // Month (12)
//echo date("d", $date); // day (14)



        $fechaexistente = CsvData::select('idcsvdata')
                                    ->whereMonth('fecha_archivo','=',$mes)
                                    ->whereYear('fecha_archivo','=',$anio)
                                    ->get()->toArray();
  
        if ($fechaexistente)
            $estado=1;

        return [
            'estado' => $estado,
            'fechaaporte'=>$fecha_aporte
        ];                           

        
    }

    public function selectCsvdata(Request $request){
        
        //if (!$request->ajax()) return redirect('/');
        if($request->activo) {
            $csvdatas=Csvdata::get();
            return ['csvdatas'=>$csvdatas];
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $csvdatas = Csvdata::join('apo__tipoaportes','csv_data.tipoaporte','=','apo__tipoaportes.idtipoaporte')
                            ->join('apo__aportes','csv_data.idlote','=','apo__aportes.idlote')
                            ->join('con__asientomaestros','apo__aportes.idasientomaestro','=','con__asientomaestros.idasientomaestro')
                            ->select('idcsvdata',
                                        'csv_data.idlote',
                                        'csv_data.csv_filename',
                                        'apo__tipoaportes.descripcion',
                                        'csv_data.fecha_archivo',
                                        'csv_data.created_at',
                                        'csv_data.activo',
                                        'con__asientomaestros.estado',
                                        'con__asientomaestros.idasientomaestro') 
                            
                            ->orderBy('csv_data.created_at', 'desc')
                            ->groupBy('csv_data.idlote')
                            ->paginate(10);
        return [
            'pagination' => [
                'total'        => $csvdatas->total(),
                'current_page' => $csvdatas->currentPage(),
                'per_page'     => $csvdatas->perPage(),
                'last_page'    => $csvdatas->lastPage(),
                'from'         => $csvdatas->firstItem(),
                'to'           => $csvdatas->lastItem(),
            ],            
            'csvdatas' => $csvdatas];
    }
    public function selectLote(Request $request){
        
        $idcsvdata=$request->idcsvdata;
        $idlote=Csvdata::select('idlote')->where('idcsvdata','=',$idcsvdata)->get()->toArray();

        $lote=$idlote[0]['idlote'];
        //dd($lote);
        //echo $lote;
        
        $csvarchivos = Csvdata::where('idlote','=',$lote)
                            ->orderBy('idcsvdata', 'asc')->get();
        return ['csvarchivos' => $csvarchivos];
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) 
            return redirect('/');
        
        $idlote=$request->idlote;
        $idasientomaestro=$request->idasientomaestro;
        $idperfilcuentamaestro=$request->idperfilcuentamaestro;
        $glosa=$request->obsdebito;
        $numdocumentodeb=$request->numdocumentodeb;
        $tipodocumentodeb=$request->tipodocumentodeb;
        $idmodulo=$request->idmodulo;
        $fecharegistro='';

       /*  $arrayaportes=Apo_Aporte::select('idaporte','aporte')
                                ->where('idlote','=',$idlote)->get()->toArray();

        $arraycsvdata=Csvdata::select('idcsvdata')
                                ->where('idlote','=',$idlote)->get()->toArray(); */

       /*  DB::beginTransaction();
        {
            try{ */
                $aportes=Apo_Aporte::where('idlote',$idlote)->update(['activo'=>0]);
                $csvdata=CsvData::where('idlote',$idlote)->update(['activo'=>0]);
                $asientomaestro=Con_Asientomaestro::where('idasientomaestro',$idasientomaestro)->update(['estado'=>2]);
                return $idlote;
                /* $montototal=0;
                foreach ($arrayaportes as $valor)
                {
                   
                    foreach($valor as $i=>$v)
                    {
                        if($i=='idaporte')
                            $idaporte=$v;
                        else 
                            $montodebito=$v;
                    }
                    

                    $debito = new Apo_Debito();
                    $debito->idaporte=$idaporte;
                    $debito->monto=$montodebito;
                    $debito->idperfilcuentamaestro=$idperfilcuentamaestro;
                    $debito->obsdebito=$glosa;
                    $debito->numdocumentodeb=$request->numdocumentodeb;
                    $debito->tipodocumentodeb=$request->tipodocumentodeb;
                    $debito->idlotecargaascii=$idlote;
                    $debito->save();
                    //$iddebito=$debito->iddebito;
                    $montototal=$montototal+$montodebito;
                }   
                foreach($arraycsvdata as $valor)
                {   foreach($valor as $v)
                    {
                        $csvdata = Csvdata::findOrFail($v);
                        $csvdata->activo = '0';
                        $csvdata->numdocdebito=$request->numdocumentodeb;
                        $csvdata->tipodocdebito=$request->tipodocumentodeb;
                        $csvdata->save();
                    }    
                }
                //dd($montototal);
                $asientomaestro= new AsientoMaestroClass();
                $idasientomaestro=$asientomaestro->AsientosMaestroDetalle($idperfilcuentamaestro, $tipodocumentodeb,$numdocumentodeb,$glosa,$montototal,$idmodulo,$fecharegistro);
                DB::unprepared('CALL asientomaestrodebitoaporte('.$idasientomaestro.','.$idlote.',0)');        

                DB::commit(); */
        /*         return $idlote;
                
            }
            catch(\Exception $e)
            {
                DB::rollback();
                $error = $e->getMessage();
                
                return $error;
            }
        } */
    }



}
