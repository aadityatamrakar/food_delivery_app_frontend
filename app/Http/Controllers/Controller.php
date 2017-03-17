<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * @param $mobile 10 Digit Mobile No
     * @param $message 160 Char Message
     * @return string
     */
    public function SendSMS($mobile, $message)
    {
        $message = urlencode($message);
        $mobile  = urlencode($mobile);
        $authkey = urlencode('144918AqxgTzQNGV58c81400');
        $senderid = urlencode('TRMBOY');

        return file_get_contents("https://control.msg91.com/api/sendhttp.php?authkey=$authkey&mobiles=$mobile&message=$message&sender=$senderid&route=4&country=91");
        //return file_get_contents("http://sms.hostingfever.in/sendSMS?username=spantech&message=$message&sendername=ONLINE&smstype=TRANS&numbers=$mobile&apikey=4d360261-78da-4d98-826c-d02a6771545c");
        //return 'hello';
    }

    public function short_url($longUrl)
    {
        $username = 'admin';
        $password = 'Password@123';
        $format  = 'simple';
        $api_url = 'http://t3b.in/yourls-api.php';
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $api_url );
        curl_setopt( $ch, CURLOPT_HEADER, 0 );            // No header in the result
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Return, do not echo result
        curl_setopt( $ch, CURLOPT_POST, 1 );              // This is a POST request
        curl_setopt( $ch, CURLOPT_POSTFIELDS, array(      // Data to POST
            'url'      => $longUrl,
            'format'   => $format,
            'action'   => 'shorturl',
            'username' => $username,
            'password' => $password
        ) );
        $data = curl_exec($ch);
        curl_close($ch);
        return (object) ['id'=>str_replace('http://', '', $data)];

//        $apiKey = 'AIzaSyDqKxkz7UElmCzjs-2SnsC5rVYgbpmsP5I';
//        $postData = array('longUrl' => $longUrl, 'key' => $apiKey);
//        $curlObj = curl_init();
//        curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
//        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($curlObj, CURLOPT_HEADER, 0);
//        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
//        curl_setopt($curlObj, CURLOPT_POST, 1);
//        curl_setopt($curlObj, CURLOPT_POSTFIELDS, json_encode($postData));
//        $json = json_decode(curl_exec($curlObj));
//        curl_close($curlObj);
//        return $json;
    }

    public function callr($mobile, $message)
    {
        $login = 'spantechnologies_1';
        $password = 'wVAZcLFbFZ';

        $api = new \CALLR\API\Client();
        $api->setAuthCredentials($login, $password);

        $target = new \stdClass();
        $target->number = '+91'.$mobile;
        $target->timeout = 30;

        $messages = ['TTS|TTS_EN-GB_SERENA|'.urldecode($message)];

        $options = new \stdClass();
        $options->cdr_field = 'userData';
        $options->cli = 'BLOCKED';
        $options->loop = 1;

        $result = $api->call('calls.broadcast_1', [$target, $messages, $options]);
    }

}
