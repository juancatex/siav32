<?php

namespace App\Http\Controllers;
use App\Adm_User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Con_Cuentanivel1;
use Illuminate\Support\Facades\DB;

class ConCuentanivel1Controller extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $user = New Adm_User();
       //$user()->authorizeRoles(['user', 'admin']);
        $user->authorizeRoles('user');
    
        //if (!$request->ajax()) return redirect('/');


        $cuentanivel1 = Con_Cuentanivel1::orderBy('codn1', 'asc')
                                            //->where('activo','=',1)
                        ->paginate(20)    ;
        
        $raw=DB::raw('max(codn1) as maxcn1');
        $maxcn1=Con_Cuentanivel1::select($raw)->get()->toArray();
        //dd($maxcn1);
                        
            
        
        return [
            'pagination' => [
                'total'        => $cuentanivel1->total(),
                'current_page' => $cuentanivel1->currentPage(),
                'per_page'     => $cuentanivel1->perPage(),
                'last_page'    => $cuentanivel1->lastPage(),
                'from'         => $cuentanivel1->firstItem(),
                'to'           => $cuentanivel1->lastItem(),
            ],
            'cuentasnivel1' => $cuentanivel1,
            'maxcn1'=>$maxcn1
        ];
    }
    public function selectCuentanivel1(Request $request){
       // if (!$request->ajax()) return redirect('/');
        $cuentanivel1 = Con_Cuentanivel1::orderBy('codn1', 'asc')
                                        ->where('activo','=',1)
                                        ->get();
        return ['cuentasnivel1' => $cuentanivel1];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function selectcodn1(Request $request){
        // if (!$request->ajax()) return redirect('/');
        //$raw=DB::raw('max(codn1) as maxn1');
         $codn1 = Con_Cuentanivel1::select('codn1','nomcuentan1')
                            ->where('codn1','=',$request->codn1)
                            ->get();
         return ['codn1' => $codn1];
     }
    
    
     public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $validator = Validator::make($request->all(), [
            'nomcuentan1' => 'unique:con__cuentanivel1',
            'codn1'     => 'unique:con__cuentanivel1'
        ]);

        if ($validator->fails()) {
            //echo $validator->messages()->first(); exit();
            return ($validator->messages()->first());
       }
        
        $valorcuentanivel1=strtoupper($request->codn1);
        $nombrecuentanivel1=ucwords(strtolower($request->nombrecuentan1));

        $cuentanivel1 = new Con_Cuentanivel1();
        
        $cuentanivel1->nomcuentan1 = $nombrecuentanivel1;
        $cuentanivel1->codn1 = $valorcuentanivel1;
        $cuentanivel1->save();
    }
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $codn1
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomcuentan1' => 'unique:con__cuentanivel1'
        ]);

        if ($validator->fails()) { 
            return ($validator->messages()->first());
       }
        
        if (!$request->ajax()) return redirect('/');

        $cuentanivel1 = Con_Cuentanivel1::findOrFail($request->codn1);
        $cuentanivel1->nomcuentan1 = ucwords(strtolower($request->nomcuentan1));
		$cuentanivel1->codn1 = $request->codn1;
        $cuentanivel1->activo = '1';
        $cuentanivel1->save();
    }

    
}
