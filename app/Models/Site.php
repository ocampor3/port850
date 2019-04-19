<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public $timestamps = false;
	
    protected $table = 'sites'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['SiteCode','Title','Icon','IconName','SiteUrl','ParentId',
                           'PasswordRequired', 'MenuFooter', 'AllowFavorites',
                           'TopBannerShow', 'HamburgerFooter', 'ShowInLogin', 'IsArticle', 'ArticleId', 'Status',
                           'CreatedBy','CreatedDate','ModifiedBy','ModifiedDate', 'IsDelete'];

	// column name that will not be shown
	protected $primaryKey = "Id";

    //append a new field for results
    protected $appends = ['CreatedDate','ModifiedDate'];

    public function category()
    {
        return $this->hasMany('App\Models\Category', 'SiteCode', 'Id')->where('IsDelete', 0);
    }

    public function theme()
    {
        return $this->hasOne('App\Models\Theme', 'SiteCode', 'Id');
    }

    public function article()
    {
        return $this->hasMany('App\Models\Article', 'SiteCode', 'Id')->where('IsDelete', 0);
    }

    public function siteArticle() {
        return $this->hasOne('App\Models\Article', 'Id', 'ArticleId')->where('IsDelete', 0);
    }

    public function parentSite() {
        return $this->hasOne('App\Models\Site', 'Id', 'ParentId')->where('IsDelete', 0);
    }

    public function subsites() {
        return $this->hasMany('App\Models\Site', 'ParentId', 'Id')->where('IsDelete', 0);
    }

    public function usersite()
    {
        return $this->hasMany('App\Models\UserHasSite', 'SiteCode', 'Id');
    }

    public function siteUsers() {
        return $this->belongsToMany('App\User', 'user_has_sitecode', 'SiteCode', 'UserId');
    }

    //function for the appended data
    public function getCreatedDateAttribute($value){
        return date("n/d/Y g:i:s A",strtotime($this->attributes['CreatedDate']));
    }

    //function for the appended data
    public function getModifiedDateAttribute($value){        
        return date("n/d/Y g:i:s A",strtotime($this->attributes['ModifiedDate']));
    }
}
