<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User, App\Group_request, Hash; 
use Illuminate\Support\Facades\Auth; 
use Validator, DB;
use Illuminate\Validation\Rule;
use Session;
use App\FollowModel, App\FriendsModel; 
use Illuminate\Routing\UrlGenerator;

class FollowController extends Controller 
{
public $successStatus = true;
public $failureStatus = false;
    
    /** 
    * sendfollowrequest api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function sendfollowrequest(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required', 
                'follower_id'  => 'required',
                //'status'     => 'required'          
            ],
            [   
                'user_id.required'     => 'required user_id',
                'follower_id.required'   => 'required follower_id',
                //'status.required'       => 'required status'
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }
        $follower_id = $forminput['follower_id'];
        $user_id = $forminput['user_id'];

        $follow  = new FollowModel;
        $follow->follower_id = $forminput['follower_id'];
        $follow->user_id = $forminput['user_id'];
        $follow->request_status = isset( $forminput['request_status'] )? $forminput['request_status']: $follow->request_status;

        $select_query=DB::select("SELECT count(*) as count from follower where follower_id='$follower_id' AND user_id='$user_id'");

        if( $select_query[0]->count > 0 ){
            
             /*if user accept following request*/
            if( $follow->request_status == 1 ){
               

               $friends =  new FriendsModel;
               
               $sel_query=DB::select("SELECT count(*) as count from friends where friend_id='$follower_id' AND user_id='$user_id'");

               if( $sel_query[0]->count == 0){
                    $friends->user_id = $forminput['user_id'];
                    $friends->friend_id = $forminput['follower_id'];
                    $check = $friends->save();
               }

               $follow->where('user_id',$forminput['user_id'] )->where( 'follower_id',$forminput['follower_id'] )->update( ['followedDate' => date('Y-m-d'),'unfollewedDate' => NULL ] ); 
            }

            /*if user declined following request*/
            if( $follow->request_status == 2 ){
               $friends =  new FriendsModel;
               $friends->user_id = $forminput['user_id'];
               $friends->friend_id = $forminput['follower_id'];
               
               FriendsModel::where('user_id',$forminput['user_id'])->where('friend_id',$forminput['follower_id'])->delete();

                $follow->where('user_id',$forminput['user_id'] )->where( 'follower_id',$forminput['follower_id'] )->update( ['unfollewedDate' => date('Y-m-d'),'followedDate' => NULL] );
            }

