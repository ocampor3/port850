<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use App\Models\Article;
use App\Models\Site;
use App\Models\CalendarValue;
use App\User;

use File;

class ArticleApiController extends Controller
{
    /**
     * Create a new WebApiController controller instance.     
     */  
    public function __construct()
    {
        $this->middleware('webapiauth', ['except' => ['']]);
    }   

    //---------------------------------------//
    //---------------- GET ------------------//
    //---------------------------------------//

    public function getSiteArticles(Request $request) {
        $siteCode = $request->input('sc');


        //check if site code input is empty
        if($siteCode) {
          $site = Site::where('SiteCode',$siteCode)
                      ->where('IsDelete', 0)
                      ->first();



          //check if site code exist
          if($site) {
            $article = Article::where('IsDelete', 0)
                              ->where('SiteCode', $site->Id)
                              ->get(['Id', 'Title', 'CreatedDate', 'ModifiedDate', 'Type']);

            $article->ErrorMessage = null;;
            return $article;
          } else {
            return response()->json(['ErrorMessage' => 'SiteCode does not exist.']);
          }
        } else {
          return response()->json(['ErrorMessage' => 'Invalid URI. Empty SiteCode value.']);
        }
    }

    /**
     * return all articles for a specific site code
     */
    public function getArticles(Request $request)
    {      
        $siteCode       = $request->input('sc');
        $category_id    = $request->input('cid');  

        //check if site code input is empty
        if($siteCode)
        {   
            $site = Site::where('SiteCode',$siteCode)
                        ->where('IsDelete', 0)
                        ->first();

            //check if site code exist
            if($site)
            {
                //check if site code exist
            	if($category_id)
                {
                  $username = $request->input('username');
                  $user = User::where('UserName', $username)->first();
                  $userId = $user!=null?$user->Id:0;
                  $userGroup = $user!=null?$user->UserGroup:"";
                  if($userGroup == "Owner") {
                    $article = Article::with('CalendarValue')
                                        ->where('SiteCode',$site->Id)
                                        ->where('CategoryId',$category_id)
                                        ->where(function($q) use ($userId) {
                                          $q->where('GeoLocAssignedUserId', $userId)
                                            ->orWhereNull('GeoLocAssignedUserId');
                                        })
                                        ->where('IsDelete', 0)
                                        ->whereIn('Status', ['Live', 'Test'])
                                        ->orderBy('SortOrder','ASC')                             
                                        ->get();
                  } else {
                    $article = Article::with('CalendarValue')
                                        ->where('SiteCode',$site->Id)
                                        ->where('CategoryId',$category_id)
                                        ->where(function($q) use ($userId) {
                                          $q->where('GeoLocAssignedUserId', $userId)
                                            ->orWhereNull('GeoLocAssignedUserId');
                                        })
                                        ->where('IsDelete', 0)
                                        ->where('Status', 'Live')
                                        ->orderBy('SortOrder','ASC')                             
                                        ->get();
                  }
            
	                $article->ErrorMessage = null;           

	                return $article; 
				}
                else                
                    return response()->json(['ErrorMessage' => 'Invalid URI. Empty CategoryId value.']);                
            }
            else               
                return response()->json(['ErrorMessage' => 'SiteCode does not exist.']);                       
        }
        else        
            return response()->json(['ErrorMessage' => 'Invalid URI. Empty SiteCode value.']);
        
    }

    public function getArticle(Request $request) {
      $articleId = $request->input('articleId');

      if($articleId) {
        $article = Article::with('CalendarValue')
                            ->where('Id',$articleId)
                            ->where('IsDelete', 0)
                            ->first();

        return $article;
      } else {
        return response()->json(['ErrorMessage' => 'Invalid URI. Empty article value.']);
      }

    }

    //---------------------------------------//
    //---------------- POST -----------------//
    //---------------------------------------//

