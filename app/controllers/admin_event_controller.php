<?php
/**
 * Admin Dashboard Controller
 */
class AdminEventController extends AppController {

  var $name = 'AdminEvent';
  var $uses = array('Admin','Event','User','Category','Group','State','City','TerritoryAssign','Notification','EmailTemplate');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array();


    function send_message() 
    {
           $adminData = $this->Session->read('adminData');
           if(empty($adminData))
              $this->redirect('/admins/login');

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $sitesettings = $this->getSiteSettings();

            $event_id = $_REQUEST['event_id'];
            $message = $_REQUEST['message'];

            $conditions ="Event.id = '".$event_id."'";
            $ArEventDetails = $this->Event->find('first',array('conditions' => $conditions));

            $receiver_id = $ArEventDetails['Event']['created_by_owner_id'];
            $this->data['Notification']['sender_id'] = '0';
            $this->data['Notification']['type'] = 'P';
            $this->data['Notification']['group_id'] = '0';
            $this->data['Notification']['message'] = addslashes($message);              
            $this->data['Notification']['receiver_id'] = $receiver_id;
            $this->data['Notification']['receiver_type'] = 'NGM';
            $this->data['Notification']['sender_type'] = 'NGM';
            $this->data['Notification']['is_read'] = 0;
            $this->data['Notification']['is_receiver_accepted'] = 2;
            $this->data['Notification']['is_reversed_notification'] = 0;
            $this->data['Notification']['status'] = 1;
            
            $this->Notification->create();
            if($this->Notification->save($this->data['Notification']))
            {

                  $condition_receiver_detail = " User.id = '".$receiver_id."'";
                  $receiver_details = $this->User->find('first', array('conditions'=>$condition_receiver_detail));
                  
                  $user_name = $receiver_details['User']['fname'].' '.$receiver_details['User']['lname']; 
                  $admin_sender_email = $sitesettings['site_email']['value'];
                  $site_url = $sitesettings['site_url']['value'];
                  $sender_name = 'Administrator';
                  $message = stripslashes($message);

                  $condition = "EmailTemplate.id = '19'";
                  $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                  $to = $receiver_details['User']['email'];

                  $user_subject = $mailDataRS['EmailTemplate']['subject'];
                  $user_subject = str_replace('[SITE NAME]', 'Grouper | Admin Message', $user_subject);

                  $user_body = $mailDataRS['EmailTemplate']['content'];
                  $user_body = str_replace('[USERNAME]', $user_name, $user_body);
                  $user_body = str_replace('[SENDERNAME]', $sender_name, $user_body);
                  $user_body = str_replace('[MESSAGE]', $message, $user_body);

                  $user_message = stripslashes($user_body);
                  
                  $string = '';
                  $filepath = '';
                  $filename = '';
                  $sendCopyTo = '';

                  $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$message,$string,$filepath,$filename,$sendCopyTo);                 
                  // exit;
                  if($sendmail)
                  {

                      $page="Push message from Admin";
                      $message= "Admin has sent you push notification \n".$message; 

                      $this->send_push_notification($message,$receiver_details['User']['device_token'],$page);

                      echo 'ok';
                  }
                  else
                  {
                      echo 'err';
                  }
            }
            else
            {
                echo 'err';
            }

