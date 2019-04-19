<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;

use App\Models\Site;
use App\Models\UserHasSite;
use App\Models\SecurityGroup;
use App\User;
use Symfony\Component\HttpFoundation\Response;

use Session;
use Hash;
use Auth;

class UserController extends Controller
{
     /**
     * Create a new Controller controller instance.     
     */  
     public function __construct()
     {
        $this->middleware('admin',['except' => ['edit','update']]);       

        //salt hash encrypt
        $this->salt = "RocheApp";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!empty($_COOKIE['user_pageshow']))
        {   
            $paginate = $_COOKIE['user_pageshow'];
        }   
        else
            $paginate = 25;

        $iusers = [];

        //check accessibility of user logged in
        if(Auth::user()->UserGroup != 'Admin')
            {
                $userId = Auth::user()->Id;

            // get all sites where logged in user belongs to
                $userSites = UserHasSite::with('sites')->where('UserId', $userId)->get()->pluck('SiteCode')->toArray();

            // get all user ids that belongs to the sites
                $userIds = UserHasSite::whereIn('SiteCode', $userSites)->groupBy('UserId')->pluck('UserId');

                $user = User::with('usersite.sites')
                ->where('Id','!=',Auth::user()->Id)
                ->whereIn('Id', $userIds)
                ->where('Status', null)
                ->paginate($paginate);
            }
            else
            {
                $user = User::with('usersite.sites')
                ->where('Id','!=',Auth::user()->Id)
                ->where('Status', null)
                ->paginate($paginate); 

                $iusers = User::where('Status', 'inactive')->get();

            //return $user[22]['usersite'];
            // $userSites = UserHasSite::with('sites')->where('UserId',Auth::user()->Id)->get();

            // $arrSites = [];

            // foreach ($userSites as $key => $site) 
            // {   
            //     array_push($arrSites,$site['sites'][0]->SiteCode);
            // }


            // return $arrSites;



            }

            return view ('pages.user.index', ['users' => $user, 'inactiveusers' => $iusers, 'paginate' => $paginate]);
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site = Site::where('IsDelete',0)->pluck('Title', 'Id');

        $securitygroups = SecurityGroup::get()->pluck('DisplayName', 'Id');

