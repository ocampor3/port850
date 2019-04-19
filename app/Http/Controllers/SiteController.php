<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Models\Site;
use App\Models\ThemeCMS as Theme;
use App\Models\Category;
use App\Models\Article;
use App\Models\UserHasSite;
use App\User;

use Session; 
use DB;
use Auth;

class SiteController extends Controller
{
    /**
     * Create a new Controller controller instance.     
     */  
    public function __construct()
    {
        //restrict access using middleware
        $this->middleware('admin',['except' => ['show']]);       

        //salt hash encrypt
        $this->salt = "RocheApp";

        if (Auth::check()){
            parent::__construct();
        }
    }

    /**
     * Display parent sites
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        if(Auth::user()->UserGroup == "Admin")
        {
            $sites = Site::with('theme')->where('IsDelete', 0)->get();

            //reset site sessions
            Session::put('SiteCode','');
            Session::put('SiteId','');
            Session::put('SiteTitle','');

            return view ('pages.site.index', ['sites' => $sites]);
        }
        //if not admin
        else
        {   
            $userSiteCodes = UserHasSite::where('UserId', Auth::user()->Id)->get()->pluck('SiteCode');
            $sites = Site::with('theme')->where('IsDelete', 0)->whereIn('Id', $userSiteCodes)->get();

            return view ('pages.site.index', ['sites' => $sites]);
        }        
    }

    /**
     * Show the form for creating a new site.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allSites = Site::where('ParentId', 0)
                        ->where('IsDelete', 0)
                        ->pluck('Title', 'Id');
        $allSites->prepend('None', '0');

        $allArticles = Article::where('IsDelete', 0)->pluck('Title', 'Id');

        return view ('pages.site.create', ['allSites' => $allSites, 'allArticles' => $allArticles]);
    }

    /**
     * Store new site     
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            'title'     => 'required',
            'url'       => 'required',        
            'sitecode'  => 'required|no_special_chars|unique:sites',        
        ]);

        $site = new Site; //new instance of site

        //===== store site details
        $site->Title        = $request->title;
        $site->SiteUrl      = $request->url;
        $site->SiteCode     = $request->sitecode;

        if($request->parentid == null)
            $site->ParentId = 0;
        else
            $site->ParentId     = $request->parentid;

        //In case checkbox is null, set passwordrequired to false
        if (isset($request->passwordrequired))        
            $site->PasswordRequired = 1;        
        else    
            $site->PasswordRequired = 0;

        //In case checkbox is null, set menufooter to false
        if (isset($request->menufooter))        
            $site->MenuFooter = 1;        
        else    
            $site->MenuFooter = 0;

        //In case checkbox is null, set allowfavorites to false
        if (isset($request->allowfavorites))        
            $site->AllowFavorites = 1;        
        else    
            $site->AllowFavorites = 0;

        //In case checkbox is null, set topbannershow to false
        if (isset($request->topbannershow))        
            $site->TopBannerShow = 1;        
        else    
            $site->TopBannerShow = 0;

        //In case checkbox is null, set hamburgerfooter to false
        if (isset($request->hamburgerfooter))        
            $site->HamburgerFooter = 1;        
        else    
            $site->HamburgerFooter = 0;

        //In case checkbox is null, set showinlogin to false
        if (isset($request->showinlogin))        
            $site->ShowInLogin = 1;        
        else    
            $site->ShowInLogin = 0;

        //In case isArticle checkbox is not null, set article id
        if (isset($request->isArticle)) {
            $site->IsArticle = 1;
            $site->ArticleId = $request->articleId;
        }
        else {
            $site->IsArticle = 0;
            $site->ArticleId = null;
        }
        
        $site->Status       = $request->status;

        //creation details
        $site->CreatedBy    = Auth::user()->UserName;
        $site->CreatedDate  = date("y-m-d h:i:s");
        
        //update details, creation serves as default value for update details
        $site->ModifiedBy   = Auth::user()->UserName;
        $site->ModifiedDate = date("y-m-d h:i:s");

        $site->save();

        //save icon and encrypt file name
        if($request->hasFile('images')) 
        {
            $files      = Input::file('images');
            $name       = $files->getClientOriginalName();
            $extension  = Input::file('images')->getClientOriginalExtension();
            $size       = getImageSize($files);
            $fileExts   = array('jpg','jpeg','png','gif','bmp');

            //new filename but hashed 
            $hashName   = hash( 'sha256',  $this->salt . $name). '.' . $extension;   

            $filePath   = public_path().'/images/site/'.$site->Id;
            $files->move($filePath, $hashName);             

            $image      = [];
            $image['imagePath'] = url('/').'/images/site/'.$site->Id.'/'.$hashName;

            $site->Icon     = $image['imagePath'];
            $site->IconName = $name;

            $site->save();
            
        }

        //===== create a record for theme for the site created
        $theme = new Theme;

        $theme->SiteCode = $site->Id;
        $theme->title = $request->title;
        $theme->ViewType = $request->viewtype;
        $theme->SubviewType = $request->subviewtype;

        //creation details
        $theme->CreatedBy    = Auth::user()->UserName;
        $theme->CreatedDate  = date("y-m-d h:i:s");
        
        //update details, creation serves as default value for update details
        $theme->ModifiedBy   = Auth::user()->UserName;
        $theme->ModifiedDate = date("y-m-d h:i:s");
        
        //$theme->img_filter = 
        
        $theme->save();

        //flash a notification
        Session::flash('flash_message', 'Site created successfully.');

        return redirect()->route('site.index');
               
    }

    /**
     * Display the site settings page.   
     */
    public function show($code)
    {   
        $site = Site::with('theme','article','category')
                    ->where('IsDelete', 0)
                    ->where('SiteCode',$code)->first();           

        if($site)
        {
            //check accessibility
            if(Auth::user()->UserGroup != 'Admin')
            {
                // if(Auth::user()->SiteCode != $site->Id)
                
                //     return view('errors.401');
                // }

                $sites = UserHasSite::where('UserId', Auth::user()->Id)
                                    ->where('SiteCode', $site->Id)->first();

                if(empty($sites)) {
                    return view('errors.401');
                }
            }

            //save site code in session
            Session::put('SiteCode',$site->SiteCode);
            Session::put('SiteId',$site->Id);
            Session::put('SiteTitle',$site->Title);

            return view ('pages.site.show', ['site' => $site]);    
        }
        else
            return view('errors.404');
        
    }

