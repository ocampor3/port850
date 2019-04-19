<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Input;

use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Article;
use App\Models\Site;
use App\Models\UserHasSite;
use App\Models\SecurityGroup;

use Session;
use Auth;

class SubcategoryController extends CategoryController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $cat = Category::find(Session::get('CategoryId'));

        $allArticles = Article::where('IsDelete', 0)->pluck('Title', 'Id');

        $siteId = Session::get('SiteId');
        
        $securitygroups = SecurityGroup::where("SiteId","=",$siteId)->get();

        //$securitygroups = SecurityGroup::get();

        return view ('pages.subcategory.create',['cat' => $cat, 'allArticles' => $allArticles, 'securitygroups' => $securitygroups]);
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
        $category->ParentId = Session::get('CategoryId');

        $this->fillCategoryDetails($category, $request);
        
        //flash a notification
        Session::flash('flash_message', 'Subcategory created successfully.');

        return redirect()->route('subcategory.show',Session::get('CategoryId'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paginate = $this->getPaginate('subcategory_pageshow');

        $category = Category::where('Id', $id)
                            ->where('IsDelete', 0)
                            ->firstOrFail();

        $subcat = $category->Subcategories()
                           ->orderBy('SortOrder', 'ASC')
                           ->paginate($paginate);

        Session::put('CategoryId',$id);

        $site = $category->Site;

        if(!Auth::user()->IsHandlingSite($site)) {
            return view('errors.401');
        }

        return view ('pages.subcategory.show', ['subcat' => $subcat, 'category' => $category, 'paginate' => $paginate]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->returnEditPage($id, 'pages.subcategory.edit');
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
        
        //new instace of subcategory
        $category = Category::findOrFail($id);
        $category->ParentId = Session::get('CategoryId');

        $this->fillCategoryDetails($category, $request);
        
        //flash a notification
        Session::flash('flash_message', 'Subcategory updated successfully.');

        return redirect()->route('subcategory.show',Session::get('CategoryId'));
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcat = Category::find($id);

        //delete articles associated with subcategory
        Article::where('CategoryId',$id)->update(['IsDelete' => 1]);
        
        //delete subcategory itself
        $subcat->IsDelete = 1;

        $subcat->save();

        //flash a notification
        Session::flash('flash_message', 'Subcategory deleted successfully.');

        return redirect()->route('subcategory.show',Session::get('CategoryId'));
    }
}
