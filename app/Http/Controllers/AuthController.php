<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
   
    public function login(Request $request){

     
      $email = filter_var($request->email,FILTER_VALIDATE_EMAIL);
      $password = $request->password;
      
      $credentials = ['email'=> $email,'password'=>$password];
        

      if (!Auth::attempt($credentials)) 
          return response()->json(['erro'=>'Email e ou senha inválidos'],401);
   
      $token = Auth::User()->createToken('teste');
      $loggedUser = Auth::User();
      $loggedUser['token'] = $token->plainTextToken;

      return response()->json($loggedUser,200);
    }

    public function register(Request $request){

        $name = $request->name;
        $email = filter_var($request->email,FILTER_VALIDATE_EMAIL);
        $password = $request->password; 

        /*
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required',
        ]);
        */

        if (!$email)
           return response()->json(['erro'=>'Email inválido ou não informado.'],400);

        if(!$name or !$password)
           return response()->json(['erro'=>'Dados obrigatórios não informados.'],400);

        $user = User::where('email', $request->email)->first();
        if($user)
          return response()->json(['erro'=>'Email já cadastrado.'],400);


        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = Hash::make($password);
        $newUser->save();
        return response()->json($newUser,201);

    }
}
