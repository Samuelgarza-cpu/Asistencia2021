<?php

namespace App\Http\Controllers\inside;

use App\Role;
use App\User;
use App\Institute;
use App\Department;
use App\DepartmentInstitute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerfilController extends Controller
{
    public function index($id){
        if(is_numeric($id)){
            $generalData = User::find($id);
            $department_institute = DepartmentInstitute::find($generalData->departments_institutes_id);
            $department = Department::find($department_institute->departments_id);
            $institute = Institute::find($department_institute->institutes_id);
            $role = Role::find($generalData->roles_id);

            $generalData->department = $department->name;
            $generalData->institute = $institute->name;
            $generalData->role = $role->name;
            $generalData->stamp = $department_institute->stamp;
            $generalData->image = $department_institute->image;
            
            $data = array('generalData' => $generalData);
            return view('catalogs.perfil', $data);
        }
        else{
            return redirect($id);
        }
    }
}
