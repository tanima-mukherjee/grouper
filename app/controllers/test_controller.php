<?php
class TestController extends AppController {

    var $name = 'Test';
    var $uses = array('Category','State','City','Group','User','EmailTemplate','Event','GroupMessage','GroupMessageReply','GroupUser','GroupImage','GroupDoc','Video');
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
	  
	  
	  
	  
	  function group_detail($group_id) {
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        //$this->_checkSessionUser();

         $this->set('group_id',$group_id);
         $date = date('Y-m-d');
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);
        $authorized_member = '0';
		/************************Starts**************************/
		
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

		/************************Ends**************************/
        /************************Category details for edit group starts**************************/
        $condition = "Category.status ='1'";
        $all_categories = $this->Category->find("all",array('conditions'=>$condition,'order' => 'Category.id DESC'));
        $this->set('all_categories', $all_categories);
        /************************Category details for edit group ends**************************/
       
        /***************   Group Detail Starts    **************/
		
        $condition = "Group.status= '1'  AND Group.id= '".$group_id."'";
        $group_details = $this->Group->find("first",array('conditions'=>$condition));
      	$this->set('group_details',$group_details);
		
		$arr_group_owners= explode(',', $group_details['Group']['group_owners']);
		if(in_array($user_id, $arr_group_owners)){
			$show_edit=1;
		}
		else{
			$show_edit=0;
		}
        $this->set('show_edit',$show_edit);
		/***************   Group Detail Ends    **************/

		/***************   Group Member Type Starts    **************/
      	$group_member_type = 'nonmember';

      	$condition_group_owner_count = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_details['Group']['id']."'  AND GroupUser.user_id = '".$user_id."' AND GroupUser.user_type= 'O'";	//Check the logged in user is owner or not 
      	$group_owner_count = $this->GroupUser->find("count",array('conditions'=>$condition_group_owner_count));
		if($group_owner_count > 0){
			$group_member_type = 'owner';
            $group_user_type = 'O';
		}

      	$condition_group_member_count = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_details['Group']['id']."'  AND GroupUser.user_id = '".$user_id."' AND GroupUser.user_type= 'M'";	//Check the logged in user is member or not 
      	$arr_group_member_count = $this->GroupUser->find("first",array('conditions'=>$condition_group_member_count));
      	if(!empty($arr_group_member_count))
      	{    

            if($arr_group_member_count['GroupUser']['can_post_topic'] == '1')
            {
                $authorized_member = '1';
            }

        	$group_member_type = 'member';
            $group_user_type = 'M';
            $is_notification_stop = $arr_group_member_count['GroupUser']['is_notification_stop'];
            $this->set('is_notification_stop',$is_notification_stop);
      	}
      	//pr($group_member_type);exit();
      	$this->set('group_member_type',$group_member_type);
        $this->set('authorized_member',$authorized_member);
        $this->set('group_user_type',$group_user_type);
		
		/***************   Group Member Type Ends    **************/

		/***************   Group Image Starts    **************/
		 
        $condn = "GroupImage.group_id = '".$group_id."' AND GroupImage.status = '1'";
        $photo_list = $this->GroupImage->find('all',array('conditions'=>$condn));
        $this->set('photo_list',$photo_list);
		
		/***************   Group Image Ends    **************/

		/***************   Group Doc Starts    **************/
		
        $condn = "GroupDoc.group_id = '".$group_id."' AND GroupDoc.status = '1'";
        $doc_list = $this->GroupDoc->find('all',array('conditions'=>$condn));
        //pr($doc_list);exit();
        $this->set('doc_list',$doc_list);
		
		/***************   Group Doc Ends    **************/

		/***************   Group Video Starts    **************/
		
        $condn = "Video.group_id = '".$group_id."' AND Video.status = '1'";
        $video_list = $this->Video->find('all',array('conditions'=>$condn));
        // pr($video_list);exit();
        $this->set('video_list',$video_list);
		
		/***************   Group Video Ends    **************/

