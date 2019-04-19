<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

//dynamic data for master view can be controlled here
View::creator('layouts.master', function($view)
{   
    if(Auth::user()->UserGroup == "Admin")
    {
        $site = App\Models\Site::where('IsDelete', 0)->get();

        $view->with(['sites' => $site,'limit' => 'no']);
    }
    //if ordinary user
    else
    {   
        $user = App\User::with('usersite.site')->where('Id', Auth::user()->Id)->first();

        //dd($user['usersite'][0]['sites']);
        
       // $userhassite = App\Models\UserHasSite::with('user.usersite')->where('UserId',Auth::user()->Id)->get();

        // $site =  $user;
        $site = $user;

        //save site code in session
        // Session::put('UserSiteCode',$site[0]->Id);
        // Session::put('SiteCode',$site[0]->SiteCode);
        // Session::put('SiteId',$site[0]->Id);
        // Session::put('SiteTitle',$site[0]->Title);

        $view->with(['sites' => $site,'limit' => 'yes']);
    }
});

//------------------ AUTHENTICATION ------------------//    
Auth::routes(); //built-in auth

//unavailable page display
Route::get('/unavailable', function () {   
    return view('errors.uc');
});

Route::get('401', function () {   
    return view('errors.401');
});

Route::get('/', function () {   
  
    if(Auth::user())
    {
        if(Auth::user()->UserGroup == "Admin")
        {
            return redirect()->route('site.index');
        }
        else
        {

            $user = App\User::with('usersite.site')->where('Id', Auth::user()->Id)->first();
            return redirect('/v1/site/'. $user['usersite'][0]['site']->SiteCode);
        } 
    }
    else{
         return redirect()->route('site.index');
    }
       
});


//------------------ RESOURCE ROUTES ------------------//    
Route::group(['prefix' => 'v1','middleware' => 'auth'], function() 
{ 
	//home page
    Route::get('/', function () {   
        return redirect()->route('site.index');
        //return view('pages.home.index');
    });

    /* CUSTOM CONTROLLERS */
    Route::get('edit-account', ['as' => 'edit-user','uses' => 'UserController@editUser']);
    Route::get('site-icon/{id}', [
        'as' => 'edit-site-icon', 'uses' => 'ThemeController@editSiteIcon']);

    Route::patch('update-icon/{id}', [
        'as' => 'update-site-icon', 'uses' => 'ThemeController@updateSiteIcon']);

    //theme custom controllers
    Route::get('theme/{id}/edit/{field}/{fieldName}', [
               'as' => 'editImagefield', 'uses' => 'ThemeController@editImageField']);
    
    Route::get('theme/{id}/edit/content', [
               'as' => 'editContent', 'uses' => 'ThemeController@editContent']);

    //article custom controllers
    Route::get('article/details/{article}/', [
               'as' => 'showArticle', 'uses' => 'ArticleController@showDetails']);

    //security custom controllers
    Route::get('securitygroup/create/{siteId}', ['as' => 'securitygroup.createSGSite', 'uses' => 'SecurityGroupController@create']);
    Route::delete('securitygroup/delete/{siteId}/{securitygroupId}', ['as' => 'securitygroup.delete', 'uses' => 'SecurityGroupController@destroy']);

	//*------RESOURCE CONTROLLERS--------*//
    Route::resource('site', 'SiteController');
    Route::get('subsites/{parent_id}', ['as' => 'subsites.index', 'uses' => 'SubsiteController@index']);
    Route::resource('subsite', 'SubsiteController', ['except' => ['index']]);
	Route::resource('category', 'CategoryController');
	Route::resource('subcategory', 'SubcategoryController');
    Route::resource('article', 'ArticleController');    
    Route::resource('theme', 'ThemeController');
    Route::resource('user', 'UserController');
    Route::resource('pinnedarticle', 'PinnedArticleController');
    Route::resource('securitygroup', 'SecurityGroupController');

   



});
//-------------------- For Ajax ---------------------------------------------//

Route::group(['prefix' => 'User'], function() 
{ 
    Route::get('getSecurityGrouBySiteId', 'UserController@getSiteSecurityGroup');
});

 Route::get('files/{type}/{article_id}/{filename}', 'FileController@getFile');
