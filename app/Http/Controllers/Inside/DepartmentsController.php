<?php

namespace App\Http\Controllers\Inside;

use App\Department;
use App\DepartmentInstitute;
use App\Institute;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function index(){
        // $departments =Department::all();
        // $institutes = Institute::all();
        // foreach($departments as $department)
        // {
        //     $departmentInstitute = DepartmentInstitute::where('departments_id','=',$department->id)->first();
        //     $department->image = $departmentInstitute->image;

        //     if(isset($departmentInstitute->stamp)){
        //         $department->stampSRC = '../storage/'.$departmentInstitute->stamp;
        //         $department->stamp = $departmentInstitute->stamp;        
        //     }    
        //     if(isset($departmentInstitute->image)){
        //         $department->imageSRC = '../storage/'.$departmentInstitute->image;
        //         $department->image = $departmentInstitute->image;
        //     }
            
        // }
        // $datos = array('departments' => $departments,
        // 'institutes' => $institutes);
        // return view('catalogs.departments', $datos);
        return view('catalogs.departments');
    }
    public function departments(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $departments = Department::all();
            $count = 1;
            foreach ($departments as $department) 
            {
                // $departmentsInstitutes = DepartmentInstitute::where('departments_id','=',$department['id'])->get();
                // foreach ($departmentsInstitutes as $value)
                // {
                //     $institute = Institute::find($value['institutes_id']);
                //     $department->institutes_id = $institute->id;
                //     $department->institute = $institute->name;
                //     if(isset($value->stamp)){
                //         $department->stampSRC = '../storage/'.$value->stamp;
                //         $department->stamp = $value->stamp; 
                //     }    
                //     if(isset($value->image)){
                //         $department->imageSRC = '../storage/'.$value->image;
                //         $department->stamp = $value->image; 
                //     }
                // }
                $department->number = $count++;
                $department->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return  $departments;
        break;
        case 'new':
            $code = substr($request->name, 0, 4);

            // $stampFile = $request->file('stamp');
            // $stampName = 'sello-departamento-'.$stampFile->getClientOriginalName(); 
            // \Storage::disk('local')->put($stampName,  \File::get($stampFile));
            
            // $imageFile = $request->file('image');
            // $imageName = 'departamento-'.$imageFile->getClientOriginalName(); 
            // \Storage::disk('local')->put($imageName,  \File::get($imageFile));
            $department = Department::create([
                    'name' => $request->name,
                    'code' => $code
            ]);
            $department->save();
            
            // $departmentInstitute = DepartmentInstitute::create([
            //         'departments_id' => $department->id,
            //         'institutes_id' => $request->institutes_id,
            //         'stamp'=> $stampName,
            //         'image'=> $imageName
            // ]);
            // $departmentInstitute->save();

            return redirect('departamentos')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
            $department = Department::find($request->id);
            $department->name = $request->name;
            $department->code = substr($request->name, 0, 4);
            // $departmentInstitute = DepartmentInstitute::where('departments_id','=',$department->id)->first();

            // if($request->file('stamp') == ''){
            //     $stampName = $departmentInstitute->stamp;
            // }
            // else{
            //     $stampFile = $request->file('stamp');
            //     $stampName = 'sello-departamento-'.$stampFile->getClientOriginalName(); 
            //     Storage::disk('local')->put($stampName,  File::get($stampFile));
            // }

            // if($request->file('image') == '')
            // {
            //     $imageName =  $departmentInstitute->image;
            // }
            // else{
            //     $imageFile = $request->file('image');
            //     $imageName = 'departamento-'.$imageFile->getClientOriginalName(); 
            //     Storage::disk('local')->put($imageName,  File::get($imageFile));
            // }

            // $departmentInstitute->institutes_id = $request->institutes_id;
            // $departmentInstitute->stamp = $stampName;
            // $departmentInstitute->image = $imageName;  
            
            // $departmentInstitute->save();
            $department->save();
            return redirect('departamentos')->with('success','Tus datos fueron modificados de forma satisfactoria.');
        break;
        case 'delete':
            $department = Department::find($request->registerId);
            // $departmentInstitute = DepartmentInstitute::where('departments_id','=', $department->id);
            // $departmentInstitute->delete();
            $department->delete();
            return redirect('departamentos')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
            }
    }
}
