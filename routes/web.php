<?php

use App\Role;
use App\User;
use App\Institute;
use App\Department;
use App\DepartmentInstitute;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\Filters\DemoFilter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function(){
    if(Auth::check()){
        if(session('user_agent') == 'Soport')
            return redirect('/usuarios');
        elseif(session('user_agent') == 'TrSo')
            return redirect('/solicitudes');
        else
            return redirect('/dashboard');
    }
    else{

        return redirect('/login');
    }
});

Route::middleware(['auth'])->group(function () {
    //MAIN
    Route::get('/home', 'dashboard\HomeController');
    Route::get('/dashboard','dashboard\DashboardController');
    Route::get('/logout','auth\LogoutController');
    Route::get('/cookieset/{ecript?}','CacheController@setCookie');
    Route::get('/cookieget','CacheController@getCookie');


    //Catalogs
    Route::get('/institutos', 'inside\InstitutesController@index');
    Route::post('/institutos', 'inside\InstitutesController@institutes');

    Route::get('/departamentos', 'inside\DepartmentsController@index');
    Route::post('/departamentos', 'inside\DepartmentsController@departments');

    Route::get('/roles', 'inside\RolesController@index');
    Route::post('/roles', 'inside\RolesController@roles');

    Route::get('/apoyos', 'inside\SupportsController@index');
    Route::post('/apoyos', 'inside\SupportsController@supports');

    Route::get('/ocupaciones', 'inside\EmploymentsController@index');
    Route::post('/ocupaciones', 'inside\EmploymentsController@employments');

    Route::get('/estados_solicitud', 'inside\StatusController@index');
    Route::post('/estados_solicitud', 'inside\StatusController@status');

    Route::get('/categorias', 'inside\CategoriesController@index');
    Route::post('/categorias', 'inside\CategoriesController@categories');

    Route::get('/productos', 'inside\ProductsController@index');
    Route::post('/productos', 'inside\ProductsController@products');

    Route::get('/muebles', 'inside\FurnituresController@index');
    Route::post('/muebles', 'inside\FurnituresController@furnitures');

    Route::get('/servicios', 'inside\ServicesController@index');
    Route::post('/servicios', 'inside\ServicesController@services');

    Route::get('/materialesConstruccion', 'inside\buildingMaterialsController@index');
    Route::post('/materialesConstruccion', 'inside\buildingMaterialsController@buildingMaterials');

    Route::get('/categoria-diagnostico', 'Disabilitycategory@index');
    Route::post('/categoria-diagnostico', 'Disabilitycategory@products');

    Route::get('/diagnostico', 'DisabilitiesController@index');
    Route::post('/diagnostico', 'DisabilitiesController@data');

    //Relations//Connections
    Route::get('/instituto_departamento', 'inside\InstitutesDepartmentsController@index');
    Route::post('/instituto_departamento', 'inside\InstitutesDepartmentsController@instituteDepartment');

    Route::get('/productos_apoyos', 'inside\SupportProductController@index');
    Route::post('/productos_apoyos', 'inside\SupportProductController@supportProducts');

    Route::get('/apoyos_departamento', 'inside\SupportDepartmentController@index');
    Route::post('/apoyos_departamento', 'inside\SupportDepartmentController@departmentSupports');

    //Operational
    Route::get('/proveedores', 'inside\SuppliersController@index');
    Route::post('/proveedores', 'inside\SuppliersController@suppliers');
    Route::get('/registrar_proveedor', 'inside\SuppliersController@new');
    Route::post('/registrar_proveedor', 'inside\SuppliersController@save');
    Route::get('/modificar_proveedor/{id}', 'inside\SuppliersController@updated');
    Route::post('/modificar_proveedor/{id?}', 'inside\SuppliersController@save');
    Route::get('/productosdelproveedor/{id}', 'inside\SuppliersController@indexPP');
    Route::post('/productosdelproveedor/{id}', 'inside\SuppliersController@productsSuppliers');

    Route::get('/solicitudes', 'inside\RequestsController@index');
    Route::post('/solicitudes', 'inside\RequestsController@requests');
    Route::get('/nueva_solicitud', 'inside\RequestsController@new');
    Route::post('/nueva_solicitud', 'inside\RequestsController@save');
    Route::get('/modificar_solicitud/{id}', 'inside\RequestsController@updated');
    Route::post('/modificar_solicitud/{id}', 'inside\RequestsController@save');
    Route::get('/generardocumento/{id}','inside\RequestsController@document');
    Route::get('/verdocumento/{id}','inside\RequestsController@showDoc');

    //configuration//Control
    Route::get('/perfil/{id}', 'inside\PerfilController@index');

    Route::get('/usuarios', 'inside\UsersController@index');
    Route::post('/usuarios', 'inside\UsersController@users');
    Route::get('/registrar_usuario', 'inside\UsersController@new');
    Route::post('/registrar_usuario', 'inside\UsersController@save');
    Route::get('/modificar_usuario/{id}', 'inside\UsersController@updated');
    Route::post('/modificar_usuario/{id}', 'inside\UsersController@save');

    //Reports

    //pendientes

    Route::get('/reporte_proveedor', 'reports\ReportsController@reportSuppliers');
    Route::post('/reporte_proveedor/{id?}', 'reports\ReportsController@reportSupplier');

    Route::get('/reportes', 'reports\ReportsController@index');
    Route::post('/reportes', 'reports\ReportsController@reports');

    //pruebas eliminar
    Route::get('sendemail','emailcontroller@create')->name('email');
    Route::get('recpass','emailcontroller@recuperarcontraseña')->name('email');

    Route::get('senddatabase','emailcontroller@notificaciones')->name('database');
    Route::get('/errors', function(){
        return view('errors.errors');
    });

    Route::get('/notificaciones', 'NotificacionesController@index');
    Route::post('/notificaciones','NotificacionesController@store');
    Route::get('/leidas/{id?}','NotificacionesController@leidas' )->name('leidas');

});

Route::get('/numeroaletra','numeroletracontroller@index');


    Route::get('reset', 'auth\ForgotPasswordController@recuperar')->name('reset');
    Route::post('reset', 'auth\ForgotPasswordController@recuperarformulario');
    Route::get('nueva-contraseña/{id?}/', 'auth\ForgotPasswordController@obtenertoken');
    Route::post('nueva-contraseña/{id}', 'auth\ForgotPasswordController@guardarnuevacontra');



    Route::get('/login', function (Request $request) {
        $data = array(
            'CName' => $request->cookie('CName'),
            'CPass' => $request->cookie('CPass'),
        );
        return view('outside.index',  $data);
    })->name('login');


    Route::post('/loggingIn', 'auth\LoginController@authenticate');

    Route::get('/registrar', function(){
    $institute = new Institute();
    $institute->name = 'Presidencia';
    $institute->save();

    $department = new Department();
    $department->name = 'Informatica';
    $department->code = 'info';
    $department->save();

    $department_institute = new DepartmentInstitute();
    $department_institute->departments_id = $department->id;
    $department_institute->institutes_id = $institute->id;
    $department_institute->save();

    $role = new Role();
    $role->name = 'administrador';
    $role->code = 'admin';
    $role->save();


    $user = new User();
    $user->name = 'Sam';
    $user->password = Hash::make('123');
    $user->email = 'sam@gmail.com';
    $user->owner = 'Samuel Garza del Toro';
    $user->roles_id=$role->id;
    $user->departments_institutes_id = $department_institute->id;
    $user->save();
    });

    Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
        return "Cache is cleared";
    });

