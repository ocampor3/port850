<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Models\Article;
use App\Models\Category;
use App\Models\Site;
use App\Models\UserHasSite;
use App\Models\CalendarValue;
use App\User;

use Session;
use Auth;
use Cookie;

class FileController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getFile($type,$id,$filename)
	{
		$format = sprintf('Upload/files/%s/%s/%s',$type,$id,$filename);
		
		return response()->download(storage_path($format), null, [], null);
	}
}

