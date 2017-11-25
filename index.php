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
            
            if (($OnSystem == 'on') || (strtolower($ask) == 'on'))
            {
            if(count($appointments) == 2) {
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
                    case 'off':
                    $params = array(
                        'active' => 'off',
                    );
                    $statement = $connection->prepare('UPDATE flagactive SET active=:active'); 
                    $statement->execute($params);
                    $typeresponse = 'txt';                 
                    $respMessage = 'บอท หยุดทำงาน!'; 
                    break;  
                    case 'on':
                     $params = array(
                        'active' => 'on',
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
    
                    case 'ชัช':
                    $typeresponse = 'txt';
                    $respMessage = 'ชัชชัย เป็นคนดี และ หล่อมาก';
                    break;
                    case 'ลิง':
                    $typeresponse = 'txt';
                    $respMessage = 'พ่อมึงหรอ.. ไอ้คนพิมพ์';
                    break;
                    case 'เอก':
                    $typeresponse = 'txt';
                    $respMessage = 'เอก เป็นคนดี และ หล่อมาก';
                    break;
                    case 'คอง':
                    $typeresponse = 'txt';
                    $respMessage = 'คองเหี้ยมาก บ้ากาม..';
                    break;
                    case 'ตู่':
                    $typeresponse = 'txt';
                    $respMessage = 'เลวเหี้ย เหมือนไอ้คอง';
                    break;
                    case 'รักษ์':
                    $typeresponse = 'txt';
                    $respMessage = 'รักษ์เอาดะ ตัณหากลับ';
                    break;
                    case 'เขี้ยว':
                    $typeresponse = 'txt';
                    $respMessage = 'เขี้ยวเหี้ยสุดๆ มีเมียหนาวตลอดปี';
                    break;
                    case 'ออย':
                    $typeresponse = 'txt';
                    $respMessage = 'ถ้าเป็นออยหญิง ก็อีปี๊ป ถ้าเป็นชายก็ออยก้อย';
                    break;
                    case 'ทันย่า':
                    $typeresponse = 'txt';
                    $respMessage = 'โชคดีมาก มีผัวเป็นคนดีสุด ๆ';
                    break;
                    case 'เปรม':
                    $typeresponse = 'txt';
                    $respMessage = 'อ่อนๆ ตามพี่คองเขาว่าไว้';
                    break;
                    case 'ตุ้ย':
                    $typeresponse = 'txt';
                    $respMessage = 'ไร้น้ำยา';
                    break;
                    case 'เติ้ง':
                    $typeresponse = 'txt';
                    $respMessage = 'หนาวจัด จนฟันออกปาก';
                    break;
                    case 'ก้อย':
                    $typeresponse = 'txt';
                    $respMessage = 'เป็นเมียออย';
                    break;
                    case 'สัน':
                    $typeresponse = 'txt';
                    $respMessage = 'ไอ้ปลาค๊าฟ...';
                    break;
                    case 'ตุ๊ด':
                    $typeresponse = 'txt';
                    $respMessage = 'มีเมียเป็นน้องตลกชื่อดัง พ่อชื่อเราะ อีกชื่อคือลิเก';
                    break;

                    default: 
                    //  $typeresponse = 'none';                
                    //  $respMessage = 'What is your sex? M or F or Img to view image or 12.00,House keeping. to save to db'; 
                    $typeresponse = 'txt';
                
                    $sql = "select textout from compare_message where textin = '" + strtolower($ask) + "'";
                   $respMessage = $sql;
                   $result = $connection->query($sql);
                    if($result !== false && $result->rowCount() >0) {
                        foreach ($result as $row) {
                         $respMessage = $row['textout'];
                        }
                    }
                   else
                   {
                    //$respMessage = $sql;
                    $typeresponse = 'txt'; 
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
