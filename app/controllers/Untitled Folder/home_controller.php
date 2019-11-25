<?php
class HomeController extends AppController {

    var $name = 'Home';
    var $uses = array('User','SiteSetting','EmailTemplate','State','City','GroupUser','Group');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
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
      

    }

    function show_city(){
    $stateid = $_REQUEST['state_id'];
    $condition = "City.isdeleted = '0' AND City.state_id='".$stateid."'";
    $citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
    $this->set('citylist',$citylist);
  }

    function signup() { 
            $this->layout = "";
            $this->set('pagetitle', 'Sign Up');
            $sitesettings = $this->getSiteSettings();
      
            if (isset($this->params['form']) && !empty($this->params['form'])) {
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

                  
            if ($countUser > 0) {
                $this->Session->setFlash(__d("statictext", "Email-id already exists.", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'home','action'=>'index'));
            }
            else if($countUserName > 0 ) 
            {
               $this->Session->setFlash(__d("statictext", "UserName already exists.", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'home','action'=>'index'));
            }
            else {
                
                    
                    $this->data['User']['fname'] = $this->params['form']['first_name'];
                    $this->data['User']['lname'] = $this->params['form']['last_name'];  
                    $this->data['User']['image'] = $upload_image;
                    $this->data['User']['username'] = $username;
                    $this->data['User']['email'] = $email; 
                    $this->data['User']['password'] = $password;  
                    $this->data['User']['txt_password'] = $txt_password; 
                    $this->data['User']['status'] = '0'; 
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
                
                $password = md5($this->params['form']['password']);
                $txt_password = $this->params['form']['password'];
                $username = $this->params['form']['username'];

                if (!empty($this->params['form'])) {

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
      //  print_r($uid);
  //  print_r($UserDetails);
  //  exit;
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
    
    function all_members()
    {
		
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


    function my_group() {

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
            
            //pr($all_free_groups);exit;
        $group = array(); 
        foreach($all_groups as $grp)
                {
                    
                    
                    array_push($group,$grp['GroupUser']['group_id']);
                }
        $allgroups = implode(",",$group);
             
        
        $condition7 = "Group.status= '1'  AND Group.id IN (".$allgroups.")";

        //$all_owner_groups = $this->Group->find('all',array('conditions'=>$condition7,'order' => 'Group.group_title ASC'));
        //pr($all_owner_groups);

        $limit = 4;
        $this->paginate = array('conditions' =>$condition7,'limit' => $limit,'order'=>'Group.group_title ASC');
        $all_owner_groups = $this->paginate('Group');
        //pr($all_owner_groups);exit;
        $this->set('all_owner_groups',$all_owner_groups);   
         //owner grp end//

        //member grp start//
        /*$condition6 = "GroupUser.status= '1' AND  GroupUser.user_type = 'M' AND  GroupUser.user_id = '".$user_id."' ";
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
		
		$limit = 4;
        $this->paginate = array('conditions' =>$condition8,'limit' => $limit,'order'=>'Group.group_title ASC');
        $all_member_groups = $this->paginate('Group');*/
        
        
       
        
        
        
        //pr($all_member_groups);exit;
        //exit;
        $this->set('all_member_groups',$all_member_groups); 

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



    }
    
   
    
}?>
