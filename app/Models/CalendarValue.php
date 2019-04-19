<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarValue extends Model
{
    public $timestamps = false;
	
    protected $table = 'calendar_value'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
	protected $fillable = ['Description', 'DateStart', 'DateEnd', 'TimezoneStart', 'TimezoneEnd'];		

	protected $primaryKey = "Id"; 

	//append a new field for results
	protected $appends = ['DateStart', 'DateEnd', 'DateStartInput', 'DateEndInput'];

	public function getDateStartAttribute($value) {
        return date("n/d/Y g:i:s A",strtotime($this->attributes['DateStart']));
	}

	public function getDateStartInputAttribute($value) {
		$dateStartString = strtotime($this->attributes['DateStart']);
		return date("Y-m-d", $dateStartString).'T'.date("H:i", $dateStartString);
	}

	public function getDateEndAttribute($value) {
        return date("n/d/Y g:i:s A",strtotime($this->attributes['DateEnd']));
	}

	public function getDateEndInputAttribute($value) {
		$dateEndString = strtotime($this->attributes['DateEnd']);
		return date("Y-m-d", $dateEndString).'T'.date("H:i", $dateEndString);
	}

	public function article() {
        return $this->belongsTo('App\Models\Article', 'Id', 'CalendarValueId');
	}
}
