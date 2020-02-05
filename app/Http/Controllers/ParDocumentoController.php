<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Par_Documento;

class ParDocumentoController extends Controller
{

    public function selectDocumento(Request $request){  
        $documentos=Par_Documento::where('idmodulo',$request->idmodulo)
        ->where('activo',1)->orderBy('nomdocumento')->get();
        return ['documentos'=>$documentos];
    }

    public function listaDocumentos(Request $request)
    {
        $documentos=Par_Documento::where('idmodulo','like','%'.$request->idmodulo.'%');
        if($request->iddocumentos) $documentos=Par_Documento::whereIn('iddocumento',explode(',',$request->iddocumentos));
        return ['documentos'=>$documentos->get()];
    }

    public function storeDocumento(Request $request)
    {
        $documento=new Par_Documento();
        $documento->nomdocumento = $request->nomdocumento;
        $documento->idmodulo = $request->idmodulo;
        $documento->save();
    }
  
    public function updateDocumento(Request $request)
    {
        $documento=Par_Documento::findOrFail($request->iddocumento);
        $documento->nomdocumento=$request->nomdocumento;
        $documento->idmodulo=$request->idmodulo;
        $documento->save();
    }

    public function switchDocumento(Request $request)
    {
        $documento=Par_Documento::findOrFail($request->iddocumento);
        $documento->activo=abs($documento->activo-1);
        $documento->save();
    }
}
