<?php

namespace App\Http\Controllers\Inside;

use App\Product;
use App\Department;
use App\DepartmentInstitute;
use App\Institute;
use App\SupportProduct;
use App\Support;
use App\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(){
        $categories = Category::all();
        $data = array('categories' => $categories);
        return view('catalogs.products', $data);
    }
    public function products(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $products = Product::all();
            $count = 1;
            foreach ($products as $product) 
            {
                $category = Category::find($product->categories_id);
                $product->number = $count++;
                $product->category = $category->name;
                $product->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return  $products;
        break;
        case 'new':
            $code = substr($request->name, 0, 4);
            $product = Product::create([
                'name' => $request->name,
                'code' => $code,
                'categories_id' => $request->categories_id
            ]);
            $product->save();

            return redirect('productos')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
            $code = substr($request->name, 0, 4);
            $product = Product::find($request->id);
            $product->name = $request->name;
            $product->code = $code;
            $product->save();

            return redirect('productos')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;
        case 'delete':
            $product = Product::find($request->registerId);
            $product->delete();
            return redirect('productos')->with('success','el registro se elimino de forma satisfactoria.');
            

        break;
        default:
            return array();
        break;
            }
    }
}
