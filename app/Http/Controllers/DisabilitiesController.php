<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Disabilities;
use App\DisabilityCategories;


class DisabilitiesController extends Controller
{
    public function index(){

        $categories = DisabilityCategories::all();
        
        $data = array('categories' => $categories);
        return view('Catalogs.disabilities',$data);

   }

   public function data(Request $request){
    switch($request->input('action')){
        case "query":
            $products = Disabilities::all();
            $count = 1;
            foreach ($products as $product) 
            {
                // $category = Disabilities::find($product->categories_id);
                $product->number = $count++;
                $product->category = $product->disabilitycategories_id;
                $product->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return  $products;
        break;
        case 'new':
          
            $product = Disabilities::create([
                'name' => $request->name,
                'disabilitycategories_id' => $request->categories_id,
                'active' => $request->active == "on" ? 1 : 0
            ]);
            $product->save();

            return redirect('diagnostico')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
          
            $product = Disabilities::find($request->id);
            $product->name = $request->name;
            $product->disabilitycategories_id=$request->categories_id;
            $product->active = $request->active == "on" ? 1 : 0;

            $product->save();

            return redirect('diagnostico')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;
        case 'delete':
            $product = Disabilities::find($request->registerId);
            $product->active=0;
            $product->save();
            return redirect('diagnostico')->with('success','el registro se elimino de forma satisfactoria.');
            

        break;
        default:
            return array();
        break;
            }
    }
       
   }

