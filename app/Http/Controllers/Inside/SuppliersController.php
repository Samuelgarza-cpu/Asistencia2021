<?php

namespace App\Http\Controllers\Inside;
use DateTime;
use App\State;
use App\Address;
use App\Product;
use App\Support;
use App\Category;
use App\Supplier;
use App\Community;
use App\InsDepSup;
use App\ProductLog;
use App\PhoneNumber;
use App\Municipality;

use App\SupportProduct;
use App\AddressSupplier;
use App\SupplierProduct;
use App\SupplierPhoneNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\DepartmentInstituteSupportProduct;

class SuppliersController extends Controller
{
    public function index(){
        return view('catalogs.suppliers');
    }

    public function indexPP($id){
        if(is_numeric($id)){
            $insDepSup = InsDepSup::where('departmentsInstitutes_id','=', session('department_institute_id'))->get();
            $products = [];
            $supports = [];
            $categories = Category::all();
            $count = 0;
            $count2 = 0;
            foreach($insDepSup as $value){
                $support = Support::find($value->supports_id);
                $supportProduct = SupportProduct::where('supports_id', '=', $support->id)->get();
                foreach($supportProduct as $sp){
                    $category = Category::find($sp->categories_id);
                    $product = Product::where($category->products_id)->get();
                    $count = $product->count();
                    for($i = 0; $i<$count; $i++){
                        $products[$i] = $product[$i];
                    }
                }
                $supports[$count2] = $support;
                $count2++;
            }
            $data = array('supports' => $supports, 'products' => $products, 'categories' => $categories ,'id' => $id);
            // dd($data);
            return view('catalogs.suppliersProducts', $data);
        }
        else{
            return redirect($id);
        }
    }

    public function new(){
        $data = array('action' => 'new', 'title' => "Registrar nuevo proveedor");
        return view('catalogs.suppliersForm', $data);
    }

    public function updated(Request $request, $id){
        if(is_numeric($id)){
            $supplier = Supplier::find($id);
            $supplier->ismoral = strlen($supplier->RFC) == 13 ? 1 : 0;
            $communities = array();
            $municipalities = array();
            $states = array();
            $phonenumbers_supplier = SupplierPhoneNumber::where('suppliers_id','=',$supplier->id)->get();
            $phoneNumbers = array(); 
            $countPhoneNumbers = $phonenumbers_supplier->count();
            for($i=0; $i<$countPhoneNumbers; $i++){
                $phonenumber_supplier = $phonenumbers_supplier[$i];
                $phoneNumber = PhoneNumber::find($phonenumber_supplier->phoneNumbers_id);
                $phoneNumbers['phoneNumber'.($i+1)] = $phoneNumber->number;
                $phoneNumbers['ext'.($i+1)] = $phonenumber_supplier->ext;
                $phoneNumbers['description'.($i+1)] = $phonenumber_supplier->description;
            }
            $supplier->countPhoneNumber = $countPhoneNumbers;
            $supplier->phonesNumbers = $phoneNumbers;

            $addresses_supplier = AddressSupplier::where('suppliers_id','=',$supplier->id)->get();
            $addresses= array();
            $countAddresses = $addresses_supplier->count();
            for($a=0; $a<$countAddresses;$a++){
                $address_supplier = $addresses_supplier[$a];
                $address = Address::find($address_supplier->addresses_id);
                $addresses['street'.($a+1)] = $address->street;
                $addresses['externalNumber'.($a+1)] = $address->externalNumber;
                $addresses['internalNumber'.($a+1)] = $address->internalNumber;
                $addresses['communities_id'.($a+1)] = $address->communities_id;
                $community = Community::find($address->communities_id);
                $addresses['postalCode'.($a+1)] = $community->postalCode;
                $municipality = Municipality::find($community->municipalities_id);
                $state = State::find($municipality->states_id);
                $addresses['municipalities_id'.($a+1)] = $municipality->id;
                $addresses['municipalities_name'.($a+1)] = $municipality->name;
                $addresses['states_id'.($a+1)] = $state->id;
                $addresses['states_name'.($a+1)] = $state->name;
                $communities = Community::where('postalCode','=',$community->postalCode)->get();
                $addresses['communities'.($a+1)] = $communities;
            }



            $supplier->countAddress = $countAddresses;
            $supplier->addresses = $addresses;
            $data = array('supplier' => $supplier,
                'action' => 'update',
                'communities' => $supplier['addresses']['communities1'],
                'title' => "Modificar proveedor"
            );
            // dd($supplier['addresses']['municipalities1']->id);
            return view('catalogs.suppliersForm', $data);
        }
        else{
            return redirect($id);
        }
    }

