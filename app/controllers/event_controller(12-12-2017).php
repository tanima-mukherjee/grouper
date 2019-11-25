<?php
class EventController extends AppController {

    var $name = 'Event';
    var $uses = array('Event','State','City','Group','GroupUser','User','Notification','EmailTemplate','SiteSetting');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    

    function add_event()
    {
       
            $this->layout = ""; 
            $this->_checkSessionUser();

              $this->layout = "";
              $user_id = $this->Session->read('userData.User.id');
            

                 //echo strtotime(date('Y-m-d H:i:s'));exit
                  $this->data['Event']['title'] =  $this->params['form']['title'];
                  $this->data['Event']['group_id'] = $this->params['form']['event_group_id'];
                  $this->data['Event']['desc'] = $this->params['form']['desc'];
                  $this->data['Event']['location'] = $this->params['form']['address'];
                  $this->data['Event']['latitude'] = $this->params['form']['lat'];
                  $this->data['Event']['longitude'] = $this->params['form']['lng'];
                  $this->data['Event']['place_id'] = $this->params['form']['place_id'];
                   $con_group_detail = "Group.id = '".$this->params['form']['event_group_id']."' AND Group.status = '1'";
                   $group_detail = $this->Group->find('first',array('conditions'=>$con_group_detail));
                 //  pr($group_detail['Group']['category_id']);
                   if($group_detail['Group']['category_id']=='')
                   {
                    $this->data['Event']['category_id'] = '';
                   }
                   else
                   {
                    $this->data['Event']['category_id'] = $group_detail['Group']['category_id'];
                   }
                   $this->data['Event']['group_type'] = $group_detail['Group']['group_type']; 
                    if($group_detail['Group']['group_type']=='B')
                   {
                    $this->data['Event']['deal_amount'] = $this->params['form']['amount'];
                    $this->data['Event']['type'] = 'public';
                    
                   }
                   else if($group_detail['Group']['group_type']=='F')
                   {
                    $this->data['Event']['deal_amount'] = '0.00';
                    $this->data['Event']['type'] = $this->params['form']['group_type'];
                   }      
                  
                  if($this->params['form']['is_multiple'] == '1')     
                  {
                    $this->data['Event']['event_timestamp'] = '0';
                    $this->data['Event']['event_date'] = '0000-00-00'; 
                    $this->data['Event']['event_start_timestamp'] = strtotime($this->params['form']['event_start_date']);
                    $this->data['Event']['event_end_timestamp'] = strtotime($this->params['form']['event_end_date']);
                    $this->data['Event']['is_multiple_date'] = $this->params['form']['is_multiple'];
                    $this->data['Event']['event_start_date'] = date("Y-m-d",strtotime($this->params['form']['event_start_date']));  
                    $this->data['Event']['event_end_date'] = date("Y-m-d",strtotime($this->params['form']['event_end_date']));  
                  }    
                  else
                  {
                    $this->data['Event']['event_date'] = date("Y-m-d",strtotime($this->params['form']['event_date'])); 
                    $this->data['Event']['event_timestamp'] = strtotime($this->params['form']['event_date']);
                    $this->data['Event']['event_start_timestamp'] = '0';
                    $this->data['Event']['event_end_timestamp'] = '0';
                    $this->data['Event']['event_start_date'] = '0000-00-00';
                    $this->data['Event']['event_end_date'] = '0000-00-00';
                    $this->data['Event']['is_multiple_date'] = $this->params['form']['is_multiple'];
                  }
                  
                  $this->data['Event']['created_by_owner_id'] = $user_id;
                                 
                  $this->Event->create();
                  if($this->Event->save($this->data))
                    {

                  $last_insert_id = $this->Event->getLastInsertId();
                  $condition_event_detail = "Event.id = '".$last_insert_id."'";
                  $latest_event_details = $this->Event->find('first',array('conditions'=>$condition_event_detail));
				

				 if($latest_event_details['Event']['group_type'] == 'F'){	
				 
				 
                  $condition_group_user_detail = "GroupUser.group_id = '".$this->params['form']['event_group_id']."' AND GroupUser.user_id != '".$user_id."'";
				  $group_members = $this->GroupUser->find('all',array('conditions'=>$condition_group_user_detail));
				  

                  foreach($group_members as $grp_mem){

                        $this->data['Notification']['sender_id'] = $user_id;
                        $this->data['Notification']['receiver_id'] = $grp_mem['GroupUser']['user_id'];
                        $this->data['Notification']['type'] = 'E';
                        $this->data['Notification']['group_type'] = $latest_event_details['Event']['group_type'];
                        $this->data['Notification']['group_id'] = $latest_event_details['Event']['group_id'];

                        $this->data['Notification']['is_read'] = 0;
                        $this->data['Notification']['is_receiver_accepted'] = 0;
                        $this->data['Notification']['is_reversed_notification'] = 0;
                        $this->data['Notification']['status'] = 1;
                        $this->data['Notification']['event_id'] = $last_insert_id ;

                      
                        $this->Notification->create();
                        //$this->Notification->save($this->data['Notification']);
                        if($this->Notification->save($this->data['Notification']))
                        {

                          $notification_id = $this->Notification->getLastInsertId();  
                          $eve_name = $this->params['form']['title'];
         
                         // $this->notification_email($notification_id,$eve_name);
						  
						  $page="Event reminder";
					  	  //$this->send_push_notification_event($user_id, $grp_mem['GroupUser']['user_id'], $latest_event_details['Event']['group_id'], $last_insert_id, $page);
                        }

                        
                         
                      }
					  
				  }
                          
                  $this->Session->setFlash(__d("statictext", "Event created successfully", true));
                    $_SESSION['meesage_type'] = '1';
                    
                    $this->redirect("/group/group_detail/".$this->params['form']['event_group_id']);
                
                 
                    }

                        else
                        {
                          $this->Session->setFlash(__d("statictext", "Event creation failed", true));
                          $_SESSION['meesage_type'] = '0';
                    
                          $this->redirect("/group/group_detail/".$this->params['form']['event_group_id']);
                        }
       
    }


