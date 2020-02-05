<?php

namespace App\Http\Controllers;

use App\Apo_Debito;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\AsinalssClass\AsientoMaestroClass;

class ApoDebitoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) 
            return redirect('/');
        
       
        // verificar que validar 

            /* $validator = Validator::make($request->all(), [
                'descripcion' => 'unique:apo__aportes'
            ]);   

            if ($validator->fails()) {
                //echo $validator->messages()->first(); exit();
                return ($validator->messages()->first());
        }
            */
        //return $idperfilcuentamaestro;
        $idaporte=$request->idaporte;
        $idperfilcuentamaestro=$request->idperfilcuentamaestro;
        
        $glosa=$request->obsdebito;
        $importetotal=$request->montodebito;
        $idmodulo=$request->idmodulo;
        $numdocumento=$request->numdocumentodeb;
        $tipodocumento=$request->tipodocumentodeb;
        $fecharegistro='';
        

        /*
        $asientomaestro= new AsientoMaestroClass($idperfilcuentamaestro, $tipodocumento,$numdocumento,$glosa,$importetotal);
        $asientomaestro->AsientosMaestroDetalle();
*/
      DB::beginTransaction();
        {
            try{       
                
                $debito = new Apo_Debito();
                $debito->idaporte=$idaporte;
                $debito->monto=$importetotal;
                $debito->idperfilcuentamaestro=$idperfilcuentamaestro;
                $debito->obsdebito=$glosa;
                $debito->numdocumentodeb=$numdocumento;
                $debito->tipodocumentodeb=$tipodocumento;
                $debito->save();
                $iddebito=$debito->iddebito;
                

                $asientomaestro= new AsientoMaestroClass();
                $idasientomaestro=$asientomaestro->AsientosMaestroDetalle($idperfilcuentamaestro, $tipodocumento,$numdocumento,$glosa,$importetotal,$idmodulo,$fecharegistro);
                DB::unprepared('CALL asientomaestrodebitoaporte('.$idasientomaestro.',0,'.$idaporte.')');
                DB::commit();
                return $iddebito;
                
            }
            catch(\Exception $e)
            {
                $error = $e->getMessage();
                DB::rollback();
                //return $e;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Apo_Debito  $apo_Debito
     * @return \Illuminate\Http\Response
     */
    public function show(Apo_Debito $apo_Debito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Apo_Debito  $apo_Debito
     * @return \Illuminate\Http\Response
     */
    public function edit(Apo_Debito $apo_Debito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Apo_Debito  $apo_Debito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apo_Debito $apo_Debito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Apo_Debito  $apo_Debito
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apo_Debito $apo_Debito)
    {
        //
    }
}
