<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckConnectionApiController extends Controller
{
    /**
     * returns true for successful connection
     * returns ApiType
     */
    public function getConnect(Request $request)
    {      
        return response()->json(['IsActive' => 'true',
        						  'ApiType' => 'SQL',]);
    }
}
