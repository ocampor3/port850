<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Models\ThemeCMS as Theme;
use App\Models\FileGallery;
use App\Models\Site;
use App\Models\UserHasSite;

use Session;
use Auth;

class ThemeController extends Controller
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
    
    public function index()
    {
        return view('errors.404');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($site)
    {
        $theme = Theme::with('img_bg','img_banner','img_arrowback','img_arrowcollapse','img_arrowexpand',
                             'img_arrownext','img_hamburger','img_home','img_mail','img_scrollbtm',
                             'img_scrolltop','img_search','img_share','img_sync','img_logo','img_mainscreen')
                      ->where('SiteCode',$site)
                      ->first(); 
        
        if($theme)
        {
            $siteDetails = Site::where('Id',$site)->first();
            //check accessibility of user logged in
            if(Auth::user()->UserGroup != 'Admin')
            {
                // if(Auth::user()->SiteCode != $site)
                // {
                //     return view('errors.401');
                // }

                $sites = UserHasSite::where('UserId', Auth::user()->Id)
                                    ->where('SiteCode', $site)->first();

                if(empty($sites)) {
                    return view('errors.401');
                }
            }
            
            $siteSelected = Site::where('Id',$site)
                                ->where('IsDelete', 0)
                                ->first();

            //save site code in session
            Session::put('SiteCode',$siteSelected->SiteCode);
            Session::put('SiteId',$siteSelected->Id);
            Session::put('SiteTitle',$siteSelected->Title);
            Session::put('ThemeId',$theme->Id);

            return view ('pages.theme.show', ['theme' => $theme,'siteDet' => $siteDetails]);
        }
        else
            return view ('errors.404');
    }

    //edit an image field
    public function editImageField($id,$fieldName,$field)
    {   
        $theme = Theme::where('Id',$id)                        
                      ->first();

        if($theme)
        {
            //check accessibility of user logged in
            if(Auth::user()->UserGroup != 'Admin')
            {
                // if(Auth::user()->SiteCode != $theme->SiteCode)
                // {
                //     return view('errors.401');
                // }

                $sites = UserHasSite::where('UserId', Auth::user()->Id)
                                    ->where('SiteCode', $theme->SiteCode)->first();

                if(empty($sites)) {
                    return view('errors.401');
                }
            }

            return view('pages.theme.editImage',['theme' => $theme, 'field' => $field, 'fieldName' => $fieldName]);
        }
        else
            return view ('errors.404');               
        
    }

    //edit site icon
    public function editSiteIcon($id) {
        $siteDet = Site::where('Id',$id)->first();

        if($siteDet) {
            //check accessibility of user logged in
            if(Auth::user()->UserGroup != 'Admin') {
                $sites = UserHasSite::where('UserId', Auth::user()->Id)
                                    ->where('SiteCode', $theme->SiteCode)->first();

                if(empty($sites)) {
                    return view('errors.401');
                }
            }

            return view('pages.site.editIcon',['siteDet' => $siteDet]);
        } else {
            return view ('errors.404'); 
        }
    }

    //edit site icon
    public function updateSiteIcon(Request $request,$id) {
        $siteDet = Site::findOrFail($id);

        //save icon and encrypt file name
        if($request->hasFile('images')) {
            $files      = Input::file('images');
            $name       = $files->getClientOriginalName();
            $extension  = Input::file('images')->getClientOriginalExtension();
            $size       = getImageSize($files);
            $fileExts   = array('jpg','jpeg','png','gif','bmp');

            //new filename but hashed 
            $hashName   = hash( 'sha256',  $this->salt . $name). '.' . $extension;

            $filePath   = public_path().'/images/site/'.$siteDet->Id;
            $files->move($filePath, $hashName);

            $image      = [];
            //$image['imagePath'] = url('/').'/images/site/'.$siteDet->Id.'/'.$hashName;
            $image['imagePath'] = '/images/site/'.$siteDet->Id.'/'.$hashName;

            $siteDet->Icon     = $image['imagePath'];
            $siteDet->IconName = $name;

            $siteDet->save();
        }

        //flash a notification
        Session::flash('flash_message', 'Updated site icon successfully.');

        return redirect()->route('theme.show',Session::get('SiteId'));
    }

    //edit an text contents
    public function editContent($id)
    {
        $theme = Theme::where('Id',$id)                        
                      ->first();

        if($theme)
        {
            //check accessibility of user logged in
            if(Auth::user()->UserGroup != 'Admin')
            {
                // if(Auth::user()->SiteCode != $theme->SiteCode)
                // {
                //     return view('errors.401');
                // }

                $sites = UserHasSite::where('UserId', Auth::user()->Id)
                                    ->where('SiteCode', $theme->SiteCode)->first();

                if(empty($sites)) {
                    return view('errors.401');
                }
            }

            return view('pages.theme.editText',['theme' => $theme]);
        }
        else
            return view ('errors.404'); 
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
        $theme = Theme::find($id);  

        //update image data
        if($request->edit_type == "image")
        {
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

                $filePath   = public_path().'/images/theme/'.$theme->Id;
                $files->move($filePath, $hashName);             

                $image      = [];
                //$image['imagePath'] = url('/').'/images/theme/'.$theme->Id.'/'.$hashName;
                $image['imagePath'] = '/images/theme/'.$theme->Id.'/'.$hashName;

                //create new record in file gallery
                $fileGallery = new FileGallery;

                $fileGallery->FileName = $name;
                $fileGallery->FileType = 'image';
                $fileGallery->Contents = $image['imagePath'];

                //creation details
                $fileGallery->CreatedBy    = Auth::user()->UserName;
                $fileGallery->CreatedDate  = date("y-m-d h:i:s");
                
                //update details, creation serves as default value for update details
                $fileGallery->ModifiedBy   = Auth::user()->UserName;
                $fileGallery->ModifiedDate = date("y-m-d h:i:s");

                $fileGallery->save();

                //add to theme the updated Id for the image
                $theme[$request->field_name] = $fileGallery->Id;

                //update details for theme
                $theme->ModifiedBy   = Auth::user()->UserName;
                $theme->ModifiedDate = date("y-m-d h:i:s");

                $theme->save();   

                //flash a notification
                Session::flash('flash_message', 'Updated '.$request->field. ' successfully.');     

                return redirect()->route('theme.show',Session::get('SiteId'));        
            }            
        }       

        //if editing content
        elseif($request->edit_type == "text")
        {
            $this->validate($request, [
                'title'         => 'required',
                'aboutTitle'    => 'required',        
                'contactTitle'  => 'required',        
                'factsTitle'    => 'required',        
            ]);

            $theme->Title           = $request->title;
            $theme->ButtonColor     = $request->btnColor;
            $theme->SeparatorColor  = $request->separatorColor;
            $theme->TextColor       = $request->textColor;
            $theme->AboutTitle      = $request->aboutTitle;
            $theme->AboutContent    = $request->about_content;
            $theme->ContactTitle    = $request->contactTitle;
            $theme->ContactContent  = $request->contact_content;
            $theme->FactsTitle      = $request->factsTitle;
            $theme->FactsContent    = $request->facts_content;
            $theme->ViewType        = $request->viewtype;
            $theme->SubViewType     = $request->subviewtype;

            //update details for theme
            $theme->ModifiedBy      = Auth::user()->UserName;
            $theme->ModifiedDate    = date("y-m-d h:i:s");

            $theme->save();

            //flash a notification
            Session::flash('flash_message', ' Content data updated successfully.');     

            return redirect()->route('theme.show',Session::get('SiteId'));       
        }
        else
        {
            return view ('errors.404');
        }
    }
}
