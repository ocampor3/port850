<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHasSite extends Model
{
    public $timestamps = false;
	
    protected $table = 'user_has_sitecode'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['UserId','SiteCode','SecurityGroupId'];

	// column name that will not be shown
	protected $primaryKey = "Id";

    public function sites()
    {
        return $this->hasMany('App\Models\Site', 'Id', 'SiteCode')->where('IsDelete', 0);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'UserId', 'Id');
    }

    public function site() {
        return $this->belongsTo('App\Models\Site', 'SiteCode', 'Id')->where('IsDelete', 0);
    }
}
