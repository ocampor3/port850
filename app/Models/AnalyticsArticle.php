<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsArticle extends Model
{
    public $timestamps = false;
	
    protected $table = 'analytics_article'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['UserId', 'ArticleId','StartTime', 'EndTime'];

	// column name that will not be shown
	protected $primaryKey = "Id";

    public function user()
    {
        return $this->belongsTo('App\User', 'UserId', 'Id');
    }

    public function article()
    {
        return $this->belongsTo('App\Models\Article', 'ArticleId', 'Id')->where('IsDelete', 0);
    }
}
