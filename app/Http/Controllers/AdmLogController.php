<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Adm_Log;

class AdmLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;       
        $buscar1 = $request->buscar1;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
       
        $logs = DB::table('db_sia_logs.telescope_entries as log')->select('log.*');
        
        if ($buscar!='')
            $logs = $logs->where ('content', 'like', "%sql\":\"". $buscar . '%');
        if ($buscar1!='')
             $logs = $logs->where ('content', 'like', "%name\":\"". $buscar1 . '%');     
        if ($buscar2!='' && $buscar3!='')
            $logs = $logs->whereBetween('created_at', [$buscar2, $buscar3]);
        else 
            $logs = $logs->where ('content', 'like', "%sql\":\"%");

        $logs = $logs->orderBy('created_at', 'desc')->paginate(10);

        return [
            'pagination' => [
                 'total'        => $logs->total(),
                 'current_page' => $logs->currentPage(),
                 'per_page'     => $logs->perPage(),
                 'last_page'    => $logs->lastPage(),
                 'from'         => $logs->firstItem(),
                 'to'           => $logs->lastItem(),
            ],
            'logs' => $logs
        ];
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    { 
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
