<?php

class HomeController extends AppController {

    var $name = 'Home';
    var $uses = array('User','SiteSetting','EmailTemplate','State','City','GroupUser','StaticContent','Group','Event','Notification','Friendlist','Testimonial','Category');
    var $helpers = array("Html", "Form", "Javascript", "Fck", 'Js', "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
		
   	

    function index() {
        $this->layout = "home_landing";
        $this->set('pagetitle', 'Welcome to Grouper');
		//$this->_checkSessionUser();
      	$loggedin_user_id = $this->Session->read('userData.User.id');

        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);

         /****************************** Starts *****************************/
         if($selected_state_id>0 && $selected_city_id>0){
			
			
			$this->set('selected_state_id',$selected_state_id);
			$this->set('selected_city_id',$selected_city_id);
			
			$conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
			$StateArr = $this->State->find('first', array('conditions' => $conditions));
			$this->set('StateName', $StateArr['State']['name']);
			
			$conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
			$CityArr = $this->City->find('first', array('conditions' => $conditions));
			$this->set('CityName', $CityArr['City']['name']);
			
		}

		$conditions = "State.isdeleted = '0' AND State.country_id = '254'";
		$ArState = $this->State->find('all', array('conditions' => $conditions));
		$this->set('ArState', $ArState);

		$condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
		$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
		$this->set('citylist',$citylist); 
		/******************************Ends *****************************/
               
        $condition = "User.status= '1' ";
        $featured_user = $this->User->find("first",array('conditions'=>$condition,'order' => 'User.id DESC'));
        $this->set('featured_user',$featured_user);

        $condition_user = "User.id= '".$loggedin_user_id."'";
        $loggedinuser = $this->User->find("first",array('conditions'=>$condition_user));
        //pr($loggedinuser);
        $this->set('loggedinuser',$loggedinuser);


        

       
         
		$condition_user_detail = "Testimonial.is_approved = '1' AND Testimonial.is_featured = '1'";
		$this->Testimonial->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
	   
		$testimonial_list = $this->Testimonial->find('all',array('conditions'=>$condition_user_detail,'order' => 'Testimonial.id DESC'));
		$this->Testimonial->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
	   
		$this->set('testimonial_list',$testimonial_list);

		
        $contents = $this->StaticContent->find("all",array('order' => 'StaticContent.id ASC'));
        //pr($contents);exit();
        $this->set('contents',$contents);
      

    }

    function show_city()
    {
		$stateid = $_REQUEST['state_id'];
		$condition = "City.isdeleted = '0' AND City.state_id='".$stateid."'";
		$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
		$this->set('citylist',$citylist);
	}

    function show_index_city()
    {
		$stateid = $_REQUEST['state_id'];
		$condition = "City.isdeleted = '0' AND City.state_id='".$stateid."'";
		$indexcitylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
		$this->set('indexcitylist',$indexcitylist);
	}

