<?php

namespace App\Http\Controllers\Inside;

use App\Institute;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstitutesController extends Controller
{
    public function index(){
        return view('catalogs.institutes');
    }
    public function institutes(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $institutes=Institute::all();
            $count = 1;
            foreach ($institutes as $value) 
            {
                $value->number = $count++;
                $value->status = $value->active == 1 ? 'Activo' : 'Inactivo';
                $value->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return $institutes;
        break;
        case 'new':
            $institute = Institute::create([
                    'name' => $request->name,
                    'active' => $request->active == "on" ? 1 : 0
            ]);
            $institute->save();
            return redirect('institutos')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
            $institute = Institute::find($request->id);
            $institute->name = $request->name;
            $institute->active = $request->active == "on" ? 1 : 0;
            $institute->save();
            return redirect('institutos')->with('success','Tus datos fueron modificados de forma satisfactoria.');
        break;
        case 'delete':
            $institute = Institute::find($request->registerId);
            $institute->active = 0;
            $institute->save();
            return redirect('institutos')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
            }
    }
}