    function edit_event($event_id){
       
        $this->layout = ""; 
		$this->_checkSessionUser();
		
		$defaultconfig = $this->getDefaultConfig();      
		$base_url = $defaultconfig['base_url'];
		$this->_checkSessionUser();
		$user_id = $this->Session->read('userData.User.id');
		$session_user_id = $this->Session->read('userData.User.id');

		$conditions_event = "Event.id = '".$event_id."'";
		$this->Event->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id'))), false);
		$this->Event->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'created_by_owner_id'))), false);
		$this->Event->bindModel(array('belongsTo' => array('Category' => array('foreignKey' => 'category_id'))), false);
		$event_details =  $this->Event->find('first',array('conditions' => $conditions_event));
		// pr($event_details);exit();
		$this->set('event_details',$event_details);
              
            
       
    }

    function delete_event(){
    
        $this->layout = ""; 
        $response = array();
        $this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');
       
        $event_id = $_REQUEST['event_id'];
       // $user_id = $_REQUEST['user_id'];
        

        if($event_id>0)
        {
        
              $this->Event->id = $event_id;
              $this->Event->delete();
              $this->Session->setFlash(__d("statictext", "Event deleted successfully!!", true));
              $_SESSION['meesage_type'] ='1';
              echo "1";
              exit;
              /*
              $response['is_error'] = 0;
              $response['success_msg'] = 'Event deleted successfully !!!';*/
        }         
        else
        {
               $this->Session->setFlash(__d("statictext", "Event deletion unsuccessful!!", true));
               $_SESSION['meesage_type'] ='1';
               echo "0";
               exit;
             /*$response['is_error'] = 1;
             $response['err_msg'] = 'Event does not exist';*/
        }
        
        
    }

    function submit_edit_event($event_id){
      
    $this->layout = "";
	$this->_checkSessionUser();
      
	$defaultconfig = $this->getDefaultConfig();      
	$base_url = $defaultconfig['base_url'];
	$this->_checkSessionUser();
	$user_id = $this->Session->read('userData.User.id');
	$session_user_id = $this->Session->read('userData.User.id');
     

    if (isset($this->params['form']) && !empty($this->params['form'])) {
       // pr($this->params['form']);exit();
       
		  $this->data['Event']['id'] =  $event_id;
		  $this->data['Event']['title'] =  $this->params['form']['title'];
		  $this->data['Event']['desc'] = $this->params['form']['desc'];
		  $this->data['Event']['location'] = $this->params['form']['address'];
		  $this->data['Event']['latitude'] = $this->params['form']['lat'];
		  $this->data['Event']['longitude'] = $this->params['form']['lng'];
		  $this->data['Event']['place_id'] = $this->params['form']['place_id'];

                   
                   
		   $con_group_detail = "Group.id = '".$this->params['form']['event_group_id']."' AND Group.status = '1'";
		   $group_detail = $this->Group->find('first',array('conditions'=>$con_group_detail));

		  $this->data['Event']['group_type'] = $group_detail['Group']['group_type']; 
			if($group_detail['Group']['group_type']=='B')
		   {
			$this->data['Event']['deal_amount'] = $this->params['form']['amount'];
			$this->data['Event']['type'] = 'public';
			
		   }
		   else if($group_detail['Group']['group_type']=='F')
		   {
			$this->data['Event']['deal_amount'] = '0.00';
			$this->data['Event']['type'] = $this->params['form']['group_type'];
		   }      
		  
		  if($this->params['form']['is_multiple'] == '1')     
		  {
			$this->data['Event']['event_timestamp'] = '0';
			$this->data['Event']['event_date'] = '0000-00-00'; 
			$this->data['Event']['event_start_timestamp'] = strtotime($this->params['form']['event_start_date']);
			$this->data['Event']['event_end_timestamp'] = strtotime($this->params['form']['event_end_date']);
			$this->data['Event']['is_multiple_date'] = $this->params['form']['is_multiple'];
			$this->data['Event']['event_start_date'] = date("Y-m-d",strtotime($this->params['form']['event_start_date']));  
			$this->data['Event']['event_end_date'] = date("Y-m-d",strtotime($this->params['form']['event_end_date']));  
		  }    
		  else
		  {
			$this->data['Event']['event_date'] = date("Y-m-d",strtotime($this->params['form']['event_date'])); 
			$this->data['Event']['event_timestamp'] = strtotime($this->params['form']['event_date']);
			$this->data['Event']['event_start_timestamp'] = '0';
			$this->data['Event']['event_end_timestamp'] = '0';
			$this->data['Event']['event_start_date'] = '0000-00-00';
			$this->data['Event']['event_end_date'] = '0000-00-00';
			$this->data['Event']['is_multiple_date'] = $this->params['form']['is_multiple'];
		  }

        
         if($this->Event->save($this->data['Event']))
        {

            $condition_event_detail = "Event.id = '".$event_id."'";
                      
            $latest_event_details = $this->Event->find('first',array('conditions'=>$condition_event_detail));

            $condition_group_user_detail = "GroupUser.group_id = '".$latest_event_details['Event']['group_id']."' AND GroupUser.user_id != '".$user_id."'";
                      
                  $group_members = $this->GroupUser->find('all',array('conditions'=>$condition_group_user_detail));

                  foreach($group_members as $grp_mem)
                      {
                      	
                        if($latest_event_details['Event']['group_type'] == 'F')
                      {
                        $this->data['Notification']['sender_id'] = $user_id;
                        $this->data['Notification']['receiver_id'] = $grp_mem['GroupUser']['user_id'];
                        $this->data['Notification']['type'] = 'E';
                     $this->data['Notification']['group_type'] = $latest_event_details['Event']['group_type'];
                        $this->data['Notification']['group_id'] = $latest_event_details['Event']['group_id'];

                        $this->data['Notification']['is_read'] = 0;
                        $this->data['Notification']['is_receiver_accepted'] = 0;
                        $this->data['Notification']['is_reversed_notification'] = 0;
                        $this->data['Notification']['status'] = 1;
                        $this->data['Notification']['event_id'] = $event_id ;
                      
                        $this->Notification->create();
                        //$this->Notification->save($this->data['Notification']);
                        if($this->Notification->save($this->data['Notification']))
                        {
                          $notification_id = $this->Notification->getLastInsertId();  
                          $eve_name = $this->params['form']['title'];
         
                          //$this->notification_email($notification_id,$eve_name);
						  
						  $page="Event update";
                         // $this->send_push_notification_edit_event($user_id, $grp_mem['GroupUser']['user_id'], $latest_event_details['Event']['group_id'], $event_id, $page);
                        }
                      }

                        
                         
                      }
        
         
		 echo "<script>parent.$.fancybox.close();window.top.location.reload();</script>";
		 $this->Session->setFlash(__d("statictext", "Event Updated Successfully !!", true));
         $_SESSION['meesage_type'] = '1';
         //$this->redirect("/group/group_detail/".$this->params['form']['event_group_id']);
         //$this->redirect($this->referer());

        }
     
                   

        }
    }



    function notification_email($notification_id,$eve_name){
	
		$this->layout = "";
	 
		$sitesettings = $this->getSiteSettings();


		$condition = "(Notification.id = '".$notification_id."')";
		$this->Notification->bindModel(
		  array('belongsTo'=>array(
			  'Group'=>array('className'=>'Group','foreignKey'=>'group_id',
			  'fields'=>'Group.id,Group.group_title,Group.group_type'),
			  'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
			  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname'),
			  'Receiver'=>array('className'=>'User','foreignKey'=>'receiver_id',
			  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname')
			)
			));
		$notification_detail = $this->Notification->find('first', array('conditions'=>$condition));

		//pr($notification_detail);exit();

		if($notification_detail['Notification']['type'] == 'E')
		{

		  $group_name = $notification_detail['Group']['group_title'];
		  $sender_name = $notification_detail['Sender']['fname'].' '.$notification_detail['Sender']['lname'];
		  
		  $sender_email = $notification_detail['Sender']['email'];
		 // $sender_email = $sender_name.'<'.$notification_detail['Sender']['email'].'>';

		  $receiver_name = $notification_detail['Receiver']['fname'].' '.$notification_detail['Receiver']['lname'];
		  
		  $receiver_email = $notification_detail['Receiver']['email'];
		  //$receiver_email = $receiver_name.'<'.$notification_detail['Receiver']['email'].'>';
		  
				
				$site_url = $sitesettings['site_url']['value'];
				
				$condition = "EmailTemplate.id = '5'";
				$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
				
				$to = $receiver_email;
				$user_name = $receiver_name;

				
				$condition_event = "Event.id = '".$notification_detail['Notification']['event_id']."'";
				$event_detail = $this->Event->find("first", array('conditions' => $condition_event));

				if($event_detail['Event']['is_multiple_date'] == '1' ) { 
				$event_time =  date("F j, Y, g:i a",$event_detail['Event']['event_start_timestamp']) .'-'. date("F j, Y, g:i a",$event_detail['Event']['event_end_timestamp']);
				 } else { 
				$event_time =   date("F j, Y, g:i a",$event_detail['Event']['event_timestamp']) ;
				 } 


				 if($notification_detail['Notification']['group_type'] == 'F')
				{
					$group_type = 'PRIVATE';
				}
				else if($notification_detail['Notification']['group_type'] == 'B')
				{
					$group_type = 'BUSINESS';
				}

				$user_subject = $mailDataRS['EmailTemplate']['subject'];
				$user_subject = str_replace('[SITE NAME]', 'Grouper |', $user_subject);
						   
			 
				$user_body = $mailDataRS['EmailTemplate']['content'];
				$user_body = str_replace('[USERNAME]', $user_name, $user_body);
				$user_body = str_replace('[SENDERNAME]', $sender_name, $user_body);
				$user_body = str_replace('[EVENTNAME]', $eve_name, $user_body);
				$user_body = str_replace('[EVENTTIME]', $event_time, $user_body);
				$user_body = str_replace('[EVENTLOCATION]', $event_detail['Event']['location'], $user_body);
				$user_body = str_replace('[GROUPNAME]', $group_name, $user_body);
				$user_body = str_replace('[GROUPTYPE]', $group_type, $user_body);
								   
								 
				$user_message = stripslashes($user_body);
				
	   
			   $string = '';
			   $filepath = '';
			   $filename = '';
			   $sendCopyTo = '';
	   
	   
				$sendmail = sendmail($sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                
                     
                              
          }

    }



     function event_list()
     {

          //$this->_checkSessionUser();
          $group_id = $_REQUEST['group_id']; 
          $selectdate = $_REQUEST['total_dt']; 
          $user_id = $this->Session->read('userData.User.id');

          $this->layout = "";
        //  pr($selectdate);

          //event list
        $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND `event_date` = '".$selectdate."' UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND`event_start_date` <= '".$selectdate."' AND `event_end_date` >= '".$selectdate."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
       // pr($event_details);exit(); 
        $event_list = array(); 
		
		$condition = "Group.id = '".$group_id."'";
		$group_detail = $this->Group->find('first',array('conditions'=>$condition));
		
		$arr_group_owners= explode(',', $group_detail['Group']['group_owners']);
		if(in_array($user_id, $arr_group_owners)){
		 	$show_edit = 1;
		}
		else{
		 	$show_edit = 0;
		}
		  
        foreach($event_details as $events)
		{
			$list = array();

			$list['id'] = $events['Event']['id'];
			$list['event_name'] =  $events['Event']['title'];
			$list['desc'] =  $events['Event']['desc'];
			$list['deal_amount'] =  $events['Event']['deal_amount'];
			
			
			$list['group_id'] = $events['Event']['group_id'];
			$list['group_name'] =  $group_detail['Group']['group_title'];
			$list['show_edit'] =  $show_edit;
       		

			$list['group_type'] =  $events['Event']['group_type'];
			$list['event_type'] = $events['Event']['type'];
			$list['is_multiple_date'] = $events['Event']['is_multiple_date'];
			$condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
        	$event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
            if($events['Event']['is_multiple_date'] == '1')
              {

                $list['event_start_date_time'] =  date("jS F Y g:i a",$event_time_detail['Event']['event_start_timestamp']);
                $list['event_end_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_end_timestamp']);
              }
            else
              {
                $list['event_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_timestamp']);        

              }

		   
			$list['location'] = $events['Event']['location'];
			$list['latitude'] =  $events['Event']['latitude'];
			$list['longitude'] =  $events['Event']['longitude'];
			
			
			array_push($event_list,$list);
		}
		 
		 $this->set('event_list',$event_list);
		 $this->set('selectdate',$selectdate);
                 
                 
             // return $event_list;
               
      }



       function my_calender() 

       {

          $this->layout = "home_inner";
          $this->set('pagetitle', 'Welcome to Grouper');
          $this->_checkSessionUser();
          $user_id = $this->Session->read('userData.User.id');

          
          $date = date('Y-m-d');

          $user_id = $this->Session->read('userData.User.id');
          $selected_state_id = $this->Session->read('selected_state_id');
          $selected_city_id = $this->Session->read('selected_city_id');

          $this->set('selected_state_id',$selected_state_id);
          $this->set('selected_city_id',$selected_city_id);
		  
		  /*******************Starts************************/
		  
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

 		  
		  /******************Ends***************************/


          $con_group_detail = "GroupUser.user_id = '".$user_id."' AND GroupUser.status = '1'";		//Fetch the all groups where logged in user is either owner or member
		  $group_detail = $this->GroupUser->find('all',array('conditions'=>$con_group_detail));
		  $group = array(); 
		  foreach($group_detail as $grp)
		  {

			  array_push($group,$grp['GroupUser']['group_id']);
		  }
		  $allgroups = implode(",",$group);
       
	   	  $this->set('allgroups',$allgroups);

         // free my calender start //

      
          //event list
        $event_f_group_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `status` = '1' AND `event_date` = '".$date."' AND `group_id` IN (". $allgroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `status` = '1' AND `group_id` IN (". $allgroups .") AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
     
        $event_free_group_list = array(); 
		
		if(!empty($event_f_group_details)){
			foreach($event_f_group_details as $events)
			{
				  $list = array();
			
				  $list['id'] = $events['Event']['id'];
				  $list['event_name'] =  $events['Event']['title'];
				  $list['desc'] =  $events['Event']['desc'];
				  $list['deal_amount'] =  $events['Event']['deal_amount'];
				  
				  $condition = "Group.id = '".$events['Event']['group_id']."'";
				  $group_detail = $this->Group->find('first',array('conditions'=>$condition));
				  $list['group_id'] = $events['Event']['group_id'];
				  $list['group_name'] =  $group_detail['Group']['group_title'];
				  $arr_group_owners= explode(',', $group_detail['Group']['group_owners']);
				  if(in_array($user_id, $arr_group_owners)){
					$list['show_edit'] = 1;
				  }
				  else{
					$list['show_edit'] = 0;
				  }
				  
				  $list['group_type'] =  $events['Event']['group_type'];
				  $list['event_type'] = $events['Event']['type'];
				  $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
				  
				  $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
				  $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
				  
				  if($events['Event']['is_multiple_date'] == '1')
				  {
	
					$list['event_start_date_time'] =  date("jS F Y g:i a",$event_time_detail['Event']['event_start_timestamp']);
					$list['event_end_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_end_timestamp']);
				  }
				  else
				  {
					$list['event_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_timestamp']);
				  }
	
		   
				  $list['location'] = $events['Event']['location'];
				  $list['latitude'] =  $events['Event']['latitude'];
				  $list['longitude'] =  $events['Event']['longitude'];
		  
		  
				  array_push($event_free_group_list,$list);
			}
		}
		$this->set('event_free_group_list',$event_free_group_list);
		//$this->set('selectdate',$selectdate);
                 
       
       // free my calender end //

      // business my calender start //
        
      $event_b_group_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_date` = '".$date."' AND `group_id` IN (". $allgroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `group_id` IN (". $allgroups .") AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
	  
        $event_business_group_list = array(); 
		
		if(!empty($event_b_group_details)){
			foreach($event_b_group_details as $events){
			
				  $list = array();
			
				  $list['id'] = $events['Event']['id'];
				  $list['event_name'] =  $events['Event']['title'];
				  $list['desc'] =  $events['Event']['desc'];
				  $list['deal_amount'] =  $events['Event']['deal_amount'];
				  
				  $condition = "Group.id = '".$events['Event']['group_id']."'";
				  $group_detail = $this->Group->find('first',array('conditions'=>$condition));
				  
				  $list['group_id'] = $events['Event']['group_id'];
				  $list['group_name'] =  $group_detail['Group']['group_title'];
				  $arr_group_owners= explode(',', $group_detail['Group']['group_owners']);
				  if(in_array($user_id, $arr_group_owners)){
					$list['show_edit'] = 1;
				  }
				  else{
					$list['show_edit'] = 0;
				  }
				  $list['group_type'] =  $events['Event']['group_type'];
				  $list['event_type'] = $events['Event']['type'];
				  $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
				  
				  $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
				  $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
				  if($events['Event']['is_multiple_date'] == '1'){
	
					  $list['event_start_date_time'] =  date("jS F Y g:i a",$event_time_detail['Event']['event_start_timestamp']);
					  $list['event_end_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_end_timestamp']);
				  }
				  else{
				  
					  $list['event_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_timestamp']);
				  }
	
		   
				  $list['location'] = $events['Event']['location'];
				  $list['latitude'] =  $events['Event']['latitude'];
				  $list['longitude'] =  $events['Event']['longitude'];
		  
		  
				  array_push($event_business_group_list,$list);
			}
		}

		$this->set('event_business_group_list',$event_business_group_list);
		//$this->set('selectdate',$selectdate);  
	
	   // business my calender end //

        
    }


    function free_group_event_list(){
	   	  
          $this->layout = "";
          $this->_checkSessionUser();
          $user_id = $this->Session->read('userData.User.id');
          
          $selectdate = $_REQUEST['total_dt']; 
          $allgroups = $_REQUEST['allgroups'];
       

       // free my calender start //

      
          //event list
       /*$condition_event_list = "Event.status = '1' AND Event.group_type = 'F' AND Event.group_id IN (". $allgroups .")  AND  ((Event.event_date = '".$selectdate."') OR (Event.event_start_date <= '".$selectdate."' AND Event.event_end_date >= '".$selectdate."'))";
       $event_f_group_details = $this->Event->find('all',array('conditions'=>$condition_event_list,'order'=>'Event.id DESC'));  */

        $event_f_group_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `status` = '1' AND `event_date` = '".$selectdate."' AND `group_id` IN (". $allgroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `status` = '1' AND `group_id` IN (". $allgroups .") AND`event_start_date` <= '".$selectdate."' AND `event_end_date` >= '".$selectdate."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
     
        $event_free_group_list = array(); 
        foreach($event_f_group_details as $events)
    {
      $list = array();

      $list['id'] = $events['Event']['id'];
      $list['event_name'] =  $events['Event']['title'];
      $list['desc'] =  $events['Event']['desc'];
      $list['deal_amount'] =  $events['Event']['deal_amount'];
      
      $condition = "Group.id = '".$events['Event']['group_id']."'";
      $group_detail = $this->Group->find('first',array('conditions'=>$condition));
      
      $list['group_id'] = $events['Event']['group_id'];
      $list['group_name'] =  $group_detail['Group']['group_title'];
      $arr_group_owners= explode(',', $group_detail['Group']['group_owners']);
	  if(in_array($user_id, $arr_group_owners)){
		$list['show_edit'] = 1;
	  }
	  else{
		$list['show_edit'] = 0;
	  }
          
      $list['group_type'] =  $events['Event']['group_type'];
      $list['event_type'] = $events['Event']['type'];
      $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
        $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
        $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
          if($events['Event']['is_multiple_date'] == '1')
            {

              $list['event_start_date_time'] =  date("jS F Y g:i a",$event_time_detail['Event']['event_start_timestamp']);
              $list['event_end_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_end_timestamp']);
            }
          else
            {
              $list['event_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_timestamp']);
            }

       
      $list['location'] = $events['Event']['location'];
      $list['latitude'] =  $events['Event']['latitude'];
      $list['longitude'] =  $events['Event']['longitude'];
      
      
      array_push($event_free_group_list,$list);
    }
      // pr($event_list);exit();
     $this->set('event_free_group_list',$event_free_group_list);
     $this->set('selectdate',$selectdate);
                 
       
         // free my calender end //

    }


    function business_group_event_list(){
	
		 $this->layout = "";
         $this->_checkSessionUser();
         $user_id = $this->Session->read('userData.User.id');
          
         $selectdate = $_REQUEST['total_dt']; 
		 $allgroups = $_REQUEST['allgroups'];

       	// business my calender start //

       $event_b_group_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_date` = '".$selectdate."' AND `group_id` IN (". $allgroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `group_id` IN (". $allgroups .") AND`event_start_date` <= '".$selectdate."' AND `event_end_date` >= '".$selectdate."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
                
     
        $event_business_group_list = array(); 
        foreach($event_b_group_details as $events)
    {
      $list = array();

      $list['id'] = $events['Event']['id'];
      $list['event_name'] =  $events['Event']['title'];
      $list['desc'] =  $events['Event']['desc'];
      $list['deal_amount'] =  $events['Event']['deal_amount'];
      
      $condition = "Group.id = '".$events['Event']['group_id']."'";
      $group_detail = $this->Group->find('first',array('conditions'=>$condition));
      
      $list['group_id'] = $events['Event']['group_id'];
      $list['group_name'] =  $group_detail['Group']['group_title'];
      $arr_group_owners= explode(',', $group_detail['Group']['group_owners']);
	  if(in_array($user_id, $arr_group_owners))
	  {
	  	$list['show_edit'] = 1;
	  }
	  else{
	  	$list['show_edit'] = 0;
	  }
          
      $list['group_type'] =  $events['Event']['group_type'];
      $list['event_type'] = $events['Event']['type'];
      $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
     
      $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
      $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
            if($events['Event']['is_multiple_date'] == '1')
                {

                  $list['event_start_date_time'] =  date("jS F Y g:i a",$event_time_detail['Event']['event_start_timestamp']);
                  $list['event_end_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_end_timestamp']);
                }
            else
                {
                  $list['event_date_time'] = date("jS F Y g:i a",$event_time_detail['Event']['event_timestamp']);        

                }

       
      $list['location'] = $events['Event']['location'];
      $list['latitude'] =  $events['Event']['latitude'];
      $list['longitude'] =  $events['Event']['longitude'];
      
      
      array_push($event_business_group_list,$list);
    }
      // pr($event_list);exit();
     $this->set('event_business_group_list',$event_business_group_list);
     $this->set('selectdate',$selectdate);
                 
       
         // business my calender end //

    }
	
	/*---------------------------  Push Notification to Group Users After creating the Event starts    -----------------------*/
	
	
	function send_push_notification_event($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $event_id=NULL,$page){
	
	    //$this->_checkSession();
		$condition_receiver = "User.id = '".$receiver_id."'";
        $user_details= $this->User->find('first',array('conditions' => $condition_receiver));
		if($user_details['User']['device_token']!='')
		{
			$condition_sender = "User.id = '".$sender_id."'";
			$sender_details= $this->User->find('first',array('conditions' => $condition_sender));
			
			$condition_group = "Group.id = '".$group_id."'";
			$group_details= $this->Group->find('first',array('conditions' => $condition_group));
			
			if($group_details['Group']['group_type'] == 'F' ){
				$group_type= 'Private';
			}
			else{
				$group_type= 'Business';
			}
			
			$condition_event = "Event.id = '".$event_id."'";
			$event_details= $this->Event->find('first',array('conditions' => $condition_event));
			
			if($event_details['Event']['is_multiple_date'] == '1' ) {
				
				$timeline= date("F j, Y, g:i a",$event_details['Event']['event_start_timestamp']).' - '.date("F j, Y, g:i a",$event_details['Event']['event_end_timestamp']);
			}
			else{
				$timeline= date("F j, Y, g:i a",$event_details['Event']['event_timestamp']);
			}
				
			$message= "Event Reminder for ".$group_type." group  : ".ucfirst($group_details['Group']['group_title']);
			$message.="\nEvent Title : ".ucfirst($event_details['Event']['title']);
			$message.="\nEvent Time : ".$timeline;
			$message.="\nLocation : ".ucfirst($event_details['Event']['location']);
			
			###################		Get the Notification Counter Starts 	################
				
			$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
	  
			$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
			
			###################		Get the Notification Counter Starts 	################
			
		  	$this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
		}
   }
   
    /*---------------------------  Push Notification to Group Users After creating the Event ends   -----------------------*/
	
	
	/*---------------------------  Push Notification to Group Users After editing the Event starts    -----------------------*/
    
    
    function send_push_notification_edit_event($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $event_id=NULL,$page){
    
        //$this->_checkSession();
        $condition_receiver = "User.id = '".$receiver_id."'";
        $user_details= $this->User->find('first',array('conditions' => $condition_receiver));
        if($user_details['User']['device_token']!='')
        {
            $condition_sender = "User.id = '".$sender_id."'";
            $sender_details= $this->User->find('first',array('conditions' => $condition_sender));
            
            $condition_group = "Group.id = '".$group_id."'";
            $group_details= $this->Group->find('first',array('conditions' => $condition_group));
            
            if($group_details['Group']['group_type'] == 'F' ){
                $group_type= 'Private';
            }
            else{
                $group_type= 'Business';
            }
            
            $condition_event = "Event.id = '".$event_id."'";
            $event_details= $this->Event->find('first',array('conditions' => $condition_event));
            
            if($event_details['Event']['is_multiple_date'] == '1' ) {
                
                $timeline= date("F j, Y, g:i a",$event_details['Event']['event_start_timestamp']).' - '.date("F j, Y, g:i a",$event_details['Event']['event_end_timestamp']);
            }
            else{
                $timeline= date("F j, Y, g:i a",$event_details['Event']['event_timestamp']);
            }
                
            $message= "Event Reminder for ".$group_type." group  : ".ucfirst($group_details['Group']['group_title']);
            $message.="\nEvent Title : ".ucfirst($event_details['Event']['title']);
            $message.="\nEvent Time : ".$timeline;
            $message.="\nLocation : ".ucfirst($event_details['Event']['location']);
			
			###################		Get the Notification Counter Starts 	################
				
			$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
	  
			$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
			
			###################		Get the Notification Counter Starts 	################
            
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
        }
   }
   
    /*---------------------------  Push Notification to Group Users After editing the Event ends   -----------------------*/
	
	
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
