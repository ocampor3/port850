<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Article;
use App\Models\Category;
use App\Models\Site;
use App\Models\SecurityGroup;
use App\User;

class SearchApiController extends Controller
{
    /**
     * Create a new WebApiController controller instance.     
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['']]);
    }

    /**
     * return parent categories and articles with name/title that matches the search term
    **/
    public function getSearchItems(Request $request) {
        $sc = $request->input('sc');
        $siteId = Site::where('SiteCode', $sc)->pluck('Id')->first();

        if(!empty($siteId)) {
            $searchterm = $request->input('searchterm');

            if(!empty($searchterm)) {
                $username = $request->input('username');
                $user = User::where('UserName', $request->username)->first();
                $userGroup = $user->UserGroup;
                $userId = $user->Id;

                $SecurityGroupId = $user->SecurityGroupId;
                $secgroup = SecurityGroup::find($SecurityGroupId);

                if($userGroup == "Owner") {
                  $articles = Article::with('CalendarValue')
                                      ->where('SiteCode',$siteId)
                                      ->where('Title', 'LIKE', '%'.$searchterm.'%')
                                      ->where(function($q) use ($userId) {
                                        $q->where('GeoLocAssignedUserId', $userId)
                                          ->orWhereNull('GeoLocAssignedUserId');
                                      })
                                      ->where('IsDelete', 0)
                                      ->whereIn('Status', ['Live', 'Test'])
                                      ->get();

                  $categories = $secgroup->categories()->with('categoryArticle')
                                                       ->where('Name', 'LIKE', '%'.$searchterm.'%')
                                                       ->where('IsDelete', 0)
                                                       //->where('ParentId',0)
                                                       ->where('SiteCode',$siteId)
                                                       ->whereIn('Status', ['Live', 'Test'])
                                                       ->get();
                } else {
                  $articles = Article::with('CalendarValue')
                                      ->where('SiteCode',$siteId)
                                      ->where('Title', 'LIKE', '%'.$searchterm.'%')
                                      ->where(function($q) use ($userId) {
                                        $q->where('GeoLocAssignedUserId', $userId)
                                          ->orWhereNull('GeoLocAssignedUserId');
                                      })
                                      ->where('IsDelete', 0)
                                      ->where('Status', 'Live')
                                      ->get();

                  $categories = $secgroup->categories()->with('categoryArticle')
                                                       ->where('Name', 'LIKE', '%'.$searchterm.'%')
                                                       ->where('IsDelete', 0)
                                                       //->where('ParentId',0)
                                                       ->where('SiteCode',$siteId)
                                                       ->where('Status', 'Live')
                                                       ->get();
                }

                return response()->json([
                                    'ErrorMessage' => null,
                                    'SearchTerm' => $searchterm,
                                    'Articles' => $articles,
                                    'Categories' => $categories
                ]);

            } else {
                return response()->json(['ErrorMessage' => 'Invalid URI. Invalid search term value.']);
            }
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
