<?php
//$conn = mysql_connect("localhost", 'chris_grouperusa', '#$chris%grouperusa$#');
$conn = mysql_connect("localhost", 'root', 'mysqlpass');
$db = mysql_select_db("grouppers", $conn);
date_default_timezone_set('America/New_York');
//echo date('H:i:s');exit;
$base_url = "https://www.grouperusa.com/";
$json = '';
if(isset($_REQUEST['rq'])):
 switch($_REQUEST['rq']):
 case 'new':
 /*$msg = $_POST['msg'];
 $myid = $_POST['mid'];
 $fid = $_POST['fid'];*/
 
 
$msg = $_REQUEST['msg'];
$myid = $_REQUEST['mid'];
$fid = $_REQUEST['fid'];
$to_name = $_REQUEST['to_name'];
$from_name = $_REQUEST['from_name'];
$date = date('Y-m-d H:i:s');
 
 if(empty($msg)){
 //$json = array('status' => 0, 'msg'=> 'Enter your message!.');
 }else{
 //$qur = mysql_query('insert into msg set `to`="'.$fid.'", `from`="'.$myid.'", `msg`="'.$msg.'", `status`="1"');
 //print_r($_REQUEST);exit;
//echo "INSERT INTO chats (`from`, `to`, `from_id`, `to_id`, `conversation`, `is_viewed_to`, `is_viewed_from`, `created`) VALUES ('".$from_name."', '".$to_name."', '".$myid."', '".$fid."', '".$msg."', '0', '1','".$date."')";


$qur = mysql_query("INSERT INTO chats (`from`, `to`, `from_id`, `to_id`, `conversation`, `is_viewed_to`, `is_viewed_from`, `status`, `created`) VALUES ('".$from_name."', '".$to_name."', '".$myid."', '".$fid."', '".$msg."', '0', '1','1','".$date."')");
 //$qur = mysql_query('insert into chats set `from`="'.$from_name.'",`to`="'.$to_name.'", `from_id`="'.$myid.'", `to_id`="'.$fid.'", `conversation`="'.$msg.'", `is_viewed_to`="0", `is_viewed_from`="1"');
 //echo mysql_insert_id();exit;

 if($qur){
 //$qurGet = mysql_query("select * from chats where id='".mysql_insert_id()."'");
 $qurGet = mysql_query("select users.image,chats.conversation,chats.created from chats,users where chats.id='".mysql_insert_id()."' && chats.from_id=users.id");	
 $qurGet1 = mysql_query("select users.device_token,chats.conversation from chats,users where chats.id='".mysql_insert_id()."' && chats.to_id=users.id"); 
 $last_chat_insert_id = mysql_insert_id();

while($row = mysql_fetch_array($qurGet1)){
 $message = $msg;
 $device_token = $row['device_token'];
 $notification_title = $from_name." has sent you a message.";
 $result = mysql_query("select * from chats where chats.to_id = '".$fid."' AND chats.status = '1'"); 
 $notification_count = mysql_num_rows($result); 
 
 
 send_push_notification($message,$device_token,$notification_title,$notification_count,$last_chat_insert_id);

}

while($row = mysql_fetch_array($qurGet)){
 //$json = array('status' => 1, 'msg' => $row['conversation'], 'lid' => mysql_insert_id(), 'time' => $row['created']);

 $json = array('status' => 1, 'from_name' => $from_name, 'profile_image' => $row['image'], 'msg' => $row['conversation'], 'lid' => $last_insert_id, 'time' => $row['created']);
			
 }
 }else{
 $json = array('status' => 0, 'msg'=> 'Unable to process request.');
 }
 }
 break;
 case 'msg':
 $myid = $_REQUEST['mid'];
 $fid = $_REQUEST['fid'];
 $lid = $_REQUEST['lid'];
 if(empty($myid)){
 
 }else{
 //print_r($_POST);
 //$qur = mysql_query("select * from chats where `to_id`='$myid' && `from_id`='$fid' && `status`=1");
 
 $qur = mysql_query("select chats.id from chats where (chats.from_id = '".$myid."' AND chats.to_id = '".$fid."') OR (chats.from_id = '".$fid."' AND chats.to_id = '".$myid."') AND chats.status = '1'");
	 
 //print_r( $qur);exit;
 if(mysql_num_rows($qur) > 0){
 $json = array('status' => 1);
 }else{
 $json = array('status' => 0);
 }
 }
 break;
 case 'NewMsg':
 $myid = $_REQUEST['mid'];
 $fid = $_REQUEST['fid'];
 
 //$qur = mysql_query("select * from chats where `to_id`='$myid' && `from_id`='$fid' && `status`=1 order by id desc limit 1");
 
 $qur = mysql_query("select chats.from,users.image,chats.conversation,chats.created from chats,users where chats.from_id = users.id AND chats.`to_id` = '".$myid."' AND chats.`from_id` = '".$fid."' AND chats.`status` = '1' order by chats.id desc limit 1");
 
 
 while($rw = mysql_fetch_array($qur)){
 //$json = array('status' => 1, 'msg' => $rw['conversation'], 'lid' => $rw['id'], 'time'=> $rw['created']);
 $json = array('status' => 1, 'from_name' => $rw['from'], 'profile_image' => $rw['image'], 'msg' => $rw['conversation'], 'lid' => $rw['0'], 'time'=> $rw['created']);

 }
 // update status
 $up = mysql_query("UPDATE `chats` SET  `status` = '0' WHERE `to_id`='".$myid."' && `from_id`='".$fid."'");
 break;
 endswitch;
