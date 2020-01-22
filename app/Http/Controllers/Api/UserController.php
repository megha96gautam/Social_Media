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
                //->where('user_role', 2)
                ->where(function($query) use ($email){
                    $query->where('email', $email);
                })           
                ->first();
        
        if(!empty($user)){
            $credentials = array(
                        'email'         => $user->email,
                        'password'      => $request->password,
                       // 'user_role'  => '2'
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
                    $msg = Auth::user()->name . ' Login successfully';                   
                }
                
                return response()->json([
                        'status'=>$status, 
                        'msg' => $msg, 
                        'response' => 
                            [
                                'user_id'           => $user->id,
                                'fullname'          => $user->name.' '.$user->lname,
                                'email'             => $user->email,
                                'user_mobile'       => $user->user_mob,
                                'gender'            => $user->user_gender,
                                'dob'               => $user->dob,
                                'user_city'         => $user->user_city,
                                'address'      => $user->user_address,
                                'user_status'       => $user->user_status,
                                'oauth_provider'          => $user->oauth_provider,
                                'devicetype'       => $user->devicetype,
                                'deviceid'       => $user->deviceid,
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

    /** 
    * Register api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function register(Request $request) 
    {   
        $validator = Validator::make($request->all(), [ 
            'username'  => 'required', 
            'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->where(function ($query) {
                        return $query->where('user_status','!=', 2);
                    }),
                ],
            'password'  => [
                'required', 
                'min:8', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ],     
            'mobile' => [
                'required',
                'min:8',
                'max:12',
            ], 
            'date_of_birth' => 'required|date|before:-18 years',
            'address'  => 'required',
            'gender'  =>  'required'  
        ],
        [   
            'username.required'         => 'Username is required',
            
            'email.required'            => 'Email is required',
            'email.email'               => 'Please enter valid email address',
            'email.unique'              => 'Email already exist',
            
            'password.required'         => 'Password is required',
            'password.min'              => 'Please eneter atleast 8 characters',
            'password.regex'            => 'Password must contain a lowercase letters, uppercase letter,  special characters, numbers',
            
            'mobile.required'           => 'Mobile no. is required',
            'mobile.min'                => 'Mobile no is invalid',
            'mobile.max'                => 'Mobile no is invalid',

            'date_of_birth.before'      => 'User must be 18 years old',
            'date_of_birth.required'    => 'Date of Birth is required',

            'address.required'     => 'Address is required',
            'gender.required'      => 'Gender is required'

        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }
        }

        $digits                 = 6;
        $otp                    = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $forminput              = $request->all();
        $user                   = new User; 
        $user->name         = $forminput['username'];
        $user->email            = $forminput['email'];

        $user->password         = Hash::make($forminput['password']);
        $user->user_mob           = $forminput['mobile'];

        $user->user_gender           = $forminput['gender'];

        $user->dob    = $forminput['date_of_birth'];
        $user->user_address    = $forminput['address'];

        $user->user_status      = 0;
        $user->user_role        = '2';
        $user->user_otp         = $otp;
        $user->created_at       = Date('Y-m-d H:i:s');
        
        $user->devicetype      = isset($forminput['devicetype'])?$forminput['devicetype']:'';

        $user->deviceid      = isset($forminput['deviceid'])?$forminput['deviceid']:'';

        $user->api_token      = isset($forminput['api_token'])?$forminput['api_token']:'';

        if($user->save()){
             $email_content = DB::Table('email_template')->where('eid', 1)->first();
            $searchArray = array("{user_name}", "{user_email}","{user_mobile}", "{user_otp}", "{site_url}");
          //  $verifyurl = "<a href=".url('verify/email').'/'.$user->vrfn_code.">Verify Email</a>";
            $replaceArray = array($user->name, $forminput['email'], $user->user_mob, $user->user_otp, url('/'));

            $content = str_replace($searchArray, $replaceArray, $email_content->content);
            
            $data = [
                'name'      => $user->fullname,
                'email'     => $forminput['email'],                    
                'vrfn_code' => $user->vrfn_code,         
                'subject'   => $email_content->subject,
                'content'   => $content,
            ];
            send_mail($data);

            return response()->json(
                [
                    'status'=>$this->successStatus, 
                    'msg' => 'Registration successfully, please check your Inbox to verify your account',
                    'response'=>
                        [    
                            'user_id'           => $user->id,
                            'username'              => $user->name,
                            'email'             => $user->email,
                            'address'             => $user->user_address,
                            'mobile'            => $user->user_mob,
                            'gender'            => $user->user_gender,
                            'date_of_birth'     => $user->dob,
                            'user_status'       => $user->user_status,
                            'oauth_provider '   => $user->oauth_provider ,
                            'devicetype'       => $user->devicetype,
                            'deviceid'         => $user->deviceid,
                            'api_token'        => $user->api_token,
                        ]
                ]
            ); 
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Please try again']);
        }
    }
    
}