<?php 
require_once('./vendor/autoload.php'); 

// Namespace 
use \LINE\LINEBot\HTTPClient\CurlHTTPClient; 
use \LINE\LINEBot; 
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder; 
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
$OnSystem = 'on';
$channel_token = '7Ief0P/yJzty4DMy+Qw0ybqIQEeT+w38s+iQ+Cf8btBiFQyElMd7a0sKD8JLbsv1RIO0XshvZ44EgTXuk/w31V1THkqSpQtdq7+SurKEK4u6SXX1E4ogT6dt6QcT5BtfXODNoIJuPhtpMLZdGZOGhwdB04t89/1O/w1cDnyilFU='; 
$channel_secret = 'e44033a80aed821ccecff6ab0df784d4'; 
 
$host = 'ec2-50-16-228-232.compute-1.amazonaws.com';
$dbname = 'd6cm71n101ita9'; 
$user = 'dvyuqfuldvzebl';
$pass = '4c662a23211ff72c4b3eff5e78729e8e3c9e78956e22806f4e65a989fa386306';
$connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 

$sql = sprintf(
    "SELECT active FROM flagactive");

   $result = $connection->query($sql);

if($result !== false && $result->rowCount() >0) {
    foreach ($result as $row) {
     $OnSystem = $row['active'];
    }
}


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
            $appointments = explode(',', $event['message']['text']) ;
            
            if (($OnSystem == 'open') || (strtolower($ask) == 'open'))
            {
                if ($event['message']['type'] == 'sticker'){
                    $typeresponse = 'txt';  
                    $messageID = $event['message']['packageId']; 
                    if ($messageID == 1 && $event['message']['stickerId'] == 100) {
                        //$respMessage = 'Hello, your Sticker Package ID is '. $messageID.' Stricker ID :'.$event['message']['stickerId'];
                        $respMessage = 'ขำเหี้ยไรมึง';
                    }                 
                     
                   // break;
                }                     
                     
            else if(count($appointments) == 2) {
                $typeresponse = 'txt';  
                $host = 'ec2-50-16-228-232.compute-1.amazonaws.com';
                $dbname = 'd6cm71n101ita9'; 
                $user = 'dvyuqfuldvzebl';
                $pass = '4c662a23211ff72c4b3eff5e78729e8e3c9e78956e22806f4e65a989fa386306';
                $connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 

                $params = array(  
                    'time' => $appointments[0],
                    'content' => $appointments[1], 
                ) ;
 
                $statement = $connection->prepare(" INSERT INTO appointments (time, content) VALUES (:time,:content)");
                $result = $statement->execute($params) ; 

                $respMessage = 'Your appointment has saved.';
            // }
            // else
            // {
            //     $respMessage = 'You can send appointment like this "12.00,House keeping." '; 
            // }

            }

            else{
                switch(strtolower($ask)) {  
                    case 'close':
                    $params = array(
                        'active' => 'close',
                    );
                    $statement = $connection->prepare('UPDATE flagactive SET active=:active'); 
                    $statement->execute($params);
                    $typeresponse = 'txt';                 
                    $respMessage = 'บอท หยุดทำงาน!'; 
                    break; 

                    case 'open':
                     $params = array(
                        'active' => 'open',
                    );
                    $statement = $connection->prepare('UPDATE flagactive SET active=:active'); 
                    $statement->execute($params);

                    $typeresponse = 'txt';                 
                    $respMessage = 'บอททำงาน!'; 
                    break; 

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
                    $originalContentUrl = 'https://lh3.googleusercontent.com/SdWCiU5B8Hq4cYGe1SWf3TJZtOwbBhZqbUVudIQ7EVghl9AP8920jgp1SkaBqZP6juTS13b2jC5PwCqJw0SCXfo9i33uRfLmbOAMDofOMpZlPAJHzf9JcyVHxBC2MnIRleUW3OSwR0L_up08cl-7jGhzn2meaDeKL7dfaKCn72pNusdXQUAxz9QirQWxs_YRDSSPzUuHy7BpUnLbSKV_IpsfGqjWD_ro-A8e3VdstGIT3aRG8RmUhr--Cs5TefRlK2LlLihJPfdEj7K7CUtwEFwUkFuChfP5oiJUMixOroQWdemtqTbOU7Z9YbG7FDcY9treU3uKXaDKzpZ93boYn38niqpHCz8rFUeOAX0R0rwKH85s-_sXShl9EcPf2eDuFrTBrz65UlffG9COvnz8VF5c7SzeCcWHn4rSi9sUiaUfh5poBAzq4oPK2ZZImORQsZlLan2mNOHhLyX4CHKzzPZqJoDoZMKtEPShCD1nAWub612z7rih-7Vt-rmMPkwQdyYnFYog7g_uPFkR6ewTq46OLsFx79Sc--Nm0xMRodirC76my0Ypuh8AZzei8HKy9FXlrnw8RrKvPuuJVuC6ro-l9DcTV9gdnmrQL1sr_A=w678-h508-no'; 
                    $previewImageUrl =  'https://lh3.googleusercontent.com/SdWCiU5B8Hq4cYGe1SWf3TJZtOwbBhZqbUVudIQ7EVghl9AP8920jgp1SkaBqZP6juTS13b2jC5PwCqJw0SCXfo9i33uRfLmbOAMDofOMpZlPAJHzf9JcyVHxBC2MnIRleUW3OSwR0L_up08cl-7jGhzn2meaDeKL7dfaKCn72pNusdXQUAxz9QirQWxs_YRDSSPzUuHy7BpUnLbSKV_IpsfGqjWD_ro-A8e3VdstGIT3aRG8RmUhr--Cs5TefRlK2LlLihJPfdEj7K7CUtwEFwUkFuChfP5oiJUMixOroQWdemtqTbOU7Z9YbG7FDcY9treU3uKXaDKzpZ93boYn38niqpHCz8rFUeOAX0R0rwKH85s-_sXShl9EcPf2eDuFrTBrz65UlffG9COvnz8VF5c7SzeCcWHn4rSi9sUiaUfh5poBAzq4oPK2ZZImORQsZlLan2mNOHhLyX4CHKzzPZqJoDoZMKtEPShCD1nAWub612z7rih-7Vt-rmMPkwQdyYnFYog7g_uPFkR6ewTq46OLsFx79Sc--Nm0xMRodirC76my0Ypuh8AZzei8HKy9FXlrnw8RrKvPuuJVuC6ro-l9DcTV9gdnmrQL1sr_A=w678-h508-no';              
                    break;  
     
                    default:     
                    $typeresponse = 'txt';
                   // $sql = "select textout from compare_message where textin like '%".strtolower($ask)."%'";
                   $sql = "select textin,textout from compare_message";
                    $result = $connection->query($sql);
                    if($result !== false && $result->rowCount() >0) {
                        foreach ($result as $row) {
                         if (strstr(strtolower($ask), $row['textin'])) {
                             $respMessage = $row['textout'];
                             break;
                            }
                        else{
                           // $typeresponse = 'none';
                            //$respMessage ="Not found";
                        }
                         
                        }
                    }
                   else
                   {
                    $typeresponse = 'none'; 
                   }

                    break; 
            } 
        }
    
            if ($event['type'] == 'follow') {
                $typeresponse = 'txt';
                // Get replyToken
                $replyToken = $event['replyToken'];
                
              // Greeting
                $respMessage = 'Thanks you. I try to be your best friend.';        
            }
    
            if ($event['type'] == 'join') {
                $typeresponse = 'txt';
                // Get replyToken
                $replyToken = $event['replyToken'];   
                // Greeting 
                $respMessage = 'Hi guys, I am MR.Robot. You can ask me everything.';
            }
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
            
            if ($typeresponse != 'none'){
            $httpClient = new CurlHTTPClient($channel_token);                      
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
            
            if ($typeresponse == 'txt'){
                //$textMessageBuilder = new TextMessageBuilder($event['message']['type'].' - '. $respMessage);   
                $textMessageBuilder = new TextMessageBuilder($respMessage);                        
            }
            elseif ($typeresponse == 'img'){
                $textMessageBuilder = new ImageMessageBuilder($originalContentUrl, $previewImageUrl);
            }
            
            $response = $bot->replyMessage($replyToken, $textMessageBuilder) ;
        }
    } 
}
} 
 
echo "OK"; 
