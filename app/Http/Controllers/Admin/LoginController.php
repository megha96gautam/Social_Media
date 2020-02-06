<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator, DB, Mail;
use Illuminate\Validation\Rule;
use Twilio\Rest\Client;
use Session;
use App\Helpers\Helper;

class LoginController extends Controller 
{
	public function index() {
		return view('admin/login');
	}

	public function submit_login(Request $request) {
		$request->email;
		$request->password;

		$validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return redirect('/login')
            ->withErrors($validator)
            ->withInput();
        } else {

            $inputVal = $request->all(); 

            $credentials = array(
                'email' => $inputVal['email'],
                'password' => $inputVal['password'],
                'user_status' => 1
            ); 
            
            if (Auth::attempt($credentials)){    
                if (Auth::user()->is_admin == 1) {
                    session::flash('message', 'Loggedin successfully.' );
                    return redirect('/admin/dashboard');  
                } else {
                    Auth::logout();
                    session::flash('error', 'Your have no permission to access in this section.');
                    return redirect('/login');
                }   
                
            }else{ 
                $user = DB::table('users')->where('email',$inputVal['email'])->first();
                if(!empty($user)){
                    if ($user->user_status != 1) {
                        Auth::logout();
                        session::flash('error', 'Your acount is inactive.');
                        return redirect('/login');
                    } else {  
                        Auth::logout();
                        session::flash('error', 'Email or Password is incorrect.' );
                        return redirect('/login');
                        
                    } 
                }else{  
                    session::flash('error', 'Your account does not exist.' );
                    return redirect('/login');
                }  
            }
        }
	}

    public function forgot_password(Request $request) {
        return view('admin/forgot_password');
    }

    public function forgot_password_submit(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/forgot_password')
            ->withErrors($validator)
            ->withInput();
        } else {
            $user = DB::table('users')->where('email',$request->email)->first();
            if(!empty($user->id)){
                $user_id = $user->id;
                $vrfn_code = Helper::generateRandomString(6);

                $obj_user = User::find($user_id);
                $obj_user->password = bcrypt($vrfn_code);
                $res = $obj_user->save();
                if ($res) {
                    $data['url'] = url('/');
                    $data['email'] = $user->email;
                    $data['password'] = $vrfn_code;
                    $data['fullname'] = $user->fullname;

                    $inData['email'] = $user->email;

                    if ($_SERVER['SERVER_NAME'] != 'localhost') {
                        $fromEmail = Helper::getFromEmail();
                        $inData['from_email']     =  $fromEmail;
                        Mail::send('emails.forgot_password',$data, function ($message) use ($inData) {
                            $message->from($inData['from_email'],'Social Networking App');
                            $message->to($inData['email']);
                            $message->subject('Social Networking App - Forgot Password');
                        });
                    }
                    session::flash('message', 'New password is sended in your registered email address. Please check it.');
                    return redirect('/forgot_password');
                } else {
                    session::flash('error', 'Some internal issue occured. Please check and try again.' );
                    return redirect('/forgot_password');
                }
            } else {
                session::flash('error', 'Email not registered.');
                return redirect('/forgot_password');
            }
        }
    }
}
?>