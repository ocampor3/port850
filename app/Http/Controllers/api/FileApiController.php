<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use App\Models\Article;
use App\Models\Site;
use App\Models\CalendarValue;
use App\User;

use File;

class FileApiController extends Controller
{
	/**
     * Create a new WebApiController controller instance.     
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['']]);
    }   

    //---------------------------------------//
    //---------------- GET ------------------//
    //---------------------------------------//

    public function getFile($type,$id,$filename)
	{
		$format = sprintf('Upload/files/%s/%s/%s',$type,$id,$filename);
		
		return response()->download(storage_path($format), null, [], null);
	}
}