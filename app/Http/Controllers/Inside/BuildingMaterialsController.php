<?php

namespace App\Http\Controllers\inside;

use App\BuildingMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuildingMaterialsController extends Controller
{
    public function index(){
        return view('catalogs.buildingMaterial');
    }
    public function buildingMaterials(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $buildingMaterials=BuildingMaterial::all();
            $count = 1;
            foreach ($buildingMaterials as $value) 
            {
                $value->number = $count++;
                $value->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return $buildingMaterials;
        break;
        case 'new':
            $buildingMaterial = BuildingMaterial::create([
                    'name' => $request->name
            ]);
                $buildingMaterial->save();
                return redirect('materialesConstruccion')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
            $buildingMaterial = BuildingMaterial::find($request->id);
            $buildingMaterial->name = $request->name;
            $buildingMaterial->save();
            return redirect('materialesConstruccion')->with('success','Tus datos fueron almacenados de forma satisfactoria.;');
        break;
        case 'delete':
            $buildingMaterial = BuildingMaterial::find($request->registerId);
            $buildingMaterial->delete();
            return redirect('materialesConstruccion')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
            }
    }
}
