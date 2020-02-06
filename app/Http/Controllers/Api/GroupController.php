<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User, App\Group_request, Hash; 
use Illuminate\Support\Facades\Auth; 
use Validator, DB;
use Illuminate\Validation\Rule;
use Session;
use App\GroupsModel;

class GroupController extends Controller 
{
public $successStatus = true;
public $failureStatus = false;
    
    /** 
    * create group api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function creategroup(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'group_name'  => 'required', 
                'group_owner_id'  => 'required',
            ],
            [   
                'group_name.required'     => 'required group_name',
                'group_owner_id.required'   => 'required group_owner_id',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $name = '';
        if ($request->hasFile('group_image')) {
            $image = $request->file('group_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/group_image');         
            $imagePath = $destinationPath. '/'.  $name;
            $image->move($destinationPath, $name);
        }

        $groupmodel  = new GroupsModel;
        $groupmodel->group_name = $forminput['group_name'];
        $groupmodel->group_owner_id = $forminput['group_owner_id'];
        $groupmodel->group_image = !empty($name)?url('public/uploads/group_image').'/'.$name:url('resources/assets/images/blank_image.jpg');

        $check = $groupmodel->save();

        $group_id  = Db::getpdo()->lastInsertId();
        
        DB::table('group_users')->insert(
                [
                  'user_id' =>  $forminput['group_owner_id'],
                  'group_id' =>  $group_id,
                  'is_group_admin' => 1,
                  
                ]
        );

        if( $check ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Group created successfully', 'response'=>['group_id' => $group_id, 'group_owner_id' => $request->group_owner_id]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }


    /** 
     * get group details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getgroupdetails(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'group_id' => 'required' 
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }
        }

        $group = DB::table('groups')
         ->select('groups.group_owner_id','groups.group_name', 'groups.group_image', 'users.fullname', 'users.email', 'users.profile_image')
        ->where('group_id', $request->group_id)
        ->join('users','users.id','=','groups.group_owner_id')
        ->first();

        $user_list = DB::table('group_users')->where('group_id',  $request->group_id )->get();
        
        $user_list =  DB::table('group_users')
        ->select('group_users.*', 'users.fullname', 'users.email', 'users.user_address', 'users.profile_image', 'users.cover_image', 'users.user_mob', 'users.user_gender', 'users.dob', 'users.user_status', 'users.oauth_provider', 'users.devicetype', 'users.deviceid', 'users.api_token')
        ->join('users','users.id','=','group_users.user_id')
        ->where('group_users.group_id', $request->group_id )
        ->get();

        if(!empty($group) ){
            
            $detail = array(
                'group_owner_id'           => $group->group_owner_id,
                'group_name'          => $group->group_name,
                'group_image'          => $group->group_image,
                'group_owner_name'          => $group->fullname,
                'group_owner_profile_image'          => $group->profile_image,
                'user_list'  => $user_list
            );
            
            return response()->json([
                'status'=>$this->successStatus, 
                'msg' => 'Group details fetched successfully', 
                'response' => $detail
            ]);

        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No details found']);
        }
    }

    /** 
    * add group member api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function addgroupmember(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'group_id'  => 'required', 
                'user_id'  => 'required',
                'is_group_admin' => 'required',
                'added_by' => 'required'
            ],
            [   
                'group_id.required'     => 'required group_id',
                'user_id.required'   => 'required user_id',
                'is_group_admin.required' => 'required is_group_admin',
                'added_by.required' => 'required added_by'
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        if( is_numeric($forminput['user_id']) ){
            
            $res =  DB::table('group_users')->where('group_id', $forminput['group_id'] )->where('user_id', $forminput['user_id'] )->first();

            if( sizeof($res) ){
                $check = DB::table('group_users')->where('user_id',$forminput['user_id'] )->where( 'group_id',$forminput['group_id'] )->update( [ 'is_group_admin' => $forminput['is_group_admin'] ] );
            }else{
                $check = DB::table('group_users')->insert(
                            [
                              'user_id' =>   $forminput['user_id'],
                              'group_id' =>  $forminput['group_id'],
                              'is_group_admin' => $forminput['is_group_admin'],
                            ]
                    );
            }

        }else{
            $users = explode( ',', $forminput['user_id'] );
            foreach( $users as $user_id ){
                $res =  DB::table('group_users')->where('group_id', $forminput['group_id'] )->where('user_id', $user_id )->first();

                if( sizeof($res) ){
                    $check = DB::table('group_users')->where('user_id',$user_id )->where( 'group_id',$forminput['group_id'] )->update( [ 'is_group_admin' => $forminput['is_group_admin'] ] );
                }else{
                    $check = DB::table('group_users')->insert(
                            [
                              'user_id' =>   $user_id,
                              'group_id' =>  $forminput['group_id'],
                              'is_group_admin' => $forminput['is_group_admin'],
                            ]
                    );
                }    
            }
        }

        $groupmodel = new GroupsModel;
        
        $groupmodel::where('group_id', $forminput['group_id'])->update([ 'updated_by' => $forminput['added_by'], 'updated_at' => NOW() ]);
        
        if( $check ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'User added successfully', 'response'=>['group_id' => $forminput['group_id'] ]] );
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }

    /** 
    * remove group member api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function removegroupmember(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'group_id'  => 'required', 
                'user_id'  => 'required',
                'removed_by' => 'required',
                'is_group_admin' => 'required'
            ],
            [   
                'group_id.required'     => 'required group_id',
                'user_id.required'   => 'required user_id',
                'removed_by.required' => 'required removed_by',
                'is_group_admin.required' => 'required is_group_admin'
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }
        
        if( $forminput['is_group_admin'] != 1 ){
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'You are not admin'] );
        }

        $check = DB::table('group_users')->where('group_id',  $forminput['group_id'] )->where('user_id',  $forminput['user_id'] )->delete();
        
        $groupmodel = new GroupsModel;
        
        $groupmodel::where('group_id', $forminput['group_id'])->update([ 'updated_by' => $forminput['removed_by'], 'updated_at' => NOW() ]);
        
        if( $check ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'User removed successfully', 'response'=>['group_id' => $forminput['group_id'] ]] );
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }

    /** 
    * exit group api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function exitgroup(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'group_id'  => 'required', 
                'user_id'  => 'required'
            ],
            [   
                'group_id.required'     => 'required group_id',
                'user_id.required'   => 'required user_id',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }
        $groupmodel = new GroupsModel;
        $groupmodel::where('group_id', $forminput['group_id'])->update([ 'updated_at' => NOW() ]);

        $check = DB::table('group_users')->where('group_id',  $forminput['group_id'] )->where('user_id',  $forminput['user_id'] )->delete();

        if( $check ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'You left group successfully', 'response'=>['group_id' => $forminput['group_id'] ]] );
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }

    /** 
    * group user listing
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function groupuserlist(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'group_id'  => 'required', 
            ],
            [   
                'group_id.required'     => 'required group_id',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $user_list = DB::table('group_users')->where('group_id',  $forminput['group_id'] )->get();
        

        $user_list =  DB::table('group_users')
        ->select('group_users.*', 'users.fullname', 'users.email', 'users.user_address', 'users.profile_image', 'users.cover_image', 'users.user_mob', 'users.user_gender', 'users.dob', 'users.user_status', 'users.oauth_provider', 'users.devicetype', 'users.deviceid', 'users.api_token')
        ->join('users','users.id','=','group_users.user_id')
        ->where('group_users.group_id', $forminput['group_id'])
        ->get();

        if( sizeof( $user_list ) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Group user listing successfully', 'response'=>['user_list' => $user_list ]] );
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No users in this group']); 
        }
    }
    
}