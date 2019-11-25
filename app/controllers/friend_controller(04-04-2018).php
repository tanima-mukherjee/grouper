<?php
App::import('Vendor','authnet_aim');
class FriendController extends AppController {

    var $name = 'Friend';
    var $uses = array('Category','Group','SubscriptionPlan','User','GroupUser','City','State','GroupImage','GroupDoc','Video','Notification','Friendlist','EmailTemplate','SiteSetting');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    

    function group_list($category_id) {
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        $this->_checkSessionUser();

  
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);


         /*     $selected_state_id = '132';
                $selected_city_id = '10541';
        */
        $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
        $ArState = $this->State->find('all', array('conditions' => $conditions));
        $this->set('ArState', $ArState);


         $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
         $StateName = $this->State->find('first', array('conditions' => $conditions));
         $this->set('StateName', $StateName);
		 
		$condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
		$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
		$this->set('citylist',$citylist);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
        $CityName = $this->City->find('first', array('conditions' => $conditions));
        $this->set('CityName', $CityName);

       
        $user_id = $this->Session->read('userData.User.id');
        $this->set('user_id',$user_id);

        $this->set('category_id',$category_id);
        
        $condition = "Category.status= '1'  AND Category.id= '".$category_id."'";
        $category_details = $this->Category->find("first",array('conditions'=>$condition));
       // pr($featured_users);exit();
        $this->set('category_details',$category_details);

