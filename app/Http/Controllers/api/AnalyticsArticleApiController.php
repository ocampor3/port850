<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AnalyticsArticle;
use App\User;

class AnalyticsArticleApiController extends Controller
{
    /**
     * Create a new WebApiController controller instance.     
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['']]);
    }

    /**
     *	Record time when user started to access the article
    **/
    public function postStartAccess(Request $request) {
        $username = $request->input('username');
        $userId = User::where('UserName', $username)->pluck('Id')->first();
    	$articleId = $request->input('ArticleId');

    	if(!empty($userId)) {
    		if(!empty($articleId)) {
    			try {
	    			$analyticsArticle = AnalyticsArticle::create([
	    					'UserId' 	=> $userId,
	    					'ArticleId'	=> $articleId,
	    					'StartTime'	=> time()
	    				]);

	    			$analyticsArticle->ErrorMessage = null;
	    			$analyticsArticle->IsSuccessful = 'True';

	    			return $analyticsArticle;
    			} catch(\Illuminate\Database\QueryException $e) {
                    return response()->json(['ErrorMessage'     => 'Query Exception.',
                                             'IsSuccessful'     => 'False']);
                }

    		} else {
	    		return response()->json(['ErrorMessage'		=> 'Invalid URI.',
	    								 'IsSuccessful'		=> 'False',
	    								 'InnerException'	=> 'Empty ArticleId']);
    		}

    	} else {
            if(!empty($username)) {
                return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                         'IsSuccessful'     => 'False',
                                         'InnerException'   => 'Invalid Username']);
            } else {
                return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                         'IsSuccessful'     => 'False',
                                         'InnerException'   => 'Empty Username']);
            }
    	}
    }

    /**
     *	Record time when user ended accessing the article
    **/
    public function postEndAccess(Request $request) {
        $username = $request->input('username');
        $userId = User::where('UserName', $username)->pluck('Id')->first();
    	$articleId = $request->input('ArticleId');
    	$id = $request->input('Id');

    	if(!empty($userId)) {

    		if(!empty($articleId)) {

    			if(!empty($id)) {
    				try {
	    				$analyticsArticle = AnalyticsArticle::where('Id', $id)
	    													->where('UserId', $userId)
	    													->where('ArticleId', $articleId);

	    				if($analyticsArticle->exists()) {
	    					$analyticsArticle->update(['EndTime' => time()]);

	                        return response()->json(['ErrorMessage'     => null,
	                                                 'IsSuccessful'     => 'True',
	                                                 'InnerException'   => null]);
	    				} else {
				    		return response()->json(['ErrorMessage'		=> 'Unable to find analytics record',
				    								 'IsSuccessful'		=> 'False']);
	    				}

	    			} catch(\Illuminate\Database\QueryException $e) {
	                    return response()->json(['ErrorMessage'     => 'Query Exception.',
	                                             'IsSuccessful'     => 'False']);
	                }
    			} else {
		    		return response()->json(['ErrorMessage'		=> 'Invalid URI.',
		    								 'IsSuccessful'		=> 'False',
		    								 'InnerException'	=> 'Empty Id']);
    			}
    			$analyticsArticle = AnalyticsArticle::create([
    					'UserId' 	=> $userId,
    					'ArticleId'	=> $articleId,
    					'StartTime'	=> time()
    				]);

    			$analyticsArticle->ErrorMessage = null;
    			$analyticsArticle->IsSuccessful = 'True';

    			return $analyticsArticle;

    		} else {
	    		return response()->json(['ErrorMessage'		=> 'Invalid URI.',
	    								 'IsSuccessful'		=> 'False',
	    								 'InnerException'	=> 'Empty ArticleId']);
    		}

    	} else {
            if(!empty($username)) {
                return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                         'IsSuccessful'     => 'False',
                                         'InnerException'   => 'Invalid Username']);
            } else {
                return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                         'IsSuccessful'     => 'False',
                                         'InnerException'   => 'Empty Username']);
            }
        }

    }
}