            exit;
    }
	
	function send_push_notification($message=NULL,$registatoin_ids=NULL,$page=NULL){
        /*$message = 'Sajjad Mistri invited you to join Private group Alovera';
        $registatoin_ids ='dKH2FWX6DtQ:APA91bHchG6PmkkWWic1S_BcBwiYPMt1dFjhlbilgQ1wXv0symPqwsNqHkFLBEClgb88YMa92weHi-JDefahsIESD3Ib5Wg2TKEeprwAjQYxsX9Nej60ghr8czb_qnisnSY3WmBZtusD';
        $page = 'Group Joining Invitation To Friends';
*/
        //$this->_checkSession();
        $messageArr = array();       
        $messageArr['to']  =  $registatoin_ids;       
        $messageArr['notification']['title']  =  $page;     
        $messageArr['notification']['body']   =  $message;
      
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


    
    function event_list() {
        
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
      	$this->set('pageTitle', 'Event List');
      
		$selected_state_id = '';
		$selected_city_id = '';
		$total_business_group = 0;
		$total_private_group = 0;
		$total_private_organisation_group = 0;
		$search_by = '';


      if($this->Session->read('admin_type') == 'TA')
      {


          $territory_id = $this->Session->read('adminData.Admin.id');
          $con = "TerritoryAssign.territory_id = '".$territory_id."'";
          $fields = array('TerritoryAssign.assign_state','TerritoryAssign.assign_city');
          $ArTerritoryStateCity = $this->TerritoryAssign->find('all',array('conditions' => $con, 'fields' => $fields));
          
          if(!empty($ArTerritoryStateCity))
          {
            $ar_states = array();
            $ar_city = array();
            foreach ($ArTerritoryStateCity as $key => $value) {
                array_push($ar_states, $value['TerritoryAssign']['assign_state']);
                array_push($ar_city, $value['TerritoryAssign']['assign_city']);
            }

            $ar_states = array_unique($ar_states);
            $ar_city = array_unique($ar_city);


            $str_states = implode(',', $ar_states);
            $str_city = implode(',', $ar_city);

             $fields = array('Group.id');
             $conditions_group = "Group.state_id IN (".$str_states.") AND Group.city_id IN (".$str_city.") ";
             $all_groups = $this->Group->find('list',array('conditions' => $conditions_group, 'fields' => $fields)); 
             

             $str_groups = implode(',', $all_groups);
          }

          $conditions_state = "State.id IN (".$str_states.") AND State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          $con = "Event.group_id IN (".$str_groups.")";
          $all_events = $this->Event->find('all',array('conditions' => $con,'order' => 'Event.id DESC'));  

          //pr($all_events);exit;

          $this->set('city_list',$city_list); 
          $this->set('ArState', $ArState);
      }
      else
      {
          $conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));


          $this->Event->bindModel(array('belongsTo'=>array(
          'Group'=>array('foreignKey'=>'group_id',
          'fields'=>'Group.id,Group.group_title')
          )));
          $this->Event->bindModel(array('belongsTo'=>array(
          'User'=>array('foreignKey'=>'created_by_owner_id',
          'fields'=>'User.id,User.fname,User.lname')
          )));
          $this->Event->bindModel(array('belongsTo'=>array(
          'Category'=>array('foreignKey'=>'category_id',
          'fields'=>'Category.id,Category.title')
          )));
          $all_events = $this->Event->find('all',array('order' => 'Event.id DESC'));  


          $this->set('city_list',$city_list); 
          $this->set('ArState', $ArState);
      }
      

      /*$conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
      $ArState = $this->State->find('all', array('conditions' => $conditions_state));*/

      if(!empty($this->params['form']))
      {
        if($this->params['form']['group_type_select'] != '' && $this->params['form']['state_id'] != '' && $this->params['form']['index_city_id'] != '')
        {

            $search_by = 'tsc';
            $conditions_group = "Group.city_id = '".$this->params['form']['index_city_id']."' AND Group.state_id = '".$this->params['form']['state_id']."' ";
              $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
              // $str_group_owners= implode(',', $all_groups);
              $group = array(); 
              foreach($all_groups as $grp)
              {

              array_push($group,$grp['Group']['id']);
              }
              $allstatecitygroups = implode(",",$group);

              if(!empty($allstatecitygroups))
              {
              $condition_of_events = "Event.group_type = '".$this->params['form']['group_type_select']."' AND Event.group_id  IN (".$allstatecitygroups.") ";
              $this->Event->bindModel(array('belongsTo'=>array(
              'Group'=>array('foreignKey'=>'group_id',
              'fields'=>'Group.id,Group.group_title')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'User'=>array('foreignKey'=>'created_by_owner_id',
              'fields'=>'User.id,User.fname,User.lname')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'Category'=>array('foreignKey'=>'category_id',
              'fields'=>'Category.id,Category.title')
              )));

              $all_events = $this->Event->find('all',array('conditions'=>$condition_of_events,'order' => 'Event.id DESC')); 
              }
              else
              {
              $all_events = array();
              }

              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];

              $business_group = array();
              $private_group = array();
              $private_organisation_group = array();

              foreach ($all_events as $key => $value) {
              if($value['Event']['group_type'] == 'B')
              {
              array_push($business_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'F')
              {
              array_push($private_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'PO')
              {
              array_push($private_organisation_group, $value['Event']['id']);
              }
              }
              $total_business_group = count($business_group);
              $total_private_group = count($private_group);
              $total_private_organisation_group = count($private_organisation_group);


              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        }

        if($this->params['form']['group_type_select'] != '' && $this->params['form']['state_id'] != '' && $this->params['form']['index_city_id'] == '')
        {
              $search_by = 'ts';
              $conditions_group = "Group.state_id = '".$this->params['form']['state_id']."' ";
              $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
              // $str_group_owners= implode(',', $all_groups);
              $group = array(); 
              foreach($all_groups as $grp)
              {

              array_push($group,$grp['Group']['id']);
              }
              $allstatecitygroups = implode(",",$group);

              if(!empty($allstatecitygroups))
              {
              $condition_of_events = "Event.group_type = '".$this->params['form']['group_type_select']."' AND Event.group_id  IN (".$allstatecitygroups.") ";
              $this->Event->bindModel(array('belongsTo'=>array(
              'Group'=>array('foreignKey'=>'group_id',
              'fields'=>'Group.id,Group.group_title')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'User'=>array('foreignKey'=>'created_by_owner_id',
              'fields'=>'User.id,User.fname,User.lname')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'Category'=>array('foreignKey'=>'category_id',
              'fields'=>'Category.id,Category.title')
              )));

              $all_events = $this->Event->find('all',array('conditions'=>$condition_of_events,'order' => 'Event.id DESC')); 
              }
              else
              {
              $all_events = array();
              }

              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];

              $business_group = array();
              $private_group = array();
              $private_organisation_group = array();

              foreach ($all_events as $key => $value) {
              if($value['Event']['group_type'] == 'B')
              {
              array_push($business_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'F')
              {
              array_push($private_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'PO')
              {
              array_push($private_organisation_group, $value['Event']['id']);
              }
              }
              $total_business_group = count($business_group);
              $total_private_group = count($private_group);
              $total_private_organisation_group = count($private_organisation_group);

              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        }

        if($this->params['form']['group_type_select'] != '' && $this->params['form']['state_id'] == '' && $this->params['form']['index_city_id'] != '')
        {

              $search_by = 'tc';
              $conditions_group = "Group.city_id = '".$this->params['form']['index_city_id']."'";
              $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
              // $str_group_owners= implode(',', $all_groups);
              $group = array(); 
              foreach($all_groups as $grp)
              {

              array_push($group,$grp['Group']['id']);
              }
              $allstatecitygroups = implode(",",$group);

              if(!empty($allstatecitygroups))
              {
              $condition_of_events = "Event.group_type = '".$this->params['form']['group_type_select']."' AND Event.group_id  IN (".$allstatecitygroups.") ";
              $this->Event->bindModel(array('belongsTo'=>array(
              'Group'=>array('foreignKey'=>'group_id',
              'fields'=>'Group.id,Group.group_title')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'User'=>array('foreignKey'=>'created_by_owner_id',
              'fields'=>'User.id,User.fname,User.lname')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'Category'=>array('foreignKey'=>'category_id',
              'fields'=>'Category.id,Category.title')
              )));

              $all_events = $this->Event->find('all',array('conditions'=>$condition_of_events,'order' => 'Event.id DESC')); 
              }
              else
              {
              $all_events = array();
              }

              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];

              $business_group = array();
              $private_group = array();
              $private_organisation_group = array();

              foreach ($all_events as $key => $value) {
              if($value['Event']['group_type'] == 'B')
              {
              array_push($business_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'F')
              {
              array_push($private_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'PO')
              {
              array_push($private_organisation_group, $value['Event']['id']);
              }
              }
              $total_business_group = count($business_group);
              $total_private_group = count($private_group);
              $total_private_organisation_group = count($private_organisation_group);


              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        }

        if($this->params['form']['group_type_select'] == '' && $this->params['form']['state_id'] != '' && $this->params['form']['index_city_id'] != '')
        {
              $search_by = 'sc';
              $conditions_group = "Group.city_id = '".$this->params['form']['index_city_id']."' AND Group.state_id = '".$this->params['form']['state_id']."' ";
              $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
              // $str_group_owners= implode(',', $all_groups);
              $group = array(); 
              foreach($all_groups as $grp)
              {

              array_push($group,$grp['Group']['id']);
              }
              $allstatecitygroups = implode(",",$group);

              if(!empty($allstatecitygroups))
              {
              $condition_of_events = "Event.group_id  IN (".$allstatecitygroups.") ";
              $this->Event->bindModel(array('belongsTo'=>array(
              'Group'=>array('foreignKey'=>'group_id',
              'fields'=>'Group.id,Group.group_title')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'User'=>array('foreignKey'=>'created_by_owner_id',
              'fields'=>'User.id,User.fname,User.lname')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'Category'=>array('foreignKey'=>'category_id',
              'fields'=>'Category.id,Category.title')
              )));

              $all_events = $this->Event->find('all',array('conditions'=>$condition_of_events,'order' => 'Event.id DESC')); 
              }
              else
              {
              $all_events = array();
              }

              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];

              $business_group = array();
              $private_group = array();
              $private_organisation_group = array();

              foreach ($all_events as $key => $value) {
              if($value['Event']['group_type'] == 'B')
              {
              array_push($business_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'F')
              {
              array_push($private_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'PO')
              {
              array_push($private_organisation_group, $value['Event']['id']);
              }
              }
              $total_business_group = count($business_group);
              $total_private_group = count($private_group);
              $total_private_organisation_group = count($private_organisation_group);


              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        }

        if($this->params['form']['group_type_select'] != '' && $this->params['form']['state_id'] == '' && $this->params['form']['index_city_id'] == '')
        {
              $search_by = 't';
              $condition_of_events = "Event.group_type = '".$this->params['form']['group_type_select']."'";
              $this->Event->bindModel(array('belongsTo'=>array(
              'Group'=>array('foreignKey'=>'group_id',
              'fields'=>'Group.id,Group.group_title')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'User'=>array('foreignKey'=>'created_by_owner_id',
              'fields'=>'User.id,User.fname,User.lname')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'Category'=>array('foreignKey'=>'category_id',
              'fields'=>'Category.id,Category.title')
              )));

              $all_events = $this->Event->find('all',array('conditions'=>$condition_of_events,'order' => 'Event.id DESC')); 
              

              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];

              $business_group = array();
              $private_group = array();
              $private_organisation_group = array();

              foreach ($all_events as $key => $value) {
              if($value['Event']['group_type'] == 'B')
              {
              array_push($business_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'F')
              {
              array_push($private_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'PO')
              {
              array_push($private_organisation_group, $value['Event']['id']);
              }
              }
              $total_business_group = count($business_group);
              $total_private_group = count($private_group);
              $total_private_organisation_group = count($private_organisation_group);


              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        }

        if($this->params['form']['group_type_select'] == '' && $this->params['form']['state_id'] != '' && $this->params['form']['index_city_id'] == '')
        {

              $search_by = 's';
              $conditions_group = "Group.state_id = '".$this->params['form']['state_id']."' ";
              $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
              // $str_group_owners= implode(',', $all_groups);
              $group = array(); 
              foreach($all_groups as $grp)
              {

              array_push($group,$grp['Group']['id']);
              }
              $allstatecitygroups = implode(",",$group);

              if(!empty($allstatecitygroups))
              {
              $condition_of_events = "Event.group_id  IN (".$allstatecitygroups.") ";
              $this->Event->bindModel(array('belongsTo'=>array(
              'Group'=>array('foreignKey'=>'group_id',
              'fields'=>'Group.id,Group.group_title')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'User'=>array('foreignKey'=>'created_by_owner_id',
              'fields'=>'User.id,User.fname,User.lname')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'Category'=>array('foreignKey'=>'category_id',
              'fields'=>'Category.id,Category.title')
              )));

              $all_events = $this->Event->find('all',array('conditions'=>$condition_of_events,'order' => 'Event.id DESC')); 
              }
              else
              {
              $all_events = array();
              }

              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];

              $business_group = array();
              $private_group = array();
              $private_organisation_group = array();

              foreach ($all_events as $key => $value) {
              if($value['Event']['group_type'] == 'B')
              {
              array_push($business_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'F')
              {
              array_push($private_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'PO')
              {
              array_push($private_organisation_group, $value['Event']['id']);
              }
              }
              $total_business_group = count($business_group);
              $total_private_group = count($private_group);
              $total_private_organisation_group = count($private_organisation_group);


              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        }

        if($this->params['form']['group_type_select'] == '' && $this->params['form']['state_id'] == '' && $this->params['form']['index_city_id'] != '')
        {

              $search_by = 'c';
              $conditions_group = "Group.city_id = '".$this->params['form']['index_city_id']."'";
              $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
              // $str_group_owners= implode(',', $all_groups);
              $group = array(); 
              foreach($all_groups as $grp)
              {

              array_push($group,$grp['Group']['id']);
              }
              $allstatecitygroups = implode(",",$group);

              if(!empty($allstatecitygroups))
              {
              $condition_of_events = "Event.group_id  IN (".$allstatecitygroups.") ";
              $this->Event->bindModel(array('belongsTo'=>array(
              'Group'=>array('foreignKey'=>'group_id',
              'fields'=>'Group.id,Group.group_title')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'User'=>array('foreignKey'=>'created_by_owner_id',
              'fields'=>'User.id,User.fname,User.lname')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'Category'=>array('foreignKey'=>'category_id',
              'fields'=>'Category.id,Category.title')
              )));

              $all_events = $this->Event->find('all',array('conditions'=>$condition_of_events,'order' => 'Event.id DESC')); 
              }
              else
              {
              $all_events = array();
              }

              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];

              $business_group = array();
              $private_group = array();
              $private_organisation_group = array();

              foreach ($all_events as $key => $value) {
              if($value['Event']['group_type'] == 'B')
              {
              array_push($business_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'F')
              {
              array_push($private_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'PO')
              {
              array_push($private_organisation_group, $value['Event']['id']);
              }
              }
              $total_business_group = count($business_group);
              $total_private_group = count($private_group);
              $total_private_organisation_group = count($private_organisation_group);


              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        }

      }
        

      if(isset($this->params['form']['group_type_select']) && $this->params['form']['group_type_select']!=''){
      $this->set('groupt_select',$this->params['form']['group_type_select']);
      }else{
       $this->set('groupt_select','0'); 
      }

      if(isset($this->params['form']['state_id']) && $this->params['form']['state_id']!=''){
      $this->set('state_id',$this->params['form']['state_id']);
      }else{
       $this->set('state_id','0'); 
      }

      if(isset($this->params['form']['index_city_id']) && $this->params['form']['index_city_id']!=''){
      $this->set('city_id',$this->params['form']['index_city_id']);
      }else{
       $this->set('city_id','0'); 
      }

      $this->set('city_list',$city_list); 
      $this->set('ArState', $ArState);
      $this->set('total_business_group',$total_business_group);
      $this->set('total_private_group',$total_private_group);
      $this->set('total_private_organisation_group',$total_private_organisation_group);
      $this->set('all_events', $all_events);
      $this->set('SelectedStateId', $selected_state_id);
      $this->set('SelectedCityId', $selected_city_id);
      $this->set('search_by',$search_by);
      /*$condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
      $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));*/

  }
    


       function edit_event($event_id) 
    {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Edit Event');

     $conditions_event = "Event.id = '".$event_id."'";
      $this->Event->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id'))), false);
      $this->Event->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'created_by_owner_id'))), false);
      $this->Event->bindModel(array('belongsTo' => array('Category' => array('foreignKey' => 'category_id'))), false);
      $event_details =  $this->Event->find('first',array('conditions' => $conditions_event));
      // pr($event_details);exit();
      $this->set('event_details',$event_details);


       if($event_details['Event']['group_id']!=" ")
      {
        $condition_event = "Group.group_type='".$event_details['Event']['group_type']."' AND Group.status = '1'";
        $selectedgrouplist = $this->Group->find("all",array('conditions'=>$condition_event,'order' => array('Group.id DESC')));
       // pr($citylist);exit();
       $this->set('selectedgrouplist', $selectedgrouplist); 
      }

      
      
      if (isset($this->params['form']) && !empty($this->params['form'])) {
       
                  $this->data['Event']['id'] =  $event_details['Event']['id'];
                  $this->data['Event']['group_id'] =  $this->params['form']['group_name'];
                  $this->data['Event']['group_type'] =  $this->params['form']['new_group_type'];
                  $this->data['Event']['title'] =  addslashes($this->params['form']['title']);
                  $this->data['Event']['desc'] = addslashes($this->params['form']['desc']);
                  $this->data['Event']['location'] = $this->params['form']['address'];
                  $this->data['Event']['latitude'] = $this->params['form']['lat'];
                  $this->data['Event']['longitude'] = $this->params['form']['lng'];
                  $this->data['Event']['place_id'] = $this->params['form']['place_id'];

                   
                   
                   $con_category_detail = "Group.id = '".$this->params['form']['group_name']."' AND Group.status = '1'";
                   $category_detail = $this->Group->find('first',array('conditions'=>$con_category_detail));

                  $this->data['Event']['category_id'] = $category_detail['Group']['category_id']; 
                    if($this->params['form']['new_group_type']=='B')
                   {
                    $this->data['Event']['deal_amount'] = $this->params['form']['amount'];
                    $this->data['Event']['type'] = 'public';
                    
                   }
                   else if($this->params['form']['new_group_type']=='F')
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
          $this->Session->setFlash(__d("statictext", "Event Edited Successfully !!.", true));
          $this->redirect("/admin_event/event_list/".$event_details['Event']['group_id']);
        }
     
                   

        }
    }
    

   
  

    function view_event($event_id,$search_by=NULL,$state_id=NULL,$city_id=NULL,$groupt_select=NULL) {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'View Event');
      
      
      $conditions_event = "Event.id = '".$event_id."'";
      $this->Event->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id'))), false);
      $this->Event->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'created_by_owner_id'))), false);
      $this->Event->bindModel(array('belongsTo' => array('Category' => array('foreignKey' => 'category_id'))), false);
      $event_details =  $this->Event->find('first',array('conditions' => $conditions_event));
      // pr($event_details);exit();
      $this->set('event_details',$event_details);
      $this->set('search_by',$search_by);
      $this->set('city_id',$city_id);
      $this->set('state_id',$state_id);
      $this->set('groupt_select',$groupt_select);
     
    }

    function change_event_status() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

      $userID = $_REQUEST['userID'];
      $userStatus = $_REQUEST['userStatus'];
      
      if($userStatus == '1')
        $this->data['Event']['status'] = '0';
      else
        $this->data['Event']['status'] = '1';
      
      $this->Event->id = $userID;
      if($this->Event->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
    }


    function show_group()
    {
       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

		$this->layout = "";
    	$group_type = $_REQUEST['group_type'];

    	$condition_event = "Group.group_type='".$group_type."' AND Group.status = '1'";
        $selectedgrouplist = $this->Group->find("all",array('conditions'=>$condition_event,'order' => array('Group.id DESC')));
       //pr($selectedgrouplist);
    	$this->set('selectedgrouplist', $selectedgrouplist); 

    }

    function delete_event() 
    {
       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');
            
      $categoryIDArrFinal = $_REQUEST['categoryIDArrFinal'];
      $categoryIDArray = explode(',', $categoryIDArrFinal);
      
      foreach($categoryIDArray as $categoryID) 
      {
        //pr($categoryID);
        $this->Event->id = $categoryID;
        $this->Event->delete();
      }
      
      echo 'ok';
      exit();
    }




  function delete_this_event()
  {
    $event_id = $_REQUEST['event_id'];
    $this->Event->id = $event_id;
    $this->Event->delete();
    echo "1";
    exit();
  }

    
}

?>
