<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Models\Article;
use App\Models\Category;
use App\Models\Site;
use App\Models\UserHasSite;
use App\Models\CalendarValue;
use App\User;


use Session;
use Auth;
use Cookie;

class ArticleController extends Controller
{

    /**
     * Create a new Controller controller instance.
     */
    public function __construct()
    {
        //restrict access using middleware
        $this->middleware('visitor',['except' => ['show','showDetails']]);

        //salt hash encrypt
        $this->salt = "RocheApp";
    }

    public function index()
    {
        return view('errors.404');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $siteCode = Session::get('SiteId');

        //list all possible categories
        $category = Category::where('SiteCode', $siteCode)
                            ->where('IsDelete', 0)
                            ->get()
                            ->pluck('ListName', 'Id');

        // list all possible geo loc assigned user
        $users = UserHasSite::where('SiteCode', $siteCode)->distinct()->get()->pluck('user.FullName', 'UserId');

        // for display, replace logged in user's full name
        $userId = Auth::user()->Id;
        if(isset($users[$userId])) {
            $users->prepend("Me", $userId);
        }

        $allArticles = Article::where('IsDelete', 0)
                              ->where('SiteCode', $siteCode)
                              ->pluck('Title', 'Id');

        return view ('pages.article.create', ['category' => $category, 'users' => $users, 'allArticles' => $allArticles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->value;

        $this->validate($request, [
            'title' => 'required',
            'order' => 'required|numeric',
        ]);

        $article = new Article;

        $article->Title      = $request->title;
        $article->CategoryId = $request->categoryId;
        $article->Status     = $request->status;

        //In case checkbox is null, set menufooter to false
        if ($request->input('menufooter') == null)
            $article->MenuFooter = 0;
        else
            $article->MenuFooter = $request->input('menufooter');

        //In case checkbox is null, set allowshare to false
        if ($request->input('allowshare') == null)
            $article->AllowShare = 0;
        else
            $article->AllowShare = $request->input('allowshare');

        //In case checkbox is null, set topbannershow to false
        if ($request->input('topbannershow') == null)
            $article->TopBannerShow = 0;
        else
            $article->TopBannerShow = $request->input('topbannershow');

        $article->Type       = $request->type;
        $article->SortOrder  = $request->order;
        $article->SiteCode   = Session::get('SiteId');
        $article->Value      = "placeholder";

        $article->CreatedBy    = Auth::user()->UserName;
        $article->CreatedDate  = date("y-m-d h:i:s");

        $article->ModifiedBy   = Auth::user()->UserName;
        $article->ModifiedDate = date("y-m-d h:i:s");

        $article->save();

        //save icon and encrypt file name
        if($request->hasFile('icon'))
        {
            $files      = Input::file('icon');
            $name       = $files->getClientOriginalName();
            $extension  = Input::file('icon')->getClientOriginalExtension();
            $size       = getImageSize($files);
            $fileExts   = array('jpg','jpeg','png','gif','bmp');

            //new filename but hashed
            $hashName   = hash( 'sha256',  $this->salt . $name). '.' . $extension;

            $filePath   = public_path().'/images/article/icon/'.$article->Id;
            $files->move($filePath, $hashName);

            $image      = [];
            //$image['imagePath']  = url('/').'/images/article/icon/'.$article->Id.'/'.$hashName;
            $image['imagePath']  = '/images/article/icon/'.$article->Id.'/'.$hashName;

            $article->Icon       = $image['imagePath'];
            $article->IconName   = $name;
        }
        //if no icon file uploaded
        else
        {
            $article->Icon       = NULL;
            $article->IconName   = NULL;
        }

        //check if not file upload then set value
        if(strtoupper($request->type) != 'TEXT' && strtoupper($request->type) != 'FILE' )
        {
            $article->FileName   = NULL;

            $artType = $request->type;

            //get protocol field
            $protocolField = "protocol".$request->type;

            //set type for direct text full type
            if($artType == 'DIRECTTEXTFULL')
            {
                $artType = 'DIRECTTEXT';
            }

            // //trim white spaces
            // $artNoSpace = trim(preg_replace('/\s\s+/', '', $request->value[$artType]));

            // $article->Value = $request->protocol[$request->type].''.$artNoSpace;

            if($artType == 'LINKAutofill' || $artType == 'LINKCredential')
            {
                //trim white spaces
                $artNoSpace = trim(preg_replace('/\s\s+/', '', $request->value[$artType]));

                $article->Value = $request[$protocolField].''.$artNoSpace;
            }
            else if($artType == 'LINKPassword') {
                //trim white spaces
                $artNoSpace = trim(preg_replace('/\s\s+/', '', $request->value[$artType]));
                
               // $strPos = strpos($request->filelinkPassword[$artType],':');
 
                $encrypted = Crypt::encryptString($request->filelinkPassword['Password']);
                $newLink = $request->filelinkPassword['Username'].':'.$encrypted;
                //substr_replace($request->filelinkPassword[$artType],$encrypted,$strPos+1);
                
                $article->Value = $request[$protocolField].$newLink.'@'.$artNoSpace;
            }
            else if($artType == 'LINK' || $artType == 'LINKExternal' || $artType == 'LINKLogin' || $artType == 'LINKInheritLogin')
            {
                $article->Value = preg_replace('/\s+/', '%20', $request->value[$artType]);
            }
            else if($artType == 'DIRECTTEXT' || $artType == 'GeoLocation')
            {
                $article->Value = $request->value[$artType];
            }

            if($artType == 'GeoLocation') {
                $article->GeoLocAssignedUserId = $request->geoLocAssignedUserId;
            } else {
                $article->GeoLocAssignedUserId = null;
            }

            if($artType == 'CalendarEvent') {
                $calEvent = CalendarValue::create([
                    'Description' => $request->caldescription,
                    'DateStart' => $request->caldatestart,
                    'DateEnd' => $request->caldateend,
                    'TimezoneStart' => $request->caltzstart,
                    'TimezoneEnd' => $request->caltzend
                ]);

                $calEvent->save();

                $article->CalendarValueId = $calEvent->Id;
            }

            // if article type is article, set article id
            if ($artType == 'LinkedArticle') {
                $article->IsArticle = 1;
                $article->ArticleId = $request->linkedArticleId;
            }
            else {
                $article->IsArticle = 0;
                $article->ArticleId = null;
            }
        }
        else
        {
            //set field to check file
            $fileField = "";

            if(strtoupper($request->type) == "TEXT")
            {
                $fileField = 'textfile';
            }
            elseif(strtoupper($request->type) == "FILE")
            {
                $fileField = 'browsefile';
            }

            //save file and encrypt file name
            if($request->hasFile($fileField))
            {

                $files      = Input::file($fileField);
                $name       = $files->getClientOriginalName();
                $extension  = Input::file($fileField)->getClientOriginalExtension();

                //new filename but hashed
                $hashName   = hash( 'sha256',  $this->salt . $name). '.' . $extension;

               //  $filePath   = public_path().'/files/article/'.$article->Id;
               //  $files->move($filePath, $hashName);

               //  $newPath = Storage::putFile('sample', new File('/files/article/'));
                
               //  var_dump($files);
                

                
                Storage::disk('local')->put('files/article/'.$article->Id.'/'.$hashName, file_get_contents($files));
                
                //Storage::put($hashName.'.'.$extension, $files);

                $savefile      = [];
                //$savefile['path'] = url('/').'/files/article/'.$article->Id.'/'.$hashName;
                $savefile['path'] = '/files/article/'.$article->Id.'/'.$hashName;
                //set article values
                $article->Value      = $savefile['path'];
                $article->FileName   = $name;
            }
        }


        $article->save();

        //flash a notification
        Session::flash('flash_message', 'Created Article successfully.');

        return redirect(Session::get('return_page'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $site = Site::where('Id',$id)
                    ->where('IsDelete', 0)
                    ->first();

        if($site)
        {
            $filter_category = "";
            $filter_user = "";

            //list all possible categories
            $category = Category::where('SiteCode', $site->Id)
                                ->where('IsDelete', 0)
                                ->get()
                                ->pluck('ListName', 'Id')
                                ->prepend('All','All');

            //list all possible users
            $users = UserHasSite::with('user')
                                ->where('SiteCode', $site->Id)
                                ->get()
                                ->pluck('user')
                                ->pluck('FullName', 'FullName')
                                ->prepend('All', 'All');

            //check pagination
            if(!empty($_COOKIE['article_pageshow']))
            {
                $paginate = $_COOKIE['article_pageshow'];
            }
            else
                $paginate = 25;

            // set default filter
            $filter_category = "All";
            $filter_user = "All";

            //check if there is a category filter
            if(!empty($_COOKIE['article_categoryfilter'])) {
                $filter_category = $_COOKIE['article_categoryfilter'];
            }

            //check if there is a user filter
            if(!empty($_COOKIE['article_userfilter'])) {
                $filter_user = $_COOKIE['article_userfilter'];
            }

            if($filter_category == "All")
            {
                if($filter_user == "All") {
                    $article = Article::with('category')
                                      ->where('SiteCode',$id)
                                      ->where('IsDelete', 0)
                                      ->paginate($paginate);
                } else {
                    $article = Article::with('category')
                                      ->where('CreatedBy',$filter_user)
                                      ->where('IsDelete', 0)
                                      ->where('SiteCode',$id)
                                      ->paginate($paginate);
                }
            }
            else
            {
                if($filter_user == "All") {
                    $article = Article::with('category')
                                      ->where('CategoryId',$filter_category)
                                      ->where('IsDelete', 0)
                                      ->where('SiteCode',$id)
                                      ->paginate($paginate);
                } else {
                    $article = Article::with('category')
                                      ->where('CategoryId',$filter_category)
                                      ->where('IsDelete', 0)
                                      ->where('CreatedBy',$filter_user)
                                      ->where('SiteCode',$id)
                                      ->paginate($paginate);
                }
            }

            //check accessibility of user logged in
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

            //save site code in session
            Session::put('SiteCode',$site->SiteCode);
            Session::put('SiteId',$site->Id);
            Session::put('SiteTitle',$site->Title);

            return view ('pages.article.show', ['article' => $article, 'paginate' => $paginate,
                         'category' => $category,'filter_category' => $filter_category, 'users' => $users,
                         'filter_user' => $filter_user]);
        }
        else
        {
            return view('errors.404');
        }
    }

    //show specific article
    public function showDetails($id)
    {
        $article = Article::with('category')
                          ->where('Id',$id)
                          ->where('IsDelete', 0)
                          ->firstOrFail();

        //check accessibility of user logged in
        if(Auth::user()->UserGroup != 'Admin')
        {
            // if(Auth::user()->SiteCode != $article->SiteCode)
            // {
            //     return view('errors.401');
            // }

            $sites = UserHasSite::where('UserId', Auth::user()->Id)
                                ->where('SiteCode', $article->SiteCode)->first();



            if(empty($sites)) {
                return view('errors.401');
            }
        }

        return view ('pages.article.showSpec', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::with('site')
                          ->where('IsDelete', 0)
                          ->findOrFail($id);

        //check accessibility of user logged in
        if(Auth::user()->UserGroup != 'Admin')
        {
            // if(Auth::user()->SiteCode != $article['site']->Id)
            // {
            //     return view('errors.401');
            // }

            $sites = UserHasSite::where('UserId', Auth::user()->Id)
                                ->where('SiteCode', $article['site']->Id)->first();

            if(empty($sites)) {
                return view('errors.401');
            }
        }

        $siteCode = Session::get('SiteId');

        //list all possible categories
        $category = Category::where('SiteCode', $siteCode)
                            ->where('IsDelete', 0)
                            ->get()
                            ->pluck('ListName', 'Id');

        // list all possible geo loc assigned user
        // get users of site using sitecode column in user table
        $users = User::where('SiteCode', $siteCode)
                       ->get()
                       ->pluck('FullName', 'Id');

        // get users of site using user_has_site table
        $userList = UserHasSite::where('SiteCode', $siteCode)->distinct()->get(['UserId']);

        foreach ($userList as $key => $value) {
            $users->put($value->user->Id, $value->user->FullName);
        }

        // for display, replace logged in user's full name
        $userId = Auth::user()->Id;
        if(isset($users[$userId])) {
            $users->prepend("Me", $userId);
        }

        $allArticles = Article::where('IsDelete', 0)
                              ->where('SiteCode', $siteCode)
                              ->pluck('Title', 'Id');

        return view ('pages.article.edit', ['category' => $category, 'users' => $users, 'article' => $article, 'allArticles' => $allArticles, 'linkedArticle' => $article->linkedArticle]);
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
            'title' => 'required',
            'order' => 'required|numeric',
        ]);

        $article = Article::find($id);

        $article->Title      = $request->title;
        $article->CategoryId = $request->categoryId;
        $article->Status     = $request->status;

        //In case checkbox is null, set topbannershow to false
        if (isset($request->topbannershow))
            $article->TopBannerShow = 1;
        else
            $article->TopBannerShow = 0;

        //In case checkbox is null, set allowshare to false
        if (isset($request->allowshare))
            $article->AllowShare = 1;
        else
            $article->AllowShare = 0;

        //In case checkbox is null, set menufooter to false
        if (isset($request->menufooter))
            $article->MenuFooter = 1;
        else
            $article->MenuFooter = 0;

        $article->Type       = $request->type;
        $article->SortOrder  = $request->order;
        $article->SiteCode   = Session::get('SiteId');

        $article->CreatedBy    = Auth::user()->UserName;
        $article->CreatedDate  = date("y-m-d h:i:s");

        $article->ModifiedBy   = Auth::user()->UserName;
        $article->ModifiedDate = date("y-m-d h:i:s");

        $article->save();

        //save icon and encrypt file name
        if($request->hasFile('icon'))
        {
            $files      = Input::file('icon');
            $name       = $files->getClientOriginalName();
            $extension  = Input::file('icon')->getClientOriginalExtension();
            $size       = getImageSize($files);
            $fileExts   = array('jpg','jpeg','png','gif','bmp');

            //new filename but hashed
            $hashName   = hash( 'sha256',  $this->salt . $name). '.' . $extension;

            $filePath   = public_path().'/images/article/icon/'.$article->Id;
            $files->move($filePath, $hashName);

            $image      = [];
            //$image['imagePath']  = url('/').'/images/article/icon/'.$article->Id.'/'.$hashName;
            $image['imagePath']  = '/images/article/icon/'.$article->Id.'/'.$hashName;

            $article->Icon       = $image['imagePath'];
            $article->IconName   = $name;
        }

        //check if not file upload then set value
        if(strtoupper($request->type) != 'TEXT' && strtoupper($request->type) != 'FILE' )
        {
            $article->FileName   = NULL;

            //get protocol field
            $protocolField = "protocol".$request->type;

            $artType = $request->type;

            //set type for direct text full type
            if($artType == 'DIRECTTEXTFULL')
            {
                $artType = 'DIRECTTEXT';
            }

            if($artType == 'LINKAutofill' || $artType == 'LINKCredential')
            {
                //trim white spaces
                $artNoSpace = trim(preg_replace('/\s\s+/', '', $request->value[$artType]));

                $article->Value = $request[$protocolField].''.$artNoSpace;
            }
            else if($artType == 'LINKPassword') {
                //trim white spaces
                $artNoSpace = trim(preg_replace('/\s\s+/', '', $request->value[$artType]));
               
                $encrypted = Crypt::encryptString($request->filelinkPassword['Password']);
                $newLink = $request->filelinkPassword['Username'].':'.$encrypted;
                $article->Value = $request[$protocolField].$newLink.'@'.$artNoSpace;
            }
            else if($artType == 'LINK' || $artType == 'LINKExternal' || $artType == 'LINKLogin' || $artType == 'LINKInheritLogin')
            {
                $article->Value = preg_replace('/\s+/', '%20', $request->value[$artType]);
            }
            else if($artType == 'DIRECTTEXT' || $artType == 'GeoLocation')
            {
                $article->Value = $request->value[$artType];
            }

            if($artType == 'GeoLocation') {
                $article->GeoLocAssignedUserId = $request->geoLocAssignedUserId;
            } else {
                $article->GeoLocAssignedUserId = null;
            }

            if($artType == 'CalendarEvent') {
                if($article->CalendarValue == null) {
                    $calEvent = CalendarValue::create([
                        'Description' => $request->caldescription,
                        'DateStart' => $request->caldatestart,
                        'DateEnd' => $request->caldateend,
                        'TimezoneStart' => $request->caltzstart,
                        'TimezoneEnd' => $request->caltzend
                    ]);
                } else {
                    $calEvent = $article->CalendarValue;
                    $calEvent->Description = $request->caldescription;
                    $calEvent->DateStart = $request->caldatestart;
                    $calEvent->DateEnd = $request->caldateend;
                    $calEvent->TimezoneStart = $request->caltzstart;
                    $calEvent->TimezoneEnd = $request->caltzend;
                }

                $calEvent->save();

                $article->CalendarValueId = $calEvent->Id;
            }

            // if category type is article, set article id
            if ($artType == 'LinkedArticle') {
                $article->IsArticle = 1;
                $article->ArticleId = $request->linkedArticleId;
            }
            else {
                $article->IsArticle = 0;
                $article->ArticleId = null;
            }
        }
        else
        {
            //set field to check file
            $fileField = "";

            if(strtoupper($request->type) == "TEXT")
            {
                $fileField = 'textfile';
            }
            elseif(strtoupper($request->type) == "FILE")
            {
                $fileField = 'browsefile';
            }

            //save file and encrypt file name
            if($request->hasFile($fileField))
            {
                $files      = Input::file($fileField);
                $name       = $files->getClientOriginalName();
                $extension  = Input::file($fileField)->getClientOriginalExtension();

                //new filename but hashed
                $hashName   = hash( 'sha256',  $this->salt . $name). '.' . $extension;

                //$filePath   = public_path().'/files/article/'.$article->Id;
                //$files->move($filePath, $hashName);

                Storage::disk('local')->put('files/article/'.$article->Id.'/'.$hashName, file_get_contents($files));

                $savefile      = [];
                //$savefile['path'] = url('/').'/files/article/'.$article->Id.'/'.$hashName;
                $savefile['path'] = '/files/article/'.$article->Id.'/'.$hashName;

                //set article values
                $article->Value      = $savefile['path'];
                $article->FileName   = $name;
            }
        }

        $article->save();

        //flash a notification
        Session::flash('flash_message', 'Article updated successfully.');

        return redirect(Session::get('return_page'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);

        // search for site pointing to this article
        $site = Site::where('ArticleId', $id)
                    ->where('IsArticle', 1)
                    ->first();

        if($site) {
            Session::flash('error_message', 'Unable to delete article. A site/s are pointing to this.');
            return redirect(Session::get('return_page'));
        }

        // search for category pointing to this article
        $category = Category::where('ArticleId', $id)
                            ->where('IsArticle', 1)
                            ->first();

        if($category) {
            Session::flash('error_message', 'Unable to delete article. A category is pointing to this.');
            return redirect(Session::get('return_page'));
        }

        $parentArticle = Article::where('ArticleId', $id)
                                ->where('IsArticle', 1)
                                ->first();

        if($parentArticle) {
            Session::flash('error_message', 'Unable to delete article. An article is pointing to this.');
            return redirect(Session::get('return_page'));
        }

        $article->IsDelete = 1;

        $article->save();

        //flash a notification
        Session::flash('flash_message', 'Article deleted successfully.');

        return redirect(Session::get('return_page'));
    }
}
