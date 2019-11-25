<?php 

include('config.php');
include('functions.php');

	//echo date('Y-m-d H:i:s A');exit;
	//echo date("Y-m-d H:i", strtotime('+2 hour'));
	$current_timestamp= strtotime(date('Y-m-d H:i'));
	$after_two_hours_timestamp= strtotime(date("Y-m-d H:i", strtotime('+2 hour')));
	//echo '---'.date('Y-m-d H:i:s', '1513874880');
	
	$sql= "select * from `events` where (`event_timestamp` = '".$after_two_hours_timestamp."' OR `event_start_timestamp` = '".$after_two_hours_timestamp."')";
	$result= mysqli_query($conn,$sql);
	
	while ($row_event = mysqli_fetch_assoc($result)){
		
		$group_id= $row_event['group_id'];
		if($row_event['event_timestamp']!='0'){
			$event_timestamp= date('Y-m-d H:i A', $row_event['event_timestamp']);
		}
		else{
			$event_timestamp= date('Y-m-d H:i A', $row_event['event_start_timestamp']);
		}
		
		$push_message = $row_event['title']."\r\n".$event_timestamp."\r\n".$row_event['location'];
		
		$sql2= "select * from `group_users` where `group_id` = '".$group_id."' AND `user_type` = 'M' AND `is_notification_stop`='0'";
		$result2= mysqli_query($conn,$sql2);
		while ($row2_users = mysqli_fetch_assoc($result2)){
		
			$sql3= "select * from `users` where `id` = '".$row2_users["user_id"]."'";
			$result3= mysqli_query($conn,$sql3);
			$row3_user_dtls = mysqli_fetch_assoc($result3);

			
			send_push_notification($push_message, $row3_user_dtls['device_token'], 'Event Notification', '1');
			
			$sql7= "select * from `site_settings` where `id` = '4'";
			$result7= mysqli_query($conn,$sql7);
			$row7_site_dtls = mysqli_fetch_assoc($result7);
			$admin_sender_email= $row7_site_dtls['value'];
			
			$user_header   = "From: ".$admin_sender_email." \r\n";
			$user_header  .= "MIME-Version: 1.0\r\n"; 
			$user_header  .= "Content-Type: text/html; charset=iso-8859-1\r\n";
			
			@mail($row3_user_dtls['email'], 'Event Notification', $push_message, $user_header);
					 
			/*$email = 'sjd753@gmail.com';
			$user_name = 'tania';
			$admin_sender_email = 'jishnu.php@gmail.com';
			$site_url = 'https://www.grouperusa.com/';
			$sender_name = 'tanima';
		
			
			$query = "SELECT * FROM `email_templates` WHERE `id` ='5'";
			$admin_email_query = mysqli_query($conn,$query);
			$mailDataRS = mysqli_fetch_assoc($admin_email_query);
		
			
			$user_subject = $mailDataRS['subject'];
			$user_subject = str_replace('[SITE NAME]', 'Test Cron Mail', $user_subject);
					   
		 
			$user_body = $mailDataRS['content'];
			$user_body = str_replace('[NAME]', $user_name, $user_body);
			
							 
			$user_mail = stripslashes($user_body);
		
			$user_header   = "From: ".$admin_sender_email." \r\n";
		
			$user_header  .= "MIME-Version: 1.0\r\n"; 
		
			$user_header  .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
		
			@mail($email, 'Registration successfull', $user_mail, $user_header);*/
			
		}
	}



?>