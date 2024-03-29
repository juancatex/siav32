<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public function __construct() {  //para obtner datos de usuarios dentro los logs
        $this->middleware('auth');
        Auth::user();
    }
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
