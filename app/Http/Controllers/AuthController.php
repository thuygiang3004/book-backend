<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @method sendError(string $string, \Illuminate\Support\MessageBag $errors)
 */
class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password'])
        ]);
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'User created',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $loginUser = User::where('email', $request->email)->first();

        if (!$loginUser || !Hash::check($request->password, $loginUser->password)) {
            return response()->json([
                'message' => 'No matching user',
            ], 401);
        }
        $token = $loginUser->createToken('token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Logged in',
            'token' => $token
        ]);

    }

}
