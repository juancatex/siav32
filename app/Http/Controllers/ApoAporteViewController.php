<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apo_AporteView;

class ApoAporteViewController extends Controller
{
    public function index(Request $request)
    {///por modificar
        
        $aporteview = Apo_AporteView::orderBy('fechaaporte', 'asc')->paginate(10);
        return [
            'pagination' => [
                'total'        => $prueva_vista->total(),
                'current_page' => $prueva_vista->currentPage(),
                'per_page'     => $prueva_vista->perPage(),
                'last_page'    => $prueva_vista->lastPage(),
                'from'         => $prueva_vista->firstItem(),
                'to'           => $prueva_vista->lastItem(),
            ],
            'prueva$prueva_vista' => $prueva_vista
        ];
    }
}
