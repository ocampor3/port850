<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SecurityGroup;
use Illuminate\Support\Facades\DB;
use Session;

class SecurityGroupController extends Controller
{
     /**
     * Create a new Controller controller instance.     
     */  
    public function __construct()
    {
        $this->middleware('admin',['except' => ['']]);       

        //salt hash encrypt
        $this->salt = "RocheApp";
    }



    public function index() {
    	//$securitygroups = SecurityGroup::get();
    	 return redirect()->route('site.index');
        //return view('pages.securitygroup.index', ['securitygroups' => $securitygroups,'SiteId'=>null]);
    }


    public function show($index){

        $securitygroups = SecurityGroup::where('SiteId','=',$index)->get();
        return view('pages.securitygroup.index', ['securitygroups' => $securitygroups,'SiteId'=>$index]);
    }

    public function create($siteId = null) {
    	
        return view('pages.securitygroup.create',['SiteId'=>$siteId]);
    }

    public function store(Request $request) {


    	$check = SecurityGroup::where('SiteId','=',$request->SiteId)
        ->where('DisplayName','=',$request->displayname)
        ->first();
        
        $secGroup = SecurityGroup::firstOrCreate(['DisplayName' => $request->displayname,'SiteId'=>$request->SiteId]);
        
        if(isset($check)){
        
           Session::flash('error_message', 'Security group duplicate item found.');
        
        }else{
            Session::flash('flash_message', 'Security group created successfully.');

        }
       
        //flash a notification
        //Session::flash('flash_message', 'Security group created successfully.');
        
        return redirect()->route('securitygroup.show',[$request->SiteId]);
    }

    public function edit($id) {
    	
        $sgroup = SecurityGroup::find($id);
    	return view('pages.securitygroup.edit', ['sgroup' => $sgroup]);
    }

    public function update(Request $request, $id) {

        
        $displayName = $request->displayname;
    	$duplicateSecurityGroup = SecurityGroup::where('Id', '<>', $id)
    				 ->where('DisplayName', $displayName)
                     ->where('SiteId','=',$request->SiteId)
    				 ->first();

        $sgroup = SecurityGroup::find($id);

    	if(!$duplicateSecurityGroup) {
    		
    		$sgroup->DisplayName = $displayName;
    		$sgroup->save();

	        //flash a notification
	        Session::flash('flash_message', 'Security group updated successfully.');
    	} else {
            Session::flash('error_message', 'Unable to update due to duplicate security group name.');
    	}
        
        //return redirect()->route('securitygroup.index');
        return  redirect()->route('securitygroup.show',[$request->SiteId]);

        //view('pages.securitygroup.edit', ['sgroup' => $sgroup]);
    }

    public function destroy($siteId,$id)
    {

        
         $sg = SecurityGroup::find($id);

         if($sg){
         $sg->delete();
        

         DB::table('user_has_sitecode')
         ->where('SecurityGroupId',$id)
         ->update(['SecurityGroupId'=>null]);
        }
        

        // //delete category itself
        // $category->IsDelete = 1;

        // $category->save();
        
        // //flash a notification
        // Session::flash('flash_message', 'Category deleted successfully.');

         return redirect()->route('securitygroup.show',$siteId);
    }
}
