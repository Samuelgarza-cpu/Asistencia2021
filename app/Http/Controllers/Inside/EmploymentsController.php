<?php

namespace App\Http\Controllers\Inside;

use App\Employment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmploymentsController extends Controller
{
    public function index(){
        return view('catalogs.employments');
    }
    public function employments(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $employments=Employment::all();
            $count = 1;
            foreach ($employments as $value) 
            {
                $value->number = $count++;
                $value->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return $employments;
        break;
        case 'new':
            $employment = Employment::create([
                    'name' => $request->name
            ]);
                $employment->save();
                return redirect('ocupaciones')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
            $employment = Employment::find($request->id);
            $employment->name = $request->name;
            $employment->save();
            return redirect('ocupaciones')->with('success','Tus datos fueron almacenados de forma satisfactoria.;');
        break;
        case 'delete':
            $employment = Employment::find($request->registerId);
            $employment->delete();
            return redirect('ocupaciones')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
            }
    }
}
