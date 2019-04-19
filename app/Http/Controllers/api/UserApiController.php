<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\UserHasSite;
use App\Models\Site;
use App\User;
use Auth;

class UserApiController extends Controller
{
    /**
     * Create a new WebApiController controller instance.
     *
     * @return void
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['getSiteSecurityGroup']]);
    }

    public function getAllSiteUsers(Request $request) {
    	$siteCode = $request->input('sc');

    	if($siteCode) {
            $site = Site::where('SiteCode', $siteCode)->first();
    		$username = $request->input('username');
    		
    		if($username) {
		        // list all possible geo loc assigned user
		        return Site::find($site->Id)->siteUsers()->get(['users.Id', 'users.UserName']);
    		} else {
            	return response()->json(['ErrorMessage' => 'Invalid URI. Empty Username value.']);
    		}
    	} else {
            return response()->json(['ErrorMessage' => 'Invalid URI. Empty SiteCode value.']);
    	}

    }

     public function getSiteSecurityGroup(Request $request){

      $user = Auth::user();
      echo 'api controller';
       // return response()->json([
       //      'status' => Auth::check(),
       //      'message' => 'CA'
       //  ]);
      
    }
}
