<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class UserFavoriteArticleApiController extends Controller
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
    public function getFavoriteArticles(Request $request) {
        $username = $request->input('username');
        $user = User::where('UserName', $username)->first();

        if($user) {
            return $user->favoriteArticles;
       	} else {
            if(!empty($username)) {
                return response()->json(['ErrorMessage' => 'Invalid URI. Invalid username value.']);
            } else {
                return response()->json(['ErrorMessage' => 'Invalid URI. Empty username value.']);
            }
        }
    }

    /**
     *  Mark article as favorite
    **/
    public function postMarkFavoriteArticle(Request $request) {
    	return $this->markOrUnmarkFavoriteArticle($request, true);
    }

    /**
     *  Unmark article as favorite
    **/
    public function postUnmarkFavoriteArticle(Request $request) {
    	return $this->markOrUnmarkFavoriteArticle($request, false);
    }

    /**
     * mark or unmark favorite article
     * @param $isCreate true if action is to mark article as favorite, otherwise unmark article as favorite
    **/
    private function markOrUnmarkFavoriteArticle(Request $request, bool $isCreate) {
        $username = $request->input('username');
        $user = User::where('UserName', $username)->first();
        $articleId = $request->input('ArticleId');

        if($user) {
	        if(!empty($articleId)) {

	        	if($isCreate) {
              // mark as favorite
              $user->favoriteArticles()->syncWithoutDetaching($articleId);
	        	} else {
	        		// unmark as favorite
              $user->favoriteArticles()->detach($articleId);
	        	}

            return response()->json(['ErrorMessage'     => null,
                                     'IsSuccessful'     => 'True',
                                     'InnerException'   => null]);
	       	} else {
	            return response()->json(['ErrorMessage' => 'Invalid URI. Empty ArticleId value.']);
	        }
       	} else {
            if(!empty($username)) {
                return response()->json(['ErrorMessage' => 'Invalid URI. Invalid username value.']);
            } else {
                return response()->json(['ErrorMessage' => 'Invalid URI. Empty username value.']);
            }
        }
    }
}