endif;





function send_push_notification($message=NULL,$registatoin_ids=NULL,$page=NULL,$notification_count=NULL,$last_chat_insert_id=NULL){
       
		$base_url = "https://www.grouperusa.com/";
        $qurGet = mysql_query("select users.id as user_id,chats.id as chat_id,users.image,users.fname,users.lname,chats.conversation,chats.created as chat_date from chats,users where chats.id='".$last_chat_insert_id."' && chats.from_id=users.id"); 
 

        $messageArr = array();       
        $list = array();
        $messageArr['to']  =  $registatoin_ids;       
        $messageArr['notification']['title']  =  $page;     
        $messageArr['notification']['body']   =  $message;
		$messageArr['notification']['sound']   =  'default';
  		$messageArr['notification']['icon']   =  'ic_app_launcher';
        $messageArr['data']['notification_count']   =  $notification_count;
		$messageArr['data']['notification_type']   =  'chat_notification';

        while($row = mysql_fetch_array($qurGet))
        {
            $list['chat_id'] = $row['chat_id'];
            $list['sender_id'] =  $row['user_id'];
            $list['sender_name'] =   $row['fname'].' '.$row['lname'];
            $list['message'] =  $row['conversation'];
            $list['date_time'] = date('h:i A',strtotime($row['chat_date']));
            if($row['image']!=""){
            
                $list['sender_image'] = $base_url.'user_images/thumb/'.$row['image'];

            }
            else{
                $list['sender_image'] = $base_url.'images/no_profile_img.jpg';
            }
        }
        //$messageArr['notification']['body']   =  json_encode($list);
		$messageArr['data']['chat_details']   =  json_encode($list);

      
        $message = $messageArr;

        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . "AAAAAqY2LLo:APA91bEp82S2ewKHqL5_bpo6uW80i9CIa4eAQDT90-wkbyVTWlYTud797-2FOAI-9vkNlMYAYKjN-bAxrKeSxhNPqxJA0IA3LqO5SbW4CI35KQ8XJTLrtUj55GFX63fTV1mc-VhyO7ZD",
            'Content-Type: application/json'
        );
        
        $fields = $message;
        
        $fields = json_encode ( $fields );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    
        $result = curl_exec ( $ch );
        
        curl_close ( $ch );
        return $result;
    }



 
@mysql_close($conn);
header('Content-type: application/json');
echo json_encode($json);
exit;
?>
