<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User, App\Group_request, Hash; 
use Illuminate\Support\Facades\Auth; 
use Validator, DB;
use Illuminate\Validation\Rule;
use Session;
use Illuminate\Routing\UrlGenerator;

class NotificationController extends Controller
{
public $successStatus = true;
public $failureStatus = false;
    
    /*get notification listing*/
    public function getNotificationListing( Request $request ){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required', 
                'type'  => 'required', 
            ],
            [   
                'user_id.required'     => 'required user_id',
                'type.required'        => 'required type',
            ]
        );

        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }
        $user_id = $forminput['user_id'];

        $notiIds =  DB::table('notifications as noti')
            ->select(DB::raw('group_concat(noti.id) as noti_id'))
            ->whereRaw("FIND_IN_SET('$user_id',noti.send_to)")
            ->get();

        $list = array();    
        if( !empty($notiIds[0]->noti_id) ){    

	        $ar = explode(",", $notiIds[0]->noti_id);    

	        $list =  DB::table('notifications_status as noti_status')
	            ->select('noti.type as notification_type', 'noti_status.notification_id', 'noti_status.notification_id', 'noti_status.seen_status','users.id as user_id', 'users.fullname', 'users.profile_image')
	            ->join('users','users.id','=','noti_status.user_id')
	            ->join('notifications as noti','noti.id','=','noti_status.notification_id')
	            ->whereIn('noti_status.notification_id', $ar)
	            ->where('users.user_status', 1)
	            ->where('noti.type', $forminput['type'])
	            ->where('noti_status.user_id', $user_id)
	            ->get();        
        }
            
        $profile_path = url('/public/uploads/profile_image/');

        if( sizeof($list) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Fetched successfully', 'response'=>[
                'pending' => $list,
                'profile_path' => $profile_path,
                ]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No notifications']); 
        }    
    }       
}
