<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
class Article extends Model
{
    public $timestamps = false;

    protected $table = 'articles'; // add the table name 
    
	// Fillable data in table : Use the column name in which data will be fillable
    protected $fillable = ['Title','CategoryId','Type','Icon','IconName','Value','GeoLocAssignedUserId','FileName','Status',
    'CalendarValueId',
    'TopBannerShow','MenuFooter','AllowShare','IsArticle','ArticleId','CreatedBy','CreatedDate','ModifiedBy','ModifiedDate',
    'SortOrder','SiteCode', 'IsDelete'];		

    protected $primaryKey = "Id"; 

	// column names that will not be shown
    protected $hidden = ['SiteCode'];

	//append a new field for results
    protected $appends = ['ErrorMessage','CreatedDate','ModifiedDate','LinkValue','EncryptedLinkValue'];

	//function for the appended data()
    public function getErrorMessageAttribute($value){
        return $value;
    }

    //function for the appended data
    public function getCreatedDateAttribute($value){
        return date("n/d/Y g:i:s A",strtotime($this->attributes['CreatedDate']));
    }

    //function for the appended data
    public function getModifiedDateAttribute($value){        
        return date("n/d/Y g:i:s A",strtotime($this->attributes['ModifiedDate']));
    }

     //function for the appended data
    public function getLinkValueAttribute($value){  


        if($this->attributes['Type'] == 'LINKAutofill' || $this->attributes['Type'] == 'LINKCredential')
        {

            return isset($this->attributes['Value'])?$this->attributes['Value']:'';
        } else if($this->attributes['Type'] == 'LINKPassword' && isset($this->attributes['Value'])) {


            $start = strpos($this->attributes['Value'], ':',5);
            $end = strpos($this->attributes['Value'], '@');
            $password = substr($this->attributes['Value'], $start+1,$end-($start+1));
            //change to asterisk
            //$val = isset($this->attributes['Value'])?substr_replace($this->attributes['Value'], '*****',$start+1, $end-($start+1)):'';

            //change to actual decrypted value
            if(strlen($password)>150){
                $val = isset($this->attributes['Value'])?substr_replace($this->attributes['Value'], Crypt::decryptString($password),$start+1, $end-($start+1)):'';
            }else{
               $val = isset($this->attributes['Value'])?$this->attributes['Value']:'';
           }
            // $protocol = explode('}:', $val)[0].'}:';
            // $filelinkProt = explode('@', $val)[1];

            // $encryptVal = $protocol . '*******@' . $filelinkProt;
           return $val;
       }
       else
        return '';

}

public function getEncryptedLinkValueAttribute($value){  


        if($this->attributes['Type'] == 'LINKAutofill' || $this->attributes['Type'] == 'LINKCredential')
        {

            return isset($this->attributes['Value'])?$this->attributes['Value']:'';
        } else if($this->attributes['Type'] == 'LINKPassword' && isset($this->attributes['Value'])) {

            $start = strpos($this->attributes['Value'], ':',5);
            $end = strpos($this->attributes['Value'], '@');
            $password = substr($this->attributes['Value'], $start+1,$end-($start+1));
            //change to asterisk
            //$val = isset($this->attributes['Value'])?substr_replace($this->attributes['Value'], '*****',$start+1, $end-($start+1)):'';

            //change to actual decrypted value
            if(strlen($password)>150){
                $val = isset($this->attributes['Value'])?substr_replace($this->attributes['Value'], '*****',$start+1, $end-($start+1)):'';
            }else{
               $val = isset($this->attributes['Value'])?$this->attributes['Value']:'';
           }

           return  $val;
       }
       else
        return '';

}

public function category()
{
    return $this->hasMany('App\Models\Category', 'Id', 'CategoryId')->where('IsDelete', 0);
}

public function site()
{
    return $this->belongsTo('App\Models\Site', 'SiteCode', 'Id')->where('IsDelete', 0);
}

public function geoLocAssignedUser() {
    return $this->hasOne('App\User', 'Id', 'GeoLocAssignedUserId');
}

public function calendarValue() {
    return $this->hasOne('App\Models\CalendarValue', 'Id', 'CalendarValueId');
}

public function linkedArticle() {
    return $this->hasOne('App\Models\Article', 'Id', 'ArticleId')->where('IsDelete', 0);
}

}
