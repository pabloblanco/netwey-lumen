<?php


namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        User::create($input);
        $log = ['status' => 'success', 'message' => 'Create user '.$input['email']];
        Log::create($log);
        return response()->json([
            'success' => true,
            'message' => 'Registro insertado correctamente'
        ]);
    }

    public function login(Request $request){
        $user = User::whereEmail($request->email)->first();

        if(!is_null($user) && Hash::check($request->password, $user->password))
        {
            $user->api_token = Str::random(150);
            $user->save();
            $log = ['status' => 'success', 'message' => 'Login user '.$user->email];
            Log::create($log);
            return response()->json([
                'success' => true,
                'token' => $user->api_token,
                'message' => 'Bienvenido al sistema'
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Cuenta o password incorrecto'
            ]);
        }
    }

    public function logout()
    {
        $user = auth()->user();
        $user->api_token = null;
        $user->save();
        $log = ['status' => 'success', 'message' => 'Logout user '.$user->email];
        Log::create($log);
        return response()->json([
            'success' => true,
            'message' => 'Gracias por usar Netwey'
        ]);
    }
}