        return view ('pages.user.create', ['site' => $site, 'securitygroups' => $securitygroups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // var_dump($request->sitecodesg);
        // echo "<br /><br /><br />";
        // var_dump($request->sitecode);
        $this->validate($request, [
            'name'              => 'required',
            'username'          => 'required|unique:users',
            'pincode'           => 'nullable|digits:4'
        ]);

        $this->validatePassword($request);

        //new User
        $user = new User; 

        $this->fillPassword($user, $request);


        $this->fillUserDetails($user, $request, null, true);
        //flash a notification
        Session::flash('flash_message', 'User created successfully.');

        return redirect()->route('user.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('usersite.sites')->where('Id',$id)->get();
        $userSites = User::with('usersite')->where('Id',$id)->first();
        $encode = json_encode($userSites->usersite);

        //1. allow user to edit an account if user is an owner or an admin
        //2. allow user to edit his/her own account
        if(Auth::user()->UserGroup != 'Owner' && !Auth::user()->UserGroup == 'Admin') {
            if(Auth::user()->Id != $id)
            {
                return view('errors.401');
            }
        }

        $site = Site::where('IsDelete',0)->pluck('Title', 'Id');

        $securitygroups = SecurityGroup::get()->pluck('DisplayName', 'Id');

        if(isset($user->first()->DomainPassword)){
           $user->first()->DomainPassword = Crypt::decryptString($user->first()->DomainPassword);
       }
       return view ('pages.user.edit', ['user' => $user, 'site' => $site, 'securitygroups' => $securitygroups,'initSiteSG'=>$encode]);

    }

    public function editUser() {
        $user = User::with('usersite.sites')->where('Id',Session::get('active_user'))->get();
        $userSites = User::with('usersite')->where('Id',Session::get('active_user'))->first();
        $encode = json_encode($userSites->usersite);
        // 1. allow user to edit an account if user is an owner or an admin
        // 2. allow user to edit his/her own account
        if(Auth::user()->UserGroup != 'Owner' && !Auth::user()->UserGroup == 'Admin') {
            if(Auth::user()->Id != $id) {
                return view('errors.401');
            }
        }

        $site = Site::where('IsDelete',0)->pluck('Title', 'Id');

        $securitygroups = SecurityGroup::get()->pluck('DisplayName', 'Id');

        

        return view ('pages.user.edit', ['user' => $user, 'site' => $site, 'securitygroups' => $securitygroups,'initSiteSG'=>$encode]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


         $user = User::find($id);
        // var_dump($request->sitecode);
        // var_dump($request->sitecodesg);
        $this->validate($request, [
            'name'      => 'required',
            'username'  => 'required',
            'pincode'   => 'nullable|digits:4'
        ]);

        $sameusername = User::where('Id','!=',$id)->where('UserName','=',$request->username)->get();

        if(count($sameusername) != 0) {
            $this->validate($request, [
                'username' => 'required|unique:users',
            ]);
        }

        //if same id = edit profile            
        if(Auth::user()->Id == $id)
            {   
            //update password
                if($request->password && $request->oldPass && $request->confirmPassword )
                {
                    if (!Hash::check($request->input('oldPass'), $user->Password)) 
                        { 
                            Session::flash('error_message', 'Incorrect Old Password.');

                            return redirect()->back();
                        }

                        $this->validatePassword($request);
                        $this->fillPassword($user, $request);
                    }

                    $this->fillUserDetails($user, $request, $id, false);

            //flash a notification
                    Session::flash('flash_message', 'Profile updated successfully.');

                    return redirect()->back();
                }
        //if not, then admin or owner is editing user
                else
                {
                    if($request->password && $request->confirmPassword )
                    {
                        $this->validatePassword($request);
                        $this->fillPassword($user, $request);
                    }

                    $this->fillUserDetails($user, $request, $id, false);

            //flash a notification
                    Session::flash('flash_message', 'User updated successfully.');

                    return redirect()->route('user.index');
                }          
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        //flash a notification
        Session::flash('flash_message', 'User deleted successfully.');    

        return redirect()->route('user.index');
    }

    /**
     *
     *  Validates user input on password and confirm password
     *
     */
    private function validatePassword(Request $request) {
        $this->validate($request, [
            'password'          => 'required',            
            'confirmPassword'   => 'required|same:password'                
        ]);
    }

    /**
     *  Fill/Replace user's username field with user's input
     */
    private function fillUserName(User $user, Request $request) {
        $user->UserName = $request->username;
    }

    /**
     *  Fill/Replace user's full name field with user's input
     */
    private function fillFullName(User $user, Request $request) {
        $user->FullName = $request->name;
    }

    /**
     *  Fill/Replace user's password field with user's input
     */
    private function fillPassword(User $user, Request $request) {
        $user->Password = bcrypt($request->password);
    }

    /**
     *  Set user as active (null is active)
     */
    private function fillStatus(User $user, Request $request) {
        $user->Status = null;
    }

    /**
     *  Fill/Replace user's pincode field with user's input
     */
    private function fillPincode(User $user, Request $request) {
        $user->Pincode = $request->pincode;
    }

    /**
     *  Fill/Replace user's user group field with user's input
     */
    private function fillUserGroup(User $user, Request $request) {
        $user->UserGroup = $request->usergroup;
    }

    /**
     *  Fill/Replace user's user group field with user's input
     */
    private function fillSecurityGroup(User $user, Request $request) {
        //$user->SecurityGroupId = $request->securitygroup;
    }

    /**
     *  Fill/Replace user's domain field with user's input
     */
    private function fillDomain(User $user, Request $request) {
        $user->Domain = $request->domain;
    }

    /**
     *  Fill/Replace user's domain user id field with user's input
     */
    private function fillDomainUserId(User $user, Request $request) {
        $user->DomainUserId = $request->domainuserid;
    }

    /**
     *  Fill/Replace user's domain password field with user's input
     */
    private function fillDomainPassword(User $user, Request $request) {
        if(isset($request->domainpassword))
            if(strlen($request->domainpassword)>=100){
                
                $user->DomainPassword =$request->domainpassword;

            }else
            $user->DomainPassword = Crypt::encryptString($request->domainpassword);
    }

    /**
     *  Fill user's creation details
     */
    private function fillCreationDetails(User $user) {
        $user->CreatedBy    = Auth::user()->UserName;
        $user->CreatedDate  = date("y-m-d h:i:s");
    }

    /**
     *  Fill/Replace user's modification details
     */
    private function fillModificationDetails(User $user) {
        $user->ModifiedBy   = Auth::user()->UserName;
        $user->ModifiedDate = date("y-m-d h:i:s");
    }

    /**
     *  Save new sites
     */
    private function saveNewSites(User $user, Request $request) {
        if(!empty($request->sitecode))
        {
            foreach($request->sitecode as $key => $site)
            {   
                // retrieve user site or create if does not exist
                $site = UserHasSite::firstOrCreate(['SiteCode' => $request->sitecode[$key],
                    'UserId' => $user->Id]);

                $site->SecurityGroupId =  $request->sitecodesg[$key];
                $site->save();
            }    
        }
    }

    /**
     *  Delete user sites that are no longer in the list of sites associated to user
     */
    private function deleteSitesForUser(Request $request, $id) {
        $delSites = UserHasSite::where("UserId",$id)->get();

        // check if there is any site associated to user
        if(!empty($delSites)) {
            // check if user is set to be associated to any site
            if(!empty($request->sitecode)) {

                // delete user sites that are no longer in the list of sites associated to user
                foreach($delSites as $delSite) {
                    if(!array_key_exists($delSite->SiteCode, $request->sitecode)) {
                        $delSite->delete();
                    }
                }
            } else {
                // delete all sites associated to user
                UserHasSite::where("UserId",$id)->delete();
            }
        }
    }

    private function fillUserDetails(User $user, Request $request, $id,$isCreate) {
        $this->fillUserName($user, $request);
        $this->fillFullName($user, $request);
        $this->fillPincode($user, $request);
        $this->fillDomain($user, $request);
        $this->fillDomainUserId($user, $request);
        $this->fillDomainPassword($user, $request);
        $this->fillUserGroup($user, $request);
        $this->fillSecurityGroup($user, $request);
        $this->fillStatus($user, $request);

        // if(Auth::user()->UserGroup == 'Admin')     
        // {
        //     $user->SiteCode = $request->sitecode;
        // }

        $user->save();


        $this->deleteSitesForUser($request, $id);
        
        $this->saveNewSites($user, $request);
        
        if($isCreate) {
            $this->fillCreationDetails($user);
        }

        $this->fillModificationDetails($user);

        $user->save();
    }

    public function getSiteSecurityGroup(Request $request){
        
        $siteSecurityGroup = SecurityGroup::where("SiteId","=",$request->SiteId)
        ->whereNotNull("SiteId")
        ->get();
       return response()->json([
            'status' => '',
            'message' => '',
            'data'=> $siteSecurityGroup
        ]);
      
    }

   

}
