<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SesionOut extends Controller
{
    public function out(Request $request)
     { 
            \Auth::logout();
            \Session::flush();
            \Session::regenerate();  
       return redirect('/')->with('setout','Se cerro por inactividad');
   
       
    }

}
