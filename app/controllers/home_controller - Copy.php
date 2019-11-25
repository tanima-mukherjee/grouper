<?php
class HomeController extends AppController {

    var $name = 'Home';
    var $uses = array('User','SiteSetting','EmailTemplate','State','City','GroupUser','Group','Event','Notification','Friendlist','Testimonial');
    var $helpers = array("Html", "Form", "Javascript", "Fck", 'Js', "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
		
   	

    function index() {
        $this->layout = "home_landing";
        $this->set('pagetitle', 'Welcome to Grouper');

        
        $condition = "User.status= '1' ";
        $featured_user = $this->User->find("first",array('conditions'=>$condition,'order' => 'User.id DESC'));
       // pr($featured_users);exit();
        $this->set('featured_user',$featured_user);

        $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
            $ArState = $this->State->find('all', array('conditions' => $conditions));
            $this->set('ArState', $ArState);

         
            $condition_user_detail = "Testimonial.is_approved = '1' AND Testimonial.is_featured = '1'";
            $this->Testimonial->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
           
            $testimonial_list = $this->Testimonial->find('all',array('conditions'=>$condition_user_detail,'order' => 'Testimonial.id DESC'));
            $this->Testimonial->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
           
            $this->set('testimonial_list',$testimonial_list);
      

    }

    function show_city(){
		$stateid = $_REQUEST['state_id'];
		$condition = "City.isdeleted = '0' AND City.state_id='".$stateid."'";
		$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
		$this->set('citylist',$citylist);
	}

    function signup(){ 
		$this->layout = "";
		$this->set('pagetitle', 'Sign Up');
		$sitesettings = $this->getSiteSettings();
  
		if (isset($this->params['form']) && !empty($this->params['form'])) {
			$upload_image = '';
		
			if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= ''){
					$image_name = $this->params['form']['upload_image']['name'];
					
					$extension = end(explode('.',$image_name));               
					$upload_image = time().accessCode(5).'.'.$extension;          
					$upload_target_original = 'user_images/'.$upload_image;
								
					$imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
					list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
					
					
					 if($type == 1 || $type == 2 )
					 {
						if($uploaded_width >=640 && $uploaded_height >= 480)
						{
							if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
																																																									
																																									 
									$upload_target_thumb = 'user_images/thumb/'.$upload_image;
									$upload_target_medium = 'user_images/medium/'.$upload_image;
								   
									$this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
									$this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
																	   
							  }      
							else  
							{
								
							
							$this->Session->setFlash(__d("statictext", "Image upload failed", true));
							$_SESSION['meesage_type'] = '0';
							$this->redirect(array('controller'=>'home','action'=>'index'));
						
							}
						
						}
						else
						{        
						 
						$this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
						$_SESSION['meesage_type'] = '0';
						$this->redirect(array('controller'=>'home','action'=>'index'));
						
						}
						
					 }
					 else
					 {
						
					   $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
						$_SESSION['meesage_type'] = '0';
						$this->redirect(array('controller'=>'home','action'=>'index'));
					  
					 }
				
				}
	   
		
			$email = $this->params['form']['email'];
			$txt_password = $this->params['form']['password'];
			$password = md5($this->params['form']['password']);
			$username = $this->params['form']['username'];
			$countUser = $this->User->find('count', array('conditions' => array('User.email' => $email, 'User.status' => '1')));
			//pr($countUser);exit();

			$countUserName = $this->User->find('count', array('conditions' => array('User.username' => $username, 'User.status' => '1')));

			if($countUser > 0)
			{

				 $email_is_exist = $this->User->find('first', array('conditions'=>array('email'=>$email,'status'=>'1','is_invite' => '1')));
				
					if(!empty($email_is_exist))
					{
						$condition_user_detail = " User.id = '".$email_is_exist['User']['id']."' AND User.status = '1' ";
						$email_details = $this->User->find('first', array('conditions'=>$condition_user_detail));
					
						
						$this->data['User']['fname'] = stripcslashes($this->params['form']['first_name']);
						$this->data['User']['lname'] = stripcslashes($this->params['form']['last_name']);  
						$this->data['User']['password'] = md5($password);
						$this->data['User']['txt_password'] = $password;
						$this->data['User']['username'] = stripcslashes($username);
						$this->data['User']['image'] = $upload_image;
						$this->data['User']['device_token'] = $device_token;
						$this->data['User']['state_id'] = $this->params['form']['state_id'];
						$this->data['User']['city_id'] = $this->params['form']['city_id'];
						$this->data['User']['device_type'] = 'web';
						$this->data['User']['groups'] = '';
						$this->data['User']['is_invite'] = '0';
					   
					   $this->User->id = $email_details['User']['id'];
						   
						if($this->User->save($this->data['User']))
						{
				   // $insert_id = $this->User->getLastInsertID(); 
					$last_insert_id = $email_details['User']['id'];
										   

					$user_name = $username; 
					$admin_sender_email = $sitesettings['site_email']['value'];
					$site_url = $sitesettings['site_url']['value'];
					$sender_name = $sitesettings['email_sender_name']['value'];

					$condition = "EmailTemplate.id = '1'";
					$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
					$link = '<a href="' . $site_url . '/home/activation/' . base64_encode($last_insert_id) . '"> ' . $site_url . '/home/activation/'. base64_encode($last_insert_id) . '</a>';
				   
					
					$to = $email_details['User']['email'];

					$user_name = $user_name;
					$user_subject = $mailDataRS['EmailTemplate']['subject'];
					$user_subject = str_replace('[SITE NAME]', 'Grouper | New Registration', $user_subject);
							   
				 
					$user_body = $mailDataRS['EmailTemplate']['content'];
					$user_body = str_replace('[NAME]', $user_name, $user_body);
					$user_body = str_replace('[LINK]', $link, $user_body);
									 
					$user_message = stripslashes($user_body);
					
		   
				   $string = '';
				   $filepath = '';
				   $filename = '';
				   $sendCopyTo = '';
		   
		   
					$sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                
						if($sendmail)
						  {
							$this->Session->setFlash("Thank you for registering with Grouper. The registration is not yet completed. You will receive a verification email with the instructions. ");    
							$_SESSION['meesage_type'] = '1';
								 $this->redirect(array('controller'=>'home','action'=>'index'));

							
						  }
						  else
						  {
							$this->Session->setFlash("Failed to send email.");    
							$_SESSION['meesage_type'] = '1';
							  $this->redirect(array('controller'=>'home','action'=>'index'));
						  }
						  $this->redirect(array('controller'=>'home','action'=>'index'));
									
									
					
								
				}
						
					}
					else
					{
						
						 $this->Session->setFlash(__d("statictext", "You are already registered in this email", true));
						 $_SESSION['meesage_type'] = '0';
						 $this->redirect(array('controller'=>'home','action'=>'index'));

					}
			}
			else if($countUserName > 0 ) 
			{
			   $this->Session->setFlash(__d("statictext", "You are already registered in this username", true));
				$_SESSION['meesage_type'] = '0';
				$this->redirect(array('controller'=>'home','action'=>'index'));
			}
			else {

					$this->data['User']['fname'] = stripcslashes($this->params['form']['first_name']);
					$this->data['User']['lname'] = stripcslashes($this->params['form']['last_name']);  
					$this->data['User']['image'] = $upload_image;
					$this->data['User']['username'] = stripcslashes($username);
					$this->data['User']['email'] = $email; 
					$this->data['User']['password'] = $password;  
					$this->data['User']['txt_password'] = $txt_password; 
					$this->data['User']['status'] = '0'; 
					$this->data['User']['groups'] = '';
					$this->data['User']['state_id'] = $this->params['form']['state_id'];
					$this->data['User']['city_id'] = $this->params['form']['city_id'];
					$this->data['User']['device_type'] = 'web';


					$this->User->create(); 
					if ($this->User->save($this->data)) 
					
					{

					  $last_insert_id = $this->User->getLastInsertId();
											

					$user_name = $this->params['form']['username']; 
					$admin_sender_email = $sitesettings['site_email']['value'];
					$site_url = $sitesettings['site_url']['value'];
					$sender_name = $sitesettings['email_sender_name']['value'];

					$condition = "EmailTemplate.id = '1'";
					$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
					$link = '<a href="' . $site_url . '/home/activation/' . base64_encode($last_insert_id) . '"> ' . $site_url . '/home/activation/'. base64_encode($last_insert_id) . '</a>';
				   
					
					$to = $this->params['form']['email'];

					$user_name = $user_name;
					$user_subject = $mailDataRS['EmailTemplate']['subject'];
					$user_subject = str_replace('[SITE NAME]', 'Grouper | New Registration', $user_subject);
							   
				 
					$user_body = $mailDataRS['EmailTemplate']['content'];
					$user_body = str_replace('[NAME]', $user_name, $user_body);
					$user_body = str_replace('[LINK]', $link, $user_body);
									 
					$user_message = stripslashes($user_body);
					
		   
				   $string = '';
				   $filepath = '';
				   $filename = '';
				   $sendCopyTo = '';
		   
		   
					$sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                 
		 // exit;
		 if($sendmail)
		  {
			$this->Session->setFlash("Thank you for registering with Grouper. The registration is not yet completed. You will receive a verification email with the instructions. ");    
			$_SESSION['meesage_type'] = '1';
				 $this->redirect(array('controller'=>'home','action'=>'index'));

			
		  }
		  else
		  {
			$this->Session->setFlash("Failed to send email.");    
			$_SESSION['meesage_type'] = '1';
			  $this->redirect(array('controller'=>'home','action'=>'index'));
		  }
		  $this->redirect(array('controller'=>'home','action'=>'index'));
	  }
			}
		}
		 
	 }


    function login() {
        
	$this->layout = "";
	$this->set('pageTitle', 'Login');
	
	

	if (!empty($this->params['form'])) {

	$password = md5($this->params['form']['password']);
	$txt_password = $this->params['form']['password'];
	$username = $this->params['form']['username'];

		$condition = "User.username = '".$username."' AND User.txt_password = '".$txt_password."' AND User.password = '".$password."' AND User.status = '1'";
		$detail = $this->User->find('first',array('conditions'=>$condition));
		
		$is_exist = $this->User->find('count', array('conditions' => $condition));
		if($is_exist > 0)
		{
		  
		  if ($detail['User']['status'] == 1) 
		  {
			$this->Session->write('userData', $detail);
			$this->Session->write('selected_state_id', $detail['User']['state_id']);
			$this->Session->write('selected_city_id', $detail['User']['city_id']);
			$this->Session->write('USER_ID', $detail['User']['id']);
			 /*$this->Session->setFlash(__d("statictext", "Logged in sucessfully", true));
				$_SESSION['meesage_type'] = '1';*/
			$this->redirect(array("controller" => "category", "action" => "category_list")); 
		  }
		  else 
			{
				$this->Session->setFlash(__d("statictext", "Your account is not yet activated! Please check your mail for its activation", true));
				$_SESSION['meesage_type'] = '0';
				$this->redirect(array('controller'=>'home','action'=>'index'));
			}

		}
		else 
			{
				$this->Session->setFlash(__d("statictext", "Incorrect username password!", true));
				$_SESSION['meesage_type'] = '0';
				$this->redirect(array('controller'=>'home','action'=>'index'));
			}
	   }
	
	}


    function activation($id) {
        
        $this->layout = "home_landing";
        $this->set('pageTitle', 'Account Activation');
        //$sitesettings = $this->getSiteSettings();   

        $uid = base64_decode($id);
    
        $UserDetails = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
      	//print_r($uid);
  		//print_r($UserDetails);
  		//exit;
		if(!empty($UserDetails))
		{
		   $updatedata['User']['id']= $uid;
		  $updatedata['User']['status'] = '1';
		  if($this->User->save($updatedata)){
			$this->Session->setFlash(__d('statictext', "Congratulations, Your account is successfully activated", true));
			$_SESSION['meesage_type'] = '1';
			$this->redirect(array('controller'=>'home','action'=>'index'));
		  }
		}
		else
		{     
			$this->Session->setFlash(__d('statictext', "Sorry!!! Your account cannot be activated ", true));
			$_SESSION['meesage_type'] = '0';
			$this->redirect(array('controller'=>'home','action'=>'index'));
		  
		}
        
        
    }

    function logout() {
        $this->layout = "";



        $this->Session->delete('userData');
        $this->Session->destroy();
        $this->Session->write('user_logout', 1);
        $this->redirect(array('controller'=>'home','action'=>'index'));
    }


       
    
    function all_members(){
		
		$this->layout = "ajax";
		
		$url = $_REQUEST['url'];
		$user_id = $this->Session->read('userData.User.id');
        $condition6 = "GroupUser.status= '1' AND  GroupUser.user_type = 'M' AND  GroupUser.user_id = '".$user_id."' ";
        $all_m_groups = $this->GroupUser->find('all',array('conditions'=>$condition6 ));
            
        $mgroup = array(); 
        foreach($all_m_groups as $grp)
                {
                    array_push($mgroup,$grp['GroupUser']['group_id']);
                }
        $allmgroups = implode(",",$mgroup);
		//echo  $allmgroups ;exit;
        $condition8 = "Group.id IN (".$allmgroups.")";

        //$all_member_groups = $this->Group->find('all',array('conditions'=>$condition8,'order' => 'Group.group_title ASC'));
        //pr($all_member_groups);exit();
		
		$limit = 1;
        $this->paginate = array('conditions' =>$condition8,'limit' => $limit,'order'=>'Group.group_title ASC');
        $all_member_groups = $this->paginate('Group');
		$this->set('all_member_groups',$all_member_groups);
		$this->set('url',$url);
	}


    
    /*function my_group1() {

        $this->layout = "home_inner";
        $this->set('pagetitle', 'My Group');
        $this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');
        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);

       //owner grp start//
        $condition4 = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' ";
        $all_groups = $this->GroupUser->find('all',array('conditions'=>$condition4 ));
            
           
        $group = array(); 
        foreach($all_groups as $grp)
                {
                    
                    
                    array_push($group,$grp['GroupUser']['group_id']);
                }
        $allgroups = implode(",",$group);
             
        
        $condition7 = "Group.status= '1'  AND Group.id IN (".$allgroups.")";

        $all_owner_groups = $this->Group->find('all',array('conditions'=>$condition7,'order' => 'Group.group_title ASC'));
       
       /* $limit = 8;
        $this->paginate = array('conditions' =>$condition7,'limit' => $limit,'order'=>'Group.group_title ASC');
        $all_owner_groups = $this->paginate('Group');*/
      /*  $this->set('all_owner_groups',$all_owner_groups);   
         //owner grp end//

         //member grp start//
        $condition6 = "GroupUser.status= '1' AND  GroupUser.user_type = 'M' AND  GroupUser.user_id = '".$user_id."' ";
        $all_m_groups = $this->GroupUser->find('all',array('conditions'=>$condition6 ));
            
        
        $mgroup = array(); 
        foreach($all_m_groups as $grp)
                {
                    
                    
                    array_push($mgroup,$grp['GroupUser']['group_id']);
                }
        $allmgroups = implode(",",$mgroup);

        $condition8 = "Group.status= '1'  AND Group.id IN (".$allmgroups.")";

        $all_member_groups = $this->Group->find('all',array('conditions'=>$condition8,'order' => 'Group.group_title ASC'));
       
        /*$limit = 8;
        $this->paginate = array('conditions' =>$condition8,'limit' => $limit,'order'=>'Group.group_title ASC');
        $all_member_groups = $this->paginate('Group');*/
        
      /*   $this->set('all_member_groups',$all_member_groups);   

         //member grp end//

         $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
            $ArState = $this->State->find('all', array('conditions' => $conditions));
            $this->set('ArState', $ArState);

        $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
            $StateName = $this->State->find('first', array('conditions' => $conditions));
            $this->set('StateName', $StateName);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
            $CityName = $this->City->find('first', array('conditions' => $conditions));
            $this->set('CityName', $CityName);



    }*/

    /*function my_group() {

        $this->layout = "home_inner";
        $this->set('pagetitle', 'My Group');
        $this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');
        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);

       //All grp start//
        $condition = "GroupUser.status= '1'  AND ( GroupUser.user_type = 'O' OR GroupUser.user_type = 'M') AND GroupUser.user_id = '".$user_id."' ";
        $arr_all_groups = $this->GroupUser->find('all',array('conditions'=>$condition ));
         
        $group = array(); 
        foreach($arr_all_groups as $grp)
		{
			
			
			array_push($group,$grp['GroupUser']['group_id']);
		}
        $allgroups = implode(",",$group);
           

        $limit = 16;   

        $condition7 = "Group.status= '1'  AND Group.group_type='F'  AND Group.id IN (".$allgroups.")";

        $this->paginate = array('conditions' =>$condition7,'limit' => $limit,'order' => 'Group.group_title ASC');
		$all_free_groups = $this->paginate('Group');

		$this->set('all_free_groups',$all_free_groups);  
       
         //free grp end//

         //business grp start//
        $condition6 = "GroupUser.status= '1'  AND ( GroupUser.user_type = 'O' OR GroupUser.user_type = 'M') AND GroupUser.user_id = '".$user_id."' ";
        $all_b_groups = $this->GroupUser->find('all',array('conditions'=>$condition6 ));
            
        
        $bgroup = array(); 
        foreach($all_b_groups as $grp)
		{
		  
			array_push($bgroup,$grp['GroupUser']['group_id']);
		}
        $allbgroups = implode(",",$bgroup);

        /*$condition8 = "Group.status= '1' AND Group.group_type='B' AND Group.id IN (".$allbgroups.")";

        $all_business_groups = $this->Group->find('all',array('conditions'=>$condition8,'order' => 'Group.group_title ASC'));
          
        $this->set('all_business_groups',$all_business_groups);  

         $limit = 16;   

        $condition8 = "Group.status= '1' AND Group.group_type='B' AND Group.id IN (".$allbgroups.")";

        $this->paginate = array('conditions' =>$condition8,'limit' => $limit,'order' => 'Group.group_title ASC');
		$all_business_groups = $this->paginate('Group');

		$this->set('all_business_groups',$all_business_groups); 

         //business grp end//

         $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
            $ArState = $this->State->find('all', array('conditions' => $conditions));
            $this->set('ArState', $ArState);

        $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
            $StateName = $this->State->find('first', array('conditions' => $conditions));
            $this->set('StateName', $StateName);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
            $CityName = $this->City->find('first', array('conditions' => $conditions));
            $this->set('CityName', $CityName);



    }*/

	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$recursive = -1;
		$group = $fields = array();
		return $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group'));
	}
	
	function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$sql = "SELECT DISTINCT ON(week, home_team_id, away_team_id) week, home_team_id, away_team_id FROM games";
		$this->recursive = $recursive;
		$results = $this->query($sql);
		return count($results);
	}

    function my_group(){

        $this->layout = "home_inner";
        $this->set('pagetitle', 'My Group');
        $this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');
        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);

       //free grp start//
        $condition_my_groups = "GroupUser.status= '1'  AND ( GroupUser.user_type = 'O' OR GroupUser.user_type = 'M') AND GroupUser.user_id = '".$user_id."' ";
        $all_my_groups = $this->GroupUser->find('all',array('conditions'=>$condition_my_groups ));
         
		if(empty($all_my_groups)){
			 $this->set('all_free_groups',''); 	
			 $this->set('all_business_groups',''); 	
		}
		else{
			$groups = array(); 
			
			foreach($all_my_groups as $grp){
				array_push($groups, $grp['GroupUser']['group_id']);
			}
			
			$str_allgroups = implode(",",$groups);
			
			//$limit = 16;
			
			/************          Fetch free groups starts      ***************/
		    /*$cond_free_grps = "Group.status= '1'  AND Group.group_type='F'  AND Group.id IN (".$str_allgroups.")";
		  	$this->paginate = array('conditions' =>$cond_free_grps,'limit' => $limit,'order' => 'Group.group_title ASC');
		  	$all_free_groups = $this->paginate('Group');

		  	$this->set('all_free_groups',$all_free_groups); */
			
			$this->set('all_free_groups','');
			/************          Fetch free groups ends      *****************/
			
			/************          Fetch business groups starts      ***************/
		    $cond_business_grps = "Group.status= '1'  AND Group.group_type='B'  AND Group.id IN (".$str_allgroups.")";
		  	$this->paginate = array('conditions' =>$cond_business_grps, 'limit' => 2);
		  	$all_business_groups = $this->paginate('Group');

		  	$this->set('all_business_groups',$all_business_groups); 
			$this->helpers['Paginator'] = array('ajax' => 'Ajax');
			
			
			/************          Fetch business groups ends      *****************/
		}
		
		$conditions = "State.isdeleted = '0' AND State.country_id = '254'";
		$ArState = $this->State->find('all', array('conditions' => $conditions));
		$this->set('ArState', $ArState);

        $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
		$StateName = $this->State->find('first', array('conditions' => $conditions));
		$this->set('StateName', $StateName);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
		$CityName = $this->City->find('first', array('conditions' => $conditions));
		$this->set('CityName', $CityName);
		
    }

    function friends(){
	
      $this->layout = "home_inner";
      $this->_checkSessionUser();
      $user_id = $this->Session->read('userData.User.id');
       $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');
        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);
      $this->set('pageTitle', 'Friends');


         $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
            $ArState = $this->State->find('all', array('conditions' => $conditions));
            $this->set('ArState', $ArState);

        $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
            $StateName = $this->State->find('first', array('conditions' => $conditions));
            $this->set('StateName', $StateName);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
            $CityName = $this->City->find('first', array('conditions' => $conditions));
            $this->set('CityName', $CityName);

             $limit = 10;
              $condition_friend_detail = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
              $this->paginate = array('conditions' =>$condition_friend_detail,'limit' => $limit);
             $this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'sender_id'))),false);
           
              $friend_list = $this->paginate('Friendlist');
             //pr($friend_list);exit();
      $this->set('friend_list',$friend_list);

    }


    function user_detail()
    {

  // $this->_checkSessionUser();

      $this->layout = "";
      $this->_checkSessionUser();
      $user_id = $this->Session->read('userData.User.id');
      $this->set('pageTitle', 'User Detail');
      
        $condition4 = "User.status= '1' AND  User.id = '".$user_id."'";
        $user_details = $this->User->find('first',array('conditions'=>$condition4));
            
    
       return $user_details;
      // exit;
       //pr($UserDetailType);exit();

    }

    function TimeCalculate($currentDate,$PostedDate)
        {
            $currentDate = date('Y-m-d H:i:s',$currentDate);
            $PostedDate = date('Y-m-d H:i:s',$PostedDate);
           // pr($currentDate);
           //  pr($PostedDate);exit();
            $day='';
            $hour='';
            $TotalMinute='';
            $TotalSecond='';
            
            $data = $this->User->query("SELECT TIMEDIFF( '".$currentDate."','".$PostedDate."') as `diff` ");
            
            $FullDate = $data[0][0]['diff'];
            
            $minus=substr($FullDate,0,1);
            if($minus=='-')
            {
                return '';
            }
            else
            {
                $gethour = $this->User->query("SELECT HOUR('".$FullDate."') as `hour`" );
                
                $TotalHour =  $gethour[0][0]['hour'];
                
                
                $getMinute = $this->User->query("SELECT MINUTE('".$FullDate."') as `minute`" );
                $TotalMinute =  $getMinute[0][0]['minute'];
                
                if($TotalMinute=='00' || $TotalMinute=='0')
                {
                    $TotalMinute='0 min';
                }
                else if($TotalMinute>1)
                {
                    
                    $TotalMinute=$TotalMinute.' mins';
                }
                else
                {
                    $TotalMinute=$TotalMinute.' min';
                }
            
                
                $getSecond = $this->User->query("SELECT SECOND('".$FullDate."') as `second`" );
                $TotalSecond =  $getSecond[0][0]['second'];
                
                if($TotalSecond=='00' || $TotalSecond=='0') 
                {
                    $TotalSecond='0 sec';
                }
                else if($TotalSecond>1)
                {
                    
                    $TotalSecond=$TotalSecond.' secs';
                }
                else
                {
                    $TotalSecond=$TotalSecond.' sec';
                }
                
                // get no of days from the total number of hours    ==>> START
                if($TotalHour>24)       // if no of hours is more than a day
                {
                    $day=(int)($TotalHour/24);
                    $day= $day." days";
                    
                    
                    $hour=$TotalHour % 24;
                    
                    if($hour=='0' || $hour=='00')
                    {
                        $hour='';
                        
                    }
                    else
                    {   
                        if($hour >1)
                        {
                            $hour= $hour." hrs";
                        }
                        else
                        {
                            $hour= $hour." hr";
                        }
                        
                        
                    }
                }
                else if($TotalHour == '24') // if no of hours is equal to a single day(24)
                {
                    
                    $day= '1'." day";
                    
                    $hour='';
                    
                }   
                else if($TotalHour < '24' && $TotalHour > '00') // if no of hours is less than a day but greater than 00 hour 
                {
                    
                    $hour=$TotalHour;
                    $hour= $hour." hrs";
                    
                }
                // get no of days from the total number of hours    ==>> END
                
                $PostBefore=$day.' '.$hour.' '.$TotalMinute;
                
                if($day>0)
                {
                    $PostBefore=$day;
                }
                elseif($hour>0)
                {
                    $PostBefore=$hour;
                }
                elseif($TotalMinute>0)
                {
                    $PostBefore=$TotalMinute;
                }
                else
                {
                    $PostBefore=  'few sec';
                } 
                              
                $PostBefore = $PostBefore;
                return $PostBefore;
            }
        }


         function post_tym($notification_id)
        {
          //$created_date = $_REQUEST['date'];
//echo $created_date;
          $con_noti_dtls = "Notification.id = '".$notification_id."' AND Notification.status = '1'";
       $noti_dtls = $this->Notification->find('first',array('conditions'=>$con_noti_dtls));  

                    $now = strtotime(date('Y-m-d H:i:s'));
                    $post_date = strtotime($noti_dtls['Notification']['created']);
                  //  echo $now;
                    //echo $post_date;
                    $time = $this->TimeCalculate($now,$post_date);
                   
                  //echo $time;
               return $time;
              
  
        }

  


    function notification_list()
    {
       
          
           $this->layout = "";
           $this->_checkSessionUser();
       
           $user_id = $this->Session->read('userData.User.id');

           $con_user_dtls = "User.id = '".$user_id."' AND User.status = '1'";
       $is_user_exists = $this->User->find('count',array('conditions'=>$con_user_dtls));  
       if( $is_user_exists >0)
       {

       $condition = "Notification.receiver_id = '".$user_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P'))))";
      
       $notification_list = $this->Notification->find('all',array('conditions'=>$condition,'order'=>'Notification.id DESC'));  
       //pr($notification_list);
       $this->set('notification_list',$notification_list);
       }
        
        
    }

     function update_profile()
        {
            $this->layout = "";
            $this->set('pagetitle', 'Welcome to Grouper');
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');


               $condition_user_detail = "User.id = '".$user_id."'";
                $UserData = $this->User->find('first', array('conditions' => $condition_user_detail));
                //pr($UserData);exit();
                $this->set('UserData', $UserData);
        

                if (!empty($this->params['form']) && isset($this->params['form'])) {
                  if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= '')
        
        {
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'user_images/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=640 && $uploaded_height >= 480)
                {
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original))
                      {
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'user_images/thumb/'.$upload_image;
                            $upload_target_medium = 'user_images/medium/'.$upload_image;
                            
                           

                            $max_web_width =  262;
                            $max_web_height = 178;
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
                            
                         $is_upload = 1;
                         $this->data['User']['image'] = $upload_image;    
                                                                                    
                      }   
                       
                    else  
                      {
                        
                    $is_upload = 0;
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    $this->redirect( Router::url( $this->referer(), true ) );

                    // $this->redirect("/group/group_detail/".$group_id);
                
                      }
                
                }
                else
                {        
                 
                 $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
                //$this->redirect("/group/group_detail/".$group_id);
                $this->redirect( Router::url( $this->referer(), true ) );


                
                }
                
           }
           else
           {
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect( Router::url( $this->referer(), true ) );
                //$this->redirect("/group/group_detail/".$group_id);
              
           }
        
        }
       
          else{
                $is_upload = 1;
              $this->data['User']['image'] = $UserData['User']['image'];
              }
          if( $is_upload == 1)
          {                
                                     
                    
                    $this->data['User']['fname'] = addslashes($this->params['form']['fname']);
                    $this->data['User']['lname'] = addslashes($this->params['form']['lname']);
                    $this->data['User']['groups'] = $UserData['User']['groups'];
                   

                               $this->data['User']['id'] = $user_id;
                          if ($this->User->save($this->data['User']))
              {

                  
                  $this->User->query("UPDATE friendlists SET friend_name ='".$this->params['form']['fname'].' '.$this->params['form']['lname']."' WHERE receiver_id = '".$user_id."'");


                        $this->Session->setFlash(__d("statictext", "User Profile Edited Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        //$this->redirect("/group/group_detail/".$group_id);
                        $this->redirect( Router::url( $this->referer(), true ) );


              }
          }
        }
      }


      function testimonials()
    {
      $this->layout = "home_inner";
      $this->_checkSessionUser();
      $user_id = $this->Session->read('userData.User.id');
       $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');
        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);
      $this->set('pageTitle', 'Friends');


         $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
            $ArState = $this->State->find('all', array('conditions' => $conditions));
            $this->set('ArState', $ArState);

        $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
            $StateName = $this->State->find('first', array('conditions' => $conditions));
            $this->set('StateName', $StateName);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
            $CityName = $this->City->find('first', array('conditions' => $conditions));
            $this->set('CityName', $CityName);

           $limit = 8;
           $condition_user_detail = "Testimonial.is_approved = '1'";
              $this->paginate = array('conditions' =>$condition_user_detail,'limit' => $limit,'order'=>'Testimonial.id DESC');
             $this->Testimonial->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))),false);
           
          //  $testimonial_list = $this->Testimonial->find('all',array('conditions'=>$condition_user_detail,'limit' => $limit));
        
                 $testimonial_list = $this->paginate('Testimonial');
            // pr($testimonial_list);exit();
      $this->set('testimonial_list',$testimonial_list);

    }


     function add_testimonial() {
        
       $this->layout = "home_inner";
        $this->set('pagetitle', 'Add Testimonial');
        $sitesettings = $this->getSiteSettings(); 
         $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
       
                        $this->data['Testimonial']['user_id'] = $user_id;
                        $this->data['Testimonial']['desc'] = stripcslashes($this->params['form']['desc']);
                        $this->data['Testimonial']['is_approved'] = '0';
                        $this->Testimonial->create();
                        if($this->Testimonial->save($this->data['Testimonial']))
                          {
                        
                             $this->Session->setFlash(__d("statictext", "Testimonial added sucessfully", true));
                             $_SESSION['meesage_type'] = '1';
                             $this->redirect("/home/testimonials/");
                          }
      } 


      function business_event_calender() 

       {

          $this->layout = "home_content";
          $this->set('pagetitle', 'Welcome to Grouper');
          
         
          
          $date = date('Y-m-d');

           $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1'  AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
            

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
                $this->set('event_list',$event_list);
            
      }


       function business_event_list() 

       {

                   
          $selectdate = $_REQUEST['total_dt']; 

          $this->layout = "";

          

          $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B'AND `status` = '1' AND `event_date` = '".$selectdate."' UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_start_date` <= '".$selectdate."' AND `event_end_date` >= '".$selectdate."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
            

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
                 
       
         // business my calender end //

    }



      function community_event_calender() 

       {

          $this->layout = "home_content";
          $this->set('pagetitle', 'Welcome to Grouper');
          
         
          
          $date = date('Y-m-d');

           $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `type` = 'public' AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `type` = 'public' AND `status` = '1'  AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
            

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
                $this->set('event_list',$event_list);
            
      }


       function community_event_list() 

       {

                   
          $selectdate = $_REQUEST['total_dt']; 

          $this->layout = "";

          

          $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `type` = 'public' AND `status` = '1' AND `event_date` = '".$selectdate."' UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `type` = 'public' AND `status` = '1' AND `event_start_date` <= '".$selectdate."' AND `event_end_date` >= '".$selectdate."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
            

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
                 
       
         // business my calender end //

    }





      


    
}?>
