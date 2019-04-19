<?php 

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\Models\Site;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;

    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['UserName', 'Password', 'Pincode', 'FullName', 'UserGroup', 'SecurityGroupId',
    'SiteCode', 'Domain', 'DomainUserId', 'DomainPassword', 
    'CreatedBy', 'CreatedDate','ModifiedBy', 'ModifiedDate','remember_token',
    'Status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['Password'];


    protected $primaryKey = "Id";

    public function isHandlingSite(Site $site) {
        //check accessibility of user logged in
        if($this->UserGroup != 'Admin')
        {
            // double check if user handles the site of the category
            $sites = $this->HandledSites->where('Id', $site->Id);

            if(count($sites) <= 0) {
                return false;
            }
        }

        return true;
    }

    public function sc()
    {
        return $this->hasOne('App\Models\Site', 'Id', 'SiteCode');
    }

    public function usersite()
    {
        return $this->hasMany('App\Models\UserHasSite', 'UserId', 'Id');
    }

    public function securityGroup() {
        return $this->hasOne('App\Models\SecurityGroup', 'Id', 'SecurityGroupId');
    }

    public function favoriteArticles()
    {
        return $this->belongsToMany('App\Models\Article', 'user_has_favorite', 'UserId', 'ArticleId')
        ->where("IsDelete",0)
        ;
    }

    public function articleNotes()
    {
        return $this->belongsToMany('App\Models\Article', 'user_has_article_note', 'UserId', 'ArticleId')->withPivot('Id', 'Note');
    }

    public function handledSites() {
        return $this->belongsToMany('App\Models\Site', 'user_has_sitecode', 'UserId', 'SiteCode')->where('IsDelete', 0);
    }
}