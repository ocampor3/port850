<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SitePinnedArticle;
use App\Models\Article;

use Session;

class PinnedArticleController extends Controller
{

    /**
     * Create a new Controller controller instance.     
     */  
    public function __construct()
    {
        //restrict access using middleware
        $this->middleware('admin',['except' => []]);

        //salt hash encrypt
        $this->salt = "RocheApp";
    }

    public function show($siteId) {
    	$pinned_articles = SitePinnedArticle::with('article')
    										->where('SiteCode', $siteId)
    										->get()
    										->pluck('article');

    	return view('pages.pinnedarticle.show', ['pinned_articles' => $pinned_articles]);
    }

    public function edit($siteId) {
        $pinned_articles = SitePinnedArticle::with('article')
                                            ->where('SiteCode', $siteId)
                                            ->get()
                                            ->pluck('article');

        $all_articles = Article::where('SiteCode', $siteId)
                               ->where('IsDelete', 0)
                               ->get()
                               ->pluck('Title', 'Id');

        return view('pages.pinnedarticle.edit', ['pinned_articles' => $pinned_articles, 'all_articles' => $all_articles]);
    }

    public function store(Request $request) {
        $siteId = Session::get('SiteId');
        $new_pinned_articles = $request['pinned_article'];

        $curr_pinned_articles = SitePinnedArticle::where('SiteCode', $siteId)
                                                 ->get();

        foreach($curr_pinned_articles as $curr_pinned_article) {
            $articleId = $curr_pinned_article->ArticleId;
            if($new_pinned_articles != null && in_array($articleId, $new_pinned_articles)) {
                $pos = array_search($articleId, $new_pinned_articles);
                unset($new_pinned_articles[$pos]);
            } else {
                SitePinnedArticle::destroy($curr_pinned_article->Id);
            }
        }

        if($new_pinned_articles != null) {
            foreach($new_pinned_articles as $pa) {
                SitePinnedArticle::create([
                    'ArticleId' => $pa,
                    'SiteCode'  => $siteId
                ]);
            }
        }

        $pinned_articles = SitePinnedArticle::with('article')
                                            ->where('SiteCode', $siteId)
                                            ->get()
                                            ->pluck('article');

        $all_articles = Article::where('SiteCode', $siteId)
                               ->where('IsDelete', 0)
                               ->get()
                               ->pluck('Title', 'Id');

        return view('pages.pinnedarticle.show', ['pinned_articles' => $pinned_articles, 'all_articles' => $all_articles]);
    }
}
