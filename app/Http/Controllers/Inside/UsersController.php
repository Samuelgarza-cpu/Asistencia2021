<?php

namespace App\Http\Controllers\Inside;

use App\User;
use App\Role;
use App\Department;
use App\DepartmentInstitute;
use App\Institute;
use App\Events\eventusernew;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Mail\UpdateUser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Mail\UsuarioNuevo;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function index(){
        return view('catalogs.users');
    }

    public function new(){
        $roles = Role::all();
        $departments = Department::all();

        foreach($departments as $department)
        {
            $department_institute = DepartmentInstitute::where('departments_id','=',$department->id)->first();
            if($department_institute != null){
                $institute = Institute::find($department_institute->institutes_id);
                $department->id_of_institute_deparment = $department_institute->id;
                $department->name_with_institute = $department->name.' de '.$institute->name;
            }
        }

        $data = array('roles' => $roles,
            'departments' => $departments,
            'action' => 'new'
            );
          return view('catalogs.usersForm', $data);
    }

    public function updated(Request $request, $id){
        if(is_numeric($id)){
        $roles = Role::all();
        $user = User::find($id);
        if(isset($user->signature)){
            $user->signatureSRC = '../storage/'.$user->signature;
        }
        $departments = Department::all();
        foreach($departments as $department)
        {
            $department_institute = DepartmentInstitute::where('departments_id','=',$department->id)->first();
            if($department_institute != null){
                $institute = Institute::find($department_institute->institutes_id);
                $department->id_of_institute_deparment = $department_institute->id;
                $department->name_with_institute = $department->name.' de '.$institute->name;
            }
        }
        $data = array('roles' => $roles,
            'departments' => $departments,
            'action' => 'update',
            'user' => $user
            );
          return view('catalogs.usersForm', $data);
        }
        else{
            return redirect($id);
        }
    }

    public function save(Request $request){
        switch($request->input('action')){
            case "new":
                $imageFile = $request->file('signature');
                $imageName = $imageFile->getClientOriginalName();
                $user = User::create([
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'email' => $request->email,
                    'signature' => $imageName,
                    'active' => $request->active == "on" ? 1 : 0,
                    'owner' => $request->owner,
                    'roles_id' => $request->roles_id,
                    'departments_institutes_id' => $request->departments_institutes_id
                ]);

                   $datos=array([
                    'usuario'=> $request->owner,
                    'email'=>$request->email,
                    'name' => $request->name,
                    'password' =>$request->password


                ]);

                $usuarios=User::all();
                event(new eventusernew($usuarios));
                Mail::to($request->email)->send(new UsuarioNuevo($datos));
                $user->save();
                \Storage::disk('local')->put($imageName,  \File::get($imageFile));
                return redirect('usuarios')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
            break;

            case "update":
                $user = User::find($request->id);

                if(isset($user)){
                    if(isset($request->signature)){
                        $imageFile = $request->file('signature');
                        $imageName = $imageFile->getClientOriginalName();
                        \Storage::disk('local')->put($imageName,  \File::get($imageFile));
                        $user->signature = $imageName;
                    }
                    $user->name = $request->name;
                    $password = $request->password == $user->password ? '' : Hash::make($request->password);
                    if($password != ''){
                        $user->password = $password;
                    }
                    $user->email = $request->email;
                    $user->active = $request->active == "on" ? 1 : 0;
                    $user->owner = $request->owner;
                    $user->roles_id = $request->roles_id;
                    $user->departments_institutes_id = $request->departments_institutes_id;
                    $user->save();

                    $datos=array([
                        'usuario'=> $request->owner,
                        'email'=>$request->email,
                        'name' => $request->name,
                        'password' =>$request->password
                        ]);

                    if($request->password != $user->password && $request->name == $user->name)
                    {

                            Mail::to($request->email)->send(new UpdateUser($datos));




                    }
                    elseif($request->name != $user->name && $request->password != $user->password){

                        Mail::to($request->email)->send(new UpdateUser($datos));

                    }






                }
                return redirect('usuarios')->with('success','Tus datos fueron almacenados de forma satisfactoria.');
            break;

            default:
                return array();
            break;
        }
    }

    public function users(Request $request)
    {
        switch($request->input('action')){
        case "query":
            // $users = User::where('active','=',1)->get();
            $users = User::all();
            $activeUsers = array();
            $count = 1;
            foreach ($users as $user)
            {
                $role=Role::find($user['roles_id']);
                $department_institute = DepartmentInstitute::find($user['departments_institutes_id']);
                $department = Department::find($department_institute['departments_id']);
                $institute = Institute::find($department_institute['institutes_id']);
                $user->number = $count++;
                $user->role = $role->name;
                $user->department= $department->name;
                $user->institute = $institute->name;
                $user->status = $user->active == 1 ? "Activo" : "Inactivo";
                $user->actions = '<a class="update" id="update" title="Modificar"> <i class="fas fa-edit"></i></a>
                <a class="remove" id="delete" title="Eliminar"><i class="fas fa-trash"></i></a>';
            }
            return $users;
        break;
        case 'delete':
            $user = User::find($request->registerId);
            // $user->delete();
            $user->active = 0;
            $user->save();
            return redirect('usuarios')->with('success','el registro se elimino de forma satisfactoria.');
        break;
        default:
            return array();
        break;
        }
    }
}
