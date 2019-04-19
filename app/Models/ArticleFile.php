<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleFile extends Model
{
    public $timestamps = false;
	
    protected $table = 'article_files'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['ArticleId','File','CreatedBy','CreatedDate','ModifiedBy','ModifiedDate'];		

	// column name that will not be shown
	protected $primaryKey = "Id"; 
}
