<?php require_once('./vendor/autoload.php'); 
 
// Namespace 
use \LINE\LINEBot\HTTPClient\CurlHTTPClient; 
use \LINE\LINEBot; 
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder; 
 
$channel_token = '7Ief0P/yJzty4DMy+Qw0ybqIQEeT+w38s+iQ+Cf8btBiFQyElMd7a0sKD8JLbsv1RIO0XshvZ44EgTXuk/w31V1THkqSpQtdq7+SurKEK4u6SXX1E4ogT6dt6QcT5BtfXODNoIJuPhtpMLZdGZOGhwdB04t89/1O/w1cDnyilFU='; 
$channel_secret = 'e44033a80aed821ccecff6ab0df784d4'; 
 
// Get message from Line API 
$content = file_get_contents('php://input'); $events = json_decode($content, true); 
 
if (!is_null($events['events'])) {     // Loop through each event     
    foreach ($events['events'] as $event) {         
        //  Line API send a lot of event type, we interested in message only.         
        if ($event['type'] == 'message') {                
            switch($event['message']['type']) {                  
                case 'text':                       
                // Get replyToken                       
                $replyToken = $event['replyToken']; 
 
                // Reply message                       
                 $respMessage = 'Hello, your message is '. $event['message']['text']; 
                 
                 $httpClient = new CurlHTTPClient($channel_token);                      
                 $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));                       
                 $textMessageBuilder = new TextMessageBuilder($respMessage);                       
                 $response = $bot->replyMessage($replyToken, $textMessageBuilder); 
                 break; 
            } 
        } 
    } 
} 
 
echo "OK"; 