        /****************** Group Message Start ********************/
        $con_grp_msg = "GroupMessage.group_id = '".$group_id."'";
        $ArGroupMessageCount = $this->GroupMessage->find('count',array('conditions' => $con_grp_msg));
        if($ArGroupMessageCount>0){
        
            $limit_f = 12;
            $lastpage_f = ceil($ArGroupMessageCount/$limit_f);
            $start_f=0;
            $page_f = 1;
            $prev_f = $page_f - 1;
            $next_f = $page_f + 1;
            $lpm1_f = $lastpage_f - 1;
            
            $this->set('limit_f',$limit_f); 
            $this->set('lastpage_f',$lastpage_f); 
            $this->set('start_f',$start_f); 
            $this->set('page_f',$page_f); 
            $this->set('prev_f',$prev_f); 
            $this->set('next_f',$next_f); 
            $this->set('lpm1_f',$lpm1_f);
            
            $this->GroupMessage->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.fname,User.lname,User.image'))),false);
            $this->GroupMessage->bindModel(array('hasMany' => array('GroupMessageReply' => array('foreignKey' => 'message_id'))),false);
            $ArGroupMessage = $this->GroupMessage->find('all',array('conditions' => $con_grp_msg, 'offset' => $start_f, 'limit' => $limit_f, 'order' => 'GroupMessage.id DESC'));
            //pr($ArGroupMessage);exit;
            $this->set('ArGroupMessage',$ArGroupMessage);

            //$this->set('all_free_groups',$all_free_groups); 
        }
        else
        {
            //$this->set('all_free_groups',''); 
            $this->set('ArGroupMessage', '');
        } 


        /****************** Group Message End ********************/

        
        /***********************      event list starts       **********************/
        $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
         $event_list = array(); 
         foreach($event_details as $events){
		 
			$list = array();

			$list['id'] = $events['Event']['id'];
			$list['show_edit'] = $show_edit;
			$list['event_name'] =  $events['Event']['title'];
			$list['desc'] =  $events['Event']['desc'];
			$list['created_by_owner_id'] =  $events['Event']['created_by_owner_id'];
			$list['deal_amount'] =  $events['Event']['deal_amount'];
			$list['group_id'] = $events['Event']['group_id'];
			$list['group_name'] =  $group_details['Group']['group_title'];
			$list['group_type'] =  $events['Event']['group_type'];
			$list['event_type'] = $events['Event']['type'];
			$list['is_multiple_date'] = $events['Event']['is_multiple_date'];
			
			$condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
			$event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
			if($events['Event']['is_multiple_date'] == '1'){

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
		// pr($event_list);exit();
	 	 $this->set('event_list',$event_list);

		/***********************      event list ends       **********************/

  }


  function post_all_reply(){
    
        $this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');

        $group_id = $_REQUEST['group_id'];

        $this->set('group_id',$group_id);
        
        $con_grp_msg = "GroupMessage.group_id = '".$group_id."'";
        $ArGroupMessageCount = $this->GroupMessage->find('count',array('conditions' => $con_grp_msg));
        //echo $ArGroupMessageCount;
        if($ArGroupMessageCount>0){
        
            $limit = 12;
            $lastpage = ceil($ArGroupMessageCount/$limit);
            $start=($_REQUEST['page'] - 1) * $limit;
            $page = $_REQUEST['page'];
            $prev = $page - 1;
            $next = $page + 1;
            $lpm1 = $lastpage - 1;
            
            $this->set('limit',$limit); 
            $this->set('lastpage',$lastpage); 
            $this->set('start',$start); 
            $this->set('page',$page); 
            $this->set('prev',$prev); 
            $this->set('next',$next); 
            $this->set('lpm1',$lpm1);
            
            $this->GroupMessage->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.fname,User.lname,User.image'))),false);
            $this->GroupMessage->bindModel(array('hasMany' => array('GroupMessageReply' => array('foreignKey' => 'message_id'))),false);
            $ArGroupMessage = $this->GroupMessage->find('all',array('conditions' => $con_grp_msg, 'offset' => $start, 'limit' => $limit, 'order' => 'GroupMessage.id DESC'));
            //pr($ArGroupMessage);exit;
            $this->set('ArGroupMessage',$ArGroupMessage);

            //$this->set('all_free_groups',$all_free_groups); 
        }
        else
        {
            //$this->set('all_free_groups',''); 
            $this->set('ArGroupMessage', '');
        } 

        //pr($ArGroupMessage);
    }
	  
	  
	  
	  





	
}?>
