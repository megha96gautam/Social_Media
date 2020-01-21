<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User, App\Restaurant_detail, App\Group_request, Hash; 
use Illuminate\Support\Facades\Auth; 
use Validator, DB;
use Illuminate\Validation\Rule;
use Twilio\Rest\Client;
use Session;

class UserController extends Controller 
{
public $successStatus = true;
public $failureStatus = false;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){
        $validator = Validator::make($request->all(), [ 
            'email' => 'required', 
            'password' => 'required', 
        ],
        [
            'email.required' => 'Email is required',
            'password.required' =>  'Password is required',
        ]
        );
        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }
        }
        $email = $request->email;
        
        $user = DB::table('users')
                ->where('user_status', '!=', 2)
                //->where('user_role_id', 2)
                ->where(function($query) use ($email){
                    $query->where('email', $email);
                })           
                ->first();
        
        if(!empty($user)){
            $credentials = array(
                        'email'         => $user->email,
                        'password'      => $request->password,
                       // 'user_role_id'  => '2'
                    );          
            if (Auth::attempt($credentials)){
                $status = ''; 
                $msg = ''; 
                
                if( $user->user_status == 0){
                    Auth::logout();
                    $status = $this->successStatus; 
                    $msg = 'Your Account is not verified';  
                }


                if ($user->user_status == 1){
                    $user = Auth::user(); 
                    $data = User::findorfail(Auth::user()->id);
                    //$data->device_type  = $request->device_type;
                    //$data->device_token = $request->device_token; 
                    //$data->save();
                    $status = $this->successStatus; 
                    $msg = Auth::user()->name . 'Login successfully';                   
                }
                
                $country = DB::table("countries")->where('id', $user->country_code)->first();

                // if(!empty($user->profile_img)){
                //     $img = url('public/uploads/profile_img').'/'.$user->profile_img;
                // }else{
                //     $img = url('resources/assets/images/blank_user.jpg');
                // }

                return response()->json([
                        'status'=>$status, 
                        'msg' => $msg, 
                        'response' => 
                            [
                                'user_id'           => $user->id,
                                'fullname'          => $user->name.' '.$user->lname,
                                'email'             => $user->email,
                                'user_mobile'            => $user->user_mob,
                                'dob'               => $user->dob,
                                'user_city'         => $user->user_city,
                                'country_code'    => $user->country_code,
                                'country_name'    => $country->name,
                                'user_status'       => $user->user_status,
                                'oauth_provider'          => $user->oauth_provider,
                                'device_type'       => $user->devicetype,
                                'api_token'      => $user->api_token,
                                //'profile_img'       => $img,
                                
                            ]
                    ]);
            }else{
                return response()->json([
                    'status'=>$this->failureStatus, 
                    'msg' => 'Your Email Id or Password is not correct',
                    ]); 
            }
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Your Account does not exist']); 
        }
    }

}