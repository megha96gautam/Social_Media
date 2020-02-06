<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\User;
use Illuminate\Support\Facades\Auth; 
use Validator, DB;
use Illuminate\Validation\Rule;
use Twilio\Rest\Client;
use Session;

class DashboardController extends Controller 
{
	public function index() {
		return view('admin/dashboard');
	}

	public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
?>