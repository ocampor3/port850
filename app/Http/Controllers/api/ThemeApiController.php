<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Site;
use App\Models\Theme;

class ThemeApiController extends Controller
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
    
    /**
     * return theme by sitecode
     */
    public function getTheme(Request $request)
    {      
        $siteCode = $request->input('sc');  

        //check if sitecode input is not empty
        if($siteCode)
        {   
            $site = Site::where('SiteCode',$siteCode)
                        ->where('IsDelete', 0)
                        ->first();

            //check if site code exist
            if($site)
            {
                $theme = Theme::with('img_bg','img_banner','img_arrowback','img_arrowcollapse','img_arrowexpand',
                                     'img_arrownext','img_hamburger','img_home','img_mail','img_scrollbtm',
                                     'img_scrolltop','img_search','img_filter','img_share','img_sync','img_logo','img_mainscreen')
                              ->where('SiteCode',$site->Id)                            
                              ->first();             

                $theme['BackgroundImage']               = $theme->img_bg;                
                $theme['BackgroundImage']->CreatedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_bg->CreatedDate));                
                $theme['BackgroundImage']->ModifiedDate = date("n/d/Y g:i:s A",strtotime($theme->img_bg->ModifiedDate));                

                $theme['BannerImage']               = $theme->img_banner;
                $theme['BannerImage']->CreatedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_banner->CreatedDate));                
                $theme['BannerImage']->ModifiedDate = date("n/d/Y g:i:s A",strtotime($theme->img_banner->ModifiedDate));                

                $theme['IconArrowBackImage']                = $theme->img_arrowback;
                $theme['IconArrowBackImage']->CreatedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_arrowback->CreatedDate));                
                $theme['IconArrowBackImage']->ModifiedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_arrowback->ModifiedDate));                

                $theme['IconArrowCollapseImage']                = $theme->img_arrowcollapse;
                $theme['IconArrowCollapseImage']->CreatedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_arrowcollapse->CreatedDate));                
                $theme['IconArrowCollapseImage']->ModifiedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_arrowcollapse->ModifiedDate));                

                $theme['IconArrowExpandImage']                = $theme->img_arrowexpand;
                $theme['IconArrowExpandImage']->CreatedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_arrowexpand->CreatedDate));                
                $theme['IconArrowExpandImage']->ModifiedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_arrowexpand->ModifiedDate));                

                $theme['IconArrowNextImage']                = $theme->img_arrownext;
                $theme['IconArrowNextImage']->CreatedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_arrownext->CreatedDate));                
                $theme['IconArrowNextImage']->ModifiedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_arrownext->ModifiedDate));                

                $theme['IconHamburgerImage']                = $theme->img_hamburger;
                $theme['IconHamburgerImage']->CreatedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_hamburger->CreatedDate));                
                $theme['IconHamburgerImage']->ModifiedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_hamburger->ModifiedDate));                

                $theme['IconHomeImage']                 = $theme->img_home;
                $theme['IconHomeImage']->CreatedDate    = date("n/d/Y g:i:s A",strtotime($theme->img_home->CreatedDate));                
                $theme['IconHomeImage']->ModifiedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_home->ModifiedDate));                

                $theme['IconMailImage']                 = $theme->img_mail;
                $theme['IconMailImage']->CreatedDate    = date("n/d/Y g:i:s A",strtotime($theme->img_mail->CreatedDate));                
                $theme['IconMailImage']->ModifiedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_mail->ModifiedDate));                

                $theme['IconScrollBottomImage']                 = $theme->img_scrollbtm;
                $theme['IconScrollBottomImage']->CreatedDate    = date("n/d/Y g:i:s A",strtotime($theme->img_scrollbtm->CreatedDate));                
                $theme['IconScrollBottomImage']->ModifiedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_scrollbtm->ModifiedDate));                

                $theme['IconScrollTopImage']                = $theme->img_scrolltop;
                $theme['IconScrollTopImage']->CreatedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_scrolltop->CreatedDate));                
                $theme['IconScrollTopImage']->ModifiedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_scrolltop->ModifiedDate));                

                $theme['IconSearchImage']               = $theme->img_search;
                $theme['IconSearchImage']->CreatedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_search->CreatedDate));                
                $theme['IconSearchImage']->ModifiedDate = date("n/d/Y g:i:s A",strtotime($theme->img_search->ModifiedDate));

                $theme['IconFilterImage']               = $theme->img_filter;
                $theme['IconFilterImage']->CreatedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_search->CreatedDate));                
                $theme['IconFilterImage']->ModifiedDate = date("n/d/Y g:i:s A",strtotime($theme->img_search->ModifiedDate));                
                

                $theme['IconShareImage']                = $theme->img_share;
                $theme['IconShareImage']->CreatedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_share->CreatedDate));                
                $theme['IconShareImage']->ModifiedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_share->ModifiedDate));                

                $theme['IconSyncImage']                 = $theme->img_sync;
                $theme['IconSyncImage']->CreatedDate    = date("n/d/Y g:i:s A",strtotime($theme->img_sync->CreatedDate));                
                $theme['IconSyncImage']->ModifiedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_sync->ModifiedDate));                

                $theme['IconLogoImage'] = $theme->img_logo;
                if($theme->img_logo != null) { 
                    $theme['IconLogoImage']->CreatedDate    = date("n/d/Y g:i:s A",strtotime($theme->img_logo->CreatedDate)); 
                    $theme['IconLogoImage']->ModifiedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_logo->ModifiedDate)); 
                }
                
                $theme['MainScreenBoxImage'] = $theme->img_mainscreen;
                if($theme->img_mainscreen != null) { 
                    $theme['MainScreenBoxImage']->CreatedDate   = date("n/d/Y g:i:s A",strtotime($theme->img_mainscreen->CreatedDate)); 
                    $theme['MainScreenBoxImage']->ModifiedDate  = date("n/d/Y g:i:s A",strtotime($theme->img_mainscreen->ModifiedDate)); 
                }
                
                $theme['CreatedDate']   = date("n/d/Y g:i:s A",strtotime($theme->CreatedDate));               
                $theme['ModifiedDate']  = date("n/d/Y g:i:s A",strtotime($theme->ModifiedDate));               

                //make eager load variables hidden 
                $theme->makeHidden(['img_bg']);
                $theme->makeHidden(['img_banner']);
                $theme->makeHidden(['img_arrowback']);
                $theme->makeHidden(['img_arrowcollapse']);
                $theme->makeHidden(['img_arrowexpand']);
                $theme->makeHidden(['img_arrownext']);
                $theme->makeHidden(['img_hamburger']);
                $theme->makeHidden(['img_home']);
                $theme->makeHidden(['img_mail']);
                $theme->makeHidden(['img_scrollbtm']);
                $theme->makeHidden(['img_scrolltop']);
                $theme->makeHidden(['img_search']);
                $theme->makeHidden(['img_filter']);
                $theme->makeHidden(['img_share']);
                $theme->makeHidden(['img_sync']);
                $theme->makeHidden(['img_logo']);
                $theme->makeHidden(['img_mainscreen']);

                return $theme;
            }
            else   
                return response()->json(['ErrorMessage' => 'SiteCode does not exist.']);
        }
        else
            return response()->json(['ErrorMessage' => 'Invalid URI. Empty SiteCode value.']);        
    }
}
