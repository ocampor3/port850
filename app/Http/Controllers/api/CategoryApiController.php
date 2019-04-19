<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Site;
use App\Models\SecurityGroup;
use App\User;
use Illuminate\Support\Facades\DB;

class CategoryApiController extends Controller
{
    /**
     * Create a new WebApiController controller instance.
     *
     * @return void
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['']]);
    }   

    //---------------------------------------//
    //---------------- GET ------------------//
    //---------------------------------------//

    private function retrieveCategories($siteCode, $categoryParentId, $username,$isPublic) {
        if($siteCode) {

            $site = Site::where('SiteCode',$siteCode)
                        ->where('IsDelete', 0)
                        ->first();

            if($site) {
                if($categoryParentId == 0 || $categoryParentId) {
                    if($isPublic == 0){
                        $user = User::where('UserName', $username)->first();
                        $UserGroup = $user->UserGroup;

                        $sites = DB::table('user_has_sitecode')
                                ->where("UserId",$user->Id)
                                ->where("SiteCode",$site->Id)
                                //->get()
                                ->first();


                         //$user->usersite->where("SiteCode","=",$site->Id)->first();
                       

                        if(isset($sites))
                        $SecurityGroupId = $sites->SecurityGroupId;
                        else
                        $SecurityGroupId = null;  

                        $secgroup = SecurityGroup::find($SecurityGroupId);
                        
                        if($UserGroup == "Owner") {
                            $categories = $secgroup->categories()->with('categoryArticle')
                            ->where('IsDelete', 0)
                            ->where('ParentId',$categoryParentId)
                            ->where('SiteCode',$site->Id)
                            ->whereIn('Status', ['Live', 'Test'])
                            ->orderBy('SortOrder','ASC')
                            ->get();
                        } else {
                            if(isset($secgroup)){
                                $categories = $secgroup->categories()->with('categoryArticle')
                                ->where('IsDelete', 0)
                                ->where('ParentId',$categoryParentId)
                                ->where('SiteCode',$site->Id)
                                ->where('Status', 'Live')
                                ->orderBy('SortOrder','ASC')
                                ->get();

                                // $categories = DB::table('category')
                                //         ->leftjoin('category_security_group','category.Id','=','category_security_group.CategoryId')
                                //          ->select('category.*')
                                //          ->with('categoryArticle')
                                //          ->where('SiteCode',$site->Id)
                                //          ->where('category_security_group.SecurityGroupId',$SecurityGroupId)
                                //          ->where('IsDelete',0)
                                //          ->get();
                            }else{


                            }
                        }

                       
                        $publicCategory = DB::table('category')
                                        ->leftjoin('category_security_group','category.Id','=','category_security_group.CategoryId')
                                         ->select('category.*')
                                         ->where('ParentId',$categoryParentId)
                                         ->where('SiteCode',$site->Id)
                                         ->where('category_security_group.SecurityGroupId',null)
                                         ->where('IsDelete',0)
                                         ->where('Status', 'Live')
                                         ->get();

                        //var_dump(($categories->first()->"fillable"));
                       
                       
                       // $finalResult = $categories->merge($publicCategory);
                       
                        
                        if(isset($categories)){
                            $categories = json_encode(array_merge($categories->toArray(),$publicCategory->toArray()));
                        }else{

                             $categories =$publicCategory;
                        }
                        //array_sort( array_merge($categories->toArray(),$publicCategory->toArray()),'SortOrder','ASC');
                         

                    }else{

                        //$categories = Category::where('SiteCode',$site->Id)
                        //->get();
                        //$categories = Category::SELECT("SELECT * from category");

                        $categories = DB::table('category')
                                        ->leftjoin('category_security_group','category.Id','=','category_security_group.CategoryId')
                                         ->select('category.*')
                                         ->where('SiteCode',$site->Id)
                                         ->where('ParentId',$categoryParentId)
                                         ->where('category_security_group.SecurityGroupId',null)
                                         ->where('IsDelete',0)
                                         ->where('Status', 'Live')
                                         ->get();
                        


                    }
        
                   // $categories->ErrorMessage = null;            

                    return $categories;
                } else {
                    return response()->json(['ErrorMessage' => 'Invalid URI. Empty CategoryId value.']);
                }
            } else {
                return response()->json(['ErrorMessage' => 'SiteCode does not exist.']);
            }
        } else {
            return response()->json(['ErrorMessage' => 'Invalid URI. Empty SiteCode value.']);
        }
    }
    
    /**
     * return all categories
     */
    public function getCategories(Request $request)
    {   
        return $this->retrieveCategories($request->input('sc'), 0, $request->username,$request->p);
    }

    /**
     * return subcategories of a category
     */
    public function getSubcategories(Request $request)
    {      
        return $this->retrieveCategories($request->input('sc'), $request->input('cid'), $request->username,0);
    }

    private function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                asort($sortable_array);
                break;
                case SORT_DESC:
                arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

   
}
