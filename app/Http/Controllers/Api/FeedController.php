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
use Illuminate\Database\QueryException;

class FeedController extends Controller 
{
public $successStatus = true;
public $failureStatus = false;
    
    /** 
    * create feed api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function createfeed(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required',
                'images.*' => 'mimes:jpeg,png,jpg,gif,svg|max:5000',
                'videos.*'  => 'mimes:mp4,3gp,avi,mov,qt'
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

        // if($videos=$request->file('videos')){
        //     foreach ($videos as $video)
        //     {   
        //         $mime = $video->getMimeType();
        //         echo 'mime='.$mime;die;
        //         return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
        //     }            
        // }

        DB::table('posts')->insert(
                [
                  'user_id' =>  $forminput['user_id'],
                  'post_content' => !empty($forminput['text'])? $forminput['text']:'',
                  'post_date' =>  NOW(),
                  'post_status_id' => 1,
                  'post_like_count' => 0,
                  'post_comment_count' => 0,
                ]
        );

        $post_id = DB::getpdo()->lastInsertId();
        
        $media_json = array();
        
        $images=array();
        $json_data = array();
        $product = $request->all();
        $files=$request->file('images');
        $check = true;
        if($files=$request->file('images')){
            foreach($files as $file){

                $name = time().'_'.$file->getClientOriginalName();

                $destinationPath = public_path('/uploads/feed_images');
                $imagePath = $destinationPath. '/'.  $name;

                $jsonPath = url('/public/uploads/feed_images'). '/'.  $name;
                $file->move( $destinationPath,$imagePath );
                $images[]=$name;
                $data[] = [
                        'post_id'=>$post_id, 'media_type'=> 'image', 
                        'media_url' => $name 
                ];
                $jsonPath = preg_replace("/^http:/i", "https:", $jsonPath);

                $json_data[] = [
                        'type'=> 'image', 
                        'url' => $jsonPath 
                ];
            }
            $check = DB::table('post_media')->insert($data);
        }

        $videos=array();
        $product = $request->all();
        $files=$request->file('videos');
        if($files=$request->file('videos')){
            foreach($files as $file){

                $name = time().'_'.$file->getClientOriginalName();

                $destinationPath = public_path('/uploads/feed_videos');
                $videoPath = $destinationPath. '/'.  $name;
                $jsonPath = url('/public/uploads/feed_videos'). '/'.  $name;
                $file->move( $destinationPath,$videoPath );
                $videos[]=$name;
                $videodata[] = [
                    'post_id'=>$post_id, 'media_type'=> 'video', 
                    'media_url' => $name
                ];
                $jsonPath = preg_replace("/^http:/i", "https:", $jsonPath);
                $json_data[] = [
                    'type'=> 'video', 
                    'url' => $jsonPath 
                ];
            }
            $check = DB::table('post_media')->insert($videodata);
        }

        $media_json = array();
        
        if( sizeof($json_data) ){
            $media_json = json_encode($json_data); 
        }
        $check = DB::table('posts')->where('user_id',$forminput['user_id'] )->where( 'post_id',$post_id )->update( [ 'post_media_json' => $media_json ] );


        $follow = new FollowController;
        $user_ids = array();

        $user_ids =  $follow->getFollowersByUserId( $forminput['user_id'] , $user_ids );
        $user_ids =  $follow->getFollowingsByUserId( $forminput['user_id'] , $user_ids );

        $user_ids = array_unique($user_ids);

        if( sizeof($user_ids) ){
            $send_to =  implode(",",$user_ids);
        }

        if( !empty($send_to) ){
            DB::table('notifications')->insert(
                [
                  'type' =>  'post',
                  'type_id' => $post_id,
                  'status' =>  1,
                  'send_by' => $forminput['user_id'],
                  'send_to' => $send_to,
                ]
            );
        }

        $noti_id = DB::getPdo()->lastInsertId();
        foreach( $user_ids as $user_id){
            DB::table('notifications_status')->insert(
            [
                  'notification_id' =>  $noti_id,
                  'user_id' => $user_id
                ]
            );
        }

        if( $check ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Feed posted successfully', 'response'=>['user_id' => $request->user_id, 'post_id' => $post_id  ]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }

    /** 
    * edit feed text api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function editfeedtext(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required',
                'post_id'  => 'required',
                'text'     => 'required'
            ],
            [   
                'user_id.required'     => 'required user_id',
                'post_id.required'     => 'required post_id',
                'text.required'        => 'required text',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $check = DB::table('posts')->where('user_id',$forminput['user_id'] )->where( 'post_id',$forminput['post_id'] )->update( [ 'post_content' => $forminput['text'], 'post_date' => NOW() ] );

        if( $check ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Feed edited successfully', 'response'=>['user_id' => $request->user_id, 'post_id' => $forminput['post_id']  ]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }

    /** 
    * get user feed list api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function getUserFeedlist(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required',
            ],
            [   
                'user_id.required' => 'required user_id',
            ]
        );

        $feedList =  DB::table('posts')
            ->select('users.id', 'users.fullname', 'users.profile_image', 'users.cover_image', 'posts.post_content',  'posts.post_id', 'posts.post_media_json')
            ->join('users','users.id','=','posts.user_id')
            ->where('posts.user_id', $forminput['user_id'])
            ->where('posts.post_status_id', 1)
            ->where('users.user_status', 1)
            ->orderBy('posts.post_id', 'DESC')
            ->get();

        $result = array();
        $results = array();
        foreach( $feedList as $key => $feed ){

            $result['name'] =  $feed->fullname;
            $result['imageUrl'] =  $feed->profile_image;
            $result['content'] = json_decode( $feed->post_media_json );
            $result['text'] =  !empty($feed->post_content)? $feed->post_content:'' ;
            $results[] =  $result;
        }

        if( sizeof($feedList) ){
            return response()->json(['status'=>$this->successStatus, 
                'msg' => 'User feed list successfully',
                'response'=> ['userDetails' => array($results)]
         ]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No feeds found']); 
        }
    }

    /** 
    * get follow feed list api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function getFollowFeedlist(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required',
            ],
            [   
                'user_id.required' => 'required user_id',
            ]
        );

        $follow = new FollowController;

        $user_ids = array();
        $user_ids[] = $forminput['user_id'];

        $user_ids =  $follow->getFollowersByUserId( $forminput['user_id'] , $user_ids );

        $user_ids =  $follow->getFollowingsByUserId( $forminput['user_id'] , $user_ids );

        $user_ids = array_unique($user_ids);

        $feedList =  DB::table('posts')
            ->select('users.id', 'users.fullname', 'users.profile_image', 'users.cover_image', 'posts.post_content',  'posts.post_id', 'posts.post_media_json')
            ->join('users','users.id','=','posts.user_id')            
            ->whereIn('posts.user_id', $user_ids)
            ->where('posts.post_status_id', 1)
            ->where('users.user_status', 1)
            ->orderBy('posts.post_id', 'DESC')
            ->get();

        $result = array();
        $results = array();
        foreach( $feedList as $key => $feed ){

            //$result['id'] =  $feed->id;
            $result['name'] =  $feed->fullname;
            $result['imageUrl'] =  $feed->profile_image;
            $result['content'] = json_decode( $feed->post_media_json );
            $result['text'] =  !empty($feed->post_content)? $feed->post_content:'' ;
            $results[] =  $result;
        }

        if( sizeof($feedList) ){
            return response()->json(['status'=>$this->successStatus, 
                'msg' => 'User feed list successfully',
                'response'=> ['userDetails' => array($results)]
         ]);
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'No feeds found']); 
        }
    }

    /** 
    * delete feed api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function deletefeed(Request $request){
        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'user_id'  => 'required',
                'post_id'  => 'required',
            ],
            [   
                'user_id.required'     => 'required user_id',
                'post_id.required'     => 'required post_id',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $posts = DB::table('post_media')->where('post_id',$forminput['post_id'] )->get();

        $feed_images_path = public_path('/uploads/feed_images/');
        $feed_videos_path = public_path('/uploads/feed_videos/');
        
        $check = DB::table('posts')->where('user_id',$forminput['user_id'] )->where( 'post_id',$forminput['post_id'] )->delete();

        if( $check ){
            DB::table('post_media')->where( 'post_id',$forminput['post_id'] )->delete();

            foreach( $posts as $post ){
                //$value = substr(strrchr(rtrim($post->media_url, '/'), '/'), 1);

                if( isset($post->media_type) && $post->media_type == 'image'){
                    unlink($feed_images_path.'/'.$post->media_url);
                }
                if( isset($post->media_type) && $post->media_type == 'video'){
                    unlink($feed_videos_path.'/'.$post->media_url);
                }
            }

            return response()->json(['status'=>$this->successStatus, 'msg' => 'Feed deleted successfully', 'response'=>['user_id' => $request->user_id, 'post_id' => $forminput['post_id']  ]]);
            
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }

    /** 
    * get image gallarey api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function getImageGallarey(Request $request){

        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'page'  => 'required',
                'user_id'  => 'required',
            ],
            [   
                'page.required'     	=> 'required page',
                'user_id.required'      => 'required user_id',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $get =  DB::table('post_media')
        ->select('post_media.media_url')
        ->join('posts','posts.post_id','=','post_media.post_id')
        ->where('post_media.media_type', 'image')
        ->where('posts.user_id', $request->user_id)
        ->get();
        
        $i = count($get);
        $page = $request->page;
        $rec_limit = 10;

        if (!empty($page)) {
           $page   = $page;
           $offset = $rec_limit * $page;
        } else {
           $page   = 0;
           $offset = 0;
        }

        $left_rec    = $i - ($page * $rec_limit);
        $pages       = ceil($i / $rec_limit);

        $total_pages = $pages - 1;
        $total_pages=($total_pages>0)? $total_pages:0;

        $images =  DB::table('post_media')
        ->select('post_media.media_url')
        ->join('posts','posts.post_id','=','post_media.post_id')
        ->where('post_media.media_type', 'image')
        ->where('posts.user_id', $request->user_id)
        ->offset($offset)->limit($rec_limit)->get();

        if( sizeof($images) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Image gallery successfully', 'response'=>['images' => $images, 'total_pages' => $total_pages, 'total_records' => $i, "pages_limit" => $rec_limit ]]);
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }


    /** 
    * get video gallarey api 
    * 
    * @return \Illuminate\Http\Response 
    */ 
    public function getVideoGallarey(Request $request){

        $forminput =  $request->all();
        $validator = Validator::make($request->all(), [ 
                'page'  => 'required',
                'user_id'  => 'required',
            ],
            [   
                'page.required'     	=> 'required page',
                'user_id.required'      => 'required user_id',
            ]
        );
        
        if ($validator->fails()) { 
            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {   
                return response()->json(['status'=>$this->failureStatus,'msg'=>$message]);            
            }            
        }

        $get =  DB::table('post_media')
        ->select('post_media.media_url')
        ->join('posts','posts.post_id','=','post_media.post_id')
        ->where('post_media.media_type', 'video')
        ->where('posts.user_id', $request->user_id)
        ->get();
        
        $i = count($get);
        $page = $request->page;
        $rec_limit = 10;

        if (!empty($page)) {
           $page   = $page;
           $offset = $rec_limit * $page;
        } else {
           $page   = 0;
           $offset = 0;
        }

        $left_rec    = $i - ($page * $rec_limit);
        $pages       = ceil($i / $rec_limit);

        $total_pages = $pages - 1;
        $total_pages=($total_pages>0)? $total_pages:0;

        $videos =  DB::table('post_media')
        ->select('post_media.media_url')
        ->join('posts','posts.post_id','=','post_media.post_id')
        ->where('post_media.media_type', 'video')
        ->where('posts.user_id', $request->user_id)
        ->offset($offset)->limit($rec_limit)->get();

        if( sizeof($videos) ){
            return response()->json(['status'=>$this->successStatus, 'msg' => 'Video gallery successfully', 'response'=>['videos' => $videos, 'total_pages' => $total_pages, 'total_records' => $i, "pages_limit" => $rec_limit ]]);
        }else{
            return response()->json(['status'=>$this->failureStatus, 'msg' => 'Something went wrong']); 
        }
    }       
}