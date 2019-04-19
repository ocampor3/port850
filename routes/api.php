<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function() 
{    
	/*** TEST CONNECTION API ***/
	Route::get('/connect',"api\CheckConnectionApiController@getConnect");      

	/*** CATEGORY API ***/
	Route::get('/categories',"api\CategoryApiController@getCategories");      
	Route::get('/subcategories',"api\CategoryApiController@getSubcategories"); 


	/*** THEME API ***/
	Route::get('/theme',"api\ThemeApiController@getTheme");    

	/*** ARTICLE API ***/
	Route::get('/all-site-articles',"api\ArticleApiController@getSiteArticles");
	Route::get('/article',"api\ArticleApiController@getArticles");
	Route::get('/view-article', "api\ArticleApiController@getArticle");
	Route::get('/notes',"api\ArticleApiController@getNotes");
	Route::post('/text-article',"api\ArticleApiController@postTextArticle");
	Route::post('/file-article',"api\ArticleApiController@postFileArticle");
	Route::post('/note',"api\ArticleApiController@postNote");
	Route::post('/edit-note',"api\ArticleApiController@postEditNote");
	Route::post('/delete-note',"api\ArticleApiController@postDeleteNote");
	Route::get('/articles',"api\ArticleApiController@getMultipleArticles");    

	/*** ANALYTICS ARTICLE API ***/
	Route::post('/article-start-access',"api\AnalyticsArticleApiController@postStartAccess");
	Route::post('/article-end-access',"api\AnalyticsArticleApiController@postEndAccess");

	/*** USER FAVORITES ***/
	Route::get('/favorite-articles',"api\UserFavoriteArticleApiController@getFavoriteArticles");
	Route::post('/mark-favorite-article',"api\UserFavoriteArticleApiController@postMarkFavoriteArticle");
	Route::post('/unmark-favorite-article',"api\UserFavoriteArticleApiController@postUnmarkFavoriteArticle");

	/*** PINNED ARTICLES ***/
	Route::get('/pinned-articles',"api\PinnedArticleApiController@getPinnedArticles");

	/*** SITE API ***/
	Route::get('/all-sites',"api\SiteApiController@getAllSites");
	Route::get('/site',"api\SiteApiController@getSiteById");

	/*** USER API ***/
	Route::get('/all-site-users', "api\UserApiController@getAllSiteUsers");

	/*** SEARCH ***/
	Route::get('/search',"api\SearchApiController@getSearchItems");

	/*** REGISTER ***/
	Route::post('/register',"api\RegisterApiController@postRegister");

	/*** File ***/
	Route::get('files/{type}/{article_id}/{filename}', 'api\FileApiController@getFile');

	Route::post('/add-theme',"api\ThemesApiController@addImage");
});

Route::group(['prefix' => 'auth'], function() 
{    
	/*** WEB API AUTH ***/
	Route::get('/access-token', "Auth\WebApiAuthController@getAccessToken");
	Route::get('/destroy-token', "Auth\WebApiAuthController@getDestroyToken");
	Route::get('/refresh-token', "Auth\WebApiAuthController@getRefreshToken");
});


