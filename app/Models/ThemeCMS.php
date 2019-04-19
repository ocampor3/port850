<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeCMS extends Model
{
    public $timestamps = false;
	
    protected $table = 'theme'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['Title','bg_img','banner_img','arrow_back_img',
						   'arrow_collapse_img','arrow_expand_img','arrow_next_img',
						   'hamburger_img','home_img','mail_img','scroll_btm_img',
						   'scroll_top_img','search_img','share_img','sync_img',
						   'logo_img','ButtonColor','SeparatorColor','TextColor',
						   'AboutTitle','AboutContent','ContactTitle','ContactContent','SiteCode',
						   'FactsTitle','FactsContent','ViewType','SubViewType','main_screen_box_img',						   
                           'CreatedBy','CreatedDate','ModifiedBy','ModifiedDate'];		

  	protected $primaryKey = "Id"; 

    protected $attributes = array(
                                'bg_img' => '14',
                                'banner_img' => '1',
                                'arrow_back_img' => '2',
                                'arrow_collapse_img' => '3',
                                'arrow_expand_img' => '4',
                                'arrow_next_img' => '8',
                                'hamburger_img' => '5',
                                'home_img' => '6',
                                'mail_img' => '7',
                                'scroll_btm_img' => '10',
                                'scroll_top_img' => '11',
                                'search_img' => '12',
                                'share_img' => '13',
                                'sync_img' => '9',
                                'logo_img' => NULL,
                                'main_screen_box_img' => NULL,
                                'ButtonColor' => '#1e293a',
                                'SeparatorColor' => '#FFFFFF',
                                'TextColor' => '#FFFFFF',
                                'AboutTitle' => 'About Title',
                                'AboutContent' => 'About Content',
                                'ContactTitle' => 'Contact Title',
                                'ContactContent' => 'Contact Content',                             
                                'FactsTitle' => 'Facts Title',
                                'FactsContent' => 'Facts Content'
                            );

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

    public function img_filter()
    {
        return $this->hasOne('App\Models\FileGallery', 'Id', 'filter_img');
    }
}