    /**
     * create a text article
     */
    public function postTextArticle(Request $request)
    {     
        $newArticle = json_decode($request->input('Json'));   

        if(!empty($newArticle))
        {
            $article = [
                'CategoryId'    => $newArticle->categoryid,
                'Title'         => $newArticle->title,
                'SiteCode'      => $newArticle->sitecode,
                'SortOrder'     => $newArticle->sortorder,
                'CreatedBy'     => $newArticle->user,
                'CreatedDate'   => date('Y-m-d H:i:s'),
                'ModifiedBy'    => $newArticle->user,
                'ModifiedDate'  => date('Y-m-d H:i:s')
            ]; 
        }
       
        if($article) {
          $site = Site::where('SiteCode',$article['SiteCode'])
                      ->where('IsDelete', 0)
                      ->first();

          if(!empty($site)) {
            if(!empty($newArticle->title)) {
              $artType = $newArticle->type;
              if(!empty($artType)) {
                $crArticle = new Article;

                // for article type: geo location
                if($artType == 'GeoLocation') {
                  $geoLocAssignedUserId = $newArticle->geolocassigneduserid;
                  if($geoLocAssignedUserId) {
                    $crArticle = $geoLocAssignedUserId;
                  } else {
                    return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                             'IsSuccessful'     => 'False',
                                             'InnerException'   => 'Empty Geo Location Assigned User.']);
                  }
                }

                // for article type: linked article
                if($artType == 'LinkedArticle') {
                  $linkedArticleId = $newArticle->linkedarticleid;
                  if($linkedArticleId) {
                    $crArticle->IsArticle = 1;
                    $crArticle->ArticleId = $linkedArticleId;

                    $crArticle->Value = 'placeholder';
                  } else {
                    return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                             'IsSuccessful'     => 'False',
                                             'InnerException'   => 'Empty Linked Article']);
                  }
                }

                // for article type: calendar event
                if($artType == 'CalendarEvent') {
                  $calDescription = $newArticle->caldescription;
                  $calDateStart   = $newArticle->caldatestart;
                  $calDateEnd     = $newArticle->caldateend;
                  $calTzStart     = $newArticle->caltzstart;
                  $calTzEnd       = $newArticle->caltzend;

                  $crArticle->Value = 'placeholder';

                  if(!empty($calDescription) && !empty($calDateStart) && !empty($calDateEnd) && !empty($calTzStart) && !empty($calTzEnd)) {
                    $calEvent = CalendarValue::create([
                        'Description'   => $calDescription,
                        'DateStart'     => $calDateStart,
                        'DateEnd'       => $calDateEnd,
                        'TimezoneStart' => $calTzStart,
                        'TimezoneEnd'   => $calTzEnd
                    ]);

                    $calEvent->save();

                    $crArticle->CalendarValueId = $calEvent->Id;
                  } else {
                    return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                             'IsSuccessful'     => 'False',
                                             'InnerException'   => 'Missing field for calendar event']);
                  }
                }

                // check if article type is an article that requires data for 'value' field
                if($artType != 'CalendarEvent' && $artType != 'LinkedArticle') {
                  $value = $newArticle->value;
                  if(!empty($value)) {
                    $crArticle->Value = $value;
                  } else {
                    return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                             'IsSuccessful'     => 'False',
                                             'InnerException'   => 'Empty Value.']);
                  }
                }

                $crArticle->CategoryId    = $article['CategoryId'];
                $crArticle->Title         = $article['Title'];
                $crArticle->Status        = 'Live';
                $crArticle->Type          = $artType;
                $crArticle->SiteCode      = $site->Id;
                $crArticle->SortOrder     = $article['SortOrder'];
                $crArticle->CreatedBy     = $article['CreatedBy'];
                $crArticle->CreatedDate   = $article['CreatedDate'];
                $crArticle->ModifiedBy    = $article['ModifiedBy'];
                $crArticle->ModifiedDate  = $article['ModifiedDate'];
                $crArticle->MenuFooter    = 1;
                $crArticle->AllowShare    = 1;
                $crArticle->TopBannerShow = 1;

                $crArticle->save();

                return response()->json(['ErrorMessage'     => null,
                                         'IsSuccessful'     => 'True',
                                         'InnerException'   => null]);
              } else {
                return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                         'IsSuccessful'     => 'False',
                                         'InnerException'   => 'Empty Type.']);
              }
            } else {
              return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                       'IsSuccessful'     => 'False',
                                       'InnerException'   => 'Empty Title value.']);
            }
          } else {
            return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                     'IsSuccessful'     => 'False',
                                     'InnerException'   => 'SiteCode input error.']);
          }
        } else {
          return response()->json(['ErrorMessage'     => 'Invalid parameters.',
                                   'IsSuccessful'     => 'False',
                                   'InnerException'   => 'Invalid parameters.']);
        }
    }

    //---------------------------------------//
    //---------------- POST -----------------//
    //---------------------------------------//

    /**
     * create a file article
     */
    public function postFileArticle(Request $request)
    {   
        $newArticle = json_decode($request->input('Json'));   
        $newFile    = Input::file('File');  
        
        //get file data as string
        $fileArray  = file_get_contents($newFile);

        //salt hash encrypt
        $salt       = "RocheApp";
        
        //new filename but hashed        
        $hashFile   = hash( 'sha256',  $salt . $newFile->getClientOriginalName()) . '.' . $newFile->getClientOriginalExtension();
        
        //save file to a directory   
        File::put(public_path() . '/files/'. $hashFile, $fileArray );

        if(!empty($newArticle))
        {
            $article = [
                'CategoryId'    => $newArticle->categoryid,
                'Title'         => $newArticle->title,
                'Type'          => $newArticle->type,
                'Value'         => $request->root().'/files/'.$hashFile,
                'FileName'      => $newArticle->filename,
                'SiteCode'      => $newArticle->sitecode,
                'SortOrder'     => $newArticle->sortorder,
                'CreatedBy'     => $newArticle->user,
                'CreatedDate'   => date('Y-m-d H:i:s'),
                'ModifiedBy'    => $newArticle->user,
                'ModifiedDate'  => date('Y-m-d H:i:s')
            ]; 
        }
       
        if($article)
        {            
            $site = Site::where("SiteCode",$article['SiteCode'])
                        ->where('IsDelete', 0)
                        ->first();

            if(!empty($site))
            {
                if(!empty($article['Title']))
                {
                    if(!empty($article['Value']))
                    {
                        $crArticle = Article::create([
                                                'CategoryId'    => $article['CategoryId'],
                                                'Title'         => $article['Title'],
                                                'Status'        => 'Live',
                                                'Type'          => $article['Type'],
                                                'Value'         => $article['Value'],
                                                'FileName'      => $article['FileName'],
                                                'SiteCode'      => $site->Id,
                                                'SortOrder'     => $article['SortOrder'],   
                                                'CreatedBy'     => $article['CreatedBy'],                        
                                                'CreatedDate'   => $article['CreatedDate'],                        
                                                'ModifiedBy'    => $article['ModifiedBy'],                        
                                                'ModifiedDate'  => $article['ModifiedDate'],
                                                'MenuFooter'    => 1,
                                                'AllowShare'    => 1,
                                                'TopBannerShow' => 1
                                             ]);

                        // add prefix {Id}_{filename} on saving
                        Article::where('Id', $crArticle->Id)
                               ->where('IsDelete', 0)
                               ->update(['FileName' => $crArticle->Id . "_" . $article['FileName']]);

                        return response()->json(['ErrorMessage'     => null,
                                                 'IsSuccessful'     => 'True',
                                                 'InnerException'   => null]);                               
                    }
                    else                    
                        return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                                 'IsSuccessful'     => 'False',
                                                 'InnerException'   => 'Empty Value.']);                         
                }
                else              
                    return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                             'IsSuccessful'     => 'False',
                                             'InnerException'   => 'Empty Title value.']);                         
            }
            else
                return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                         'IsSuccessful'     => 'False',
                                         'InnerException'   => 'SiteCode input error.']);  
        }
        else
            return response()->json(['ErrorMessage'     => 'Invalid parameters.',
                                     'IsSuccessful'     => 'False',
                                     'InnerException'   => 'Invalid parameters.']);
    }

    /**
     * get all article notes
    **/
    public function getNotes(Request $request) {
        $username = $request->input('username');
        $user = User::where('UserName', $username)->first();
        $articleId = $request->input('articleId'); 

        if($user) {
            if(!empty($articleId)) {
                $articleNotes = $user->articleNotes()->where('ArticleId', $articleId)->get()->pluck('pivot');

                return $articleNotes;
            } else {
                return response()->json(['ErrorMessage' => 'Invalid URI. Empty ArticleId value.']);
            }
        } else {
          if(!empty($username)) {
            return response()->json(['ErrorMessage' => 'Invalid URI. Invalid username.']);
          } else {
            return response()->json(['ErrorMessage' => 'Invalid URI. Empty Username value.']);
          }
        }
    }

    /**
     *  add article note
    **/
    public function postNote(Request $request) {
        return $this->createOrEditNote($request, true);
    }

    /**
     *  Edit article note
    **/
    public function postEditNote(Request $request) {
        return $this->createOrEditNote($request, false);
    }

    /**
     *  Delete article note
    **/
    public function postDeleteNote(Request $request) {
        $id = $request->input('Id');
        $username = $request->input('username');
        $user = User::where('UserName', $username)->first();

        if($user) {
          if(!empty($id)) {
              $user->articleNotes()->newPivotStatement()->where('Id', $id)->delete();

              return response()->json(['ErrorMessage'     => null,
                                       'IsSuccessful'     => 'True',
                                       'InnerException'   => null]);
          } else {
              return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                       'IsSuccessful'     => 'False',
                                       'InnerException'   => 'Id input error.']);
          }
        } else {
          if(!empty($username)) {
            return response()->json(['ErrorMessage' => 'Invalid URI. Invalid username.']);
          } else {
            return response()->json(['ErrorMessage' => 'Invalid URI. Empty Username value.']);
          }
        }
    }

    /**
     * create or edit note
     * @param $isCreate true if action is to create note, otherwise edit note
    **/
    private function createOrEditNote(Request $request, bool $isCreate) {
        $username = $request->input('username');
        $user = User::where('UserName', $username)->first();
        $articleId = $request->input('ArticleId');
        $note = $request->input('Note');

        if($user) {

            if(!empty($articleId)) {

                if(!empty($note)) {

                  if($isCreate) {
                      // create note
                      $user->articleNotes()->attach($articleId, ['Note' => $note]);
                  } else {
                      // edit note; additional Id param for editing note
                      $id = $request->input('Id');

                      if(!empty($id)) {
                          $user->articleNotes()->newPivotStatement()->where('Id', $id)->update(['Note' => $note]);
                      } else {
                          return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                                   'IsSuccessful'     => 'False',
                                                   'InnerException'   => 'Id input error.']);
                      }
                  }

                  // successfully created/edited note
                  return response()->json(['ErrorMessage'     => null,
                                           'IsSuccessful'     => 'True',
                                           'InnerException'   => null]);
                } else {
                    return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                             'IsSuccessful'     => 'False',
                                             'InnerException'   => 'Note input error.']);
                }
            } else {
                return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                         'IsSuccessful'     => 'False',
                                         'InnerException'   => 'ArticleId input error.']);
            }
        } else {
                return response()->json(['ErrorMessage'     => 'Invalid URI.',
                                         'IsSuccessful'     => 'False',
                                         'InnerException'   => 'Username input error.']);
        }
    }

    public function getMultipleArticles(Request $request)
    {      

        $siteCode       = $request->input('sc');
       // $category_id    = $request->input('cid');  

        //check if site code input is empty
        if($siteCode)
        {   
            $site = Site::where('SiteCode',$siteCode)
                        ->where('IsDelete', 0)
                        ->first();

            //check if site code exist
            if($site)
            {
                //check if site code exist
              if(true)
              {
                $username = $request->input('username');
                $user = User::where('UserName', $username)->first();
                $userId = $user!=null?$user->Id:0;
                $userGroup = $user!=null?$user->UserGroup:"";
                if($userGroup == "Owner") {
                  $article = Article::with('CalendarValue')
                  ->where('SiteCode',$site->Id)
                  ->whereIn('CategoryId',$request->get('categories'))
                  ->where(function($q) use ($userId) {
                    $q->where('GeoLocAssignedUserId', $userId)
                    ->orWhereNull('GeoLocAssignedUserId');
                  })
                  ->where('IsDelete', 0)
                  ->whereIn('Status', ['Live', 'Test'])
                  ->orderBy('SortOrder','ASC')                             
                  ->get();
                } else {
                  $article = Article::with('CalendarValue')
                  ->where('SiteCode',$site->Id)
                  ->whereIn('CategoryId',$request->get('categories'))
                  ->where(function($q) use ($userId) {
                    $q->where('GeoLocAssignedUserId', $userId)
                    ->orWhereNull('GeoLocAssignedUserId');
                  })
                  ->where('IsDelete', 0)
                  ->where('Status', 'Live')
                  ->orderBy('SortOrder','ASC')                             
                  ->get();
                }

                $article->ErrorMessage = null;           

                return $article; 
              }
              else                
                return response()->json(['ErrorMessage' => 'Invalid URI. Empty CategoryId value.']);                
            }
            else               
                return response()->json(['ErrorMessage' => 'SiteCode does not exist.']);                       
        }
        else        
            return response()->json(['ErrorMessage' => 'Invalid URI. Empty SiteCode value.']);
        return $article;
        //return 
    }
}