	function admin_show_index_city()
    {
		$stateid = $_REQUEST['state_id'];
		$condition = "City.isdeleted = '0' AND City.state_id='".$stateid."'";
		$indexcitylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
		$this->set('indexcitylist',$indexcitylist);
	}

    
	 function signup(){ 
		$this->layout = "";
		$this->set('pagetitle', 'Sign Up');
		$sitesettings = $this->getSiteSettings();
  
	if (isset($this->params['form']) && !empty($this->params['form'])) 
	{
			$upload_image = '';
		
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
						if($uploaded_width >=160 && $uploaded_height >= 120)
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
			$countUser = $this->User->find('first', array('conditions' => array('User.email' => $email)));
			

			if(empty($countUser)) // Insert new user
			{
						
					

					$this->data['User']['fname'] = stripslashes($this->params['form']['first_name']);
					$this->data['User']['lname'] = stripslashes($this->params['form']['last_name']);  
					$this->data['User']['image'] = $upload_image;
					$this->data['User']['username'] = stripslashes($username);
					$this->data['User']['email'] = $email; 
					$this->data['User']['password'] = $password;  
					$this->data['User']['txt_password'] = $txt_password; 
					$this->data['User']['status'] = '0'; 
					$this->data['User']['groups'] = '';
					$this->data['User']['state_id'] = $this->params['form']['state_id'];
					$this->data['User']['city_id'] = $this->params['form']['index_city_id'];
					$this->data['User']['device_type'] = 'web';


					$this->User->create(); 
					if ($this->User->save($this->data)) 
					
					{

					  $last_insert_id = $this->User->getLastInsertId();
											

					$user_name = ucfirst($this->params['form']['first_name']).' '.ucfirst($this->params['form']['last_name']); 
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
							$this->Session->setFlash("You are now a Grouper.The registration is not yet completed. You will receive a verification email with the instructions. ");    
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
				if($countUser['User']['status']== 1 ) //email already exist
				{
					$this->Session->setFlash("Email already exist");    
					$_SESSION['meesage_type'] = '1';
					$this->redirect(array('controller'=>'home','action'=>'index'));
				}
				else
				{
					if($countUser['User']['is_invite']== 1)  //update all info
					{
						$condition_user_detail = "User.id = '".$countUser['User']['id']."'";
						$email_details = $this->User->find('first', array('conditions'=>$condition_user_detail));
					
						
						$this->data['User']['fname'] = stripslashes($this->params['form']['first_name']);
						$this->data['User']['lname'] = stripslashes($this->params['form']['last_name']);  
						$this->data['User']['password'] = $password;
						$this->data['User']['txt_password'] = $txt_password;
						$this->data['User']['username'] = stripslashes($username);
						$this->data['User']['image'] = $upload_image;
						
						$this->data['User']['state_id'] = $this->params['form']['state_id'];
						$this->data['User']['city_id'] = $this->params['form']['index_city_id'];
						$this->data['User']['device_type'] = 'web';
						$this->data['User']['groups'] = '';
						$this->data['User']['is_invite'] = '0';
					   
					   $this->User->id = $email_details['User']['id'];
						   
						if($this->User->save($this->data['User']))
						{
				   // $insert_id = $this->User->getLastInsertID(); 
					$last_insert_id = $email_details['User']['id'];
										   

					$user_name = ucfirst($this->params['form']['first_name']).' '.ucfirst($this->params['form']['last_name']); 
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
							$this->Session->setFlash("You are now a Grouper.The registration is not yet completed. You will receive a verification email with the instructions. ");    
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
					else // email already used//when they are adding a different name next time then after getting the second activation link if they activete the account.they wont be able to get the name of the new they will get the previous details in their account.
					{

						$condition_user_detail = "User.id = '".$countUser['User']['id']."'";
						$email_details = $this->User->find('first', array('conditions'=>$condition_user_detail));

						$last_insert_id = $email_details['User']['id'];
										   

					$user_name = ucfirst($email_details['User']['fname']).' '.ucfirst($email_details['User']['lname']); 
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
							$this->Session->setFlash("This email id is already used and a new activation link is already sent.");    
							$_SESSION['meesage_type'] = '1';
								 $this->redirect(array('controller'=>'home','action'=>'index'));

							
						  }
						  else
						  {
							$this->Session->setFlash("Failed to send email.");    
							$_SESSION['meesage_type'] = '1';
							  $this->redirect(array('controller'=>'home','action'=>'index'));
						  }
						  //$this->redirect(array('controller'=>'home','action'=>'index'));
									
						
					}
				}

			}
			

			}
		}
		 
	


	 function signup_copy(){ 
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
						if($uploaded_width >=160 && $uploaded_height >= 120)
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
			

			if($countUser > 0)
			{
						
				$this->Session->setFlash(__d("statictext", "You are already registered in this email", true));
				$_SESSION['meesage_type'] = '0';
				$this->redirect(array('controller'=>'home','action'=>'index'));

			}
			
			else {
				$email_is_exist = $this->User->find('first', array('conditions'=>array('email'=>$email,'status'=>'0','is_invite' => '1')));
				
					if(!empty($email_is_exist))
					{
						$condition_user_detail = " User.id = '".$email_is_exist['User']['id']."' AND User.status = '0' ";
						$email_details = $this->User->find('first', array('conditions'=>$condition_user_detail));
					
						
						$this->data['User']['fname'] = stripslashes($this->params['form']['first_name']);
						$this->data['User']['lname'] = stripslashes($this->params['form']['last_name']);  
						$this->data['User']['password'] = $password;
						$this->data['User']['txt_password'] = $txt_password;
						$this->data['User']['username'] = stripslashes($username);
						$this->data['User']['image'] = $upload_image;
						$this->data['User']['device_token'] = $device_token;
						$this->data['User']['state_id'] = $this->params['form']['state_id'];
						$this->data['User']['city_id'] = $this->params['form']['index_city_id'];
						$this->data['User']['device_type'] = 'web';
						$this->data['User']['groups'] = '';
						$this->data['User']['is_invite'] = '0';
					   
					   $this->User->id = $email_details['User']['id'];
						   
						if($this->User->save($this->data['User']))
						{
				   // $insert_id = $this->User->getLastInsertID(); 
					$last_insert_id = $email_details['User']['id'];
										   

					$user_name = ucfirst($this->params['form']['first_name']).' '.ucfirst($this->params['form']['last_name']); 
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
							$this->Session->setFlash("You are now a Grouper.The registration is not yet completed. You will receive a verification email with the instructions. ");    
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

					$this->data['User']['fname'] = stripslashes($this->params['form']['first_name']);
					$this->data['User']['lname'] = stripslashes($this->params['form']['last_name']);  
					$this->data['User']['image'] = $upload_image;
					$this->data['User']['username'] = stripslashes($username);
					$this->data['User']['email'] = $email; 
					$this->data['User']['password'] = $password;  
					$this->data['User']['txt_password'] = $txt_password; 
					$this->data['User']['status'] = '0'; 
					$this->data['User']['groups'] = '';
					$this->data['User']['state_id'] = $this->params['form']['state_id'];
					$this->data['User']['city_id'] = $this->params['form']['index_city_id'];
					$this->data['User']['device_type'] = 'web';


					$this->User->create(); 
					if ($this->User->save($this->data)) 
					
					{

					  $last_insert_id = $this->User->getLastInsertId();
											

					$user_name = ucfirst($this->params['form']['first_name']).' '.ucfirst($this->params['form']['last_name']); 
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
			$this->Session->setFlash("You are now a Grouper.The registration is not yet completed. You will receive a verification email with the instructions. ");    
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
		 
	 }



    function login() {
        
	$this->layout = "";
	$this->set('pageTitle', 'Login');
	
	

	if (!empty($this->params['form'])) {

	$password = md5($this->params['form']['password']);
	$txt_password = $this->params['form']['password'];
	$email = $this->params['form']['username'];

		$condition = "User.email = '".$email."' AND User.txt_password = '".$txt_password."' AND User.password = '".$password."' AND User.status = '1'";
		$detail = $this->User->find('first',array('conditions'=>$condition));
		//pr($detail);exit();
		//$is_exist = $this->User->find('count', array('conditions' => $condition));
		if(!empty($detail)){
		  
		  if ($detail['User']['status'] == 1){
		  
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
				$this->Session->setFlash(__d("statictext", "Incorrect email password!", true));
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
			$this->Session->setFlash(__d('statictext', "Your account is activated . Please login using your Email and Password", true));
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

	

    function my_group(){

        $this->layout = "home_inner";
        $this->set('pagetitle', 'My Group');
        $this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');
        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);
		
		/**************************    selected state and city starts   ****************/
		$selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');
        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);
		
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
		
		/***********************************Ends ***************************************/

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
			
			/************          Fetch free groups starts      ***************/
		    $cond_free_grps = "Group.status= '1'  AND Group.group_type='F'  AND Group.id IN (".$str_allgroups.")";
			$count_free_groups=  $this->Group->find('count',array('conditions'=>$cond_free_grps ));
			 
		  	if($count_free_groups>0){
			
				$limit_f = 12;
				$lastpage_f = ceil($count_free_groups/$limit_f);
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
				
				$all_free_groups = $this->Group->find('all',array('conditions' =>$cond_free_grps, 'offset' => $start_f, 'limit' => $limit_f, 'order' => 'Group.group_title ASC'));
	
				$this->set('all_free_groups',$all_free_groups); 
			}
			else{
				$this->set('all_free_groups',''); 
			} 

			//pr($all_free_groups);exit();
			
			/************          Fetch free groups ends      *****************/
			
			/************          Fetch business groups starts      ***************/
		    $cond_business_grps = "Group.status= '1'  AND Group.group_type='B'  AND Group.id IN (".$str_allgroups.")";
		    $count_business_groups=  $this->Group->find('count',array('conditions'=>$cond_business_grps ));
			
			if($count_business_groups>0){
			
				$limit_b = 12;
				$lastpage_b = ceil($count_business_groups/$limit_b);
				$start_b=0;
				$page_b = 1;
				$prev_b = $page_b - 1;
				$next_b = $page_b + 1;
				$lpm1_b = $lastpage_b - 1;
				
				$this->set('limit_b',$limit_b); 
				$this->set('lastpage_b',$lastpage_b); 
				$this->set('start_b',$start_b); 
				$this->set('page_b',$page_b); 
				$this->set('prev_b',$prev_b); 
				$this->set('next_b',$next_b); 
				$this->set('lpm1_b',$lpm1_b);
				
				$all_business_groups = $this->Group->find('all',array('conditions' =>$cond_business_grps, 'offset' => $start_b, 'limit' => $limit_b, 'order' => 'Group.group_title ASC'));
	
				$this->set('all_business_groups',$all_business_groups); 
			}
			else{
				$this->set('all_business_groups',''); 
			}
			
			/************          Fetch business groups ends      *****************/
		}
		
    }

	function my_group_free(){
	
		$this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');
		
		 $condition_my_groups = "GroupUser.status= '1'  AND ( GroupUser.user_type = 'O' OR GroupUser.user_type = 'M') AND GroupUser.user_id = '".$user_id."' ";
        $all_my_groups = $this->GroupUser->find('all',array('conditions'=>$condition_my_groups ));
		if(empty($all_my_groups)){
			 $this->set('all_free_groups',''); 	
		}
		else{
			$groups = array(); 
			
			foreach($all_my_groups as $grp){
				array_push($groups, $grp['GroupUser']['group_id']);
			}
			
			$str_allgroups = implode(",",$groups);
			
			$cond_free_grps = "Group.status= '1'  AND Group.group_type='F'  AND Group.id IN (".$str_allgroups.")";
			$count_free_groups=  $this->Group->find('count',array('conditions'=>$cond_free_grps ));
		
			if($count_free_groups>0){
				$limit = 12;
				$lastpage = ceil($count_free_groups/$limit);
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
				
				$all_free_groups = $this->Group->find('all',array('conditions' =>$cond_free_grps, 'offset' => $start, 'limit' => $limit, 'order' => 'Group.group_title ASC'));
				
				$this->set('all_free_groups',$all_free_groups); 
			}
			else{
				$this->set('all_free_groups',''); 
			}
			
		}
	}
	
	
	function my_group_business(){
	
		$this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');
		
		 $condition_my_groups = "GroupUser.status= '1'  AND ( GroupUser.user_type = 'O' OR GroupUser.user_type = 'M') AND GroupUser.user_id = '".$user_id."' ";
        $all_my_groups = $this->GroupUser->find('all',array('conditions'=>$condition_my_groups ));
		if(empty($all_my_groups)){
			 $this->set('all_business_groups',''); 	
		}
		else{
			$groups = array(); 
			
			foreach($all_my_groups as $grp){
				array_push($groups, $grp['GroupUser']['group_id']);
			}
			
			$str_allgroups = implode(",",$groups);
			
			$cond_business_grps = "Group.status= '1'  AND Group.group_type='B'  AND Group.id IN (".$str_allgroups.")";
			$count_business_groups=  $this->Group->find('count',array('conditions'=>$cond_business_grps ));
		
			if($count_business_groups>0){
				$limit = 12;
				$lastpage = ceil($count_business_groups/$limit);
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
				
				$all_business_groups = $this->Group->find('all',array('conditions' =>$cond_business_grps, 'offset' => $start, 'limit' => $limit, 'order' => 'Group.group_title ASC'));
				
				$this->set('all_business_groups',$all_business_groups); 
			}
			else{
				$this->set('all_business_groups',''); 
			}
			
		}
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


	/*************************starts****************************/
	
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
	/*************************ends **********************************/

      $limit = 10;
      $condition_friend_detail = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
      $this->paginate = array('conditions' =>$condition_friend_detail,'limit' => $limit);
      $this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'sender_id'))),false);
           
      $friend_list = $this->paginate('Friendlist');
      
      $this->set('friend_list',$friend_list);

    }

	function confirm_remove_friend()
	{
	
		$this->layout = "";
      	$this->_checkSessionUser();
     	$user_id = $this->Session->read('userData.User.id');	
		
     	
     	if(isset($this->params['form']['mode']) && $this->params['form']['mode'] == 'removefriend')
     	{
			$friend_id = $this->params['form']['friend_id'];
			
     		$this->Friendlist->query("DELETE FROM friendlists WHERE ((`sender_id` = '".$user_id."' AND `receiver_id` = '".$friend_id."') OR (`sender_id` = '".$friend_id."' AND `receiver_id` = '".$user_id."'))");
			
			$this->Chat->query("DELETE FROM chats WHERE ((`from_id` = '".$user_id."' AND `to_id` = '".$friend_id."') OR (`from_id` = '".$friend_id."' AND `to_id` = '".$user_id."'))");
     	}

     	$this->Session->setFlash(__d("statictext", "Friend removed sucessfully", true));
        $_SESSION['meesage_type'] = '1';
        $this->redirect("/home/friends/");


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
	
			   $condition = "Notification.receiver_id = '".$user_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P') )))";
			  
			   $notification_list = $this->Notification->find('all',array('conditions'=>$condition,'order'=>'Notification.id DESC'));  
			   /*echo '<pre>';
			   print_r($notification_list);
			   echo '</pre>';exit;*/
			   $this->set('notification_list',$notification_list);
			   
			   /*$condition_unread = "Notification.receiver_id = '".$user_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
			   $notification_unread_list = $this->Notification->find('all',array('conditions'=>$condition,'order'=>'Notification.id DESC'));
			   if(!empty($notification_unread_list)){
			   		foreach($notification_unread_list as $events){
					
					}
			   }*/
			   
			   $this->Notification->query("UPDATE `notifications` SET is_read ='1' WHERE receiver_id = '".$user_id."' AND status = '1' AND  ((is_reversed_notification = '0' AND is_receiver_accepted = '0' ) OR (is_reversed_notification = '1' AND is_receiver_accepted != '0') OR (is_receiver_accepted = '2' AND is_reversed_notification = '0' AND( (type = 'E') OR (type = 'P')))) AND is_read='0'");
		   }
        
        
    }
	
	function unread_notifications(){
		
		 $this->layout = "";
         $this->_checkSessionUser();
       
         $user_id = $this->Session->read('userData.User.id');
		 $condition = "Notification.receiver_id = '".$user_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
		  
	     $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
		 //echo '====>'.$this->getLastQuery();exit;
	     //pr($notification_count);
	     return $notification_count;
	}


	function check_unread_notifications(){
		
		 $user_id = $this->Session->read('userData.User.id');
		 $condition = "Notification.receiver_id = '".$user_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
		  
	     $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
		 //echo '====>'.$this->getLastQuery();exit;
	     //pr($notification_count);
	     echo $notification_count;
	     exit;
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

                	$condition_check_email = "User.id != '" . $user_id . "' AND User.email = '" . $this->params['form']['email'] . "'";

    				$CheckEmail = $this->User->find('count', array('conditions' => $condition_check_email));

    				if ($CheckEmail > 0) {

    					 $this->Session->setFlash(__d("statictext", "Sorry!! You have already used this mail id in some other account.", true));
                         $_SESSION['meesage_type'] ='0';
                         $this->redirect( Router::url( $this->referer(), true ) );

    				}

    				else
    				{
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
                if($uploaded_width >=160 && $uploaded_height >= 120)
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
                    $this->data['User']['city_id'] = $this->params['form']['city_id'];
                    $this->data['User']['state_id'] = $this->params['form']['state_id'];
                    
                    $this->data['User']['email'] = $this->params['form']['email'];
                   
                   

                               $this->data['User']['id'] = $user_id;
                          if ($this->User->save($this->data['User']))
              {

                  $this->Session->write('selected_state_id', $this->params['form']['state_id']);
        		  $this->Session->write('selected_city_id', $this->params['form']['city_id']);
        		  
                  $this->User->query("UPDATE friendlists SET friend_name ='".$this->params['form']['fname'].' '.$this->params['form']['lname']."' WHERE receiver_id = '".$user_id."'");


                        $this->Session->setFlash(__d("statictext", "User Profile Edited Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        //$this->redirect("/group/group_detail/".$group_id);
                        $this->redirect( Router::url( $this->referer(), true ) );


              }
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
	  
      $this->set('pageTitle', 'Testimonials');

		/****************************** Starts **************************************/
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

		/*****************************Ends ****************************/
		
	    $limit = 8;
	    $condition_user_detail = "Testimonial.is_approved = '1'";
	    $this->paginate = array('conditions' =>$condition_user_detail,'limit' => $limit,'order'=>'Testimonial.created DESC');
	    $this->Testimonial->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))),false);
           
        //$testimonial_list = $this->Testimonial->find('all',array('conditions'=>$condition_user_detail,'limit' => $limit));
        
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
       
	    if(isset($this->params['form']['mode']) && $this->params['form']['mode']=='add_testimonial'){
			$this->data['Testimonial']['user_id'] = $user_id;
			$this->data['Testimonial']['desc'] = stripcslashes($this->params['form']['desc']);
			$this->data['Testimonial']['is_approved'] = '0';
			$this->Testimonial->create();
			if($this->Testimonial->save($this->data['Testimonial'])){
			
				 $this->Session->setFlash(__d("statictext", "Testimonial added sucessfully", true));
				 $_SESSION['meesage_type'] = '1';
				 $this->redirect("/home/testimonials/");
			}
		}
      } 


      function business_event_calender(){
       	
         	
			
			$this->layout = "home_inner";
			$user_id = $this->Session->read('userData.User.id');
	        $selected_state_id = $this->Session->read('selected_state_id');
			$selected_city_id = $this->Session->read('selected_city_id');
			
			if($selected_state_id>0 && $selected_city_id>0){
				
				
				$this->set('selected_state_id',$selected_state_id);
				$this->set('selected_city_id',$selected_city_id);

				$conditions_group = "Group.city_id = '".$selected_city_id."' AND Group.state_id = '".$selected_state_id."' AND Group.status = '1' ";
		          $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
		        
		          $group = array(); 
		            foreach($all_groups as $grp)
		            {

		              array_push($group,$grp['Group']['id']);
		            }
            		$allstatecitygroups = implode(",",$group);
				
				
				$conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
				$StateName = $this->State->find('first', array('conditions' => $conditions));
				$this->set('StateName', $StateName);
				
				$conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
				$CityName = $this->City->find('first', array('conditions' => $conditions));
				$this->set('CityName', $CityName);
				
			}
			
			
			$conditions = "State.isdeleted = '0' AND State.country_id = '254'";
			$ArState = $this->State->find('all', array('conditions' => $conditions));
			$this->set('ArState', $ArState);
	
			$condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
			$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
			$this->set('citylist',$citylist); 
          
       		$date = date('Y-m-d');
          
          	
          	$event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_date` = '".$date."' AND `group_id` IN (". $allstatecitygroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1'  AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."' AND `group_id` IN (". $allstatecitygroups .")) AS `Event`ORDER BY `Event`.`sort_time`ASC");
            


 			$event_list = array(); 
            
              foreach($event_details as $events){
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
                    
                    
                    array_push($event_list,$list);
              }
              $this->set('event_list',$event_list);

                 
            
      }


       function business_event_list(){
      
          $this->layout = "";
          $user_id = $this->Session->read('userData.User.id');
		  $selectdate = $_REQUEST['total_dt'];
		   $selected_state_id = $_REQUEST['selected_state_id'];
		    $selected_city_id = $_REQUEST['selected_city_id'];

		    $conditions_group = "Group.city_id = '".$selected_city_id."' AND Group.state_id = '".$selected_state_id."' AND Group.status = '1' ";
		          $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
		        
		          $group = array(); 
		            foreach($all_groups as $grp)
		            {

		              array_push($group,$grp['Group']['id']);
		            }
            		$allstatecitygroups = implode(",",$group);

          $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_date` = '".$selectdate."' AND `group_id` IN (". $allstatecitygroups .")  UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_start_date` <= '".$selectdate."' AND `event_end_date` >= '".$selectdate."' AND `group_id` IN (". $allstatecitygroups .")) AS `Event`ORDER BY `Event`.`sort_time`ASC");
            
          $event_list = array(); 
            
	      foreach($event_details as $events){
		  
                    $list = array();

                    $list['id'] = $events['Event']['id'];
                    $list['event_name'] =  $events['Event']['title'];
                    $list['desc'] =  $events['Event']['desc'];
                    $list['deal_amount'] =  $events['Event']['deal_amount'];
					
                    $condition = "Group.id = '".$events['Event']['group_id']."'";
                    $group_detail = $this->Group->find('first',array('conditions'=>$condition));
					$arr_group_owners= explode(',', $group_detail['Group']['group_owners']);
					  if(in_array($user_id, $arr_group_owners)){
						$list['show_edit'] = 1;
					  }
					  else{
						$list['show_edit'] = 0;
					  }
                    $list['group_id'] = $events['Event']['group_id'];
                    $list['group_name'] =  $group_detail['Group']['group_title'];
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
      // pr($event_list);exit();
     $this->set('event_list',$event_list);
     $this->set('selectdate',$selectdate);
                 
       
         // business my calender end //

    }



      function community_event_calender(){
			
			$this->set('pagetitle', 'Welcome to Grouper');
			
			$this->layout = "home_inner";
			$user_id = $this->Session->read('userData.User.id');
	        $selected_state_id = $this->Session->read('selected_state_id');
			$selected_city_id = $this->Session->read('selected_city_id');
			
	        if($selected_state_id>0 && $selected_city_id>0){
				
				
				$this->set('selected_state_id',$selected_state_id);
				$this->set('selected_city_id',$selected_city_id);

				$conditions_group = "Group.city_id = '".$selected_city_id."' AND Group.state_id = '".$selected_state_id."' ";
		          $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
		         // $str_group_owners= implode(',', $all_groups);
		          $group = array(); 
		            foreach($all_groups as $grp)
		            {

		              array_push($group,$grp['Group']['id']);
		            }
            		$allstatecitygroups = implode(",",$group);
				
				$conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
				$StateName = $this->State->find('first', array('conditions' => $conditions));
				$this->set('StateName', $StateName);
				
				$conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
				$CityName = $this->City->find('first', array('conditions' => $conditions));
				$this->set('CityName', $CityName);
				
			}
	       
		   	$conditions = "State.isdeleted = '0' AND State.country_id = '254'";
			$ArState = $this->State->find('all', array('conditions' => $conditions));
			$this->set('ArState', $ArState);
	
			$condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
			$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
			$this->set('citylist',$citylist); 
          
       		$date = date('Y-m-d');

       		$event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `type` = 'public'  AND `status` = '1'  AND `event_date` = '".$date."' AND `group_id` IN (". $allstatecitygroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `type` = 'public' AND `status` = '1'  AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."' AND `group_id` IN (". $allstatecitygroups .")) AS `Event`ORDER BY `Event`.`sort_time`ASC");

	   		$event_list = array(); 
		
	   		foreach($event_details as $events){
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
				
				
				array_push($event_list,$list);
			}
	   		$this->set('event_list',$event_list);

       
      }

       function community_event_list(){

          $this->layout = "";        
          $user_id = $this->Session->read('userData.User.id');  
          $selectdate = $_REQUEST['total_dt']; 
           $selected_state_id = $_REQUEST['selected_state_id'];
		    $selected_city_id = $_REQUEST['selected_city_id'];

		    $conditions_group = "Group.city_id = '".$selected_city_id."' AND Group.state_id = '".$selected_state_id."' AND Group.status = '1' ";
		          $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
		        
		          $group = array(); 
		            foreach($all_groups as $grp)
		            {

		              array_push($group,$grp['Group']['id']);
		            }
            		$allstatecitygroups = implode(",",$group);

          $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `type` = 'public' AND `status` = '1' AND `event_date` = '".$selectdate."' AND `group_id` IN (". $allstatecitygroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `type` = 'public' AND `status` = '1' AND `event_start_date` <= '".$selectdate."' AND `event_end_date` >= '".$selectdate."' AND `group_id` IN (". $allstatecitygroups .")) AS `Event`ORDER BY `Event`.`sort_time`ASC");
            

          $event_list = array(); 
            
          foreach($event_details as $events){
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
      
      
      				array_push($event_list,$list);
    }
      // pr($event_list);exit();
     $this->set('event_list',$event_list);
     $this->set('selectdate',$selectdate);
                 
       
         // business my calender end //

    }


    function fetch_states()
    {
		
		$condition_state = "State.isdeleted = '0' AND State.country_id = '254'";
        $state_list = $this->State->find('all', array('conditions' => $condition_state));
        return $state_list;
		//$this->set('state_list',$state_list);
	}

	 function fetch_cities($user_state_id=NULL)
    {
		$condition_city = "City.isdeleted = '0' AND City.state_id = '".$user_state_id."'";
        $city_list = $this->City->find('all', array('conditions' => $condition_city));
        return $city_list;
	}


	function contact_us(){
	
    	$this->layout = "home_inner";
        $this->set('pagetitle', 'Contact Us');
          
	  $sitesettings = $this->getSiteSettings();
      $this->set('site_name',$sitesettings['site_name']['value']);
      $this->set('site_url',$sitesettings['site_url']['value']);
      $this->set('site_email',$sitesettings['site_email']['value']);
      $this->set('sender_email',$sitesettings['sender_email']['value']);
      $this->set('email_sender_name',$sitesettings['email_sender_name']['value']);
      $this->set('site_application_email',$sitesettings['site_application_email']['value']);
      $this->set('contact_phone',$sitesettings['contact_phone']['value']);
      $this->set('contact_address',$sitesettings['contact_address']['value']);
      $this->set('contact_latitude',$sitesettings['contact_latitude']['value']);
      $this->set('contact_longitude',$sitesettings['contact_longitude']['value']);

      $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
            $state_list = $this->State->find('all', array('conditions' => $conditions));
            $this->set('state_list', $state_list);


	}
	
	function faq(){
    	$this->layout = "home_inner";
        $this->set('pagetitle', 'Faq');
    }
	
	function terms(){
    	$this->layout = "home_inner";
        $this->set('pagetitle', 'Terms');
    }

	 function submit_contact_us()
        {
            $this->layout = "home_content";
       		$this->set('pagetitle', 'Contact Us');
            $sitesettings = $this->getSiteSettings();

            if(isset($this->params['form']['mode']) && $this->params['form']['mode']=='contact')
            {
            	$name = $this->params['form']['full_name'];
            	$salutation = $this->params['form']['salutation'];
            	$email = $this->params['form']['email'];
            	$phone = $this->params['form']['phn_no1'].'-'.$this->params['form']['phn_no2'].'-'.$this->params['form']['phn_no3'];
            	$message = $this->params['form']['message'];

            
            

            /*$query_type    = 'General Query';
            $salutation    = 'Mr.';
            $name    = 'SOVAN BISWAS';
            $email    = 'taniasrivastav007@gmail.com';
            $phone    = '445645645';
            $state_id    = '24';
            $city_id    = '45';
            $zipcode    = '756765';
            $address    = 'Kolkata,ffffgfgfg,dfgdfgf';
            $message    = 'gjkdfhghdfkhg fhgdfgj dfghgjk gdfghfjhgjfkgh fkhgfkghf gghdfgjhgjfhgkdfhgfjkhfgjdfhgjgdfhgjfhhgfhgfjhjghkfgjkfhgfdk';*/
            
                                          

                    $user_name = $salutation.' '.$name; 
                    $query_type = $query_type; 
                    $message = $message; 
                    $phone = $phone;


                    $sender_email = $email;
                    $site_url = $sitesettings['site_url']['value'];
                    $sender_name = $user_name;

                    $condition = "EmailTemplate.id = '4'";
                    $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                    
                    $to = $sitesettings['site_email']['value'];

                    $user_subject = $mailDataRS['EmailTemplate']['subject'];
                    $user_subject = str_replace('[SITE NAME]', 'Grouper |', $user_subject);
                               
                 
                    $user_body = $mailDataRS['EmailTemplate']['content'];
                    $user_body = str_replace('[USERNAME]', $user_name, $user_body);
                    $user_body = str_replace('[QUERYTYPE]', $query_type, $user_body);
                    $user_body = str_replace('[PHONE]', $phone, $user_body);
                    $user_body = str_replace('[EMAIL]', $sender_email, $user_body);
                    $user_body = str_replace('[MESSAGE]', $message, $user_body);
                    
                                     
                    $user_message = stripslashes($user_body);
                    
           
                   $string = '';
                   $filepath = '';
                   $filename = '';
                   $sendCopyTo = '';
           
           
                    $sendmail = sendmail($sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                
                        
                $this->Session->setFlash(__d("statictext", "Your query is submitted sucessfully.", true));
                $_SESSION['meesage_type'] = '1';
				$this->redirect("/home/contact_us/");
                              
        }

    }

    function get_suggestion_list_users(){
	  
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$search_text = $_REQUEST['search_text']; 
			//$search_text = trim($search_new_text); 
			//pr($search_text);exit();
			//$group_id = $_REQUEST['group_id']; 
			$str_selected_users = $_REQUEST['str_selected_users'];
			$arr_selected_users= explode(',', $str_selected_users);
			
			$arr_notified_users=array();
		
			//$condition_user_list = "User.status = '1' AND (User.fname ='".$search_text."') AND User.id != '".$user_id."'";
			//$search_user_list = $this->User->find('all',array('conditions'=>$condition_user_list,'order' => array('User.fname ASC')));

			$search_user_list = $this->User->query("SELECT * FROM ( SELECT *, CONCAT(fname, ' ', lname) as FullName FROM users) User WHERE FullName = '".$search_text."' AND status='1' AND id !='".$user_id."'"); 
	
	
			###############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected starts  #################
			
			$con_is_req_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";
			$arr_req_sent_users = $this->Notification->find('all',array('conditions'=>$con_is_req_sent, 'fields'=>array('Notification.receiver_id'))); 
			if(!empty($arr_req_sent_users)){	
				foreach($arr_req_sent_users as $key3 => $val3){	
						array_push($arr_notified_users, $val3['Notification']['receiver_id']);		
				}
			}
			
			
			###############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected ends  #################
			
			
			$arr_not_group_users=array();
			
			if(count($search_user_list) > 0)
			{
				foreach($search_user_list as $key => $val)
				{
					
					$con_is_member = "((Friendlist.sender_id = '".$user_id."' AND Friendlist.receiver_id = '".$val['User']['id']."') OR (Friendlist.receiver_id = '".$user_id."' AND Friendlist.sender_id = '".$val['User']['id']."'))";
					$count_is_member = $this->Friendlist->find('count',array('conditions'=>$con_is_member));
					if($count_is_member == 0){		// Those who are not the friend
					
					if(!empty($arr_notified_users)){
						if(!in_array($val['User']['id'], $arr_notified_users)){		//Those who are not being notified 
							if(!empty($arr_selected_users)){
								if(!in_array($val['User']['id'], $arr_selected_users)){		//Those who are not selected 
									array_push($arr_not_group_users, $val);	
								}
							}
							else{
								array_push($arr_not_group_users, $val);	
							}
						}
					}
					else{
							if(!empty($arr_selected_users)){		//Those who are not selected
								if(!in_array($val['User']['id'], $arr_selected_users)){
									array_push($arr_not_group_users, $val);	
								}
							}
							else{
								array_push($arr_not_group_users, $val);	
							}
					}
						
					}
				}
				$this->set('arr_search_result',$arr_not_group_users);	
			}
			else{			
			//when search text does not match or all users belong to the group already
				$this->set('arr_search_result',$arr_not_group_users);	
			}
			
	}


	function submit_invitation(){
	  
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
		
			//$group_id = $_REQUEST['group_id'];	
			$mode = $_REQUEST['mode'];
			$sender_type = $_REQUEST['sender_type'];
			//$group_type = $_REQUEST['group_type'];
			
				
			 if(isset($mode) && $mode =='invite_users'){
				
				$str_users= $_REQUEST['str_users'];	
				$arr_users= explode(',', $str_users);
				
				for($i=0; $i<count($arr_users); $i++){
						$this->data['Notification']['sender_id'] = $user_id;
						$this->data['Notification']['receiver_id'] = $arr_users[$i];
						$this->data['Notification']['type'] = 'F';
						$this->data['Notification']['group_type'] = 'F';
						
						$this->data['Notification']['is_read'] = 0;
						$this->data['Notification']['is_receiver_accepted'] = 0;
						$this->data['Notification']['is_reversed_notification'] = 0;
						$this->data['Notification']['status'] = 1;
					
					$this->Notification->create();
					$this->Notification->save($this->data['Notification']);

					$last_insert_id = $this->Notification->getLastInsertId(); 
                      // send notification email for friend request accept
                       
                    $this->notification_email($last_insert_id);
                  

				}
				echo "1";
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
	
			function getLastQuery() 
			{
			  $dbo = ConnectionManager::getDataSource('default');
			  $logs = $dbo->getLog();
			  $lastLog = end($logs['log']);
			  return $lastLog['query'];
		    }

		  function featured_group()
		  {
		  	$this->layout = "home_inner";
	        $this->set('pagetitle', 'Welcome to Grouper');
	        $user_id = $this->Session->read('userData.User.id');
	        $selected_state_id = $this->Session->read('selected_state_id');
			$selected_city_id = $this->Session->read('selected_city_id');
			
	        if($selected_state_id>0 && $selected_city_id>0){
				
				
				$this->set('selected_state_id',$selected_state_id);
				$this->set('selected_city_id',$selected_city_id);
				
				$conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
				$StateName = $this->State->find('first', array('conditions' => $conditions));
				$this->set('StateName', $StateName);
				
				$conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
				$CityName = $this->City->find('first', array('conditions' => $conditions));
				$this->set('CityName', $CityName);
				
			}
	       
		   	$conditions = "State.isdeleted = '0' AND State.country_id = '254'";
			$ArState = $this->State->find('all', array('conditions' => $conditions));
			$this->set('ArState', $ArState);
	
			$condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
			$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
			$this->set('citylist',$citylist); 
			
			
	        $limit = 4;
	        $conditions_group = "Group.status= '1'  AND Group.is_featured ='1' AND Group.state_id= '".$selected_state_id."'  AND Group.city_id= '".$selected_city_id."'";
			$count_groups=  $this->Group->find('count',array('conditions'=>$conditions_group ));
			
			if($count_groups>0){ 
		  	$this->paginate = array('conditions' =>$conditions_group,'limit' => $limit,'order'=>'Group.group_title ASC');
			$this->Group->bindModel(array('hasMany' => array('GroupUser' => array('foreignKey' => 'group_id'))),false);
			$group_list = $this->paginate('Group');
	
			$this->set('count_groups',$count_groups);
			$this->set('group_list',$group_list);
			}
			else{
				$this->set('count_groups','0');
				$this->set('group_list',''); 
			} 
	        //
		  }


		   function category_list()
    {

  // $this->_checkSessionUser();

      $this->layout = "";
     
        $condition4 = "Category.status= '1'";
        $category_details = $this->Category->find('all',array('conditions'=>$condition4));
            
    	//pr($category_details);exit();
       return $category_details;
      
    }

    function check_password() {

    
    $current_pwd = $_REQUEST['current_pwd'];
    
     $user_id = $this->Session->read('userData.User.id');



    $conditions = "User.id = '" . $user_id . "' AND User.password = '" . md5($current_pwd) . "'";

    $CheckPassword = $this->User->find('first', array('conditions' => $conditions));

    //pr($CheckPassword);

      if ($CheckPassword) {

        echo "1";

        exit();

      } else {

        echo "0";

        exit();

      }

    }

    function check_email() {

    $user_id = $this->Session->read('userData.User.id');

    
    $email = $_REQUEST['email'];
   



    $condition_check_email = "User.id != '" . $user_id . "' AND User.email = '" . $email . "'";

    $CheckEmail = $this->User->find('count', array('conditions' => $condition_check_email));

    //pr($CheckPassword);

      if ($CheckEmail > 0) {

        echo "0";

        exit();

      } else {

        echo "1";

        exit();

      }

    }


    function change_password() 
    {

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $user_id = $this->Session->read('userData.User.id');  
            $sitesettings = $this->getSiteSettings();

            if(!empty($this->params['form']) && isset($this->params['form'])){
              //pr($this->params['form']);exit();
            
            $this->data['User']['txt_password'] = $this->params['form']['con_pwd']; 
            $this->data['User']['password'] = md5($this->params['form']['con_pwd']); 
            $this->data['User']['id'] = $user_id;

            if($this->User->save($this->data['User'])){

             $condition_receiver_detail = " User.id = '".$user_id."' AND User.status = '1' ";
             $receiver_details = $this->User->find('first', array('conditions'=>$condition_receiver_detail));

              $user_name = $receiver_details['User']['fname'].' '.$receiver_details['User']['lname']; 
                $admin_sender_email = $sitesettings['site_email']['value'];
                $site_url = $sitesettings['site_url']['value'];
                $sender_name = $user_name;
                
                $condition = "EmailTemplate.id = '23'";
                $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                
                $to = $receiver_details['User']['email'];
                $password = $this->params['form']['con_pwd'];

                
                $user_subject = $mailDataRS['EmailTemplate']['subject'];
                $user_subject = str_replace('[SITE NAME]', 'Grouper', $user_subject);
                       
               
                $user_body = $mailDataRS['EmailTemplate']['content'];
                $user_body = str_replace('[USERNAME]', $user_name, $user_body);
                $user_body = str_replace('[PASSWORD]', $password, $user_body);
                               
                         
                $user_message = stripslashes($user_body);
                
             
                 $string = '';
                 $filepath = '';
                 $filename = '';
                 $sendCopyTo = '';
             
             
                $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                 
                     // exit;
               if($sendmail)
                {
                $this->Session->setFlash("Your password is updated. ");    
                $_SESSION['meesage_type'] = '1';
                   $this->redirect(array('controller'=>'category','action'=>'category_list'));

                
                }
                else
                {
                $this->Session->setFlash("Failed to send email.");    
                $_SESSION['meesage_type'] = '1';
                  $this->redirect(array('controller'=>'category','action'=>'category_list'));
                }
              
              
            }
          }
          else{
         $this->Session->setFlash(__d("statictext", "No message sent", true));
         $_SESSION['meesage_type'] = '0';
         $this->redirect(array('controller'=>'category','action'=>'category_list'));
          } 
  

      
    }

    function join_request()
  {
	
	 $this->layout = "";
	  $user_id = $this->Session->read('userData.User.id');
	  $this->set('pageTitle', 'Group Type');
   
		$condition = "Group.id = '".$this->params['form']['id_of_group']."'";
		$group_detail = $this->Group->find('first',array('conditions'=>$condition));  

		
		$condition_check_group_member_owner = "GroupUser.group_id = '".$this->params['form']['id_of_group']."' AND GroupUser.user_id = '".$user_id."' AND (GroupUser.user_type = 'O' OR GroupUser.user_type = 'M')"; 	//Check whether the user is Group Owner/ Member
		
		$is_owner_or_member = $this->GroupUser->find('count',array('conditions'=>$condition_check_group_member_owner));  

		if($is_owner_or_member > 0)
		{

			 $this->Session->setFlash(__d("statictext", "You are already a member of this group", true));
			 $_SESSION['meesage_type'] = '0';
			 $this->redirect("/home/featured_group");
		}
		else
		{
		 
			$cur_date_time= date('Y-m-d H:i:s');
			
			$this->data['GroupUser']['group_id'] = $group_detail['Group']['id']; 
			$this->data['GroupUser']['user_type'] =  'M';
			$this->data['GroupUser']['user_id'] =  $user_id;
			$this->data['GroupUser']['member_mode'] =  $this->params['form']['group_type'];
			  
			$this->GroupUser->create();
			  
		   //////////////////////////   Insert to Groups field in User table starts      //////////////////////   
		   if($this->GroupUser->save($this->data))
		   { 
			  
					$cond_user = "User.id = '".$user_id."'";
					$user_details = $this->User->find('first',array('conditions'=>$cond_user)); 
	
					if($user_details['User']['groups']!=''){
	
						$this->data['User']['id'] = $user_details['User']['id'];
						$this->data['User']['groups'] = $user_details['User']['groups'].",".$group_detail['Group']['id'];
						$this->User->save($this->data['User']);
						
						$this->Session->setFlash(__d("statictext", "You have successfully joined to Business group - ".$group_detail['Group']['group_title'], true));
						$_SESSION['meesage_type'] = '1';
						$this->redirect("/home/featured_group");  
					}
					else
					{
						$this->data['User']['id'] = $user_details['User']['id'];
						$this->data['User']['groups'] = $group_detail['Group']['id'];
						$this->User->save($this->data['User']);
						
						$this->Session->setFlash(__d("statictext", "You have successfully joined to Business group - ".$group_detail['Group']['group_title'], true));
						$_SESSION['meesage_type'] = '1';
						$this->redirect("/home/featured_group");  
					}
				}
				
			//////////////////////////   Insert to Groups field in User table ends      //////////////////////   
			
		}
		
  }



	}?>