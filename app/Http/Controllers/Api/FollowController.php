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
               $friends->user_id = $forminput['user_id'];
               $friends->friend_id = $forminput['follower_id'];
               $friends->save(); 
            }

            /*if user declined following request*/
            if( $follow->request_status == 2 ){
               $friends =  new FriendsModel;
               $friends->user_id = $forminput['user_id'];
               $friends->friend_id = $forminput['follower_id'];
               
               FriendsModel::where('user_id',$forminput['user_id'])->where('friend_id',$forminput['follower_id'])->delete();

                $follow->where('user_id',$forminput['user_id'] )->where( 'follower_id',$forminput['follower_id'] )->update( ['unfollewedDate' => date('Y-m-d')] );
            }

            $check =  $follow->where('user_id',$forminput['user_id'] )->where( 'follower_id',$forminput['follower_id'] )->update( ['request_status' => $follow->request_status] );

        }else{
            $follow->request_status = 0;
            $follow->followedDate = date('Y-m-d');
            $check = $follow->save();
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
        $followerList = DB::select("select follower_id as user_id from follower where user_id = '$user_id' and request_status = 1");

        if( !empty($followerList) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'List successfully', 'response'=>['followers' => $followerList]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No followers']); 
        }
    }

    /** 
    * getfollowinglist api 
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
        $followerList = DB::select("select user_id from follower where follower_id = '$user_id' and request_status = 1");

        if( !empty($followerList) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'List successfully', 'response'=>['followings' => $followerList]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Not following to anyone']); 
        }
    }         
}