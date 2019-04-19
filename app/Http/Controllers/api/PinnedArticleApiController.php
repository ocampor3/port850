<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SitePinnedArticle;
use App\Models\Site;

class PinnedArticleApiController extends Controller
{
    /**
     * Create a new WebApiController controller instance.     
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['']]);
    }

    /**
     * return favorite articles of user
    **/
    public function getPinnedArticles(Request $request) {
        $sc = $request->input('sc');
        $siteId = Site::where('SiteCode', $sc)->pluck('Id')->first();

        if(!empty($siteId)) {
            $pinnedArticles = SitePinnedArticle::with('article')
                                               ->where('SiteCode', $siteId)
                                               ->get()
                                               ->pluck('article');

            return $pinnedArticles;
       	} else {
            return response()->json(['ErrorMessage' => 'Invalid URI. Invalid sitecode value.']);
        }
    }
}
