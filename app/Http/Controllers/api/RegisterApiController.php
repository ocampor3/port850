<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class RegisterApiController extends Controller
{
    /**
     * Create a new WebApiController controller instance.     
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['postRegister']]);
    }

    /**
     * register new user
    **/
    public function postRegister(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');
        $fullname = $request->input('fullname');

    	$user = User::where('UserName', $username)->pluck('Id')->first();
    	if(empty($user)) {
    		User::create([
    			'UserName'	=> $username,
    			'Password'	=> bcrypt($password),
    			'FullName'	=> $fullname,
    			'Status'	=> 'inactive'
    		]);

    		return response()->json([
    			'ErrorMessage' => null,
    			'InnerException' => null,
    			'IsSuccessful' => 1
    		]);
    	} else {
            return response()->json([
            	'ErrorMessage'		=> 'Invalid URI. Username already exists.',
            	'InnerException'	=> null,
            	'IsSuccessful'		=> 0
            ]);
    	}

    }
}
