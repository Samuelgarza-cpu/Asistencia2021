<?php

namespace App\Http\Controllers\Inside;

use PDF;
use App\User;
use DateTime;
use App\State;
use App\Status;
use App\Address;
use App\Product;
use App\Service;
use App\Support;
use App\Category;
use App\Document;
use App\Supplier;
use App\Community;
use App\Furniture;
use App\InsDepSup;
use App\Employment;
use App\PhoneNumber;
use App\Requisition;

use App\Disabilities;
use App\EconomicData;
use App\Municipality;
use App\PersonalData;
use App\LifeCondition;
use App\RequestService;
use App\SupportProduct;
use App\ExtPersonalData;
use App\FamilySituation;
use App\RPDDisabilities;
use App\SupplierProduct;
use App\BuildingMaterial;
use App\deliberypictures;
use App\RequestFurniture;
use App\DepartmentInstitute;
use App\RequestInsDepSupPro;
use App\RequestPersonalData;
use Illuminate\Http\Request;
use App\DisabilityCategories;
use App\RequestSupplierProduct;
use App\PhoneNumberPersonalData;
use App\RequestBuildingMaterial;
use App\Mail\StatusSolicitudMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\DepartmentInstituteSupportProduct;

class RequestsController extends Controller
{
    public function index(){
        return view('catalogs.requests');
    }

    public function new(){
        $employments = Employment::all();
        $catdisability=DisabilityCategories::all();
        $disability=Disabilities::all();
        $supports = [];
        $categories = [];
        $department_supports = InsDepSup::where('departmentsInstitutes_id','=',session('department_institute_id'))->get();
        $count_ds = $department_supports->count();
        $existSup = true;
        for($i = 0; $i < $count_ds; $i++)
        {
            $valueDS = $department_supports[$i];

            $support = Support::find($valueDS->supports_id);
            if($supports != null){
                foreach($supports as $valueSup){
                    if($support->id == $valueSup->id){
                        $existSup = true;
                        break;
                    }
                    else{
                        $existSup = false;
                    }
                }
            }
            else{
                array_push($supports, $support);
                $category = Category::find($valueDS->categories_id);
             }
            if(!$existSup){
                array_push($supports, $support);
                $category = Category::find($valueDS->categories_id);
            }
        }

        $furnitures = Furniture::all();
        $buildingMaterials = BuildingMaterial::all();
        $services = Service::all();
        $suppliers = Supplier::all();
        $products = Product::all();
        $data = array(
            // 'communities' => $communities,
            // 'municipalities' => $municipalities,
            // 'states' => $states,
            'employments' => $employments,
            'furnitures' => $furnitures,
            'buildingMaterials' => $buildingMaterials,
            'services' => $services,
            'supports' => $supports,
            'suppliers' => $suppliers,
            'products' => $products,
            'categotydisability'=> $catdisability,
            'disabilities'=>$disability,
            'action' => 'new'
            );

          return view('catalogs.RequestsForm', $data);
    }

    public function update(){
        $states = State::all();
        $municipalities = Municipality::all();
        $communities = Community::all();
        $employments = Employment::all();

            $data = array('communities' => $communities,
            'municipalities' => $municipalities,
            'states' => $states,
            'employments' => $employments,
            'action' => 'update'
         );

          return view('catalogs.RequestsForm', $data);
    }

    // public function check(Request $request){
    //     $personaData = PersonalData::where('curp','=',$request->curp);
    //     if(isset($personaData)){
    //        $message = 'No existe un usuario con esa curp';
    //     }
    //     else{
    //         $message = 'Ya existe un usuario con esa curp';
    //     }
    //     return ($validator)->withInput();
    // }

