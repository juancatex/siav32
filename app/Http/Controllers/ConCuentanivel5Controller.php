<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Con_Cuentanivel4;
use App\Con_Cuentanivel5;
use Illuminate\Support\Facades\DB;
use App\Adm_User;
use Illuminate\Support\Facades\Validator;

class ConCuentanivel5Controller extends Controller
{
    public function index(Request $request)
    {
        $user = New Adm_User();
       //$user()->authorizeRoles(['user', 'admin']);
        $user->authorizeRoles('user');
    
        //if (!$request->ajax()) return redirect('/');


        //$cuentanivel2 = Con_Cuentanivel2::orderBy('valorn2', 'asc')
                        
        $codn4=$request->codn4;               
        $cuentanivel5 = Con_Cuentanivel5::join('con__cuentanivel4','con__cuentanivel4.codn4','=','con__cuentanivel5.codn4')
        //->where('con__cuentanivel5.activo','=','1')
        ->where('con__cuentanivel5.codn4','=',$codn4)
        ->select('con__cuentanivel5.codn4','codn5','valorn5','nomcuentan5','con__cuentanivel5.activo')
        ->orderBy('valorn5', 'asc')
        ->paginate(20);   
        
        $raw=DB::raw('max(valorn5) as maxcn5');
        $maxcn5=Con_Cuentanivel5::where('codn4',$codn4)
                                    ->select($raw)
                                    ->get()->toarray();
        
        return [
            'pagination' => [
                'total'        => $cuentanivel5->total(),
                'current_page' => $cuentanivel5->currentPage(),
                'per_page'     => $cuentanivel5->perPage(),
                'last_page'    => $cuentanivel5->lastPage(),
                'from'         => $cuentanivel5->firstItem(),
                'to'           => $cuentanivel5->lastItem(),
            ],
            'cuentasnivel5' => $cuentanivel5,
            'maxcn5'=>$maxcn5
        ];
    }

    public function selectCuentanivel5(Request $request){
        // if (!$request->ajax()) return redirect('/');
        $codn4=$request->codn4;
         $cuentanivel5 = Con_Cuentanivel5::join('con__cuentanivel4','con__cuentanivel5.codn4','=','con__cuentanivel4.codn4')
         ->where('con__cuentanivel5.activo','=','1')
         ->where('con__cuentanivel5.codn4','=',$codn4)
         
         ->orderBy('valorn5', 'asc')->get();
         return ['cuentasnivel5' => $cuentanivel5];
     }
     public function selectValorn5(Request $request){
        // if (!$request->ajax()) return redirect('/');
         $valorn5 = Con_Cuentanivel5::select('valorn5','nomcuentan5')
                            ->where('codn5','=',$request->codn5)
                            ->get();
         return ['valorn5' => $valorn5];
     }
     public function store(Request $request)
     {
         if (!$request->ajax()) return redirect('/');

      
             $validator = Validator::make($request->all(), [
                'con__cuentanivel5_codn4_valorn5_unique' => 'unique:con__cuentanivel5' 
                //'valorn5'     => 'unique:con__cuentanivel5'
            ]);
        
         
         $codn4=$request->codn4;
         $valorcuentanivel5=strtoupper($request->valorn5);
         $nombrecuentanivel5=ucwords(strtolower($request->nombrecuentan5));
 
         $cuentanivel5 = new Con_Cuentanivel5();
         $cuentanivel5->codn4=$codn4;
         $cuentanivel5->codn5=$codn4.$valorcuentanivel5;
         $cuentanivel5->nomcuentan5 = $nombrecuentanivel5;
         $cuentanivel5->valorn5 = $valorcuentanivel5;
         $cuentanivel5->save();
     }
     
}
