<?php
/**
 * Admin Dashboard Controller
 */
class AdminGroupController extends AppController {

  var $name = 'AdminGroup';
  var $uses = array('Admin','Category','User','Group','Event','GroupUser','GroupImage','GroupDoc','Video','State','City','Notification','TerritoryAssign', 'GroupMessage', 'GroupMessageReply');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array();


    function user_list($group_id) {
        
        $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
      $this->set('pageTitle', 'Member List');
      $this->set('group_id',$group_id);

      $selected_state_id = '';
      $selected_city_id = '';
      $total_user = '';

      $search_by = 0;

      $con_grp_user = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_type <> 'O'";
      $ArGroupUser = $this->GroupUser->find('list',array('conditions' => $con_grp_user, 'fields' => 'GroupUser.user_id'));
      if(!empty($ArGroupUser))
      {
        $str_group_user = implode(',', $ArGroupUser);
      }
      //pr($ArGroupUser);exit;
      $all_users = array();
      $city_list = array();
      $ArState = array();
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
          }


          $conditions_state = "State.id IN (".$str_states.") AND State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          $con = "User.id IN (".$str_group_user.") AND User.state_id IN (".$str_states.") AND User.city_id IN (".$str_city.")";
          $all_users = $this->User->find('all',array('conditions' => $con));

      }
      else
      {
        if(!empty($ArGroupUser))
        {
          $con_cm_user = "User.id IN (".$str_group_user.")";
          $conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          $all_users = $this->User->find('all',array('conditions' => $con_cm_user));
        }
          
      }
      
    //pr($this->params['form']);exit;
      /*if(!empty($this->params['form']))
      {
          //pr($this->params['form']);exit;
          if(isset($this->params['form']['index_city_id']) && $this->params['form']['index_city_id'] != '')
          {
            //pr($this->params['form']);exit;
              $conditions_user = "User.id IN (".$str_group_user.") AND User.city_id = '".$this->params['form']['index_city_id']."' AND User.state_id = '".$this->params['form']['state_id']."' ";
              $all_users = $this->User->find("all",array('conditions'=>$conditions_user,'order' => 'User.fname ASC'));
              $user_state_city = $this->User->find("first",array('conditions'=>$conditions_user));
              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];
              $total_user = count($all_users);
              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

              $search_by = 2;
          }
          else if((isset($this->params['form']['state_id']) && $this->params['form']['state_id'] != ''))
          {
            
            //echo "==================";exit;
              $conditions_user = "User.id IN (".$str_group_user.") AND User.state_id = '".$this->params['form']['state_id']."' ";
              $all_users = $this->User->find("all",array('conditions'=>$conditions_user,'order' => 'User.fname ASC'));
              $user_state_city = $this->User->find("first",array('conditions'=>$conditions_user));
              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = '';
              $total_user = count($all_users);
              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

              $search_by = 1;
          }
      }*/
        

      $this->set('all_users', $all_users);
      $this->set('city_list',$city_list); 
      $this->set('ArState', $ArState);
      $this->set('total_user',$total_user);
      $this->set('SelectedStateId', $selected_state_id);
      $this->set('SelectedCityId', $selected_city_id);
      $this->set('search_by',$search_by);

      }


      function send_message() 
    {
           $adminData = $this->Session->read('adminData');
           if(empty($adminData))
              $this->redirect('/admins/login');

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $sitesettings = $this->getSiteSettings();

            $receiver_id = $_REQUEST['receiver_id'];
            $message = $_REQUEST['message'];
            $group_id = $_REQUEST['group_id'];

            $this->data['Notification']['sender_id'] = '0';
            $this->data['Notification']['type'] = 'P';
            $this->data['Notification']['group_id'] = $group_id;
            $this->data['Notification']['message'] = addslashes($message);              
            $this->data['Notification']['receiver_id'] = $receiver_id;
            $this->data['Notification']['receiver_type'] = 'GM';
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
    
    function group_list() {
        
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
      $this->set('pageTitle', 'Groop List');
      
    
      $this->Group->bindModel(array('belongsTo'=>array(
      'User'=>array('foreignKey'=>'created_by',
      'fields'=>'User.id,User.fname,User.lname')
      )));
      $this->Group->bindModel(array('belongsTo'=>array(
      'Category'=>array('foreignKey'=>'category_id',
      'fields'=>'Category.id,Category.title')
      )));

      $total_business_group = 0;
      $total_private_group = 0;
      $total_private_organisation_group = 0;

      $selected_state_id = '';
      $selected_city_id = '';

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
          }


          $conditions_state = "State.id IN (".$str_states.") AND State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          $conditions_group = "Group.state_id IN (".$str_states.") AND Group.city_id IN (".$str_city.") ";
          $this->Group->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'state_id'))));
          $this->Group->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city_id'))));
          $all_groups = $this->Group->find('all',array('conditions' => $conditions_group)); 

      }
      else
      {
          $conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
          $this->Group->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'state_id'))));
          $this->Group->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city_id'))));
          $all_groups = $this->Group->find('all',array('order' => 'Group.group_title ASC')); 

          
      }

    if(!empty($this->params['form'])){

        if(($this->params['form']['index_city_id'] != '') && ($this->params['form']['state_id'] != ''))
          {

            //echo "+++++++++++++++++++";exit;
            
            //$conditions_group = "Group.state_id IN (".$str_states.") AND Group.city_id IN (".$str_city.") ";
            $conditions_group = "Group.city_id = '".$this->params['form']['index_city_id']."' AND Group.state_id = '".$this->params['form']['state_id']."' ";

            $this->Group->bindModel(array('belongsTo' => array('Category' => array('foreignKey' => 'category_id'))));
            $this->Group->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'created_by'))));
            $this->Group->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'state_id'))));
            $this->Group->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city_id'))));
            $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group,'order' => 'Group.group_title ASC'));

            $group_state_city = $this->Group->find("first",array('conditions'=>$conditions_group));
            $selected_state_id = $this->params['form']['state_id'];
            $selected_city_id = $this->params['form']['index_city_id'];

            $business_group = array();
            $private_group = array();
            $private_organisation_group = array();

            foreach ($all_groups as $key => $value) {
            if($value['Group']['group_type'] == 'B')
            {
            array_push($business_group, $value['Group']['id']);
            }
            if($value['Group']['group_type'] == 'F')
            {
            array_push($private_group, $value['Group']['id']);
            }
            if($value['Group']['group_type'] == 'PO')
            {
            array_push($private_organisation_group, $value['Group']['id']);
            }
            }
            $total_business_group = count($business_group);
            $total_private_group = count($private_group);
            $total_private_organisation_group = count($private_organisation_group);


            $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
            $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          }


          if(($this->params['form']['state_id'] != '') && ($this->params['form']['index_city_id'] == ''))
          {


            //echo "==================================";exit;
            //$conditions_group = "Group.state_id IN (".$str_states.") AND Group.city_id IN (".$str_city.") ";
            $conditions_group = "Group.state_id = '".$this->params['form']['state_id']."'";
            
            $this->Group->bindModel(array('belongsTo' => array('Category' => array('foreignKey' => 'category_id'))));
            $this->Group->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'created_by'))));
            $this->Group->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'state_id'))));
            $this->Group->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city_id'))));
            $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group,'order' => 'Group.group_title ASC'));

            $group_state_city = $this->Group->find("first",array('conditions'=>$conditions_group));
            $selected_state_id = $this->params['form']['state_id'];
            $selected_city_id = $this->params['form']['index_city_id'];

            $business_group = array();
            $private_group = array();
            $private_organisation_group = array();

            foreach ($all_groups as $key => $value) {
            if($value['Group']['group_type'] == 'B')
            {
            array_push($business_group, $value['Group']['id']);
            }
            if($value['Group']['group_type'] == 'F')
            {
            array_push($private_group, $value['Group']['id']);
            }
            if($value['Group']['group_type'] == 'PO')
            {
            array_push($private_organisation_group, $value['Group']['id']);
            }
            }
            $total_business_group = count($business_group);
            $total_private_group = count($private_group);
            $total_private_organisation_group = count($private_organisation_group);


            $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
            $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          }
        }
        /*else
        {

          $selected_state_id = '';
          $selected_city_id = '';

            
        }*/

      /*$conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
      $ArState = $this->State->find('all', array('conditions' => $conditions_state));

      $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
      $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
      $this->set('city_list',$city_list); 
      $this->set('ArState', $ArState);*/

      $this->set('all_groups', $all_groups);
      $this->set('city_list',$city_list); 
      $this->set('ArState', $ArState);
      
      $this->set('SelectedStateId', $selected_state_id);
      $this->set('SelectedCityId', $selected_city_id);

      $this->set('total_business_group',$total_business_group);
      $this->set('total_private_group',$total_private_group);
      $this->set('total_private_organisation_group',$total_private_organisation_group);
      
    }
   

   function group_mem_count($group_id)
   {
      $conditions = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_type <> 'O'";
      $group_mem_count = $this->GroupUser->find('count',array('conditions' => $conditions));
      return $group_mem_count;
   } 


    function edit_group($group_id) 
    {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
      $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Edit Groop');

       $conditions = "Group.id = '" . $group_id . "'";
      $GroupDetails = $this->Group->find('first', array('conditions' => $conditions));
      $this->set('GroupDetails', $GroupDetails);


      if(isset($this->params['form']) && !empty($this->params['form'])) {
        //pr($this->params['form']);
        
        $this->data['Group']['id'] = $group_id;
        $this->data['Group']['group_title'] = addslashes($this->params['form']['group_title']);
        $this->data['Group']['group_desc'] = addslashes($this->params['form']['group_desc']);
        //$this->data['Group']['group_purpose'] = addslashes($this->params['form']['group_purpose']);
        $this->data['Group']['group_type'] = $this->params['form']['new_group_type'];

        $this->data['Group']['category_id'] = $this->params['form']['category_id'];
         
         if($this->Group->save($this->data['Group']))
        {
         // echo("UPDATE `events` SET group_type = '".$this->params['form']['new_group_type']."' , category_id = '".$this->params['form']['category_id']."'WHERE `group_id` = '".$group_id."'");exit();
      
          $this->Event->query("UPDATE `events` SET group_type = '".$this->params['form']['new_group_type']."' , category_id = '".$this->params['form']['category_id']."'WHERE `group_id` = '".$group_id."'");

          $this->Notification->query("UPDATE `notifications` SET group_type = '".$this->params['form']['new_group_type']."' WHERE `group_id` = '".$group_id."'");

          $this->Session->setFlash(__d("statictext", "Groop Edited Successfully !!.", true));
          $this->redirect("/admin_group/group_list"); 
        }
     
                   

        }
		
	  
     
      $condition = "Category.status ='1' AND Category.POG_status ='0'";
      $all_categories = $this->Category->find("all",array('conditions'=>$condition,'order' => 'Category.id DESC'));
      $this->set('all_categories', $all_categories);
	  
	  $condition_po = "Category.status ='1' AND Category.POG_status ='1'";
      $all_categories_po = $this->Category->find("all",array('conditions'=>$condition_po,'order' => 'Category.id DESC'));
      $this->set('all_categories_po', $all_categories_po);
    }
    
	function show_categories_business(){
		$condition = "Category.status ='1' AND Category.POG_status ='0'";
		$all_categories = $this->Category->find("all",array('conditions'=>$condition,'order' => 'Category.id DESC'));
		$this->set('all_categories', $all_categories);
	}
	
	function show_categories_po(){
		$condition = "Category.status ='1' AND Category.POG_status ='1'";
		$all_categories = $this->Category->find("all",array('conditions'=>$condition,'order' => 'Category.id DESC'));
		$this->set('all_categories', $all_categories);
	}
	
	function delete_group() {

       $adminData = $this->Session->read('adminData');
	   if(empty($adminData))
       $this->redirect('/admins/login');
      
  
      $groupIDArrFinal = $_REQUEST['groupIDArrFinal'];
      $groupIDArray = explode(',', $groupIDArrFinal);
	  
      foreach($groupIDArray as $groupID) {

        
		$conditions = "Group.id = '".$groupID."'"; 
		$Group = $this->Group->find('first',array('conditions' => $conditions)); 

			 
		 $folder='group_images/';
		 $folder_medium= 'group_images/medium/';
		 $folder_thumb= 'group_images/thumb/';
		 $folder_web= 'group_images/web/';
			
		 $this->removeFile($Group['Group']['icon'],$folder);
		 $this->removeFile($Group['Group']['icon'],$folder_medium);
		 $this->removeFile($Group['Group']['icon'],$folder_thumb);
		 $this->removeFile($Group['Group']['icon'],$folder_web);
			 
		 $this->Group->id = $Group['Group']['id'];
		 $this->Group->delete();
			 
		 //$this->GroupImage->deleteAll(array('GroupImage.group_id' => $Group['Group']['id']), false);
		 $arr_group_images = $this->GroupImage->find('all',array('conditions' => array('GroupImage.group_id' => $Group['Group']['id']), 'fields' => array('GroupImage.id', 'GroupImage.image')));
		 if(!empty($arr_group_images)){
			foreach($arr_group_images as $key => $val_image){
				
				$folder='gallery/';
				$folder_medium= 'gallery/medium/';
				$folder_thumb= 'gallery/thumb/';
				$folder_web= 'gallery/web/';
				
				$this->removeFile($val_image['GroupImage']['image'],$folder);
				$this->removeFile($val_image['GroupImage']['image'],$folder_medium);
				$this->removeFile($val_image['GroupImage']['image'],$folder_thumb);
				$this->removeFile($val_image['GroupImage']['image'],$folder_web);
				
				$this->GroupImage->id = $val_image['GroupImage']['id'];
				$this->GroupImage->delete();
				
			}
		 }
			 
		 //$this->GroupDoc->deleteAll(array('GroupDoc.group_id' => $Group['Group']['id']), false);
		 $arr_group_docs = $this->GroupDoc->find('all',array('conditions' => array('GroupDoc.group_id' => $Group['Group']['id']), 'fields' => array('GroupDoc.id', 'GroupDoc.docname')));
		 if(!empty($arr_group_docs)){
			foreach($arr_group_docs as $key => $val_doc){
				
				$folder='gallery/doc/';
				$this->removeFile($val_doc['GroupDoc']['docname'],$folder);
				
				$this->GroupDoc->id = $val_doc['GroupDoc']['id'];
				$this->GroupDoc->delete();
				
			}
		 }
			 
			 
		 //$this->Video->deleteAll(array('Video.group_id' => $Group['Group']['id']), false);
		 $arr_group_videos = $this->Video->find('all',array('conditions' => array('Video.group_id' => $Group['Group']['id']), 'fields' => array('Video.id', 'Video.v_image', 'Video.video')));
		 if(!empty($arr_group_videos)){
			foreach($arr_group_videos as $key => $val_video){
				
				$folder='group_videos/';
				$folder_images='group_videos/images/';
				$this->removeFile($val_video['Video']['video'],$folder);
				$this->removeFile($val_video['Video']['v_image'],$folder_images);
				
				$this->Video->id = $val_video['Video']['id'];
				$this->Video->delete();
				
			}
		 }
			 
		 $this->GroupMessage->deleteAll(array('GroupMessage.group_id' => $Group['Group']['id']), false);
		 $this->GroupMessageReply->deleteAll(array('GroupMessageReply.group_id' => $Group['Group']['id']), false);
		 	 
		 $this->GroupUser->deleteAll(array('GroupUser.group_id' => $Group['Group']['id']), false);
		 $this->Event->deleteAll(array('Event.group_id' => $Group['Group']['id']), false);
		 
		 $arr_users = $this->User->find('all',array('conditions' => array('User.status' => '1' ,'FIND_IN_SET(\''. $Group['Group']['id'] .'\',User.groups)'), 'fields' => array('User.groups', 'User.id')));
		 if(!empty($arr_users)){
			 foreach($arr_users as $key => $val){
				$arr_user_groups= explode(',', $val['User']['groups']);
				$key= array_search($Group['Group']['id'],$arr_user_groups,true);
				unset($arr_user_groups[$key]);
				array_values( $arr_user_groups );
				
				$str_user_groups= implode(',', $arr_user_groups);
				
				$arr_new_user_groups = array();
				$arr_new_user_groups['User']['id'] = $val['User']['id'];
				$arr_new_user_groups['User']['groups'] = $str_user_groups;
				$this->User->save($arr_new_user_groups);
			 }
		 }
		
      }
      echo 'ok';
      exit();
    }
   
      function profile($profile_user_id=NULL){

         $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

        $this->layout = "home_inner";
      
        $this->set('pageTitle', 'User Detail');
        $this->set('profile_user_id', $profile_user_id);

        $user_id = $this->Session->read('USER_USERID');
        $session_required_user_id = $user_id;
        $this->set('SessionRequiredUserId', $session_required_user_id);
        //$profile_user_id = 71;

        $this->User->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city'))));

        $conditions = "User.id = '".$profile_user_id."'";            
        $UserData = $this->User->find('first', array('conditions' => $conditions));

        if($UserData['User']['type'] == '1')    // customer
        {
            if ($this->Session->check('USER_USERID'))
            {
                $user_id = $this->Session->read('USER_USERID');
                $loggedin_user_type = $this->Session->read('userData.User.type');
                if($loggedin_user_type == '2')    // instructor viewing customer profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }  else if($loggedin_user_type == '3')    // facility viewing customer profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
            }
            
            
        }       
        else if($UserData['User']['type'] == '2')    // instructor
        {
            if ($this->Session->check('USER_USERID'))
            {
                $user_id = $this->Session->read('USER_USERID');
                $loggedin_user_type = $this->Session->read('userData.User.type');
                
                $this->UserReviewComment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.image,User.first_name,User.last_name'              ))));

              $this->UserReview->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'post_by'))));
              $this->UserReview->bindModel(array(
              'hasMany' => array(
              'UserReviewComment' => array('foreignKey' => 'review_id','order'=>'UserReviewComment.created Asc')               
              )
              ));
              
              $conditions = "UserReview.user_id = '".$profile_user_id."'";
            $this->UserReview->recursive = 2;
            $AllReview = $this->UserReview->find('all', array('conditions' => $conditions));
            //pr($user_id);
            //pr($AllReview);exit();
             $this->set('AllReview', $AllReview);

              $this->Class->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
              $condition = "Class.friend_id = '".$profile_user_id."'";
              $new_class_list = $this->Class->find('all', array('conditions'=>$condition,'order' => 'Class.id DESC')); 
             //pr($new_class_list);exit();
              $this->set('new_class_list', $new_class_list);
             

                if($loggedin_user_type == '3')    // facility viewing instructor profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
                  if($loggedin_user_type == '1')    // customer viewing instructor profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
            }
            
            
        }
        else if($UserData['User']['type'] == '3')    // facility
        {
            
            if ($this->Session->check('USER_USERID'))
            {
                $user_id = $this->Session->read('USER_USERID');
        $this->UserReviewComment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.image,User.first_name,User.last_name'))));
                        
                          $this->UserReview->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'post_by'))));
                          $this->UserReview->bindModel(array(
                          'hasMany' => array(
                          'UserReviewComment' => array('foreignKey' => 'review_id','order'=>'UserReviewComment.created Asc')               
                          )
                          ));
                          
                        $conditions = "UserReview.user_id = '".$profile_user_id."'";
                        $this->UserReview->recursive = 2;
                        $AllReview = $this->UserReview->find('all', array('conditions' => $conditions));
                        //pr($user_id);
                        //pr($AllReview);exit();
                         $this->set('AllReview', $AllReview);

                        $this->Class->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
                        $condition = "Class.friend_id = '".$profile_user_id."'";
                        $new_class_list = $this->Class->find('all', array('conditions'=>$condition,'order' => 'Class.id DESC')); 
                        $this->set('new_class_list', $new_class_list);
                        //pr($new_class_list);exit();

                                   
                      $loggedin_user_type = $this->Session->read('userData.User.type');
                      //pr($loggedin_user_type)   ;exit();
                      if($loggedin_user_type == '2')    // instructor viewing facility profile
                
                 {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
                               if($loggedin_user_type == '1')    // customer viewing facility profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
            }
        }

        $this->set('UserData', $UserData);

        $condition2 = "Job.user_id ='".$profile_user_id."' AND Job.status = '1'";
        $my_jobs = $this->Job->find('all',array('conditions'=>$condition2));
        $this->set('my_jobs', $my_jobs);
        
     if (isset($this->params['form']) && !empty($this->params['form'])) {

      //$user_review_id = $this->params['form']['inst_fac_name'];
      $post_by = $user_id;

      $rating = $this->params['form']['rating_value'];
      $title = $this->params['form']['title'];
      $review = $this->params['form']['review'];
      $recommend = $this->params['form']['recommend'];
      $profile_user_id = $this->params['form']['profile_user_id'];
      
      if(($profile_user_id)==($post_by))
      {
        $this->Session->setFlash(__d("statictext", "Sorry !!You cannot give review to Yourself.", true));
        $_SESSION['meesage_type'] = '1';
        $this->redirect(array('controller' => 'users', 'action' => 'profile', $profile_user_id));
      }
      else
      {
              $this->data['UserReview']['user_id'] = $profile_user_id;
              $this->data['UserReview']['post_by'] = $post_by;
              if(!empty($rating))
              {
                $this->data['UserReview']['rating'] = $rating;
              }
              else{
                $this->data['UserReview']['rating'] = 0;
              }
              
              $this->data['UserReview']['review'] = $review;
              $this->data['UserReview']['title'] = $title;
              $this->data['UserReview']['recommend'] = $recommend;
              $this->UserReview->create();
              $this->UserReview->save($this->data['UserReview']);
              //$this->Session->setFlash(__d("statictext", "Thanks For Your Review!!.", true));
              //$_SESSION['meesage_type'] ='1';
              //echo $profile_user_id;exit;
              
              $this->Session->setFlash(__d("statictext", "Thanks For Your Review!!.", true ));
              $_SESSION['meesage_type'] = '1';  
              $this->redirect(array('controller' => 'users', 'action' => 'profile', $profile_user_id)); 
              
         // $this->redirect("/users/profile/".$this->params['form']['popup-sender-id']);

         }
     }
     
     $this->UserReviewComment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.image,User.first_name,User.last_name'))));
    
      $this->UserReview->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'post_by'))));
      $this->UserReview->bindModel(array(
      'hasMany' => array(
      'UserReviewComment' => array('foreignKey' => 'review_id','order'=>'UserReviewComment.created Asc')               
      )
      ));
      
      $conditions = "UserReview.user_id = '".$profile_user_id."'";
    $this->UserReview->recursive = 2;
    $AllReview = $this->UserReview->find('all', array('conditions' => $conditions));
    //pr($user_id);
    //pr($AllReview);exit();
     $this->set('AllReview', $AllReview);
     
    $this->Article->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
    $condition = "Article.user_id = '".$profile_user_id."' AND Article.is_published = '1'";
    $article_list = $this->Article->find('all', array('conditions'=>$condition));
    //pr($article_list);exit();
    $this->set('article_list', $article_list);
    
    
  }

  

    function view_user($user_id) {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'View User');
      
      $user_exists = $this->User->find('count', array('conditions'=>array('id'=>$user_id, 'isdeleted'=>'0')));
      
     
        
        $conditions = "User.id = '".$user_id."'";
        $this->User->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city'))), false);
        $this->User->bindModel(array('belongsTo' => array('Country' => array('foreignKey' => 'country'))), false);
        $this->User->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'state'))), false);
        $user_details =  $this->User->find('first',array('conditions' => $conditions));
        $this->set('user_details',$user_details);
       
    }

        function change_group_status() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

      $userID = $_REQUEST['userID'];
      $userStatus = $_REQUEST['userStatus'];
      
      if($userStatus == '1')
      {
        $this->data['Group']['status'] = '0';
      
      $this->GroupUser->query("UPDATE `group_users` SET status ='0' WHERE group_id = '".$userID."'");
      }
      else
        {
        $this->data['Group']['status'] = '1';
       $this->GroupUser->query("UPDATE `group_users` SET status ='1' WHERE group_id = '".$userID."'");  
        }
      
      $this->Group->id = $userID;
      if($this->Group->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
    }


    function change_group_is_featured() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

            
      $userID = $_REQUEST['userID'];
      $userFeatured = $_REQUEST['userFeatured'];
      
      if($userFeatured == '1')
        $this->data['Group']['is_featured'] = '0';
      else
        $this->data['Group']['is_featured'] = '1';
      
      $this->Group->id = $userID;
      if($this->Group->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
    }

    function view_group($group_id)
    {
          $adminData = $this->Session->read('adminData');
          if(empty($adminData))
         $this->redirect('/admins/login');
      
         $this->layout = "";
         $this->set('pageTitle', 'View Groop');


         $this->set('group_id',$group_id);
         $date = date('Y-m-d');
      
     
        /***************   Group Detail Starts    **************/
    
        $condition = "Group.status= '1'  AND Group.id= '".$group_id."'";
        $group_details = $this->Group->find("first",array('conditions'=>$condition));
        $this->set('group_details',$group_details);
    
    /*$arr_group_owners= explode(',', $group_details['Group']['group_owners']);
    if(in_array($user_id, $arr_group_owners)){
      $show_edit=1;
    }
    else{
      $show_edit=0;
    }
    $this->set('show_edit',$show_edit);*/
    /***************   Group Detail Ends    **************/

    /***************   Group Member Type Starts    **************/
        $group_member_type = 'nonmember';
    
            $condition_group_owner_count = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_details['Group']['id']."' AND GroupUser.user_type= 'O'"; //Check the logged in user is owner or not 
            $group_owner_count = $this->GroupUser->find("count",array('conditions'=>$condition_group_owner_count));
        if($group_owner_count > 0)
        {
          $group_member_type = 'owner';
        }
            $condition_group_member_count = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_details['Group']['id']."' AND GroupUser.user_type= 'M'";  //Check the logged in user is member or not 
            $group_member_count = $this->GroupUser->find("count",array('conditions'=>$condition_group_member_count));
            if($group_member_count > 0)
            {
              $group_member_type = 'member';
            }
        //pr($group_member_type);exit();
        $this->set('group_member_type',$group_member_type);
    
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


    function show_category()
    {
       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

    $this->layout = "";
    
      $condition = "Category.status ='1'";
      $all_categories = $this->Category->find("all",array('conditions'=>$condition,'order' => 'Category.id DESC'));
      $this->set('all_categories', $all_categories);

    }
  
    
  
  
    
}

?>
