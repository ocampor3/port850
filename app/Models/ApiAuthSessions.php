<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiAuthSessions extends Model
{
    public $timestamps = false;
	
	// add the table name 
    protected $table = 'apiauthsessions'; 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['apiAuthSessionsUsername','apiAuthSessionsToken','apiAuthSessionsIP',
						   'createdDate','ExpirationDate'];		

	// column name that will not be shown
	protected $primaryKey = "apiAuthSessionsID";
}
