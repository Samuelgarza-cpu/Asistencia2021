<?php

namespace App\Http\Controllers\inside;

use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServicesController extends Controller
{
    public function index(){
        return view('catalogs.services');
    }
    public function services(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $services= Service::all();
            $count = 1;
            foreach ($services as $value) 
            {
                $value->number = $count++;
                $value->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return $services;
        break;
        case 'new':
            $service = Service::create([
                    'name' => $request->name
            ]);
                $service->save();
                return redirect('servicios')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
            $service = Service::find($request->id);
            $service->name = $request->name;
            $service->save();
            return redirect('servicios')->with('success','Tus datos fueron almacenados de forma satisfactoria.;');
        break;
        case 'delete':
            $service = Service::find($request->registerId);
            $service->delete();
            return redirect('servicios')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
            }
    }
}
