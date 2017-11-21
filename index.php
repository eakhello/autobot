<?php require_once('./vendor/autoload.php'); 
 
// Namespace 
use \LINE\LINEBot\HTTPClient\CurlHTTPClient; 
use \LINE\LINEBot; 
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder; 
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
 
$channel_token = '7Ief0P/yJzty4DMy+Qw0ybqIQEeT+w38s+iQ+Cf8btBiFQyElMd7a0sKD8JLbsv1RIO0XshvZ44EgTXuk/w31V1THkqSpQtdq7+SurKEK4u6SXX1E4ogT6dt6QcT5BtfXODNoIJuPhtpMLZdGZOGhwdB04t89/1O/w1cDnyilFU='; 
$channel_secret = 'e44033a80aed821ccecff6ab0df784d4'; 
 
// Get message from Line API 
$content = file_get_contents('php://input'); 
$events = json_decode($content, true); 
 
if (!is_null($events['events'])) {     // Loop through each event     
    foreach ($events['events'] as $event) {         
        //  Line API send a lot of event type, we interested in message only.         
        if ($event['type'] == 'message') {   
           
            // Get replyToken                       
            $replyToken = $event['replyToken'];  
            $ask = $event['message']['text']; 

            switch(strtolower($ask)) {             
                case 'm':
                $typeresponse = 'txt';                 
                $respMessage = 'What sup man. Go away!'; 
                break;             
                case 'f':
                $typeresponse = 'txt';                 
                $respMessage = 'Love you lady.'; 
                break;             
                case 'img':
                $typeresponse = 'img';
                $originalContentUrl = 'https://olymptrade-promo.com/yahoo-news/v/th/17kapook2/images/6.jpg'; 
                $previewImageUrl =  'https://olymptrade-promo.com/yahoo-news/v/th/17kapook2/images/6.jpg';              
                break;  

                default: 
                $typeresponse = 'txt';                
                $respMessage = 'What is your sex? M or F'; 
                break; 
        } 

            // switch($event['message']['type']) {                  
            //     case 'text':                       
            //     // Reply message                       
            //      $respMessage = 'Hello, your message is '. $event['message']['text']; 
            //      break; 
                 
            //      case 'image':                     
            //      $messageID = $event['message']['id'];                     
            //      $respMessage = 'Hello, your image ID is '. $messageID; 
            //      break; 

            //      case 'sticker':                     
            //      $messageID = $event['message']['packageId'];                  
            //      $respMessage = 'Hello, your Sticker Package ID is '. $messageID; 
            //      break; 

            //      case 'video':                     
            //       $messageID = $event['message']['id'];                   
            //       $fileID = $event['message']['id'];                     
            //       $response = $bot->getMessageContent($fileID);                     
            //       $fileName = 'linebot.mp4';                     
            //       $file = fopen($fileName, 'w');                     
            //       fwrite($file, $response->getRawBody()); 
            //       $respMessage = 'Hello, your video ID is '. $messageID; 
            //       break; 

            //       case 'audio':                     
            //        $messageID = $event['message']['id']; 
            //          // Create audio file on server.                     
            //         $fileID = $event['message']['id'];                     
            //         $response = $bot->getMessageContent($fileID);                     
            //         $fileName = 'linebot.m4a';                     
            //         $file = fopen($fileName, 'w');                     
            //         fwrite($file, $response->getRawBody());                  
            //         $respMessage = 'Hello, your audio ID is '. $messageID; 

            //         break;

            //         case 'location':                     
            //          $address = $event['message']['address'];                    
            //          $respMessage = 'Hello, your address is '. $address; 
            //         break;

            //      default:                     
            //       $respMessage = 'Please send xxx only'; 
            //      break; 

            // }
            
            $httpClient = new CurlHTTPClient($channel_token);                      
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
            
            if ($typeresponse == 'txt'){
                $textMessageBuilder = new TextMessageBuilder($event['message']['type'].' - '. $respMessage);                          
            }
            elseif ($typeresponse == 'img'){
                $textMessageBuilder = new ImageMessageBuilder($originalContentUrl, $previewImageUrl);
            }
            
            $response = $bot->replyMessage($replyToken, $textMessageBuilder) ;
           
        } 
    } 
} 
 
echo "OK"; 
