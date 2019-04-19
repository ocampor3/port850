<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityGroup extends Model
{
	public $timestamps = false;

    protected $table = 'security_group'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['DisplayName','SiteId'];		

	protected $primaryKey = "Id";

	public function categories() {
		return $this->belongsToMany('App\Models\Category', 'category_security_group', 'SecurityGroupId', 'CategoryId');
	}
}
