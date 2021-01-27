<?php

namespace App\Http\Controllers\Inside;

use App\Status;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(){
        return view('catalogs.status');
    }
    public function status(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $status=Status::all();
            $count = 1;
            foreach ($status as $value) 
            {
                $value->number = $count++;
                $value->status = $value->active == 1 ? 'Activo' : 'Inactivo';
                $value->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return $status;
        break;
        case 'new':
            $code = substr($request->name, 0, 4);            
            $status = Status::create([
                    'name' => $request->name,
                    'code' => $code,
                    'active' => $request->active == "on" ? 1 : 0
            ]);
                $status->save();
                return redirect('estados_solicitud')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
            $status = status::find($request->id);
            $status->name = $request->name;
            $status->code = substr($request->name, 0, 4);
            $status->active = $request->active == "on" ? 1 : 0;       
            $status->save();
            return redirect('estados_solicitud')->with('success','Tus datos fueron modificados de forma satisfactoria.');
        break;
        case 'delete':
            $status = status::find($request->registerId);
            $status->active = 0;
            $status->save();
            return redirect('estados_solicitud')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
            }
    }
}
