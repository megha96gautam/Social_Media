<?php 
namespace App\Http\Middleware;  
  
use Closure;  
use Illuminate\Contracts\Auth\Guard;  
use Response;  
  
class checkHeader  
{  
    /** 
     * The Guard implementation. 
     * 
     * @var Guard 
     */  
  
    /** 
     * Handle an incoming request. 
     * 
     * @param  \Illuminate\Http\Request  $request 
     * @param  \Closure  $next 
     * @return mixed 
     */  
    public function handle($request, Closure $next)  
    {  
        
       /* if((empty($request->header('Content-Type'))) && (empty($request->header('Votive')))){  
            return Response::json(array('error'=>'Please set custom header'));  
        } */ 
  
        if($request->header('Votive') != '123456'){  
            return Response::json(array('error'=>'wrong custom header'));  
        }  
  
        return $next($request);  
    }  
} 