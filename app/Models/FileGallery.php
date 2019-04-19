<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileGallery extends Model
{
    public $timestamps = false;
	
    protected $table = 'file_gallery'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['FileName','FileType','Contents','CreatedBy','CreatedDate','ModifiedBy','ModifiedDate'];

	// column name that will not be shown
	protected $primaryKey = "Id";

	//protected $hidden = ['Id'];
}
