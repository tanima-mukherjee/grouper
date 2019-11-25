<?php
class EventCronController extends AppController {

    var $name = 'EventCron';
    var $uses = array('Event','State','City','Group','GroupUser','User','Notification','EmailTemplate','SiteSetting');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    

    

    function index(){
	
		$this->layout = "";
	 
		$sitesettings = $this->getSiteSettings();

		//pr($notification_detail);exit();

		
		  
		  $sender_email = 'taniasrivastav007@gmail.com';
		 // $sender_email = $sender_name.'<'.$notification_detail['Sender']['email'].'>';

		  $receiver_name = 'tania';
		  
		  $receiver_email = 'tanima.mukherjee9@gmail.com';
		  //$receiver_email = $receiver_name.'<'.$notification_detail['Receiver']['email'].'>';
		  
				
				$site_url = $sitesettings['site_url']['value'];
				
				$condition = "EmailTemplate.id = '5'";
				$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
				
				$to = $receiver_email;
				$user_name = $receiver_name;
				$sender_name = 'tanima';


				
				$user_subject = $mailDataRS['EmailTemplate']['subject'];
				$user_subject = str_replace('[SITE NAME]', 'Grouper |', $user_subject);
						   
			 
				$user_body = $mailDataRS['EmailTemplate']['content'];
				$user_body = str_replace('[USERNAME]', $user_name, $user_body);
				$user_body = str_replace('[SENDERNAME]', $sender_name, $user_body);
				$user_message = stripslashes($user_body);
				
	   
			   $string = '';
			   $filepath = '';
			   $filename = '';
			   $sendCopyTo = '';
	   
	   
				$sendmail = sendmail($sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                
                             
        

    }



	
    
}?>
