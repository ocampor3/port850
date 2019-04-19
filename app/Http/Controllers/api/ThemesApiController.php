<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Site;
use App\Models\Category;
use App\User;

class ThemesApiController extends Controller
{
	public function addImage(Request $request)
	{
		$user = User::where('UserName', $request->email)->first(); 
		$src = public_path().'/images/site/default';
		$dest = public_path().'/images/site/'.$user->SiteCode;
		$dir = opendir($src); 
	    @mkdir($dest); 
	    while(false !== ( $file = readdir($dir)) ) { 
	        if (( $file != '.' ) && ( $file != '..' )) { 
	            if ( is_dir($src . '/' . $file) ) { 
	                recurse_copy($src . '/' . $file,$dest . '/' . $file); 
	            } 
	            else { 
	                copy($src . '/' . $file,$dest . '/' . $file); 
	            } 
	            $name = $file;
	            $file_name = str_replace('.png', '', $name);
	            if($file_name == 'Icon') {
	            	$site = Site::find($user->SiteCode);
		            $image['imagePath'] = '/images/site/'.$site->Id.'/'.$name;
		            $site->Icon     = $image['imagePath'];
		            $site->IconName = $name;
		            $site->save();
	            }

	            $category = Category::where('SiteCode', $user->SiteCode)->where('Name', $file_name)->first();
	            if($category != null) {
	            	$image['imagePath'] = '/images/site/'.$user->SiteCode.'/'.$name;
		            $category->Icon     = $image['imagePath'];
		            $category->IconName = $name;
		            $category->save();
	            }
	        } 
	    } 
	    closedir($dir); 

	    return "[{message:'Success'}]";

	}
}