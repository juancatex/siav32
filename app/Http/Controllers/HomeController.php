<?php

namespace App\Http\Controllers;
use App\Adm_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        
        if ($request->user())
            echo "<br>si con request";
        else
            echo "<br>no cn request";

        if (Auth::user()) {
            echo "<br>si con auth<br>";
            echo "<br>valor: ";  
        }            
        else
            echo "<br>no con auth";            

        if (Auth::check()) echo "<br>dentro<br>";
        

        $user = new Adm_User();
        $user->authorizeRoles(['user', 'admin']);
        $user->jaja();
        
        return view('home');
    }
}
