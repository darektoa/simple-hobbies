<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Api\RequestValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class AuthController extends Controller
{
    use RequestValidator;

    public function signUp(Request $request) {
        try{
            $this->validate($request, [
                'name'      => 'required|min:3|max:100',
                'email'     => 'required|email|unique:users',
                'phone'     => 'required|numeric|digits_between:6,20',
                'password'  => 'required'
            ]);

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => Hash::make($request->password),
            ]);
            
            $token  = Auth::login($user);
            $user   = collect($user)->put('token', $token);
            
            return ResponseHelper::make(UserResource::make($user));
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
