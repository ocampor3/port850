<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Site;

class SiteApiController extends Controller
{
    /**
     * Create a new WebApiController controller instance.     
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['getAllSites']]);
    }
    
    /**
     * return all sites
     */
    public function getAllSites()
    {   
    	$sites = Site::where('IsDelete', 0)
                     ->whereIn('Status', ['Live', 'Test'])
                     ->where('PasswordRequired', 0)
                     ->get();
    	return $sites;
    }

    /**
     * return site by id
     */
    public function getSiteById(Request $request)
    {   
    	$sites = Site::where('Id', $request->input('siteId'))
                    ->where('IsDelete', 0)
    				->get();
    	return $sites;
    }
}
