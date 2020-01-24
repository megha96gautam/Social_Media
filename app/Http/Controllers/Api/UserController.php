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
            'email' => [
                        'required',
                        'email', 
                        ],
            'password' => 'required', 
        ],
        [
            'email.required' => 'required email',
            'email.email'     => 'Please enter valid email',
            'password.required' =>  'required password',
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
                    $msg = Auth::user()->fullname . ' Login successfully';                   
                }

                if(!empty($user->profile_image)){
                    $img = url('public/uploads/profile_image').'/'.$user->profile_image;
                }else{
                    $img = url('resources/assets/images/blank_user.jpg');
                }

                if(!empty($user->cover_image)){
                    $cover_img = url('public/uploads/cover_image').'/'.$user->cover_image;
                }else{
                    $cover_img = url('resources/assets/images/blank_image.jpg');
                }
                
                return response()->json([
                        'status'=>$status, 
                        'msg' => $msg, 
                        'response' => 
                            [
                                'user_id'           => $user->id,
                                'fullname'          => $user->fullname,
                                'username'          => $user->username,
                                'email'             => $user->email,
                                'user_mobile'       => $user->user_mob,
                                'gender'            => $user->user_gender,
                                'dob'               => $user->dob,
                                'user_city'         => $user->user_city,
                                'address'      => $user->user_address,
                                'user_status'       => $user->user_status,
                                'profile_image'       => $img,
                                'cover_image'       => $cover_img,
                                'works_at'   => $user->works_at,
                                'study_at'   => $user->study_at,
                                'relation_status' => $user->relation_status,
                                'languages_known' => $user->languages_known,
                                'bio'             => $user->bio,
                                //'user_otp'       => $user->user_otp,
                                'oauth_provider'          => $user->oauth_provider,
                                'devicetype'       => $user->devicetype,
                                'deviceid'       => $user->deviceid,
                                'api_token'      => $user->api_token,
                                
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
            'fullname'  => 'required', 
            'username'  => [ 'required',
                            Rule::unique('users')->where(function ($query) {
                            return $query->where('user_status','!=', 2);
                            }) ], 
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
            'username.required'         => 'required username',
            'username.unique'           => 'Username already exist',
            'fullname.required'         => 'required fullname',
            
            'email.required'            => 'required email',
            'email.email'               => 'Please enter valid email address',
            'email.unique'              => 'Email already exist',
            
            'password.required'         => 'required password',
            'password.min'              => 'Please eneter atleast 8 characters',
            'password.regex'            => 'Password must contain a lowercase letters, uppercase letter,  special characters, numbers',
            
            'mobile.required'           => 'required mobile',
            'mobile.min'                => 'Mobile no is invalid',
            'mobile.max'                => 'Mobile no is invalid',

            'date_of_birth.before'      => 'User must be 18 years old',
            'date_of_birth.required'    => 'required date_of_birth',

            'address.required'          => 'required address',
            'gender.required'           => 'required gender'

        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }
        }

        $name = '';
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/profile_image');         
            $imagePath = $destinationPath. '/'.  $name;
            $image->move($destinationPath, $name);
        }

        $digits                 = 6;
        $otp                    = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $forminput              = $request->all();
        $user                   = new User; 
        $user->username         = $forminput['username'];
        $user->fullname         = $forminput['fullname'];
        $user->email            = $forminput['email'];

        $user->password         = Hash::make($forminput['password']);
        $user->user_mob           = $forminput['mobile'];

        $user->user_gender           = $forminput['gender'];

        $user->dob    = $forminput['date_of_birth'];
        $user->user_address    = $forminput['address'];
        $user->profile_image      = $name;
        $user->user_status      = 0;
        $user->user_role        = 2;
        $user->user_otp         = $otp;
        $user->created_at       = Date('Y-m-d H:i:s');
        
        $user->devicetype      = isset($forminput['devicetype'])?$forminput['devicetype']:'';

        $user->deviceid      = isset($forminput['deviceid'])?$forminput['deviceid']:'';

        $user->api_token      = isset($forminput['api_token'])?$forminput['api_token']:'';

        if(!empty($user->cover_image)){
            $cover_img = url('public/uploads/cover_image').'/'.$user->cover_image;
        }else{
            $cover_img = url('resources/assets/images/blank_image.jpg');
        }

        if($user->save()){

            DB::table('user_otpdetail')->insert(
                    [
                      'user_id' =>  $user->id,
                      'otp_for' =>  'registration',
                      'user_mob' => $user->user_mob,
                      'user_email' => $user->email,
                      'otp_number' =>  $user->user_otp,
                    ]
            );

             $email_content = DB::Table('email_template')->where('eid', 1)->first();
            $searchArray = array("{user_name}", "{user_email}","{user_mobile}", "{user_otp}", "{site_url}");
          //  $verifyurl = "<a href=".url('verify/email').'/'.$user->vrfn_code.">Verify Email</a>";
            $replaceArray = array($user->fullname, $forminput['email'], $user->user_mob, $user->user_otp, url('/'));

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
                            'username'              => $user->username,
                            'fullname'              => $user->fullname,
                            'email'             => $user->email,
                            'address'             => $user->user_address,
                            'profile_image'       => !empty($name)?url('public/uploads/profile_image').'/'.$name:url('resources/assets/images/blank_user.jpg'),
                            'cover_image'       => $cover_img,
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

     /*Forgot Password Api*/
     public function forgotpassword(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
            'email' =>[
                'required',
                'email',
            ],
            'user_role' => [ 'required' ]          
        ],
        [   
            'email.required'   => 'required email',
            'email.email'      =>  'Please enter valid email',
            'user_role.required' => 'required user_role'
        ]);
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }      
        }

        $user = User::where('email', $forminput['email'])->where('user_status','!=', 0)->where('user_role', $forminput['user_role'])->first();
        if((!empty($user))){
            if($user->user_status == 1){
                $digits = 6;
                $otp =  rand(pow(10, $digits-1), pow(10, $digits)-1);
                $user->forgot_pass_otp   = $otp;
                $user->save();

                DB::table('user_otpdetail')->insert(
                    [
                      'user_id' =>  $user->id,
                      'otp_for' =>  'forgotpassword',
                      'user_mob' => $user->user_mob,
                      'user_email' => $user->email,
                          'otp_number' =>  $otp,
                        ]
                );

                User::where('id', $user->id)->update(['forgot_pass_otp' => $otp]);  

                //////////Forgot opt////////////
                $email_content = DB::Table('email_template')->where('eid', 4)->first();

                $searchArray = array("{user_email}","{user_name}",
                    "{user_otp}", "{site_url}");
                $replaceArray = array($user->email, $user->fullname, $otp, url('/'));
                $content = str_replace($searchArray, $replaceArray, $email_content->content);
                
                $data = [
                    'name'      => $user->fullname,
                    'email'     => $user->email,       
                    'subject'   => $email_content->subject,
                    'content'   => $content,
                ];
                send_mail($data);

                /////////////Resend otp//////////////////
                $resp=array("id"=>$user->id, "email"=>$user->email);
                return response()->json(['status'=>$this->successStatus, 'msg' => 'OTP has been sent to your Email', 'response'=>$resp]); 
            }else{
                return response()->json(['status'=>$this->failureStatus, 'msg' => 'Please verify your account first']); 
            }
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Please enter registered Email']); 
        }

    }

    /*verify otp for forgot password and login verification*/
    public function verifyotp(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'otp'  => 'required|min:6', 
                'type'  => 'required',
                'email' =>[
                    'required',
                    'email',
                ],          
            ],
            [   
                'otp.required'     => 'required otp',
                'otp.min'          => 'Please enter at least 6 characters',
                'type.required'    => 'Verify type is required',
                'email.required'   => 'required email',
                'email.email'      =>  'Please enter valid email',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        /*type == 'forgotpassword'*/
        if($forminput['type'] == 1){
           
            $user = User::where('forgot_pass_otp', $forminput['otp'])->where('user_status', '!=', 0)->where('email', $request->email)->first();

            if((!empty($user))){
                if($user->user_status == 1){
                    $user->forgot_pass_otp = null;
                    $user->save();
                    return response()->json(['status'=>$this->successStatus, 'msg' => 'OTP verified, please update your new password', 'response'=>['user_id' => $user->id]]);
                }else{
                    return response()->json(['status'=>$this->failureStatus, 'msg' => 'Your account is not verified', 'response'=>['user_id' => $user->id]]); 

                } 
            }else{
                return response()->json(['status'=>$this->failureStatus, 'msg' => 'Your OTP has been expired or invalid']); 
            }

        }/*type= [login otp verification]*/
        else{
           
            $user = User::where('user_otp', $forminput['otp'])->where('user_status', '=', 0)->where('email', $request->email)->first();
            if((!empty($user))){
                $user->user_status = 1;                
                $user->user_otp = null;
                $user->email_verified = 1;
                $user->email_verified_at = Date('Y-m-d H:i:s');
                $user->save();
                return response()->json(['status'=>$this->successStatus, 'msg' => 'Your account has been verified successfully. Please Login', 'response'=>['user_id' => $user->id]]); 
            }else{
                return response()->json(['status'=>$this->failureStatus, 'msg' => 'Please enter correct email or OTP']); 
            }
        }
    }

    /*for change password*/
    public function changepassword(Request $request){
        $validator = Validator::make($request->all(), [            
            'email' => ['required','email' ], 
            'new_password' =>  [
                'required', 
                'min:8', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ],   
        ],
        [               
            'email.required'            => 'required email',
            'email.email'               => 'Please enter valid email',
            'new_password.required'     => 'required new_password',
            'new_password.min'          => 'Please eneter atleast 8 characters',
            'new_password.regex'        => 'Password must contain a lowercase letters, uppercase letter,  special characters, numbers',
            
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }
        }
        $credentials = array(
            'email' => $request->email,
            'password' => $request->password,
            'user_role' => '2',
            'user_status' => 1
        );
   
        if (Auth::attempt($credentials)){
            $user = User::where('user_role', 2)->where('email', $request->email)->update(['password' => Hash::make($request->new_password)]);
               
            if($user==1){                

                return response()->json(['status'=>$this->successStatus, 'msg' => 'Password updated succcessfully']);
            }else{
                return response()->json(['status'=>$this->failureStatus, 'msg' => 'Password did not updated, please try again later']);
            }                       
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Old password did not match']);
            
        }
    }

    /*resend otp*/
    public function resendotp(Request $request){
        $forminput = $request->all();
        /*if user login from social login then save mobile number and send otp*/
        if($forminput['type'] == 3){
            // social login code to be added
        }else{

            $validator = Validator::make($request->all(), [ 
                'email' => [ 'required', 'email' ],
                'type'      =>  'required'                       
            ],
            [   
                'email.required'   =>  'required email',
                'email.email'      =>  'Please enter valid email',
                'type'              => 'required type'  
            ]
            );
            if ($validator->fails()) { 
                $messages = $validator->messages();
                foreach ($messages->all() as $message)
                {   
                    return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
                }         
            }
            $digits = 6;
            $otp =  rand(pow(10, $digits-1), pow(10, $digits)-1);
            $user = User::where('email', $forminput['email'])->first(); 
            if( !empty($user) ){
                if($forminput['type'] == 0){           
                    $user->user_otp   = $otp;
                }else{
                    $user->forgot_pass_otp   = $otp;
                }            
                if($user->save()){
                       //////////Forgot opt////////////
                    $email_content = DB::Table('email_template')->where('eid', 6)->first();
                    $searchArray = array("{user_name}", '{user_otp}','{user_email}', "{site_url}");
                    $replaceArray = array($user->fullname, $otp,$user->email , url('/'));
                    $content = str_replace($searchArray, $replaceArray, $email_content->content);
                    
                    $data = [
                        'name'      => $user->fullname,
                        'email'     => $user->email,       
                        'subject'   => $email_content->subject,
                        'content'   => $content,
                    ];
                    send_mail($data);

                    /////////////Resend otp//////////////////
                        return response()->json([
                            'status'=>$this->successStatus, 
                            'msg' => 'OTP sent successully', 
                            'response' => [
                                'user_id'           => $user->id,
                                'username'          => $user->username,
                                'fullname'          => $user->fullname,
                                'email'             => $user->email,
                                'address'           => $user->user_address,
                                'mobile'            => $user->user_mob,
                                'gender'            => $user->user_gender,
                                'date_of_birth'     => $user->dob,
                                'user_status'       => $user->user_status,
                                'oauth_provider '   => $user->oauth_provider ,
                                'devicetype'       => $user->devicetype,
                                'deviceid'         => $user->deviceid,
                                'api_token'        => $user->api_token,
                            ]   
                        ]); 
                    }else{
                        return response()->json(['status'=>$this->failureStatus, 'msg' => 'Please resend otp']); 
                    }
            }else{
                return response()->json(['status'=>$this->failureStatus, 'msg' => 'Your account does not exist']); 
            }
        }
    }

    /*API for update user profile*/
    public function updateprofile(Request $request){
        $forminput = $request->all();
        $validator = Validator::make($request->all(), [ 
            //'username'  => 'required', 
            'fullname'  => 'required', 
            'gender' => 'required',
            'date_of_birth' => 'required|date|before:-18 years',
            'address' => 'required',
             'mobile' => [
                'required',
                'min:8',
                'max:12',
            ], 
        ],
        [   
            //'username.required'         => 'required username',
            'fullname.required'         => 'required fullname',
            'gender.required'           => 'required gender',
            'date_of_birth.before'      => 'User must be 18 years old',
            'date_of_birth.required'    => 'required date_of_birth',            
            'address.required'          => 'required address',
            'mobile.required'           => 'required mobile',
            'mobile.min'                => 'Mobile no is invalid',
            'mobile.max'                => 'Mobile no is invalid',

        ]);
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);
            }          
        }
       
        $user = User::where('id', $forminput['user_id'])->where('user_status',1)->first();
        if( !empty($user) ){            
            $name = '';
            if ($request->hasFile('profile_image')) {            
                $image = $request->file('profile_image');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/profile_image/');         
                $imagePath = $destinationPath. '/'.  $name;
                $image->move($destinationPath, $name);
            }

            $cover_name = '';
            if ($request->hasFile('cover_image')) {            
                $cover_image = $request->file('cover_image');
                $cover_name = time().'.'.$image->getClientOriginalExtension();
                $coverDestinationPath = public_path('/uploads/cover_image/');         
                $coverImagePath = $coverDestinationPath. '/'.  $cover_name;
                $cover_image->move($coverDestinationPath, $cover_name);
            }

            //$user->username             = $forminput['username'];
            $user->fullname             = $forminput['fullname'];
            $user->user_gender      = isset($forminput['gender'])?$forminput['gender']:'';
            $user->user_address      = isset($forminput['address'])?$forminput['address']:'';
            
            $user->works_at             = $forminput['works_at'];
            $user->study_at             = $forminput['study_at'];
            $user->relation_status             = $forminput['relation_status'];
            $user->languages_known             = $forminput['languages_known'];
            $user->bio             = $forminput['bio'];

            $user->user_mob         = isset($forminput['mobile'])?$forminput['mobile']:$user->user_mob;
            $user->dob    = $forminput['date_of_birth'];
            
            if(!empty($name)){
                $user->profile_image  = $name;
            }

            if(!empty($cover_name)){
                $user->cover_image  = $cover_name;
            }

            if($user->save()){

                $img = '';
                if(!empty($user->profile_image)){
                    $img = url('public/uploads/profile_image').'/'.$user->profile_image;
                }else{
                    $img = url('resources/assets/images/blank_user.jpg');
                }

                $cover_img = '';
                if(!empty($user->cover_image)){
                    $cover_img = url('public/uploads/cover_image').'/'.$user->profile_image;
                }else{
                    $cover_img = url('resources/assets/images/blank_image.jpg');
                }

                return response()->json(
                    [
                        'status'=>$this->successStatus, 
                        'msg' => 'Your profile updated successfully', 
                        'response'=> [
                            'user_id'    =>  $user->id, 
                            'username' => $user->username,
                            'fullname' => $user->fullname, 
                            'email' => $user->email, 
                            'mobile' => $user->user_mob,
                            'profile_image' => $img,
                            'cover_image' => $cover_img, 
                            'gender' => $user->user_gender,   
                            'date_of_birth' => $user->dob,
                            'address' => $user->user_address, 
                            'email_verified' => $user->email_verified,
                            'user_status' => $user->user_status,

                            'works_at'   => $user->works_at,
                            'study_at'   => $user->study_at,
                            'relation_status' => $user->relation_status,
                            'languages_known' => $user->languages_known,
                            'bio'             => $user->bio

                        ]
                    ]                    
                ); 
            }else{
                return response()->json(['status'=>$this->failureStatus, 'msg' => 'Please try again later']);
            }
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Your account is not active']);
        }
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getuserdetails(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required' 
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }
        }
        $user = User::where('id', $request->user_id)->where('user_status', 1)->first();
        if(!empty($user) ){
            $img = '';
            if(!empty($user->profile_image)){
                $img = url('public/uploads/profile_image').'/'.$user->profile_image;
            }else{
                $img = url('resources/assets/images/blank_user.jpg');
            }

            $cover_img = '';
            if(!empty($user->cover_image)){
                $cover_img = url('public/uploads/cover_image').'/'.$user->profile_image;
            }else{
                $cover_img = url('resources/assets/images/blank_image.jpg');
            }

            $detail = array(
                'user_id'           => $user->id,
                'username'          => $user->username,
                'fullname'          => $user->fullname,
                'email'             => $user->email,
                'mobile'            => $user->user_mob,
                'gender'            => $user->user_gender,
                'profile_image'     => $img,
                'cover_image'       => $cover_img,
                'works_at'   => $user->works_at,
                'study_at'   => $user->study_at,
                'relation_status' => $user->relation_status,
                'languages_known' => $user->languages_known,
                'bio'             => $user->bio, 
                'date_of_birth'     => $user->dob,
                'email_verified'   => $user->email_verified,
                'user_status'       => $user->user_status,
                'oauth_provider'   => $user->oauth_provider,
                'devicetype'       => $user->devicetype,
                'deviceid'       => $user->deviceid,
                'api_token'      => $user->api_token,

            );
            return response()->json([
                'status'=>$this->successStatus, 
                'msg' => 'User details fetched successfully', 
                'response' => $detail
            ]);

        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Your Account does not exist']);
        }
    }

    /** 
    * blockuser api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function blockuser(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required',
                'block_user_id'  => 'required',
                'block_status'  => 'required'
            ],
            [   
                'user_id.required'     => 'required user_id',
                'block_user_id.required'     => 'required block_user_id',
                'block_status.required'     => 'required block_status',

            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $block_user_id  = $forminput["block_user_id"];
        $user_id        = $forminput["user_id"];

        if( $forminput['block_status'] == 1){

            $select_query=DB::select("SELECT count(*) as count from block_user where block_user_id='$block_user_id' AND user_id='$user_id'");

            if( $select_query[0]->count > 0 ){
                $block = DB::table('block_user')->where('user_id',$forminput['user_id'] )->where( 'block_user_id',$forminput['block_user_id'] )->update( [ 'blockedDate' =>date('Y-m-d'),
                          'block_status' => 1, 'unblockedDate' => null ] );
            }else{
                $block = DB::table('block_user')->insert(
                            [
                              'user_id' => $user_id,
                              'block_user_id' => $block_user_id,
                              'blockedDate' =>date('Y-m-d'),
                              'block_status' => 1
                            ]
                );
            }
            $msg = 'User blocked successfully';

        }else{
            $block = DB::table('block_user')->where('user_id',$forminput['user_id'] )->where( 'block_user_id',$forminput['block_user_id'] )->update( [ 'unblockedDate' =>date('Y-m-d'),
                          'block_status' => 0, 'blockedDate' => null ] );
             $msg = 'User unblocked successfully';
        }

        if( $block ){
            return response()->json(['status'=>$this->successStatus, 'msg' => $msg ]);
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }     
}