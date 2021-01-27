<?php

namespace App\Http\Controllers\Inside;

use App\Institute;
use App\Department;
use App\DepartmentInstitute;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;

class InstitutesDepartmentsController extends Controller
{
    public function index(){
        $departmentInstitutes = DepartmentInstitute::all();
        $departments =Department::all();
        $institutes = Institute::all();
        
        foreach($departmentInstitutes as $departmentInstitute)
        {
            $department = Department::find($departmentInstitute->departments_id);
            $institute = Institute::find($departmentInstitute->institutes_id);
            
            $departmentInstitute->department = $department->name;
            $departmentInstitute->institute = $institute->name;
            
            if(isset($departmentInstitute->stamp))
                $departmentInstitute->stampSRC = '../storage/'.$departmentInstitute->stamp;

            if(isset($departmentInstitute->image))
                $departmentInstitute->imageSRC = '../storage/'.$departmentInstitute->image;
        }
        $datos = array('departments' => $departments,
        'institutes' => $institutes,
        'departmentInstitutes' =>$departmentInstitutes
        );
        return view('catalogs.departmentInstitute', $datos);
    }

    public function instituteDepartment(Request $request)
    {
        switch($request->input('action')){
        case "query":
            $departmentInstitutes = DepartmentInstitute::all();            
            $count = 1;
            foreach ($departmentInstitutes as $departmentInstitute) 
            {
                $department = Department::find($departmentInstitute->departments_id);
                $institute = Institute::find($departmentInstitute->institutes_id);
                $departmentInstitute->institute = $institute->name;
                $departmentInstitute->department = $department->name;
               
                if(isset($departmentInstitute->stamp))
                    $departmentInstitute->stampSRC = '../storage/'.$departmentInstitute->stamp;

                if(isset($departmentInstitute->image))
                        $departmentInstitute->imageSRC = '../storage/'.$departmentInstitute->image;
                
                $departmentInstitute->number = $count++;
                $departmentInstitute->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a> 
                            <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return  $departmentInstitutes;
        break;
        case 'new':
            if($request->file('stamp') != ''){
                $stampFile = $request->file('stamp');
                $stampName = 'sello-departamento-'.$stampFile->getClientOriginalName(); 
            }

            if($request->file('image') != ''){
                $imageFile = $request->file('image');
                $imageName = 'departamento-'.$imageFile->getClientOriginalName();                 
            }
            $departmentInstitute = DepartmentInstitute::create([
                    'departments_id' => $request->departments_id,
                    'institutes_id' => $request->institutes_id,
                    'stamp'=> $stampName,
                    'image'=> $imageName
            ]);
            $departmentInstitute->save();

            \Storage::disk('local')->put($stampName,  \File::get($stampFile));
            \Storage::disk('local')->put($imageName,  \File::get($imageFile));
            return redirect('instituto_departamento')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
        break;  
        case 'update':
            $departmentInstitute = DepartmentInstitute::find($request->id);
            
            if($request->file('stamp') != ''){
                $stampFile = $request->file('stamp');
                $stampName = 'sello-departamento-'.$stampFile->getClientOriginalName(); 
                $departmentInstitute->stamp = $stampName;
                \Storage::disk('local')->put($stampName,  \File::get($stampFile));
            }

            if($request->file('image') != ''){
                $imageFile = $request->file('image');
                $imageName = 'departamento-'.$imageFile->getClientOriginalName();                 
                $departmentInstitute->image = $imageName;  
                \Storage::disk('local')->put($imageName,  \File::get($imageFile));
            }

            $departmentInstitute->departments_id = $request->departments_id;
            $departmentInstitute->institutes_id = $request->institutes_id;
            
            $departmentInstitute->save();

            return redirect('instituto_departamento')->with('success','Tus datos fueron modificados de forma satisfactoria.');
        break;
        case 'delete':
            $departmentInstitute = DepartmentInstitute::find($request->registerId);
            $departmentInstitute->delete();
            return redirect('instituto_departamento')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
            }
    }
}