        $limit = 8;
		$conditions_group = "Group.status= '1'  AND Group.category_id= '".$category_id."' AND Group.state_id= '".$selected_state_id."'  AND Group.city_id= '".$selected_city_id."'";
        $this->paginate = array('conditions' =>$conditions_group,'limit' => $limit,'order'=>'Group.group_title ASC');
		$this->Group->bindModel(array('hasMany' => array('GroupUser' => array('foreignKey' => 'group_id'))),false);
		$group_list = $this->paginate('Group');

       
        //$plan_list = $this->SubscriptionPlan->find("all");
        //$this->set('plan_list',$plan_list);
        $this->set('group_list',$group_list);

    }


    




    function add_friend_request()
    {
       
            $this->layout = "";
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');

            
            $receiver_id = $_REQUEST['receiver_id'];
           
           /* $user_id    = '2';
            $receiver_id    = '15';*/

            


                 $condition1 = "Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '". $receiver_id."' AND Notification.type = 'F' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";
                $is_request = $this->Notification->find('count',array('conditions'=>$condition1));  
                if($is_request > 0)
                {

                  /*$response['is_error'] = 1;
                  $response['err_msg'] = 'You have already send a request ';*/
                  echo '3';
                  exit;
                }
                else 
                {
                 
                 $condition8 = "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$user_id."' AND Friendlist.receiver_id = '".$receiver_id."')OR(Friendlist.sender_id = '".$receiver_id."' AND Friendlist.receiver_id = '".$user_id."'))";  // fetch the friends who belongs to the group
                $is_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));

                if($is_friend > 0)
                {

                 /* $response['is_error'] = 1;
                  $response['err_msg'] = 'You are already friends';*/
                  echo '2';
                  exit;
                }
                else
                {
                 
                  $this->data['Notification']['type'] = 'F';
                  $this->data['Notification']['sender_id'] = $user_id;
                  $this->data['Notification']['receiver_id'] = $receiver_id;
                                          
                $this->Notification->create();
                if($this->Notification->save($this->data))
                {

                  // send notification email for friend request
                     $notification_id = $this->Notification->getLastInsertId();  
                     $this->notification_email($notification_id);
                  
                  	 $page="Friend request";
					 $this->send_push_notification_friend_request($user_id, $receiver_id, 'sending_request', $page);

                  /*$response['is_error'] = 0;  
                  $response['success_msg'] = 'Friend request sent successfully';*/
                  echo '0';
                  exit;


                    }

                        else
                        {
                          /*$response['is_error'] = 1;
                          $response['err_msg'] = 'Join now request unccessful';*/
                          echo '1';
                          exit;
                        }
                    }
                }

       
        
    }
	
	
	function add_friend_request_again()
    {
       
            $this->layout = "";
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');

            
            $receiver_id = $_REQUEST['receiver_id'];
           
           /* $user_id    = '2';
            $receiver_id    = '15';*/

            


                 $condition1 = "Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '". $receiver_id."' AND Notification.type = 'F' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";
                $arr_not = $this->Notification->find('first',array('conditions'=>$condition1));  
                if(!empty($arr_not)){
				
					$this->Notification->id = $arr_not['Notification']['id'];
             		$this->Notification->delete();
					
					$this->data['Notification']['type'] = 'F';
                  	$this->data['Notification']['sender_id'] = $user_id;
                  	$this->data['Notification']['receiver_id'] = $receiver_id;
					
					$this->Notification->create();
					if($this->Notification->save($this->data)){
	
					// send notification email for friend request
					$notification_id = $this->Notification->getLastInsertId();  
					$this->notification_email($notification_id);
					  
					$page="Friend request";
					$this->send_push_notification_friend_request($user_id, $receiver_id, 'sending_request', $page);
	
					echo '0';
					exit;
	
	
					}
                }
                else{
					echo '1';
					exit;
				}      
        
    }
	
	
    function notification_email($notification_id)
        {
            $this->layout = "";
         
            $sitesettings = $this->getSiteSettings();


            $condition = "(Notification.id = '".$notification_id."')";
            $this->Notification->bindModel(
              array('belongsTo'=>array(
                  
                  'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'receiver_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname')
                )
                ));
            $notification_detail = $this->Notification->find('first', array('conditions'=>$condition));

            if($notification_detail['Notification']['type'] == 'F')
            {

              //pr($notification_detail);exit();

              

              $sender_name = $notification_detail['Sender']['fname'].' '.$notification_detail['Sender']['lname'];
              
              $sender_email = $notification_detail['Sender']['email'];
             // $sender_email = $sender_name.'<'.$notification_detail['Sender']['email'].'>';

              $receiver_name = $notification_detail['Receiver']['fname'].' '.$notification_detail['Receiver']['lname'];
              
             $receiver_email = $notification_detail['Receiver']['email'];
             // $receiver_email = $receiver_name.'<'.$notification_detail['Receiver']['email'].'>';
              
                    
                    $site_url = $sitesettings['site_url']['value'];

                     //
                if(($notification_detail['Notification']['is_receiver_accepted'] == '0')&& ($notification_detail['Notification']['is_reversed_notification']== '0')){ 


                    $condition = "EmailTemplate.id = '15'";// sent you a friend request
                    $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                                        
                 }


                else if (($notification_detail['Notification']['is_receiver_accepted']== '1')&& ($notification_detail['Notification']['is_reversed_notification']== '1')) { 


                      $condition = "EmailTemplate.id = '17'";//  rejected your friend request
                      $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                                     
                  } 


                 else if (($notification_detail['Notification']['is_receiver_accepted']== '2')&& ($notification_detail['Notification']['is_reversed_notification']== '1')) { 

                      $condition = "EmailTemplate.id = '16'";// accepted your friend request .  
                      $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                } 
                        
                //
                    
                                     
                    $to = $receiver_email;
                    $user_name = $receiver_name;
                    

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

    }


    function accept_friend_request()
    {
       
            $this->layout = "";
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');



            $notification_id = $_REQUEST['notification_id'];
           
            
           
            $condition = "Notification.id = '".$notification_id."' AND Notification.type = 'F' AND Notification.status = '1'";
                $is_noti = $this->Notification->find('count',array('conditions'=>$condition));  
                if($is_noti > 0)
                {

                  
                $condition1 = "Notification.id = '".$notification_id."' AND Notification.type = 'F'";
                $noti_detail = $this->Notification->find('first',array('conditions'=>$condition1));  
                $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition1));  

                
                $this->data['Notification']['is_read'] = '1';
                $this->data['Notification']['is_receiver_accepted'] = '2';
                
                 $this->Notification->id = $notification_id;                 
                if($this->Notification->save($this->data))
                {
                  $this->data['Notification']['type'] = 'F';
                  $this->data['Notification']['sender_id'] = $old_noti_detail['Notification']['receiver_id'];
                  $this->data['Notification']['request_mode'] = $old_noti_detail['Notification']['request_mode'];
                  $this->data['Notification']['receiver_id'] = $old_noti_detail['Notification']['sender_id'];
                  $this->data['Notification']['group_id'] = $old_noti_detail['Notification']['group_id'];
                  $this->data['Notification']['is_receiver_accepted'] = '2';
                  $this->data['Notification']['is_reversed_notification'] =  '1';
                  $this->data['Notification']['is_read'] =  '0';
                                            
               
                $this->Notification->create();
                if($this->Notification->save($this->data))
                    {
                      $last_insert_id = $this->Notification->getLastInsertId(); 
                      // send notification email for friend request accept
                       
                      $this->notification_email($last_insert_id);
                  
                      //
                      //pr($last_insert_id);exit();
                      $condition7 = "Notification.id = '".$last_insert_id."'";
                        $noti_detail = $this->Notification->find('first',array('conditions'=>$condition7)); 
                      //pr($noti_detail);exit();
                        $this->data['Friendlist']['sender_id'] = $noti_detail['Notification']['receiver_id'];
                      $this->data['Friendlist']['receiver_id'] =  $noti_detail['Notification']['sender_id'];
                      $condition7 = "User.id = '".$noti_detail['Notification']['sender_id']."'";
                        $user_details = $this->User->find('first',array('conditions'=>$condition7)); 
                        $this->data['Friendlist']['friend_name'] = $user_details['User']['fname']." ".$user_details['User']['lname'];

                        $this->Friendlist->create();
                      if($this->Friendlist->save($this->data))
                      {
                        $last_insert_id = $this->Friendlist->getLastInsertId(); 
                     
                        $condition7 = "Friendlist.id = '".$last_insert_id."'";
                          $friend_detail = $this->Friendlist->find('first',array('conditions'=>$condition7)); 
                      

                          $this->data['Friendlist']['sender_id'] = $friend_detail['Friendlist']['receiver_id'];
                        $this->data['Friendlist']['receiver_id'] = $friend_detail['Friendlist']['sender_id'];
                        $condition7 = "User.id = '".$friend_detail['Friendlist']['sender_id']."'";
                          $user_details = $this->User->find('first',array('conditions'=>$condition7)); 
                          $this->data['Friendlist']['friend_name'] = $user_details['User']['fname']." ".$user_details['User']['lname'];
                         $this->Friendlist->create();
                      if($this->Friendlist->save($this->data))
                      { 
					  
					  		$page="Friend request accepted";
							$this->send_push_notification_friend_request($old_noti_detail['Notification']['receiver_id'], $old_noti_detail['Notification']['sender_id'], 'accept_request', $page);

                            echo '0';
                            exit;
                          // $response['is_error'] = 0;  
                         //$response['success_msg'] = 'Friend Request accepted';
                      }
                      else
                    {
                      echo '1';
                      exit;
                     /* $response['is_error'] = 1;
                      $response['err_msg'] = 'Friend Request unaccepted ';*/
                    }

                        }
                        else
                    {
                      /*$response['is_error'] = 1;
                      $response['err_msg'] = 'Friend Request unaccepted ';*/
                      echo '1';
                      exit;
                    }


                      }
                      else
                    {
                      /*$response['is_error'] = 1;
                      $response['err_msg'] = 'Friend Request unaccepted ';*/
                      echo '1';
                      exit;
                    }

                 
                  }
                  else
                    {
                      /*$response['is_error'] = 1;
                      $response['err_msg'] = 'Friend Request unaccepted ';*/
                      echo '1';
                      exit;
                    }
               
               
           }

          else{
            /*$response['is_error'] = 1;
            $response['err_msg'] = 'No notification found';*/
            echo '2';
            exit;
          }
               
    }



    function reject_friend_request()
    {
       
             $this->layout = "";
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');



            $notification_id = $_REQUEST['notification_id'];
                        

           // $notification_id    = '27';
           
            
            
            $condition = "Notification.id = '".$notification_id."' AND Notification.type = 'F' AND Notification.status = '1'";
                $is_noti = $this->Notification->find('count',array('conditions'=>$condition));  
                if($is_noti > 0)
                {

                $condition1 = "Notification.id = '".$notification_id."' AND Notification.type = 'F' ";
                $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition1));  

                //pr($old_noti_detail);exit();
               
               // $this->data['Notification']['id'] = $notification_id;
                $this->data['Notification']['is_read'] = '1';
                $this->data['Notification']['is_receiver_accepted'] = '1';
                
                 $this->Notification->id = $notification_id;                 
                if($this->Notification->save($this->data))
                {
                  $this->data['Notification']['type'] = 'F';
                  $this->data['Notification']['sender_id'] = $old_noti_detail['Notification']['receiver_id'];
                  $this->data['Notification']['receiver_id'] = $old_noti_detail['Notification']['sender_id'];
                  $this->data['Notification']['is_receiver_accepted'] = '1';
                  $this->data['Notification']['is_reversed_notification'] =  '1';
                  $this->data['Notification']['is_read'] =  '0';
                                            
               
                $this->Notification->create();
                if($this->Notification->save($this->data))
                    {
                      // send notification email for friend request reject
                      $notification_id = $this->Notification->getLastInsertId();  
                      $this->notification_email($notification_id);
					  
					  $page="Friend request rejected";
					  $this->send_push_notification_friend_request($old_noti_detail['Notification']['receiver_id'], $old_noti_detail['Notification']['sender_id'], 'reject_request', $page);
                  
                      //
                  /*$response['is_error'] = 0;  
                  $response['success_msg'] = 'Friend Request rejected';*/

                            echo '0';
                            exit;
                 
                  }

                    else
                    {
                      /*$response['is_error'] = 1;
                      $response['err_msg'] = 'Friend Request not rejected ';*/


                            echo '1';
                            exit;
                    }


            }
           }
          else{
            /*$response['is_error'] = 1;
            $response['err_msg'] = 'No notification found';*/
            echo '2';
            exit;
          }
       
    }
	
	
	/*---------------------------  Push Notification to send friend request starts    -----------------------*/
	
	function send_push_notification_friend_request($sender_id=NULL, $receiver_id=NULL, $mode, $page=NULL){
	    //$this->_checkSession();
		$condition_receiver = "User.id = '".$receiver_id."'";
        $user_details= $this->User->find('first',array('conditions' => $condition_receiver));
		if($user_details['User']['device_token']!='')
		{
			$condition_sender = "User.id = '".$sender_id."'";
			$sender_details= $this->User->find('first',array('conditions' => $condition_sender));
			
			if($mode=='sending_request'){
				$message= ucfirst($sender_details['User']['fname'] .' '. $sender_details['User']['lname']).' sent you a friend request.';
			}
			else if($mode=='accept_request'){
				$message= ucfirst($sender_details['User']['fname'] .' '. $sender_details['User']['lname']).' has accepted your friend request.';
			}
			else if($mode=='reject_request'){
				$message= ucfirst($sender_details['User']['fname'] .' '. $sender_details['User']['lname']).' has rejected your friend request.';
			}
            
			###################		Get the Notification Counter Starts 	################
				
			$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
	  
			$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
			
			###################		Get the Notification Counter Starts 	################
			            			
		  	$this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
		}
   }
   
   /*---------------------------  Push Notification to send friend request ends    -----------------------*/
   
   
   function send_push_notification($message=NULL,$registatoin_ids=NULL,$page=NULL, $notification_count=NULL){
        /*$message = 'Sajjad Mistri invited you to join Private group Alovera';
        $registatoin_ids ='dKH2FWX6DtQ:APA91bHchG6PmkkWWic1S_BcBwiYPMt1dFjhlbilgQ1wXv0symPqwsNqHkFLBEClgb88YMa92weHi-JDefahsIESD3Ib5Wg2TKEeprwAjQYxsX9Nej60ghr8czb_qnisnSY3WmBZtusD';
        $page = 'Group Joining Invitation To Friends';
*/
        //$this->_checkSession();
        $messageArr = array();       
        $messageArr['to']  =  $registatoin_ids;       
        $messageArr['notification']['title']  =  $page;     
        $messageArr['notification']['body']   =  $message;
		$messageArr['notification']['sound']   =  'default';
  		$messageArr['notification']['icon']   =  'ic_app_launcher';
		$messageArr['data']['notification_count']   =  $notification_count;
		$messageArr['data']['notification_type']   =  'bell_notification';
      
        $message = $messageArr;

        /*echo json_encode($messageArr);
        exit();

        echo "<pre>";

        print_r($message);*/

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
        //echo $result;
        //exit();
        curl_close ( $ch );
        //die;
        return $result;
       /*echo "<pre>";

        print_r($result); */
    }




    


       


}?>