    /**
     * Show the form for editing site given its sitecode
     *
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    { 
        $site = Site::with('theme')
                    ->where('SiteCode',$code)
                    ->where('IsDelete', 0)
                    ->first();

        if($site)
        {
            //check accessibility
            if(Auth::user()->UserGroup != 'Admin')
            {
                // if(Auth::user()->SiteCode != $site->Id)
                // {
                //     return view('errors.401');
                // }

                $sites = UserHasSite::where('UserId', Auth::user()->Id)
                                    ->where('SiteCode', $site->Id)->first();

                if(empty($sites)) {
                    return view('errors.401');
                }
            }
            $allCategory = Category::where('SiteCode',$site->Id)
            ->where('IsDelete', 0)
            ->pluck('Id');
            // get all possible parent sites
            $allSites = Site::where('ParentId', 0)
                        ->where('Id', '!=', $site->Id)
                        ->where('IsDelete', 0)
                        ->pluck('Title', 'Id');
            $allSites->prepend('None', '0');
            $parentSite = Site::where('Id', $site->ParentId)
                              ->where('IsDelete', 0)
                              ->first();
            $allArticles = Article::where('IsDelete', 0)
                                  ->wherein('CategoryId',$allCategory)
                                  ->pluck('Title', 'Id');
            

            return view ('pages.site.edit', ['site' => $site, 'allSites' => $allSites, 'parentSite' => $parentSite, 'allArticles' => $allArticles, 'article' => $site->siteArticle]);  
        }
        else
            return view('errors.404');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            'title'     => 'required',
            'url'       => 'required',        
            'sitecode'  => 'required|no_special_chars',        
        ]);

        $samesitecode = Site::where('Id','!=',$id)->where('SiteCode','=',$request->sitecode)->get();

        if(count($samesitecode) != 0) {
            $this->validate($request, [
                'sitecode'  => 'required|no_special_chars|unique:sites',
            ]);
        }

        $site = Site::with('theme')->findOrFail($id);     
        
        //===== store site details
        $site->Title        = $request->title;
        $site->SiteUrl      = $request->url;
        $site->SiteCode     = $request->sitecode;

        if($request->parentid == null)
            $site->ParentId = 0;
        else
            $site->ParentId     = $request->parentid;

        //In case checkbox is null, set passwordrequired to false
        if (isset($request->passwordrequired))        
            $site->PasswordRequired = 1;        
        else    
            $site->PasswordRequired = 0;

        //In case checkbox is null, set menufooter to false
        if (isset($request->menufooter))        
            $site->MenuFooter = 1;        
        else    
            $site->MenuFooter = 0;

        //In case checkbox is null, set allowfavorites to false
        if (isset($request->allowfavorites))        
            $site->AllowFavorites = 1;        
        else    
            $site->AllowFavorites = 0;

        //In case checkbox is null, set topbannershow to false
        if (isset($request->topbannershow))        
            $site->TopBannerShow = 1;        
        else    
            $site->TopBannerShow = 0;

        //In case checkbox is null, set hamburgerfooter to false
        if (isset($request->hamburgerfooter))        
            $site->HamburgerFooter = 1;        
        else    
            $site->HamburgerFooter = 0;

        //In case checkbox is null, set showinlogin to false
        if (isset($request->showinlogin))        
            $site->ShowInLogin = 1;        
        else    
            $site->ShowInLogin = 0;

        //In case isArticle checkbox is not null, set article id
        if (isset($request->isArticle)) {
            $site->IsArticle = 1;
            $site->ArticleId = $request->articleId;
        }
        else {
            $site->IsArticle = 0;
            $site->ArticleId = null;
        }
        
        $site->Status       = $request->status;
        
        //update details, creation serves as default value for update details
        $site->ModifiedBy   = Auth::user()->UserName;
        $site->ModifiedDate = date("y-m-d h:i:s");

        $site->save();

        //update theme viewtype and subviewtype
        $theme = Theme::find($site['theme']->Id);

        $theme->ViewType     = $request->viewtype;
        $theme->SubviewType  = $request->subviewtype;
        
        //update details, creation serves as default value for update details
        $theme->ModifiedBy   = Auth::user()->UserName;
        $theme->ModifiedDate = date("y-m-d h:i:s");

        $theme->save();

        //save icon and encrypt file name
        if($request->hasFile('images')) 
        {
            $files      = Input::file('images');
            $name       = $files->getClientOriginalName();
            $extension  = Input::file('images')->getClientOriginalExtension();
            $size       = getImageSize($files);
            $fileExts   = array('jpg','jpeg','png','gif','bmp');

            //new filename but hashed 
            $hashName   = hash( 'sha256',  $this->salt . $name). '.' . $extension;   

            $filePath   = public_path().'/images/site/'.$site->Id;
            $files->move($filePath, $hashName);             

            $image      = [];
            $image['imagePath'] = url('/').'/images/site/'.$site->Id.'/'.$hashName;

            $site->Icon     = $image['imagePath'];
            $site->IconName = $name;

            $site->save();
            
        }

        //flash a notification
        Session::flash('flash_message', 'Site successfully updated.');

        return redirect()->route('site.index');               
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $site = Site::find($id);
        
        //delete theme 
        // Theme::where('SiteCode',$id)->delete();

        //delete articles
        Article::where('SiteCode',$id)->update(['IsDelete' => 1]);

        //delete categories
        Category::where('SiteCode',$id)->update(['IsDelete' => 1]);

        //update users SiteCode to null
        User::where('SiteCode',$id)->update(['SiteCode' => NULL]);

        UserHasSite::where('SiteCode', $id)->delete();

        //delete site itself
        $site->IsDelete = 1;

        $site->save();

        //flash a notification
        Session::flash('flash_message', 'Site deleted successfully.');

        return redirect()->route('site.index');
    }
}
