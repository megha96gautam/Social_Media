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

class FriendController extends Controller 
{
public $successStatus = true;
public $failureStatus = false;
    
    /** 
    * get friend list api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function getfriendlist(Request $request){
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
            $friendList =  DB::table('friends')
            ->select('friends.friend_id', 'users.fullname', 'users.email', 'users.user_address', 'users.profile_image', 'users.cover_image', 'users.user_mob', 'users.user_gender', 'users.dob', 'users.user_status', 'users.oauth_provider', 'users.devicetype', 'users.deviceid', 'users.api_token')
            ->join('users','users.id','=','friends.friend_id')
            ->where('friends.user_id', $user_id)
            ->where('users.user_status', 1)
            ->get();
        }else{
            $friendList =  DB::table('friends')
            ->select('friends.friend_id', 'users.fullname', 'users.email', 'users.user_address', 'users.profile_image', 'users.cover_image', 'users.user_mob', 'users.user_gender', 'users.dob', 'users.user_status', 'users.oauth_provider', 'users.devicetype', 'users.deviceid', 'users.api_token')
            ->join('users','users.id','=','friends.friend_id')
            ->where('friends.user_id', $user_id)
            ->where('users.user_status', 1)
            ->where('users.fullname', 'like', '%' . $request->search_text . '%')
            ->get();
        }    
        
        if( sizeof($friendList) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'List successfully', 'response'=>[
                'friends' => $friendList ]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No friends found']); 
        }
    }   
}