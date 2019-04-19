<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Site;
use App\Models\Article;

use Session;

class SubsiteController extends Controller
{

    /**
     * Display the subsites of site given its id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if(!empty($_COOKIE['subsite_pageshow']))
        {   
            $paginate = $_COOKIE['subsite_pageshow'];
        }   
        else
            $paginate = 25;

        $subsites = Site::with('theme')
                        ->where('ParentId',$id)
                        ->where('IsDelete', 0)
                        ->paginate($paginate);

        $site = Site::where('Id', $id)->firstOrFail();

        Session::put('SiteId',$id);

        if($subsites)
        {
            return view ('pages.subsite.index', ['subsites' => $subsites, 'site' => $site, 'paginate' => $paginate]);
        }
        else
            return view ('errors.404');
    }

    /**
     * Show the form for creating a subsite
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $site = Site::where('Id',Session::get('SiteId'))->first();

        $allArticles = Article::where('IsDelete', 0)->pluck('Title', 'Id');

        return view ('pages.subsite.create', ['site' => $site, 'allArticles' => $allArticles]);
    }
}
