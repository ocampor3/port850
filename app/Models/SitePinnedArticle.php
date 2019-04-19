<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePinnedArticle extends Model
{
    //
    public $timestamps = false;
	
    protected $table = 'site_pinned_article'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['ArticleId','SiteCode'];

	// column name that will not be shown
	protected $primaryKey = "Id";

    public function article()
    {
        return $this->belongsTo('App\Models\Article', 'ArticleId', 'Id')->where('IsDelete', 0);
    }

    public function site() {
        return $this->belongsTo('App\Models\Site', 'SiteCode', 'Id')->where('IsDelete', 0);
    }
}