    public function save(Request $request){
        switch($request->input('action')){
            case 'new':
                    // dd($request);
                $supplier = Supplier::create([
                    'companyname' => $request->companyname,
                    'RFC' => $request->RFC,
                    'email' => $request->email,
                    'active' => $request->active == "on" ? 1 : 0,
                    'description' =>$request->description,
                    'department' => session('department_institute_id')
                ]);
                $supplier->save();

                $countAddresses = $request->countAddress;
                $countPhoneNumbers = $request->countPhoneNumber;

                for($i = 1; $i <= $countAddresses; $i++){
                    if($request['street'.$i] != null){
                        $address = Address::create([
                            'street' => $request['street'.$i],
                            'externalNumber' => $request['externalNumber'.$i],
                            'internalNumber' => $request['internalNumber'.$i],
                            'communities_id' => $request['communities_id'.$i]
                        ]);
                        $address->save();
                        $addresses_suppliers = AddressSupplier::create([
                            'addresses_id' => $address->id,
                            'suppliers_id' => $supplier->id
                        ]);
                        $addresses_suppliers->save();    
                    }
                }
                for($a = 1; $a <= $countPhoneNumbers; $a++){
                    if($request['phonenumber'.$a] != null){
                        $phonenumber = PhoneNumber::create([
                            'number' => $request['phonenumber'.$a]
                        ]);
                        $phonenumber->save();   
                        $suppliers_phone_numbers = SupplierPhoneNumber::create([
                            'phoneNumbers_id' => $phonenumber->id,
                            'suppliers_id' => $supplier->id,
                            'ext' => $request['ext'.$a],
                            'description' => $request['description'.$a]
                        ]);
                        $suppliers_phone_numbers->save();
                    }
                }
            return redirect('proveedores')->with('success','Tus datos fueron almacenados de forma satisfactoria.'); 
            break;
            case 'update':
                $supplier = Supplier::find($request->id);
                $supplier->companyname = $request->companyname;
                $supplier->RFC = $request->RFC;
                $supplier->email = $request->email;
                $supplier->active = $request->active == "on" ? 1 : 0;
                $supplier->description = $request->description;
                $supplier->save();

                $suppliers_phone_numbers = SupplierPhoneNumber::where('suppliers_id','=',$supplier->id)->get();
                $qtyPhoneNumbers = $suppliers_phone_numbers->count();
                $countPhoneNumbers = $request->countPhoneNumber;
                $totalFPhoneNumbers = $request->countTotalPN;
                
                for($i = 1; $i<= $countPhoneNumbers; $i++){
                    if($request['phonenumber'.$i] != null){
                        if($i <= $qtyPhoneNumbers){
                            $supplierPhoneNumber = $suppliers_phone_numbers[$i-1];
                            $supplierPhoneNumber->ext = $request['ext'.$i];
                            $supplierPhoneNumber->description = $request['description'.$i];
                            $supplierPhoneNumber->save();
                            $phoneNumber = PhoneNumber::find($supplierPhoneNumber->phoneNumbers_id);
                            $phoneNumber->number = $request['phonenumber'.$i];
                            $phoneNumber->save();
                            
                        }
                        else{
                            $phonenumber = PhoneNumber::create([
                                'number' => $request['phonenumber'.$i]
                            ]);
                            $phonenumber->save();   
                            $new_suppliers_phone_numbers = SupplierPhoneNumber::create([
                                'phoneNumbers_id' => $phonenumber->id,
                                'suppliers_id' => $supplier->id,
                                'ext' => $request['ext'.$i],
                                'description' => $request['description'.$i]
                            ]);
                            $new_suppliers_phone_numbers->save();
                        }
                    }
                    else{
                        if($i <= $totalFPhoneNumbers && isset($suppliers_phone_numbers[$i-1])){
                            $phoneNumberSupplier = PhoneNumber::find($suppliers_phone_numbers[$i-1]->phoneNumbers_id);
                            $suppliers_phone_numbers[$i-1]->delete();
                            $phoneNumberSupplier->delete();
                        }
                     }
                }
                $addresses_suppliers = AddressSupplier::where('suppliers_id','=',$supplier->id)->get();
                $qtyAddresses = $addresses_suppliers->count();
                $countAddresses = $request->countAddress;
                $countTotalA = $request->countTotalA;
                
                for($a = 1; $a<= $countAddresses; $a++){
                    if($request['street'.$a] != null){
                        if($a <= $qtyAddresses){
                            $supplierAddress = $addresses_suppliers[$a-1];
                            $address = Address::find($supplierAddress->addresses_id);
                            $address->street = $request['street'.$a];
                            $address->externalNumber = $request['externalNumber'.$a];
                            $address->internalNumber = $request['internalNumber'.$a];
                            $address->communities_id = $request['communities_id'.$a];
                            $address->save();
                        }
                        else{
                            $address = Address::create([
                                'street' => $request['street'.$a],
                                'externalNumber' => $request['externalNumber'.$a],
                                'internalNumber' => $request['internalNumber'.$a],
                                'communities_id' => $request['communities_id'.$a]
                            ]);
                            $address->save();
                            $new_addresses_suppliers = AddressSupplier::create([
                                'addresses_id' => $address->id,
                                'suppliers_id' => $supplier->id
                            ]);
                            $new_addresses_suppliers->save();    
                        }
                    }
                    else{
                        if($a <= $countTotalA && isset($addresses_suppliers[$a-1])){
                            $addressSupplier = Address::find($addresses_suppliers[$a-1]->addresses_id);
                            $addresses_suppliers[$a-1]->delete();
                            $addressSupplier->delete();    
                        }
                    }
                }
                
                return redirect('proveedores')->with('success','Tus datos fueron almacenados de forma satisfactoria.'); 
            break;
            case 'getData':
                $communities = Community::where('postalCode','=',$request->postalCode)->get();
                $data = array();
                if($communities->count() != 0){
                    foreach($communities as $community)
                    {
                        $municipalities = Municipality::find($community->municipalities_id);
                        $states = State::find($municipalities->states_id);    
                    }
                    $data = array('communities' => $communities,
                    'municipalities' => $municipalities,
                    'states' => $states
                    );
                }
                return $data;
            break;
        }
        return array();
    }

