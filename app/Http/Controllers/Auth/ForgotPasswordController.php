<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Mail;
use App\Mail\recuperarcontraseña;
use App\User;
use App\EmailLog;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function recuperar(){
    return view ('Outside.restorePassword');



    }

    public function recuperarformulario(Request $request){
        $usuario=User::where('email','=',$request->email)->count();
        if($usuario==1){

            $usuario=User::where('email','=',$request->email)->first();
            $token=$request->_token;
            $datos=array([
                'usuario'=> $usuario,
                'token'=>  $token
            ]);


             $usuario->token = $token;
             $usuario->save();
             Mail::to($usuario->email)->send(new recuperarcontraseña($datos));
             $mailLog = EmailLog::create([
                'sender' => 'soporte.asistenciagp@gmail.com',
                'recipient' => $usuario->email,
                'status' => 'Enviado',
                'descriptionStatus' => 'Correo de recuperación de contraseña para el usuario de '.$usuario->owner
             ]);
             $mailLog->save();
             return redirect('/')->with('success','Liga generada, favor de revisar su correo');

            // return view('Outside.formularioRecPass', $datos);

        }
        else{
            return redirect()->back()->with('success','Correo no registrado');;
        }


    }
    public function obtenertoken($id=''){
        if($id!=''){
        $user=User::where('token','=',$id)->count();

         if($user>0)
         {

            return view('Outside.formularioRecPass');
         }
         else{
             return 'token invalido';
         }


        }
        return 'token invalido';

    }
    public function guardarnuevacontra(Request $request,$id){
        $user=User::where('token','=',$id)->count();
        if($user>0)
        {
            $user=User::where('token','=',$id)->first();
            $user->password = Hash::make($request->pass1);
            $user->save();
            return redirect('/')->with('success','Se actualiza contraseña correctamente');


        }
     else{
         return 'usuario no encontrado';
     }
    //  return redirect('/');

    }
}
