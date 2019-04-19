<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    public $timestamps = false;
	
    protected $table = 'theme'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['Title','bg_img','banner_img','arrow_back_img',
						   'arrow_collapse_img','arrow_expand_img','arrow_next_img',
						   'hamburger_img','home_img','mail_img','scroll_btm_img',
						   'scroll_top_img','search_img','filter_img','share_img','sync_img',
						   'logo_img','ButtonColor','SeparatorColor','TextColor',
						   'AboutTitle','AboutContent','ContactTitle','ContactContent','SiteCode',
						   'FactsTitle','FactsContent','ViewType','SubViewType','main_screen_box_img',						   
                           'CreatedBy','CreatedDate','ModifiedBy','ModifiedDate'];		

  	protected $primaryKey = "Id"; 
    
    // column names that will not be shown
	protected $hidden = ['SiteCode','bg_img','banner_img','arrow_back_img',
						 'arrow_collapse_img','arrow_expand_img','arrow_next_img',
						 'hamburger_img','home_img','mail_img','scroll_btm_img',
						 'scroll_top_img','search_img','share_img','sync_img',
						 'logo_img','main_screen_box_img'];
  
	protected $appends = ['ErrorMessage'];

	//function for the appended data
	public function getErrorMessageAttribute($value){
        return $this->value;
    }

    //--- get images for each ---//

	public function img_bg()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'bg_img');
    }

	public function img_banner()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'banner_img');
    }

	public function img_arrowback()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'arrow_back_img');
    }

	public function img_arrowcollapse()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'arrow_collapse_img');
    }

	public function img_arrowexpand()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'arrow_expand_img');
    }

	public function img_arrownext()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'arrow_next_img');
    }

	public function img_hamburger()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'hamburger_img');
    }

	public function img_home()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'home_img');
    }
    
	public function img_mail()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'mail_img');
    }
   
	public function img_scrollbtm()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'scroll_btm_img');
    }
   
	public function img_scrolltop()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'scroll_top_img');
    }
   
	public function img_search()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'search_img');
    }
    public function img_filter()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'filter_img');
    }
   
	public function img_share()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'share_img');
    }
   
	public function img_sync()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'sync_img');
    }
	
	public function img_logo()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'logo_img');
    }

	public function img_mainscreen()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'main_screen_box_img');
    }

    
}