    public function save(Request $request){
            switch($request->input('action')){

                case 'saveWOR':
                $requestCount = Requisition::all()->count();
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString='';
                for ($i = 0; $i < 7; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }

                $folio = 'R'.$randomString.$requestCount;

                if($request->file('petitionerImage') != ''){
                    $petitionerImageFile = $request->file('petitionerImage');
                    $imageName = 'solicitante-'.$petitionerImageFile->getClientOriginalName();
                    Storage::disk('local')->put($imageName,  \File::get($petitionerImageFile));
                }
                if($request->curpPetitioner1 != "" && $request->curpPetitioner1 != null){
                    $curpPetitioner = strtoupper($request->curpPetitioner1);
                }
                else{
                    $curpPetitioner = "";
                }
                $userAuth = User::where('roles_id', '=', 6)->first();
                $today = New Datetime();
                $requests = Requisition::create([
                    'folio' => $folio != null && $folio != "" ? $folio : '',
                    'petitioner' => $request->petitioner != null && $request->petitioner != "" ? $request->petitioner : '',
                    'curpPetitioner' => $curpPetitioner != null && $curpPetitioner != "" ? $curpPetitioner : '',
                    'beneficiary' => 0,
                    'type' => $request->type != null && $request->type != "" ? $request->type : '',
                    'supports_id' => $request->supports_id != null && $request->supports_id != "" ? $request->supports_id : 0,
                    'categories_id' => $request->categories_id != null && $request->categories_id != "" ? $request->categories_id : 0,
                    'description' => $request->reason != null && $request->reason != "" ? $request->reason : '',
                    'image' => $request->petitionerImage != null && $request->petitionerImage != "" ? $request->petitionerImage : '',
                    'users_id' => session('user_id'),
                    'usersAuth_id' => $userAuth != null && $userAuth != "" ? $userAuth: session('user_id'),
                    'status_id' => 8,
                    'date' => $request->date != "" && $request->date != null ? $request->date : $today,
                    'departments_institutes_id' => session('department_institute_id'),
                    'area' => $request->area != "" && $request->area != null ? $request->area : ''
                ]);
                $requests->save();


                $countProducts = $request->countProduct;

                for($i = 1; $i <= $countProducts; $i++){
                    if($request['suppliers_id'.$i] != 0){
                        $supplierProducts = SupplierProduct::where('products_id','=',$request['products_id'.$i])->where('suppliers_id','=',$request['suppliers_id'.$i])->first();

                        $requestSuppliersProducts = RequestSupplierProduct::create([
                            'requests_id' => $requests->id != null ? $requests->id : 0,
                            'suppliersProducts_id' => $supplierProducts->id != null ? $supplierProducts->id :0,
                            'qty' => $request['qty'.$i] != null ? $request['qty'.$i] : 0
                        ]);
                        $requestSuppliersProducts->save();
                    } else{
                        $requestInsDepSupPro = RequestInsDepSupPro::create([
                            'requests_id' => $requests->id != null ? $requests->id : 0,
                            'products_id'=> $request['products_id'.$i] != null && $request['products_id'.$i] != "" ? $request['products_id'.$i] : 0,
                            'qty' => $request['qty'.$i] != null && $request['qty'.$i] != "" ? $request['qty'.$i] : 0,
                            'price' => $request['unitPrice'.$i] != null && $request['unitPrice'.$i] != "" ? $request['unitPrice'.$i] : 0
                        ]);
                        $requestInsDepSupPro->save();
                    }
                }

                $address = Address::create([
                    'street' => $request->street != null && $request->street != "" ? $request->street : "",
                    'externalNumber' => $request->externalNumber != null && $request->externalNumber != "" ? $request->externalNumber : "",
                    'internalNumber' => $request->internalNumber != null && $request->internalNumber != "" ? $request->internalNumber : "",
                    'communities_id' => $request->communities_id1 != null && $request->communities_id1 != "" ? $request->communities_id1 : 0
                ]);
                $address->save();

                $countBeneficiaries = $request->countBeneficiary;


                for($i = 1; $i <= $countBeneficiaries; $i++){
                    if($request['namebeneficiary'.$i] != null){
                        $curp = strtoupper($request['curpbeneficiary'.$i]);
                        $personalData = PersonalData::create([
                            'name' => $request['namebeneficiary'.$i] != null && $request['namebeneficiary'.$i] != "" ? $request['namebeneficiary'.$i] : "" ,
                            'lastName' => $request['lastNamebeneficiary'.$i] != null && $request['lastNamebeneficiary'.$i] != null ? $request['lastNamebeneficiary'.$i] : "",
                            'secondLastName' => $request['secondLastNamebeneficiary'.$i] != null && $request['secondLastNamebeneficiary'.$i] != "" ? $request['secondLastNamebeneficiary'.$i] : "",
                            'addresses_id' => $address->id != null && $address->id != "" ? $address->id : 0,
                            'curp' => $curp != null && $curp != "" ? $curp : "",
                            'age' => $request['agebeneficiary'.$i] != null && $request['agebeneficiary'.$i] != "" ? $request['agebeneficiary'.$i] : ""
                        ]);
                        $personalData->save();

                        $extPersonalData = ExtPersonalData::create([
                            'civilStatus' => $request['civilStatusbeneficiary'.$i] != null && $request['civilStatusbeneficiary'.$i] != "" ? $request['civilStatusbeneficiary'.$i] : "",
                            'scholarShip' => $request['scholarShipbeneficiary'.$i] != null && $request['scholarShipbeneficiary'.$i] != "" ? $request['scholarShipbeneficiary'.$i] : "",
                            'number' => $request['phonenumber'.$i] != null && $request['phonenumber'.$i] != "" ? $request['phonenumber'.$i] : "",
                            'employments_id' => $request['employments_idbeneficiary'.$i] != null && $request['employments_idbeneficiary'.$i] != "" ? $request['employments_idbeneficiary'.$i] : 0,
                            'personal_data_id' => $personalData->id != null && $personalData->id != "" ? $personalData->id : 0
                        ]);
                        $extPersonalData->save();

                        if($i == 0){
                            $requestPersonalData = RequestPersonalData::create([
                                'familiar' => 0,
                                'personalData_id' => $personalData->id != null && $personalData->id != "" ? $personalData->id : 0,
                                'requests_id' => $requests->id != null && $requests->id != "" ? $requests->id : 0
                            ]);
                            $requestPersonalData->save();
                        }
                        else{
                            $requestPersonalData = RequestPersonalData::create([
                                'familiar' => 1,
                                'personalData_id' => $personalData->id != null && $personalData->id != "" ? $personalData->id : 0,
                                'requests_id' => $requests->id != null && $requests->id != "" ? $requests->id : 0
                            ]);
                            $requestPersonalData->save();
                        }
                    }
                }

                $countMHs = $request->countMH;

                for($i = 1; $i <= $countMHs; $i++){
                    if($request['name'.$i] != null){
                        $familysituations = FamilySituation::create([
                            'name' => $request['name'.$i] != null && $request['name'.$i] != "" ? $request['name'.$i] : "",
                            'lastname' => $request['lastName'.$i] != null && $request['lastName'.$i] != "" ? $request['lastName'.$i] : "",
                            'secondlastname' => $request['secondLastName'.$i] != null && $request['secondLastName'.$i] != "" ? $request['secondLastName'.$i] : "",
                            'age' => $request['age'.$i] != null && $request['age'.$i] != "" ? $request['age'.$i] : "",
                            'relationship' => $request['relationship'.$i] != null && $request['relationship'.$i] != "" ? $request['relationship'.$i] : "",
                            'civilStatus' => $request['civilStatus'.$i] != null && $request['civilStatus'.$i] != "" ? $request['civilStatus'.$i] : "",
                            'scholarship' => $request['scholarShip'.$i] != null && $request['scholarShip'.$i] != "" ? $request['scholarShip'.$i] : "",
                            'employments_id' => $request['employments_id'.$i],
                            'requests_id' => $requests->id
                        ]);
                        $familysituations->save();
                    }
                }

                $lifeConditions = LifeCondition::create([
                    'typeHouse' => $request->typeHouse != null && $request->typeHouse != "" ? $request->typeHouse : "",
                    'number_rooms' => $request->number_rooms != null && $request->number_rooms != "" ? $request->number_rooms : 0,
                    'requests_id' => $requests->id != null && $requests->id != "" ? $requests->id : 0
                ]);
                $lifeConditions->save();

                $countFurnitures = $request->countFurniture;

                for($i = 1; $i <= $countFurnitures; $i++){
                    if($request['furnitures_id'.$i] != null){
                        $requestFurnitures = RequestFurniture::create([
                            'furnitures_id' => $request['furnitures_id'.$i] != null && $request['furnitures_id'.$i] != "" ? $request['furnitures_id'.$i] : 0,
                            'requests_id' => $requests->id != null && $requests->id != "" ? $requests->id : 0
                        ]);
                        $requestFurnitures->save();
                    }
                }

                $countBuildingMaterials = $request->countBuildingMaterial;

                for($i = 1; $i <= $countBuildingMaterials; $i++){
                    if($request['buildingMaterials_id'.$i] != null){
                        $requestBuildingMaterial = RequestBuildingMaterial::create([
                            'buildingMaterials_id' => $request['buildingMaterials_id'.$i] != null && $request['buildingMaterials_id'.$i] != "" ? $request['buildingMaterials_id'.$i] : 0,
                            'requests_id' => $requests->id != null && $requests->id != "" ? $requests->id : 0
                        ]);
                        $requestBuildingMaterial->save();
                    }
                }

                $countServices = $request->countService;

                for($i = 1; $i <= $countServices; $i++){
                    if($request['services_id'.$i] != null){
                        $requestServices = RequestService::create([
                            'services_id' => $request['services_id'.$i] != null && $request['services_id'.$i] != "" ? $request['services_id'.$i] : 0,
                            'requests_id' => $requests->id != null && $requests->id != "" ? $requests->id : 0
                        ]);
                        $requestServices->save();
                    }
                }
                $economicData = EconomicData::create([
                    'income' => $request->income != null &&  $request->income != "" ? $request->income : "",
                    'expense' => $request->expense != null && $request->expense != "" ? $request->expense : "",
                    'requests_id' => $requests->id != null && $requests->id != "" ? $requests->id : 0
                ]);
                $economicData->save();
                return redirect('solicitudes');
                    break;

                case 'new':

                $requestCount = Requisition::all()->count();
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString='';
                for ($i = 0; $i < 7; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }

                $folio = 'R'.$randomString.$requestCount;

                if($request->file('petitionerImage') != ''){
                    $petitionerImageFile = $request->file('petitionerImage');
                    $imageName = 'solicitante-'.$petitionerImageFile->getClientOriginalName();
                    Storage::disk('local')->put($imageName,  \File::get($petitionerImageFile));
                }

                $curpPetitioner = strtoupper($request->curpPetitioner1);
                $requests = Requisition::create([
                    'folio' => $folio,
                    'petitioner' => $request->petitioner,
                    'curpPetitioner' => $curpPetitioner,
                    'beneficiary' => 0,
                    'type' => $request->type,
                    'supports_id' => $request->supports_id,
                    'categories_id' => $request->categories_id,
                    'description' => $request->reason,
                    'image' => $request->petitionerImage,
                    'users_id' => session('user_id'),
                    'usersAuth_id' => session('user_id'),
                    'status_id' => 1,
                    'date' => $request->date,
                    'departments_institutes_id' => session('department_institute_id'),
                    'area' => $request->area
                ]);
                $requests->save();


                $countProducts = $request->countProduct;

                for($i = 1; $i <= $countProducts; $i++){
                    if($request['suppliers_id'.$i] != 0){
                        $supplierProducts = SupplierProduct::where('products_id','=',$request['products_id'.$i])->where('suppliers_id','=',$request['suppliers_id'.$i])->first();

                        $requestSuppliersProducts = RequestSupplierProduct::create([
                            'requests_id' => $requests->id,
                            'suppliersProducts_id' => $supplierProducts->id,
                            'qty' => $request['qty'.$i]
                        ]);
                        $requestSuppliersProducts->save();
                    } else{
                        $requestInsDepSupPro = RequestInsDepSupPro::create([
                            'requests_id' => $requests->id,
                            'products_id'=> $request['products_id'.$i],
                            'qty' => $request['qty'.$i],
                            'price' => $request['unitPrice'.$i]
                        ]);
                        $requestInsDepSupPro->save();
                    }
                }

                $address = Address::create([
                    'street' => $request->street,
                    'externalNumber' => $request->externalNumber,
                    'internalNumber' => $request->internalNumber,
                    'communities_id' => $request->communities_id1
                ]);
                $address->save();

                $countBeneficiaries = $request->countBeneficiary;


                for($i = 1; $i <= $countBeneficiaries; $i++){
                    if($request['namebeneficiary'.$i] != null){
                        $curp = strtoupper($request['curpbeneficiary'.$i]);
                        $personalData = PersonalData::create([
                            'name' => $request['namebeneficiary'.$i],
                            'lastName' => $request['lastNamebeneficiary'.$i],
                            'secondLastName' => $request['secondLastNamebeneficiary'.$i],
                            'addresses_id' => $address->id,
                            'curp' => $curp,
                            'age' => $request['agebeneficiary'.$i]
                        ]);
                        $personalData->save();

                        $extPersonalData = ExtPersonalData::create([
                            'civilStatus' => $request['civilStatusbeneficiary'.$i],
                            'scholarShip' => $request['scholarShipbeneficiary'.$i],
                            'number' => $request['phonenumber'.$i],
                            'employments_id' => $request['employments_idbeneficiary'.$i],
                            'personal_data_id' => $personalData->id
                        ]);
                        $extPersonalData->save();

                        if($i == 0){
                            $requestPersonalData = RequestPersonalData::create([
                                'familiar' => 0,
                                'personalData_id' => $personalData->id,
                                'requests_id' => $requests->id
                            ]);
                            $requestPersonalData->save();
                        }
                        else{
                            $requestPersonalData = RequestPersonalData::create([
                                'familiar' => 1,
                                'personalData_id' => $personalData->id,
                                'requests_id' => $requests->id
                            ]);
                            $requestPersonalData->save();
                        }



                        $countTBD= $request['countDiagnosticBeneficiary'.$i];

                        for($x = 1; $x <= $countTBD; $x++)
                        {
                            if($request['disability'.$i.'_'.$x] != null){
                                $rpddisabilities= RPDDisabilities::create([
                                    'disability_id'=> $request['disability'.$i.'_'.$x] != null && $request['disability'.$i.'_'.$x] != "" ? $request['disability'.$i.'_'.$x] : "" ,
                                    'disabilitycategories_id'=>$request['disabilitycategories'.$i.'_'.$x] != null && $request['disabilitycategories'.$i.'_'.$x] != "" ? $request['disabilitycategories'.$i.'_'.$x] : "" ,
                                    'requestsPersonalData_id'=> $requestPersonalData->id != null && $requestPersonalData->id != "" ? $requestPersonalData->id : 0
                                    ]);
                                $rpddisabilities->save();
                            }
                        }

                    }
                }

                $countMHs = $request->countMH;

                for($i = 1; $i <= $countMHs; $i++){
                    if($request['name'.$i] != null){
                        $familysituations = FamilySituation::create([
                            'name' => $request['name'.$i],
                            'lastname' => $request['lastName'.$i],
                            'secondlastname' => $request['secondLastName'.$i],
                            'age' => $request['age'.$i],
                            'relationship' => $request['relationship'.$i],
                            'civilStatus' => $request['civilStatus'.$i],
                            'scholarship' => $request['scholarShip'.$i],
                            'employments_id' => $request['employments_id'.$i],
                            'requests_id' => $requests->id
                        ]);
                        $familysituations->save();
                    }
                }

                $lifeConditions = LifeCondition::create([
                    'typeHouse' => $request->typeHouse,
                    'number_rooms' => $request->number_rooms,
                    'requests_id' => $requests->id
                ]);
                $lifeConditions->save();

                $countFurnitures = $request->countFurniture;

                for($i = 1; $i <= $countFurnitures; $i++){
                    if($request['furnitures_id'.$i] != null){
                        $requestFurnitures = RequestFurniture::create([
                            'furnitures_id' => $request['furnitures_id'.$i],
                            'requests_id' => $requests->id
                        ]);
                        $requestFurnitures->save();
                    }
                }

                $countBuildingMaterials = $request->countBuildingMaterial;

                for($i = 1; $i <= $countBuildingMaterials; $i++){
                    if($request['buildingMaterials_id'.$i] != null){
                        $requestBuildingMaterial = RequestBuildingMaterial::create([
                            'buildingMaterials_id' => $request['buildingMaterials_id'.$i],
                            'requests_id' => $requests->id
                        ]);
                        $requestBuildingMaterial->save();
                    }
                }

                $countServices = $request->countService;

                for($i = 1; $i <= $countServices; $i++){
                    if($request['services_id'.$i] != null){
                        $requestServices = RequestService::create([
                            'services_id' => $request['services_id'.$i],
                            'requests_id' => $requests->id
                        ]);
                        $requestServices->save();
                    }
                }
                $economicData = EconomicData::create([
                    'income' => $request->income,
                    'expense' => $request->expense,
                    'requests_id' => $requests->id
                ]);
                $economicData->save();




                return redirect('senddatabase');
                // return redirect('generardocumento/'.$requests->id);
                break;
                case 'checkCurp':
                    if($request->curpbeneficiary != null){
                        $curp = strtoupper($request->curpbeneficiary);
                        $exist = false;
                        $data = array();

                        $requisition = Requisition::where('curpPetitioner', '=', $curp)->get();
                        if(isset($requisition) && $requisition->count() > 0){
                            $data['requisition'] = $requisition[0];
                            foreach($requisition as $value){
                                $today = New Datetime();
                                $fecha1 = new DateTime($value->date);
                                $interval = $fecha1->diff($today);
                                if($interval->m < 1){
                                    $exist = true;
                                    break;
                                }
                                else
                                {
                                    $exist = false;
                                }
                            }
                        }
                    }
                    $personalData = PersonalData::where('curp','=', $curp)->get();
                    if(isset($personalData) && $personalData->count() > 0){
                        $data['personalData'] = $personalData[0];
                        foreach($personalData as $value){
                            $requisitionPersonalData = RequestPersonalData::where('personalData_id','=',$value->id)->get();
                            if(isset($requisition) && $requisition->count() > 0){
                                foreach($requisitionPersonalData as $element){
                                    $requisition = Requisition::find($element->requests_id);
                                    if(isset($requisition)){
                                        $today = New Datetime();
                                        $fecha1 = new DateTime($requisition->date);
                                        $interval = $fecha1->diff($today);

                                        if($interval->m < 1){
                                            $exist = true;
                                            break;
                                        }
                                        else
                                        {
                                            $exist = false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if($exist){
                        $text = 'Este usuario ya recibio un apoyo dentro del mes';
                        $id = 1;
                        $message = array('text' => $text, 'exist' => $id);
                        $data['message'] = $message;
                    }
                    else{
                        $text = 'Este usuario No ha recibido un apoyo dentro del mes';
                        $id = 0;
                        $message = array('text' => $text, 'exist' => $id);
                        $data['message'] = $message;
                    }
                    return $data;
                break;
                case 'uploadImage':
                    $folderPath = public_path('assets/img/petitioners/');
                    $image_parts = explode(";base64,", $request->image);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);

                    $imageName = $request->nameImage;

                    $imageFullPath = $folderPath.$imageName;

                    file_put_contents($imageFullPath, $image_base64);
                    return "hola";
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

                case 'getCategories':
                    $supportProducts = SupportProduct::where('supports_id', '=', $request->support)->get();
                    $categories = [];
                    foreach($supportProducts as $value){
                        $category = Category::find($value->categories_id);
                        array_push($categories, $category);
                    }
                    return $categories;
                break;
                case 'getSuppliers':
                    $products = Product::where('categories_id', '=', $request->category)->get();
                    $suppliers= [];
                    $existSup = true;
                    if($request->type == 'efectivo'){
                        if($products->count() > 0){
                            foreach($products as $value){
                                $supportProducts = SupplierProduct::where('products_id', '=', $value->id)->get();
                                if($supportProducts->count()>0){
                                    foreach($supportProducts as $sPdts){
                                        $supplier = Supplier::find($sPdts->suppliers_id);
                                        if($suppliers != null){
                                            if($supplier != null){
                                                foreach($suppliers as $valueSup){
                                                    if($supplier->id == $valueSup->id){
                                                        $existSup = true;
                                                        break;
                                                    }
                                                    else{
                                                        $existSup = false;
                                                    }

                                                }
                                            }
                                        }
                                        else{
                                            if($supplier != null)
                                                array_push($suppliers,$supplier);
                                        }
                                        if(!$existSup){
                                            array_push($suppliers,$supplier);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return $suppliers;
                break;
                case 'getDisabilities':
                    if($request->categoryDisability != "" && $request->categoryDisability != null){
                        $disabilities = Disabilities::where('disabilitycategories_id','=',$request->categoryDisability)->get();
                    }
                    return $disabilities;
                break;
                case "getProducts":
                    if($request->supplier == "0"){
                        $products = Product::where('categories_id','=',$request->category)->get();
                    }
                    else{
                        $products = [];
                        $supplierProducts = SupplierProduct::where('suppliers_id', '=', $request->supplier)->get();
                        foreach($supplierProducts as $supplierProduct){
                            $product = Product::find($supplierProduct->products_id);
                            if($product->categories_id == $request->category){
                                array_push($products, $product);
                            }
                        }
                    }
                    return $products;
                break;
                case "getPrice":
                    $price = "";
                    if($request->supplier != "0"){
                        $supplierProducts = SupplierProduct::where('products_id','=',$request->product)->where('suppliers_id','=',$request->supplier)->first();
                        $price = $supplierProducts->price;
                    }
                    return $price;
                break;
                case 'getFurnitures':
                    $furnitures = Furniture::all();
                    return $furnitures;
                break;
                case 'getBuildingMaterials':
                    $buildingMaterials = BuildingMaterial::all();
                    return $buildingMaterials;
                break;
                case 'getServices':
                    $services = Service::all();
                    return $services;
                break;
                case 'getInformation':
                    $employments = Employment::all();
                    $categoryDisabilities = DisabilityCategories::all();

                    $data = array(
                        'employments' => $employments,
                        'categoryDisabilities' => $categoryDisabilities
                    );
                    return $data;
                    break;
                case 'newProduct':
                    // $code = substr($request->name, 0, 4);
                    // $role = Role::create([
                    //         'name' => $request->name,
                    //         'code' => $code
                    // ]);
                    //     $role->save();

                    // return $product;
                break;

            }
            return array();
    }

    public function statusChange($folio = null){
        switch($folio){
            case 2:
                $status = "Pendiente Autorización";
                break;
            case 3:
                $status = "Entregada Pendiente Autorización";
                break;
            case 4:
                $status = "Autorizada Pendiente Entrega";
                break;
            case 5:
                $status = "Rechazada";
                break;
            case 6:
                $status = "Finalizada";
                break;
            case 7:
                $status = "Cancelada";
                break;
            case 8:
                $status = "Inconclusa";
                break;
            case 9:
                $status = "Pendiente de Verificación";
                break;
            default:
                break;
        }
        return $status;
    }

    public function requests(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $requests = Requisition::where('departments_institutes_id','=', session('department_institute_id'))->get();
            $count = 1;
            foreach ($requests as $value)
            {
                $requestPersonalData = RequestPersonalData::where('requests_id', '=', $value->id)->get();
                $requestCount = $requestPersonalData->count();
                $beneficiariesName = "";
                $beneficiariesCurp = "";
                $beneficiariesPhones = "";
                $address="";
                for($i = 1; $i <= $requestCount; $i++){
                    if($i == 1){
                        $personalData = PersonalData::find($requestPersonalData[$i-1]->personalData_id);
                        $extpersonalData = ExtPersonalData::where('personal_data_id','=',$personalData->id)->first();
                        $fullName = $personalData->name.' '. $personalData->lastName.' '.$personalData->secondLastName;
                        $beneficiariesName = $fullName.'/'.'<br/>';
                        $beneficiariesCurp = $personalData->curp.'/'.'<br/>';
                        $beneficiariesPhones = $extpersonalData->number.'/'.'<br/>';
                    }
                    if($i > 1 && $i != $requestCount){
                        $personalData = PersonalData::find($requestPersonalData[$i-1]->personalData_id);
                        $extpersonalData = ExtPersonalData::where('personal_data_id','=',$personalData->id)->first();
                        $fullName = $personalData->name.' '. $personalData->lastName.' '.$personalData->secondLastName;
                        $beneficiariesName = $beneficiariesName.$fullName.'/'.'<br/>';
                        $beneficiariesCurp = $beneficiariesCurp.$personalData->curp.'/'.'<br/>';
                        $beneficiariesPhones = $beneficiariesPhones.$extpersonalData->number.'/'.'<br/>';
                    }
                    else{
                        $personalData = PersonalData::find($requestPersonalData[$i-1]->personalData_id);
                        $extpersonalData = ExtPersonalData::where('personal_data_id','=',$personalData->id)->first();
                        $fullName = $personalData->name.' '. $personalData->lastName.' '.$personalData->secondLastName;
                        $beneficiariesCurp = $personalData->curp;
                        $beneficiariesName = $fullName;
                        $beneficiariesPhones = $extpersonalData->number;

                        $addresses = Address::find($personalData->addresses_id);
                        $community = Community::find($addresses->communities_id);
                        $municipality = Municipality::find($community->municipalities_id);
                        $state = State::find($municipality->states_id);
                        $address = $addresses->street.' #'.$addresses->externalNumber.' '.$addresses->internalNumber.' '.$community->name.' ,'.$municipality->name.' ,'.$state->name;
                    }
                }
                $rSP = RequestSupplierProduct::where('requests_id','=',$value->id)->get();
                $rSpCount = $rSP->count();
                $products = "";
                for($i = 0; $i < $rSpCount; $i++){
                    $suppliersProducts = SupplierProduct::find($rSP[$i]->suppliersProducts_id);
                    $product = Product::find($suppliersProducts->products_id);
                    $products = $products.$rSP[$i]->qty.' '.$product->name.'<br>';
                }

                $value->beneficiaries = $beneficiariesName;
                $value->beneficiariesCurp = $beneficiariesCurp;
                $value->beneficiariesNumber = $beneficiariesPhones;
                $value->address = $address;
                $value->products = $products;
                $value->number = $count;
                switch($value->status_id){
                    case 1:
                        $value->status = "Pendiente Anexo Archivos";
                        if(session('user_agent') != 'Admin' && session('user_agent') != 'DirGen'&& session('user_agent') != 'SuperAsSo'){
                            $value->actions = '
                            <a class="addDocument" id="addDocument" title="Anexar Documento"> <i class="fas fa-file"></i></a>
                            <a class="generatePDF" id="generatePDF" title="Generar Documentos"> <i class="fas fa-file"></i></a>';
                        }
                        else{
                            $value->actions ='<a class="cancel" id="cancel" title="Cancelar"> <i class="fas fa-times-circle"></i></a>';
                        }
                    break;
                    case 2:
                        $value->status = "Pendiente Autorización";
                        if(session('user_agent') != 'Admin' && session('user_agent') != 'DirGen'&& session('user_agent') != 'SuperAsSo'){
                            $value->actions = '
                            <a class="showDocument" id="showDocument" title="VerDocumento"> <i class="fas fa-eye"></i></a>';
                        }
                        else{
                        $value->actions = '
                            <a class="showDocument" id="showDocument" title="VerDocumento"> <i class="fas fa-eye"></i></a>
                            <a class="auth" id="auth" title="Autorizar"> <i class="fas fa-check-circle"></i></a>
                            <a class="nauth" id="nauth" title="Rechazar"> <i class="fas fa-times-circle"></i></a>
                            <a class="cancel" id="cancel" title="Cancelar"> <i class="fas fa-times-circle"></i></a>';
                        }
                    break;
                    case 3:
                        $value->status = "Entregada Pendiente Autorización";
                        if(session('user_agent') != 'Admin' && session('user_agent') != 'DirGen' && session('user_agent') != 'CoordiAsSo'&& session('user_agent') != 'SuperAsSo'){
                            $value->actions = '
                            <a class="showDocument" id="showDocument" title="VerDocumento"> <i class="fas fa-eye"></i></a>';
                        }
                        else{
                        $value->actions = '
                        <a class="showDocument"id="showDocument" title="VerDocumento"> <i class="fas fa-eye"></i></a>
                        <a class="auth" id="auth" title="Autorizar"> <i class="fas fa-check-circle"></i></a>
                        <a class="nauth" id="nauth" title="Rechazar"> <i class="fas fa-times-circle"></i></a>
                        <a class="cancel" id="cancel" title="Cancelar"> <i class="fas fa-times-circle"></i></a>';
                        }
                    break;
                    case 4:
                        $value->status = "Autorizada Pendiente Entrega";
                        if(session('user_agent') != 'Admin' && session('user_agent') != 'DirGen' && session('user_agent') != 'CoordiAsSo'&& session('user_agent') != 'SuperAsSo'){
                            $value->actions = '
                            <a class="showDocument" id="showDocument" title="VerDocumento"> <i class="fas fa-eye"></i></a>
                           ';

                        }
                        else{
                        $value->actions = '
                        <a class="showDocument" id="showDocument" title="VerDocumento"> <i class="fas fa-eye"></i></a>
                        <a class="addDeliveryPicture" id="addDeliveryPicture" title="Anexar Documento"> <i class="fas fa-camera"></i></a>';

                        }
                    break;
                    case 5:
                        $value->status = "Rechazada";
                        $value->actions = '<a class="showDocument" id="showDocument" title="VerDocumento"> <i class="fas fa-eye"></i></a>';
                    break;
                    case 6:
                        $value->status = "Finalizada";
                        $value->actions = '<a class="showDocument" id="showDocument" title="VerDocumento"> <i class="fas fa-eye"></i></a>';
                    break;
                    case 7:
                        $value->status = "Cancelada";
                        $value->actions = '-';
                    break;
                    case 8:
                        $value->status = "Incompleta";
                        if(session('user_agent') != 'Admin' && session('user_agent') != 'DirGen'){
                            $value->actions = '-';
                        }
                        else{
                            $value->actions ='<a class="cancel" id="cancel" title="Cancelar"> <i class="fas fa-times-circle"></i></a>';
                        }
                    break;
                    case 9:
                        $value->status = "Pendiente de Verificación";
                        if(session('user_agent') == 'Admin' || session('user_agent') == 'Cont'){
                            $value->actions ='<a class="verify" id="verify" title="Verificar"> <i class="fas fa-check"></i></a>';
                        }
                    break;
                    default:
                    break;
                }
                $count++;
            }
            return $requests;
        break;
        case 'addDocument':
            $requisition = Requisition::find($request->registerId);
            if($requisition->count() > 0){
                if($request->file('document') != ''){
                    $stampFile = $request->file('document');
                    $stampName = 'document-'.$requisition->folio.'.PDF';

                    $document = Document::create([
                        'name' => $stampName,
                        'requests_id' => $requisition->id
                    ]);
                    $document->save();

                    $status = $request->active == "on" ? 1 : 0;

                    if($request->file('deliveryImage') != '' && $request->file('deliveryImage') != null){
                        $stampFile = $request->file('deliveryImage');
                        $stampName = 'imagenEntrega-'.$requisition->folio.'.jpg';

                        $deliveryImage = deliberypictures::create([
                            'name' => $stampName,
                            'requests_id' => $requisition->id
                        ]);
                        $deliveryImage->save();

                        Storage::disk('local')->put($stampName,  \File::get($stampFile));
                    }

                    Storage::disk('local')->put($stampName,  \File::get($stampFile));

                    $requisition->status_id = $status == 1 ? 3 : 2;
                    $requisition->save();
                    $status = $this->statusChange($requisition->status_id);
                    $folio=array('folio'=>$requisition->folio, 'status' => $status);
                    Mail::to('cuentabusiness50@gmail.com')->send(new StatusSolicitudMail($folio));

                    return redirect('solicitudes')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
                }
                return redirect('solicitudes')->with('error','Tus datos no fueron almacenados de forma satisfactoria.');
            }
            return redirect('solicitudes')->with('error','Tus datos no fueron almacenados de forma satisfactoria.');
            break;
        case 'autorizar':
            $requisition = Requisition::find($request->registerId);
            if($requisition->status_id == 3)
                $requisition->status_id = 9;

            if($requisition->status_id == 2)

                $requisition->status_id = 4;

            $requisition->save();

            $status = $this->statusChange($requisition->status_id);
            $folio=array('folio'=>$requisition->folio, 'status' => $status);


             Mail::to('cuentabusiness50@gmail.com')->send(new StatusSolicitudMail($folio));

            return redirect('solicitudes')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;

        case 'Pendiente de Verificación':
            $requisition = Requisition::find($request->registerId);
            if($requisition->status_id == 9)
                $requisition->status_id = 6;

            // if($requisition->status_id == 2)

            //     $requisition->status_id = 4;

            $requisition->save();

            $status = $this->statusChange($requisition->status_id);
            $folio=array('folio'=>$requisition->folio, 'status' => $status);


            //  Mail::to('cuentabusiness50@gmail.com')->send(new StatusSolicitudMail($folio));

            return redirect('solicitudes')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;
        case 'rechazar':
            $requisition = Requisition::find($request->registerId);
            $requisition->status_id = 5;
            $requisition->save();
            $status = $this->statusChange($requisition->status_id);
            $folio=array('folio'=>$requisition->folio, 'status' => $status);


             Mail::to('cuentabusiness50@gmail.com')->send(new StatusSolicitudMail($folio));
            return redirect('solicitudes')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;
        case 'cancelar':
            $requisition = Requisition::find($request->registerId);
            $requisition->status_id = 7;
            $requisition->save();
            $status = $this->statusChange($requisition->status_id);
            $folio=array('folio'=>$requisition->folio, 'status' => $status);


             Mail::to('cuentabusiness50@gmail.com')->send(new StatusSolicitudMail($folio));
            return redirect('solicitudes')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;
        case 'finalizar':
            $requisition = Requisition::find($request->registerId);
            $requisition->status_id = 6;
            $requisition->save();
            $status = $this->statusChange($requisition->status_id);
            $folio=array('folio'=>$requisition->folio, 'status' => $status);


             Mail::to('cuentabusiness50@gmail.com')->send(new StatusSolicitudMail($folio));
            return redirect('solicitudes')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;
        case 'entregar':
            $requisition = Requisition::find($request->registerId);
            if($requisition->status_id == 2)
                $requisition->status_id = 3;

            if($requisition->status_id == 4)
                $requisition->status_id = 9;
            $requisition->save();
            $status = $this->statusChange($requisition->status_id);
            $folio=array('folio'=>$requisition->folio, 'status' => $status);


             Mail::to('cuentabusiness50@gmail.com')->send(new StatusSolicitudMail($folio));
            return redirect('solicitudes')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;
        case 'getDocument':
            $requisition = Requisition::find($request->id);
            if($requisition->count() > 0){
                $document = Document::where('requests_id','=',$requisition->id)->first();
                if($document->count() > 0){
                    $file = storage_path('app/public').'/'.$document->name;
                    $headers = array(
                       'Content-Type: application/pdf',
                      );
                    return response()->file($file, $headers);
                    // return $document;
                }
                return 'oa';
            }
            break;
        default:
            return array();
        break;
            }
    }

    public function showDoc($id){
        $requisition = Requisition::find($id);
        if($requisition->count() > 0){
            $document = Document::where('requests_id','=',$requisition->id)->first();
            if($document != null && $document->count() > 0){
                $file = storage_path('app/public').'/'.$document->name;
                $headers = array(
                   'Content-Type: application/pdf',
                  );
                return response()->file($file, $headers);
            }
            return redirect()->back();
        }
    }

    public function document(Request $request, $id){
        $requests = Requisition::find($id);

        if(isset($requests) && $requests->count() > 0){
            $folderPath = public_path('assets/img/petitioners/');
            $requests->public_path = $folderPath;
            $requests->mainPublic_path= public_path('storage/');
            $requests->images_path = public_path('assets/img/');
            $date = date_format($requests->created_at, 'd/m/Y');
            $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $day = date_format($requests->created_at, 'd');
            $month = date_format($requests->created_at, 'm');
            $year = date_format($requests->created_at, 'Y');
            $requests->date = $day.'/'.$months[$month-1].'/'.$year;

            $status = Status::find($requests->status_id);
            $requests->status = $status->name;

            $userAuth = User::find($requests->usersAuth_id);
            $departmentsInstitutes = DepartmentInstitute::find($userAuth->departments_institutes_id);
            $userAuth->stamp = $departmentsInstitutes->stamp;
            $user = User::find($requests->users_id);

            $requestSupplierProducts = RequestSupplierProduct::where('requests_id','=',$requests->id)->get();
            $requestServices = RequestService::where('requests_id','=',$requests->id)->get();
            $requestFurnitures = RequestFurniture::where('requests_id','=',$requests->id)->get();
            $requestBuildingMaterial = RequestBuildingMaterial::where('requests_id','=',$requests->id)->get();

            $requestPersonalData = RequestPersonalData::where('requests_id','=',$requests->id)->get();
            $requestProducts = RequestInsDepSupPro::where('requests_id','=',$requests->id)->get();

            $lifeCondition = LifeCondition::where('requests_id','=',$requests->id)->get();
            $economicData = EconomicData::where('requests_id','=',$requests->id)->get();
            $familySituation = FamilySituation::where('requests_id','=',$requests->id)->get();
            if($familySituation != null){
                foreach($familySituation as $value){
                    $employments = Employment::find($value->employments_id);
                    $value->employmentName = $employments->name;
                }
            }


            $personalDatas = [];
            $address = [];

            $products = [];

            if($requestSupplierProducts != null){
                foreach($requestSupplierProducts as $value){
                    $supplierProduct = SupplierProduct::find($value->suppliersProducts_id);
                    $product = Product::find($supplierProduct->products_id);
                    $supplier = Supplier::find($supplierProduct->suppliers_id);
                    $value->productName = $product->name;
                    $value->companyName = $supplier->companyname;
                    $value->RFC = $supplier->RFC;
                    $value->email = $supplier->email;
                    $value->description = $supplier->description;
                    $value->price = $supplierProduct->price;
                    $value->total = $supplierProduct->price * $value->qty;
                }
            }

            if($requestServices != null){
                foreach($requestServices as $value){
                    $service = Service::find($value->services_id);
                    $value->name = $service->name;
                }
            }

            if($requestFurnitures != null){
                foreach($requestFurnitures as $value){
                    $furniture = Furniture::find($value->furnitures_id);
                    $value->name = $furniture->name;
                }
            }


            if($requestBuildingMaterial != null){
                foreach($requestBuildingMaterial as $value){
                    $bM = BuildingMaterial::find($value->buildingMaterials_id);
                    $value->name = $bM->name;
                }
            }

            if($requestPersonalData != null){
                foreach($requestPersonalData as $value){
                    $personalData = PersonalData::find($value->personalData_id);
                    $extPersonalData = ExtPersonalData::where('personal_data_id','=',$personalData->id)->first();
                    $employments = Employment::find($extPersonalData->employments_id);
                    $address = Address::find($personalData->addresses_id);
                    $community = Community::find($address->communities_id);
                    $municipality = Municipality::find($community->municipalities_id);
                    $state = State::find($municipality->states_id);
                    $value->curp = $personalData->curp;
                    $value->name = $personalData->name;
                    $value->lastName = $personalData->lastName;
                    $value->secondLastName = $personalData->secondLastName;
                    $value->age = $personalData->age;
                    $value->familiar = $personalData->familiar;
                    $value->civilStatus = $extPersonalData->civilStatus;
                    $value->scholarShip = $extPersonalData->scholarShip;
                    $value->number = $extPersonalData->number;
                    $value->employmentName = $employments->name;

                    $address->community = $community;
                    $address->municipality = $municipality;
                    $address->state = $state;
                }
            }

            $data = array(
                'address' => $address,
                'requestPersonalData' => $requestPersonalData,
                'requestBuildingMaterial' => $requestBuildingMaterial,
                'requestSupplierProducts' => $requestSupplierProducts,
                'requestFurnitures' => $requestFurnitures,
                'requestServices' => $requestServices,
                'userAuth' => $userAuth,
                'user' => $user,
                'status' => $status,
                'lifeCondition' => $lifeCondition,
                'economicData' => $economicData,
                'familySituation' => $familySituation,
                'requisition' => $requests,
                'requestProducts' => $requestProducts
            );
            $pdf = PDF::loadView('PDF.requestsDocument', $data);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('pdf_file.pdf');
        }
        else
        return redirect()->back();
        //  return view('PDF.requestsDocument', $data);
    }
}
