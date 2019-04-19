<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Article;
use App\Models\Site;
use App\Models\UserHasSite;
use App\Models\SecurityGroup;

use Session;
use Auth;

class CategoryController extends Controller
{
    
    /**
     * Create a new Controller controller instance.     
     */  
    public function __construct()
    {        
        //restrict access using middleware
        $this->middleware('visitor',['except' => ['show']]);

        //salt hash encrypt
        $this->salt = "RocheApp";
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allArticles = Article::where('IsDelete', 0)->pluck('Title', 'Id');

        $siteId = Session::get('SiteId');
        
        $securitygroups = SecurityGroup::where("SiteId","=",$siteId)->get();

        return view ('pages.category.create', ['allArticles' => $allArticles, 'securitygroups' => $securitygroups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateData($request);

        $category = new Category;
        $category->ParentId = 0;

        $this->fillCategoryDetails($category, $request);

        //flash a notification
        Session::flash('flash_message', 'Category created successfully.');

        return redirect()->route('category.show',Session::get('SiteId'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($site)
    {
        $paginate = $this->getPaginate('category_pageshow');

        $site = Site::where('Id',$site)
                    ->where('IsDelete', 0)
                    ->firstOrFail();

        $category = $site->Category()
                         ->where('ParentId', 0)
                         ->orderBy('SortOrder', 'ASC')
                         ->paginate($paginate);

        if(!Auth::user()->IsHandlingSite($site)) {
            return view('errors.401');
        }

        //save site code in session
        Session::put('SiteCode',$site->SiteCode);
        Session::put('SiteId',$site->Id);
        Session::put('SiteTitle',$site->Title);

        return view ('pages.category.show', ['category' => $category, 'paginate' => $paginate]);
    }

    protected function returnEditPage($id, $editPage) {
        $category = Category::where('IsDelete', 0)->findOrFail($id);

        $site = $category->Site;

        if(!Auth::user()->IsHandlingSite($site)) {
            return view('errors.401');
        }
    
        $allArticles = Article::where('IsDelete', 0)->pluck('Title', 'Id');

        $siteId = Session::get('SiteId');
        
        $securitygroups = SecurityGroup::where("SiteId","=",$siteId)->get();

        $catSecGroupIds = [];

        $catSecGroups = $category->securitygroups;
        foreach ($catSecGroups as $catSecGroup) {
            array_push($catSecGroupIds, $catSecGroup->Id);
        }

        return view ($editPage, ['category' => $category, 'allArticles' => $allArticles, 'article' => $category->categoryArticle, 'securitygroups' => $securitygroups, 'catSecGroupIds' => $catSecGroupIds]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->returnEditPage($id, 'pages.category.edit');
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
        $this->validateData($request);
        
        $category = Category::findOrFail($id);
        $category->ParentId = 0;

        $this->fillCategoryDetails($category, $request);

        //flash a notification
        Session::flash('flash_message', 'Category updated successfully.');
        
        return redirect()->route('category.show',Session::get('SiteId'));              
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        $subcatIds = $category->subcategories->pluck('Id');

        //delete articles associated with subcategory
        Article::whereIn('CategoryId',$subcatIds)->update(['IsDelete' => 1]);

        //delete subcategories of the category
        Subcategory::where('ParentId',$id)->update(['IsDelete' => 1]);

        //delete articles associated with category
        Article::where('CategoryId',$id)->update(['IsDelete' => 1]);

        //delete category itself
        $category->IsDelete = 1;

        $category->save();
        
        //flash a notification
        Session::flash('flash_message', 'Category deleted successfully.');

        return redirect()->route('category.show',Session::get('SiteId'));
    }

    protected function validateData(Request $request) {
        $this->validate($request, [
            'display_name'  => 'required',
            'order'         => 'required|numeric',        
        ]);
    }

    protected function fillCategoryDetails(Category $category, Request $request) {
        $category->Name         = $request->display_name;
        $category->Status       = $request->status; 
        $category->SortOrder    = $request->order;        
        $category->SiteCode     = Session::get('SiteId'); //get session of active site
        
        //if no color is checked
        if (isset($request->no_color))
            $category->ViewColor   = null;
        else            
            $category->ViewColor   = $request->color;

        //In case checkbox is null, set allowupload to false
        if (isset($request->allowupload))
            $category->AllowUpload = 1;        
        else    
            $category->AllowUpload = 0;

        //In case checkbox is null, set menufooter to false
        if (isset($request->menufooter))
            $category->MenuFooter = 1;        
        else    
            $category->MenuFooter = 0;

        //In case checkbox is null, set allowshare to false
        if (isset($request->allowshare))
            $category->AllowShare = 1;        
        else    
            $category->AllowShare = 0;

        //In case checkbox is null, set topbannershow to false
        if (isset($request->topbannershow))
            $category->TopBannerShow = 1;        
        else    
            $category->TopBannerShow = 0;

        //In case checkbox is null, set isexpanded to false
        if (isset($request->isexpanded))
            $category->IsExpanded = 1;        
        else    
            $category->IsExpanded = 0;

        // if category type is article, set article id
        if ($request->type == 'Article') {
            $category->IsArticle = 1;
            $category->ArticleId = $request->articleId;
        }
        else {
            $category->IsArticle = 0;
            $category->ArticleId = null;
        }
        
        //creation details
        $category->CreatedBy    = Auth::user()->UserName;
        $category->CreatedDate  = date("y-m-d h:i:s");
        
        //update details, creation serves as default value for update details
        $category->ModifiedBy   = Auth::user()->UserName;
        $category->ModifiedDate = date("y-m-d h:i:s");

        $category->save();

        $category->securitygroups()->sync($request->securitygroups);

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

            $filePath   = public_path().'/images/category/'.$category->Id;
            $files->move($filePath, $hashName);             

            $image      = [];
            //$image['imagePath'] = url('/').'/images/category/'.$category->Id.'/'.$hashName;
            $image['imagePath'] = '/images/category/'.$category->Id.'/'.$hashName;

            $category->Icon     = $image['imagePath'];
            $category->IconName = $name;
        }

        $category->save();
    }

    protected function getPaginate($pageshow) {
        if(!empty($_COOKIE[$pageshow]))
        {   
            $paginate = $_COOKIE[$pageshow];
        }   
        else
            $paginate = 25;

        return $paginate;
    }
}