    public function suppliers(Request $request){
        switch($request->input('action')){
        case "query":
            $suppliers = Supplier::all();
            $count = 1;
            foreach ($suppliers as $value) 
            {
                $supplier_phonenumber = SupplierPhoneNumber::where('suppliers_id','=',$value['id'])->get();
                $countPhones = $supplier_phonenumber->count();
                $countPN = 1;
                $phonenumbers = "";
                foreach($supplier_phonenumber as $spn)
                {
                    if($countPN < $countPhones){
                        $phonenumber = PhoneNumber::find($spn['phoneNumbers_id']);
                        $phonenumbers = $phonenumbers.$phonenumber['number'].'/'.'</br>';
                        $countPN++;
                    }
                    else{
                        $phonenumber = PhoneNumber::find($spn['phoneNumbers_id']);
                        $phonenumbers = $phonenumbers.$phonenumber['number'];
                    }
                }
                $value->phones = $phonenumbers;
                $addresses_supplier = AddressSupplier::where('suppliers_id','=',$value['id'])->first();
                $fulladdress = "";
                $address = Address::find($addresses_supplier['addresses_id']);
                $community = Community::find($address['communities_id']);
                $municipality = Municipality::find($community['municipalities_id']);
                $state = State::find($municipality['states_id']);
                $fulladdress = $address['street'].' #'.$address['externalNumber'].' '.$address['internalNumber'].' '.$community['name'].' C.P.'.$community['postalCode'].' ,'.$municipality['name'].' ,'.$state['name'];
                $value->fulladdress = $fulladdress;
                $value->status = $value->active == 1 ? "Activo" : "Inactivo";
                $value->number = $count++;
                $value->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a>
                <a class="addProduct" id="addProduct" title="Agregar Productos"><i class="fas fa-plus-square"></i></a> 
                            <a class="remove" id="remove" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return $suppliers;
        break; 
        
        case 'delete':
            $supplier = Supplier::find($request->registerId);
            $supplier->active = 0;
            $supplier->save();
            return redirect('proveedores')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
            }
    }

    public function productsSuppliers(Request $request, $id){
        switch($request->input('action')){
            case "query":
                $supplier_products = SupplierProduct::where('suppliers_id','=',$id)->get();
                $count = 1;
                foreach($supplier_products as $supplier_product){
                    $product = Product::find($supplier_product->products_id);
                    $supplier_product->product = $product->name;
                    $supplier_product->code = $product->code;
                    $supplier_product->cost = '$'.$supplier_product->price;
                    $supplier_product->number = $count++;
                    $supplier_product->status = $supplier_product->active == 1 ? "Activo" : "Inactivo";
                    $supplier_product->created_date = date_format($supplier_product->created_at,"d/m/Y");
                    $supplier_product->updated_date = date_format($supplier_product->updated_at,"d/m/Y");
                    $supplier_product->actions = '<a class="update" id="updateProduct" title="Modificar"> <i class="fas fa-edit"></i></a> 
                                <a class="remove" id="deleteProduct" title="Eliminar"><i class="fas fa-trash"></i></a>';
                }
                return $supplier_products;
            break;
            case 'new':
                $supplierProduct = SupplierProduct::create([
                        'price' => $request->price,
                        'active' => $request->active == "on" ? 1 : 0,
                        'products_id' => $request->products_id,
                        'suppliers_id' => $id
                ]);
                $supplierProduct->save();

                $product = Product::find($request->products_id);
                $supplier = Supplier::find($id);
                $productLog = ProductLog::create([
                    'suppliersProducts_id' => $supplierProduct->id,
                    'price' => $request->price,
                    'productName' => $product->name,
                    'supplierName' => $supplier->companyname

                ]);
                $productLog->save();
                return redirect('/productosdelproveedor/'.$id)->with('success','Tus datos fueron almacenados de forma satisfactoria.');
            break; 
            case 'newProduct':
                $code = substr($request->nameProduct, 0, 4);
                // $status = $request->active == "on" ? 1 : 0;
                $product = Product::create([
                    'name' => $request->nameProduct,
                    'code' => $code,
                    'categories_id' => $request->categories_id
                ]);
                
                $product->save();

                $supportProduct = SupportProduct::create([
                    'supports_id' => $request->supports_id,
                    'active' => 1,
                    'categories_id'=> $request->categories_id
                ]);
                $supportProduct->save();
                return $product;
            break;
            case 'update':
                $supplierProduct = SupplierProduct::find($request->id);
                $supplierProduct->price = $request->price;
                $supplierProduct->products_id = $request->products_id;
                $supplierProduct->suppliers_id = $id;
                $supplierProduct->active = $request->active == "on" ? 1 : 0;
                $supplierProduct->save();

                $product = Product::find($request->products_id);
                $supplier = Supplier::find($id);
                $productLog = ProductLog::create([
                    'suppliersProducts_id' => $supplierProduct->id,
                    'price' => $request->price,
                    'productName' => $product->name,
                    'supplierName' => $supplier->companyname

                ]);
                $productLog->save();
                return redirect('/productosdelproveedor/'.$id)->with('success','Tus datos fueron almacenados de forma satisfactoria.');
            break;
            case 'delete':
                $supplierProduct = SupplierProduct::find($request->registerId);
                $supplierProduct->active = 0;
                $supplierProduct->save();
                return redirect('/productosdelproveedor/'.$id)->with('success','Tus datos fueron almacenados de forma satisfactoria.');
            break;
            default:
                return array();
            break;
        }
    }
}