            $check =  $follow->where('user_id',$forminput['user_id'] )->where( 'follower_id',$forminput['follower_id'] )->update( ['request_status' => $follow->request_status] );

        }else{
            $follow->request_status = 0;
            $check = $follow->save();
        }
        $request_id = 0;
        if(DB::getPdo()->lastInsertId() != 0){
            $request_id = DB::getPdo()->lastInsertId();

            DB::table('notifications')->insert(
                [
                  'type' =>  'request',
                  'type_id' => $request_id,
                  'status' =>  1,
                  'send_by' => $forminput['follower_id'],
                  'send_to' => $forminput['user_id'],
                ]
            );

            $noti_id = DB::getPdo()->lastInsertId();
            
            DB::table('notifications_status')->insert(
                [
                  'notification_id' =>  $noti_id,
                  'user_id' => $forminput['user_id']
                ]
            );
        }
        
        if( $check ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Request submitted successfully', 'response'=>['user_id' => $request->user_id]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }

    /** 
    * getfollowerslist api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function getfollowerslist(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required', 
            ],
            [   
                'user_id.required'     => 'required user_id',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $user_id = $forminput["user_id"];
        
        if( empty( $request->search_text ) ){
            $followerList =  DB::table('follower')
            ->select('follower.follower_id','follower.request_status', 'users.fullname', 'users.email', 'users.user_address', 'users.profile_image', 'users.cover_image', 'users.user_mob', 'users.user_gender', 'users.dob', 'users.user_status', 'users.oauth_provider', 'users.devicetype', 'users.deviceid', 'users.api_token')
            ->join('users','users.id','=','follower.follower_id')
            ->where('follower.user_id', $user_id)
            ->where('follower.request_status', 1)
            ->where('users.user_status', 1)
            ->get();
        }else{
            $followerList =  DB::table('follower')
            ->select('follower.follower_id','follower.request_status', 'users.fullname', 'users.email', 'users.user_address', 'users.profile_image', 'users.cover_image', 'users.user_mob', 'users.user_gender', 'users.dob', 'users.user_status', 'users.oauth_provider', 'users.devicetype', 'users.deviceid', 'users.api_token')
            ->join('users','users.id','=','follower.follower_id')
            ->where('follower.user_id', $user_id)
            ->where('users.user_status', 1)
            ->where('follower.request_status', 1)
            ->where('users.fullname', 'like', '%' . $request->search_text . '%')
            ->get();
        }
         $count= count($followerList);
        foreach ($followerList as $key =>$row) {
           // print_r($row->follower_id);die();
             $mutralList=DB::select("SELECT * from follower where user_id= '$row->follower_id'"); 
               $row->Mutual_friend = count($mutralList);
              } 
         //print_r($count);
        if(sizeof($followerList) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'List successfully', 'response'=>['followers_count'=>$count,'followers' => $followerList
            ]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No followers']); 
        }
    }

    /** 
    * getfollowinglist with search user api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function getfollowinglist(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required', 
            ],
            [   
                'user_id.required'     => 'required user_id',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }
        $user_id = $forminput["user_id"];
        if( empty( $request->search_text ) ){
            $followingList =  DB::table('follower')
            ->select('follower.user_id', 'follower.request_status','users.fullname', 'users.email', 'users.user_address', 'users.profile_image', 'users.cover_image', 'users.user_mob', 'users.user_gender', 'users.dob', 'users.user_status', 'users.oauth_provider', 'users.devicetype', 'users.deviceid', 'users.api_token')
            ->join('users','users.id','=','follower.user_id')
            ->where('follower.follower_id', $user_id)
            ->where('users.user_status', 1)
            ->where('follower.request_status', 1)
            ->get();
        }else{
            $followingList =  DB::table('follower')
            ->select('follower.user_id', 'follower.request_status','users.fullname', 'users.email', 'users.user_address', 'users.profile_image', 'users.cover_image', 'users.user_mob', 'users.user_gender', 'users.dob', 'users.user_status', 'users.oauth_provider', 'users.devicetype', 'users.deviceid', 'users.api_token')
            ->join('users','users.id','=','follower.user_id')
            ->where('follower.follower_id', $user_id)
            ->where('users.user_status', 1)
            ->where('follower.request_status', 1)
            ->where('users.fullname', 'like', '%' . $request->search_text . '%')
            ->get();
        }   
        $count =count($followingList);
        foreach ($followingList as $key =>$row) {
             $mutralList=DB::select("SELECT * from follower where follower_id= '$row->user_id'"); 
               $row->Mutual_friend = count($mutralList);
              } 
        
        if(sizeof($followingList) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'List successfully', 'response'=>[
                'following_user'=>$count,'followings' => $followingList ]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Not users found']); 
        }
    }

    /*get follwers by user id*/
    public function getFollowersByUserId( $user_id, $user_ids = array()){

        $followerList =  DB::table('follower')
            ->select('follower.follower_id')
            ->join('users','users.id','=','follower.follower_id')
            ->where('follower.user_id', $user_id )
            ->where('follower.request_status', 1)
            ->where('users.user_status', 1)
            ->get();

        if(sizeof($followerList)){
            foreach( $followerList as $userId ){
                $user_ids[] = $userId->follower_id;
            }
        }
        return $user_ids;    
    }

    /*get followings by user id*/
    public function getFollowingsByUserId( $user_id, $user_ids = array()){

        $followingList =  DB::table('follower')
            ->select('follower.user_id')
            ->join('users','users.id','=','follower.user_id')
            ->where('follower.follower_id', $user_id)
            ->where('users.user_status', 1)
            ->where('follower.request_status', 1)
            ->get();

        if(sizeof($followingList)){
            foreach( $followingList as $userId ){
                $user_ids[] = $userId->user_id;
            }
        }
        return $user_ids;    
    }

    /*get pending request*/
    public function getFollowRequest( Request $request ){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required', 
            ],
            [   
                'user_id.required'     => 'required user_id',
            ]
        );

        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $pendingRequest =  DB::table('follower')
            ->select('follower.follower_id', 'users.fullname', 'users.profile_image')
            ->join('users','users.id','=','follower.follower_id')
            ->where('users.user_status', 1)
            ->where('follower.request_status', 0)
            ->where('follower.user_id', $request->user_id)
            ->get();
                
        if( sizeof($pendingRequest) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Fetched successfully', 'response'=>[
                'pending' => $pendingRequest,
                ]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No pending request']); 
        }    
    }       
}