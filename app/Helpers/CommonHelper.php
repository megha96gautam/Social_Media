<?php
//use App\User_notification;
use App\User;
use App\Site_lang_text;
use Twilio\Rest\Client;
use GuzzleHttp\Client as GuzzleClient;
/*************Function to create unique alphanumeric user name *************/

/*************Function to Send mail *************/
function send_mail($data){
    Mail::send('sendmail', $data, function($message)  use ($data){
    $message->from('votivephp.lokesh@gmail.com', 'Social Networking Site');
    $message->to($data['email'])
    ->subject($data['subject']);
    });
}
?>