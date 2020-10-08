<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Apo_Carga_ascii;
use App\Apo_Aporte;
use App\CsvData;
use App\Http\Requests\CsvImportRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Apo_Total_Aporte;
use App\Con_Configuracion;

use App\AsinalssClass\AsientoMaestroClass;

class ImportController extends Controller
{
    /*
    public function getImport()
    {
        return view('import');
    }

    public function parseImport(CsvImportRequest $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        if ($request->has( 'header')) {
            $data = Excel::load($path, function($reader) {})->get()->toArray();
        } else {
            $data = array_map('str_getcsv', file($path));
        }
        if (count($data) > 0) {
            if ($request->has('header')) {
                $csv_header_fields = [];
                foreach ($data[0] as $key => $value) {
                    $csv_header_fields[] = $key;
                }
            }
            $csv_data = array_slice($data, 0, 4);
            $csv_data_file = CsvData::create([
                'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
                'csv_header' => $request->has('header'),
                'csv_data' => json_encode($data)
            ]);
        } else return redirect()->back();
        return view('import_fields', compact( 'csv_header_fields', 'csv_data', 'csv_data_file'));
    }
    */
    
    public function processImport(Request $request)
    {
        Apo_Carga_ascii::truncate();
        ini_set('memory_limit', '1024M');
        ini_set ('max_execution_time', 3200);

        $cuentas=Con_Configuracion::select('codigo','valor')
                                    ->where('activo',1)
                                    ->where('tipoconfiguracion',5)
                                    ->get()->toArray();
        
        //dd($cuentas);

        
        $idperfilcuentamaestro=$request->idperfilcuentamaestro;
        if($idperfilcuentamaestro==4) //TODO:  MODIFICAR EL PERFIL DE CUENTA PARA QUE SE DINAMICO Y CONTROLE QUE SEA EL ID CORRECTO PARA EL PERFIL DE CARGA ASCII
        {
            $importe3=0;
            $importe4=0;
            $importe5=0;
            $csv_header = $request->csv_header;
            $csv_data = $request->csv_data;
            $fecha_archivo=$request->fecha_archivo;
            $nombre_archivo=$request->nombre_archivo;
            $glosa=$request->observaciones;
            $idmodulo=$request->idmodulo;
            
            $maxcsvdata = CsvData::max('idlote');
            $idlote=$maxcsvdata+1;

            DB::beginTransaction();
            {
                try{
                    
                    foreach($nombre_archivo as $indicenom=>$valor)
                    {
                        $csvdata = new CsvData();
                        $csvdata->csv_filename = $valor;
                        $csvdata->fecha_archivo=$fecha_archivo;
                        $csvdata->tipoaporte=$request->idtipoaporte;
                        $csvdata->idlote=$idlote;
                        $csvdata->glosa=$glosa;
                        $csvdata->save();
                    }
                    

                    if($csv_data)
                    {
                        foreach ($csv_data as $indice=>$columna)
                        {
                            $cargaascii = new Apo_Carga_ascii();
                            
                            if($columna['codfuerza']==3)
                                $importe3=$importe3+$columna['aporte'];
                            if($columna['codfuerza']==4)
                                $importe4=$importe4+$columna['aporte'];
                            if($columna['codfuerza']==5)
                                $importe5=$importe5+$columna['aporte'];
                            
                            
                            
                            
                            foreach($columna as $ind=>$col)
                            {
                                $resultado = str_replace("\r", "", $ind);
                                $cargaascii->$resultado=$col;
                            }
                            $cargaascii->fechaaporte=$fecha_archivo;
                            $cargaascii->idtipoaporte=$request->idtipoaporte;
                            $cargaascii->idlote=$idlote;
                            $cargaascii->idperfilcuentamaestro=$idperfilcuentamaestro;
                            $cargaascii->observaciones=$glosa;
                            $cargaascii->save();
                        }
                        //dd($importe4);
                        
                    }
                    $tipodocumento='';
                    $numdocumento='';
                    //$glosa=$ob;
                    //$idperfilcuentamaestro=$request->idperfilcuentamaestro;  este no
                    $cont=0;
                    foreach ($cuentas as $valor) {
                        if($valor['codigo']=='EJD')
                        {   
                            $arrayDetalle[$cont]['idcuenta']=$valor['valor'];
                            $arrayDetalle[$cont]['subcuenta']='';
                            $arrayDetalle[$cont]['documento']=$tipodocumento;
                            $arrayDetalle[$cont]['moneda']='Bs';
                            $arrayDetalle[$cont]['monto']=$importe3;
                        }
                        else if($valor['codigo']=='AED')
                        {
                            $arrayDetalle[$cont]['idcuenta']=$valor['valor'];
                            $arrayDetalle[$cont]['subcuenta']='';
                            $arrayDetalle[$cont]['documento']=$tipodocumento;
                            $arrayDetalle[$cont]['moneda']='Bs';
                            $arrayDetalle[$cont]['monto']=$importe4;
                        }
                        else if($valor['codigo']=='ARD')
                        {
                            $arrayDetalle[$cont]['idcuenta']=$valor['valor'];
                            $arrayDetalle[$cont]['subcuenta']='';
                            $arrayDetalle[$cont]['documento']=$tipodocumento;
                            $arrayDetalle[$cont]['moneda']='Bs';
                            $arrayDetalle[$cont]['monto']=$importe5;
                        }
                        else if($valor['codigo']=='EJH')
                        {
                            $arrayDetalle[$cont]['idcuenta']=$valor['valor'];
                            $arrayDetalle[$cont]['subcuenta']='';
                            $arrayDetalle[$cont]['documento']=$tipodocumento;
                            $arrayDetalle[$cont]['moneda']='Bs';
                            $arrayDetalle[$cont]['monto']=$importe3*(-1);
                        }
                        else if($valor['codigo']=='AEH')
                        {
                            $arrayDetalle[$cont]['idcuenta']=$valor['valor'];
                            $arrayDetalle[$cont]['subcuenta']='';
                            $arrayDetalle[$cont]['documento']=$tipodocumento;
                            $arrayDetalle[$cont]['moneda']='Bs';
                            $arrayDetalle[$cont]['monto']=$importe4*(-1);
                        }
                        else if($valor['codigo']=='ARH')
                        {
                            $arrayDetalle[$cont]['idcuenta']=$valor['valor'];
                            $arrayDetalle[$cont]['subcuenta']='';
                            $arrayDetalle[$cont]['documento']=$tipodocumento;
                            $arrayDetalle[$cont]['moneda']='Bs';
                            $arrayDetalle[$cont]['monto']=$importe5*(-1);
                        }
                        $cont++;                   
                    }
                    //dd($arrayDetalle);
                    $asientomaestro= new AsientoMaestroClass();
                    $idasientomaestro=$asientomaestro->AsientosMaestroArrayAportes($idperfilcuentamaestro, $tipodocumento,$numdocumento,$glosa,$arrayDetalle,$idmodulo,$fecha_archivo);
                    DB::unprepared('CALL agregarasientomaestro('.$idasientomaestro.','.$idlote.',0)');
                    
                    DB::commit();
                    //return $idasientomaestro;
                    return 'Correcto!';
                }
                catch(\Exception $e)
                {
                    DB::rollback();
                    return "Error".$e->getMessage();
                    //echo 'error ('.$e->getCode().'):'.$e->getMessage();
                }
            }
        }
        else
            return 'perfil de cuenta incorrecto';
    }

}
