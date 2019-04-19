<?php namespace App\Http\Controllers\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Models\Site;
use App\Models\UserHasSite;
use App\Models\AppAccess;
use App\Models\ApiAuthSessions;
use DB;
use Input;

use Hash;

class WebApiAuthController extends Controller {


    /**
	 * Create a new WebApiController controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->tokenLifeInDays = 2;
        $this->middleware('webapiauth', ['except' => ['getAccessToken']]);
	}

	/**
	 * API Authentication, generation of Access Token	 
	 */

	public function getAccessToken(Request $request)
	{
		$appName 	= $request->input('appname');
		$accessKey 	= $request->input('accesskey');
		$siteCode 	= $request->input('sitecode');
        
		$access = AppAccess::where('appName',$appName)
							->where('appAccessKey',$accessKey)
							->where('isDeleted', '=', 0)
							->where('isActive', '=', 1)
							->firstOrFail();	

		$site = Site::where('SiteCode',$siteCode)->first();

        if(!empty($access))
        {
        	if($siteCode)
        	{
        		$authUser = User::where('UserName',$request->input('username')) 
        						->whereNull('Status')
        						->where('SiteCode',$site->Id)       					
                   				->first();
        	}
        	else
        	{
        		$authUser = User::where('UserName',$request->input('username')) 
        						->whereNull('Status')       					
                   				->first();
        	}
            
			if (!empty($authUser))
			{
	            if (Hash::check($request->input('password'), $authUser->Password)) 
	            {           
	            	if($siteCode)
        			{	
		                $authUser = User::with('sc')
		                				->where('SiteCode',$site->Id)
		                				->where('UserName', '=', $request->input('username'))->firstOrFail();
		            }
		            else
		            {
		            	$authUser = User::with('sc')
		                				->where('UserName', '=', $request->input('username'))->firstOrFail();
		            }
		               
	                if(!empty($authUser))
	                {			
	                    $randString = str_random(100);
	                    $crDate = date('Y-m-d H:i:s');
	                    //2 days
	                    $expDate = date('Y-m-d H:i:s', strtotime($crDate . ' +'.$this->tokenLifeInDays.' day'));			

	                    $authSession = ApiAuthSessions::create(['apiAuthSessionsUsername' => $request->input('username')
	                                                            ,'apiAuthSessionsToken'=>$randString
	                                                            ,'apiAuthSessionsIP'=>$request->ip()
	                                                            ,'createdDate'=>$crDate
	                                                            ,'ExpirationDate'=>$expDate
	                                                            ]);

	                    $userGroup = $authUser->UserGroup;
	                    $authSession['FullName'] = $authUser->FullName;
	                    $authSession['UserName'] = $authUser->UserName;
	                    $authSession['DefaultPincode'] = $authUser->Pincode;
	                    $authSession['DefaultWebDomain'] = $authUser->Domain;
	                    $authSession['DefaultWebUsername'] = $authUser->DomainUserId;
	                    $authSession['DefaultWebPassword'] = $authUser->DomainPassword;
	                    $authSession['Group'] = $userGroup;
	                    $authSession['ModifiedBy'] = $authUser->ModifiedBy;
	                    $authSession['ModifiedDate'] = date("n/d/Y g:i:s A",strtotime($authUser->ModifiedDate));
	                    $authSession['ErrorMessage'] = null;	                    

	                    if($userGroup == "Owner") {
		                    $authSession['SiteCodes'] = $authUser->handledSites()->with('siteArticle')
		                    													 ->whereIn('Status', ['Live','Test'])
		                    													 ->get();
	                    } else {
		                    $authSession['SiteCodes'] = $authUser->handledSites()->with('siteArticle')
		                    													 ->where('Status', 'Live')
		                    													 ->get();
	                    }

	                    if($authSession)
	                    {
	                    	if(count($authSession['SiteCodes']) > 0) {
	                        	return response()->json($authSession);
	                    	} else {
	                        	return response()->json(['ErrorMessage' => 'There are no live sites available for this user.']);
	                    	}
	                    }
	                    else
	                    {
	                        return response()->json(['ErrorMessage' => 'Cannot create session.']);
	                    }
	                }
	            }
	            else
	            {
	                return response()->json(['ErrorMessage' => 'Authentication Failed.']);
	            }
			}
			else
			{
				return response()->json(['ErrorMessage' => 'Authentication Failed.']);
			}
        }  
        else
		{
			return response()->json(['ErrorMessage' => 'Invalid Access name or Access key.']);
		}      
	}

	
	/**
	 * Destroy Access Token
	 *
	 * @return Response
	 */
	public function getDestroyToken(Request $request)
	{		
		$now = date('Y-m-d H:i:s');
		ApiAuthSessions::where('apiAuthSessionsUsername',$request->input('username'))
						->where('apiAuthSessionsToken',$request->input('token'))
						->update(['expirationDate' => $now]);
						
		return "Session Destroyed";
	}
	
	/**
	 * Refresh Access Token
	 *
	 * @return Response
	 */
	public function getRefreshToken(Request $request)
	{
		$randString = str_random(100);
		$now = date('Y-m-d H:i:s');
		$expDate = date('Y-m-d H:i:s', strtotime($now . ' +'.$this->tokenLifeInDays.' day'));			
		ApiAuthSessions::where('apiAuthSessionsUsername',$request->input('username'))
						->where('apiAuthSessionsToken',$request->input('token'))
						->update(['expirationDate' => $expDate, 'apiAuthSessionsToken'=>$randString]);
						
		return ['expirationDate' => $expDate, 'apiAuthSessionsToken'=>$randString];
	}
}
