<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppAccess extends Model
{
    public $timestamps = false;
	
    protected $table = 'appaccess'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['AppName','AppAccessKey','IPWhiteListing','Icon','IconName','Value','FileName',
                           'CreatedBy','CreatedDate','ModifiedBy','ModifiedDate','isDeleted','isActive'];		

	// column name that will not be shown
	protected $primaryKey = "AppAccessId"; 
}
