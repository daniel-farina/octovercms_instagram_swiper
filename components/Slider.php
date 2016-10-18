<?php namespace DanielFarina\Instagram\Components;
use DanielFarina\Instagram\Models\InstagramSettings;
use Cms\Classes\ComponentBase;
use Instaphp;
use \Cache;
use \Carbon\Carbon;


class Slider extends ComponentBase
{
    use \danielfarina\Instagram\Classes\MakeKeyTrait;
    public $media;
    public $errorOccurred;



    public function componentDetails()
    {
        return [
            'name'        => 'Instagram Slider',
            'description' => 'Shows the latest Instagram pictures for a specified user.'
        ];
    }

    public function defineProperties()
    {

        $settings = InstagramSettings::instance();

        return [
            'access_token' => [
                'title'             => 'Access Token',
                'description'       => 'Restrict returned media by the specified user.',
                'default'           =>  $settings->accessToken,
                'type'              => 'string',
                'validationPattern' => '^(?=\s*\S).*$',
                'validationMessage' => 'The User Name property is required'
            ],
            'user_id' => [
                'title'             => 'User Id',
                'description'       => 'Restrict returned media by the specified user.',
                'default'           =>  $settings->userid, //4003175709
                'type'              => 'string',
                'validationPattern' => '^(?=\s*\S).*$',
                'validationMessage' => 'The User Name property is required'
            ],
            'limit' => [
                'title'             => 'Limit',
                'description'       => 'The number of media to be displayed (20 maximum).',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]*$',
                'validationMessage' => 'The Limit property should be numeric'
            ],
            'cache' => [
                'title'             => 'Cache',
                'description'       => 'The number of minutes to cache the media.',
                'default'           => $settings->cache_minutes,
                'type'              => 'string',
                'validationPattern' => '^[0-9]*$',
                'validationMessage' => 'The Cache property should be numeric'
            ]
        ];
    }



    // Gets our data
    public function fetchData($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);


        return $result;
    }



    public function photos()
    {

        $this->postPage = $this->page['postPage'] = $this->property('postPage');


        // Pulls and parses data.
        $result = $this->fetchData("https://api.instagram.com/v1/users/".$this->property('user_id')."/media/recent/?access_token=".$this->property('access_token')."");
        $result = json_decode($result);

        print_r($result);

        //Check no errors were returned or empty object
        if(!empty($result->meta->error_type) || empty($result)){
            $this->errorOccurred = true;
            return false;
        }

        $this->media = $result->data;
        return $result->data;

    }


    public function preCheck()
    {
        //Make sure userid and toekn are present
        if(empty($this->property('user_id')) || empty($this->property('access_token')))
        {
            $this->errorOccurred = true;
        }
        //Check for other errors
        if($this->errorOccurred == true)
        {
           return  true;
        }else {
            return false;
        }
    }



    public function onRun()
    {

            //add required js & css
            $this->addJs('/plugins/danielfarina/instagram/assets/js/swiper.min.js');
            $this->addCss('/plugins/danielfarina/instagram/assets/css/swiper.min.css');


            $key = $this->makeKey();

            if ($this->errorOccurred == false && Cache::has($key))
            {
                $this->media = $this->page['media'] = Cache::get($key);

            }
            else {
                $expires_at = Carbon::now()->addMinutes($this->property('cache'));
                Cache::put($key, $this->photos(), $expires_at);
            }




        // Supply a user id and an access token


/*
  foreach ($result->data as $post) {
    // Do something with this data.
  }

   foreach ($result->data as $post):
      
        <a class="group" rel="group1" href="<?= $post->images->standard_resolution->url ?>"><img src="<?= $post->images->thumbnail->url ?>"></a>
   endforeach

   */

//print_r($result);

    // $api = new Instaphp\Instaphp([
    //     'client_id' => '29d25248e3794420926a9971d10fffc8',
    //     'client_secret' => '3d223a20ea3f48f2ad2be8d9e5f6b0c4',
    //     'redirect_uri' => 'http://danielfarina.com/blog',
    //     'scope' => 'comments'
    // ]);

    // $popular = $api->Media->Popular(['count' => 1]);

    // if (empty($popular->error)) {
    //     foreach ($popular->data as $item) {
    //         printf('<img src="%s">', $item['images']['low_resolution']['url']);
    //     }
    // }


//  $clientId = "29d25248e3794420926a9971d10fffc8";
//  $clientSecret = "3d223a20ea3f48f2ad2be8d9e5f6b0c4";
//  $redirectUrl = "http://danielfarina.com/blog";

//  $client = new Client($clientId, $clientSecret, null, $redirectUrl);
// // If we don't have an authorization code then get one
// if (!isset($_GET['code'])) {
   
//     header('Location: ' . $client->getLoginUrl());
//     exit;
// } else {

//      $token = $client->getAccessToken($_GET['code']);

// print_r($token);
// }


// $client = new Client($clientId, $clientSecret, null, $redirectUrl);
// $client->setAccessToken($token);
// // $search = $client->users()->search('dnlfarina');
// // print_r($search);

// $response = $client->users()->getMedia('4003175709');
// $media = json_encode($response->get());
// $media = json_decode($media);

// print_r($media);

//  $client = new Client($clientId, $clientSecret, "4003175709.29d2524.dffdcdde18844373b80880d67f25ea88", $redirectUrl);

// $response = $client->users()->find('dnlfarina');
// echo json_encode($response->get());

// You can now make requests to the API
//$client->users()->search('skrawg');


// $response = $client->request('GET', 'users/self');
// echo json_encode($response->get());




    // $instagram = new Andreyco\Instagram\Client(array(
    //   'apiKey'      => '29d25248e3794420926a9971d10fffc8',
    //   'apiSecret'   => '4003175709.29d2524.dffdcdde18844373b80880d67f25ea88',
    //   'apiCallback' => 'http://danielfarina.com/blog',
    //   'scope'       => array('basic'),
    // ));


    // echo "aha <a href='{$instagram->getLoginUrl()}'>Login with Instagram</a>";



//     // Initialize class
// $instagram = new Instagram(array(
//   'apiKey'      => '29d25248e3794420926a9971d10fffc8',
//   'apiSecret'   => '4003175709.29d2524.dffdcdde18844373b80880d67f25ea88',
//   'apiCallback' => 'http://danielfarina.com'
// ));
// // Receive OAuth code parameter
// $code = $_GET['code'];

// if (true === isset($code)) {
//   // Receive OAuth token object
//   $data = $instagram->getOAuthToken($code);
//   // Store user access token
//   $instagram->setAccessToken($data);
//   // Now you can call all authenticated user methods
//   // Get the most recent media published by a user
//   $media = $instagram->getUserMedia();
//   foreach ($media->data as $entry) {
//     echo "<img src=\"{$entry->images->thumbnail->url}\">";
//   }
// }




/* https://www.instagram.com/oauth/authorize/?client_id=29d25248e3794420926a9971d10fffc8&redirect_uri=http://danielfarina.com&response_type=token&scope=public_content

*/

        // This code will be executed when the page or layout is
        // loaded and the component is attached to it.

       // $this->page['var'] = 'value'; // Inject some variable to the page

//        function onRun($response) {
//            $search = array(
//
//                '/\>[^\S]+/s',  // strip whitespaces after tags, except space
//
//                '/[^\S]+\</s',  // strip whitespaces before tags, except space
//
//                '/(\s)+/s'       // shorten multiple whitespace sequences
//            );
//
//            $replace = array( '>','<', '\\1' );
//            $response = preg_replace($search, $replace, $response);
//            return $response;
//        }
//
//        ob_start("sanitize_output");

//        if($_SERVER['REMOTE_ADDR'] == "108.48.105.26"){
//
//            echo "..";
//            dump($this);
//        }

    }

}