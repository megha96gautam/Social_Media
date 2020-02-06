<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function getFromEmail()
    {
    	if ($_SERVER['SERVER_NAME'] == 'votivelaravel.in') {
    		    return 'votivephp.lokesh@gmail.com';
            //return 'testing@votivelaravel.in';
        } elseif($_SERVER['SERVER_NAME'] == 'socialnetworkingapp.com') {
          return 'info@socialnetworkingapp.com';
      } else {
          return 'votivephp.lokesh@gmail.com';
      }
    }

    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}