<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TipooperacionController extends Controller
{
    public function selectTipooperacion(Request $request){
        //if (!$request->ajax()) return redirect('/');
        
        /*
        $tipooperaciones = Apo_Canal::
        select('idcanal','nomcanal')
        ->where('activo','=','1')
        ->orderBy('idcanal', 'asc')->get();
        */
        $tipooperaciones=config('app.tipo_operacion');
        return ['tipooperaciones' => $tipooperaciones];
    
    }

    public function pdfToImage(Request $request){

        if (!$request->ajax()) return redirect('/');
        $request->validate([ 
            'file' => 'required|mimes:pdf|max:200' 
            ]);
            if ($request->hasFile('file')) { 
                    $nombre = Str::random(32); 
                    $imagen=$nombre.'.jpg';
                    $pdfin=$nombre.'.pdf'; 
                    Storage::putFileAs('vacuna/pdf/',$request->file('file'),$pdfin); 
 
                    /////////////////////////////////////////////////////////////////////
                    $FileHandle = fopen('../storage/vacuna/imagen/'.$imagen, 'w+');
                    $curl = curl_init(); 
                    $instructions = '{
                    "parts": [
                        {
                        "file": "document"
                        }
                    ],
                    "output": {
                        "type": "image",
                        "format": "jpg",
                        "dpi": 500
                    }
                    }';

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.pspdfkit.com/build',
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_POSTFIELDS => array(
                        'instructions' => $instructions,
                        'document' => new \CURLFILE('../storage/vacuna/pdf/'.$pdfin)
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer pdf_live_Pq3CdtDqgv309are8VtcP138AVG401zbNoKd3vN8dEu'
                    ),
                    CURLOPT_FILE => $FileHandle,
                    )); 
                    $response = curl_exec($curl); 
                    curl_close($curl); 
                    fclose($FileHandle);
                    /////////////////////////////////////////////////////////////////////
                   
                    $imagenvacunaruta = Storage::path('vacuna/imagen/'.$imagen);
                    $imagenvacuna = base64_encode(Storage::get('vacuna/imagen/'.$imagen));
              
                    return ['vacuna'=>'data:'.mime_content_type($imagenvacunaruta) . ';base64,' . $imagenvacuna]; 
                
            }else{
                return response()->json([
                    'message' => 'Datos invalidos'
                               ], 400);
            }
 
    }
    public function saveimage(Request $request){
        $nombre = Str::random(32); 
        $imagen=$nombre.'A.jpg';

        $value = substr($request->foto, strpos($request->foto, ',') + 1); 
        $value = base64_decode($value); 
        Storage::put('vacuna/print/'.$imagen, $value);

        $imagen2=$nombre.'R.jpg';

        $value2 = substr($request->foto2, strpos($request->foto2, ',') + 1); 
        $value2= base64_decode($value2); 
        Storage::put('vacuna/print/'.$imagen2, $value2);

        $imagenvacunaruta = Storage::path('vacuna/print/'.$imagen);
        $imagenvacuna = base64_encode(Storage::get('vacuna/print/'.$imagen));
        $imagenvacunaruta2 = Storage::path('vacuna/print/'.$imagen2);
        $imagenvacuna2= base64_encode(Storage::get('vacuna/print/'.$imagen2));
    
        return ['anverso'=>'data:'.mime_content_type($imagenvacunaruta) . ';base64,' . $imagenvacuna,
        'reverso'=>'data:'.mime_content_type($imagenvacunaruta2) . ';base64,' . $imagenvacuna2]; 
    }
}

  