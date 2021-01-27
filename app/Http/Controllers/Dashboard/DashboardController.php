<?php

namespace App\Http\Controllers\Dashboard;

use App\Requisition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
            $sPA = Requisition::where('status_id','=',2)->get()->count();
            $sEPA = Requisition::where('status_id','=',3)->get()->count();
            $sR = Requisition::where('status_id','=',5)->get()->count();
            $sA = Requisition::where('status_id','=',4)->get()->count();
            $sF = Requisition::where('status_id','=',6)->get()->count();
            $sT = Requisition::all()->count();
            $sT1 = Requisition::where('type','=','especie')->get()->count();
            $sT2 = Requisition::where('type','=','efectivo')->get()->count();
            $data = array(
                'sPA' => $sPA,
                'sEPA' => $sEPA,
                'sR' => $sR,
                'sA' => $sA,
                'sF' => $sF,
                'sT' => $sT,
                'sT1' => $sT1,
                'sT2' => $sT2
            );
            return view('catalogs.dashboard', $data);
    }
}
