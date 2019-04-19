<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    public $timestamps = false;
	
    protected $table = 'category'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['Icon','Name','ParentId','Status','AllowUpload','TopBannerShow','AllowShare',
                           'MenuFooter','IsExpanded','SortOrder','ViewColor','IsArticle','ArticleId','CreatedBy','CreatedDate',
                           'ModifiedBy','ModifiedDate','SiteCode', 'IsDelete'];

	// column name that will not be shown
	protected $primaryKey = "Id";

	//hidden columns
	protected $hidden = ['ParentId','SiteCode'];

	//append a new field for results
	protected $appends = ['ErrorMessage','CategoryId','CreatedDate','ModifiedDate'];

	//function for the appended data(ErrorMessage)
	public function getErrorMessageAttribute($value){
        return $value;
    }

    //function for the appended data(CategoryId)
	public function getCategoryIdAttribute(){
        return $this->ParentId;
    }

    //function for the appended data
	public function getCreatedDateAttribute($value){
        return date("n/d/Y g:i:s A",strtotime($this->attributes['CreatedDate']));
    }

    //function for the appended data
	public function getModifiedDateAttribute($value){        
        return date("n/d/Y g:i:s A",strtotime($this->attributes['ModifiedDate']));
    }

    public function site()
    {
        return $this->belongsTo('App\Models\Site', 'SiteCode', 'Id')->where('IsDelete', 0);
    }

    public function category() {
        return $this->belongsTo('App\Models\Category', 'ParentId', 'Id')->where('IsDelete', 0);
    }

    public function categoryArticle() {
        return $this->hasOne('App\Models\Article', 'Id', 'ArticleId')->where('IsDelete', 0);
    }

    public function securityGroups() {
        return $this->belongsToMany('App\Models\SecurityGroup', 'category_security_group', 'CategoryId', 'SecurityGroupId');
    }

}
