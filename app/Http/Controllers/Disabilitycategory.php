<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DisabilityCategories;


class Disabilitycategory extends Controller
{
    public function index(){
    
        $categories = DisabilityCategories::all();

        $data = array('categories' => $categories);
        return view('Catalogs.categorydisabilities', $data);
        

   }
   public function products(Request $request)
   {
       switch($request->input('action')){
       case "query":
           $products = DisabilityCategories::all();
          
           $count = 1;
           foreach ($products as $product) 
           {
            //    $category = DisabilityCategories::find($product->categories_id);
               $product->number = $count++;
               $product->category = $product->name;
            
               $product->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                           <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
           }
           return  $products;
       break;
       case 'new':
           $code = substr($request->name, 0, 4);
           $product = DisabilityCategories::create([
               'name' => $request->name,
               'active' => $request->active == "on" ? 1 : 0
           ]);
           $product->save();

           return redirect('categoria-diagnostico')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
       break;  
       case 'update':
      
           $product = DisabilityCategories::find($request->id);
           $product->name = $request->name;
           $product->active = $request->active == "on" ? 1 : 0;
       
           $product->save();

           return redirect('categoria-diagnostico')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
       break;
       case 'delete':
           $product = DisabilityCategories::find($request->registerId);
           $product->active=0;
           $product->save();
           return redirect('categoria-diagnostico')->with('success','el registro se elimino de forma satisfactoria.');
           

       break;
       default:
           return array();
       break;
           }
   }

  
}
