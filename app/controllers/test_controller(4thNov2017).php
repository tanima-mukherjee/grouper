<?php
class TestController extends AppController {

    var $name = 'Test';
    var $uses = array('Category','State','City','Group','User','EmailTemplate','Event');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    
    
        
    

        function index()
    {

          $this->layout = "home_inner";
          $this->set('pagetitle', 'Customer Dashboard');

          //$group_id = $_REQUEST['group_id']; 
         // $selectdate = $_REQUEST['total_dt']; 
          $group_id = '4'; 
          $selectdate = '2017-12-02'; 


          $user_id = $this->Session->read('userData.User.id');
          //pr($selectdate);

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
                 
                        
    }

    function test_event_list()
     {

          //$this->_checkSessionUser();
         // $group_id = $_REQUEST['group_id'];
     	//echo ('dsfsdf');exit;
         $group_id = '4'; 
          $selectdate = $_REQUEST['total_dt']; 
          $user_id = $this->Session->read('userData.User.id');

          $this->layout = "";
        // pr($selectdate);

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


      function detail_event($event_id)
      {
			//Configure::write('debug',3);
			$this->layout = ""; 
			$response = array();
			$defaultconfig = $this->getDefaultConfig();      
			$base_url = $defaultconfig['base_url'];
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
						
			
		    $condition_event_detail = "Event.id = '".$event_id."'";
		    $this->Event->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id','fields'=>'Group.id,Group.group_title'))),false);
            $event_detail = $this->Event->find('first',array('conditions'=>$condition_event_detail)); 

		
					$this->set('event_detail',$event_detail);
				    $this->set('session_user_id',$session_user_id); 
			
		 
	  }
	  
	  
	  
	  
	  
	  
	  
	  
	  





	
}?>
