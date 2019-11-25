<?php
class EventController extends AppController {

    var $name = 'Event';
    var $uses = array('Event','State','City','Group','GroupUser','User');
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
                
                  $this->Session->setFlash(__d("statictext", "Event request sent successfully", true));
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


     function event_list()
     {

          $this->_checkSessionUser();
          $group_id = $_REQUEST['group_id']; 
          $selectdate = $_REQUEST['total_dt']; 

          $this->layout = "";
        //  pr($selectdate);

          //event list
        $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND `event_date` = '".$selectdate."' UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND`event_start_date` <= '".$selectdate."' AND `event_end_date` >= '".$selectdate."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
       // pr($event_details);exit(); 
        $event_list = array(); 
        foreach($event_details as $events)
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
			$list['group_type'] =  $events['Event']['group_type'];
			$list['event_type'] = $events['Event']['type'];
			$list['is_multiple_date'] = $events['Event']['is_multiple_date'];
			$condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
        $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
            if($events['Event']['is_multiple_date'] == '1')
              {

                $list['event_start_date_time'] =  date("jS F Y ga",$event_time_detail['Event']['event_start_timestamp']);
                $list['event_end_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_end_timestamp']);
              }
            else
              {
                $list['event_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_timestamp']);        

              }

		   
			$list['location'] = $events['Event']['location'];
			$list['latitude'] =  $events['Event']['latitude'];
			$list['longitude'] =  $events['Event']['longitude'];
			
			
			array_push($event_list,$list);
		}
	  	// pr($event_list);exit();
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
				  
				  $list['group_type'] =  $events['Event']['group_type'];
				  $list['event_type'] = $events['Event']['type'];
				  $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
				  
				  $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
				  $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
				  
				  if($events['Event']['is_multiple_date'] == '1')
				  {
	
					$list['event_start_date_time'] =  date("jS F Y ga",$event_time_detail['Event']['event_start_timestamp']);
					$list['event_end_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_end_timestamp']);
				  }
				  else
				  {
					$list['event_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_timestamp']);
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
				  $list['group_type'] =  $events['Event']['group_type'];
				  $list['event_type'] = $events['Event']['type'];
				  $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
				  
				  $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
				  $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
				  if($events['Event']['is_multiple_date'] == '1'){
	
					  $list['event_start_date_time'] =  date("jS F Y ga",$event_time_detail['Event']['event_start_timestamp']);
					  $list['event_end_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_end_timestamp']);
				  }
				  else{
				  
					  $list['event_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_timestamp']);
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
      $list['group_type'] =  $events['Event']['group_type'];
      $list['event_type'] = $events['Event']['type'];
      $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
        $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
        $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
          if($events['Event']['is_multiple_date'] == '1')
            {

              $list['event_start_date_time'] =  date("jS F Y ga",$event_time_detail['Event']['event_start_timestamp']);
              $list['event_end_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_end_timestamp']);
            }
          else
            {
              $list['event_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_timestamp']);
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
      $list['group_type'] =  $events['Event']['group_type'];
      $list['event_type'] = $events['Event']['type'];
      $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
     
      $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
      $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
            if($events['Event']['is_multiple_date'] == '1')
                {

                  $list['event_start_date_time'] =  date("jS F Y ga",$event_time_detail['Event']['event_start_timestamp']);
                  $list['event_end_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_end_timestamp']);
                }
            else
                {
                  $list['event_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_timestamp']);        

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

    
}?>
