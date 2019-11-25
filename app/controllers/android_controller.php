<?php 
class AndroidController  extends AppController 
{
    var $name = 'Android';
    var $helpers = array('Html', 'Form','Javascript','Js','Session');
    var $components = array('RequestHandler', 'Session','Cookie','Email');  
    var $uses = array("Chat","Admin","User","Category","Group","Event","GroupImage","City","State","GroupUser","SiteSetting","EmailTemplate","GroupDoc","Video","Notification","Friendlist","Testimonial","Track","GroupMessage","GroupMessageReply");
    
    function registration(){
         //Configure::write('debug',3);
        $this->layout = ''; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $response = array();
        $response['is_error'] = 0;
        $sitesettings = $this->getSiteSettings();
        $json = file_get_contents('php://input');
        //$json ='{"password":"12345678","device_token":"","last_name":"ghosh","username":"ashit204","email":"kk@gmail.com","device_type":"iphone","first_name":"ashit"}';
      
        
        $fname = $_POST['first_name']; 
        $lname = $_POST['last_name'];  
        $username = $_POST['username'];
        $email = $_POST['email'];  
        $password = $_POST['password'];
        $device_token = $_POST['device_token']; 
        $device_type = $_POST['device_type'];
        $state_id = $_POST['state_id']; 
        $city_id = $_POST['city_id'];
        $last_login = date('Y-m-d H:i:s');
            
     
        /*$fname = 'Ashit';   
        $lname = 'ghosh';    
        $username = 'ashit206';
        $email = 'xyx@gmail.com';
        $password= '12345678';
        
        $device_type = 'iphone';
        $device_token = '';
        $state_id = '456'; 
        $city_id = '545';*/

        
        $upload_image = '';
        
        if(isset($_FILES["upload_image"]) && $_FILES["upload_image"]['name']!= ''){
            $image_name = $_FILES["upload_image"]['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'user_images/'.$upload_image;
                        
            $imagelist = getimagesize($_FILES["upload_image"]['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2)
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
                        
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Image upload failed';
                
                    }
                
                }
                else
                {        
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'Please upload a 200x100 or bigger image only';
                }
                
             }
             else
             {
                $response['is_error'] = 1;
                $response['err_msg'] = 'Please upload jpg,jpeg and gif image only';
             }
        
        }
                
        if($response['is_error'] == 0){

            $is_exist = $this->User->find('count', array('conditions'=>array('email'=>$email,'status'=>'1')));
            $is_username_exist = $this->User->find('count', array('conditions'=>array('username'=>$username)));
            
            //pr($is_exist);
            if($is_exist > 0)
            {

                 $email_is_exist = $this->User->find('first', array('conditions'=>array('email'=>$email,'status'=>'1','is_invite' => '1')));
                
                    if(!empty($email_is_exist))
                    {
                        $condition_user_detail = " User.id = '".$email_is_exist['User']['id']."' AND User.status = '1' ";
                        $email_details = $this->User->find('first', array('conditions'=>$condition_user_detail));
                        
                        
                        
                        $this->data['User']['fname'] = stripslashes($fname);
                        $this->data['User']['lname'] = stripslashes($lname);
                        $this->data['User']['password'] = md5($password);
                        $this->data['User']['txt_password'] = $password;
                        $this->data['User']['username'] = stripslashes($username);
                        $this->data['User']['image'] = $upload_image;
                        $this->data['User']['device_type'] = $device_type;
                        $this->data['User']['device_token'] = $device_token;
                        $this->data['User']['city_id'] = $city_id;
                        $this->data['User']['state_id'] = $state_id;
                        $this->data['User']['groups'] = '';
                        $this->data['User']['is_invite'] = '0';
                        $this->data['User']['last_login'] = $last_login;
           
                        $this->User->id = $email_details['User']['id'];
                           
                        if($this->User->save($this->data['User']))
                        {
                    $insert_id = $this->User->getLastInsertID(); exit;
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
                        
                    $response['User']['id'] = $last_insert_id;
                    $response['User']['first_name'] = is_null($fname) ? '' : ucfirst($fname) ;
                    $response['User']['last_name'] = is_null($lname) ? '' : ucfirst($lname) ;
                    $response['User']['email'] = is_null($email_details['User']['email']) ? '' : $email_details['User']['email'];
                    $response['User']['username'] = is_null($username) ? '' : $username;
                    
                    if($upload_image!="")
                    {
                    
                    $response['User']['image'] = $base_url.'user_images/thumb/'.$upload_image;

                    }
                    else
                    {
                    $response['User']['image'] = $base_url.'images/no_profile_img.jpg';
                    }
                    
                    $response['success_msg'] = 'Thank you for registering with Grouper';
                    
                    
                                
                }
                        
                    }
                    else
                    {
                         $response['is_error'] = 1;
                         $response['err_msg'] = 'You are already registered in this email';   

                    }
            }
            else if($is_username_exist > 0)
            {
                $response['is_error'] = 1;
                $response['err_msg'] = 'You are already registered in this username';    
            }
            else
            {
                $this->data['User']['fname'] = stripcslashes($fname);
                $this->data['User']['lname'] = stripcslashes($lname);
                $this->data['User']['email'] = $email;
                $this->data['User']['password'] = md5($password);
                $this->data['User']['txt_password'] = $password;
                $this->data['User']['username'] = stripslashes($username);
                $this->data['User']['image'] = $upload_image;
                $this->data['User']['device_type'] = $device_type;
                $this->data['User']['device_token'] = $device_token;
                $this->data['User']['city_id'] = $city_id;
                $this->data['User']['groups'] = '';
                $this->data['User']['state_id'] = $state_id;
                $this->data['User']['last_login'] = $last_login;
                                
                $this->User->create();
                if($this->User->save($this->data['User']))
                {
                   // $insert_id = $this->User->getLastInsertID(); 
                    $last_insert_id = $this->User->getLastInsertId();
                                           

                    $user_name = $username; 
                    $admin_sender_email = $sitesettings['site_email']['value'];
                    $site_url = $sitesettings['site_url']['value'];
                    $sender_name = $sitesettings['email_sender_name']['value'];

                    $condition = "EmailTemplate.id = '1'";
                    $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                    $link = '<a href="' . $site_url . '/home/activation/' . base64_encode($last_insert_id) . '"> ' . $site_url . '/home/activation/'. base64_encode($last_insert_id) . '</a>';
                   
                    
                    $to = $email;

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
            /* if($sendmail)
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
              }  */                 
                    $response['User']['id'] = $last_insert_id;
                    $response['User']['first_name'] = is_null($fname) ? '' : ucfirst($fname) ;
                    $response['User']['last_name'] = is_null($lname) ? '' : ucfirst($lname) ;
                    $response['User']['email'] = is_null($email) ? '' : $email;
                    $response['User']['username'] = is_null($username) ? '' : $username;
                    
                    if($upload_image!="")
                    {
                    
                    $response['User']['image'] = $base_url.'user_images/thumb/'.$upload_image;

                    }
                    else
                    {
                    $response['User']['image'] = $base_url.'images/no_profile_img.jpg';
                    }
                    
                    $response['success_msg'] = 'Thank you for registering with Grouper';
                    
                    
                                
                }
                
            }
        }
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    function track($json,$action)
    {
         $this->data['Track']['json'] = $json;
                $this->data['Track']['action'] = $action;
                $this->data['Track']['created'] = date("Y-m-d H:i:s");
                
                $this->Track->create();
                $this->Track->save($this->data['Track']);
    }
    
    function login(){

        $this->layout = '';
        $response = array();
        $response['is_error'] = 0;
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        
        $json = file_get_contents('php://input');   
        $obj = json_decode($json);
                 
        
        $email    = $obj->{'email'};
        $password  = $obj->{'password'};
        $device_token  = $obj->{'device_token'};
        $device_type  = $obj->{'device_type'};
        

        /*$email    = 'jishnu.php@gmail.com';
        $password  = '123456';
        $device_token  = '';
        $device_type  = 'android';*/

              
        
        $condition = "User.email = '".$email."' AND User.txt_password = '".$password."' AND User.password = '".md5($password)."'";
        $detail = $this->User->find('first',array('conditions'=>$condition));
                
        //pr($detail_count);exit();
        if(!empty($detail))
        {

            if($detail['User']['status'] == '1')
            {

                $last_login = date('Y-m-d H:i:s');

                $this->User->query("UPDATE `users` SET `device_token` ='".$device_token."',`device_type` ='".$device_type."',`last_login` ='".$last_login."'  WHERE `id` = '".$detail['User']['id']."'");
            
            $response['is_error'] = 0;
            $response['User']['id'] = $detail['User']['id'];
            $response['User']['first_name'] = ucfirst($detail['User']['fname']);
            $response['User']['last_name'] = ucfirst($detail['User']['lname']);
            $response['User']['state_id'] = $detail['User']['state_id'];
            
            $condition = "State.id = '".$detail['User']['state_id']."'";
            $state_detail = $this->State->find('first',array('conditions'=>$condition));
            $response['User']['state_name'] = ucfirst($state_detail['State']['name']);
    
            $response['User']['city_id'] = $detail['User']['city_id'];
            $condition = "City.id = '".$detail['User']['city_id']."'";
            $city_detail = $this->City->find('first',array('conditions'=>$condition));
            $response['User']['city_name'] = ucfirst($city_detail['City']['name']);
    
            $response['User']['email'] = $detail['User']['email'];
            $response['User']['username'] = ucfirst($detail['User']['username']);
            if($detail['User']['image']!=""){
            
                $response['User']['image'] = $base_url.'user_images/thumb/'.$detail['User']['image'];
    
            }
            else
            {
                $response['User']['image'] = $base_url.'images/no_profile_img.jpg';
            }
            
            $response['success_msg'] = "Logged in successfully";
                  
            }
            else
            {
                $response['is_error'] = 1;
                $response['err_msg'] = 'Please activate your account from the activation link provided in your mail';
            }
        
            
        }
        else
        {
            
            $response['is_error'] = 1;
            $response['err_msg'] = 'Invalid login information';
        }
        
        
    
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function update_device_token(){
        
        $this->layout = '';
        $response = array();
        
        $json = file_get_contents('php://input');   
        $obj = json_decode($json);
        
        $user_id  = $obj->{'user_id'};
        $device_token  = $obj->{'device_token'};
        $device_type  = $obj->{'device_type'};
        
        $this->User->query("UPDATE `users` SET `device_token` ='".$device_token."', `device_type` ='".$device_type."'  WHERE `id` = '".$user_id."'");
        
        $response['is_error'] = 0;
        $response['success_msg'] = "Device Token updated successfully";
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
       
    function forgot_password(){
    
        $this->layout = ""; 
        $response = array();

        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $email    = $obj->{'email'};


        if($email!=''){
       // pr($email);
        $ArUserDetails = $this->User->find('first', array('conditions' => array('User.email' => $email, 'User.status' => '1'), 'fields' => array('User.id,User.fname,User.lname,User.txt_password,User.email') ));
        if (!empty($ArUserDetails)){
            //pr($ArUserDetails);
            $sitesettings = $this->SiteSetting->getSettings();
            $user_password =  $ArUserDetails['User']['txt_password'];
            
            $user_name = ucfirst($ArUserDetails['User']['fname']).' '.ucfirst($ArUserDetails['User']['lname']); 
            $admin_sender_email = $sitesettings['site_email']['value'];
            $site_url = $sitesettings['site_url']['value'];
            $sender_name = $sitesettings['email_sender_name']['value'];

            $condition = "EmailTemplate.id = '21'";
            $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
               
                
            $to = $email;

            $user_name = $user_name;
            $user_subject = $mailDataRS['EmailTemplate']['subject'];
            $user_subject = str_replace('[SITE NAME]', 'Grouper', $user_subject);
                       
         
            $user_body = $mailDataRS['EmailTemplate']['content'];
            $user_body = str_replace('[NAME]', $user_name, $user_body);
            $user_body = str_replace('[PASSWORD]', $user_password, $user_body);
                             
            $user_message = stripslashes($user_body);
            
   
           $string = '';
           $filepath = '';
           $filename = '';
           $sendCopyTo = '';
   
           // pr($mailDataRS);exit;
            $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                 
            if($sendmail)
              {
                $response['is_error'] = 0;
                $response['err_msg'] = "Please check your email to get your password. ";
                  
              }
              else
              {
                 $response['is_error'] = 1;
                $response['err_msg'] = "Failed to send email.";
                 
              }
                        
        } else{
                    $response['is_error'] = 1;
                    $response['err_msg'] = "This email doesn't exist in our database.";

        }
      }

       header('Content-type: application/json');
       echo json_encode($response);
       exit;
    }
    
    
    function change_password(){

        $this->layout = ""; 
        $response = array();
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $current_pwd    = $obj->{'current_pwd'};
        $new_pwd    = $obj->{'new_pwd'};
        $user_id    = $obj->{'user_id'};

        /*$current_pwd    = '123456';
        $new_pwd    = '321321';
        $user_id    = '2';*/

        if(isset($current_pwd) && $current_pwd!='' && isset($new_pwd) && $new_pwd!=''){
        
            $con = "User.id ='".$user_id."' AND User.txt_password = '".$current_pwd."' ";
            $arr_user = $this->User->find('first',array('conditions'=>$con));   
            
            if(!empty($arr_user)){
                
                $this->data['User']['password'] = md5($new_pwd);    
                $this->data['User']['txt_password'] = $new_pwd;
                $this->User->id = $user_id;
                
                if($this->User->save($this->data)){
                    $response['is_error'] = 0;
                    $response['success_msg'] = "Password changed successfully !!!";
                }
            }
            else{
     
                $response['is_error'] = 1;
                $response['err_msg'] = "Current password does not match";
    
            } 
        }
        else{
     
            $response['is_error'] = 1;
            $response['err_msg'] = "Please put the new password";

        } 

     
         
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
      
    }
    
    
    function state_list(){
    
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
                 
    
        $condition2 = "State.isdeleted ='0' AND State.country_id = '254' ";
        $all_state = $this->State->find('all',array('conditions'=>$condition2));

        $state = array(); 
        
        if(count($all_state) > 0)
        { 
            foreach($all_state as $st)
            {
                $list['id'] = $st['State']['id'];
                $list['name'] =  ucfirst($st['State']['name']);
                
                array_push($state,$list);
            }
            $response['state']= $state; 
            $response['is_error'] = 0;
        }
        
        else {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No States found';
             }
         header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
   

    function city_list(){
        $this->layout = ""; 
        $response = array();
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $state_id    = $obj->{'state_id'};
                 
    
        $condition2 = "City.isdeleted ='0' AND City.state_id = '".$state_id."'";
        $all_city = $this->City->find('all',array('conditions'=>$condition2));

          $city = array(); 
        
        if(count($all_city) > 0)
        { 
            foreach($all_city as $ct)
            {
                $list['id'] = $ct['City']['id'];
                $list['name'] =  ucfirst($ct['City']['name']);
                $list['state_id'] = $ct['City']['state_id'];
                
                
                array_push($city,$list);
            }
            $response['city']= $city; 
            $response['is_error'] = 0;
        }
        
        else {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Cities found';
             }
         header('Content-type: application/json');
        echo json_encode($response);
        exit;

    }
    
    
    function category_list(){
    
        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $user_id    = $obj->{'user_id'};
        //$user_id = 2;
        
    
        $condition2 = "Category.status ='1'";
        $all_category = $this->Category->find('all',array('conditions'=>$condition2,'order' => 'Category.title ASC'));
        
        //pr($all_category);exit;
        $category = array(); 
        
        if(count($all_category) > 0)
        { 
            $i=0;
            foreach($all_category as $cat)
            {
                $category[$i]['id'] = $cat['Category']['id'];
                $category[$i]['title'] =  ucfirst(stripslashes($cat['Category']['title']));
                $category[$i]['desc'] =  ucfirst(stripslashes($cat['Category']['category_desc']));
				$category[$i]['POG_status'] =  ucfirst(stripslashes($cat['Category']['POG_status']));
                if(!empty($cat['Category']['image']))
                   {
                   $category[$i]['image_url']['thumb'] = $base_url.'category_photos/thumb/'.$cat['Category']['image'];
                   $category[$i]['image_url']['medium'] = $base_url.'category_photos/android/medium/'.$cat['Category']['image'];
                   $category[$i]['image_url']['original'] = $base_url.'category_photos/'.$cat['Category']['image'];
                   }
                else
                   {
                   $category[$i]['image_url']['thumb'] = $base_url.'images/no_profile_img.jpg';
                   $category[$i]['image_url']['medium'] = $base_url.'images/no_profile_img.jpg';
                   $category[$i]['image_url']['original'] = $base_url.'images/no_profile_img.jpg';
                   }
                $i=$i+1;
            }
            $response['category']= $category; 
            $response['is_error'] = 0;
        }
        
        else {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Categories found';
        }
        //pr($response );exit;
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
     
     }
     
    
    function category_details(){
    
        $this->layout = '';
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
       
        $category_id = $obj->{'category_id'};
        $user_id = $obj->{'user_id'};
        $state_id = $obj->{'state_id'};
        $city_id = $obj->{'city_id'};
        
                
        /*$category_id = '25';
        $user_id = '26' ;
        $state_id = '126';
        $city_id = '9531';*/


        
        
         $condition = "Category.id = '".$category_id."' AND Category.status = '1'";
         $category_detail = $this->Category->find('first',array('conditions'=>$condition)); 
         
         if(!empty($category_detail)){

         $response['is_error'] = 0;
         $response['id'] = $category_detail['Category']['id'];
         $response['title'] = ucfirst(stripslashes($category_detail['Category']['title']));
         $response['description'] = ucfirst(stripslashes($category_detail['Category']['category_desc']));
         if($category_detail['Category']['image']!=''){
             $response['image_url']['thumb'] = $base_url.'category_photos/thumb/'.$category_detail['Category']['image'];
             $response['image_url']['medium'] = $base_url.'category_photos/android/medium/'.$category_detail['Category']['image'];
             $response['image_url']['original'] = $base_url.'category_photos/'.$category_detail['Category']['image'];
         }
         else{
             $response['image_url']['thumb'] = $base_url.'images/no-group-img_1.jpg';
             $response['image_url']['medium'] = $base_url.'images/no-group-img_1.jpg';
             $response['image_url']['original'] = $base_url.'images/no-group-img_1.jpg';
         }

         $this->Group->bindModel(array('hasMany' => array('GroupUser' => array('foreignKey' => 'group_id'))),false);
         $condition = "Group.category_id = '".$category_id."' AND Group.status = '1' AND Group.city_id = '".$city_id."' AND Group.state_id = '".$state_id."' AND Group.status = '1'";
         $group_detail = $this->Group->find('all',array('conditions'=>$condition,'order'=>'Group.group_title ASC')); 
         
        
         $groups = array(); 
            
         if(count($group_detail) > 0){ 
            foreach($group_detail as $group)
            {
                $list['group_id'] = $group['Group']['id'];
                $list['group_title'] = ucfirst(stripslashes($group['Group']['group_title']));
                $list['group_desc'] =  ucfirst(stripslashes($group['Group']['group_desc']));
                $list['group_type'] = $group['Group']['group_type'];
                $list['group_user_count'] =  count($group['GroupUser']);

                $conditions = "GroupUser.group_id =  '".$group['Group']['id']."' AND GroupUser.user_id =  '".$user_id."' ";
                $ArrGroupType = $this->GroupUser->find('first', array('conditions' => $conditions));
                if(!empty($ArrGroupType))
                {
                   $list['group_user_type'] =  $ArrGroupType['GroupUser']['user_type'];
                }
                else
                {
                   $list['group_user_type'] = '';
                      
                }

                if($group['Group']['icon']!=''){
                     $list['image_url']['thumb'] = $base_url.'group_images/thumb/'.$group['Group']['icon'];
                     $list['image_url']['medium'] = $base_url.'group_images/medium/'.$group['Group']['icon'];
                     $list['image_url']['original'] = $base_url.'group_images/'.$group['Group']['icon'];
                }
                else{
                    $list['image_url']['thumb'] = $base_url.'images/no-group-img_1.jpg';
                    $list['image_url']['medium'] = $base_url.'images/no-group-img_1.jpg';
                    $list['image_url']['original'] = $base_url.'images/no-group-img_1.jpg';
                }
    
                array_push($groups,$list);
            }
            
        }
            
        $response['groups']= $groups;       
       }
         else{
           
                $response['is_error'] = 1;
                $response['err_msg'] = 'No Category found';
         } 
            
           
         
          
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function group_member_list(){
    
        //Configure::write('debug',3);
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $group_type    = $obj->{'group_type'};

       /* $group_id    = '2';
        $user_id    = '7';
        $group_type    = 'F';*/

        $con_chk_creator = "Group.created_by = '".$user_id."' AND  Group.id = '".$group_id."'";
        $chk_grp_creator = $this->Group->find('count',array('conditions'=>$con_chk_creator ));
        if($chk_grp_creator>0){
            $is_creator = 1;
        }
        else{
            $is_creator = 0;
        }
		
		$condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";
		$group_details = $this->Group->find('first',array('conditions'=>$condition2));
		
		$arr_group_owners= explode(',', $group_details['Group']['group_owners']);
		if(in_array($user_id, $arr_group_owners)){
			$is_owner=1;
		}
		else{
			$is_owner=0;
		}
   
        if($group_type == 'B'){         //B for Business group

              $group_members = array(); 
    
              $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";        //Condition to check Group Owner
              $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));
    
              if($count_chk_owner > 0){
    
                    $condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."'";    // Fetches the all members (public+private) of the Group
                    $all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));
                    
                    if(!empty($all_group_members)){
                        foreach($all_group_members as $groups){
                        
                                $list['id'] = $groups['GroupUser']['id'];
                                $list['user_id'] = $groups['GroupUser']['user_id'];
                                $list['user_type'] = $groups['GroupUser']['user_type'];
                                $list['member_mode'] = $groups['GroupUser']['member_mode'];
                                $list['group_id'] = $groups['GroupUser']['group_id'];
								$list['can_post_topic'] = $groups['GroupUser']['can_post_topic'];
        
                                $condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
                                $check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
                       
                                $is_friend = '0';
                
                                if($check_friend > 0 )
                                {
                                  $is_friend = '1';     // The Group member is my friend.
                                }                   
                                else {          //If Group Member is not my friend
                                
                                        $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";     //Check whether logged in user(owner) has sent the request already or not
                                        
                                        $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));
                
                                        if(($count_request_sent > 0)){
                                            $is_friend = '2'; //Request sent by logged in user(owner) ,not accepted or rejected by other user
                                        }
                                        else{
                
                                            $condition9 = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.receiver_id = '".$user_id."' AND Notification.sender_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";       //Check whether logged in user(owner) has received the request already or not
                                            
                                            $count_request_received = $this->Notification->find('count',array('conditions'=>$condition9));
                                            $notification_detail = $this->Notification->find('first',array('conditions'=>$condition9));
                                            
                                            if($count_request_received > 0){
                                            
                                                $is_friend = '3';  
                                                $notification_id =  $notification_detail['Notification']['id'];
                                                //Whether the logged in user have got the Notification with accept or reject button
                                            }
                                        }
                                 }
                        
                                $list['is_friend'] = $is_friend;   
                                $list['notification_id'] = $notification_id; 
                        
        
                                $con_user = "User.status= '1' AND  User.id = '".$groups['GroupUser']['user_id']."'";
                                $user_detail = $this->User->find('first',array('conditions'=>$con_user));
                                $list['user_name'] = ucfirst($user_detail['User']['fname'].' '.$user_detail['User']['lname']) ;
                                if(!empty($user_detail['User']['image']))
                                {
                
                                    $list['image_url'] = $base_url.'user_images/thumb/'.$user_detail['User']['image'];
                                   
                                }
                                else
                                {
                                    $list['image_url'] = $base_url.'images/no_profile_img.jpg';
                                }
                        
                                array_push($group_members,$list);
    
                    }
                    
                        $response['group_members']= $group_members; 
                        $response['is_auth']= 1; 
                        $response['is_error'] = 0;
                        $response['is_creator'] = $is_creator;
						$response['is_owner'] = $is_owner;
                    }
                    else{
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'No users found within the Group';
                        $response['is_auth']= 1; 
                        $response['is_creator'] = $is_creator;
						$response['is_owner'] = $is_owner;
                    }
                    
              }
              else{
              
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'You are not authorized';
                    $response['is_auth']= 0; 
                    $response['is_creator'] = $is_creator;
					$response['is_owner'] = $is_owner;
               }
        }
        else if($group_type == 'F'){
        
                $group_members = array(); 

                $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";      //Condition to check Group Owner
                $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));

                if($count_chk_owner > 0){       //if Logged in user is the Group owner of the selected Free group
        
                
                        $condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."'";    // Fetches the all members (public+private) of the Group
                        $all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));
        
                        if(!empty($all_group_members)){
                            foreach($all_group_members as $groups){
                            
                                    $list['id'] = $groups['GroupUser']['id'];
                                    $list['user_id'] = $groups['GroupUser']['user_id'];
                                    $list['user_type'] = $groups['GroupUser']['user_type'];
                                    $list['member_mode'] = $groups['GroupUser']['member_mode'];
                                    $list['group_id'] = $groups['GroupUser']['group_id'];
									$list['can_post_topic'] = $groups['GroupUser']['can_post_topic'];
            
                                    $condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
                                    $check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
                           
                                    $is_friend = '0';
            
                                    if($check_friend > 0 ){
                                        $is_friend = '1';   // The Group member is my friend.
                                    }                   
                                    else {          //If Group Member is not my friend
                            
                                        $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";     //Check whether logged in user has sent the request already or not
                                        $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));
            
                                        if(($count_request_sent > 0)){
                                            $is_friend = '2'; //Request sent by logged in user (owner) ,not accepted or rejected by other user
                                        }
                                        else{
            
                                            $condition9 = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.receiver_id = '".$user_id."' AND Notification.sender_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";
                                            $count_request_received = $this->Notification->find('count',array('conditions'=>$condition9));
                                            $notification_detail = $this->Notification->find('first',array('conditions'=>$condition9));
            
                                            if(($count_request_received > 0)){
                                                $is_friend = '3';  
                                                $notification_id =  $notification_detail['Notification']['id'];    //Whether the logged in user have got the Notification with accept or reject button
                                            }
                                        }
                                 }
                            
                                $list['is_friend'] = $is_friend;  
                                $list['notification_id'] = $notification_id;  
                            
            
                                $condition3 = "User.status= '1' AND  User.id = '".$groups['GroupUser']['user_id']."'";
                                $user_detail = $this->User->find('first',array('conditions'=>$condition3));
                                $list['user_name'] = ucfirst($user_detail['User']['fname'].' '.$user_detail['User']['lname']) ;
                                if(!empty($user_detail['User']['image'])){
            
                                    $list['image_url'] = $base_url.'user_images/thumb/'.$user_detail['User']['image'];
                               
                                }
                                else{
            
                                    $list['image_url'] = $base_url.'images/no_profile_img.jpg';
            
                                }
                            
                            
                                array_push($group_members,$list);
                        }
                        
                            $response['group_members']= $group_members; 
                            $response['is_auth']= 1; 
                            $response['is_error'] = 0;
                            $response['is_creator'] =$is_creator;
							$response['is_owner'] =$is_owner;
                        }
                        else{
                            $response['is_error'] = 1;
                            $response['err_msg'] = 'No users found within the Group';
                            $response['is_auth']= 1; 
                            $response['is_creator'] = $is_creator;
							$response['is_owner'] = $is_owner;
                        }
        
        
                }else{          // If the logged in user is the group member of Free Group 
                  
                        $condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."' AND GroupUser.member_mode = 'public'";
        
                        $all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));
            
                        if(!empty($all_group_members)){
                            foreach($all_group_members as $groups){
                            
                                    $list['id'] = $groups['GroupUser']['id'];
                                    $list['user_id'] = $groups['GroupUser']['user_id'];
                                    $list['user_type'] = $groups['GroupUser']['user_type'];
                                    $list['member_mode'] = $groups['GroupUser']['member_mode'];
                                    $list['group_id'] = $groups['GroupUser']['group_id'];
                                
            
                                    $condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
                                    $check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
                           
                                    $is_friend = '0';
                
                                    if($check_friend > 0 ){
                                        $is_friend = '1';   // The Group member is my friend.
                                    }                   
                                    else{           //If Group Member is not my friend
                            
                                        $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";     //Check whether logged in user has sent the request already or not
                                        $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));
            
                                        if(($count_request_sent > 0)){
                                            $is_friend = '2'; //Request sent by logged in user ,not accepted or rejected by other user
                                        }
                                        else{
            
                                            $condition9 = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.receiver_id = '".$user_id."' AND Notification.sender_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";
                                            $count_request_received = $this->Notification->find('count',array('conditions'=>$condition9));
                                            $notification_detail = $this->Notification->find('first',array('conditions'=>$condition9));
                                            if(($count_request_received > 0)){
                                                    $is_friend = '3'; 
                                                    $notification_id =  $notification_detail['Notification']['id'];     //Whether the logged in user have got the Notification with accept or reject button
                                            }
                                        }
                                    }
                            
                                    $list['is_friend'] = $is_friend; 
                                    $list['notification_id'] = $notification_id; 
             
            
                                    $condition3 = "User.status= '1' AND  User.id = '".$groups['GroupUser']['user_id']."'";
                                    $user_detail = $this->User->find('first',array('conditions'=>$condition3));
                                    $list['user_name'] = ucfirst($user_detail['User']['fname'].' '.$user_detail['User']['lname']) ;
                                    if(!empty($user_detail['User']['image'])){
                
                                        $list['image_url'] = $base_url.'user_images/thumb/'.$user_detail['User']['image'];
                                   
                                    }
                                    else{
                                        $list['image_url'] = $base_url.'images/no_profile_img.jpg';
                                    }
                            
                            
                                    array_push($group_members,$list);
                            }
                            
                            $response['group_members']= $group_members; 
                            $response['is_auth']= 1; 
                            $response['is_error'] = 0;
                            $response['is_creator'] =$is_creator;
							$response['is_owner'] =$is_owner;
                        }
                        else{
                            $response['is_error'] = 1;
                            $response['err_msg'] = 'No users found within the Group';
                            $response['is_auth']= 1; 
                            $response['is_creator'] = $is_creator;
							$response['is_owner'] = $is_owner;
                        }
                
                }       
        }
		else if($group_type == 'PO'){         //PO for Public Organization group

              $group_members = array(); 
    
              $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";        //Condition to check Group Owner
              $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));
    
              if($count_chk_owner > 0){
    
                    $condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."'";    // Fetches the all members (public+private) of the Group
                    $all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));
                    
                    if(!empty($all_group_members)){
                        foreach($all_group_members as $groups){
                        
                                $list['id'] = $groups['GroupUser']['id'];
                                $list['user_id'] = $groups['GroupUser']['user_id'];
                                $list['user_type'] = $groups['GroupUser']['user_type'];
                                $list['member_mode'] = $groups['GroupUser']['member_mode'];
                                $list['group_id'] = $groups['GroupUser']['group_id'];
								$list['can_post_topic'] = $groups['GroupUser']['can_post_topic'];
        
                                $condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
                                $check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
                       
                                $is_friend = '0';
                
                                if($check_friend > 0 )
                                {
                                  $is_friend = '1';     // The Group member is my friend.
                                }                   
                                else {          //If Group Member is not my friend
                                
                                        $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";     //Check whether logged in user(owner) has sent the request already or not
                                        
                                        $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));
                
                                        if(($count_request_sent > 0)){
                                            $is_friend = '2'; //Request sent by logged in user(owner) ,not accepted or rejected by other user
                                        }
                                        else{
                
                                            $condition9 = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.receiver_id = '".$user_id."' AND Notification.sender_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";       //Check whether logged in user(owner) has received the request already or not
                                            
                                            $count_request_received = $this->Notification->find('count',array('conditions'=>$condition9));
                                            $notification_detail = $this->Notification->find('first',array('conditions'=>$condition9));
                                            
                                            if($count_request_received > 0){
                                            
                                                $is_friend = '3';  
                                                $notification_id =  $notification_detail['Notification']['id'];
                                                //Whether the logged in user have got the Notification with accept or reject button
                                            }
                                        }
                                 }
                        
                                $list['is_friend'] = $is_friend;   
                                $list['notification_id'] = $notification_id; 
                        
        
                                $con_user = "User.status= '1' AND  User.id = '".$groups['GroupUser']['user_id']."'";
                                $user_detail = $this->User->find('first',array('conditions'=>$con_user));
                                $list['user_name'] = ucfirst($user_detail['User']['fname'].' '.$user_detail['User']['lname']) ;
                                if(!empty($user_detail['User']['image']))
                                {
                
                                    $list['image_url'] = $base_url.'user_images/thumb/'.$user_detail['User']['image'];
                                   
                                }
                                else
                                {
                                    $list['image_url'] = $base_url.'images/no_profile_img.jpg';
                                }
                        
                                array_push($group_members,$list);
    
                    }
                    
                        $response['group_members']= $group_members; 
                        $response['is_auth']= 1; 
                        $response['is_error'] = 0;
                        $response['is_creator'] = $is_creator;
						$response['is_owner'] = $is_owner;
                    }
                    else{
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'No users found within the Group';
                        $response['is_auth']= 1; 
                        $response['is_creator'] = $is_creator;
						$response['is_owner'] = $is_owner;
                    }
                    
              }
              else{
              
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'You are not authorized';
                    $response['is_auth']= 0; 
                    $response['is_creator'] = $is_creator;
					$response['is_owner'] = $is_owner;
               }
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
         
      }
      
    
    function join_now(){
       
        $this->layout = ""; 
        $response = array();
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);


        
        $user_id    = $obj->{'user_id'};
        $group_id    = $obj->{'group_id'};
        $request_mode   = $obj->{'request_mode'};


        /*$user_id    = 6;
        $group_id    = 16;
        $request_mode    = 'public';*/
            

        $condition = "Group.id = '".$group_id."'";
        $group_detail = $this->Group->find('first',array('conditions'=>$condition));  

        
        $condition_check_group_member_owner = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."' AND (GroupUser.user_type = 'O' OR GroupUser.user_type = 'M')";   //Check whether the user is Group Owner/ Member
        
        $is_owner_or_member = $this->GroupUser->find('count',array('conditions'=>$condition_check_group_member_owner));  
        
        if($is_owner_or_member > 0)
        {

             $response['is_error'] = 1;
             $response['err_msg'] = 'You are already a member of this group';
        }
        else{
        
            $cur_date_time= date('Y-m-d H:i:s');
            
            $this->data['GroupUser']['group_id'] = $group_id; 
            $this->data['GroupUser']['user_type'] =  'M';
            $this->data['GroupUser']['user_id'] =  $user_id;
            $this->data['GroupUser']['member_mode'] =  $request_mode;
              
            $this->GroupUser->create();
            
            //////////////////////////   Insert to Groups field in User table starts      //////////////////////   
           if($this->GroupUser->save($this->data))
           { 
              
                    $cond_user = "User.id = '".$user_id."'";
                    $user_details = $this->User->find('first',array('conditions'=>$cond_user)); 
    
                    if($user_details['User']['groups']!=''){
    
                        $this->data['User']['id'] = $user_details['User']['id'];
                        $this->data['User']['groups'] = $user_details['User']['groups'].",".$group_id;
                        $this->User->save($this->data['User']);
                        
                    }
                    else
                    {
                        $this->data['User']['id'] = $user_details['User']['id'];
                        $this->data['User']['groups'] = $group_detail['Group']['id'];
                        $this->User->save($this->data['User']);
                    }
            }
            
           $response['is_error'] = 0;
           $response['success_msg'] = "You have successfully joined to Business group - ".$group_detail['Group']['group_title'];    
            //////////////////////////   Insert to Groups field in User table ends      //////////////////////
        }

        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function create_group(){
    
        $this->layout = ''; 
        
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        
        $group_title = addslashes($_POST['group_title']); 
        $group_desc = addslashes($_POST['group_desc']);  
        $user_id = $_POST['user_id'];  
        $category_id = $_POST['category_id'];
        $group_type = $_POST['group_type'];
        //$group_purpose = addslashes($_POST['group_purpose']); 
        $state_id = $_POST['state_id'];
        $city_id = $_POST['city_id'];

       
        $condition = "User.status= '1'  AND User.id= '".$user_id."'";
        $user_details = $this->User->find("first",array('conditions'=>$condition));
        
        $upload_image = '';
        
        if(isset($_FILES["upload_image"]) && $_FILES["upload_image"]['name']!= ''){
            
            $image_name = $_FILES["upload_image"]['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'group_images/'.$upload_image;
                        
            $imagelist = getimagesize($_FILES["upload_image"]['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
            if($type == 1 || $type == 2){
            
                if($uploaded_width >=160 && $uploaded_height >= 120){
                
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)){
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'group_images/thumb/'.$upload_image;
                            $upload_target_medium = 'group_images/medium/'.$upload_image;
                            $upload_target_web = 'group_images/web/'.$upload_image;
                            
                            
                            $max_web_width =  262;
                            $max_web_height = 178;
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_web, $max_web_width, $max_web_height, 100, true);
                        
                                                                                    
                    }           
                    else{
                        
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Image upload failed !!!';
                
                    }
                
                }
                else
                {        
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'Please upload a 200x100 or bigger image only';
                }
                
            }
            else{
            
                $response['is_error'] = 1;
                $response['err_msg'] = 'Please upload jpg,jpeg and gif image only';
            }
        }
        else{
        
            $response['is_error'] = 1;
            $response['err_msg'] = 'Please upload Group image !!!';
        }
        /*echo '<pre>';
        print_r($_FILES["upload_image"]);
        exit;*/
        if($response['is_error'] == 0){

            if($group_type == 'F'){
                    
                    $this->data['Group']['plan_id'] = '0';
                    $this->data['Group']['amount'] = '0';
                    $this->data['Group']['group_title'] = $group_title;
                    $this->data['Group']['group_desc'] = $group_desc;
                    //$this->data['Group']['group_purpose'] = $group_purpose;
                    $this->data['Group']['group_type'] = $group_type;
                    $this->data['Group']['group_owners'] = $user_id;
                    $this->data['Group']['created_by'] = $user_id;
                    $this->data['Group']['category_id'] = $category_id;
                    $this->data['Group']['icon'] = $upload_image;
                    $this->data['Group']['card_number'] = '0';
                    $this->data['Group']['expiry_month'] = '0';
                    $this->data['Group']['expiry_year'] = '0';
                    $this->data['Group']['cvv_number'] = '0';
                    $this->data['Group']['approval_code'] = '0';
                    $this->data['Group']['avs_result'] = '0';
                    $this->data['Group']['cvv_result'] = '0';
                    $this->data['Group']['transaction_id'] = '0';
                    $this->data['Group']['state_id'] = $state_id;
                    $this->data['Group']['city_id'] = $city_id;
    
                    
                    $this->Group->create();
                    
                     if($this->Group->save($this->data['Group'])){
                     
                            $last_insert_id = $this->Group->getLastInsertId();
                            if($user_details['User']['groups']!=''){
    
                                $this->data['User']['id'] = $user_id;
                                $this->data['User']['groups'] = $user_details['User']['groups'].",".$last_insert_id;
                                $this->User->save($this->data); 
                            }
                            else
                            {
                               $this->data['User']['id'] = $user_id;
                               $this->data['User']['groups'] = $last_insert_id;
                               $this->User->save($this->data);
                            }
    
                            $this->data['GroupUser']['group_id'] = $last_insert_id;
                            $this->data['GroupUser']['user_type'] = 'O';
                            $this->data['GroupUser']['user_id'] = $user_id;
                            $this->GroupUser->create();
                            
                            if($this->GroupUser->save($this->data['GroupUser'])){
                                 $response['group_id'] = $last_insert_id;
                                 $response['success_msg'] = "The Free Group Created Successfully !!!";  
                            }
                    } 
            }
            else if($group_type == 'B'){

                    $this->data['Group']['plan_id'] = '0';
                    $this->data['Group']['amount'] = '0';
                    $this->data['Group']['group_title'] = $group_title;
                    $this->data['Group']['group_desc'] = $group_desc;
                    //$this->data['Group']['group_purpose'] = $group_purpose;
                    $this->data['Group']['group_url'] = addslashes($_POST['group_url']);
                    $this->data['Group']['group_type'] = $group_type;
                    $this->data['Group']['group_owners'] = $user_id;
                    $this->data['Group']['created_by'] = $user_id;
                    $this->data['Group']['category_id'] = $category_id;
                    $this->data['Group']['icon'] = $upload_image;
                    $this->data['Group']['card_number'] = '0';
                    $this->data['Group']['expiry_month'] = '0';
                    $this->data['Group']['expiry_year'] = '0';
                    $this->data['Group']['cvv_number'] = '0';
                    $this->data['Group']['approval_code'] = '0';
                    $this->data['Group']['avs_result'] = '0';
                    $this->data['Group']['cvv_result'] = '0';
                    $this->data['Group']['transaction_id'] = '0';
                    $this->data['Group']['state_id'] = $state_id;
                    $this->data['Group']['city_id'] = $city_id;
                    
                    
                    
                    $this->Group->create();
                    if($this->Group->save($this->data['Group'])){
                    
                            $last_insert_id = $this->Group->getLastInsertId();
                            if($user_details['User']['groups']!=''){
                                
                                $this->data['User']['id'] = $user_id;
                                $this->data['User']['groups'] = $user_details['User']['groups'].",".$last_insert_id;
                                $this->User->save($this->data); 
                            }
                            else{
                            
                               $this->data['User']['id'] = $user_id;
                               $this->data['User']['groups'] = $last_insert_id;
                               $this->User->save($this->data);
                            }
                            
                            
                            $this->data['GroupUser']['group_id'] = $last_insert_id;
                            $this->data['GroupUser']['user_type'] = 'O';
                            $this->data['GroupUser']['user_id'] = $user_id;
                            $this->GroupUser->create();
                           
                            if($this->GroupUser->save($this->data['GroupUser'])){
                                $response['group_id'] = $last_insert_id;
                                $response['success_msg'] = "The Business Group Created Successfully !!!";   
                            }
                   } 
            }
			else if($group_type == 'PO'){

                    $this->data['Group']['plan_id'] = '0';
                    $this->data['Group']['amount'] = '0';
                    $this->data['Group']['group_title'] = $group_title;
                    $this->data['Group']['group_desc'] = $group_desc;
                    //$this->data['Group']['group_purpose'] = $group_purpose;
                    $this->data['Group']['group_url'] = addslashes($_POST['group_url']);
                    $this->data['Group']['group_type'] = $group_type;
                    $this->data['Group']['group_owners'] = $user_id;
                    $this->data['Group']['created_by'] = $user_id;
                    $this->data['Group']['category_id'] = $category_id;
                    $this->data['Group']['icon'] = $upload_image;
                    $this->data['Group']['card_number'] = '0';
                    $this->data['Group']['expiry_month'] = '0';
                    $this->data['Group']['expiry_year'] = '0';
                    $this->data['Group']['cvv_number'] = '0';
                    $this->data['Group']['approval_code'] = '0';
                    $this->data['Group']['avs_result'] = '0';
                    $this->data['Group']['cvv_result'] = '0';
                    $this->data['Group']['transaction_id'] = '0';
                    $this->data['Group']['state_id'] = $state_id;
                    $this->data['Group']['city_id'] = $city_id;
                    
                    
                    
                    $this->Group->create();
                    if($this->Group->save($this->data['Group'])){
                    
                            $last_insert_id = $this->Group->getLastInsertId();
                            if($user_details['User']['groups']!=''){
                                
                                $this->data['User']['id'] = $user_id;
                                $this->data['User']['groups'] = $user_details['User']['groups'].",".$last_insert_id;
                                $this->User->save($this->data); 
                            }
                            else{
                            
                               $this->data['User']['id'] = $user_id;
                               $this->data['User']['groups'] = $last_insert_id;
                               $this->User->save($this->data);
                            }
                            
                            
                            $this->data['GroupUser']['group_id'] = $last_insert_id;
                            $this->data['GroupUser']['user_type'] = 'O';
                            $this->data['GroupUser']['user_id'] = $user_id;
                            $this->GroupUser->create();
                           
                            if($this->GroupUser->save($this->data['GroupUser'])){
                                $response['group_id'] = $last_insert_id;
                                $response['success_msg'] = "The Business Group Created Successfully !!!";   
                            }
                   } 
            }
        }
     
     
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function group_details(){
        $this->layout = '';
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
       
        $group_id = $obj->{'group_id'};
        $date    = $obj->{'date'};
        $user_id    = $obj->{'user_id'};
                
       /*$group_id = '45';
       $date = '2017-06-29';*/
       

       $condition_notification_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."' AND GroupUser.user_type = 'M'";
       $arr_group_notification_status = $this->GroupUser->find('first',array('conditions'=>$condition_notification_status)); 

       $con_post_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
       $arr_post_topic_status = $this->GroupUser->find('first',array('conditions'=>$con_post_status, 'fields' => 'GroupUser.can_post_topic')); 

       if(!empty($arr_group_notification_status)){
            $push_message_notification_status= $arr_group_notification_status['GroupUser']['is_notification_stop'];
       }
       else{
            $push_message_notification_status= '';
       }
       
       /*$con_user_type = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_id."'  AND GroupUser.user_id = '".$user_id."'";	//Check the logged in user is owner or not 
       $arr_user_group_status = $this->GroupUser->find("first",array('conditions'=>$con_user_type));
	   if(!empty($arr_user_group_status)){
	   
	   		$group_user_type = $arr_user_group_status['GroupUser']['user_type'];
			if($group_user_type=='O'){
				$group_member_type = 'owner';	
			}
			else if($group_user_type=='M'){
				$group_member_type = 'member';	
			}    
	   }
	   else{
	   		$group_user_type = '';
			$group_member_type = 'nonmember';
	   }*/
		
	   
	   
	     
       $condition = "Group.id = '".$group_id."' AND Group.status = '1'";
       $group_detail = $this->Group->find('first',array('conditions'=>$condition)); 

		if($group_detail['Group']['parent_id']!='0'){
			$parent_grp_id= $group_detail['Group']['parent_id'];
			
			$condition_parent_grp = "Group.status = '1' AND (find_in_set('".$user_id."',Group.`group_owners`)) AND Group.id = '".$parent_grp_id."'";
			$is_parent_grp_owner = $this->Group->find('count',array('conditions'=>$condition_parent_grp));  
	   }
	   else{
			$is_parent_grp_owner = 0;
	   }
	   
	   

       $con_msg = "GroupMessage.group_id = '".$group_id."' AND GroupMessage.status = '1' AND GroupMessage.isdeleted = '0'";
       $GroupPostCount = $this->GroupMessage->find('count',array('conditions'=>$con_msg)); 
         
         if(!empty($group_detail)){

            $response['is_error'] = 0;
            $response['id'] = $group_detail['Group']['id'];
            $response['category_id'] = $group_detail['Group']['category_id'];
            $response['group_title'] = ucfirst(stripslashes($group_detail['Group']['group_title']));
            $response['group_desc'] = ucfirst(stripslashes($group_detail['Group']['group_desc']));
            $response['group_purpose'] = ucfirst(stripslashes($group_detail['Group']['group_purpose']));
            $response['group_url'] = stripslashes($group_detail['Group']['group_url']);
            $response['group_type'] = $group_detail['Group']['group_type'];
            $response['push_message_notification_status'] = $push_message_notification_status;
			$response['is_parent_grp_owner'] = $is_parent_grp_owner;
            $response['can_post_topic'] = $arr_post_topic_status['GroupUser']['can_post_topic'];
            $response['post_count'] = $GroupPostCount; 
			$response['parent_group_id'] = $group_detail['Group']['parent_id']; 
			//$response['group_user_type'] = $group_user_type; 
			//$response['group_member_type'] = $group_member_type; 
			
			if($group_detail['Group']['parent_id']!='0'){			// Sub Group
				if($group_detail['Group']['created_by'] == $user_id || $is_parent_grp_owner>0){
					$response['can_delete'] = 1; 
				}
				else{
					$response['can_delete'] = 0; 
				}
			}
			else{
				if($group_detail['Group']['created_by'] == $user_id){
					$response['can_delete'] = 1; 
				}
				else{
					$response['can_delete'] = 0; 
				}
			}
			
                        
            if($group_detail['Group']['icon']!=''){
            
				if($group_detail['Group']['parent_id']=='0'){
					$response['image_url']['thumb'] = $base_url.'group_images/thumb/'.$group_detail['Group']['icon'];
					$response['image_url']['medium'] = $base_url.'group_images/medium/'.$group_detail['Group']['icon'];       
					$response['image_url']['original'] = $base_url.'group_images/'.$group_detail['Group']['icon'];
				}
				else{
					$response['image_url']['thumb'] = $base_url.'sub_group_images/thumb/'.$group_detail['Group']['icon'];
					$response['image_url']['medium'] = $base_url.'sub_group_images/medium/'.$group_detail['Group']['icon'];       
					$response['image_url']['original'] = $base_url.'sub_group_images/'.$group_detail['Group']['icon'];
				}
            }
            else{
            
                $response['image_url']['thumb'] = $base_url.'images/no-group-img_1.jpg';
                $response['image_url']['medium'] = $base_url.'images/no-group-img_1.jpg';
                $response['image_url']['original'] = $base_url.'images/no-group-img_1.jpg';
            }
            
            

            //video count
            $condition_video_count = "Video.group_id = '".$group_id."' AND Video.status = '1'";
            $video_count = $this->Video->find('count',array('conditions'=>$condition_video_count));

            $response['video_count'] = $video_count; 

            //image count
            $condition_image_count = "GroupImage.group_id = '".$group_id."' AND GroupImage.status = '1'";
            $image_count = $this->GroupImage->find('count',array('conditions'=>$condition_image_count));

            $response['image_count'] = $image_count; 

             //doc count
            $condition_doc_count = "GroupDoc.group_id = '".$group_id."' AND GroupDoc.status = '1'";
            $doc_count = $this->GroupDoc->find('count',array('conditions'=>$condition_doc_count));

            $response['doc_count'] = $doc_count; 
			
			##################################         Check the list of sub Groups of the Group starts        ###########################
		
			if($group_detail['Group']['group_type']!='B'){
				$con_fetch_subgrps = "Group.status= '1'  AND Group.parent_id= '".$group_id."'";
				$all_subgrps = $this->Group->find("all",array('conditions'=>$con_fetch_subgrps));
				if(!empty($all_subgrps)){
					$arr_subgrps= array();
					foreach($all_subgrps as $all_sub_grps){
						array_push($arr_subgrps, $all_sub_grps['Group']['id']);		
					}
					$str_subgroups= implode(',',$arr_subgrps);
				}
				else{
					$str_subgroups='';
				}
			}
			else{
				$str_subgroups='';	
			}
			##################################         Check the list of sub Groups of the Group ends        ###########################
      
            if($str_subgroups!=''){
            	$event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE (`group_id` = '".$group_id."' OR (`group_id` IN (".$str_subgroups.") AND (`type` = 'public' OR `type` = 'semi_private'))) AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE (`group_id` = '".$group_id."' OR (`group_id` IN (".$str_subgroups.") AND (`type` = 'public' OR `type` = 'semi_private'))) AND `status` = '1' AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
			}
			else{
				$event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
			}

            $event_list = array(); 
            
            if(count($event_details) > 0){
             
                foreach($event_details as $events){
                
                    $list = array();

                    $list['id'] = $events['Event']['id'];
                    $list['event_name'] =  ucfirst(stripslashes($events['Event']['title']));
                    $list['desc'] =  ucfirst(stripslashes($events['Event']['desc']));
                    $list['deal_amount'] =  $events['Event']['deal_amount'];
                    
                    $list['group_id'] = $events['Event']['group_id'];
					
					if($events['Event']['group_id']!=$group_id){
					
						$condition_grp_name = "Group.id = '".$events['Event']['group_id']."'";
						$arr_grp_name = $this->Group->find('first',array('conditions'=>$condition_grp_name)); 
						$list['group_name'] =  ucfirst(stripslashes($arr_grp_name['Group']['group_title']));
					}
					else{
                    	$list['group_name'] =  ucfirst(stripslashes($group_detail['Group']['group_title']));
					}
                    $list['group_type'] =  $events['Event']['group_type'];
                    $list['event_type'] = $events['Event']['type'];
                    $list['is_multiple_date'] = $events['Event']['is_multiple_date'];
                   
                    $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
                    $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 
                    if($events['Event']['is_multiple_date'] == '1')
                    {

                        $list['event_start_date_time'] =  date("Y-m-d H:i:s",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_end_timestamp']);
                    }
                    else
                    {
                       $list['event_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_timestamp']);        

                    }
                   
                    $list['location'] = $events['Event']['location'];
                    $list['latitude'] =  $events['Event']['latitude'];
                    $list['longitude'] =  $events['Event']['longitude'];
                    $list['place_id'] =  $events['Event']['place_id'];
                    
                    
                    array_push($event_list,$list);
                }
                $response['event_list']= $event_list; 
                $response['is_error'] = 0;
            }
            else{
                $response['is_error'] = 0;
                $response['event_list']= array(); 
            }
			
			
            /***********************      Fetch the current month with Events starts       **********************/
			/*$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
			$last_day_this_month  = date('Y-m-t');
			$days_this_month= date('t');
			$current_day_this_month= $first_day_this_month;
			$arr_event_dates= array();
			
			for($i=1;$i<=$days_this_month;$i++){
				
				 if($current_day_this_month<= $last_day_this_month){
					 $con_count_events = "((Event.`event_date` = '".$current_day_this_month."') OR (Event.`event_start_date` <= '".$current_day_this_month."' AND Event.`event_end_date` >= '".$current_day_this_month."')) AND Event.`group_id` = '".$group_id."' AND Event.`status` = '1'";
					 $event_count = $this->Event->find('count',array('conditions' => $con_count_events));
					 
					 if($event_count>0){
						array_push($arr_event_dates, $current_day_this_month);
					 }
					 
					 $current_day_this_month= date('Y-m-d', strtotime("+1 day", strtotime($current_day_this_month)));
				 }
			}
			
			$response['event_dates']= $arr_event_dates; */
			/***********************      Fetch the current month with Events ends       **********************/    
			    
           }
           else
           {
                $response['is_error'] = 1;
                $response['err_msg'] = 'No Group found';
           } 
            
       /*echo '<pre>';
       print_r($response);
       echo '</pre>';  */  
       header('Content-type: application/json');
       echo json_encode($response);
       exit;
        
    }
	
	function fetch_event_dates(){
  	
		$this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $group_id    = $obj->{'group_id'};
		$next_month_date = $obj->{'month_date'};
		
		$con_grp_details = "Group.status= '1'  AND Group.id= '".$group_id."'";
		$arr_group_detail = $this->Group->find('first',array('conditions'=>$con_grp_details));
		
		##################################         Check the list of sub Groups of the Group starts        ###########################
		
		if($arr_group_detail['Group']['group_type']!='B'){
			$con_fetch_subgrps = "Group.status= '1'  AND Group.parent_id= '".$group_id."'";
			$all_subgrps = $this->Group->find("all",array('conditions'=>$con_fetch_subgrps));
			if(!empty($all_subgrps)){
				$arr_subgrps= array();
				foreach($all_subgrps as $all_sub_grps){
					array_push($arr_subgrps, $all_sub_grps['Group']['id']);		
				}
				$str_subgroups= implode(',',$arr_subgrps);
			}
			else{
				$str_subgroups='';
			}
		}
		else{
			$str_subgroups='';
		}
		##################################         Check the list of sub Groups of the Group ends        ###########################
		
		/***********************      Fetch the dates with Events starts       **********************/
		$first_day_this_month = date('Y-m-01', strtotime($next_month_date)); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-t', strtotime($next_month_date));
		$days_this_month= date('t', strtotime($next_month_date));
		$current_day_this_month= $first_day_this_month;
		$arr_event_dates= array();
		
		for($i=1;$i<=$days_this_month;$i++){
			
			 if($current_day_this_month<= $last_day_this_month){
			 
			 	 if($str_subgroups!=''){
					 $con_count_events = "((Event.`event_date` = '".$current_day_this_month."') OR (Event.`event_start_date` <= '".$current_day_this_month."' AND Event.`event_end_date` >= '".$current_day_this_month."')) AND (Event.`group_id` = '".$group_id."' OR (Event.`group_id` IN (".$str_subgroups.") AND (`type` = 'public' OR `type` = 'semi_private'))) AND Event.`status` = '1'";
				 }
				 else{
				 	$con_count_events = "((Event.`event_date` = '".$current_day_this_month."') OR (Event.`event_start_date` <= '".$current_day_this_month."' AND Event.`event_end_date` >= '".$current_day_this_month."')) AND Event.`group_id` = '".$group_id."' AND Event.`status` = '1'";
				 }
				 
				 $event_count = $this->Event->find('count',array('conditions' => $con_count_events));
				 
				 if($event_count>0){
					array_push($arr_event_dates, $current_day_this_month);
				 }
				 
				 $current_day_this_month= date('Y-m-d', strtotime("+1 day", strtotime($current_day_this_month)));
			 }
		}
		
		$response['is_error'] = 0;
		$response['event_dates']= $arr_event_dates; 
		
		
		header('Content-type: application/json');
        echo json_encode($response);
        exit;
		/***********************      Fetch the dates with Events ends       **********************/
		
  }
	
	function fetch_event_dates_community_calendar(){
  	
		$this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

		$selected_state_id = $obj->{'state_id'};
		$selected_city_id = $obj->{'city_id'};
		$next_month_date = $obj->{'month_date'};
		
		if($selected_state_id>0 && $selected_city_id>0){
				
				  $conditions_group = "Group.city_id = '".$selected_city_id."' AND Group.state_id = '".$selected_state_id."' ";
		          $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
		          $group = array(); 
		            foreach($all_groups as $grp)
		            {

		              array_push($group,$grp['Group']['id']);
		            }
            		$allstatecitygroups = implode(",",$group);
				
			}
		
		/***********************      Fetch the dates with Events starts       **********************/
		$first_day_this_month = date('Y-m-01', strtotime($next_month_date)); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-t', strtotime($next_month_date));
		$days_this_month= date('t', strtotime($next_month_date));
		$current_day_this_month= $first_day_this_month;
		$arr_event_dates= array();
		
		for($i=1;$i<=$days_this_month;$i++){
				
				 if($current_day_this_month<= $last_day_this_month){
					 $con_count_events = "((Event.`event_date` = '".$current_day_this_month."') OR (Event.`event_start_date` <= '".$current_day_this_month."' AND Event.`event_end_date` >= '".$current_day_this_month."')) AND (Event.`group_type` = 'F' OR Event.`group_type` = 'PO') AND Event.`type` = 'public'  AND Event.`status` = '1' AND Event.`group_id` IN (". $allstatecitygroups .")";
					 $event_count = $this->Event->find('count',array('conditions' => $con_count_events));
					 
					 if($event_count>0){
						array_push($arr_event_dates, $current_day_this_month);
					 }
					 
					 $current_day_this_month= date('Y-m-d', strtotime("+1 day", strtotime($current_day_this_month)));
				 }
			}
		
		$response['is_error'] = 0;
		$response['event_dates']= $arr_event_dates; 
		
		
		header('Content-type: application/json');
        echo json_encode($response);
        exit;
		/***********************      Fetch the dates with Events ends       **********************/
		
  }
  
    
	function fetch_event_dates_business_calendar(){
  	
		$this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

		$selected_state_id = $obj->{'state_id'};
		$selected_city_id = $obj->{'city_id'};
		$next_month_date = $obj->{'month_date'};
		
		if($selected_state_id>0 && $selected_city_id>0){
				
				  $conditions_group = "Group.city_id = '".$selected_city_id."' AND Group.state_id = '".$selected_state_id."' ";
		          $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
		          $group = array(); 
		            foreach($all_groups as $grp)
		            {

		              array_push($group,$grp['Group']['id']);
		            }
            		$allstatecitygroups = implode(",",$group);
				
			}
		
		/***********************      Fetch the dates with Events starts       **********************/
		$first_day_this_month = date('Y-m-01', strtotime($next_month_date)); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-t', strtotime($next_month_date));
		$days_this_month= date('t', strtotime($next_month_date));
		$current_day_this_month= $first_day_this_month;
		$arr_event_dates= array();
		
		for($i=1;$i<=$days_this_month;$i++){
				
				 if($current_day_this_month<= $last_day_this_month){
					 $con_count_events = "((Event.`event_date` = '".$current_day_this_month."') OR (Event.`event_start_date` <= '".$current_day_this_month."' AND Event.`event_end_date` >= '".$current_day_this_month."')) AND Event.`group_type` = 'B' AND Event.`type` = 'public'  AND Event.`status` = '1' AND Event.`group_id` IN (". $allstatecitygroups .")";
					 $event_count = $this->Event->find('count',array('conditions' => $con_count_events));
					 
					 if($event_count>0){
						array_push($arr_event_dates, $current_day_this_month);
					 }
					 
					 $current_day_this_month= date('Y-m-d', strtotime("+1 day", strtotime($current_day_this_month)));
				 }
			}
		
		$response['is_error'] = 0;
		$response['event_dates']= $arr_event_dates; 
		
		
		header('Content-type: application/json');
        echo json_encode($response);
        exit;
		/***********************      Fetch the dates with Events ends       **********************/
		
  }
	
	
	function delete_group(){
    
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $group_id    = $obj->{'group_id'};
    
        if($group_id>0){
        
             $conditions = "Group.id = '".$group_id."'"; 
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
		 
		 
		     $arr_group_docs = $this->GroupDoc->find('all',array('conditions' => array('GroupDoc.group_id' => $Group['Group']['id']), 'fields' => array('GroupDoc.id', 'GroupDoc.docname')));
			 if(!empty($arr_group_docs)){
				foreach($arr_group_docs as $key => $val_doc){
					
					$folder='gallery/doc/';
					$this->removeFile($val_doc['GroupDoc']['docname'],$folder);
					
					$this->GroupDoc->id = $val_doc['GroupDoc']['id'];
					$this->GroupDoc->delete();
					
				}
			 }
			 
			 
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
              
             $response['is_error'] = 0;
             $response['success_msg'] = 'Group deleted successfully !!!';
        }         
        else{
             $response['is_error'] = 1;
             $response['err_msg'] = 'Group does not exist';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;   
    }
    
    
    function edit_group(){
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $user_id = $_POST['user_id'];
        $group_id = $_POST['group_id']; 
        $group_name = $_POST['group_name']; 
        $description = $_POST['description']; 
        //$purpose = $_POST['purpose']; 
        $new_group_type = $_POST['new_group_type']; 
        $category_id = $_POST['category_id'];
   
        
       /* $user_id = '4';
        $group_id = '67'; 
        $group_name = 'Fashion Mania'; 
        $description = 'Fashion On Clothing And More'; 
        $purpose = 'Fashion'; 
        $new_group_type ='B';
        $upload_image = '';
        $category_id = '3';*/
        
        
        
        $condition_group_detail = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."' AND GroupUser.user_type = 'O' AND GroupUser.status= '1'";
        $GroupUserData = $this->GroupUser->find('count', array('conditions' => $condition_group_detail));
		
		$condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";
		$group_details = $this->Group->find('first',array('conditions'=>$condition2));
		$parent_grp_id = $group_details['Group']['parent_id'];
		
		if($parent_grp_id!='0'){			//Determines the logged in user is the owner of the Parent Group/ Not
				
			$condition_parent_grp = "Group.status = '1' AND (find_in_set('".$user_id."',Group.`group_owners`)) AND Group.id = '".$parent_grp_id."'";
			$is_parent_grp_owner = $this->Group->find('count',array('conditions'=>$condition_parent_grp));  	
		}
		else{
			$is_parent_grp_owner=0;
		}

       // pr($GroupUserData);exit();
        if($GroupUserData > 0 || $is_parent_grp_owner>0){

            $condition_group_detail = "Group.id = '".$group_id."'";
            $GroupData = $this->Group->find('first', array('conditions' => $condition_group_detail));
            $this->set('GroupData', $GroupData);
    

            $upload_image = '';
    
           if(isset($_FILES["upload_image"]) && $_FILES["upload_image"]['name']!= ''){
           
            $image_name = $_FILES["upload_image"]['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'group_images/'.$upload_image;
                        
            $imagelist = getimagesize($_FILES["upload_image"]['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2){
          
                  if($uploaded_width >=160 && $uploaded_height >= 120){
                  
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                        
                                $upload_target_thumb = 'group_images/thumb/'.$upload_image;
                                $upload_target_medium = 'group_images/medium/'.$upload_image;
                                $upload_target_web = 'group_images/web/'.$upload_image;
                               
        
                                $max_web_width =  262;
                                $max_web_height = 178;
                                
                                $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                                $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
                                $this->imgOptCpy($upload_target_original, $upload_target_web, $max_web_width, $max_web_height, 100, true);
                                $this->data['Group']['icon'] = $upload_image;
                                
                                
                                $folder = 'group_images/';
                                $folder_medium = 'group_images/medium/';
                                $folder_thumb = 'group_images/thumb/';
                                $folder_web = 'group_images/web/';
                                
                                $this->removeFile($GroupData['Group']['icon'],$folder);
                                $this->removeFile($GroupData['Group']['icon'],$folder_medium);
                                $this->removeFile($GroupData['Group']['icon'],$folder_thumb);
                                $this->removeFile($GroupData['Group']['icon'],$folder_web);                         
                    }         
                    else {
                        
                            $response['is_error'] = 1;
                            $response['err_msg'] = 'Image upload failed';
                    }
                }
                else
                {        
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'Please upload a 200x100 or bigger image only';
                }
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'Please upload jpg,jpeg and gif image only';
            }
    
        }
            else
            {
                $this->data['Group']['icon'] = $GroupData['Group']['icon'];
            }
                   
           $this->data['Group']['group_title'] = addslashes($group_name);
           $this->data['Group']['group_desc'] = addslashes($description);
           //$this->data['Group']['group_purpose'] = addslashes($purpose);
           $this->data['Group']['group_type'] = $new_group_type;
           if($new_group_type == 'F')
           {
            $this->data['Group']['category_id'] = '0';
           }
           else if($new_group_type == 'B' || $new_group_type == 'PO')
           {
            $this->data['Group']['group_url'] = addslashes($_POST['group_url']);
            $this->data['Group']['category_id'] = $category_id;
           } 
           $this->data['Group']['id'] = $group_id;
       
           if ($this->Group->save($this->data['Group'])) {

			if($new_group_type == 'B'){
				$this->Event->query("UPDATE `events` SET `group_type` = '".$new_group_type."' , `type` = 'public', `category_id` = '".$category_id."'WHERE `group_id` = '".$group_id."'");
			}
			else{
				$this->Event->query("UPDATE `events` SET group_type = '".$new_group_type."' , category_id = '".$category_id."'WHERE `group_id` = '".$group_id."'");
			}
            

            $this->Notification->query("UPDATE `notifications` SET group_type = '".$new_group_type."' WHERE `group_id` = '".$group_id."'");
                            
                    $response['is_error'] = 0;
                    $response['success_msg'] = 'Group Edited Successfully!!';
           }
        }
        else{        
            $response['is_error'] = 1;
            $response['err_msg'] = 'Unauthorized to edit this group';
        }
          
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function push_message_member_list(){
       
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;

        $group_id    = $obj->{'group_id'};
        $group_type    = $obj->{'group_type'};
        $user_id    = $obj->{'user_id'};
        
        /*$user_id    = '4';
        $group_id    = '2';*/

    
        $group_members = array(); 
        $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";    //Condition to check Group Owner
        $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));
		
		$condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";
		$group_details = $this->Group->find('first',array('conditions'=>$condition2));
		$parent_grp_id = $group_details['Group']['parent_id'];
		
		if($parent_grp_id!='0'){			//Determines the logged in user is the owner of the Parent Group/ Not
				
			$condition_parent_grp = "Group.status = '1' AND (find_in_set('".$user_id."',Group.`group_owners`)) AND Group.id = '".$parent_grp_id."'";
			$is_parent_grp_owner = $this->Group->find('count',array('conditions'=>$condition_parent_grp));  	
		}
		else{
			$is_parent_grp_owner=0;
		}

        if($count_chk_owner > 0 || $is_parent_grp_owner>0){


      if($parent_grp_id > 0){
			$condition3 = "Group.status= '1' AND  Group.id = '".$parent_grp_id."'";
			$parent_group_details = $this->Group->find('first',array('conditions'=>$condition3));
			$parent_group_owners = $parent_group_details['Group']['group_owners'];
			
			$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."' AND  GroupUser.user_id != '".$user_id."' AND GroupUser.user_id NOT IN (".$parent_group_owners.")";  // Fetches the all members (public+private) of the Group
		
		}
		else{
			$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."' AND  GroupUser.user_id != '".$user_id."'";  // Fetches the all members (public+private) of the Group
		}
      $this->GroupUser->bindModel(array('belongsTo' => array(
                'User' => array(
                'className' => 'User','foreignKey' => 'user_id',
                'fields' => array('User.id','User.fname','User.lname','User.image')
                )
        )));
        $this->GroupUser->recursive=2;
        $all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));

        if(!empty($all_group_members)){
            foreach($all_group_members as $groups){
          
                $list['id'] = $groups['GroupUser']['id'];
                $list['user_id'] = $groups['GroupUser']['user_id'];
                $list['user_type'] = $groups['GroupUser']['user_type'];
                $list['member_mode'] = $groups['GroupUser']['member_mode'];          
                $list['group_id'] = $groups['GroupUser']['group_id'];
    
                $list['user_name'] = ucfirst($groups['User']['fname'].' '.$groups['User']['lname']) ;
                if($groups['User']['image']!=''){
    
                  $list['image_url'] = $base_url.'user_images/thumb/'.$groups['User']['image'];
    
                }
                else
                {
                  $list['image_url'] = $base_url.'images/no_profile_img.jpg';
                }
    
               //
              array_push($group_members,$list);
    
              
            }
             
            $response['group_members']= $group_members; 
            $response['is_error'] = 0;
        }
        else{
              $response['group_members']= array(); 
              $response['is_error'] = 1;
              $response['err_msg'] = 'No Group member found';
        }

        }
        else{
          $response['group_members']= array(); 
          $response['is_error'] = 1;
          $response['err_msg'] = 'Unauthorized to push massage to this group';
        }


    
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
	
	
	function push_message_owner_list(){
       
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;

        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        
        /*$user_id    = '4';
        $group_id    = '2';*/

    
        $group_owners = array(); 
        
		$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."' AND  GroupUser.user_type = 'O'";  // Fetches the all owners of the Group
		$all_group_owners = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));

        if(count($all_group_owners)>0){
			foreach($all_group_owners as $owners){
					
					$list['id'] = $owners['GroupUser']['id'];
					$list['user_id'] = $owners['GroupUser']['user_id'];
					$list['user_type'] = $owners['GroupUser']['user_type'];
	
					$con_owner = "User.status= '1' AND  User.id = '".$owners['GroupUser']['user_id']."'";
					$owner_detail = $this->User->find('first',array('conditions'=>$con_owner));
					$list['user_name'] = ucfirst(stripslashes($owner_detail['User']['fname']).' '.stripslashes($owner_detail['User']['lname'])) ;
					if(!empty($owner_detail['User']['image']))
					{
					  $list['image_url'] = $base_url.'user_images/thumb/'.$owner_detail['User']['image'];  
					}
					else
					{
					  $list['image_url'] = $base_url.'images/no_profile_img.jpg';
					}
	
					array_push($group_owners,$list);
	
			}
				  
			$response['group_owners']= $group_owners; 
			$response['is_error'] = 0;
			
		}
		else{
			  	$response['group_owners']= array(); 
            	$response['is_error'] = 1;
            	$response['err_msg'] = 'No Group owner found';
		}
       
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function push_message(){
           
            $this->layout = ""; 
            $response = array();
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            
            $sender_id    = $obj->{'sender_id'};            
            $group_id    = $obj->{'group_id'};
            $group_type    = $obj->{'group_type'};
            $group_title    = $obj->{'group_title'};
            $message = $obj->{'message'};
            $member_list    = $obj->{'member_list'};



            if(!empty($member_list)){
                $con_sender_detail = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$sender_id."'";
                $sender_detail = $this->GroupUser->find('first',array('conditions'=>$con_sender_detail));
				
				$condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";
				$group_details = $this->Group->find('first',array('conditions'=>$condition2));
				$parent_grp_id = $group_details['Group']['parent_id'];
				
				if($parent_grp_id!='0'){			//Determines the logged in user is the owner of the Parent Group/ Not
						
					$condition_parent_grp = "Group.status = '1' AND (find_in_set('".$sender_id."',Group.`group_owners`)) AND Group.id = '".$parent_grp_id."'";
					$is_parent_grp_owner = $this->Group->find('count',array('conditions'=>$condition_parent_grp));  	
				}
				else{
					$is_parent_grp_owner=0;
				}

                if($sender_detail['GroupUser']['user_type'] == 'O' || $is_parent_grp_owner>0){
                

                foreach($member_list as $members){
                     
                        $this->data['Notification']['sender_id'] = $sender_id;
                        $this->data['Notification']['type'] = 'P';
                        $this->data['Notification']['group_id'] = $group_id;
                        $this->data['Notification']['message'] = addslashes($message);                     
                        $this->data['Notification']['receiver_id'] = $members;
                        $this->data['Notification']['group_type'] = $group_type;
                        $this->data['Notification']['sender_type'] = 'GO';
                        
                        $con_member_detail = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$members."'";
                        $member_detail = $this->GroupUser->find('first',array('conditions'=>$con_member_detail));
    
                        if($member_detail['GroupUser']['user_type'] == 'O')
                        {
                            $this->data['Notification']['receiver_type'] = 'GO';
                        }
                        else if($member_detail['GroupUser']['user_type'] == 'M')
                        {
                            $this->data['Notification']['receiver_type'] = 'GM';    
                        }
    
                        $this->data['Notification']['is_read'] = 0;
                        $this->data['Notification']['is_receiver_accepted'] = 2;
                        $this->data['Notification']['is_reversed_notification'] = 0;
                        $this->data['Notification']['status'] = 1;
                        $this->Notification->create();
                        
                        if($this->Notification->save($this->data['Notification'])){
                            $notification_id = $this->Notification->getLastInsertId();  
                            $this->notification_email($notification_id);
                            
                            if($member_detail['GroupUser']['user_type'] == 'O' || ($member_detail['GroupUser']['user_type'] == 'M' && $member_detail['GroupUser']['is_notification_stop'] == '0')){
                                    $page="Push message from ".$group_title;
                                    $this->send_notification($sender_id, $members, $group_id, $message, $page,'o_m');
                            }
                              
                         }
      
                }
                $response['is_error'] = 0;  
                $response['success_msg'] = 'Message sent successfully';
                
                }
                else if($sender_detail['GroupUser']['user_type'] == 'M'){
                //$this->data['Notification']['sender_type'] = 'GM'; 
                    $response['is_error'] = 1;  
                    $response['err_msg'] = 'You are not authorized';  
                }
            }
            else
            {
    
                $response['is_error'] = 1;  
                $response['err_msg'] = 'No member available into Group';
    
            }

         header('Content-type: application/json');
         echo json_encode($response);
         exit;

     }
	 
	 
	function push_message_to_owners(){
           
            $this->layout = ""; 
            $response = array();
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            
            $sender_id    = $obj->{'sender_id'};            
            $group_id    = $obj->{'group_id'};
            $group_type    = $obj->{'group_type'};
            $group_title    = $obj->{'group_title'};
            $message = $obj->{'message'};
            $member_list    = $obj->{'member_list'};



            if(!empty($member_list)){
				foreach($member_list as $owners){
					
						$this->data['Notification']['sender_id'] = $sender_id;
						$this->data['Notification']['type'] = 'P';
						$this->data['Notification']['group_id'] = $group_id;
						$this->data['Notification']['message'] = $message;                     
						$this->data['Notification']['receiver_id'] = $owners;
						
	
						$this->data['Notification']['receiver_type'] = 'GO';
						$this->data['Notification']['sender_type'] = 'GM';	
						$this->data['Notification']['is_read'] = 0;
						$this->data['Notification']['is_receiver_accepted'] = 2;
						$this->data['Notification']['is_reversed_notification'] = 0;
						$this->data['Notification']['status'] = 1;
						$this->Notification->create();
						
                        if($this->Notification->save($this->data['Notification'])){

						  // send notification email for friend request
							 $notification_id = $this->Notification->getLastInsertId();  
							 $this->notification_email($notification_id);
						  
							$page="Push message from ".$group_title;
							//echo $sender_id.'--'.$owners.'--'.$group_id.'--'.$message.'--'.$page;exit;
							$this->send_notification($sender_id, $owners, $group_id, $message, $page,'m_o'); 
							 
						 }
						  
					}
					
			    $response['is_error'] = 0;  
                $response['success_msg'] = 'Message sent successfully';
			}
            else
            {
    
                $response['is_error'] = 1;  
                $response['err_msg'] = 'No Owner available into Group';
    
            }

         header('Content-type: application/json');
         echo json_encode($response);
         exit;

     }
	 
	 
	
     
    
    function make_owner(){
       
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        
        $sender_id    = $obj->{'sender_id'};            
        $group_id    = $obj->{'group_id'};
        $receiver_id    = $obj->{'receiver_id'};
        
        $con_chk_receiver_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$receiver_id."' AND GroupUser.status = '1'";
        $arr_receiver_status = $this->GroupUser->find('first',array('conditions'=>$con_chk_receiver_status));
        
        if(!empty($arr_receiver_status)){
        
            if($arr_receiver_status['GroupUser']['user_type']=='M'){
                $this->GroupUser->query("UPDATE `group_users` SET `user_type` ='O', `can_post_topic` = '1' WHERE `group_id` = '".$group_id."' AND `user_id` = '".$receiver_id."'");
                
                $con_group_dtls = "Group.id = '".$group_id."'";
                $group_dtls = $this->Group->find("first",array('conditions'=>$con_group_dtls,'fields'=>array('Group.id','Group.group_title','Group.group_owners','Group.group_type'))); 
                $arr_group_owners= explode(',', $group_dtls['Group']['group_owners']); 
                
                if(!in_array($receiver_id, $arr_group_owners)){
                    array_push($arr_group_owners, $receiver_id);
                }
                
                $str_group_owners= implode(',', $arr_group_owners);
                
                $this->data['Group']['id'] = $group_id; 
                $this->data['Group']['group_owners'] = $str_group_owners;  
                if($this->Group->save($this->data['Group'])){
                        
                        $message= 'You have been assigned as the Owner of the Group: '.$group_dtls['Group']['group_title'];
                        $this->data['Notification']['sender_id'] = $sender_id;
                        $this->data['Notification']['type'] = 'G';
                        $this->data['Notification']['group_id'] = $group_id;
                        $this->data['Notification']['message'] = $message;                     
                        $this->data['Notification']['receiver_id'] = $receiver_id;
                        $this->data['Notification']['group_type'] = $group_dtls['Group']['group_type'];
                        $this->data['Notification']['sender_type'] = 'GO';
                        $this->data['Notification']['receiver_type'] = 'GO';
                        $this->data['Notification']['is_read'] = 0;
                        $this->data['Notification']['is_receiver_accepted'] = 2;
                        $this->data['Notification']['is_reversed_notification'] = 0;
                        $this->data['Notification']['status'] = 1;
                        $this->Notification->create();
                        $this->Notification->save($this->data['Notification']);
                }
                
                $response['is_error'] = 0;  
                $response['success_msg'] = 'You have added a new owner';
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'The user is already a Owner';   
            }
        }
        else{
            
            $response['is_error'] = 1;
            $response['err_msg'] = 'The user does not belong to the Group';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function remove_owner(){
       
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        
        $sender_id    = $obj->{'sender_id'};            
        $group_id    = $obj->{'group_id'};
        $receiver_id    = $obj->{'receiver_id'};
        $group_type    = $obj->{'group_type'};    
        
        $con_chk_receiver_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$receiver_id."' AND GroupUser.status = '1'";
        $arr_receiver_status = $this->GroupUser->find('first',array('conditions'=>$con_chk_receiver_status));
        
        if(!empty($arr_receiver_status)){
            if($arr_receiver_status['GroupUser']['user_type']=='O'){

                if($group_type == 'B')
                {
                    $this->GroupUser->query("UPDATE `group_users` SET `user_type` ='M', `can_post_topic` ='0' WHERE `group_id` = '".$group_id."' AND `user_id` = '".$receiver_id."'");
                }
                else
                {
                    $this->GroupUser->query("UPDATE `group_users` SET `user_type` ='M' WHERE `group_id` = '".$group_id."' AND `user_id` = '".$receiver_id."'");
                }
                
                $con_group_dtls = "Group.id = '".$group_id."'";
                $group_dtls = $this->Group->find("first",array('conditions'=>$con_group_dtls,'fields'=>array('Group.id','Group.group_owners','Group.group_title','Group.group_type'))); 
                $arr_group_owners= explode(',', $group_dtls['Group']['group_owners']); 
                
                if(in_array($receiver_id, $arr_group_owners)){
                    unset($arr_group_owners[array_search($receiver_id,$arr_group_owners)]);
                }
                
                $str_group_owners= implode(',', $arr_group_owners);
                
                $this->data['Group']['id'] = $group_id; 
                $this->data['Group']['group_owners'] = $str_group_owners;  
                if($this->Group->save($this->data['Group'])){
                        
                        $message= 'You  are not now the Owner of the Group: '.$group_dtls['Group']['group_title'];
                        $this->data['Notification']['sender_id'] = $sender_id;
                        $this->data['Notification']['type'] = 'G';
                        $this->data['Notification']['group_id'] = $group_id;
                        $this->data['Notification']['message'] = $message;                     
                        $this->data['Notification']['receiver_id'] = $receiver_id;
                        $this->data['Notification']['group_type'] = $group_dtls['Group']['group_type'];
                        $this->data['Notification']['sender_type'] = 'GO';
                        $this->data['Notification']['receiver_type'] = 'GM';
                        $this->data['Notification']['is_read'] = 0;
                        $this->data['Notification']['is_receiver_accepted'] = 2;
                        $this->data['Notification']['is_reversed_notification'] = 0;
                        $this->data['Notification']['status'] = 1;
                        $this->Notification->create();
                        $this->Notification->save($this->data['Notification']);
        
                }
                $response['is_error'] = 0;  
                $response['success_msg'] = 'You have removed an owner';
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'The user is not an Owner';  
            }
        }
        else{
            
            $response['is_error'] = 1;
            $response['err_msg'] = 'The user does not belong to the Group';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }

    function remove_member(){
       
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        
        $sender_id    = $obj->{'sender_id'};            
        $group_id    = $obj->{'group_id'};
        $receiver_id    = $obj->{'receiver_id'};
        
        $con_group_dtls = "Group.id = '".$group_id."'";
        $group_dtls = $this->Group->find("first",array('conditions'=>$con_group_dtls));


        $con_group_dtls = "GroupUser.user_type = 'M' AND GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$receiver_id."'";
        $group_user_dtls = $this->GroupUser->find("first",array('conditions'=>$con_group_dtls,'fields'=>array('GroupUser.id')));

        if($group_user_dtls['GroupUser']['id']!= " ")
        {
            $this->GroupUser->id = $group_user_dtls['GroupUser']['id'];
            if($this->GroupUser->delete())
            {
               $con_user_dtls = "User.id = '".$receiver_id."'";
              
                $user_dtls = $this->User->find("first",array('conditions'=>$con_user_dtls)); 
                
                $arr_groups= explode(',', $user_dtls['User']['groups']); 

                
                
                if(in_array($group_id, $arr_groups))
                {

                    unset($arr_groups[array_search($group_id,$arr_groups)]);
                    
                    $str_groups= implode(',', $arr_groups);
                
                    $this->data['User']['id'] = $receiver_id; 
                    $this->data['User']['groups'] = $str_groups;  
                    if($this->User->save($this->data['User']))
                    {
                         $message= 'You  are no more Member of the Group: '.$group_dtls['Group']['group_title'];
                    $this->data['Notification']['sender_id'] = $sender_id;
                    $this->data['Notification']['type'] = 'G';
                    $this->data['Notification']['group_id'] = $group_id;
                    $this->data['Notification']['message'] = $message;               
                    $this->data['Notification']['receiver_id'] = $receiver_id;
                    $this->data['Notification']['group_type'] = $group_dtls['Group']['group_type'];
                    $this->data['Notification']['sender_type'] = 'GO';
                    $this->data['Notification']['receiver_type'] = 'NGM';
                    $this->data['Notification']['is_read'] = 0;
                    $this->data['Notification']['is_receiver_accepted'] = 2;
                    $this->data['Notification']['is_reversed_notification'] = 0;
                    $this->data['Notification']['status'] = 1;
                    $this->Notification->create();
                    $this->Notification->save($this->data['Notification']);                    
                    $response['is_error'] = 0;  
                    $response['success_msg'] = 'You have removed a member';
                    }
                }
            }
        }
        else
        {
            $response['is_error'] = 1;
            $response['err_msg'] = 'This user is not a member';
        }
            header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    /*function search_friend_list_invitation(){
       
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            $response['is_error'] = 0;


            
            $group_id    = $obj->{'group_id'};
            $user_id    = $obj->{'user_id'};
            $search_text    = $obj->{'search_text'};
            $str_selected_users    = $obj->{'str_selected_users'};

           
            $condition_friend_list = "Friendlist.sender_id = '".$user_id."' AND Friendlist.is_blocked = '0' AND Friendlist.friend_name LIKE '%".$search_text."%'";
            $this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'receiver_id','fields'=>'User.id,User.fname,User.lname,User.image'))),false);
            $search_friend_list = $this->Friendlist->find('all',array('conditions'=>$condition_friend_list));              
            $arr_not_group_friends=array();
            $arr_notified_users=array();

            if(count($search_friend_list) > 0)
            {
            
                ############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected starts  ############
            
                    $con_is_req_sent = "Notification.status = '1' AND  Notification.type = 'G' AND Notification.sender_id = '".$user_id."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' AND Notification.group_id = '".$group_id."'";
                    $arr_req_sent_users = $this->Notification->find('all',array('conditions'=>$con_is_req_sent, 'fields'=>array('Notification.receiver_id'))); 
                    if(!empty($arr_req_sent_users)){    
                        foreach($arr_req_sent_users as $key3 => $val3){ 
                                array_push($arr_notified_users, $val3['Notification']['receiver_id']);      
                        }
                    }
                    
                    
                  ###########   Fetch the users to whom the friend request is already sent, but not accepted/ rejected ends  ###########
                  
                  
                  #########   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet starts  ############
                    foreach($search_friend_list as $key => $val){
                        
                            $con_is_member = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$val['Friendlist']['receiver_id']."' AND GroupUser.status = '1'";
                            $count_is_member = $this->GroupUser->find('count',array('conditions'=>$con_is_member));
                            if($count_is_member == 0)           // Remove the friend who does not belong to selected group
                            {
                                if(!empty($arr_notified_users)){
                                    if(!in_array($val['Friendlist']['receiver_id'], $arr_notified_users)){  // Remove the friend to whom the notification is sent already 
                                        array_push($arr_not_group_friends, $val);
                                    }
                                }
                                else{
                                    array_push($arr_not_group_friends, $val);
                                }
                            }
                            
                    }   
                #########   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet ends  #############

                if($str_selected_users!=''){
                    $arr_selected_users= explode(',', $str_selected_users);
                    $arr_search_result= array();
                    
                    foreach($arr_not_group_friends as $key1 => $val1){  
                        if(!in_array($val1['Friendlist']['receiver_id'], $arr_selected_users)){
                            array_push($arr_search_result, $val1);      
                        }
                    }
                }
                else{
                    $arr_search_result= $arr_not_group_friends;
                }
            
                $friend_search_list = array(); 
                if(!empty($arr_search_result)){
                    foreach($arr_search_result as $valu){
                        $list['friend_id'] = $valu['User']['id'];
                        $list['friend_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'] ;
                        if($valu['User']['image']!='')
                        {
                        $list['friend_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                        }
                        else
                        {
                         $list['friend_image'] =  $base_url.'images/no_profile_img.jpg';
                        }
                        array_push($friend_search_list,$list);
                    }
                        $response['Friendlist']= $friend_search_list; 
                }
                else{
                        $response['Friendlist'] = array();
                }
            }
            else
            {
                
                $response['Friendlist'] = array();
                
            }


        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }*/
    
    
    function search_friend_list_invitation(){
       
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            $response['is_error'] = 0;


            
            $group_id    = $obj->{'group_id'};
            $user_id    = $obj->{'user_id'};
            $search_text    = $obj->{'search_text'};
            $str_selected_users    = $obj->{'str_selected_users'};

           
            $condition_friend_list = "Friendlist.sender_id = '".$user_id."' AND Friendlist.is_blocked = '0' AND Friendlist.friend_name LIKE '%".$search_text."%'";
            $this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'receiver_id','fields'=>'User.id,User.fname,User.lname,User.image'))),false);
            $search_friend_list = $this->Friendlist->find('all',array('conditions'=>$condition_friend_list));              
            $arr_not_group_friends=array();

            if(count($search_friend_list) > 0)
            {
            
                  #########   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet starts  ############
                    foreach($search_friend_list as $key => $val){
                        
                            $con_is_member = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$val['Friendlist']['receiver_id']."' AND GroupUser.status = '1'";
                            $count_is_member = $this->GroupUser->find('count',array('conditions'=>$con_is_member));
                            if($count_is_member == 0)           // Remove the friend who does not belong to selected group
                            {
                                array_push($arr_not_group_friends, $val);
                            }
                            
                    }   
                #########   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet ends  #############

                if($str_selected_users!=''){
                    $arr_selected_users= explode(',', $str_selected_users);
                    $arr_search_result= array();
                    
                    foreach($arr_not_group_friends as $key1 => $val1){  
                        if(!in_array($val1['Friendlist']['receiver_id'], $arr_selected_users)){
                            array_push($arr_search_result, $val1);      
                        }
                    }
                }
                else{
                    $arr_search_result= $arr_not_group_friends;
                }
            
                $friend_search_list = array(); 
                if(!empty($arr_search_result)){
                    foreach($arr_search_result as $valu){
                        $list['friend_id'] = $valu['User']['id'];
                        $list['friend_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'] ;
                        if($valu['User']['image']!='')
                        {
                        $list['friend_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                        }
                        else
                        {
                         $list['friend_image'] =  $base_url.'images/no_profile_img.jpg';
                        }
                        array_push($friend_search_list,$list);
                    }
                        $response['Friendlist']= $friend_search_list; 
                }
                else{
                        $response['Friendlist'] = array();
                }
            }
            else
            {
                
                $response['Friendlist'] = array();
                
            }


        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    /*function search_user_list_invitation(){
       
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;


        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $search_text    = $obj->{'search_text'};
        $str_selected_users    = $obj->{'str_selected_users'};
        $arr_selected_users= explode(',', $str_selected_users);
        
        

        $arr_notified_users=array();
            
        $condition_user_list = "User.status = '1' AND (User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND (!find_in_set('".$group_id."',User.`groups`)) AND ( User.`groups` IS NOT NULL) AND User.id != '".$user_id."'";
        $search_user_list = $this->User->find('all',array('conditions'=>$condition_user_list,'order' => array('User.fname ASC', 'User.lname ASC'))); 
        
        ###############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected starts  #################
            
        $con_is_req_sent = "Notification.status = '1' AND  Notification.type = 'G' AND Notification.sender_id = '".$user_id."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' AND Notification.group_id = '".$group_id."'";
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
                    if($count_is_member == 0){      // Those who are not the friend
                    
                    if(!empty($arr_notified_users)){
                        if(!in_array($val['User']['id'], $arr_notified_users)){     //Those who are not being notified 
                            if(!empty($arr_selected_users)){
                                if(!in_array($val['User']['id'], $arr_selected_users)){     //Those who are not selected 
                                    array_push($arr_not_group_users, $val); 
                                }
                            }
                            else{
                                array_push($arr_not_group_users, $val); 
                            }
                        }
                    }
                    else{
                            if(!empty($arr_selected_users)){        //Those who are not selected
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
                //$this->set('arr_search_result',$arr_not_group_users); 
            }
            

            
            $user_search_list = array(); 
            if(!empty($arr_not_group_users)){
                foreach($arr_not_group_users as $valu){
                    $list['user_id'] = $valu['User']['id'];
                    $list['user_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'];
                    if($valu['User']['image']!='')
                    {
                        $list['user_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                    }
                    else
                    {
                        $list['user_image'] =  $base_url.'images/no_profile_img.jpg';
                    }
                    array_push($user_search_list,$list);
                }
                    $response['Userlist']= $user_search_list; 
            }
            else{
                    $response['Userlist'] = array();
            }

        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }*/
    
    
    function search_user_list_invitation(){
       
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;


        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $search_text    = $obj->{'search_text'};
        $str_selected_users    = $obj->{'str_selected_users'};
        $arr_selected_users= explode(',', $str_selected_users);
        
        /*$group_id    = 56;
        $user_id    = 138;
        $search_text    = 'tam';
        $str_selected_users    = '';
        $arr_selected_users= explode(',', $str_selected_users);*/

            
        $condition_user_list = "User.status = '1' AND (User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND (!find_in_set('".$group_id."',User.`groups`)) AND ( User.`groups` IS NOT NULL) AND User.id != '".$user_id."'";
        $search_user_list = $this->User->find('all',array('conditions'=>$condition_user_list,'order' => array('User.fname ASC', 'User.lname ASC'))); 
        
            $arr_not_group_users=array();

            if(count($search_user_list) > 0)
            {
                foreach($search_user_list as $key => $val)
                {
                    
                    $con_is_member = "((Friendlist.sender_id = '".$user_id."' AND Friendlist.receiver_id = '".$val['User']['id']."') OR (Friendlist.receiver_id = '".$user_id."' AND Friendlist.sender_id = '".$val['User']['id']."'))";
                    $count_is_member = $this->Friendlist->find('count',array('conditions'=>$con_is_member));
                    if($count_is_member == 0){      // Those who are not the friend
                    
                    if(!empty($arr_selected_users)){        //Those who are not selected
                        if(!in_array($val['User']['id'], $arr_selected_users)){
                            array_push($arr_not_group_users, $val); 
                        }
                    }
                    else{
                        array_push($arr_not_group_users, $val); 
                    }
                        
                    }
                }
                //$this->set('arr_search_result',$arr_not_group_users); 
            }
            /*else
            { 
                $this->set('arr_search_result',$arr_not_group_users);     
            }*/

            
            $user_search_list = array(); 
            if(!empty($arr_not_group_users)){
                foreach($arr_not_group_users as $valu){
                    $list['user_id'] = $valu['User']['id'];
                    $list['user_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'];
                    if($valu['User']['image']!='')
                    {
                        $list['user_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                    }
                    else
                    {
                        $list['user_image'] =  $base_url.'images/no_profile_img.jpg';
                    }
                    array_push($user_search_list,$list);
                }
                    $response['Userlist']= $user_search_list; 
            }
            else{
                    $response['Userlist'] = array();
            }

        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    /*function submit_invitation(){
      
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;
        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $mode    = $obj->{'mode'};
        $sender_type    = $obj->{'sender_type'};
        $group_type= $obj->{'group_type'};
        $str_users= $obj->{'str_users'};
        
       

        
            
            
        if(isset($mode) && $mode =='invite_friends'){
                
                $arr_users= explode(',', $str_users);
                
                for($i=0; $i<count($arr_users); $i++){
                
                    $this->data['Notification']['type'] = 'G';
                    $this->data['Notification']['group_id'] = $group_id;
                    $this->data['Notification']['group_type'] = $group_type;
                    $this->data['Notification']['sender_id'] = $user_id;
                    $this->data['Notification']['sender_type'] = $sender_type;
                    $this->data['Notification']['request_mode'] = 'public';
                    $this->data['Notification']['receiver_id'] = $arr_users[$i];
                    $this->data['Notification']['receiver_type'] = 'NGM';
                    $this->data['Notification']['is_read'] = '0';
                    $this->data['Notification']['is_receiver_accepted'] = '0';
                    $this->data['Notification']['is_reversed_notification'] = '0';
                    $this->data['Notification']['status'] = 1;
                    
                    $this->Notification->create();
                    //$this->Notification->save($this->data['Notification']);
                    if($this->Notification->save($this->data['Notification'])){
                
                          $notification_id = $this->Notification->getLastInsertId();  

                          
                          $page="Group invitation";
                          $this->send_notification_group_invitation($user_id, $arr_users[$i], $group_id, $mode,$page);
                        

                      
                    }
                }
                $response['success_msg'] = 'Invitation sent successfully to friends';
        }   
        else if(isset($mode) && $mode=='invite_users'){
                
                $arr_users= explode(',', $str_users);
                
                for($i=0; $i<count($arr_users); $i++){
                
                    $this->data['Notification']['type'] = 'G';
                    $this->data['Notification']['group_id'] = $group_id;
                    $this->data['Notification']['group_type'] = $group_type;
                    $this->data['Notification']['sender_id'] = $user_id;
                    $this->data['Notification']['sender_type'] = $sender_type;
                    $this->data['Notification']['request_mode'] = 'public';
                    $this->data['Notification']['receiver_id'] = $arr_users[$i];
                    $this->data['Notification']['receiver_type'] = 'NGM';
                    $this->data['Notification']['is_read'] = '0';
                    $this->data['Notification']['is_receiver_accepted'] = '0';
                    $this->data['Notification']['is_reversed_notification'] = '0';
                    $this->data['Notification']['status'] = 1;
                    
                    $this->Notification->create();
                    //$this->Notification->save($this->data['Notification']);
                    if($this->Notification->save($this->data['Notification']))
                    {
                      $notification_id = $this->Notification->getLastInsertId();  
                      $this->notification_email($notification_id);
                      
                       $page="Group invitation";
                       $this->send_notification_group_invitation($user_id, $arr_users[$i], $group_id, $mode,$page);

                    }
                }
                $response['success_msg'] = 'Invitation sent successfully to users';
        }       
        else if(isset($mode) && $mode=='invite_emails'){
            
                $sitesettings = $this->getSiteSettings();
                
                $arr_emails= explode(',', $str_users);
                
                $arr_site_users = array();
                $arr_notified_users = array();
                
                for($i=0; $i<count($arr_emails); $i++){
                
                     $cond_email_check = "User.email = '".$arr_emails[$i]."'";   // Check whether the email exists
                     $email_check = $this->User->find('first',array('conditions'=>$cond_email_check));
                     
                     if(!empty($email_check)){
                            
                        if($email_check['User']['status']=='1'){
                            array_push($arr_site_users,$arr_emails[$i]);        //Already site users
                        }
                        else{
                            $cond_notification_check = "Notification.sender_id = '".$user_id."' AND Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$email_check['User']['id']."' AND Notification.type = 'G'";   // Check notification already exists for that group from the same owner 
                            $notification_arr = $this->Notification->find('first',array('conditions'=>$cond_notification_check));   
                            
                            if(!empty($notification_arr)) {
                            
                                array_push($arr_notified_users,$arr_emails[$i]);        //Already site users

                                $con_noti_detail = "Notification.id = '".$notification_arr['Notification']['id']."' AND Notification.status = '1'";
                                    $this->Notification->bindModel(
                                      array('belongsTo'=>array(
                                          'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                          'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                                          
                                          'Group'=>array('foreignKey'=>'group_id')
                                        )
                                        ));
                                    $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                                    $email = $arr_emails[$i];
                                    $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                                    $group_name = $noti_detail['Group']['group_title'];
                                    if($noti_detail['Group']['group_type'] == 'B')
                                    {
                                        $group_type = 'Business';
                                            
                                    }
                                    else if($noti_detail['Group']['group_type'] == 'F')
                                    {
                                        $group_type = 'Private';
                                    }
               
                                    
                                    $admin_sender_email = $sitesettings['site_email']['value'];
                                    $site_url = $sitesettings['site_url']['value'];
                                    //$sender_name = $sitesettings['email_sender_name']['value'];
                
                                    $condition = "EmailTemplate.id = '2'";
                                    $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                                   
                                    $to = $email;
                
                                    $user_subject = $mailDataRS['EmailTemplate']['subject'];
                                    $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                    
            
            
                                 
                                    $user_body = $mailDataRS['EmailTemplate']['content'];
                                    $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                                    $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                                    $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                                    $user_body = str_replace('[GROUP TYPE]', $group_type, $user_body);
                                    $user_body = str_replace('[SITEURL]', $site_url, $user_body);   
                
                                    $user_message = stripslashes($user_body);
                                    
                                    $string = '';
                                    $filepath = '';
                                    $filename = '';
                                    $sendCopyTo = '';
                           
                           
                                    $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                            }
                            else{
                             
                                $this->data['Notification']['sender_id'] = $user_id;
                                $this->data['Notification']['type'] = 'G';
                                $this->data['Notification']['group_id'] = $group_id;
                                $this->data['Notification']['group_type'] = $group_type;
                                $this->data['Notification']['sender_type'] = $sender_type;
                                $this->data['Notification']['request_mode'] = 'public';
                                $this->data['Notification']['receiver_id'] = $email_check['User']['id'];
                                $this->data['Notification']['receiver_type'] = 'NGM';
                                $this->data['Notification']['is_read'] = 0;
                                $this->data['Notification']['is_receiver_accepted'] = 0;
                                $this->data['Notification']['is_reversed_notification'] = 0;
                                $this->data['Notification']['status'] = 1;
                                $this->Notification->create();
                                
                                if($this->Notification->save($this->data['Notification'])){     //Send the notification to the dummy user
                                    
                                    $last_noti_insert_id = $this->Notification->getLastInsertId();
                                    $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                                    $this->Notification->bindModel(
                                      array('belongsTo'=>array(
                                          'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                          'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                                          
                                          'Group'=>array('foreignKey'=>'group_id')
                                        )
                                        ));
                                    $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                                    $email = $arr_emails[$i];
                                    $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                                    $group_name = $noti_detail['Group']['group_title'];
                                    if($noti_detail['Group']['group_type'] == 'B')
                                    {
                                        $group_type = 'Business';
                                            
                                    }
                                    else if($noti_detail['Group']['group_type'] == 'F')
                                    {
                                        $group_type = 'Private';
                                    }
                
                                    
                                    $admin_sender_email = $sitesettings['site_email']['value'];
                                    $site_url = $sitesettings['site_url']['value'];
                                    //$sender_name = $sitesettings['email_sender_name']['value'];
                
                                    $condition = "EmailTemplate.id = '2'";
                                    $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                                   
                                    $to = $email;
                
                                    $user_subject = $mailDataRS['EmailTemplate']['subject'];
                                    $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                    
            
            
                                 
                                    $user_body = $mailDataRS['EmailTemplate']['content'];
                                    $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                                    $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                                    $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                                    $user_body = str_replace('[GROUP TYPE]', $group_type, $user_body);
                                    $user_body = str_replace('[SITEURL]', $site_url, $user_body);   
                
                                    $user_message = stripslashes($user_body);
                                    
                                    $string = '';
                                    $filepath = '';
                                    $filename = '';
                                    $sendCopyTo = '';
                           
                           
                                    $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                                }
                            }
                            
                        }
                     }
                     else{
                            ###################    User creation starts    ######################
                            $this->data['User']['email'] = trim(preg_replace('/\s+/','',$arr_emails[$i]));
                            $this->data['User']['status'] = 0;
                            $this->data['User']['is_invite'] = 1;
                            $this->data['User']['groups'] = '';
                                        
                            $this->User->create();
                            $this->User->save($this->data['User']);         // Creates a dummy User
                            $last_insert_id = $this->User->getLastInsertId();
                            
                            ####################   User creation ends    ########################
                            
                            ####################    Sending Notification starts     ####################
                            
                            $this->data['Notification']['sender_id'] = $user_id;
                            $this->data['Notification']['type'] = 'G';
                            $this->data['Notification']['group_id'] = $group_id;
                            $this->data['Notification']['group_type'] = $group_type;
                            $this->data['Notification']['sender_type'] = $sender_type;
                            $this->data['Notification']['request_mode'] = 'public';
                            $this->data['Notification']['receiver_id'] = $last_insert_id;
                            $this->data['Notification']['receiver_type'] = 'NGM';
                            $this->data['Notification']['is_read'] = 0;
                            $this->data['Notification']['is_receiver_accepted'] = 0;
                            $this->data['Notification']['is_reversed_notification'] = 0;
                            $this->data['Notification']['status'] = 1;
                            $this->Notification->create();
                            
                            if($this->Notification->save($this->data['Notification'])){     
                            
                                $last_noti_insert_id = $this->Notification->getLastInsertId();
                                $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                                $this->Notification->bindModel(
                                  array('belongsTo'=>array(
                                      'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                      'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                                      
                                      'Group'=>array('foreignKey'=>'group_id')
                                    )
                                    ));
                                $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                                $email = $arr_emails[$i];
                                $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                                $group_name = $noti_detail['Group']['group_title'];
                                if($noti_detail['Group']['group_type'] == 'B')
                                {
                                    $group_type = 'Business';
                                        
                                }
                                else if($noti_detail['Group']['group_type'] == 'F')
                                {
                                    $group_type = 'Private';
                                }
            
                                
                                $admin_sender_email = $sitesettings['site_email']['value'];
                                $site_url = $sitesettings['site_url']['value'];
                                //$sender_name = $sitesettings['email_sender_name']['value'];
            
                                $condition = "EmailTemplate.id = '2'";
                                $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                               
                                $to = $email;
            
                                $user_subject = $mailDataRS['EmailTemplate']['subject'];
                                $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                
        
        
                             
                                $user_body = $mailDataRS['EmailTemplate']['content'];
                                $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                                $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                                $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                                $user_body = str_replace('[GROUP TYPE]', $group_type, $user_body);
                                $user_body = str_replace('[SITEURL]', $site_url, $user_body);   
            
                                $user_message = stripslashes($user_body);
                                
                                $string = '';
                                $filepath = '';
                                $filename = '';
                                $sendCopyTo = '';
                       
                       
                                $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                        }
                            
                            ####################    Sending Notification ends        #####################
                     }
                    
                }

                if(!empty($arr_site_users) || !empty($arr_notified_users)){
                    
                    if(!empty($arr_site_users)){
                        $str_site_users= implode(',', $arr_site_users);
                        $response['is_error'] = 1;
                        $response['err_msg'] = "Existing active users: ".$str_site_users;   
                    }
                    if(!empty($arr_notified_users)){
                        $str_notified_users= implode(',', $arr_notified_users);
                        $response['is_error'] = 1;
                        $response['err_msg'].= "Existing notified users: ".$str_notified_users;
                    }
                }
                else{
                    $response['success_msg'] = 'Invitation sent successfully to Non Users.';
                }
            }
            
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }*/
    
    
    
    function submit_invitation(){
      
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;
        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $mode    = $obj->{'mode'};
        $sender_type    = $obj->{'sender_type'};
        $group_type= $obj->{'group_type'};
        $str_users= $obj->{'str_users'};
        
        /*$group_id    = '59';
        $user_id    = '6';
        $mode    = 'invite_friends';
        $sender_type    = 'GO';
        $group_type = 'F';
        $str_users= '4';*/

        
            
            
        if(isset($mode) && $mode =='invite_friends'){
                
                $arr_users= explode(',', $str_users);
                
                for($i=0; $i<count($arr_users); $i++){
                
                    $cond_chk_notification_exist = "Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$arr_users[$i]."' AND Notification.type = 'G' AND Notification.group_type = '".$group_type."'AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";   // Check notification already exists for that group from the any owner 
                    $notification_arr_exists = $this->Notification->find('first',array('conditions'=>$cond_chk_notification_exist));    
                    if(!empty($notification_arr_exists)){
                         $this->Notification->query("DELETE FROM notifications WHERE (`id` = '".$notification_arr_exists['Notification']['id']."')");
                    }
                
                    $this->data['Notification']['type'] = 'G';
                    $this->data['Notification']['group_id'] = $group_id;
                    $this->data['Notification']['group_type'] = $group_type;
                    $this->data['Notification']['sender_id'] = $user_id;
                    $this->data['Notification']['sender_type'] = $sender_type;
                    $this->data['Notification']['request_mode'] = 'public';
                    $this->data['Notification']['receiver_id'] = $arr_users[$i];
                    $this->data['Notification']['receiver_type'] = 'NGM';
                    $this->data['Notification']['is_read'] = '0';
                    $this->data['Notification']['is_receiver_accepted'] = '0';
                    $this->data['Notification']['is_reversed_notification'] = '0';
                    $this->data['Notification']['status'] = 1;
                    
                    $this->Notification->create();
                    //$this->Notification->save($this->data['Notification']);
                    if($this->Notification->save($this->data['Notification'])){
                
                          $notification_id = $this->Notification->getLastInsertId();  
                          /*$this->notification_email($notification_id);*/
                          
                          $page="Group invitation";
                          $this->send_notification_group_invitation($user_id, $arr_users[$i], $group_id, $mode,$page);
                        

                      
                    }
                }
                $response['success_msg'] = 'Invitation sent successfully to friends';
        }   
        else if(isset($mode) && $mode=='invite_users'){
                
                $arr_users= explode(',', $str_users);
                
                for($i=0; $i<count($arr_users); $i++){
                
                    $cond_chk_notification_exist = "Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$arr_users[$i]."' AND Notification.type = 'G' AND Notification.group_type = '".$group_type."'AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";   // Check notification already exists for that group from the any owner 
                    $notification_arr_exists = $this->Notification->find('first',array('conditions'=>$cond_chk_notification_exist));    
                    if(!empty($notification_arr_exists)){
                         $this->Notification->query("DELETE FROM `notifications` WHERE (`id` = '".$notification_arr_exists['Notification']['id']."')");
                    }
                
                    $this->data['Notification']['type'] = 'G';
                    $this->data['Notification']['group_id'] = $group_id;
                    $this->data['Notification']['group_type'] = $group_type;
                    $this->data['Notification']['sender_id'] = $user_id;
                    $this->data['Notification']['sender_type'] = $sender_type;
                    $this->data['Notification']['request_mode'] = 'public';
                    $this->data['Notification']['receiver_id'] = $arr_users[$i];
                    $this->data['Notification']['receiver_type'] = 'NGM';
                    $this->data['Notification']['is_read'] = '0';
                    $this->data['Notification']['is_receiver_accepted'] = '0';
                    $this->data['Notification']['is_reversed_notification'] = '0';
                    $this->data['Notification']['status'] = 1;
                    
                    $this->Notification->create();
                    //$this->Notification->save($this->data['Notification']);
                    if($this->Notification->save($this->data['Notification']))
                    {
                      $notification_id = $this->Notification->getLastInsertId();  
                      $this->notification_email($notification_id);
                      
                       $page="Group invitation";
                       $this->send_notification_group_invitation($user_id, $arr_users[$i], $group_id, $mode,$page);

                    }
                }
                $response['success_msg'] = 'Invitation sent successfully to users';
        }       
        else if(isset($mode) && $mode=='invite_emails'){
            
                $sitesettings = $this->getSiteSettings();
                
                $arr_emails= explode(',', $str_users);
                
                $arr_site_users = array();
                
                for($i=0; $i<count($arr_emails); $i++){
                
                     $cond_email_check = "User.email = '".$arr_emails[$i]."'";   // Check whether the email exists
                     $email_check = $this->User->find('first',array('conditions'=>$cond_email_check));
                     
                     if(!empty($email_check)){
                            
                        if($email_check['User']['status']=='1'){
                            array_push($arr_site_users,$arr_emails[$i]);        //Already site users
                        }
                        else{
                            $cond_chk_notification_exist = "Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$email_check['User']['id']."' AND Notification.type = 'G' AND Notification.group_type = '".$group_type."'AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";   // Check notification already exists for that group from the any owner 
                            $notification_arr_exists = $this->Notification->find('first',array('conditions'=>$cond_chk_notification_exist));    
                            if(!empty($notification_arr_exists)){
                                 $this->Notification->query("DELETE FROM `notifications` WHERE (`id` = '".$notification_arr_exists['Notification']['id']."')");
                            }  
                            
                             
                                $this->data['Notification']['sender_id'] = $user_id;
                                $this->data['Notification']['type'] = 'G';
                                $this->data['Notification']['group_id'] = $group_id;
                                $this->data['Notification']['group_type'] = $group_type;
                                $this->data['Notification']['sender_type'] = $sender_type;
                                $this->data['Notification']['request_mode'] = 'public';
                                $this->data['Notification']['receiver_id'] = $email_check['User']['id'];
                                $this->data['Notification']['receiver_type'] = 'NGM';
                                $this->data['Notification']['is_read'] = 0;
                                $this->data['Notification']['is_receiver_accepted'] = 0;
                                $this->data['Notification']['is_reversed_notification'] = 0;
                                $this->data['Notification']['status'] = 1;
                                $this->Notification->create();
                                
                                if($this->Notification->save($this->data['Notification'])){     //Send the notification to the dummy user
                                    
                                    $last_noti_insert_id = $this->Notification->getLastInsertId();
                                    $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                                    $this->Notification->bindModel(
                                      array('belongsTo'=>array(
                                          'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                          'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                                          
                                          'Group'=>array('foreignKey'=>'group_id')
                                        )
                                        ));
                                    $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                                    $email = $arr_emails[$i];
                                    $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                                    $group_name = $noti_detail['Group']['group_title'];
                                    if($noti_detail['Group']['group_type'] == 'B')
                                    {
                                        $grouptype = 'Business';
                                            
                                    }
                                    else if($noti_detail['Group']['group_type'] == 'F')
                                    {
                                        $grouptype = 'Private';
                                    }
                
                                    
                                    $admin_sender_email = $sitesettings['site_email']['value'];
                                    $site_url = $sitesettings['site_url']['value'];
                                    //$sender_name = $sitesettings['email_sender_name']['value'];
                
                                    $condition = "EmailTemplate.id = '2'";
                                    $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                                   
                                    $to = $email;
                
                                    $user_subject = $mailDataRS['EmailTemplate']['subject'];
                                    $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                    
            
            
                                 
                                    $user_body = $mailDataRS['EmailTemplate']['content'];
                                    $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                                    $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                                    $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                                    $user_body = str_replace('[GROUP TYPE]', $grouptype, $user_body);
                                    $user_body = str_replace('[SITEURL]', $site_url, $user_body);   
                
                                    $user_message = stripslashes($user_body);
                                    
                                    $string = '';
                                    $filepath = '';
                                    $filename = '';
                                    $sendCopyTo = '';
                           
                           
                                    $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                                }
                            
                        }
                     }
                     else{
                            ###################    User creation starts    ######################
                            $this->data['User']['email'] = trim(preg_replace('/\s+/','',$arr_emails[$i]));
                            $this->data['User']['status'] = 0;
                            $this->data['User']['is_invite'] = 1;
                            $this->data['User']['groups'] = '';
                                        
                            $this->User->create();
                            $this->User->save($this->data['User']);         // Creates a dummy User
                            $last_insert_id = $this->User->getLastInsertId();
                            
                            ####################   User creation ends    ########################
                            
                            ####################    Sending Notification starts     ####################
                            
                            $this->data['Notification']['sender_id'] = $user_id;
                            $this->data['Notification']['type'] = 'G';
                            $this->data['Notification']['group_id'] = $group_id;
                            $this->data['Notification']['group_type'] = $group_type;
                            $this->data['Notification']['sender_type'] = $sender_type;
                            $this->data['Notification']['request_mode'] = 'public';
                            $this->data['Notification']['receiver_id'] = $last_insert_id;
                            $this->data['Notification']['receiver_type'] = 'NGM';
                            $this->data['Notification']['is_read'] = 0;
                            $this->data['Notification']['is_receiver_accepted'] = 0;
                            $this->data['Notification']['is_reversed_notification'] = 0;
                            $this->data['Notification']['status'] = 1;
                            $this->Notification->create();
                            
                            if($this->Notification->save($this->data['Notification'])){     
                            
                                $last_noti_insert_id = $this->Notification->getLastInsertId();
                                $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                                $this->Notification->bindModel(
                                  array('belongsTo'=>array(
                                      'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                      'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                                      
                                      'Group'=>array('foreignKey'=>'group_id')
                                    )
                                    ));
                                $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                                $email = $arr_emails[$i];
                                $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                                $group_name = $noti_detail['Group']['group_title'];
                                if($noti_detail['Group']['group_type'] == 'B')
                                {
                                    $grouptype = 'Business';
                                        
                                }
                                else if($noti_detail['Group']['group_type'] == 'F')
                                {
                                    $grouptype = 'Private';
                                }
            
                                
                                $admin_sender_email = $sitesettings['site_email']['value'];
                                $site_url = $sitesettings['site_url']['value'];
                                //$sender_name = $sitesettings['email_sender_name']['value'];
            
                                $condition = "EmailTemplate.id = '2'";
                                $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                               
                                $to = $email;
            
                                $user_subject = $mailDataRS['EmailTemplate']['subject'];
                                $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                
        
        
                             
                                $user_body = $mailDataRS['EmailTemplate']['content'];
                                $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                                $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                                $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                                $user_body = str_replace('[GROUP TYPE]', $grouptype, $user_body);
                                $user_body = str_replace('[SITEURL]', $site_url, $user_body);   
            
                                $user_message = stripslashes($user_body);
                                
                                $string = '';
                                $filepath = '';
                                $filename = '';
                                $sendCopyTo = '';
                       
                       
                                $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                        }
                            
                            ####################    Sending Notification ends        #####################
                     }
                    
                }

                if(!empty($arr_site_users)){

                    $str_site_users= implode(',', $arr_site_users);
                    $response['is_error'] = 1;
                    $response['err_msg'] = "Existing active users: ".$str_site_users;   

                }
                else{
                    $response['success_msg'] = 'Invitation sent successfully to Non Users.';
                }
            }
            
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    /*function search_friend_list_recommendation(){
    
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;
        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $search_text    = $obj->{'search_text'};
        $str_selected_users    = $obj->{'str_selected_users'};
        
        $condition_friend_list= "Friendlist.sender_id = '".$user_id."' AND Friendlist.is_blocked = '0' AND Friendlist.friend_name LIKE '%".$search_text."%'";
        $this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'receiver_id','fields'=>'User.id,User.fname,User.lname,User.image'))),false);
        $search_friend_list = $this->Friendlist->find('all',array('conditions'=>$condition_friend_list));  
        $arr_not_group_friends=array();
        $arr_notified_users=array();
        
        if(count($search_friend_list) > 0)
        {
        
            ###############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected starts  #################
            
            $con_is_req_sent = "Notification.status = '1' AND  Notification.type = 'G' AND Notification.sender_id = '".$user_id."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' AND Notification.group_id = '".$group_id."'";
            $arr_req_sent_users = $this->Notification->find('all',array('conditions'=>$con_is_req_sent, 'fields'=>array('Notification.receiver_id'))); 
            if(!empty($arr_req_sent_users)){    
                foreach($arr_req_sent_users as $key3 => $val3){ 
                        array_push($arr_notified_users, $val3['Notification']['receiver_id']);      
                }
            }

            
            ###############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected ends  #################
            
            ############   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet starts  ###############
            foreach($search_friend_list as $key => $val)
            {
                
                $con_is_member = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$val['Friendlist']['receiver_id']."' AND GroupUser.status = '1'";
                $count_is_member = $this->GroupUser->find('count',array('conditions'=>$con_is_member));
                if($count_is_member == 0)           // Remove the friend who does not belong to selected group
                {
                    if(!empty($arr_notified_users)){
                        if(!in_array($val['Friendlist']['receiver_id'], $arr_notified_users)){  // Remove the friend to whom the notification is sent already 
                            array_push($arr_not_group_friends, $val);
                        }
                    }
                    else{
                        array_push($arr_not_group_friends, $val);
                    }
                }
                
            }   
            ############   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet ends  #################
            
            
            
            if($str_selected_users!=''){
                $arr_selected_users= explode(',', $str_selected_users);
                $arr_search_result= array();
                
                foreach($arr_not_group_friends as $key1 => $val1){  
                    if(!in_array($val1['Friendlist']['receiver_id'], $arr_selected_users)){
                        array_push($arr_search_result, $val1);      
                    }
                }
            }
            else{
                $arr_search_result= $arr_not_group_friends;
            }
            
            $friend_search_list = array(); 
            if(!empty($arr_search_result)){
                foreach($arr_search_result as $valu){
                    $list['friend_id'] = $valu['User']['id'];
                    $list['friend_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'] ;
                    if($valu['User']['image']!='')
                    {
                    $list['friend_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                    }
                    else
                    {
                     $list['friend_image'] =  $base_url.'images/no_profile_img.jpg';
                    }
                    array_push($friend_search_list,$list);
                }
                    $response['Friendlist']= $friend_search_list; 
            }
            else{
                    $response['Friendlist'] = array();
            }
            
        }
        else{
                
            $response['Friendlist'] = array();
            
        }


        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;       
     }*/
     
     
     function search_friend_list_recommendation(){
    
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;
        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $search_text    = $obj->{'search_text'};
        $str_selected_users    = $obj->{'str_selected_users'};
        
        $condition_friend_list= "Friendlist.sender_id = '".$user_id."' AND Friendlist.is_blocked = '0' AND Friendlist.friend_name LIKE '%".$search_text."%'";
        $this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'receiver_id','fields'=>'User.id,User.fname,User.lname,User.image'))),false);
        $search_friend_list = $this->Friendlist->find('all',array('conditions'=>$condition_friend_list));  
        $arr_not_group_friends=array();
        
        if(count($search_friend_list) > 0)
        {
        
            ############   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet starts  ###############
            foreach($search_friend_list as $key => $val)
            {
                
                $con_is_member = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$val['Friendlist']['receiver_id']."' AND GroupUser.status = '1'";
                $count_is_member = $this->GroupUser->find('count',array('conditions'=>$con_is_member));
                if($count_is_member == 0)           // Remove the friend who does not belong to selected group
                {
                       array_push($arr_not_group_friends, $val);
                }
                
            }   
            ############   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet ends  #################
            
            
            
            if($str_selected_users!=''){
                $arr_selected_users= explode(',', $str_selected_users);
                $arr_search_result= array();
                
                foreach($arr_not_group_friends as $key1 => $val1){  
                    if(!in_array($val1['Friendlist']['receiver_id'], $arr_selected_users)){
                        array_push($arr_search_result, $val1);      
                    }
                }
            }
            else{
                $arr_search_result= $arr_not_group_friends;
            }
            
            $friend_search_list = array(); 
            if(!empty($arr_search_result)){
                foreach($arr_search_result as $valu){
                    $list['friend_id'] = $valu['User']['id'];
                    $list['friend_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'] ;
                    if($valu['User']['image']!='')
                    {
                    $list['friend_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                    }
                    else
                    {
                     $list['friend_image'] =  $base_url.'images/no_profile_img.jpg';
                    }
                    array_push($friend_search_list,$list);
                }
                    $response['Friendlist']= $friend_search_list; 
            }
            else{
                    $response['Friendlist'] = array();
            }
            
        }
        else{
                
            $response['Friendlist'] = array();
            
        }


        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;       
     }
     
     
    /*function search_user_list_recommendation(){
       
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;


        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $search_text    = $obj->{'search_text'};
        $str_selected_users    = $obj->{'str_selected_users'};
        $arr_selected_users= explode(',', $str_selected_users);

        $arr_notified_users=array();
            
        $condition_user_list = "User.status = '1' AND (User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND (!find_in_set('".$group_id."',User.`groups`)) AND ( User.`groups` IS NOT NULL) AND User.id != '".$user_id."'";
        $search_user_list = $this->User->find('all',array('conditions'=>$condition_user_list,'order' => array('User.fname ASC', 'User.lname ASC')));    
        ###############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected starts  #################
            
        $con_is_req_sent = "Notification.status = '1' AND  Notification.type = 'G' AND Notification.sender_id = '".$user_id."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' AND Notification.group_id = '".$group_id."'";
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
                    if($count_is_member == 0){      // Those who are not the friend
                    
                    if(!empty($arr_notified_users)){
                        if(!in_array($val['User']['id'], $arr_notified_users)){     //Those who are not being notified 
                            if(!empty($arr_selected_users)){
                                if(!in_array($val['User']['id'], $arr_selected_users)){     //Those who are not selected 
                                    array_push($arr_not_group_users, $val); 
                                }
                            }
                            else{
                                array_push($arr_not_group_users, $val); 
                            }
                        }
                    }
                    else{
                            if(!empty($arr_selected_users)){        //Those who are not selected
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
                //$this->set('arr_search_result',$arr_not_group_users); 
            }
            
            
            $user_search_list = array(); 
            if(!empty($arr_not_group_users)){
                foreach($arr_not_group_users as $valu){
                    $list['user_id'] = $valu['User']['id'];
                    $list['user_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'];
                    if($valu['User']['image']!='')
                    {
                        $list['user_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                    }
                    else
                    {
                        $list['user_image'] =  $base_url.'images/no_profile_img.jpg';
                    }
                    array_push($user_search_list,$list);
                }
                    $response['Userlist']= $user_search_list; 
            }
            else{
                    $response['Userlist'] = array();
            }


        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }*/
    
    
    function search_user_list_recommendation(){
       
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;


        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $search_text    = $obj->{'search_text'};
        $str_selected_users    = $obj->{'str_selected_users'};
        $arr_selected_users= explode(',', $str_selected_users);

            
        $condition_user_list = "User.status = '1' AND (User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND (!find_in_set('".$group_id."',User.`groups`)) AND ( User.`groups` IS NOT NULL) AND User.id != '".$user_id."'";
        $search_user_list = $this->User->find('all',array('conditions'=>$condition_user_list,'order' => array('User.fname ASC', 'User.lname ASC')));    
        

        $arr_not_group_users=array();

        if(count($search_user_list) > 0)
        {
            foreach($search_user_list as $key => $val)
            {
                
                $con_is_member = "((Friendlist.sender_id = '".$user_id."' AND Friendlist.receiver_id = '".$val['User']['id']."') OR (Friendlist.receiver_id = '".$user_id."' AND Friendlist.sender_id = '".$val['User']['id']."'))";
                $count_is_member = $this->Friendlist->find('count',array('conditions'=>$con_is_member));
                if($count_is_member == 0){      // Those who are not the friend
                
                    if(!empty($arr_selected_users)){        //Those who are not selected
                        if(!in_array($val['User']['id'], $arr_selected_users)){
                            array_push($arr_not_group_users, $val); 
                        }
                    }
                    else{
                        array_push($arr_not_group_users, $val); 
                    }
                    
                }
            }
            //$this->set('arr_search_result',$arr_not_group_users); 
        }
            
            
            $user_search_list = array(); 
            if(!empty($arr_not_group_users)){
                foreach($arr_not_group_users as $valu){
                    $list['user_id'] = $valu['User']['id'];
                    $list['user_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'];
                    if($valu['User']['image']!='')
                    {
                        $list['user_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                    }
                    else
                    {
                        $list['user_image'] =  $base_url.'images/no_profile_img.jpg';
                    }
                    array_push($user_search_list,$list);
                }
                    $response['Userlist']= $user_search_list; 
            }
            else{
                    $response['Userlist'] = array();
            }


        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    /*function submit_recommendation(){
      
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;
        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $mode    = $obj->{'mode'};
        $sender_type    = $obj->{'sender_type'};
        $group_type= $obj->{'group_type'};
        $str_users= $obj->{'str_users'};    
            
            
        if(isset($mode) && $mode=='recommend_friends'){
                
            $arr_users= explode(',', $str_users);
            
            for($i=0; $i<count($arr_users); $i++){
            
                $this->data['Notification']['type'] = 'G';
                $this->data['Notification']['group_id'] = $group_id;
                $this->data['Notification']['group_type'] = $group_type;
                $this->data['Notification']['sender_id'] = $user_id;
                $this->data['Notification']['sender_type'] = $sender_type;
                $this->data['Notification']['request_mode'] = 'public';
                $this->data['Notification']['receiver_id'] = $arr_users[$i];
                $this->data['Notification']['receiver_type'] = 'NGM';
                $this->data['Notification']['is_read'] = '0';
                $this->data['Notification']['is_receiver_accepted'] = '0';
                $this->data['Notification']['is_reversed_notification'] = '0';
                $this->data['Notification']['status'] = 1;
                
                $this->Notification->create();
                
                if($this->Notification->save($this->data['Notification']))
                    {
                      $notification_id = $this->Notification->getLastInsertId();  
                      $this->notification_email($notification_id);


                      
                      $page="Group recommendation";
                     

                      $this->send_notification_group_recommendation($user_id, $arr_users[$i], $group_id, $mode,$page);
                    }
            }
            $response['success_msg'] = 'Recommendation sent successfully to friends';
        }   
        else if(isset($mode) && $mode=='recommend_users'){
            
            $arr_users= explode(',', $str_users);
            
            for($i=0; $i<count($arr_users); $i++){
            
                $this->data['Notification']['type'] = 'G';
                $this->data['Notification']['group_id'] = $group_id;
                $this->data['Notification']['group_type'] = $group_type;
                $this->data['Notification']['sender_id'] = $user_id;
                $this->data['Notification']['sender_type'] = $sender_type;
                $this->data['Notification']['request_mode'] = 'public';
                $this->data['Notification']['receiver_id'] = $arr_users[$i];
                $this->data['Notification']['receiver_type'] = 'NGM';
                $this->data['Notification']['is_read'] = '0';
                $this->data['Notification']['is_receiver_accepted'] = '0';
                $this->data['Notification']['is_reversed_notification'] = '0';
                $this->data['Notification']['status'] = 1;
                
                $this->Notification->create();
                //$this->Notification->save($this->data['Notification']);
                if($this->Notification->save($this->data['Notification']))
                    {
                      $notification_id = $this->Notification->getLastInsertId();  
                      $this->notification_email($notification_id);
                      
                      $page="Group recommendation";
                      
                     $this->send_notification_group_recommendation($user_id, $arr_users[$i], $group_id, $mode,$page);
                    }
            }
            $response['success_msg'] = 'Recommendation sent successfully to users';
        }       
        else if(isset($mode) && $mode=='recommend_emails'){
        
            $sitesettings = $this->getSiteSettings();
            $str_users= $_REQUEST['str_users']; 
            
            $arr_emails= explode(',', $str_users);
            
            $arr_site_users = array();
            $arr_notified_users = array();
            
            for($i=0; $i<count($arr_emails); $i++){
            
                 $cond_email_check = "User.email = '".$arr_emails[$i]."'";   // Check whether the email exists
                 $email_check = $this->User->find('first',array('conditions'=>$cond_email_check));
                 
                 if(!empty($email_check)){
                        
                    if($email_check['User']['status']=='1'){
                        array_push($arr_site_users,$arr_emails[$i]);        //Already site users
                    }
                    else{
                        $cond_notification_check = "Notification.sender_id = '".$user_id."' AND Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$email_check['User']['id']."' AND Notification.type = 'G'";   // Check notification already exists for that group from the same owner 
                        
                        $notification_arr = $this->Notification->find('first',array('conditions'=>$cond_notification_check));   
                            
                            if(!empty($notification_arr)){
                                array_push($arr_notified_users,$arr_emails[$i]);        //Already site users

                                $con_noti_detail = "Notification.id = '".$notification_arr['Notification']['id']."' AND Notification.status = '1'";
                                    $this->Notification->bindModel(
                                      array('belongsTo'=>array(
                                          'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                          'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                                          
                                          'Group'=>array('foreignKey'=>'group_id')
                                        )
                                        ));
                                    $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                                    $email = $arr_emails[$i];
                                    $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                                    $group_name = $noti_detail['Group']['group_title'];
                                    if($noti_detail['Group']['group_type'] == 'B')
                                    {
                                        $group_type = 'Business';
                                            
                                    }
                                    else if($noti_detail['Group']['group_type'] == 'F')
                                    {
                                        $group_type = 'Private';
                                    }
               
                                    
                                    $admin_sender_email = $sitesettings['site_email']['value'];
                                    $site_url = $sitesettings['site_url']['value'];
                                    //$sender_name = $sitesettings['email_sender_name']['value'];
                
                                    $condition = "EmailTemplate.id = '20'";
                                    $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                                   
                                    $to = $email;
                
                                    $user_subject = $mailDataRS['EmailTemplate']['subject'];
                                    $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                    
                                 
                                    $user_body = $mailDataRS['EmailTemplate']['content'];
                                    $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                                    $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                                    $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                                    $user_body = str_replace('[GROUP TYPE]', $group_type, $user_body);
                                    $user_body = str_replace('[SITEURL]', $site_url, $user_body);   
                
                                    $user_message = stripslashes($user_body);
                                    
                                    $string = '';
                                    $filepath = '';
                                    $filename = '';
                                    $sendCopyTo = '';
                           
                           
                                    $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                            }
                        else{ 
                            $this->data['Notification']['sender_id'] = $user_id;
                            $this->data['Notification']['type'] = 'G';
                            $this->data['Notification']['group_id'] = $group_id;
                            $this->data['Notification']['group_type'] = $group_type;
                            $this->data['Notification']['sender_type'] = $sender_type;
                            $this->data['Notification']['request_mode'] = 'public';
                            $this->data['Notification']['receiver_id'] = $email_check['User']['id'];
                            $this->data['Notification']['receiver_type'] = 'NGM';
                            $this->data['Notification']['is_read'] = 0;
                            $this->data['Notification']['is_receiver_accepted'] = 0;
                            $this->data['Notification']['is_reversed_notification'] = 0;
                            $this->data['Notification']['status'] = 1;
                            $this->Notification->create();
                            
                            if($this->Notification->save($this->data['Notification'])){     //Send the notification to the dummy user
                        
                            $last_noti_insert_id = $this->Notification->getLastInsertId();
                            $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                            $this->Notification->bindModel(
                              array('belongsTo'=>array(
                                  'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                  'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                                  
                                  'Group'=>array('foreignKey'=>'group_id')
                                )
                                ));
                            $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                            $email = $arr_emails[$i];
                            $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                            $group_name = $noti_detail['Group']['group_title'];
                            if($noti_detail['Group']['group_type'] == 'B')
                            {
                                $group_type = 'Business';
                                    
                            }
                            else if($noti_detail['Group']['group_type'] == 'F')
                            {
                                $group_type = 'Private';
                            }
        
                            
                            $admin_sender_email = $sitesettings['site_email']['value'];
                            $site_url = $sitesettings['site_url']['value'];
                            //$sender_name = $sitesettings['email_sender_name']['value'];
        
                            $condition = "EmailTemplate.id = '20'";
                            $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                           
                            $to = $email;
        
                            $user_subject = $mailDataRS['EmailTemplate']['subject'];
                            $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                       
                         
                            $user_body = $mailDataRS['EmailTemplate']['content'];
                            $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                            $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                            $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                            $user_body = str_replace('[GROUP TYPE]', $group_type, $user_body);
                            $user_body = str_replace('[SITEURL]', $site_url, $user_body);
        
                            $user_message = stripslashes($user_body);
                            
                            $string = '';
                            $filepath = '';
                            $filename = '';
                            $sendCopyTo = '';
                   
                   
                            $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                        }
                        }
                    }
                 }
                 else{
                 
                        ###################    User creation starts    ######################
                        $this->data['User']['email'] = trim(preg_replace('/\s+/','',$arr_emails[$i]));
                        $this->data['User']['status'] = 0;
                        $this->data['User']['is_invite'] = 1;
                        $this->data['User']['groups'] = '';
                                    
                        $this->User->create();
                        $this->User->save($this->data['User']);     // Creates a dummy User
                        $last_insert_id = $this->User->getLastInsertId();
                        
                        ####################   User creation ends    ########################
                        
                        ####################    Sending Notification starts     ####################
                        
                        $this->data['Notification']['sender_id'] = $user_id;
                        $this->data['Notification']['type'] = 'G';
                        $this->data['Notification']['group_id'] = $group_id;
                        $this->data['Notification']['group_type'] = $group_type;
                        $this->data['Notification']['sender_type'] = $sender_type;
                        $this->data['Notification']['request_mode'] = 'public';
                        $this->data['Notification']['receiver_id'] = $last_insert_id;
                        $this->data['Notification']['receiver_type'] = 'NGM';
                        $this->data['Notification']['is_read'] = 0;
                        $this->data['Notification']['is_receiver_accepted'] = 0;
                        $this->data['Notification']['is_reversed_notification'] = 0;
                        $this->data['Notification']['status'] = 1;
                        $this->Notification->create();
                        
                        if($this->Notification->save($this->data['Notification'])){     //Send the notification to the dummy user
                        
                        $last_noti_insert_id = $this->Notification->getLastInsertId();
                        $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                        $this->Notification->bindModel(
                          array('belongsTo'=>array(
                              'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                              'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                              
                              'Group'=>array('foreignKey'=>'group_id')
                            )
                            ));
                        $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                        $email = $arr_emails[$i];
                        $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                        $group_name = $noti_detail['Group']['group_title'];
                        if($noti_detail['Group']['group_type'] == 'B')
                        {
                            $group_type = 'Business';
                                
                        }
                        else if($noti_detail['Group']['group_type'] == 'F')
                        {
                            $group_type = 'Private';
                        }
    
                        
                        $admin_sender_email = $sitesettings['site_email']['value'];
                        $site_url = $sitesettings['site_url']['value'];
                        //$sender_name = $sitesettings['email_sender_name']['value'];
    
                        $condition = "EmailTemplate.id = '20'";
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                       
                        $to = $email;
    
                        $user_subject = $mailDataRS['EmailTemplate']['subject'];
                        $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                   
                     
                        $user_body = $mailDataRS['EmailTemplate']['content'];
                        $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                        $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                        $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                        $user_body = str_replace('[GROUP TYPE]', $group_type, $user_body);
                        $user_body = str_replace('[SITEURL]', $site_url, $user_body);
    
                        $user_message = stripslashes($user_body);
                        
                        $string = '';
                        $filepath = '';
                        $filename = '';
                        $sendCopyTo = '';
               
               
                        $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                    }
                        
                        ####################    Sending Notification ends        #####################
                 }
                
            }

            
            if(!empty($arr_site_users) || !empty($arr_notified_users)){
                
                if(!empty($arr_site_users)){
                    $str_site_users= implode(',', $arr_site_users);
                    $response['is_error'] = 1;
                    $response['err_msg'] = "Existing active users: ".$str_site_users;   
                }
                if(!empty($arr_notified_users)){
                    $str_notified_users= implode(',', $arr_notified_users);
                    $response['is_error'] = 1;
                    $response['err_msg'].= "<br>"."Existing notified users: ".$str_notified_users;
                }
            }
            else{
                $response['success_msg'] = 'Recommendation sent successfully to Non Users.';
            }
        }
            
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }*/
    
    
    function submit_recommendation(){
      
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;
        
        $group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $mode    = $obj->{'mode'};
        $sender_type    = $obj->{'sender_type'};
        $group_type= $obj->{'group_type'};
        $str_users= $obj->{'str_users'};    
            
            
        if(isset($mode) && $mode=='recommend_friends'){
                
            $arr_users= explode(',', $str_users);
            
            for($i=0; $i<count($arr_users); $i++){
            
                $cond_chk_notification_exist = "Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$arr_users[$i]."' AND Notification.type = 'G' AND Notification.group_type = '".$group_type."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";   // Check notification already exists for that group from the any owner 
                $notification_arr_exists = $this->Notification->find('first',array('conditions'=>$cond_chk_notification_exist));    
                if(!empty($notification_arr_exists)){
                     $this->Notification->query("DELETE FROM notifications WHERE (`id` = '".$notification_arr_exists['Notification']['id']."')");
                }
            
                $this->data['Notification']['type'] = 'G';
                $this->data['Notification']['group_id'] = $group_id;
                $this->data['Notification']['group_type'] = $group_type;
                $this->data['Notification']['sender_id'] = $user_id;
                $this->data['Notification']['sender_type'] = $sender_type;
                $this->data['Notification']['request_mode'] = 'public';
                $this->data['Notification']['receiver_id'] = $arr_users[$i];
                $this->data['Notification']['receiver_type'] = 'NGM';
                $this->data['Notification']['is_read'] = '0';
                $this->data['Notification']['is_receiver_accepted'] = '0';
                $this->data['Notification']['is_reversed_notification'] = '0';
                $this->data['Notification']['status'] = 1;
                
                $this->Notification->create();
                
                if($this->Notification->save($this->data['Notification']))
                    {
                      $notification_id = $this->Notification->getLastInsertId();  
                      $this->notification_email($notification_id);


                      
                      $page="Group recommendation";
                     

                      $this->send_notification_group_recommendation($user_id, $arr_users[$i], $group_id, $mode,$page);
                    }
            }
            $response['success_msg'] = 'Recommendation sent successfully to friends';
        }   
        else if(isset($mode) && $mode=='recommend_users'){
            
            $arr_users= explode(',', $str_users);
            
            for($i=0; $i<count($arr_users); $i++){
            
                $cond_chk_notification_exist = "Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$arr_users[$i]."' AND Notification.type = 'G' AND Notification.group_type = '".$group_type."'AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";   // Check notification already exists for that group from the any owner 
                $notification_arr_exists = $this->Notification->find('first',array('conditions'=>$cond_chk_notification_exist));    
                if(!empty($notification_arr_exists)){
                     $this->Notification->query("DELETE FROM `notifications` WHERE (`id` = '".$notification_arr_exists['Notification']['id']."')");
                }
            
                $this->data['Notification']['type'] = 'G';
                $this->data['Notification']['group_id'] = $group_id;
                $this->data['Notification']['group_type'] = $group_type;
                $this->data['Notification']['sender_id'] = $user_id;
                $this->data['Notification']['sender_type'] = $sender_type;
                $this->data['Notification']['request_mode'] = 'public';
                $this->data['Notification']['receiver_id'] = $arr_users[$i];
                $this->data['Notification']['receiver_type'] = 'NGM';
                $this->data['Notification']['is_read'] = '0';
                $this->data['Notification']['is_receiver_accepted'] = '0';
                $this->data['Notification']['is_reversed_notification'] = '0';
                $this->data['Notification']['status'] = 1;
                
                $this->Notification->create();
                //$this->Notification->save($this->data['Notification']);
                if($this->Notification->save($this->data['Notification']))
                    {
                      $notification_id = $this->Notification->getLastInsertId();  
                      $this->notification_email($notification_id);
                      
                      $page="Group recommendation";
                      
                     $this->send_notification_group_recommendation($user_id, $arr_users[$i], $group_id, $mode,$page);
                    }
            }
            $response['success_msg'] = 'Recommendation sent successfully to users';
        }       
        else if(isset($mode) && $mode=='recommend_emails'){
        
            $sitesettings = $this->getSiteSettings();

            
            $arr_emails= explode(',', $str_users);
            
            $arr_site_users = array();
            
            for($i=0; $i<count($arr_emails); $i++){
            
                 $cond_email_check = "User.email = '".$arr_emails[$i]."'";   // Check whether the email exists
                 $email_check = $this->User->find('first',array('conditions'=>$cond_email_check));
                 
                 if(!empty($email_check)){
                        
                    if($email_check['User']['status']=='1'){
                        array_push($arr_site_users,$arr_emails[$i]);        //Already site users
                    }
                    else{
                    
                        $cond_chk_notification_exist = "Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$email_check['User']['id']."' AND Notification.type = 'G' AND Notification.group_type = '".$group_type."'AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";  // Check notification already exists for that group from the any owner 
                        $notification_arr_exists = $this->Notification->find('first',array('conditions'=>$cond_chk_notification_exist));    
                        if(!empty($notification_arr_exists)){
                             $this->Notification->query("DELETE FROM `notifications` WHERE (`id` = '".$notification_arr_exists['Notification']['id']."')");
                        }
                        
                            $this->data['Notification']['sender_id'] = $user_id;
                            $this->data['Notification']['type'] = 'G';
                            $this->data['Notification']['group_id'] = $group_id;
                            $this->data['Notification']['group_type'] = $group_type;
                            $this->data['Notification']['sender_type'] = $sender_type;
                            $this->data['Notification']['request_mode'] = 'public';
                            $this->data['Notification']['receiver_id'] = $email_check['User']['id'];
                            $this->data['Notification']['receiver_type'] = 'NGM';
                            $this->data['Notification']['is_read'] = 0;
                            $this->data['Notification']['is_receiver_accepted'] = 0;
                            $this->data['Notification']['is_reversed_notification'] = 0;
                            $this->data['Notification']['status'] = 1;
                            $this->Notification->create();
                            
                            if($this->Notification->save($this->data['Notification'])){     //Send the notification to the dummy user
                        
                            $last_noti_insert_id = $this->Notification->getLastInsertId();
                            $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                            $this->Notification->bindModel(
                              array('belongsTo'=>array(
                                  'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                  'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                                  
                                  'Group'=>array('foreignKey'=>'group_id')
                                )
                                ));
                            $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                            $email = $arr_emails[$i];
                            $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                            $group_name = $noti_detail['Group']['group_title'];
                            if($noti_detail['Group']['group_type'] == 'B')
                            {
                                $grouptype = 'Business';
                                    
                            }
                            else if($noti_detail['Group']['group_type'] == 'F')
                            {
                                $grouptype = 'Private';
                            }
        
                            
                            $admin_sender_email = $sitesettings['site_email']['value'];
                            $site_url = $sitesettings['site_url']['value'];
                            //$sender_name = $sitesettings['email_sender_name']['value'];
        
                            $condition = "EmailTemplate.id = '20'";
                            $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                           
                            $to = $email;
        
                            $user_subject = $mailDataRS['EmailTemplate']['subject'];
                            $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                       
                         
                            $user_body = $mailDataRS['EmailTemplate']['content'];
                            $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                            $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                            $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                            $user_body = str_replace('[GROUP TYPE]', $grouptype, $user_body);
                            $user_body = str_replace('[SITEURL]', $site_url, $user_body);
        
                            $user_message = stripslashes($user_body);
                            
                            $string = '';
                            $filepath = '';
                            $filename = '';
                            $sendCopyTo = '';
                   
                   
                            $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                        }
                    }
                 }
                 else{
                 
                        ###################    User creation starts    ######################
                        $this->data['User']['email'] = trim(preg_replace('/\s+/','',$arr_emails[$i]));
                        $this->data['User']['status'] = 0;
                        $this->data['User']['is_invite'] = 1;
                        $this->data['User']['groups'] = '';
                                    
                        $this->User->create();
                        $this->User->save($this->data['User']);     // Creates a dummy User
                        $last_insert_id = $this->User->getLastInsertId();
                        
                        ####################   User creation ends    ########################
                        
                        ####################    Sending Notification starts     ####################
                        
                        $this->data['Notification']['sender_id'] = $user_id;
                        $this->data['Notification']['type'] = 'G';
                        $this->data['Notification']['group_id'] = $group_id;
                        $this->data['Notification']['group_type'] = $group_type;
                        $this->data['Notification']['sender_type'] = $sender_type;
                        $this->data['Notification']['request_mode'] = 'public';
                        $this->data['Notification']['receiver_id'] = $last_insert_id;
                        $this->data['Notification']['receiver_type'] = 'NGM';
                        $this->data['Notification']['is_read'] = 0;
                        $this->data['Notification']['is_receiver_accepted'] = 0;
                        $this->data['Notification']['is_reversed_notification'] = 0;
                        $this->data['Notification']['status'] = 1;
                        $this->Notification->create();
                        
                        if($this->Notification->save($this->data['Notification'])){     //Send the notification to the dummy user
                        
                        $last_noti_insert_id = $this->Notification->getLastInsertId();
                        $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                        $this->Notification->bindModel(
                          array('belongsTo'=>array(
                              'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                              'fields'=>'Sender.id,Sender.fname,Sender.lname'),
                              
                              'Group'=>array('foreignKey'=>'group_id')
                            )
                            ));
                        $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                        $email = $arr_emails[$i];
                        $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                        $group_name = $noti_detail['Group']['group_title'];
                        if($noti_detail['Group']['group_type'] == 'B')
                        {
                            $grouptype = 'Business';
                                
                        }
                        else if($noti_detail['Group']['group_type'] == 'F')
                        {
                            $grouptype = 'Private';
                        }
    
                        
                        $admin_sender_email = $sitesettings['site_email']['value'];
                        $site_url = $sitesettings['site_url']['value'];
                        //$sender_name = $sitesettings['email_sender_name']['value'];
    
                        $condition = "EmailTemplate.id = '20'";
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                       
                        $to = $email;
    
                        $user_subject = $mailDataRS['EmailTemplate']['subject'];
                        $user_subject = str_replace('[SITE NAME]', 'Grouper | Group Invitation', $user_subject);
                                   
                     
                        $user_body = $mailDataRS['EmailTemplate']['content'];
                        $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                        $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                        $user_body = str_replace('[GROUP NAME]', $group_name, $user_body);
                        $user_body = str_replace('[GROUP TYPE]', $grouptype, $user_body);
                        $user_body = str_replace('[SITEURL]', $site_url, $user_body);
    
                        $user_message = stripslashes($user_body);
                        
                        $string = '';
                        $filepath = '';
                        $filename = '';
                        $sendCopyTo = '';
               
               
                        $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                    }
                        
                        ####################    Sending Notification ends        #####################
                 }
                
            }

            
            if(!empty($arr_site_users)){
                
                    $str_site_users= implode(',', $arr_site_users);
                    $response['is_error'] = 1;
                    $response['err_msg'] = "Existing active users: ".$str_site_users;   
            }
            else{
                $response['success_msg'] = 'Recommendation sent successfully to Non Users.';
            }
        }
            
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    
    function create_event(){
       
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        
        $obj = json_decode($json);


        
        $title    = $obj->{'title'};
        $group_id    = $obj->{'group_id'};
        $type    = $obj->{'type'};
        $datetime    = $obj->{'datetime'};
        $desc    = $obj->{'desc'};
        $location    = $obj->{'location'};
        $latitude    = $obj->{'latitude'};
        $longitude    = $obj->{'longitude'};
        $place_id    = $obj->{'place_id'};
        $user_id    = $obj->{'user_id'};
        $is_multiple    = $obj->{'is_multiple'};
        $deal_amount    = $obj->{'deal_amount'};
        $end_datetime    = $obj->{'end_datetime'};
   

            
          $this->data['Event']['title'] =  addslashes($title);
          $this->data['Event']['group_id'] = $group_id;
          $this->data['Event']['desc'] = addslashes($desc);
          $this->data['Event']['location'] = $location;
          $this->data['Event']['latitude'] = $latitude;
          $this->data['Event']['longitude'] = $longitude;
          $this->data['Event']['place_id'] = $place_id;
          
          
          $con_group_detail = "Group.id = '".$group_id."' AND Group.status = '1'";
          $group_detail = $this->Group->find('first',array('conditions'=>$con_group_detail));
          //  pr($group_detail['Group']['category_id']);
          if($group_detail['Group']['category_id']==''){
          
               $this->data['Event']['category_id'] = '';
           }
           else{
                $this->data['Event']['category_id'] = $group_detail['Group']['category_id'];
           }  
           $this->data['Event']['group_type'] = $group_detail['Group']['group_type']; 
           if($group_detail['Group']['group_type']=='B'){
                $this->data['Event']['deal_amount'] = $deal_amount;
                $this->data['Event']['type'] = 'public';
           }
           else if($group_detail['Group']['group_type']=='F'){
                $this->data['Event']['deal_amount'] = '0.00';
                $this->data['Event']['type'] = $type;
           }

                   
          if($is_multiple == '1'){
          
                $this->data['Event']['event_timestamp'] = '0';
                $this->data['Event']['event_date'] = '0000-00-00'; 
                $this->data['Event']['event_start_timestamp'] = strtotime($datetime);
                $this->data['Event']['event_end_timestamp'] = strtotime($end_datetime);
                $this->data['Event']['is_multiple_date'] = $is_multiple;
                $this->data['Event']['event_start_date'] = date("Y-m-d",strtotime($datetime));
                $this->data['Event']['event_end_date'] = date("Y-m-d",strtotime($end_datetime));

          }
          else{
                $this->data['Event']['event_date'] = date("Y-m-d",strtotime($datetime)); 
                $this->data['Event']['event_timestamp'] = strtotime($datetime);
                $this->data['Event']['event_start_timestamp'] = '0';
                $this->data['Event']['event_end_timestamp'] = '0';
                $this->data['Event']['event_start_date'] = '0000-00-00';
                $this->data['Event']['event_end_date'] = '0000-00-00';
                $this->data['Event']['is_multiple_date'] = $is_multiple;
          }
                  
          $this->data['Event']['created_by_owner_id'] = $user_id;
                  
                  
                                          
          $this->Event->create();
          if($this->Event->save($this->data)){
                  

           $last_insert_id = $this->Event->getLastInsertId();
           $condition_event_detail = "Event.id = '".$last_insert_id."'";      
                $latest_event_details = $this->Event->find('first',array('conditions'=>$condition_event_detail));
           
              

                if($latest_event_details['Event']['group_type'] == 'F'){

                    $condition_group_user_detail = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id != '".$user_id."'";
              
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
                   
                        if($this->Notification->save($this->data['Notification']))
                        {
                          $notification_id = $this->Notification->getLastInsertId();  
                          $eve_name = addslashes($title);
         
                         // $this->notification_email($notification_id);
                          
                          $page="Event reminder";
                          //$this->send_push_notification_event($user_id, $grp_mem['GroupUser']['user_id'], $latest_event_details['Event']['group_id'], $last_insert_id, $page);
                        }
                  }
                        
                         
          }

          $response['is_error'] = 0;  
          $response['success_msg'] = 'Event created successfully';
                              
                 
        }
        else{
        
              $response['is_error'] = 1;
              $response['err_msg'] = 'Event creation failed';
        }
                         
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function event_list(){
       
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
   
       $group_id    = $obj->{'group_id'};
       $date    = $obj->{'date'};


       /*$group_id    = '49';
        $date = '2017-06-29';*/
		
		$con_grp_details = "Group.status= '1'  AND Group.id= '".$group_id."'";
		$arr_group_detail = $this->Group->find('first',array('conditions'=>$con_grp_details));
		##################################         Check the list of sub Groups of the Group starts        ###########################
		
		if($arr_group_detail['Group']['group_type']!='B'){
			$con_fetch_subgrps = "Group.status= '1'  AND Group.parent_id= '".$group_id."'";
			$all_subgrps = $this->Group->find("all",array('conditions'=>$con_fetch_subgrps));
			if(!empty($all_subgrps)){
				$arr_subgrps= array();
				foreach($all_subgrps as $all_sub_grps){
					array_push($arr_subgrps, $all_sub_grps['Group']['id']);		
				}
				$str_subgroups= implode(',',$arr_subgrps);
			}
			else{
				$str_subgroups='';
			}
		}
		else{
			$str_subgroups='';
		}
		
		##################################         Check the list of sub Groups of the Group ends        ###########################
           
       if($str_subgroups!=''){
	   		$event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE (`group_id` = '".$group_id."' OR (`group_id` IN (".$str_subgroups.") AND (`type` = 'public' OR `type` = 'semi_private'))) AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,
			`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE (`group_id` = '".$group_id."' OR (`group_id` IN (".$str_subgroups.") AND (`type` = 'public' OR `type` = 'semi_private'))) AND `status` = '1' AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
	   }
	   else{   
		   $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,
			`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
		}
  
       $event_list = array(); 

            $condition_group_detail = "Group.id = '".$group_id."'";
            $group_detail = $this->Group->find('first',array('conditions'=>$condition_group_detail));
            
            if(count($event_details) > 0){ 
                foreach($event_details as $events)
                {
                    $list = array();

                    $list['id'] = $events['Event']['id'];
                    $list['event_name'] =  stripslashes($events['Event']['title']);
                    $list['desc'] =  stripslashes($events['Event']['desc']);
                    $list['deal_amount'] =  $events['Event']['deal_amount'];
                    
                    $list['group_id'] = $events['Event']['group_id'];
                    if($events['Event']['group_id']!=$group_id){
					
						$condition_grp_name = "Group.id = '".$events['Event']['group_id']."'";
						$arr_grp_name = $this->Group->find('first',array('conditions'=>$condition_grp_name)); 
						$list['group_name'] =  stripslashes($arr_grp_name['Group']['group_title']);
					}
					else{
                    	$list['group_name'] =  stripslashes($group_detail['Group']['group_title']);
					}
                    $list['group_type'] =  $events['Event']['group_type'];
                    $list['event_type'] = $events['Event']['type'];
                    $list['is_multiple_date'] = $events['Event']['is_multiple_date'];

                    $condition_event_time_detail = "Event.id = '".$events['Event']['id']."'";
                    $event_time_detail = $this->Event->find('first',array('conditions'=>$condition_event_time_detail)); 

                    if($events['Event']['is_multiple_date'] == '1')
                    {

                        $list['event_start_date_time'] =  date("Y-m-d H:i:s",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_end_timestamp']);
                    }
                    else
                    {
                       $list['event_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_timestamp']);        

                    }

                   
                    $list['location'] = $events['Event']['location'];
                    $list['latitude'] =  $events['Event']['latitude'];
                    $list['longitude'] =  $events['Event']['longitude'];
                    $list['place_id'] =  $events['Event']['place_id'];
                    
                    
                    array_push($event_list,$list);
                }
                $response['event_list']= $event_list; 
                $response['is_error'] = 0;
          }
            else{
                $response['is_error'] = 1;
                $response['event_list']= array(); 
                $response['err_msg'] = 'No events found';
            }
                
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function edit_event(){
      
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $event_id    = $obj->{'event_id'};
        $user_id = $obj->{'user_id'};
        $title    = $obj->{'title'};
        $group_id    = $obj->{'group_id'};
        $type    = $obj->{'type'};
        $datetime    = $obj->{'datetime'};
        $desc    = $obj->{'desc'};
        $location    = $obj->{'location'};
        $latitude    = $obj->{'latitude'};
        $longitude    = $obj->{'longitude'};
        $place_id    = $obj->{'place_id'};
        $is_multiple    = $obj->{'is_multiple'};
        $deal_amount    = $obj->{'deal_amount'};
        $end_datetime    = $obj->{'end_datetime'};
        $group_type = $obj->{'group_type'};

/*
        $event_id    = '130';
        $user_id = '4';
        $title    = 'Test edit';
        $group_id    = '47';
        $type    = 'private';
        $datetime    = '2017-06-30 20:34:00';
        $desc    = 'test project';
        $location    = '1\/A, Metropolvcbcbvbitan Co-operative, Sec-B, Tangra, Kolkata, West Bengal 700105, India';
        $latitude    = '22.5526651';
        $longitude    = '88.4061328';
        $is_multiple    = '0';
        $deal_amount    = '50';
        $end_datetime    = '2017-07-03 20:34:00';
        $group_type = 'B';*/

       
      $this->data['Event']['id'] =  $event_id;
      $this->data['Event']['title'] =  $title;
      $this->data['Event']['desc'] = $desc;
      $this->data['Event']['location'] = $location;
      $this->data['Event']['latitude'] = $latitude;
      $this->data['Event']['longitude'] = $longitude;
      $this->data['Event']['place_id'] = $place_id;
        

      $this->data['Event']['group_type'] = $group_type; 
      if($group_type == 'B'){
      
            $this->data['Event']['deal_amount'] = $deal_amount;
            $this->data['Event']['type'] = 'public';
        
      }
      else if($group_type == 'F' || $group_type == 'PO'){
      
        $this->data['Event']['deal_amount'] = '0.00';
        $this->data['Event']['type'] = $type;
       }      
          
      if($is_multiple == '1')     
      {
                $this->data['Event']['event_timestamp'] = '0';
                $this->data['Event']['event_date'] = '0000-00-00'; 
                $this->data['Event']['event_start_timestamp'] = strtotime($datetime);
                $this->data['Event']['event_start_date'] = date("Y-m-d",strtotime($datetime));
                $this->data['Event']['event_end_timestamp'] = strtotime($end_datetime);
                $this->data['Event']['event_end_date'] = date("Y-m-d",strtotime($end_datetime));
                $this->data['Event']['is_multiple_date'] = $is_multiple;
                
               

      }    
      else
      {

                $this->data['Event']['event_date'] = date("Y-m-d",strtotime($datetime)); 
                $this->data['Event']['event_timestamp'] = strtotime($datetime);
                $this->data['Event']['event_start_timestamp'] = '0';
                $this->data['Event']['event_end_timestamp'] = '0';
                $this->data['Event']['event_start_date'] = '0000-00-00';
                $this->data['Event']['event_end_date'] = '0000-00-00';
                $this->data['Event']['is_multiple_date'] = $is_multiple;

      }

        
         if($this->Event->save($this->data['Event'])){

            $condition_event_detail = "Event.id = '".$event_id."'";
                      
            $latest_event_details = $this->Event->find('first',array('conditions'=>$condition_event_detail));

            $condition_group_user_detail = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id != '".$user_id."'";
                      
                  $group_members = $this->GroupUser->find('all',array('conditions'=>$condition_group_user_detail));

                  foreach($group_members as $grp_mem){
                        
                        if($latest_event_details['Event']['group_type'] == 'F'){
                        
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
         
                          //$this->notification_email($notification_id);


                          $page="Event update";
                          //$this->send_push_notification_edit_event($user_id, $grp_mem['GroupUser']['user_id'], $latest_event_details['Event']['group_id'], $event_id, $page);
                        }
                      }
                }


                 $response['is_error'] = 0;
                 $response['success_msg'] = 'Event Updated Successfully !!';
                 

        }
        else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'Error to update the Event !!';
        }
        
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;   
     
    }
    
    function delete_event(){
    
        $this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $event_id    = $obj->{'event_id'};
        $user_id = $obj->{'user_id'};
    
        
        if($event_id>0){
        
              $this->Event->id = $event_id;
              $this->Event->delete();
              
              $response['is_error'] = 0;
              $response['success_msg'] = 'Event deleted successfully !!!';
        }         
        else{
             $response['is_error'] = 1;
             $response['err_msg'] = 'Event does not exist';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;   
    }
    
        
    function group_image_upload(){ 
    
        //Configure::write('debug',3);
        $this->layout = ""; 
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $group_id = $_POST['group_id'];
        $response = array();
         
        $response['is_error'] = 0;
            
        $total_image = $_POST['upload_count'];
        
        for($i = 0;$i<$total_image;$i++){
            
              if(isset($_FILES["upload_image".$i]['name']) && !empty($_FILES["upload_image".$i]['name'])){
            
                    if(isset($_FILES["upload_image".$i]['name']) && $_FILES["upload_image".$i]['name']!= ''){
              
                        $image_name = $_FILES["upload_image".$i]['name'];
        
                        $extension = end(explode('.',$image_name));           
                        $upload_image_name = time().accessCode(5).'.'.$extension;     
                        $upload_target_original = 'gallery/'.$upload_image_name;
            
                        $imagelist = getimagesize($_FILES["upload_image".$i]['tmp_name']);
                        list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
              
                        if($type == 1 || $type == 2){
                    
                            if($uploaded_width >=160 && $uploaded_height >= 120){
                      
                                if(move_uploaded_file($_FILES["upload_image".$i]['tmp_name'], $upload_target_original)){
                                                                                                        
                                          $upload_target_thumb = 'gallery/thumb/'.$upload_image_name;
                                          $upload_target_web = 'gallery/web/'.$upload_image_name;
                                          $upload_target_medium = 'gallery/medium/'.$upload_image_name;
              
                        
                                          $max_web_width =  180;
                                          $max_web_height = 122; 
    
                                          $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                                          $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);                   
                  
                                          $this->imgOptCpy($upload_target_original, $upload_target_web, $max_web_width, $max_web_height, 100, true);
                     
                                    
                                           $this->data['GroupImage']['group_id'] = $group_id;
                                           $this->data['GroupImage']['image'] = $upload_image_name;

                        
                                           $this->GroupImage->create();
                                           if($this->GroupImage->save($this->data)){
                                                   if($i == $total_image){
                                                   
                                                        $response['is_error'] = 0;
                                                    }
                                            }
                                }
                                else{
                                    $response['is_error'] = 1;
                            
                                }
            
                        }
                            else{ 
                                $response['is_error'] = 1;    
                            }
                        }
                        else{
                            
                            $response['is_error'] = 1;      
                           
                        }
                    }
    
    
            

                    if($response['is_error']==1){
                        $response['err_msg'] = 'All images could not be uploaded Please upload bigger images only';
                    }
              }
              else{
                  $response['is_error'] = 1;
                  $response['err_msg'] = 'Please upload image';
              }
          }
          
          header('Content-type: application/json');
          echo json_encode($response);
          exit;

      }
      
      
    function get_group_images(){

        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $response['is_error'] = 0;
        $obj = json_decode($json);

        $group_id = $obj->{'group_id'};
        
        $response = array();
        $response['is_error'] = 0;

        $condn = "GroupImage.group_id = '".$group_id."' AND GroupImage.status = '1'";
        $photo_list = $this->GroupImage->find('all',array('conditions'=>$condn));   
        if(!empty($photo_list)){
                
                foreach($photo_list as $key => $val){   
                     
                     $response['group_image_url'][$key]['id'] = $val['GroupImage']['id'];
                     $response['group_image_url'][$key]['thumb'] = $base_url.'gallery/thumb/'.$val['GroupImage']['image'];
                     $response['group_image_url'][$key]['medium'] = $base_url.'gallery/medium/'.$val['GroupImage']['image'];
                     $response['group_image_url'][$key]['original'] = $base_url.'gallery/'.$val['GroupImage']['image']; 
                  
                }
        }
        else{
              $response['is_error'] = 1;
              $response['err_msg'] = 'No images found';
        }

        header('Content-type: application/json');
        echo json_encode($response);
        exit;

    }
    
    
    function delete_group_image(){
    
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $response['is_error'] = 0;
        $obj = json_decode($json);

        $imageID = $obj->{'image_id'};
        //$imageID = '3';
        
        $response = array();
        $response['is_error'] = 0;

      
        $conditions = "GroupImage.id = '".$imageID."'"; 
        $GrpImgdetail = $this->GroupImage->find('first',array('conditions' => $conditions)); 
         
        if(!empty($GrpImgdetail)){
        
                $folder = 'gallery/';
                $folder_medium = 'gallery/medium/';
                $folder_thumb = 'gallery/thumb/';
                $folder_web = 'gallery/web/';
                
                $this->removeFile($GrpImgdetail['GroupImage']['image'],$folder);
                $this->removeFile($GrpImgdetail['GroupImage']['image'],$folder_medium);
                $this->removeFile($GrpImgdetail['GroupImage']['image'],$folder_thumb);
                $this->removeFile($GrpImgdetail['GroupImage']['image'],$folder_web);
                
                $this->GroupImage->id = $imageID;
                $this->GroupImage->delete();
         }         
        else{
             $response['is_error'] = 1;
             $response['err_msg'] = 'Image does not exist';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
     }
     
     
    function group_doc_upload(){      
    
            //Configure::write('debug',3);
            $this->layout = ""; 
            $json = file_get_contents('php://input');
            $obj = json_decode($json);

            $group_id = $_POST['group_id'];
            $response = array();
         
            $response['is_error'] = 0;
            
            //$image_loop = 0;
            //foreach($upload_image['name'] as $image)
            $file_type = array('doc','docx','pdf','xls');
            $total_docs = $_POST['upload_count'];
            for($i = 0;$i<$total_docs;$i++)
            {
             
              if(isset($_FILES["upload_doc".$i]['name']) && $_FILES["upload_doc".$i]['name']!= '' )
              {

                $regis_cv = $_FILES["upload_doc".$i]['name'];
                $regis_cvType = $_FILES["upload_doc".$i]['type'];
                $ext = substr(strtolower(strrchr($_FILES["upload_doc".$i]['name'], '.')), 1);
                if(in_array($ext, $file_type))
                {
                $regis_cv_arr = explode('.',$regis_cv);
                $regis_cv_arr_count = sizeof($regis_cv_arr);
                $upload_cv = 'doc_'.time().".".$regis_cv_arr[$regis_cv_arr_count-1];
                $upload_target_original = 'gallery/doc/'.$upload_cv;
               if( move_uploaded_file($_FILES["upload_doc".$i]['tmp_name'],$upload_target_original))
               {
              
                $this->data['GroupDoc']['group_id'] = $group_id;
                $this->data['GroupDoc']['docname'] = $upload_cv;

                 $this->GroupDoc->create();
                      if($this->GroupDoc->save($this->data))
                       {
                         if($i == $total_docs)
                          {
                            $response['is_error'] = 0;
                          }
                       }
                   }
                    else  
                      {
                        $response['is_error'] = 1; 
                        
                      }
                  }
                  else  
                      {
                        $response['is_error'] = 1;
                        $response['error_msg'] = 'Please upload doc/docx/pdf only';     
                      }
                }
                else
                  {
                    
                    $response['is_error'] = 1;  
                    $response['error_msg'] = 'Please upload doc';     
                   
                  }
               
          }
            


            header('Content-type: application/json');
            echo json_encode($response);
            exit;

      }
      
    
    function get_group_docs(){
            //Configure::write('debug',3);
            $this->layout = ""; 
             $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $response['is_error'] = 0;
            $obj = json_decode($json);

             $group_id = $obj->{'group_id'};
            // $group_id = '6';

              $condn = "GroupDoc.group_id = '".$group_id."' AND GroupDoc.status = '1'";
              $doc_list = $this->GroupDoc->find('all',array('conditions'=>$condn));   
              if(!empty($doc_list))
              {
                foreach($doc_list as $key => $val)
              
                {   
                  
            $response['group_doc'][$key]['id'] = $val['GroupDoc']['id'];
            $response['group_doc'][$key]['file'] = $base_url.'gallery/doc/'.$val['GroupDoc']['docname'];
            $response['group_doc'][$key]['file_name'] = $val['GroupDoc']['docname'];
            $response['group_doc'][$key]['extension'] = substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1);
                  
                }
              }
               else
            {
              $response['is_error'] = 1;
              $response['err_msg'] = 'No documents found';
            }

            header('Content-type: application/json');
            echo json_encode($response);
            exit;

     }
     
    
    function delete_group_doc(){
    
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $response['is_error'] = 0;
        $obj = json_decode($json);

        $docID = $obj->{'doc_id'};
        
        $response = array();
        $response['is_error'] = 0;

      
        $conditions = "GroupDoc.id = '".$docID."'"; 
        $GrpDocdetail = $this->GroupDoc->find('first',array('conditions' => $conditions)); 
         
        if(!empty($GrpDocdetail)){
        
                $folder='gallery/doc/';
                
                $this->removeFile($GrpDocdetail['GroupDoc']['docname'],$folder);
                
                $this->GroupDoc->id = $docID;
                $this->GroupDoc->delete();
         }         
        else{
             $response['is_error'] = 1;
             $response['err_msg'] = 'Document does not exist';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
     }
     
     
    function group_video_upload(){
        
        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $response['is_error'] = 0;
        $obj = json_decode($json);

         $group_id = $obj->{'group_id'};
         $youtube = $obj->{'youtube_link'};

         /*$group_id = '55';
         $youtube = 'https://www.youtube.com/watch?v=CdmN1B80EgM';*/

         
        if($youtube!='')
        {
            $regex_pattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";
                    $match;
                    
                    if(!preg_match($regex_pattern, $youtube, $match)){
                        
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Please provide correct youtube link';
                                              
                    }
                    else
                    {

                            
                            $queryString = parse_url($youtube, PHP_URL_QUERY);

                            parse_str($queryString, $params);

                            $v = $params['v'];  

                                                        
                            if(strlen($v)>0)
                            {
                                $thumbURL = "http://i3.ytimg.com/vi/$v/default.jpg";

                            }

                            $this->data['Video']['group_id'] =  $group_id;
                            $this->data['Video']['v_image'] = $thumbURL;
                            $this->data['Video']['video'] = $youtube;
                            $this->Video->create();
                            if($this->Video->save($this->data['Video']))
                            {
                                $response['is_error'] = 0;
                                $response['success_msg'] = 'Video uploaded successfully !!!';   
                               
                            }
                            else
                            {
                                $response['is_error'] = 1;
                                $response['error_msg'] = 'Video uploaded failed !!!'; 
                            }
                         
                        } 
           
                    }

        header('Content-type: application/json');
        echo json_encode($response);
        exit;
           
     }
        
        
    function get_group_videos()
    {
            //Configure::write('debug',3);
        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $response['is_error'] = 0;
        $obj = json_decode($json);

        $group_id = $obj->{'group_id'};
        // $group_id = '55';

          $condn = "Video.group_id = '".$group_id."' AND Video.status = '1'";
          $video_list = $this->Video->find('all',array('conditions'=>$condn));   
          if(!empty($video_list))
          {
            foreach($video_list as $key => $val)
            {   
              
                 $response['group_video'][$key]['id'] = $val['Video']['id'];
                 if($val['Video']['v_image']=='')
                 {
                    $response['group_video'][$key]['image'] = '';
                 }
                 else
                 {
                	$response['group_video'][$key]['image'] = $val['Video']['v_image'];
                 }
                 $response['group_video'][$key]['video'] = $val['Video']['video'];
                
                     
            }
          }
          else{
              $response['is_error'] = 1;
              $response['err_msg'] = 'No videos found';
          }

        header('Content-type: application/json');
        echo json_encode($response);
        exit;

    }
    
    
    function delete_group_video()
    {
    
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $response['is_error'] = 0;
        $obj = json_decode($json);

        $videoID = $obj->{'video_id'};
        
        $response = array();
        $response['is_error'] = 0;

      
        $conditions = "Video.id = '".$videoID."'"; 
        $GrpVideodetail = $this->Video->find('first',array('conditions' => $conditions)); 
         
        if(!empty($GrpVideodetail)){
        
                $this->Video->id = $videoID;
                $this->Video->delete();
         }         
        else{
             $response['is_error'] = 1;
             $response['err_msg'] = 'Video does not exist';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
     }
     
    
    function notification_list(){
       
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $user_id    = $obj->{'user_id'};
        //$user_id    = 6;
        
        $condition2 = "User.id = '".$user_id."' AND User.status = '1'";
                
        $is_user = $this->User->find('count',array('conditions'=>$condition2));  
        if( $is_user >0){

            $condition = "Notification.receiver_id = '".$user_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.message !='') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P') )) OR ((Notification.type = 'SG' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0') OR (Notification.type = 'SG' AND Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '1')))";
            $this->Notification->bindModel(array('belongsTo' => array(
                    'User' => array(
                    'className' => 'User','foreignKey' => 'sender_id',
                    'fields' => array('User.fname','User.lname','User.image')
                    )
            )));
            
            $this->Notification->bindModel(array('belongsTo' => array(
                    'Group' => array(
                    'className' => 'Group','foreignKey' => 'group_id',
                    'fields' => array('Group.group_title','Group.group_type')
                    )
            )));
            
            $this->Notification->bindModel(array('belongsTo' => array(
                    'Event' => array(
                    'className' => 'Event','foreignKey' => 'event_id',
                    'fields' => array('Event.id','Event.title','Event.is_multiple_date','Event.event_start_timestamp','Event.event_end_timestamp','Event.event_timestamp','Event.location')
                    )
            )));
            
            $notification_list = $this->Notification->find('all',array('conditions'=>$condition,'order'=>'Notification.id DESC'));  
            
            
            $notification = array(); 
            
            if(count($notification_list) > 0){ 
            
                foreach($notification_list as $noti){
                
                    $list['id'] = $noti['Notification']['id'];
                    $list['type'] =  $noti['Notification']['type'];
                    $list['group_id'] = $noti['Notification']['group_id'];
                    $list['group_name'] =  stripslashes($noti['Group']['group_title']);
                    $list['group_type'] =  $noti['Group']['group_type'];
                    $list['sender_id'] = $noti['Notification']['sender_id'];
                    $list['sender_name'] =  $noti['User']['fname'] .' '.$noti['User']['lname'] ;
                    if($noti['User']['image']!='')
                    {
                        $list['sender_image'] =  $base_url.'user_images/thumb/'.$noti['User']['image'];
                    }
                    else
                    {
                        $list['sender_image'] =  $base_url.'images/no_profile_img.jpg';
                    }
                    $list['sender_type'] =  $noti['Notification']['sender_type'];
                    $list['request_mode'] = $noti['Notification']['request_mode'];
                    $list['receiver_id'] =  $noti['Notification']['receiver_id'];
                    $list['receiver_type'] = $noti['Notification']['receiver_type'];
                    $list['is_read'] =  $noti['Notification']['is_read'];
                    $list['is_receiver_accepted'] = $noti['Notification']['is_receiver_accepted'];
                    $list['is_reversed_notification'] =  $noti['Notification']['is_reversed_notification'];
                    $list['message'] =  stripslashes($noti['Notification']['message']);
                    $list['created'] =  $noti['Notification']['created'];
                    $list['modified'] =  $noti['Notification']['modified'];
                    $now = strtotime(date('Y-m-d H:i:s'));
                    $post_date = strtotime($noti['Notification']['created']);
                    $comment_posted_time = $this->TimeCalculate($now,$post_date);
                    $list['time'] = $comment_posted_time;
                    
                    if($noti['Notification']['type']=='E' && $noti['Notification']['group_type']=='F'){
                    
                        $list['event_title'] =  stripslashes($noti['Event']['title']);    
                        $list['is_multiple_date'] =  $noti['Event']['is_multiple_date'];
                        
                        if($event_detail['Event']['is_multiple_date'] == '1' ) { 
                            $list['event_time'] =  date("F j, Y, g:i a",$noti['Event']['event_start_timestamp']) .'-'. date("F j, Y, g:i a",$noti['Event']['event_end_timestamp']);
                             } else { 
                            $list['event_time'] =   date("F j, Y, g:i a",$noti['Event']['event_timestamp']) ;
                        } 
;   
                        $list['location'] =  $noti['Event']['location'];    
                    }
                    
                    array_push($notification,$list);
                }
                
                $this->Notification->query("UPDATE `notifications` SET is_read ='1' WHERE receiver_id = '".$user_id."' AND status = '1' AND  ((is_reversed_notification = '0' AND is_receiver_accepted = '0' ) OR (is_reversed_notification = '1' AND is_receiver_accepted != '0') OR (is_receiver_accepted = '2' AND is_reversed_notification = '0' AND( (type = 'E') OR (type = 'P') OR (type='G' AND message!='')))) AND is_read='0'");
                
                $response['notification']= $notification; 
                $response['is_error'] = 0;
            }
              else
                   {
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'No notifications';
                   }
                
            }
            else
            {
                 $response['is_error'] = 1;
                 $response['err_msg'] = 'User does not exist';
            }

       
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function accept_group_request(){
    
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $notification_id = $obj->{'notification_id'};
        $group_title = $obj->{'group_title'};
        
        $response = array();
        $response['is_error'] = 0;
        
        $condition = "Notification.id = '".$notification_id."' AND Notification.type = 'G' AND Notification.status = '1'";
        $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition));  
        
        if($old_noti_detail['Notification']['is_receiver_accepted']=='0' && $old_noti_detail['Notification']['is_reversed_notification']=='0'){    
            $this->data['Notification']['is_read'] = '1';
            $this->data['Notification']['is_receiver_accepted'] = '2';
            $this->Notification->id = $notification_id;                 
            if($this->Notification->save($this->data))          // Update the Notification
            {
              
                /*********************   Update the notifications to other Admins starts    ******************/
                
                if($old_noti_detail['Notification']['sender_type']=='NGM' && $old_noti_detail['Notification']['receiver_type']=='GO'){

                
                $con_notification_other_admins = "Notification.type = 'G' AND Notification.group_id = '".$old_noti_detail['Notification']['group_id']."' AND Notification.group_type = '".$old_noti_detail['Notification']['group_type']."' AND Notification.sender_id = '".$old_noti_detail['Notification']['sender_id']."' AND Notification.sender_type = '".$old_noti_detail['Notification']['sender_type']."' AND Notification.request_mode = '".$old_noti_detail['Notification']['request_mode']."' AND Notification.receiver_type = '".$old_noti_detail['Notification']['receiver_type']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'"; 
                 
                  $arr_notification_other_admins = $this->Notification->find('all',array('conditions'=>$con_notification_other_admins));  
                  
                  if(!empty($arr_notification_other_admins)){
                        foreach($arr_notification_other_admins as $admins)
                        {
                            $this->Notification->id = $admins['Notification']['id'];
                            $this->Notification->is_read = '1';
                            $this->Notification->is_receiver_accepted = '2';
                            
                            $this->Notification->save($this->data);

                            $this->notification_email($admins['Notification']['id']);
                        }
                  }
                }
                /*********************   Update the notifications to other Admins ends    ******************/
                
                
                  $this->data['Notification']['type'] = 'G';
                  $this->data['Notification']['sender_id'] = $old_noti_detail['Notification']['receiver_id'];
                  $this->data['Notification']['sender_type'] = $old_noti_detail['Notification']['receiver_type'];
                  $this->data['Notification']['request_mode'] = $old_noti_detail['Notification']['request_mode'];
                  $this->data['Notification']['receiver_id'] = $old_noti_detail['Notification']['sender_id'];
                  $this->data['Notification']['receiver_type'] =  $old_noti_detail['Notification']['sender_type'];
                  $this->data['Notification']['group_type'] =  $old_noti_detail['Notification']['group_type'];
                  $this->data['Notification']['group_id'] = $old_noti_detail['Notification']['group_id'];
                  $this->data['Notification']['is_receiver_accepted'] = '2';
                  $this->data['Notification']['is_reversed_notification'] =  '1';
                  $this->data['Notification']['is_read'] =  '0';
                                            
               
                 $this->Notification->create();
                 if($this->Notification->save($this->data))
                 {
                      $last_insert_id = $this->Notification->getLastInsertId(); 

                      $this->notification_email($last_insert_id);  // sending email to notification sender
                      //pr($last_insert_id);exit();
                      $condition_lastNotification = "Notification.id = '".$last_insert_id."'";
                      $noti_detail = $this->Notification->find('first',array('conditions'=>$condition_lastNotification)); //Details of reversed notification
                      $page="Group request accepted";
                      $this->send_reverse_notification_group($noti_detail['Notification']['sender_id'], $noti_detail['Notification']['receiver_id'], $noti_detail['Notification']['group_id'], $noti_detail['Notification']['sender_type'], $noti_detail['Notification']['receiver_type'], 'Accept',$page);
                      
                      if($noti_detail['Notification']['sender_type']=='GO' && $noti_detail['Notification']['receiver_type']=='NGM'){
                      
					  	  $cond_grp = "Group.id = '".$noti_detail['Notification']['group_id']."'";
						  $grp_dtls = $this->Group->find('first',array('conditions'=>$cond_grp));
						  
						  if($grp_dtls['Group']['parent_id']!='0'){
						  
						  	  ######################    The user will become the member of the Sub Group starts      #####################	
							  
							  $this->data['GroupUser']['group_id'] = $noti_detail['Notification']['group_id']; 
							  $this->data['GroupUser']['user_type'] =  'M';
							  $this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['receiver_id'];
							  $this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
							  
							  $this->GroupUser->create();
							  
							  //////////////////////////   Insert to Groups field in User table starts      //////////////////////   
							  if($this->GroupUser->save($this->data))
							  { 
							  
									$user_id= $noti_detail['Notification']['receiver_id'];
									$cond_user = "User.id = '".$user_id."'";
									$user_details = $this->User->find('first',array('conditions'=>$cond_user)); 
		
									if($user_details['User']['groups']!=''){
		
										$this->data['User']['id'] = $user_details['User']['id'];
										$this->data['User']['groups'] = $user_details['User']['groups'].",".$noti_detail['Notification']['group_id'];
										$this->User->save($this->data['User']);
										
									}
									else
									{
										$this->data['User']['id'] = $user_details['User']['id'];
										$this->data['User']['groups'] = $noti_detail['Notification']['group_id'];
										$this->User->save($this->data['User']);
										
									}
								}
								
							  ######################    The user will become the member of the Sub Group ends      #####################	
							  
							  ##################   User will become the member of Parent Group, if he was not before starts    ###################
							  
							  $arr_user_grps= explode(',', $this->data['User']['groups']);
							  if(!in_array($grp_dtls['Group']['parent_id'], $arr_user_grps)){
							  
							  		$this->data['User']['id'] = $user_details['User']['id'];
									$this->data['User']['groups'] = $this->data['User']['groups'].",".$grp_dtls['Group']['parent_id'];
									$this->User->save($this->data['User']);	
									
									$this->data['GroupUser']['group_id'] = $grp_dtls['Group']['parent_id']; 
									$this->data['GroupUser']['user_type'] =  'M';
									$this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['receiver_id'];
									$this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
										  
									$this->GroupUser->create();
									$this->GroupUser->save($this->data);
										
							  }
							  
							  ##################   User will become the member of Parent Group, if he was not before ends    ###################
							  
							  $response['success_msg'] = 'You have added a member to the Group '.$group_title; 
						  }
						  else{
							  $this->data['GroupUser']['group_id'] = $noti_detail['Notification']['group_id']; 
							  $this->data['GroupUser']['user_type'] =  'M';
							  $this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['receiver_id'];
							  $this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
							  
							  $this->GroupUser->create();
							  
							  //////////////////////////   Insert to Groups field in User table starts      //////////////////////   
							  if($this->GroupUser->save($this->data))
							  { 
							  
									$user_id= $noti_detail['Notification']['receiver_id'];
									$cond_user = "User.id = '".$user_id."'";
									$user_details = $this->User->find('first',array('conditions'=>$cond_user)); 
		
									if($user_details['User']['groups']!=''){
		
										$this->data['User']['id'] = $user_details['User']['id'];
										$this->data['User']['groups'] = $user_details['User']['groups'].",".$noti_detail['Notification']['group_id'];
										$this->User->save($this->data['User']);
										
										$response['success_msg'] = 'You have added a member to the Group '.$group_title; 
									}
									else
									{
										$this->data['User']['id'] = $user_details['User']['id'];
										$this->data['User']['groups'] = $noti_detail['Notification']['group_id'];
										$this->User->save($this->data['User']);
										
										$response['success_msg'] = 'You have added a member to the Group '.$group_title; 
									}
								}
								
							  //////////////////////////   Insert to Groups field in User table ends      //////////////////////   
						  }
                      }
                      else if($noti_detail['Notification']['sender_type']=='NGM' && $noti_detail['Notification']['receiver_type']=='GO'){
					  
					  	  $cond_grp = "Group.id = '".$noti_detail['Notification']['group_id']."'";
						  $grp_dtls = $this->Group->find('first',array('conditions'=>$cond_grp));
						  
						  if($grp_dtls['Group']['parent_id']!='0'){
						  
						  	  ######################    The user will become the member of the Sub Group starts      #####################	
						  	  $this->data['GroupUser']['group_id'] = $noti_detail['Notification']['group_id']; 
							  $this->data['GroupUser']['user_type'] =  'M';
							  $this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['sender_id'];
							  $this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
							  
							  $this->GroupUser->create();
							  
							  if($this->GroupUser->save($this->data))
							  { 
							  
									$user_id= $noti_detail['Notification']['sender_id'];
									$cond_user = "User.id = '".$user_id."'";
									$user_details = $this->User->find('first',array('conditions'=>$cond_user)); 
		
									if($user_details['User']['groups']!=''){
		
										$this->data['User']['id'] = $user_details['User']['id'];
										$this->data['User']['groups'] = $user_details['User']['groups'].",".$noti_detail['Notification']['group_id'];
										$this->User->save($this->data['User']);
									}
									else
									{
										$this->data['User']['id'] = $user_details['User']['id'];
										$this->data['User']['groups'] = $noti_detail['Notification']['group_id'];
										$this->User->save($this->data['User']);
									}
								}
								
							  ######################    The user will become the member of the Sub Group ends      #####################	
							  
							  ##################   User will become the member of Parent Group, if he was not before starts    ###################
							  
							  $arr_user_grps= explode(',', $this->data['User']['groups']);
							  if(!in_array($grp_dtls['Group']['parent_id'], $arr_user_grps)){
							  
							  		$this->data['User']['id'] = $user_details['User']['id'];
									$this->data['User']['groups'] = $this->data['User']['groups'].",".$grp_dtls['Group']['parent_id'];
									$this->User->save($this->data['User']);	
									
									  $this->data['GroupUser']['group_id'] = $grp_dtls['Group']['parent_id']; 
									  $this->data['GroupUser']['user_type'] =  'M';
									  $this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['sender_id'];
									  $this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
										  
									  $this->GroupUser->create();
									  $this->GroupUser->save($this->data);
							  }
							  
							  
							  
							  ##################   User will become the member of Parent Group, if he was not before ends    ###################
							  
							  $response['success_msg'] = 'You are now the member of the Group '.$group_title;
						  }
						  else{
                      
							  $this->data['GroupUser']['group_id'] = $noti_detail['Notification']['group_id']; 
							  $this->data['GroupUser']['user_type'] =  'M';
							  $this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['sender_id'];
							  $this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
							  
							  $this->GroupUser->create();
							  
							  //////////////////////////   Insert to Groups field in User table starts      //////////////////////   
							  if($this->GroupUser->save($this->data))
							  { 
							  
									$user_id= $noti_detail['Notification']['sender_id'];
									$cond_user = "User.id = '".$user_id."'";
									$user_details = $this->User->find('first',array('conditions'=>$cond_user)); 
		
									if($user_details['User']['groups']!=''){
		
										$this->data['User']['id'] = $user_details['User']['id'];
										$this->data['User']['groups'] = $user_details['User']['groups'].",".$noti_detail['Notification']['group_id'];
										$this->User->save($this->data['User']);
										
										$response['success_msg'] = 'You are now the member of the Group '.$group_title;   
									}
									else
									{
										$this->data['User']['id'] = $user_details['User']['id'];
										$this->data['User']['groups'] = $noti_detail['Notification']['group_id'];
										$this->User->save($this->data['User']);
										
										$response['success_msg'] = 'You are now the member of the Group '.$group_title;
									}
								}
								
							  //////////////////////////   Insert to Groups field in User table ends      //////////////////////  
						  }
                      }
                      else if($noti_detail['Notification']['sender_type']=='NGM' && $noti_detail['Notification']['receiver_type']=='GM'){
                            
                              $cond_groupAdmins = "Group.id = '".$noti_detail['Notification']['group_id']."'";
                              $group_detail = $this->Group->find('first',array('conditions'=>$cond_groupAdmins, 'fields'=>'Group.group_owners, Group.group_type'));
                              
                              if(!empty($group_detail)){
                                
                                  if($group_detail['Group']['group_type']=='B'){    //For Business Groups, the NGM will become member directly
                                        
                                          $this->data['GroupUser']['group_id'] = $noti_detail['Notification']['group_id']; 
                                          $this->data['GroupUser']['user_type'] =  'M';
                                          $this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['sender_id'];
                                          $this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
                                          
                                          $this->GroupUser->create();
                                          
                                           //////////////////////////   Insert to Groups field in User table starts      //////////////////////   
                                          if($this->GroupUser->save($this->data))
                                          { 
                                          
                                                $user_id= $noti_detail['Notification']['sender_id'];
                                                $cond_user = "User.id = '".$user_id."'";
                                                $user_details = $this->User->find('first',array('conditions'=>$cond_user)); 
                    
                                                if($user_details['User']['groups']!=''){
                    
                                                    $this->data['User']['id'] = $user_details['User']['id'];
                                                    $this->data['User']['groups'] = $user_details['User']['groups'].",".$noti_detail['Notification']['group_id'];
                                                    $this->User->save($this->data['User']);
                                                    
                                                    $response['success_msg'] = 'You are now the member of the Group '.$group_title;
                                                }
                                                else
                                                {
                                                    $this->data['User']['id'] = $user_details['User']['id'];
                                                    $this->data['User']['groups'] = $noti_detail['Notification']['group_id'];
                                                    $this->User->save($this->data['User']);
                                                    
                                                    $response['success_msg'] = 'You are now the member of the Group '.$group_title;
                                                }
                                            }
                                            
                                          //////////////////////////   Insert to Groups field in User table ends      //////////////////////  
                                  }
                                  else{                     //For Free Groups, the request will be sent from NGM to all GO
                                  $arr_group_owners= explode(',', $group_detail['Group']['group_owners']);
                                  
                                  for($i=0; $i<count($arr_group_owners); $i++){
                                      $this->data['Notification']['type'] = 'G';
                                      $this->data['Notification']['sender_id'] = $noti_detail['Notification']['sender_id'];
                                      $this->data['Notification']['sender_type'] = $noti_detail['Notification']['sender_type'];
                                      $this->data['Notification']['request_mode'] = $noti_detail['Notification']['request_mode'];
                                      $this->data['Notification']['receiver_id'] = $arr_group_owners[$i];
                                      $this->data['Notification']['receiver_type'] =  'GO';
                                      $this->data['Notification']['group_type'] =  $noti_detail['Notification']['group_type'];
                                      $this->data['Notification']['group_id'] = $noti_detail['Notification']['group_id'];
                                      $this->data['Notification']['is_receiver_accepted'] = '0';
                                      $this->data['Notification']['is_reversed_notification'] =  '0';
                                      $this->data['Notification']['is_read'] =  '0';    
                                  
                                      $this->Notification->create();
                                      $this->Notification->save($this->data);
                                      
                                      $last_insert_noti_id = $this->Notification->getLastInsertId(); 

                                      $this->notification_email($last_insert_noti_id);  // sending email to notification sender
                                      
                                      $page="Group Joining Request Via Recommendation";
                                      $this->send_notification_group_joining_request($noti_detail['Notification']['sender_id'], $arr_group_owners[$i], $group_id, $page);
                                      
                                  }
                                    $response['success_msg'] = 'Your request to join the Group '.$group_title.' has been sent to Admin for approval';
                                  }
                              
                              }     
                      }
                      else{
                          $response['is_error'] = 0;
                          $response['err_msg'] = 'Error occured !!!';
                      }
                  }
            }
            else{
                $response['is_error'] = 0;
                $response['err_msg'] = 'Error occured to read the notification !!!';
            } 
        }
        else{
            if($old_noti_detail['Notification']['is_receiver_accepted']=='1'){
                $response['is_error'] = 1;
                $response['err_msg'] = 'Request has been rejected already !!!';
            }
            else if($old_noti_detail['Notification']['is_receiver_accepted']=='2'){
                $response['is_error'] = 1;
                $response['err_msg'] = 'Request has been accepted already !!!';
            }   
        }  
    
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function reject_group_request(){
       
            $this->layout = ""; 
        
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            $notification_id = $obj->{'notification_id'};
            $group_title = $obj->{'group_title'};
            
            $response = array();
            $response['is_error'] = 0;
            

            $condition1 = "Notification.id = '".$notification_id."' AND Notification.type = 'G' AND Notification.status = '1'";
            $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition1));  

            if($old_noti_detail['Notification']['is_receiver_accepted']=='0' && $old_noti_detail['Notification']['is_reversed_notification']=='0'){
            
                $this->data['Notification']['is_read'] = '1';
                $this->data['Notification']['is_receiver_accepted'] = '1';
                $this->Notification->id = $notification_id;     
                            
                if($this->Notification->save($this->data)){

                   
                     /*********************   Update the notifications to other Admins starts    ******************/
                     
                     if($old_noti_detail['Notification']['sender_type']=='NGM' && $old_noti_detail['Notification']['receiver_type']=='GO'){
                    
                     $con_notification_other_admins = "Notification.type = 'G' AND Notification.group_id = '".$old_noti_detail['Notification']['group_id']."' AND Notification.group_type = '".$old_noti_detail['Notification']['group_type']."' AND Notification.sender_id = '".$old_noti_detail['Notification']['sender_id']."' AND Notification.sender_type = '".$old_noti_detail['Notification']['sender_type']."' AND Notification.request_mode = '".$old_noti_detail['Notification']['request_mode']."' AND Notification.receiver_type = '".$old_noti_detail['Notification']['receiver_type']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";
                     
                      $arr_notification_other_admins = $this->Notification->find('all',array('conditions'=>$con_notification_other_admins));  
                      
                      if(!empty($arr_notification_other_admins)){
                            foreach($arr_notification_other_admins as $admins)
                            {
                                $this->Notification->id = $admins['Notification']['id'];
                                $this->Notification->is_read = '1';
                                $this->Notification->is_receiver_accepted = '1';
                                
                                $this->Notification->save($this->data);

                               // $this->notification_email($admins['Notification']['id']);

                            }
                      }
                    }
                    /*********************   Update the notifications to other Admins ends    ******************/
                
                      $this->data['Notification']['type'] = 'G';
                      $this->data['Notification']['sender_id'] = $old_noti_detail['Notification']['receiver_id'];
                      $this->data['Notification']['sender_type'] = $old_noti_detail['Notification']['receiver_type'];
                      $this->data['Notification']['request_mode'] = $old_noti_detail['Notification']['request_mode'];
                      $this->data['Notification']['receiver_id'] = $old_noti_detail['Notification']['sender_id'];
                      $this->data['Notification']['receiver_type'] =  $old_noti_detail['Notification']['sender_type'];
                      $this->data['Notification']['group_type'] =  $old_noti_detail['Notification']['group_type'];
                      $this->data['Notification']['group_id'] = $old_noti_detail['Notification']['group_id'];
                      $this->data['Notification']['is_receiver_accepted'] = '1';
                      $this->data['Notification']['is_reversed_notification'] =  '1';
                      $this->data['Notification']['is_read'] =  '0';
                                                
                   
                     $this->Notification->create();
                     if($this->Notification->save($this->data)){

                         $last_insert_id = $this->Notification->getLastInsertId(); 
                         $this->notification_email($last_insert_id);  
                         
                         $page="Group request rejected";
                         $this->send_reverse_notification_group($old_noti_detail['Notification']['receiver_id'], $old_noti_detail['Notification']['sender_id'], $old_noti_detail['Notification']['group_id'], $old_noti_detail['Notification']['receiver_type'], $old_noti_detail['Notification']['sender_type'], 'Reject',$page);
                            
                         $response['success_msg'] = 'You have rejected to join the Group '.$group_title;
                      }
                      else{
                      
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Error Occured to reject the request !!!';                    }
                      }
            }
            else{
                if($old_noti_detail['Notification']['is_receiver_accepted']=='1'){
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Request has been rejected already !!!';
                }
                else if($old_noti_detail['Notification']['is_receiver_accepted']=='2'){
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Request has been accepted already !!!';
                }   
            }
            
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function remove_notification(){
   
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $notification_id = $obj->{'notification_id'};
        
        $response = array();
        $response['is_error'] = 0;
        
       
        $this->data['Notification']['status'] = '0';
        $this->data['Notification']['is_read'] = '1';
           
        $this->Notification->id = $notification_id;                 
        if($this->Notification->save($this->data)){
            $response['success_msg'] = 'You have successfully removed the request';
        }
        else{
            echo '0';
            $response['err_msg'] = 'Error occured to remove request !!!';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;

    }
	
	
	function accept_subgroup_request(){
    
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $notification_id = $obj->{'notification_id'};
        $group_title = $obj->{'group_title'};
        
        $response = array();
        $response['is_error'] = 0;
        
        $condition = "Notification.id = '".$notification_id."' AND Notification.type = 'SG' AND Notification.status = '1'";
        $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition)); 
        
        if($old_noti_detail['Notification']['is_receiver_accepted']=='0' && $old_noti_detail['Notification']['is_reversed_notification']=='0'){    
			$this->data['Notification']['is_read'] = '1';
			$this->data['Notification']['is_receiver_accepted'] = '2';
			$this->Notification->id = $notification_id;                 
			if($this->Notification->save($this->data))			// Update the Notification
			{
				/*-------------------Parent Group owner who accepts the request has become the member starts----------------------*/   
				
                $this->data['GroupUser']['group_id'] = $old_noti_detail['Notification']['group_id']; 
				$this->data['GroupUser']['user_type'] =  'M';
				$this->data['GroupUser']['user_id'] =  $old_noti_detail['Notification']['receiver_id'];				
				$this->data['GroupUser']['member_mode'] =  $old_noti_detail['Notification']['request_mode'];
			  
				$this->GroupUser->create();
				$this->GroupUser->save($this->data);
				
				$parent_owner_id= $old_noti_detail['Notification']['receiver_id'];
				$cond_parent_owner = "User.id = '".$parent_owner_id."'";
				$parent_owner_details = $this->User->find('first',array('conditions'=>$cond_parent_owner)); 
				
				if($parent_owner_details['User']['groups']!=''){
	
					$this->data['User']['id'] = $parent_owner_details['User']['id'];
					$this->data['User']['groups'] = $parent_owner_details['User']['groups'].",".$old_noti_detail['Notification']['group_id'];
					$this->User->save($this->data['User']);
					
				}
				else
				{
					$this->data['User']['id'] = $parent_owner_details['User']['id'];
					$this->data['User']['groups'] = $old_noti_detail['Notification']['group_id'];
					$this->User->save($this->data['User']);
					
				}
				
				###################      Save the Group Id to group_owners field into Group Table starts         ###################
				
				/*$con_grp = "Group.id = '".$old_noti_detail['Notification']['group_id']."'"; 
				$arr_grp_detail = $this->Group->find('first',array('conditions'=>$con_grp));  
				$str_grp_owners= $arr_grp_detail['Group']['group_owners'];
				$arr_grp_owners= explode(',',$str_grp_owners);
				if(!in_array($old_noti_detail['Notification']['receiver_id'], $arr_grp_owners)){
					array_push($arr_grp_owners, $old_noti_detail['Notification']['receiver_id']);
				}
				$all_grp_owners= implode(',', $arr_grp_owners);
				
				$this->data['Group']['id'] = $old_noti_detail['Notification']['group_id'];
			    $this->data['Group']['group_owners'] = $all_grp_owners;
				$this->Group->save($this->data);*/
				
				###################      Save the Group Id to group_owners field into Group Table ends         ###################
				  
				
                /*-------------------Parent Group owner who accepts the request has become the owner ends----------------------*/ 
				
				
				/*********************   Update the notifications to other Admins starts    ******************/
				
			if($old_noti_detail['Notification']['sender_type']=='GO' && $old_noti_detail['Notification']['receiver_type']=='NGM'){

				
				$con_notification_other_admins = "Notification.type = 'SG' AND Notification.group_id = '".$old_noti_detail['Notification']['group_id']."' AND Notification.group_type = '".$old_noti_detail['Notification']['group_type']."' AND Notification.sender_id = '".$old_noti_detail['Notification']['sender_id']."' AND Notification.sender_type = '".$old_noti_detail['Notification']['sender_type']."' AND Notification.request_mode = '".$old_noti_detail['Notification']['request_mode']."' AND Notification.receiver_type = '".$old_noti_detail['Notification']['receiver_type']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'"; 
				 
				  $arr_notification_other_admins = $this->Notification->find('all',array('conditions'=>$con_notification_other_admins));  
				  
				  
				  if(!empty($arr_notification_other_admins)){
						foreach($arr_notification_other_admins as $admins)
						{
							$this->Notification->id = $admins['Notification']['id'];
							$this->Notification->is_read = '1';
							$this->Notification->is_receiver_accepted = '2';
							
							$this->Notification->save($this->data);
							
							$this->data['GroupUser']['group_id'] = $admins['Notification']['group_id']; 
							$this->data['GroupUser']['user_type'] =  'M';
							$this->data['GroupUser']['user_id'] =  $admins['Notification']['receiver_id'];		// All the other Parent Group Owners have become memeber . Bcoz, one parent Grou Owner already accepted the sub Group Request.
							$this->data['GroupUser']['member_mode'] =  $admins['Notification']['request_mode'];
						  
							$this->GroupUser->create();
							$this->GroupUser->save($this->data);
							
							/*******************************       save the group_id into users table starts         ***************************/
							
							$member_id= $admins['Notification']['receiver_id'];
							$cond_member = "User.id = '".$member_id."'";
							$member_details = $this->User->find('first',array('conditions'=>$cond_member)); 
							
							if($member_details['User']['groups']!=''){
	
								$this->data['User']['id'] = $member_details['User']['id'];
								$this->data['User']['groups'] = $member_details['User']['groups'].",".$admins['Notification']['group_id'];
								$this->User->save($this->data['User']);
								
							}
							else
							{
								$this->data['User']['id'] = $member_details['User']['id'];
								$this->data['User']['groups'] = $admins['Notification']['group_id'];
								$this->User->save($this->data['User']);
								
							}
							/*******************************       save the group_id into users table ends         ***************************/
							
							
							###################      Save the Group Id to group_owners field into Group Table starts         ###################
				
							/*$con_grp = "Group.id = '".$admins['Notification']['group_id']."'"; 
							$arr_grp_detail = $this->Group->find('first',array('conditions'=>$con_grp));  
							$str_grp_owners= $arr_grp_detail['Group']['group_owners'];
							$arr_grp_owners= explode(',',$str_grp_owners);
							if(!in_array($admins['Notification']['receiver_id'], $arr_grp_owners)){
								array_push($arr_grp_owners, $admins['Notification']['receiver_id']);
							}
							$all_grp_owners= implode(',', $arr_grp_owners);
							
							$this->data['Group']['id'] = $admins['Notification']['group_id'];
							$this->data['Group']['group_owners'] = $all_grp_owners;
							$this->Group->save($this->data);*/
							
							###################      Save the Group Id to group_owners field into Group Table ends         ###################
							

                            //$this->notification_email($admins['Notification']['id']);
							
							
						}
				  }
				}
				/*********************	 Update the notifications to other Admins ends    ******************/
				
				/*********************    Reverse notification starts    *****************/
				
				  $this->data['Notification']['type'] = 'SG';
				  $this->data['Notification']['sender_id'] = $old_noti_detail['Notification']['receiver_id'];
				  $this->data['Notification']['sender_type'] = $old_noti_detail['Notification']['receiver_type'];
				  $this->data['Notification']['request_mode'] = $old_noti_detail['Notification']['request_mode'];
				  $this->data['Notification']['receiver_id'] = $old_noti_detail['Notification']['sender_id'];
				  $this->data['Notification']['receiver_type'] =  $old_noti_detail['Notification']['sender_type'];
				  $this->data['Notification']['group_type'] =  $old_noti_detail['Notification']['group_type'];
				  $this->data['Notification']['group_id'] = $old_noti_detail['Notification']['group_id'];
				  $this->data['Notification']['is_receiver_accepted'] = '2';
				  $this->data['Notification']['is_reversed_notification'] =  '1';
				  $this->data['Notification']['is_read'] =  '0';
				  
				/*********************    Reverse notification ends    *****************/
											
				 $this->Notification->create();
				 if($this->Notification->save($this->data))
				 {
					  $last_insert_id = $this->Notification->getLastInsertId(); 

                      $this->notification_email($last_insert_id);  // sending email to notification sender
					  //pr($last_insert_id);exit();
					  $page="Sub Group creation request accepted";
					  
					  $condition_lastNotification = "Notification.id = '".$last_insert_id."'";
					  $noti_detail = $this->Notification->find('first',array('conditions'=>$condition_lastNotification)); //Details of reversed notification
					  $this->reverse_notification_sub_group_request($noti_detail['Notification']['sender_id'], $noti_detail['Notification']['receiver_id'], $noti_detail['Notification']['group_id'], $noti_detail['Notification']['sender_type'], $noti_detail['Notification']['receiver_type'], 'Accept',$page);
					  
					  if($noti_detail['Notification']['sender_type']=='NGM' && $noti_detail['Notification']['receiver_type']=='GO'){
					  
					  	  ##########################     Sub Group creator becomes the member of the Parent Group starts       #########################
					  
					  	  $con_grp_dtls= "Group.id = '".$noti_detail['Notification']['group_id']."'";
						  $grp_detail = $this->Group->find('first',array('conditions'=>$con_grp_dtls));
						  $parent_grp_id= $grp_detail['Group']['parent_id'];
						  
						  $con_parent_grp_user_relation= "GroupUser.group_id = '".$parent_grp_id."' AND GroupUser.user_id = '".$noti_detail['Notification']['receiver_id']."' AND GroupUser.user_type = 'M'";
						  $arr_parent_grp_user_relation= $this->GroupUser->find('first',array('conditions'=>$con_parent_grp_user_relation));
						  if(empty($arr_parent_grp_user_relation)){
						  		
								$this->data['GroupUser']['group_id'] = $parent_grp_id; 
								$this->data['GroupUser']['user_type'] =  'M';
								$this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['receiver_id'];
								$this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
								
								$this->GroupUser->create();
								if($this->GroupUser->save($this->data)){
									
									$cond_user_grps = "User.id = '".$noti_detail['Notification']['receiver_id']."'";
									$arr_user_grps = $this->User->find('first',array('conditions'=>$cond_user_grps)); 
									
									if($arr_user_grps['User']['groups']!=''){
	
										$this->data['User']['id'] = $arr_user_grps['User']['id'];
										$this->data['User']['groups'] = $arr_user_grps['User']['groups'].",".$parent_grp_id;
										$this->User->save($this->data['User']);
										  
									}
									else
									{
										$this->data['User']['id'] = $arr_user_grps['User']['id'];
										$this->data['User']['groups'] = $parent_grp_id;
										$this->User->save($this->data['User']);
										
									}
								}
						  }
						  
					  	  ##########################     Sub Group creator becomes the member of the Parent Group ends       #########################
					  
					  
					  
					  
					  
					  
					  
					  
					  
						  $this->data['GroupUser']['group_id'] = $noti_detail['Notification']['group_id']; 
						  $this->data['GroupUser']['user_type'] =  'O';
						  $this->data['GroupUser']['user_id'] =  $noti_detail['Notification']['receiver_id'];
						  $this->data['GroupUser']['member_mode'] =  $noti_detail['Notification']['request_mode'];
						  
						  $this->GroupUser->create();
						  
						  //////////////////////////   Insert to Groups field in User table starts      //////////////////////   
						  if($this->GroupUser->save($this->data))
						  { 
						  		/**************   update the Group table starts   ******************/
						  		$this->data['Group']['id'] = $noti_detail['Notification']['group_id'];
								$this->data['Group']['status'] = '1';
								$this->Group->save($this->data['Group']);
								/**************   update the Group table ends   ******************/
						  
								$user_id= $noti_detail['Notification']['receiver_id'];
								$cond_user = "User.id = '".$user_id."'";
								$user_details = $this->User->find('first',array('conditions'=>$cond_user)); 

								if($user_details['User']['groups']!=''){
	
									$this->data['User']['id'] = $user_details['User']['id'];
									$this->data['User']['groups'] = $user_details['User']['groups'].",".$noti_detail['Notification']['group_id'];
									$this->User->save($this->data['User']);
									
									$response['success_msg'] = 'You are now the member of the Group '.$group_title;   
								}
								else
								{
									$this->data['User']['id'] = $user_details['User']['id'];
									$this->data['User']['groups'] = $noti_detail['Notification']['group_id'];
									$this->User->save($this->data['User']);
									
									$response['success_msg'] = 'You are now the member of the Group '.$group_title;
								}
							}
							
						  //////////////////////////   Insert to Groups field in User table ends      //////////////////////   
					  }
					  else{
						 	$response['is_error'] = 0;
                          	$response['err_msg'] = 'Error occured !!!';

					  }
				  }
			  }
			else{
				$response['is_error'] = 0;
                $response['err_msg'] = 'Error occured to read the notification !!!';
			} 
		}
        else{
            if($old_noti_detail['Notification']['is_receiver_accepted']=='1'){
                $response['is_error'] = 1;
                $response['err_msg'] = 'Request has been rejected already !!!';
            }
            else if($old_noti_detail['Notification']['is_receiver_accepted']=='2'){
                $response['is_error'] = 1;
                $response['err_msg'] = 'Request has been accepted already !!!';
            }   
        }  
    
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
	
	
	function reject_subgroup_request(){
       
            $this->layout = ""; 
        
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            $notification_id = $obj->{'notification_id'};
            $group_title = $obj->{'group_title'};
            
            $response = array();
            $response['is_error'] = 0;
            

            $condition1 = "Notification.id = '".$notification_id."' AND Notification.type = 'SG' AND Notification.status = '1'";
			$old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition1));  

            if($old_noti_detail['Notification']['is_receiver_accepted']=='0' && $old_noti_detail['Notification']['is_reversed_notification']=='0'){
			
				$this->data['Notification']['is_read'] = '1';
				$this->data['Notification']['is_receiver_accepted'] = '1';
				$this->Notification->id = $notification_id;     
				            
				if($this->Notification->save($this->data)){

                   
				    

					 /*********************   Update the notifications to other Admins starts    ******************/
					 
					 if($old_noti_detail['Notification']['sender_type']=='GO' && $old_noti_detail['Notification']['receiver_type']=='NGM'){
					
					 $con_notification_other_admins = "Notification.type = 'SG' AND Notification.group_id = '".$old_noti_detail['Notification']['group_id']."' AND Notification.group_type = '".$old_noti_detail['Notification']['group_type']."' AND Notification.sender_id = '".$old_noti_detail['Notification']['sender_id']."' AND Notification.sender_type = '".$old_noti_detail['Notification']['sender_type']."' AND Notification.request_mode = '".$old_noti_detail['Notification']['request_mode']."' AND Notification.receiver_type = '".$old_noti_detail['Notification']['receiver_type']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";
					 
					  $arr_notification_other_admins = $this->Notification->find('all',array('conditions'=>$con_notification_other_admins));  
					  
					  if(!empty($arr_notification_other_admins)){
					  		foreach($arr_notification_other_admins as $admins)
							{
								$this->Notification->id = $admins['Notification']['id'];
								$this->Notification->is_read = '1';
								$this->Notification->is_receiver_accepted = '1';
								
								$this->Notification->save($this->data);

                               // $this->notification_email($admins['Notification']['id']);

							}
					  }
					}
					/*********************	 Update the notifications to other Admins ends    ******************/
				
					  $this->data['Notification']['type'] = 'SG';
					  $this->data['Notification']['sender_id'] = $old_noti_detail['Notification']['receiver_id'];
					  $this->data['Notification']['sender_type'] = $old_noti_detail['Notification']['receiver_type'];
					  $this->data['Notification']['request_mode'] = $old_noti_detail['Notification']['request_mode'];
					  $this->data['Notification']['receiver_id'] = $old_noti_detail['Notification']['sender_id'];
					  $this->data['Notification']['receiver_type'] =  $old_noti_detail['Notification']['sender_type'];
					  $this->data['Notification']['group_type'] =  $old_noti_detail['Notification']['group_type'];
					  $this->data['Notification']['group_id'] = $old_noti_detail['Notification']['group_id'];
					  $this->data['Notification']['is_receiver_accepted'] = '1';
					  $this->data['Notification']['is_reversed_notification'] =  '1';
					  $this->data['Notification']['is_read'] =  '0';
												
				   
					 $this->Notification->create();
					 if($this->Notification->save($this->data))
						{

                         $last_insert_id = $this->Notification->getLastInsertId(); 
                         $this->notification_email($last_insert_id);  
						 
						 $page="Sub Group creation request rejected";
						 $condition_lastNotification = "Notification.id = '".$last_insert_id."'";
					     $noti_detail = $this->Notification->find('first',array('conditions'=>$condition_lastNotification)); //Details of reversed notification
					     $this->reverse_notification_sub_group_request($noti_detail['Notification']['sender_id'], $noti_detail['Notification']['receiver_id'], $noti_detail['Notification']['group_id'], $noti_detail['Notification']['sender_type'], $noti_detail['Notification']['receiver_type'], 'Reject',$page);
                            
						  $response['success_msg'] = 'You have rejected to join the Group '.$group_title;
					  }
					  else
					 {
					  	$response['is_error'] = 1;
                        $response['err_msg'] = 'Error Occured to reject the request !!!';
					 }
				}
			}
            else{
                if($old_noti_detail['Notification']['is_receiver_accepted']=='1'){
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Request has been rejected already !!!';
                }
                else if($old_noti_detail['Notification']['is_receiver_accepted']=='2'){
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Request has been accepted already !!!';
                }   
            }
            
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function send_friend_request(){
        
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $receiver_id = $obj->{'receiver_id'};
        $user_id = $obj->{'user_id'};
		
		//$receiver_id = 7;
        //$user_id = 198;
        
        $response = array();
        $response['is_error'] = 0;
        
        $condition_chk_req_sent = "((Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '". $receiver_id."') OR (Notification.sender_id = '".$receiver_id."' AND Notification.receiver_id = '". $user_id."')) AND Notification.type = 'F' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";
        $is_request_sent = $this->Notification->find('count',array('conditions'=>$condition_chk_req_sent));  
        
        if($is_request_sent > 0){

              $response['is_error'] = 1;
              $response['err_msg'] = 'You have already sent / received a request ';
        }
        else{
         
              $condition8 = "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$user_id."' AND Friendlist.receiver_id = '".$receiver_id."')OR(Friendlist.sender_id = '".$receiver_id."' AND Friendlist.receiver_id = '".$user_id."'))";  // fetch the friends who belongs to the group
              $is_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));

              if($is_friend > 0){
                  $response['is_error'] = 1;
                  $response['err_msg'] = 'You both are already friends';
              }
              else{
             
                  $this->data['Notification']['type'] = 'F';
                  $this->data['Notification']['sender_id'] = $user_id;
                  $this->data['Notification']['receiver_id'] = $receiver_id;
                                      
                  $this->Notification->create();
                  if($this->Notification->save($this->data)){
    
                        // send notification email for friend request
                        $notification_id = $this->Notification->getLastInsertId();  
                        $this->notification_email_friend($notification_id);
                        
                        $page="Friend request";
                        $this->send_push_notification_friend_request($user_id, $receiver_id, 'sending_request', $page);
              
                        $response['success_msg'] = 'Friend request sent successfully';
                  }
                  else{
                      $response['is_error'] = 1;
                      $response['err_msg'] = 'Error occured to send friend request';
                  }
              }
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function add_friend_request_again(){
        
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $receiver_id = $obj->{'receiver_id'};
        $user_id = $obj->{'user_id'};
        
        $response = array();
        $response['is_error'] = 0;
        
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
            $this->notification_email_friend($notification_id);
            
            $page="Friend request";
            $this->send_push_notification_friend_request($user_id, $receiver_id, 'sending_request', $page);
  
            $response['success_msg'] = 'Friend request sent successfully';


            }
        }
        else{
            $response['is_error'] = 1;
            $response['err_msg'] = 'Error occured to send friend request';
        } 
        
        
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function accept_friend_request(){
       
        $this->layout = ""; 
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $notification_id = $obj->{'notification_id'};
        $user_id = $obj->{'user_id'};
        
        $response = array();
        $response['is_error'] = 0;
            
        $condition = "Notification.id = '".$notification_id."' AND Notification.type = 'F' AND Notification.status = '1'";
        $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition));  
        if(!empty($old_noti_detail)){
    
            $this->data['Notification']['is_read'] = '1';
            $this->data['Notification']['is_receiver_accepted'] = '2';
                
            $this->Notification->id = $notification_id;                 
            if($this->Notification->save($this->data)){
            
                  $this->data['Notification']['type'] = 'F';
                  $this->data['Notification']['sender_id'] = $old_noti_detail['Notification']['receiver_id'];
                  $this->data['Notification']['request_mode'] = $old_noti_detail['Notification']['request_mode'];
                  $this->data['Notification']['receiver_id'] = $old_noti_detail['Notification']['sender_id'];
                  $this->data['Notification']['group_id'] = $old_noti_detail['Notification']['group_id'];
                  $this->data['Notification']['is_receiver_accepted'] = '2';
                  $this->data['Notification']['is_reversed_notification'] =  '1';
                  $this->data['Notification']['is_read'] =  '0';
                                            
               
                  $this->Notification->create();
                  if($this->Notification->save($this->data)){
                  
                      $last_insert_id = $this->Notification->getLastInsertId();     // send notification email for friend request accept
                       
                      $this->notification_email_friend($last_insert_id);
                  
                      $condition_last = "Notification.id = '".$last_insert_id."'";
                      $noti_detail = $this->Notification->find('first',array('conditions'=>$condition_last)); 
                      
                      $condition7 = "User.id = '".$noti_detail['Notification']['sender_id']."'";
                      $user_details = $this->User->find('first',array('conditions'=>$condition7)); 
                      
                      $this->data['Friendlist']['sender_id'] = $noti_detail['Notification']['receiver_id'];
                      $this->data['Friendlist']['receiver_id'] =  $noti_detail['Notification']['sender_id'];
                      $this->data['Friendlist']['friend_name'] = $user_details['User']['fname']." ".$user_details['User']['lname'];

                      $this->Friendlist->create();
                      if($this->Friendlist->save($this->data)){
                      
                            
                          $this->data['Friendlist']['sender_id'] = $noti_detail['Notification']['sender_id'];
                          $this->data['Friendlist']['receiver_id'] = $noti_detail['Notification']['receiver_id'];
                          
                          $condition7 = "User.id = '".$noti_detail['Notification']['receiver_id']."'";
                          $user_details = $this->User->find('first',array('conditions'=>$condition7)); 
                          
                          $this->data['Friendlist']['friend_name'] = $user_details['User']['fname']." ".$user_details['User']['lname'];
                          $this->Friendlist->create();
                          
                          if($this->Friendlist->save($this->data)){ 
  
                              $page="Friend request accepted";
                              $this->send_push_notification_friend_request($old_noti_detail['Notification']['receiver_id'], $old_noti_detail['Notification']['sender_id'], 'accept_request', $page);
                              
                              $response['success_msg'] = 'Friend Request accepted';
                          }
                          else{
                          
                              $response['is_error'] = 1;
                              $response['err_msg'] = 'Error occured to accept friend request !!! ';
                          }

                      }
                      else{
                              $response['is_error'] = 1;
                              $response['err_msg'] = 'Error occured to accept friend request !!!';
                      }
                  }
                  else{
                      $response['is_error'] = 1;
                      $response['err_msg'] = 'Error occured to accept friend request !!!';
                  }
           }
           else{
                  $response['is_error'] = 1;
                  $response['err_msg'] = 'Friend Request unaccepted ';
            }   
           }else{
            $response['is_error'] = 1;
            $response['err_msg'] = 'No notification found';
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;           
    }
    
    
    function reject_friend_request(){
       
            $this->layout = "";
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            $notification_id = $obj->{'notification_id'};
            $user_id = $obj->{'user_id'};
            
            $response = array();
            $response['is_error'] = 0;
            
            $condition = "Notification.id = '".$notification_id."' AND Notification.type = 'F' AND Notification.status = '1'";
            $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition));  
            if(!empty($old_noti_detail)){

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
                      if($this->Notification->save($this->data)){
                      
                          // send notification email for friend request reject
                          $notification_id = $this->Notification->getLastInsertId();  
                          $this->notification_email_friend($notification_id);
                          
                          $page="Friend request rejected";
                          $this->send_push_notification_friend_request($old_noti_detail['Notification']['receiver_id'], $old_noti_detail['Notification']['sender_id'], 'reject_request', $page);
                  
                          $response['success_msg'] = 'Friend Request rejected';
                 
                      }
                      else{
                          
                          $response['is_error'] = 1;
                          $response['err_msg'] = 'Error occured to reject the friend request !!! ';
                      }
                }
          }
          else{
            $response['is_error'] = 1;
            $response['err_msg'] = 'No notification found';
          }
       
          header('Content-type: application/json');
          echo json_encode($response);
          exit;   
    }
    
    
    function my_group_free(){

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            $user_id    = $obj->{'user_id'};
            //$user_id    = 138;
            
            $response = array();
            $response['is_error'] = 0;
            
            
           
            $con_free_groups = "GroupUser.status= '1' AND  GroupUser.user_id = '".$user_id."' AND Group.group_type = 'F' AND Group.status = '1'";
            $this->GroupUser->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id', 'order'=>'Group.group_title ASC'))),false);
            $arr_free_groups = $this->GroupUser->find('all',array('conditions'=>$con_free_groups));
                      
            
            $free_group = array(); 
            if(count($arr_free_groups) > 0){
             
                foreach($arr_free_groups as $groups){
                    
                    $list = array();
                    
                    $list['id'] = $groups['Group']['id'];
                    $list['title'] =  ucfirst(stripslashes($groups['Group']['group_title']));
                    $list['desc'] =  ucfirst(stripslashes($groups['Group']['group_desc']));
                    $list['category_id'] =  $groups['Group']['category_id'];
                    $list['group_type'] =  $groups['Group']['group_type'];
                    
                    $condition4 = "GroupUser.status= '1' AND  GroupUser.group_id = '".$groups['Group']['id']."'";
                    $group_user_count = $this->GroupUser->find('count',array('conditions'=>$condition4));
                    $list['user_count'] = $group_user_count;
                    $list['group_user_type'] =  $groups['GroupUser']['user_type'];
                   
                    if($groups['Group']['icon']!=''){
                    	
					   if($groups['Group']['parent_id']=='0'){
					   		
							$list['image_url']['thumb'] = $base_url.'group_images/thumb/'.$groups['Group']['icon'];
                       		$list['image_url']['medium'] = $base_url.'group_images/medium/'.$groups['Group']['icon'];
                       		$list['image_url']['original'] = $base_url.'group_images/'.$groups['Group']['icon'];
					   }
					   else{
					   		$list['image_url']['thumb'] = $base_url.'sub_group_images/thumb/'.$groups['Group']['icon'];
                       		$list['image_url']['medium'] = $base_url.'sub_group_images/medium/'.$groups['Group']['icon'];
                       		$list['image_url']['original'] = $base_url.'sub_group_images/'.$groups['Group']['icon'];
					   }
                    }
                    else{
                       $list['image_url']['thumb'] = $base_url.'images/no-group-img_1.jpg';
                       $list['image_url']['medium'] = $base_url.'images/no-group-img_1.jpg';
                       $list['image_url']['original'] = $base_url.'images/no-group-img_1.jpg';
                    }
                    array_push($free_group,$list);
                }
                $response['group_list']= $free_group; 
                $response['is_error'] = 0;
            }
            
            else{
                 $response['is_error'] = 1;
                 $response['err_msg'] = 'No free group found';
            }


            
             //pr($response );exit;
            header('Content-type: application/json');
            echo json_encode($response);
            exit;
         
    }
         
         
    function my_group_business(){

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            $user_id    = $obj->{'user_id'};
            //$user_id    = 4;
            
            $response = array();
            $response['is_error'] = 0;
            
            
           
            $con_business_groups = "GroupUser.status= '1' AND  GroupUser.user_id = '".$user_id."' AND Group.group_type = 'B' AND Group.status = '1'";
            $this->GroupUser->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id', 'order'=>'Group.group_title ASC'))),false);
            $arr_business_groups = $this->GroupUser->find('all',array('conditions'=>$con_business_groups));
                      
       
            $business_group = array(); 
            if(count($arr_business_groups) > 0){
             
                foreach($arr_business_groups as $groups){
                    
                    $list = array();
                    
                    $list['id'] = $groups['Group']['id'];
                    $list['title'] =  ucfirst(stripslashes($groups['Group']['group_title']));
                    $list['desc'] =  ucfirst(stripslashes($groups['Group']['group_desc']));
                    $list['category_id'] =  $groups['Group']['category_id'];
                    $list['group_type'] =  $groups['Group']['group_type'];
                    
                    $condition4 = "GroupUser.status= '1' AND  GroupUser.group_id = '".$groups['Group']['id']."'";
                    $group_user_count = $this->GroupUser->find('count',array('conditions'=>$condition4));
                    $list['user_count'] = $group_user_count;
                    $list['group_user_type'] =  $groups['GroupUser']['user_type'];
                   
                    if($groups['Group']['icon']!=''){
                    
						if($groups['Group']['parent_id']=='0'){
							$list['image_url']['thumb'] = $base_url.'group_images/thumb/'.$groups['Group']['icon'];
						    $list['image_url']['medium'] = $base_url.'group_images/medium/'.$groups['Group']['icon'];
						    $list['image_url']['original'] = $base_url.'group_images/'.$groups['Group']['icon'];
						}
						else{
						   $list['image_url']['thumb'] = $base_url.'sub_group_images/thumb/'.$groups['Group']['icon'];
						   $list['image_url']['medium'] = $base_url.'group_images/medium/'.$groups['Group']['icon'];
						   $list['image_url']['original'] = $base_url.'group_images/'.$groups['Group']['icon'];
						}
                    }
                    else{
                       $list['image_url']['thumb'] = $base_url.'images/no-group-img_1.jpg';
                       $list['image_url']['medium'] = $base_url.'images/no-group-img_1.jpg';
                       $list['image_url']['original'] = $base_url.'images/no-group-img_1.jpg';
                    }
                    array_push($business_group,$list);
                }
                $response['group_list']= $business_group; 
                $response['is_error'] = 0;
            }
            
            else{
                 $response['is_error'] = 1;
                 $response['err_msg'] = 'No business group found';
            }


            
             //pr($response );exit;
            header('Content-type: application/json');
            echo json_encode($response);
            exit;
         
         }


    function my_group_organisation(){

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            $user_id    = $obj->{'user_id'};
            //$user_id    = 4;
            
            $response = array();
            $response['is_error'] = 0;
            
            
           
            $con_business_groups = "GroupUser.status= '1' AND  GroupUser.user_id = '".$user_id."' AND Group.group_type = 'PO' AND Group.status = '1'";
            $this->GroupUser->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id', 'order'=>'Group.group_title ASC'))),false);
            $arr_business_groups = $this->GroupUser->find('all',array('conditions'=>$con_business_groups));
                      
       
            $business_group = array(); 
            if(count($arr_business_groups) > 0){
             
                foreach($arr_business_groups as $groups){
                    
                    $list = array();
                    
                    $list['id'] = $groups['Group']['id'];
                    $list['title'] =  ucfirst(stripslashes($groups['Group']['group_title']));
                    $list['desc'] =  ucfirst(stripslashes($groups['Group']['group_desc']));
                    $list['category_id'] =  $groups['Group']['category_id'];
                    $list['group_type'] =  $groups['Group']['group_type'];
                    
                    $condition4 = "GroupUser.status= '1' AND  GroupUser.group_id = '".$groups['Group']['id']."'";
                    $group_user_count = $this->GroupUser->find('count',array('conditions'=>$condition4));
                    $list['user_count'] = $group_user_count;
                    $list['group_user_type'] =  $groups['GroupUser']['user_type'];
                   
                    if($groups['Group']['icon']!=''){
                    	if($groups['Group']['parent_id']=='0'){
							$list['image_url']['thumb'] = $base_url.'group_images/thumb/'.$groups['Group']['icon'];
						    $list['image_url']['medium'] = $base_url.'group_images/medium/'.$groups['Group']['icon'];
						    $list['image_url']['original'] = $base_url.'group_images/'.$groups['Group']['icon'];
						}
						else{
							$list['image_url']['thumb'] = $base_url.'sub_group_images/thumb/'.$groups['Group']['icon'];
						    $list['image_url']['medium'] = $base_url.'sub_group_images/medium/'.$groups['Group']['icon'];
						    $list['image_url']['original'] = $base_url.'sub_group_images/'.$groups['Group']['icon'];
						}
                    }
                    else{
                       $list['image_url']['thumb'] = $base_url.'images/no-group-img_1.jpg';
                       $list['image_url']['medium'] = $base_url.'images/no-group-img_1.jpg';
                       $list['image_url']['original'] = $base_url.'images/no-group-img_1.jpg';
                    }
                    array_push($business_group,$list);
                }
                $response['group_list']= $business_group; 
                $response['is_error'] = 0;
            }
            
            else{
                 $response['is_error'] = 1;
                 $response['err_msg'] = 'No business group found';
            }


            
             //pr($response );exit;
            header('Content-type: application/json');
            echo json_encode($response);
            exit;
         
         }



    function my_group_public_organisation(){

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
            $user_id    = $obj->{'user_id'};
            //$user_id    = 4;
            
            $response = array();
            $response['is_error'] = 0;
            
            
           
            $cond_PO = "Group.status= '1' AND Group.group_type='PO' AND GroupUser.user_id = '".$user_id."' AND GroupUser.status= '1'";
            $this->GroupUser->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id', 'order'=>'Group.group_title ASC'))),false);
            $arr_PO_groups = $this->GroupUser->find('all',array('conditions'=>$cond_PO));
                      
       
            $PO_group = array(); 
            if(count($arr_PO_groups) > 0){
             
                foreach($arr_PO_groups as $groups){
                    
                    $list = array();
                    
                    $list['id'] = $groups['Group']['id'];
                    $list['title'] =  ucfirst(stripslashes($groups['Group']['group_title']));
                    $list['desc'] =  ucfirst(stripslashes($groups['Group']['group_desc']));
                    $list['category_id'] =  $groups['Group']['category_id'];
                    $list['group_type'] =  $groups['Group']['group_type'];
                    
                    $condition4 = "GroupUser.status= '1' AND  GroupUser.group_id = '".$groups['Group']['id']."'";
                    $group_user_count = $this->GroupUser->find('count',array('conditions'=>$condition4));
                    $list['user_count'] = $group_user_count;
                    $list['group_user_type'] =  $groups['GroupUser']['user_type'];
                   
                    if($groups['Group']['icon']!=''){
                    
                       $list['image_url']['thumb'] = $base_url.'group_images/thumb/'.$groups['Group']['icon'];
                       $list['image_url']['medium'] = $base_url.'group_images/medium/'.$groups['Group']['icon'];
                       $list['image_url']['original'] = $base_url.'group_images/'.$groups['Group']['icon'];
                    }
                    else{
                       $list['image_url']['thumb'] = $base_url.'images/no-group-img_1.jpg';
                       $list['image_url']['medium'] = $base_url.'images/no-group-img_1.jpg';
                       $list['image_url']['original'] = $base_url.'images/no-group-img_1.jpg';
                    }
                    array_push($PO_group,$list);
                }
                $response['group_list']= $PO_group; 
                $response['is_error'] = 0;
            }
            
            else{
                 $response['is_error'] = 1;
                 $response['err_msg'] = 'No public organisation group found';
            }


            
             //pr($response );exit;
            header('Content-type: application/json');
            echo json_encode($response);
            exit;
         
         }


         
         
    function friend_list(){
    
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $user_id    = $obj->{'user_id'};
           

        
        $condition_friend_detail = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
        $this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'sender_id'))));
        $friend_detail = $this->Friendlist->find('all',array('conditions'=>$condition_friend_detail));

        $friend_list = array(); 
            
        if(count($friend_detail) > 0){ 
        
            foreach($friend_detail as $friends){
            
                $list = array();

                $list['id'] = $friends['Friendlist']['id'];
                $list['user_id'] =  $friends['User']['id'];
                $list['first_name'] =  $friends['User']['fname'];
                $list['last_name'] = $friends['User']['lname'];
                $list['email'] = $friends['User']['email'];

                if($friends['User']['image']!=""){
                
                    $list['user_image'] = $base_url.'user_images/thumb/'.$friends['User']['image'];

                }
                else{
                    $list['user_image'] = $base_url.'images/no_profile_img.jpg';
                }

                array_push($friend_list,$list);
            }
            $response['friend_list']= $friend_list; 
            $response['is_error'] = 0;
        }
        else
        {
                $response['is_error'] = 1;
                $response['err_msg'] = 'No friends found';
        }
                
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    } 
    
    
    function remove_friend(){
        $this->layout = ""; 
        $response = array();
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

         $user_id    = $obj->{'user_id'};
         $friend_id    = $obj->{'friend_id'};

       /* $user_id    = '2';
        $friend_id    = '3';
        */
        
         
         $this->Friendlist->query("DELETE FROM friendlists WHERE ((`sender_id` = '".$user_id."' AND `receiver_id` = '".$friend_id."') OR (`sender_id` = '".$friend_id."' AND `receiver_id` = '".$user_id."'))");
         
         $this->Chat->query("DELETE FROM chats WHERE ((`from_id` = '".$user_id."' AND `to_id` = '".$friend_id."') OR (`from_id` = '".$friend_id."' AND `to_id` = '".$user_id."'))");
          $response['is_error'] = 0;
          $response['success_msg'] = 'You have removed the friend successfully';
        

        
          
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function my_calender_free_group(){
       
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);

            $user_id    = $obj->{'user_id'};
            $date    = $obj->{'date'};

            $con_group_detail = "GroupUser.user_id = '".$user_id."' AND GroupUser.status = '1'";
            $groups_user = $this->GroupUser->find('all',array('conditions'=>$con_group_detail));
            $group = array(); 
            foreach($groups_user as $grp){
                    
                array_push($group,$grp['GroupUser']['group_id']);
            }
            $allgroups = implode(",",$group);


            $arr_event_list = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `status` = '1' AND `event_date` = '".$date."' AND `group_id` IN (". $allgroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'F' AND `status` = '1' AND `group_id` IN (". $allgroups .") AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");


            $event_list = array(); 
            
            if(count($arr_event_list) > 0){ 
            
                foreach($arr_event_list as $events){
                
                    $list = array();

                    $list['id'] = $events['Event']['id'];
                    $list['event_name'] =  stripslashes($events['Event']['title']);
                    $list['desc'] =  stripslashes($events['Event']['desc']);
                    $list['deal_amount'] =  $events['Event']['deal_amount'];
                    
                    $condition = "Group.id = '".$events['Event']['group_id']."'";
                    $group_detail = $this->Group->find('first',array('conditions'=>$condition));
                    
                    $list['group_id'] = $events['Event']['group_id'];
                    $list['group_name'] =  stripslashes($group_detail['Group']['group_title']);
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

                        /*$list['event_start_date_time'] =  date("jS F Y ga",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_end_timestamp']);*/
						
						$list['event_start_date_time'] =  date("Y-m-d H:i:s",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_end_timestamp']);
                    }
                    else
                    {
                        //$list['event_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_timestamp']);    
						$list['event_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_timestamp']);     

                    }

                   
                    $list['location'] = $events['Event']['location'];
                    $list['latitude'] =  $events['Event']['latitude'];
                    $list['longitude'] =  $events['Event']['longitude'];
                    $list['place_id'] =  $events['Event']['place_id'];
                    
                    
                    array_push($event_list,$list);
                }
                $response['event_list']= $event_list; 
                $response['is_error'] = 0;
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'No event found';
                $response['event_list'] = array();
             }
                
            
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function my_calender_business_group(){
       
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);

            $user_id    = $obj->{'user_id'};
            $date    = $obj->{'date'};

            $con_group_detail = "GroupUser.user_id = '".$user_id."' AND GroupUser.status = '1'";
            $groups_user = $this->GroupUser->find('all',array('conditions'=>$con_group_detail));
            $group = array(); 
            foreach($groups_user as $grp){
                    
                array_push($group,$grp['GroupUser']['group_id']);
            }
            $allgroups = implode(",",$group);
            
            

            $arr_event_list = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_date` = '".$date."' AND `group_id` IN (". $allgroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `group_id` IN (". $allgroups .") AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
            $event_list = array(); 
            
            if(count($arr_event_list) > 0){ 
            
                foreach($arr_event_list as $events){
                
                    $list = array();

                    $list['id'] = $events['Event']['id'];
                    $list['event_name'] =  stripslashes($events['Event']['title']);
                    $list['desc'] =  stripslashes($events['Event']['desc']);
                    $list['deal_amount'] =  $events['Event']['deal_amount'];
                    
                    $condition = "Group.id = '".$events['Event']['group_id']."'";
                    $group_detail = $this->Group->find('first',array('conditions'=>$condition));
                    
                    $list['group_id'] = $events['Event']['group_id'];
                    $list['group_name'] =  stripslashes($group_detail['Group']['group_title']);
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

                        /*$list['event_start_date_time'] =  date("jS F Y ga",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_end_timestamp']);*/
						
						$list['event_start_date_time'] =  date("Y-m-d H:i:s",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_end_timestamp']);
                    }
                    else
                    {
                        //$list['event_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_timestamp']);      
						$list['event_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_timestamp']);  

                    }

                   
                    $list['location'] = $events['Event']['location'];
                    $list['latitude'] =  $events['Event']['latitude'];
                    $list['longitude'] =  $events['Event']['longitude'];
                    $list['place_id'] =  $events['Event']['place_id'];
                    
                    
                    
                    array_push($event_list,$list);
                }
                $response['event_list']= $event_list; 
                $response['is_error'] = 0;
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'No event found';
                $response['event_list'] = array();
             }
                
            
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
	
	
	function my_calender_PO_group(){
       
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);

            $user_id    = $obj->{'user_id'};
            $date    = $obj->{'date'};

            $con_group_detail = "GroupUser.user_id = '".$user_id."' AND GroupUser.status = '1'";
            $groups_user = $this->GroupUser->find('all',array('conditions'=>$con_group_detail));
            $group = array(); 
            foreach($groups_user as $grp){
                    
                array_push($group,$grp['GroupUser']['group_id']);
            }
            $allgroups = implode(",",$group);


            $arr_event_list = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'PO' AND `status` = '1' AND `event_date` = '".$date."' AND `group_id` IN (". $allgroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'PO' AND `status` = '1' AND `group_id` IN (". $allgroups .") AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");


            $event_list = array(); 
            
            if(count($arr_event_list) > 0){ 
            
                foreach($arr_event_list as $events){
                
                    $list = array();

                    $list['id'] = $events['Event']['id'];
                    $list['event_name'] =  stripslashes($events['Event']['title']);
                    $list['desc'] =  stripslashes($events['Event']['desc']);
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

                        /*$list['event_start_date_time'] =  date("jS F Y ga",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_end_timestamp']);*/
						
						$list['event_start_date_time'] =  date("Y-m-d H:i:s",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_end_timestamp']);
                    }
                    else
                    {
                        //$list['event_date_time'] = date("jS F Y ga",$event_time_detail['Event']['event_timestamp']);    
						$list['event_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_timestamp']);     

                    }

                   
                    $list['location'] = $events['Event']['location'];
                    $list['latitude'] =  $events['Event']['latitude'];
                    $list['longitude'] =  $events['Event']['longitude'];
                    $list['place_id'] =  $events['Event']['place_id'];
                    
                    
                    array_push($event_list,$list);
                }
                $response['event_list']= $event_list; 
                $response['is_error'] = 0;
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'No event found';
                $response['event_list'] = array();
             }
                
            
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
	
	
	function fetch_event_dates_my_calendar(){
  	
		$this->layout = ""; 
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $user_id= $obj->{'user_id'};
		$next_month_date = $obj->{'month_date'};
		$group_type = $obj->{'group_type'};
		
		$con_group_detail = "GroupUser.user_id = '".$user_id."' AND GroupUser.status = '1'";
		$groups_user = $this->GroupUser->find('all',array('conditions'=>$con_group_detail));
		$group = array(); 
		foreach($groups_user as $grp){
				
			array_push($group,$grp['GroupUser']['group_id']);
		}
		$allgroups = implode(",",$group);
		
		/***********************      Fetch the dates with Events starts       **********************/
		$first_day_this_month = date('Y-m-01', strtotime($next_month_date)); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-t', strtotime($next_month_date));
		$days_this_month= date('t', strtotime($next_month_date));
		$current_day_this_month= $first_day_this_month;
		$arr_event_dates= array();
		
		for($i=1;$i<=$days_this_month;$i++){
			
			 if($current_day_this_month<= $last_day_this_month){
				 $con_count_events = "((Event.`event_date` = '".$current_day_this_month."') OR (Event.`event_start_date` <= '".$current_day_this_month."' AND Event.`event_end_date` >= '".$current_day_this_month."')) AND Event.`group_type` = '".$group_type."' AND Event.`status` = '1' AND Event.`group_id` IN (". $allgroups .")";
				 $event_count = $this->Event->find('count',array('conditions' => $con_count_events));
				 
				 if($event_count>0){
					array_push($arr_event_dates, $current_day_this_month);
				 }
				 
				 $current_day_this_month= date('Y-m-d', strtotime("+1 day", strtotime($current_day_this_month)));
			 }
		}
		
		$response['is_error'] = 0;
		$response['event_dates']= $arr_event_dates; 
		
		
		header('Content-type: application/json');
        echo json_encode($response);
        exit;
		/***********************      Fetch the dates with Events ends       **********************/
		
  }
	
	
	
    
    
    function notification_email($notification_id){
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

        if($notification_detail['Notification']['type'] == 'G')
        {

          $group_name = stripslashes($notification_detail['Group']['group_title']);

          $sender_name = $notification_detail['Sender']['fname'].' '.$notification_detail['Sender']['lname'];
          
          $sender_email = $notification_detail['Sender']['email'];
          //$sender_email = $sender_name.'<'.$notification_detail['Sender']['email'].'>';

          $receiver_name = $notification_detail['Receiver']['fname'].' '.$notification_detail['Receiver']['lname'];
          
          $receiver_email = $notification_detail['Receiver']['email'];
         // $receiver_email = $receiver_name.'<'.$notification_detail['Receiver']['email'].'>';
          
                
                $site_url = $sitesettings['site_url']['value'];

                 //
            if(($notification_detail['Notification']['is_receiver_accepted'] == '0')&& ($notification_detail['Notification']['is_reversed_notification']== '0')){ 

            if(($notification_detail['Notification']['sender_type']== 'NGM')&& ($notification_detail['Notification']['receiver_type']== 'GO')) { 

                $condition = "EmailTemplate.id = '6'";//  requested to join your  group
                $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
            }
            
             else if (($notification_detail['Notification']['sender_type']== 'GO')&& ($notification_detail['Notification']['receiver_type']== 'NGM')) {

                 $condition = "EmailTemplate.id = '7'";//  invited you to join group
                $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

            } else if (($notification_detail['Notification']['sender_type']== 'GM')&& ($notification_detail['Notification']['receiver_type']== 'NGM')) { 

                $condition = "EmailTemplate.id = '8'";// recommended you to join group
                $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                                    
            } }


            else if (($notification_detail['Notification']['is_receiver_accepted']== '1')&& ($notification_detail['Notification']['is_reversed_notification']== '1')) { 

            if(($notification_detail['Notification']['sender_type']== 'NGM')&& ($notification_detail['Notification']['receiver_type']== 'GO')) { 

                        $condition = "EmailTemplate.id = '9'";//  rejected your invitation to join Business  group 
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
             } else if(($notification_detail['Notification']['sender_type']== 'GO')&& ($notification_detail['Notification']['receiver_type']== 'NGM')) {

                        $condition = "EmailTemplate.id = '10'";//  rejected your request to join their  Free group
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                                  
            } else if (($notification_detail['Notification']['sender_type']== 'NGM')&& ($notification_detail['Notification']['receiver_type']== 'GM')) { 

                        $condition = "EmailTemplate.id = '11'";//  rejected your recommendation to join  Free Business 
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                                 
            }  } 


             else if (($notification_detail['Notification']['is_receiver_accepted']== '2')&& ($notification_detail['Notification']['is_reversed_notification']== '1')) { 

             
            if(($notification_detail['Notification']['sender_type']== 'NGM')&& ($notification_detail['Notification']['receiver_type']== 'GO')) { 

                            $condition = "EmailTemplate.id = '12'";// accepted your invitation to join  Business 
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                                   
             } else if(($notification_detail['Notification']['sender_type']== 'GO')&& ($notification_detail['Notification']['receiver_type']== 'NGM')) {
                             $condition = "EmailTemplate.id = '13'";// accepted your request to join their Free group 
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                                  
                     } 
                    else if(($notification_detail['Notification']['sender_type']== 'NGM')&& ($notification_detail['Notification']['receiver_type']== 'GM')) { 
                              $condition = "EmailTemplate.id = '14'";//accepted your recommendation to join Free group
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                                   
             } } 
         }
                    
          //
         if($notification_detail['Notification']['type'] == 'P')
        {

           

          $group_name = stripslashes($notification_detail['Group']['group_title']);

          $message = stripslashes($notification_detail['Notification']['message']);
         

          $sender_name = $notification_detail['Sender']['fname'].' '.$notification_detail['Sender']['lname'];
          
          $sender_email = $notification_detail['Sender']['email'];

          $receiver_name = $notification_detail['Receiver']['fname'].' '.$notification_detail['Receiver']['lname'];
          
          $receiver_email = $notification_detail['Receiver']['email'];
          
                
                $site_url = $sitesettings['site_url']['value'];

                 //
          if (($notification_detail['Notification']['is_receiver_accepted']== '2')&& ($notification_detail['Notification']['is_reversed_notification']== '0')) { 

				  if($notification_detail['Notification']['sender_type']== 'GO' && $notification_detail['Notification']['receiver_type']== 'GM'){
							
							$condition = "EmailTemplate.id = '18'";//owner of  group sent you push notification .
							$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
					}   
					else if($notification_detail['Notification']['sender_type']== 'GM' && $notification_detail['Notification']['receiver_type']== 'GO'){
					
							$condition = "EmailTemplate.id = '27'";//member of  group sent push notification to owner .
							$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
					}

             } 
        }
         //
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
                
                
                $condition_event = "Event.id = '".$notification_detail['Notification']['event_id']."'";
                $event_detail = $this->Event->find("first", array('conditions' => $condition_event));
    
                if($event_detail['Event']['is_multiple_date'] == '1' ) { 
                $event_time =  date("F j, Y, g:i a",$event_detail['Event']['event_start_timestamp']) .'-'. date("F j, Y, g:i a",$event_detail['Event']['event_end_timestamp']);
                 } else { 
                $event_time =   date("F j, Y, g:i a",$event_detail['Event']['event_timestamp']) ;
                 } 
    
                $event_name =   $event_detail['Event']['title'] ;
     
                              
          }
        //
		
		if($notification_detail['Notification']['type'] == 'SG'){

		   

		  $group_name = stripslashes($notification_detail['Group']['group_title']);

		  $message = stripslashes($notification_detail['Notification']['message']);
		 

		  $sender_name = $notification_detail['Sender']['fname'].' '.$notification_detail['Sender']['lname'];
		  
		  $sender_email = $notification_detail['Sender']['email'];

		  $receiver_name = $notification_detail['Receiver']['fname'].' '.$notification_detail['Receiver']['lname'];
		  
		  $receiver_email = $notification_detail['Receiver']['email'];
		  
				
		  $site_url = $sitesettings['site_url']['value'];

				 //
		  if(($notification_detail['Notification']['is_receiver_accepted']== '0')&& ($notification_detail['Notification']['is_reversed_notification']== '0')) { 

			 if(($notification_detail['Notification']['sender_type']== 'GO')&& ($notification_detail['Notification']['receiver_type']== 'NGM')) { 

				$condition = "EmailTemplate.id = '24'";//  requested to join your  group
				$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
			}
		  } 
		  else if(($notification_detail['Notification']['is_receiver_accepted']== '2')&& ($notification_detail['Notification']['is_reversed_notification']== '1')) { 

			 if(($notification_detail['Notification']['sender_type']== 'NGM')&& ($notification_detail['Notification']['receiver_type']== 'GO')) { 

				$condition = "EmailTemplate.id = '25'";//  requested to join your  group
				$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
			}
		  } 
		  else if(($notification_detail['Notification']['is_receiver_accepted']== '1')&& ($notification_detail['Notification']['is_reversed_notification']== '1')) { 

			 if(($notification_detail['Notification']['sender_type']== 'NGM')&& ($notification_detail['Notification']['receiver_type']== 'GO')) { 

				$condition = "EmailTemplate.id = '26'";//  requested to join your  group
				$mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
			}
		  } 
		}
        
                                 
        $to = $receiver_email;
        $user_name = $receiver_name;
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
        $user_body = str_replace('[GROUPNAME]', $group_name, $user_body);
        $user_body = str_replace('[GROUPTYPE]', $group_type, $user_body);
        $user_body = str_replace('[MESSAGE]', $message, $user_body);
        $user_body = str_replace('[SITEURL]', $site_url, $user_body);
        $user_body = str_replace('[EVENTNAME]', $event_name, $user_body);
        $user_body = str_replace('[EVENTTIME]', $event_time, $user_body);
        $user_body = str_replace('[EVENTLOCATION]', $event_detail['Event']['location'], $user_body);
                           
                         
        $user_message = stripslashes($user_body);
        

       $string = '';
       $filepath = '';
       $filename = '';
       $sendCopyTo = '';


        $sendmail = sendmail($sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                
                 
                          
     

    }
    
    
    function notification_email_friend($notification_id){
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
    
    
    function TimeCalculate($currentDate,$PostedDate){
    
        $currentDate = date('Y-m-d H:i:s',$currentDate);
        $PostedDate = date('Y-m-d H:i:s',$PostedDate);
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
    
    
    function edit_user(){
       
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);


            $user_id = $_POST['user_id']; 
            $first_name = $_POST['first_name']; 
            $last_name = $_POST['last_name']; 
            $email = $_POST['email']; 
            

           $condition_user_detail = "User.id = '".$user_id."'";
           $user_detail = $this->User->find('first',array('conditions'=>$condition_user_detail)); 

           $condition_check_email = "User.id != '" . $user_id . "' AND User.email = '" . $email . "'";

                    $CheckEmail = $this->User->find('count', array('conditions' => $condition_check_email));

                    if ($CheckEmail > 0) {

                           $response['is_error'] = 1;
                           $response['err_msg'] = 'Sorry!! You have already used this mail id in some other account.';

                    }

                    else
                    {


            
           $upload_image = '';
        
           if(isset($_FILES["upload_image"]) && $_FILES["upload_image"]['name']!= ''){
           
                $image_name = $_FILES["upload_image"]['name'];
            
                $extension = end(explode('.',$image_name));               
                $upload_image = time().accessCode(5).'.'.$extension;          
                $upload_target_original = 'user_images/'.$upload_image;
                        
                $imagelist = getimagesize($_FILES["upload_image"]['tmp_name']);
                list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
                if($type == 1 || $type == 2){
                      if($uploaded_width >=160 && $uploaded_height >= 120){
                    
                          if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)){
                                                                                                                                                        
                                $upload_target_thumb = 'user_images/thumb/'.$upload_image;
                                $upload_target_medium = 'user_images/medium/'.$upload_image;
                               
                                $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                                $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
                                $this->data['User']['image'] = $upload_image;                                                              
                          }         
                          else{
                            
                                $response['is_error'] = 1;
                                $response['err_msg'] = 'Image upload failed';
                          }
                    }
                    else{        
                            $response['is_error'] = 1;
                            $response['err_msg'] = 'Please upload a 200x100 or bigger image only';
                    }   
                }
                else{
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'Please upload jpg,jpeg and gif image only';
                }
           }
           else{
                $this->data['User']['image'] = $user_detail['User']['image'];
           }
                        

            $this->data['User']['fname'] = $first_name;
            $this->data['User']['lname'] = $last_name;
            $this->data['User']['state_id'] = $state_id;
            $this->data['User']['city_id'] = $city_id;
            $this->User->id = $user_id;
            if($this->User->save($this->data))
            {
                    $condition_new_user_detail = "User.id = '".$user_id."'";
                    $new_user_detail = $this->User->find('first',array('conditions'=>$condition_new_user_detail));
                    $response['User']['id'] = $user_id;
                    $response['User']['first_name'] = $new_user_detail['User']['fname'];
                    $response['User']['last_name'] = $new_user_detail['User']['lname'];
                    $response['User']['username'] = $new_user_detail['User']['username'];
                    $response['User']['email'] = $new_user_detail['User']['email'];
                    $response['User']['state_id'] = $new_user_detail['User']['state_id'];
                    $response['User']['city_id'] = $new_user_detail['User']['city_id'];
                                            
                    if($new_user_detail['User']['image'] == ''){
                        $response['User']['image'] = $base_url.'images/no_profile_img.jpg';
                    }
                    else{
                        $response['User']['image'] = $base_url.'user_images/thumb/'.$new_user_detail['User']['image'];
                    }
                    
                    $response['is_error'] = 0;
                    $response['success_msg'] = 'Profile updated successfully';
            }
            else{
                   $response['is_error'] = 1;
                   $response['err_msg'] = 'Profile update failed';
            }

 
            }

                    
        
            header('Content-type: application/json');
            echo json_encode($response);
            exit;
        
    }
    
    
    function contact_us_details(){
    
        $this->layout = ""; 
        $response = array();
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $sitesettings = $this->getSiteSettings();

        $response['is_error'] = 0;
        $response['contact_phone']= $sitesettings['contact_phone']['value'];
        $response['contact_address']= $sitesettings['contact_address']['value'];
        $response['contact_latitude']= $sitesettings['contact_latitude']['value'];
        $response['contact_longitude']= $sitesettings['contact_longitude']['value'];


        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function submit_contact_us(){
    
            $this->layout = ""; 
            $response = array();
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            $sitesettings = $this->getSiteSettings();


            $query_type    = $obj->{'query_type'};
            $salutation    = $obj->{'salutation'};
            $name    = $obj->{'name'};
            $email    = $obj->{'email'};
            $phone    = $obj->{'phone'};
            $state_id    = $obj->{'state_id'};
            $city_id    = $obj->{'city_id'};
            $zipcode    = $obj->{'zipcode'};
            $address    = $obj->{'address'};
            $message    = $obj->{'message'};

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
                
            $response['is_error'] = 0;
            $response['success_msg'] = 'Your query is submitted sucessfully.';
                  

            header('Content-type: application/json');
            echo json_encode($response);
            exit;
    }
    
    
    function featured_group_list(){
         
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $sitesettings = $this->getSiteSettings();

        
        $state_id    = $obj->{'state_id'};
        $city_id    = $obj->{'city_id'};
        $user_id    = $obj->{'user_id'};
        
        $condition7 =  "Group.status= '1'  AND Group.is_featured ='1' AND Group.state_id= '".$state_id."'  AND Group.city_id= '".$city_id."'";
        $this->Group->bindModel(array('hasMany' => array('GroupUser' => array('foreignKey' => 'group_id'))),false);
        $all_featured_groups = $this->Group->find('all',array('conditions'=>$condition7,'order' => 'Group.group_title ASC'));
        
   
        $featured_group = array(); 
        if(count($all_featured_groups) > 0)
        { 
            foreach($all_featured_groups as $groups)
            {
                $list['id'] = $groups['Group']['id'];
                $list['title'] =  ucfirst(stripslashes($groups['Group']['group_title']));
                $list['desc'] =  ucfirst($groups['Group']['group_desc']);
                $list['group_type'] =  $groups['Group']['group_type'];
                $list['user_count'] = count($groups['GroupUser']);

                if($user_id>0){
                    $conditions = "GroupUser.group_id =  '".$groups['Group']['id']."' AND GroupUser.user_id =  '".$user_id."' AND GroupUser.status= '1' ";
                    $GroupType = $this->GroupUser->find('first', array('conditions' => $conditions));
                    if(!empty($GroupType))
                    {
                       $list['group_user_type'] =  $GroupType['GroupUser']['user_type'];
                    }
                    else
                    {
                      $list['group_user_type'] = '';
                    }
                }
                else{
                    $list['group_user_type'] = '';
                }
                
                if(!empty($groups['Group']['icon'])){
                
                   $list['image_url']['thumb'] = $base_url.'group_images/thumb/'.$groups['Group']['icon'];
                   $list['image_url']['medium'] = $base_url.'group_images/medium/'.$groups['Group']['icon'];
                   $list['image_url']['original'] = $base_url.'group_images/'.$groups['Group']['icon'];
                }
                else{
                   $list['image_url']['thumb'] = $base_url.'images/no-group-img_1.jpg';
                   $list['image_url']['medium'] = $base_url.'images/no-group-img_1.jpg';
                   $list['image_url']['original'] = $base_url.'images/no-group-img_1.jpg';
                }
                array_push($featured_group,$list);
            }
            $response['group_list']= $featured_group; 
            $response['is_error'] = 0;
        }
        else {
             $response['group_list']= array(); 
             $response['is_error'] = 1;
             $response['err_msg'] = 'No featured group found';
        }

        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    
    function community_event_calender(){
       
            $this->layout = ""; 
            $response = array();
            $json = file_get_contents('php://input');
            $obj = json_decode($json);

            $date    = $obj->{'date'};
            $user_id = $obj->{'user_id'};
            $selected_state_id = $obj->{'state_id'};
            $selected_city_id = $obj->{'city_id'};
            /*$user_id = '';
            $date = '2017-07-06';*/

            $conditions_group = "Group.city_id = '".$selected_city_id."' AND Group.state_id = '".$selected_state_id."' ";
            $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
         
            $group = array(); 
            foreach($all_groups as $grp)
            {

              array_push($group,$grp['Group']['id']);
            }
            $allstatecitygroups = implode(",",$group);
           
          
            if($allstatecitygroups!=''){
            
            $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE (`group_type` = 'F' OR `group_type` = 'PO') AND `type` = 'public' AND `status` = '1' AND `event_date` = '".$date."' AND `group_id` IN (". $allstatecitygroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE (`group_type` = 'F' OR `group_type` = 'PO') AND `type` = 'public' AND `status` = '1'  AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."' AND `group_id` IN (". $allstatecitygroups .")) AS `Event`ORDER BY `Event`.`sort_time`ASC");
            
            $event_list = array(); 
            if(count($event_details) > 0)
            { 
                foreach($event_details as $events)
                {
                    $list = array();



                    $list['id'] = $events['Event']['id'];
                    $list['event_name'] =  ucfirst(stripslashes($events['Event']['title']));
                    $list['desc'] =  ucfirst(stripslashes($events['Event']['desc']));
                    $list['deal_amount'] =  $events['Event']['deal_amount'];
                    $condition = "Group.id = '".$events['Event']['group_id']."'";
                    
                    $group_detail = $this->Group->find('first',array('conditions'=>$condition));
                    $list['group_id'] = $events['Event']['group_id'];
                    $list['group_name'] =  ucfirst(stripslashes($group_detail['Group']['group_title']));
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

                        $list['event_start_date_time'] =  date("Y-m-d H:i:s",$event_time_detail['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_end_timestamp']);
                    }
                    else
                    {
                       $list['event_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_timestamp']);        

                    }

                   
                    $list['location'] = $events['Event']['location'];
                    $list['latitude'] =  $events['Event']['latitude'];
                    $list['longitude'] =  $events['Event']['longitude'];
                    $list['place_id'] =  $events['Event']['place_id'];
                    
                    
                    
                    array_push($event_list,$list);
                }
                $response['event_list']= $event_list; 
                $response['is_error'] = 0;
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'No Business events found';
            }
            
        }  
          else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'No events found';
          }
                
            
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }
    
    
    function business_event_calender(){
       
            $this->layout = ""; 
            $response = array();
            $json = file_get_contents('php://input');
            $obj = json_decode($json);

            $date    = $obj->{'date'};
            $user_id = $obj->{'user_id'};

            $selected_state_id = $obj->{'state_id'};
            $selected_city_id = $obj->{'city_id'};
            /*$user_id = '';
            $date = '2017-07-06';*/

            $conditions_group = "Group.city_id = '".$selected_city_id."' AND Group.state_id = '".$selected_state_id."' ";
            $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
            
            if(!empty($all_groups)){
                $group = array(); 
                foreach($all_groups as $grp)
                {

                  array_push($group,$grp['Group']['id']);
                }
                $allstatecitygroups = implode(",",$group);
            }
            else{
                $allstatecitygroups='';
            }
                  

            if($allstatecitygroups!=''){
            
                $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND `event_date` = '".$date."' AND `group_id` IN (". $allstatecitygroups .") UNION SELECT `id`,`title`,`desc`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_type` = 'B' AND `status` = '1' AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."' AND `group_id` IN (". $allstatecitygroups .")) AS `Event`ORDER BY `Event`.`sort_time`ASC");
                
                $event_list = array(); 
                if(count($event_details) > 0)
                { 
                    foreach($event_details as $events)
                    {
                        $list = array();
    
    
    
                        $list['id'] = $events['Event']['id'];
                        $list['event_name'] =  ucfirst(stripslashes($events['Event']['title']));
                        $list['desc'] =  ucfirst(stripslashes($events['Event']['desc']));
                        $list['deal_amount'] =  $events['Event']['deal_amount'];
                        $condition = "Group.id = '".$events['Event']['group_id']."'";
                        
                        $group_detail = $this->Group->find('first',array('conditions'=>$condition));
                        $list['group_id'] = $events['Event']['group_id'];
                        $list['group_name'] =  ucfirst(stripslashes($group_detail['Group']['group_title']));
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
    
                            $list['event_start_date_time'] =  date("Y-m-d H:i:s",$event_time_detail['Event']['event_start_timestamp']);
                            $list['event_end_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_end_timestamp']);
                        }
                        else
                        {
                           $list['event_date_time'] = date("Y-m-d H:i:s",$event_time_detail['Event']['event_timestamp']);        
    
                        }
    
                       
                        $list['location'] = $events['Event']['location'];
                        $list['latitude'] =  $events['Event']['latitude'];
                        $list['longitude'] =  $events['Event']['longitude'];
                        $list['place_id'] =  $events['Event']['place_id'];
                        
                        
                        
                        array_push($event_list,$list);
                    }
                    $response['event_list']= $event_list; 
                    $response['is_error'] = 0;
                }
                else{
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'No Business events found';
                }
                
            }           
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'No Business events found';
            }
          
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    } 
    
    
    function leave_group(){
    
        $this->layout = ""; 
        $response = array();
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

         $user_id    = $obj->{'user_id'};
         $group_id    = $obj->{'group_id'};

       /* $user_id    = '2';
        $friend_id    = '3';
        */
        
        ###########################         Delete from GroupUser Table and User  Table starts          ####################
         $this->GroupUser->query("DELETE FROM group_users WHERE (`group_id` = '".$group_id."' AND `user_id` = '".$user_id."' AND `user_type` = 'M')");
         $condition_user = "User.id = '".$user_id."'";
         $user_details= $this->User->find('first',array('conditions' => $condition_user));
         $user_groups = $user_details['User']['groups']; 
         $arr_user_groups= explode(',', $user_groups);
         
         $arr_new_user_groups= array();
         for($i=0;$i<count($arr_user_groups);$i++){
            if($arr_user_groups[$i]!=$group_id){
                array_push($arr_new_user_groups, $arr_user_groups[$i]);
            }
         }
         
         $str_user_groups= implode(',', $arr_new_user_groups); 
         $this->User->query("UPDATE `users` SET `groups` ='".$str_user_groups."'  WHERE `id` = '".$user_id."'");
         
         ###########################            Delete from GroupUser Table and User  Table ends            ####################
         
         
         $condition_group = "Group.id = '".$group_id."'";
         $group_details= $this->Group->find('first',array('conditions' => $condition_group));
         $group_owners= $group_details['Group']['group_owners'];
         $arr_group_owners= explode(',', $group_owners);
         
         $notification_message= 'The member '.ucfirst($user_details['User']['fname']).' '.ucfirst($user_details['User']['lname']).' has left from the group: '.stripslashes(ucfirst($group_details['Group']['group_title']));
         
         if(count($arr_group_owners)>0){
            for($i=0; $i<count($arr_group_owners); $i++){
                
                    $this->data['Notification']['type'] = 'G';
                    $this->data['Notification']['group_id'] = $group_id;
                    $this->data['Notification']['group_type'] = $group_details['Group']['group_type'];
                    $this->data['Notification']['sender_id'] = $user_id;
                    $this->data['Notification']['sender_type'] = 'NGM';
                    $this->data['Notification']['receiver_id'] = $arr_group_owners[$i];
                    $this->data['Notification']['receiver_type'] = 'GO';
                    $this->data['Notification']['is_read'] = 0;
                    $this->data['Notification']['is_receiver_accepted'] = 2;
                    $this->data['Notification']['is_reversed_notification'] = 0;
                    $this->data['Notification']['status'] = 1;
                    $this->data['Notification']['message'] = $notification_message;
                  
                    $this->Notification->create();  
                    
                    if($this->Notification->save($this->data['Notification'])){
                        $notification_id = $this->Notification->getLastInsertId();  
                        //$this->notification_email($notification_id);
                        
                        $page="Quit from ".stripslashes(ucfirst($group_details['Group']['group_title']));
                        $this->send_notification_leave_from_group($user_id, $arr_group_owners[$i], $group_id, $notification_message, $page);
                          
                     }
            }
         }
         
         
         
         $response['is_error'] = 0;
         $response['success_msg'] = 'You have left the group successfully';
        

        
          
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
    
    /*---------------------------  Push Notification from Group Details screen to Group Users starts    -----------------------*/
    
    function send_notification($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $message=NULL, $page=NULL, $mode=NULL){
        
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
            
			if($mode=='m_o'){
				$message= ucfirst($sender_details['User']['fname'] ." ". $sender_details['User']['lname']).", member of  ".$group_type." group ".ucfirst($group_details['Group']['group_title'])." sent you push notification: \n".$message; 
			}
			else{
				$message= ucfirst($sender_details['User']['fname'] ." ". $sender_details['User']['lname']).", owner of  ".$group_type." group ".ucfirst($group_details['Group']['group_title'])." sent you push notification: \n".$message; 
			} 
            
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################

            //if($user_details['User']['device_type']=='android'){
                $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
            /*}  
            else if($user_details['User']['device_type']=='iphone'){
                $data =  array(
                    'title' =>'IOS PUSH',
                    'alert' => $message,
                    'sound' => 'default',
                    'badge' => $notification_count      
                );
                $this->send_push_notification_IOS($user_details['User']['device_token'],$data);
            }*/                      
            
        }
   }
   
   /*---------------------------  Push Notification from Group Details screen to Group Users ends    -----------------------*/
   
   /*---------------------------  Push Notification from Group Details screen to Group Users starts    -----------------------*/
    
    function send_notification_leave_from_group($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $message=NULL, $page=NULL){
        //$this->_checkSession();
        $condition_receiver = "User.id = '".$receiver_id."'";
        $user_details= $this->User->find('first',array('conditions' => $condition_receiver));
        if($user_details['User']['device_token']!='')
        {
            /*$condition_sender = "User.id = '".$sender_id."'";
            $sender_details= $this->User->find('first',array('conditions' => $condition_sender));
            
            $condition_group = "Group.id = '".$group_id."'";
            $group_details= $this->Group->find('first',array('conditions' => $condition_group));
            
            if($group_details['Group']['group_type'] == 'F' ){
                $group_type= 'Private';
            }
            else{
                $group_type= 'Business';
            }*/
            
            //$message= ucfirst($sender_details['User']['fname'] ." ". $sender_details['User']['lname']).", member of  ".$group_type." group ".ucfirst($group_details['Group']['group_title'])." sent you push notification: \n".$message; 
            
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
                                    
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
        }
   }
   
   /*---------------------------  Push Notification from Group Details screen to Group Users ends    -----------------------*/
   
   
   /*---------------------------  Push Notification to send Invitation to Join Group to Friends / Users starts    -----------------------*/
    
    
    function send_notification_group_invitation($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $mode,$page){
        //$this->_checkSession();

        /*$sender_id = '6';
        $receiver_id = '4';
        $group_id = '59';
        $mode = 'invite_friends';
        $page = 'Group Joining Invitation To Friends';*/

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
            
            if($mode =='invite_friends'){
                
                $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' invited you to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));
                
            }
            else if($mode=='invite_users'){
            
                $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' invited you to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));
                
            }
            
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
           
           /* Sajjad Mistri invited you to join Private group AloveradNHH1oizSpk:APA91bF4vfxVTfX30Mj3lJ1H57dwkeoQhgLsRqu3uPzUTzGCtbNOQBhUsjGT0Zf1Eqpgl0Q1Vo6CXFlStL8yOxGERbg3OjB6R7Xfv2K0Js-VSAIPd2F0PJAOrpxmRV_K-ISR4-yxAmdmGroup Joining Invitation To Friends*/
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
           
        }
   }
   
    /*---------------------------  Push Notification to send Invitation to Join Group to Friends / Users ends    -----------------------*/
   
    /*---------------------------  Push Notification to send Recommendation to Join Group to Friends / Users starts    -----------------------*/
    
    function send_notification_group_recommendation($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $mode,$page){
        
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
            
            if($mode =='recommend_friends'){
                
                $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' recommended you to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));
                
            }
            else if($mode =='recommend_users'){
            
                $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' recommended you to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));
                
            }
             
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
                               
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
        }
   }
   
    /*---------------------------  Push Notification to send Recommendation to Join Group to Friends / Users ends    -----------------------*/
    
    /*---------------------------  Push Notification to send Joining Request Via Recommendation to Group Owner starts    -----------------------*/
     
    function send_notification_group_joining_request($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $page){
        //$this->_checkSession();

        /*$sender_id = '6';
        $receiver_id = '4';
        $group_id = '59';
        $mode = 'invite_friends';
        $page = 'Group Joining Invitation To Friends';*/

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
            
            $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' wants to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));
                
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
           
           /* Sajjad Mistri invited you to join Private group AloveradNHH1oizSpk:APA91bF4vfxVTfX30Mj3lJ1H57dwkeoQhgLsRqu3uPzUTzGCtbNOQBhUsjGT0Zf1Eqpgl0Q1Vo6CXFlStL8yOxGERbg3OjB6R7Xfv2K0Js-VSAIPd2F0PJAOrpxmRV_K-ISR4-yxAmdmGroup Joining Invitation To Friends*/
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
           
        }
   }
    
    
    /*-------------------  Push Notification to send Joining Request Via Recommendation to Group Owner Ends  -----------------------*/

    
    function send_reverse_notification_group($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $sender_type, $receiver_type, $mode,$page){
    
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
            
            if($mode =='Accept'){
                
                if($sender_type=='NGM' && $receiver_type=='GO'){
                    $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' accepted your invitation to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));    
                }
                else if($sender_type=='GO' && $receiver_type=='NGM'){
                    $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' accepted your request to join their '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title'])); 
                }
                else if($sender_type=='NGM' && $receiver_type=='GM'){
                    $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' accepted your recommendation to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));    
                }   
            }
            else if($mode=='Reject'){
            
                if($sender_type=='NGM' && $receiver_type=='GO'){
                    $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' rejected your invitation to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));    
                }
                else if($sender_type=='GO' && $receiver_type=='NGM'){
                    $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' rejected your request to join their '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title'])); 
                }
                else if($sender_type=='NGM' && $receiver_type=='GM'){
                    $message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' rejected your recommendation to join '.$group_type.' group '.ucfirst(stripslashes($group_details['Group']['group_title']));    
                }
                
            }
            
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
            
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
        }
   }
   
   /*-------------------  Reverse Push Notification By clicking on Accept/ Reject button for any Group ends  -----------------------*/
   
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
            
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
            
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
            
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
            
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
        }
   }
   
    /*---------------------------  Push Notification to Group Users After editing the Event ends   -----------------------*/
   
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
            
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
                                    
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
        }
   }
   
   /*---------------------------  Push Notification to send friend request ends    -----------------------*/
   
   
    /*---------------------  Push Notification to send Sub Group Creation Request to Primary Group Owners starts    -----------------------*/
	 
	function send_notification_sub_group_creation_request($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $page){
		//$this->_checkSession();

		/*$sender_id = '6';
		$receiver_id = '4';
		$group_id = '59';
		$mode = 'invite_friends';
		$page = 'Group Joining Invitation To Friends';*/

		$condition_receiver = "User.id = '".$receiver_id."'";
		$user_details= $this->User->find('first',array('conditions' => $condition_receiver));
		if($user_details['User']['device_token']!='')
		{
			$condition_sender = "User.id = '".$sender_id."'";
			$sender_details= $this->User->find('first',array('conditions' => $condition_sender));
			
			$condition_group = "Group.id = '".$group_id."'";
			$group_details= $this->Group->find('first',array('conditions' => $condition_group));
			
			$message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' has requested to approve the Sub Group : '.ucfirst(stripslashes($group_details['Group']['group_title']));
				
			###################		Get the Notification Counter Starts 	################
				
			$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
	  
			$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
			
			###################		Get the Notification Counter Starts 	################
		   
		   /* Sajjad Mistri invited you to join Private group AloveradNHH1oizSpk:APA91bF4vfxVTfX30Mj3lJ1H57dwkeoQhgLsRqu3uPzUTzGCtbNOQBhUsjGT0Zf1Eqpgl0Q1Vo6CXFlStL8yOxGERbg3OjB6R7Xfv2K0Js-VSAIPd2F0PJAOrpxmRV_K-ISR4-yxAmdmGroup Joining Invitation To Friends*/
			$this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);

		   
		}
   }
	
	
	/*-------------------  Push Notification to send Sub Group Creation Request to Primary Group Owners Ends  -----------------------*/
		
	/*-------------------  Reverse Push Notification By clicking on Accept/ Reject button for any Sub Group Creation Approval starts  -----------------------*/

	
	function reverse_notification_sub_group_request($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $sender_type, $receiver_type, $mode,$page){
	
		//$this->_checkSession();
		$condition_receiver = "User.id = '".$receiver_id."'";
		$user_details= $this->User->find('first',array('conditions' => $condition_receiver));
		if($user_details['User']['device_token']!='')
		{
			$condition_sender = "User.id = '".$sender_id."'";
			$sender_details= $this->User->find('first',array('conditions' => $condition_sender));
			
			$condition_group = "Group.id = '".$group_id."'";
			$group_details= $this->Group->find('first',array('conditions' => $condition_group));
			
			if($mode =='Accept'){
				
				if($sender_type=='NGM' && $receiver_type=='GO'){
					$message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' accepted your invitation to create Sub group '.ucfirst(stripslashes($group_details['Group']['group_title']));	
				}	
			}
			else if($mode=='Reject'){
			
				if($sender_type=='NGM' && $receiver_type=='GO'){
					$message= ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])).' rejected your invitation to create Sub group '.ucfirst(stripslashes($group_details['Group']['group_title']));	
				}
			}
			
			###################		Get the Notification Counter Starts 	################
				
			$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
	  
			$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
			
			###################		Get the Notification Counter Starts 	################
			
			$this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
		  
		}
   }
   
   /*-------------------  Reverse Push Notification By clicking on Accept/ Reject button for any Sub Group Creation Approval ends  -----------------------*/
   
   
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
            'Authorization: key=' . "AAAAAqY2LLo:APA91bEW_gfF_X2s4FsBtPOo0YjVrBf9Wu8rnvASP8sYjLUoe6IJBrfd8eUjHOQ2lyJcP_HZtiRRibDxlNc_195JivfqldC4spYVxcOJhArlUQGtgAsyfHjP-UNf1x4o-dvUeZ4ZQLqa",
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
    
 
   function send_push_notification_IOS($deviceToken,$data){
    
        $deviceToken= 'dd0c4a11a186121b9bcd10bfbc6f0732464372530c6d80e6c6181fb6c066fb5b';
        $data =  array(
        'type' => 'abc',
        'title' =>'IOS PUSH',
        'alert' => 'Ashit',
        'sound' => 'default',
        'badge' => 5        
        );

        
        $is_live = 0;   // 0/1
        
        //pr($data);
        if($is_live == 1)
        {
            $file_name = 'Grouper_Production.pem';  // live
            $url = 'ssl://gateway.push.apple.com:2195';
            $passphrase = '';
        }
        else
        {
            $file_name = 'Grouper_developmentt.pem';    //development
            $url = 'ssl://gateway.sandbox.push.apple.com:2195';
            $passphrase = '';
        }
        echo $file_name;
        echo $url;
        echo $passphrase;
        
        // Put your alert message here:
        
        ////////////////////////////////////////////////////////////////////////////////
        
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $file_name);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        
        // Open a connection to the APNS server
        //ssl://gateway.sandbox.push.apple.com:2195
        //ssl://gateway.push.apple.com:2195
        echo '--->'.$fp = stream_socket_client( $url, $err,     $errstr, 100, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                
        if (!$fp)
        {                   
            echo "<br>Failed to connect:".$err;
            echo "<br>=====Failed to connect:".$errstr;
            return false;
        }
        //echo '<br>Connected to APNS' . PHP_EOL;
        
        // Create the payload body
        
        //$receiver_detail = $this->Member->find('first', array('conditions' => array("Member.id"=>$user_id),'fields'=>'Member.*'));
        //echo '<pre>';
        //print_r($receiver_detail);
        
        /*if(($receiver_detail['Member']['app_stage'] == 'background' && $data['type'] == 'chatstop') || $data['type'] == 'system_offline')
        {*/
            $content_available = 1;
        /*}
        else
        {
            $content_available = 0;
        }*/
        
        if($content_available == 1)
        {
            $data['content-available'] = $content_available;
        }
        //print_r($data);
        //echo '</pre>';
        //
        $body['aps'] = $data;

        // Encode the payload as JSON
        $payload = json_encode($body);
        
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        
        /*if (!$result)
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;
        */  
        
        // Close the connection to the server
        fclose($fp);
        
        
        //$this->sendPushNotificationIOS_DEMO($deviceToken,$data,$content_available);
        
        return true;
        
    
   }

    function notification_start_stop(){
        
        $this->layout = '';
        $response = array();
        
        $json = file_get_contents('php://input');   
        $obj = json_decode($json);
        
        $user_id  = $obj->{'user_id'};
        $group_id  = $obj->{'group_id'};
        $is_stop = $obj->{'is_stop'};

       /* $user_id  = '5';
        
        $group_id  = '4';
        $is_stop = '0';*/
        
        $this->GroupUser->query("UPDATE `group_users` SET `is_notification_stop` ='".$is_stop."'WHERE `group_id` = '".$group_id."' AND `user_type` = 'M' AND `user_id` = '".$user_id."'");
        
        $response['is_error'] = 0;
        $response['success_msg'] = "Notification status updated successfully";
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
   
   

    function user_location_update()
    {
       
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);


            $state_id   = $obj->{'state_id'};
            $city_id    = $obj->{'city_id'};
            $user_id    = $obj->{'user_id'};

            
                     
                $this->data['User']['id'] = $user_id;
                $this->data['User']['city_id'] = $city_id;
                $this->data['User']['state_id'] = $state_id;
                           
                
                if($this->User->save($this->data))
                {
                  $response['is_error'] = 0;  
                  $response['state_id'] = $state_id;
                  $condition = "State.id = '".$state_id."'";
                  $state_detail = $this->State->find('first',array('conditions'=>$condition));
                  $response['state_name'] = ucfirst($state_detail['State']['name']);

                  $response['city_id'] = $city_id;
                  $condition = "City.id = '".$city_id."'";
                  $city_detail = $this->City->find('first',array('conditions'=>$condition));
                  $response['city_name'] = ucfirst($city_detail['City']['name']);


                }

                else
                {
                  $response['is_error'] = 1;
                  $response['err_msg'] = 'Location update uncessful';
                }

       
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }


    function friend_list_counter(){
    
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $user_id    = $obj->{'user_id'};
        //$user_id = 13;

        
        $condition_friend_detail = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
        $this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'sender_id'))));
        //$this->Friendlist->bindModel(array('hasMany' => array('Chat' => array('foreignKey' => 'sender_id'))));
        $friend_detail = $this->Friendlist->find('all',array('conditions'=>$condition_friend_detail));
        //pr($friend_detail);exit;
        $friend_list = array(); 
            
        if(count($friend_detail) > 0){ 
        
            foreach($friend_detail as $friends){

                //$conditn = "(Chat.to_id = '".$friends['User']['id']."' OR Chat.from_id = '".$friends['User']['id']."') AND Chat.status = '1'";
                $conditn = "Chat.from_id = '".$friends['User']['id']."' AND Chat.to_id = '".$user_id."' AND Chat.status = '1'";
                $ChatCount = $this->Chat->find("count",array("conditions" => $conditn));
            
                $list = array();

                $list['id'] = $friends['Friendlist']['id'];
                $list['user_id'] =  $friends['User']['id'];
                $list['first_name'] =  $friends['User']['fname'];
                $list['last_name'] = $friends['User']['lname'];
                $list['email'] = $friends['User']['email'];
                $list['chat_count'] = $ChatCount;

                if($friends['User']['image']!=""){
                
                    $list['user_image'] = $base_url.'user_images/thumb/'.$friends['User']['image'];

                }
                else{
                    $list['user_image'] = $base_url.'images/no_profile_img.jpg';
                }

                array_push($friend_list,$list);
                //pr($friend_list);exit;
            }
            //pr($friend_list);exit;
            $response['friend_list']= $friend_list; 
            $response['is_error'] = 0;
        }
        else
        {
                $response['is_error'] = 1;
                $response['err_msg'] = 'No friends found';
        }
                
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    } 


    function chat_list()
    {
    
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $user_id    = $obj->{'user_id'};
        $friend_id    = $obj->{'friend_id'};


        $condition_chat1 = "((Chat.to_id = '".$user_id."' AND Chat.from_id = '".$friend_id."') OR (Chat.from_id = '".$user_id."' AND Chat.to_id = '".$friend_id."')) AND Chat.status = '1'";
        $this->Chat->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'from_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'to_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image')
                )
                ));
        $ArChatHistories = $this->Chat->find('all',array('conditions' => $condition_chat1));
        if(!empty($ArChatHistories))
        {
            foreach($ArChatHistories as $ChatHistory)
            {
                $this->data['Chat']['id'] = $ChatHistory['Chat']['id'];
                $this->data['Chat']['status'] = '0';
                $this->Chat->save($this->data['Chat']);
                
            }
        }

        
        $condition_chat = "((Chat.to_id = '".$user_id."' AND Chat.from_id = '".$friend_id."') OR (Chat.from_id = '".$user_id."' AND Chat.to_id = '".$friend_id."'))";
        $this->Chat->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'from_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'to_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image')
                )
                ));
        $ArChatHistory = $this->Chat->find('all',array('conditions' => $condition_chat));
        //pr($ArChatHistory);exit;
        

        $chat_list = array(); 
            
        if(count($ArChatHistory) > 0){ 
        
            foreach($ArChatHistory as $ChatHistory){

                /*$conditn = "Chat.to_id = '".$user_id."' AND Chat.status = '1'";
                $ChatCount = $this->Chat->find("count",array("conditions" => $conditn));*/
            
                $list = array();

                $list['id'] = $ChatHistory['Chat']['id'];
                $list['receiver_id'] =  $ChatHistory['Chat']['to_id'];
                $list['receiver_name'] =  $ChatHistory['Receiver']['fname'].' '.$ChatHistory['Receiver']['lname'];
                $list['sender_id'] =  $ChatHistory['Chat']['from_id'];
                $list['sender_name'] =   $ChatHistory['Sender']['fname'].' '.$ChatHistory['Sender']['lname'];
                $list['message'] = $ChatHistory['Chat']['conversation'];
                $list['date_time'] = date('M d,Y',strtotime($ChatHistory['Chat']['created'])).'-'.date('h:i A',strtotime($ChatHistory['Chat']['created']));
                

                if($ChatHistory['Sender']['image']!=""){
                
                    $list['sender_image'] = $base_url.'user_images/thumb/'.$ChatHistory['Sender']['image'];

                }
                else{
                    $list['sender_image'] = $base_url.'images/no_profile_img.jpg';
                }


                if($ChatHistory['Receiver']['image']!=""){
                
                    $list['receiver_image'] = $base_url.'user_images/thumb/'.$ChatHistory['Receiver']['image'];

                }
                else{
                    $list['receiver_image'] = $base_url.'images/no_profile_img.jpg';
                }

                array_push($chat_list,$list);
                //pr($friend_list);exit;
            }
            $response['chat_list']= $chat_list; 
            $response['is_error'] = 0;
        }
        else
        {
                $response['is_error'] = 1;
                $response['err_msg'] = 'No message found';
        }
                
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    } 



    function send_message(){
    
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);

        $user_id    = $obj->{'user_id'};
        $friend_id    = $obj->{'friend_id'};
        $user_name    = $obj->{'user_name'};
        $friend_name    = $obj->{'friend_name'};
        $message    = $obj->{'message'};
        //$user_id = 13;


        /*$user_id    = 6;
        $friend_id    = 138;
        $user_name    = "Sajjad Mistri";
        $friend_name    = "Tanima Mukhopadhyay";
        $message = "How are you?";*/

        
        $this->data['Chat']['from_id'] = $user_id;
        $this->data['Chat']['to_id'] = $friend_id;
        $this->data['Chat']['from'] = $user_name;
        $this->data['Chat']['to'] = $friend_name;
        $this->data['Chat']['conversation'] = $message;
        $this->data['Chat']['status'] = '1';
        $this->data['Chat']['created'] = date('Y-m-d H:i:s');
        $this->Chat->create();
        $this->Chat->save($this->data['Chat']);
        $last_chat_insert_id = $this->Chat->getLastInsertId();


        $condition_chat = "Chat.id = '".$last_chat_insert_id."'";
        $this->Chat->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'from_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'to_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image,Receiver.device_token')
                )
                ));
        $ChatHistory = $this->Chat->find('first',array('conditions' => $condition_chat));
        //pr($ChatHistory);exit;

        $list = array(); 
            
        if(!empty($ChatHistory)){ 


                $device_token = $ChatHistory['Receiver']['device_token'];
                $notification_title = $ChatHistory['Sender']['fname'].' '.$ChatHistory['Sender']['lname']." has sent you a message.";
                $con_chat_count = "Chat.to_id = '".$friend_id."' AND Chat.status = '1'";
                $notification_count =  $this->Chat->find('count',array('conditions' => $con_chat_count));
                $sender_id = $ChatHistory['Sender']['id'];
                
                

                $this->send_push_chat_notification($message,$device_token,$notification_title,$notification_count,$last_chat_insert_id);
                //pr($a);
                //exit;
                $list['id'] = $ChatHistory['Chat']['id'];
                $list['receiver_id'] =  $ChatHistory['Chat']['to_id'];
                $list['receiver_name'] =  $ChatHistory['Receiver']['fname'].' '.$ChatHistory['Receiver']['lname'];
                $list['sender_id'] =  $ChatHistory['Chat']['from_id'];
                $list['sender_name'] =   $ChatHistory['Sender']['fname'].' '.$ChatHistory['Sender']['lname'];
                $list['message'] = $ChatHistory['Chat']['conversation'];
                $list['date_time'] = date('h:i A',strtotime($ChatHistory['Chat']['created']));
                

                if($ChatHistory['Sender']['image']!=""){
                
                    $list['sender_image'] = $base_url.'user_images/thumb/'.$ChatHistory['Sender']['image'];

                }
                else{
                    $list['sender_image'] = $base_url.'images/no_profile_img.jpg';
                }


                if($ChatHistory['Receiver']['image']!=""){
                
                    $list['receiver_image'] = $base_url.'user_images/thumb/'.$ChatHistory['Receiver']['image'];

                }
                else{
                    $list['receiver_image'] = $base_url.'images/no_profile_img.jpg';
                }

                $response['chat_list']= $list; 
                $response['is_error'] = 0;
        }
        else
        {
                $response['is_error'] = 1;
                $response['err_msg'] = 'No message found';
        }
                
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    } 


    function send_push_chat_notification($message=NULL,$registatoin_ids=NULL,$page=NULL,$notification_count=NULL,$last_chat_insert_id=NULL){
       
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];

        $condition_chat = "Chat.id = '".$last_chat_insert_id."'";
        $this->Chat->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'from_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'to_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image,Receiver.device_token')
                )
                ));
        $ChatHistory = $this->Chat->find('first',array('conditions' => $condition_chat));



        $messageArr = array();     
        $list = array();  
        $messageArr['to']  =  $registatoin_ids;       
        $messageArr['notification']['title']  =  $page;     
        $messageArr['notification']['body']   =  $message;
        $messageArr['notification']['sound']   =  'default';
        $messageArr['notification']['icon']   =  'ic_app_launcher';
        $messageArr['data']['notification_count']   =  $notification_count;
        $messageArr['data']['notification_type']   =  'chat_notification';




        $list['chat_id'] = $ChatHistory['Chat']['id'];
        $list['sender_id'] =  $ChatHistory['Chat']['from_id'];
        $list['sender_name'] =   $ChatHistory['Sender']['fname'].' '.$ChatHistory['Sender']['lname'];
        $list['message'] =  $ChatHistory['Chat']['conversation'];
        $list['date_time'] = date('h:i A',strtotime($ChatHistory['Chat']['created']));
        if($ChatHistory['Sender']['image']!=""){
        
            $list['sender_image'] = $base_url.'user_images/thumb/'.$ChatHistory['Sender']['image'];

        }
        else{
            $list['sender_image'] = $base_url.'images/no_profile_img.jpg';
        }

        $messageArr['data']['chat_details']   =  json_encode($list);
        

      
        $message = $messageArr;

        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . "AAAAAqY2LLo:APA91bEW_gfF_X2s4FsBtPOo0YjVrBf9Wu8rnvASP8sYjLUoe6IJBrfd8eUjHOQ2lyJcP_HZtiRRibDxlNc_195JivfqldC4spYVxcOJhArlUQGtgAsyfHjP-UNf1x4o-dvUeZ4ZQLqa",
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
        curl_close ( $ch );
        return $result;
    }
    
    function getLastQuery() {
      $dbo = ConnectionManager::getDataSource('default');
      $logs = $dbo->getLog();
      $lastLog = end($logs['log']);
      return $lastLog['query'];
   }

   function search_site_user_for_friendship(){
       
        $this->layout = ""; 
        $response = array();
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        $response['is_error'] = 0;


        
        //$group_id    = $obj->{'group_id'};
        $user_id    = $obj->{'user_id'};
        $search_text    = $obj->{'search_text'};
        $str_selected_users    = $obj->{'str_selected_users'};
        $arr_selected_users= explode(',', $str_selected_users);
        
        /*$group_id    = 56;
        $user_id    = 138;
        $search_text    = 'tam';
        $str_selected_users    = '';
        $arr_selected_users= explode(',', $str_selected_users);*/

        //$arr_notified_users=array();
            
        $condition_user_list = "User.status = '1' AND (User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND User.id != '".$user_id."'";
        $search_user_list = $this->User->find('all',array('conditions'=>$condition_user_list,'order' => array('User.fname ASC', 'User.lname ASC'))); 
        
        ###############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected starts  #################
            
       /* $con_is_req_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";
        $arr_req_sent_users = $this->Notification->find('all',array('conditions'=>$con_is_req_sent, 'fields'=>array('Notification.receiver_id'))); 
        if(!empty($arr_req_sent_users)){    
            foreach($arr_req_sent_users as $key3 => $val3){ 
                    array_push($arr_notified_users, $val3['Notification']['receiver_id']);      
            }
        }*/
        
        ###############   Fetch the users to whom the friend request is already sent, but not accepted/ rejected ends  #################

            $arr_not_friends=array();

            if(count($search_user_list) > 0)
            {
                foreach($search_user_list as $key => $val)
                {
                    
                    $con_is_friend = "((Friendlist.sender_id = '".$user_id."' AND Friendlist.receiver_id = '".$val['User']['id']."') OR (Friendlist.receiver_id = '".$user_id."' AND Friendlist.sender_id = '".$val['User']['id']."'))";
                    $count_is_friend = $this->Friendlist->find('count',array('conditions'=>$con_is_friend));
                    if($count_is_friend == 0){      // Those who are not the friend
                    
                        if(!empty($arr_selected_users)){        //Those who are not selected
                            if(!in_array($val['User']['id'], $arr_selected_users)){
                                array_push($arr_not_friends, $val); 
                            }
                        }
                        else{
                            array_push($arr_not_friends, $val); 
                        }
                        
                    }
                }
                //$this->set('arr_search_result',$arr_not_group_users); 
            }
            /*else
            { 
                $this->set('arr_search_result',$arr_not_group_users);     
            }*/

            
            $user_search_list = array(); 
            if(!empty($arr_not_friends)){
                foreach($arr_not_friends as $valu){
                    $list['user_id'] = $valu['User']['id'];
                    $list['user_name'] =  $valu['User']['fname'] .' '.$valu['User']['lname'];
                    if($valu['User']['image']!='')
                    {
                        $list['user_image'] =  $base_url.'user_images/thumb/'.$valu['User']['image'];
                    }
                    else
                    {
                        $list['user_image'] =  $base_url.'images/no_profile_img.jpg';
                    }
                    array_push($user_search_list,$list);
                }
                    $response['Userlist']= $user_search_list; 
            }
            else{
                    $response['Userlist'] = array();
            }

        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }

    function submit_friend_request_for_site_user()
    {
      
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            $response['is_error'] = 0;

            $user_id    = $obj->{'user_id'};
            $str_users = $obj->{'str_users'};
            
            $arr_users= explode(',', $str_users);

            if(count($arr_users) == 0 || empty($user_id))
            {
                $response['is_error'] = 1;
                $response['err_msg'] = 'Sorry!! No Friend request sent';
            }

            else
            {
                for($i=0; $i<count($arr_users); $i++)
                {
						 
                        $con_is_req_sent = "Notification.status = '1' AND  Notification.type = 'F' AND ((Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$arr_users[$i]."') OR (Notification.sender_id = '".$arr_users[$i]."' AND Notification.receiver_id = '".$user_id."')) AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";
                        $arr_req_sent = $this->Notification->find('first',array('conditions'=>$con_is_req_sent)); 
                        if(!empty($arr_req_sent)){  
                        
                            $this->Notification->id = $arr_req_sent['Notification']['id'];
                            $this->Notification->delete();
                        }
                        
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

                    $notification_id = $this->Notification->getLastInsertId(); 
                      // send notification email for friend request accept
                    
                    $this->notification_email_friend($notification_id);
                        
                        $page="Friend request";
                        $this->send_push_notification_friend_request($user_id, $arr_users[$i], 'sending_request', $page);
              
                }
                
                $response['success_msg'] = 'Friend request sent successfully';       
            }
                
                      

            header('Content-type: application/json');
            echo json_encode($response);
            exit;   
            
    }

   
    
    function submit_friend_request_for_non_site_user(){

            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];

            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            $response['is_error'] = 0;
            
            $sitesettings = $this->getSiteSettings();
            $user_id    = $obj->{'user_id'};
            $email_list = $obj->{'email_list'};
            
            $arr_emails= explode(',', $email_list);
                    
            $reject_email_list = array();
            $already_request_sent = array();
                    
            for($i=0; $i<count($arr_emails); $i++){
            
                 $cond_email_check = "User.email = '".$arr_emails[$i]."'";   // Check whether the email exists
                 $email_check = $this->User->find('first',array('conditions'=>$cond_email_check));
                
                 if(!empty($email_check)){
                 
                    $condition_friend_list = "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$user_id."' AND Friendlist.receiver_id = '".$email_check['User']['id']."') OR (Friendlist.receiver_id = '".$user_id."' AND Friendlist.sender_id = '".$email_check['User']['id']."'))"; //check the friendship exists

                    $friendlist = $this->Friendlist->find('count',array('conditions'=>$condition_friend_list));
                    if($friendlist >'0')
                    {
                        array_push($reject_email_list,$arr_emails[$i]);     //With the user, the friendship already exists
                    }
                    else{
                    
                        $cond_notification_check = "Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$email_check['User']['id']."' AND Notification.type = 'F'";   // Check notification already exists from one specific user to other user
                        $notification_arr = $this->Notification->find('first',array('conditions'=>$cond_notification_check));
                        if(!empty($notification_arr)){
                            $this->Notification->query("DELETE FROM `notifications` WHERE (`id` = '".$notification_arr['Notification']['id']."')");
                        }
                        
                        
                        $old_user_id = $email_check['User']['id'];
                        $this->data['Notification']['sender_id'] = $user_id;
                        $this->data['Notification']['receiver_id'] = $old_user_id;
                        $this->data['Notification']['type'] = 'F';
                        $this->data['Notification']['group_type'] = 'F';
                        
                        $this->data['Notification']['is_read'] = 0;
                        $this->data['Notification']['is_receiver_accepted'] = 0;
                        $this->data['Notification']['is_reversed_notification'] = 0;
                        $this->data['Notification']['status'] = 1;
                        $this->Notification->create();
                        
                        if($this->Notification->save($this->data['Notification']))
                        {

                            $last_noti_insert_id = $this->Notification->getLastInsertId();
                            $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                            $this->Notification->bindModel(
                            array('belongsTo'=>array(
                                'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                'fields'=>'Sender.id,Sender.fname,Sender.lname'))));
                            $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail));
                          
                            $email = $arr_emails[$i];
                            $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                                    
                            $admin_sender_email = $sitesettings['site_email']['value'];
                            $site_url = $sitesettings['site_url']['value'];
                            //$sender_name = $sitesettings['site_name']['value'];

                            $condition = "EmailTemplate.id = '3'";
                            $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
               
                            $to = $email;

                            $user_subject = $mailDataRS['EmailTemplate']['subject'];
                            $user_subject = str_replace('[SITE NAME]', 'Grouper', $user_subject);
                           
             
                            $user_body = $mailDataRS['EmailTemplate']['content'];
                            $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                            $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                
                            $user_message = stripslashes($user_body);
                
       
                           $string = '';
                           $filepath = '';
                           $filename = '';
                           $sendCopyTo = '';
                   
                   
                            $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);  
                    }
                    
                        
                    }
                }
                 else{
                        $this->data['User']['email'] = trim(preg_replace('/\s+/','',$arr_emails[$i]));
                        $this->data['User']['status'] = 0;
                        $this->data['User']['is_invite'] = 1;
                        $this->data['User']['groups'] = '';
                                    
                        $this->User->create();
                        
                        if($this->User->save($this->data['User'])){         // Creates a dummy User
                            
                            $last_insert_id = $this->User->getLastInsertId();
                            $this->data['Notification']['sender_id'] = $user_id;
                            $this->data['Notification']['receiver_id'] = $last_insert_id;
                            $this->data['Notification']['type'] = 'F';
                            $this->data['Notification']['group_type'] = 'F';
                            
                            $this->data['Notification']['is_read'] = 0;
                            $this->data['Notification']['is_receiver_accepted'] = 0;
                            $this->data['Notification']['is_reversed_notification'] = 0;
                            $this->data['Notification']['status'] = 1;
                            $this->Notification->create();
                            
                            if($this->Notification->save($this->data['Notification'])){     //Send the notification to the dummy user
                                
                                $last_noti_insert_id = $this->Notification->getLastInsertId();
                                $con_noti_detail = "Notification.id = '".$last_noti_insert_id."' AND Notification.status = '1'";
                                $this->Notification->bindModel(
                                  array('belongsTo'=>array(
                                      'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                                      'fields'=>'Sender.id,Sender.fname,Sender.lname')
                                    )
                                    ));
                                $noti_detail = $this->Notification->find('first',array('conditions'=>$con_noti_detail)); // Fetched the Sender Details
                                $email = $arr_emails[$i];
                                $sender_name = ucfirst($noti_detail['Sender']['fname']).' '.ucfirst($noti_detail['Sender']['lname']);
                                
            
                                
                                $admin_sender_email = $sitesettings['site_email']['value'];
                                $site_url = $sitesettings['site_url']['value'];
                                //$sender_name = $sitesettings['site_name']['value'];
            
                                $condition = "EmailTemplate.id = '3'";
                                $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                               
                                $to = $email;
            
                                $user_subject = $mailDataRS['EmailTemplate']['subject'];
                                $user_subject = str_replace('[SITE NAME]', 'Grouper', $user_subject);
                                           
                             
                                $user_body = $mailDataRS['EmailTemplate']['content'];
                                $user_body = str_replace('[SENDER NAME]', $sender_name, $user_body);
                                $user_body = str_replace('[RECEIVER NAME]', $email, $user_body);
                                                
                                $user_message = stripslashes($user_body);
                                
                                $string = '';
                                $filepath = '';
                                $filename = '';
                                $sendCopyTo = '';
                       
                       
                                $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo); 
                            }
                        }
                 }
            }
            
            
            if(!empty($reject_email_list)){
            
                $str_reject_emails= implode(',', $reject_email_list);

                $response['is_error'] = 1;
                $response['err_msg'] = "These are already your friends ".$str_reject_emails;
                
            }
            else{
                $response['success_msg'] = 'Friend request sent successfully';      
                 
            }
            
            header('Content-type: application/json');
            echo json_encode($response);
            exit; 
    }

        function get_counters()
        {
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            

            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            $user_id    = $obj->{'user_id'};

            if(!empty($user_id))
            {

                $con_chat_count = "Chat.to_id = '".$user_id."' AND Chat.status = '1'";
                $chat_notification_count =  $this->Chat->find('count',array('conditions' => $con_chat_count));

                $condition_notification_count = "Notification.receiver_id = '".$user_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.message !='') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
                $notification_count = $this->Notification->find('count',array('conditions'=>$condition_notification_count));

                $response['is_error'] = 0;
                $response['chat_notification_count'] = $chat_notification_count;
                $response['notification_count'] = $notification_count;

            }
        else
        {
            $response['is_error'] = 1;
            $response['err_msg'] = 'User Id does not match';
        }

            header('Content-type: application/json');
            echo json_encode($response);
            exit; 
            
        }
		
		
		function change_posting_status()
		{
		   
				$this->layout = ""; 
				$response = array();
				$defaultconfig = $this->getDefaultConfig();      
				$base_url = $defaultconfig['base_url'];
				$json = file_get_contents('php://input');
				$obj = json_decode($json);
	
	
				$group_user_id   = $obj->{'group_user_id'};
				$can_post_topic    = $obj->{'can_post_topic'};
				
				//$group_user_id   = '7';
				//$can_post_topic    = '0';
	
				
						 
					$this->data['GroupUser']['id'] = $group_user_id;
					$this->data['GroupUser']['can_post_topic'] = $can_post_topic;
							   
					
					if($this->GroupUser->save($this->data))
					{
					  $response['is_error'] = 0;  
					  
					}
	
					else
					{
					  $response['is_error'] = 1;
					  $response['err_msg'] = 'Status update uncessful';
					}
	
		   
			
			header('Content-type: application/json');
			echo json_encode($response);
			exit;
			
		}






    function send_group_message(){
    
        $this->layout = ''; 
        
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        //$json ='{"user_type":"O","group_id":"2","1":"1","group_type":"F","user_id":"2","message":"dfsdsdfs","topic":"ipgddgsdshone"}';
        $obj = json_decode($json);

        $user_type = $obj->{'user_type'};
        $group_id = $obj->{'group_id'};
        $user_id = $obj->{'user_id'};
        $group_type = $obj->{'group_type'};
        $message = $obj->{'message'};
        $topic = $obj->{'topic'};
        
        if($response['is_error'] == 0){

            $this->data['GroupMessage']['user_type'] = $user_type;
            $this->data['GroupMessage']['group_id'] = $group_id;
            $this->data['GroupMessage']['user_id'] = $user_id;
            $this->data['GroupMessage']['group_type'] = $group_type;
            $this->data['GroupMessage']['message'] = $message;
            $this->data['GroupMessage']['topic'] = $topic;
            $this->GroupMessage->create();
            if($this->GroupMessage->save($this->data['GroupMessage']))
            {
                $response['is_error'] = 0;
            }
            else
            {
                $response['is_error'] = 1;
            }
                     
        }
     
     
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }



    function group_message_list(){
    
        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $user_id    = $obj->{'user_id'};
		//$user_type    = $obj->{'user_type'};
        $group_id    = $obj->{'group_id'};
		//$group_type    = $obj->{'group_type'};
		$can_post    = $obj->{'can_post'};
		/*$user_id= 7;
		$user_type= 'O';
        $group_id = 74;
		$group_type= 'F';*/
		
		$condn_group_user_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
        $arr_group_user_status = $this->GroupUser->find('first',array('conditions'=>$condn_group_user_status, 'fields' => 'GroupUser.user_type'));     
		$user_type=  $arr_group_user_status['GroupUser']['user_type'];
		
		$condn_group_type = "Group.id = '".$group_id."'";
        $arr_group_type = $this->Group->find('first',array('conditions'=>$condn_group_type, 'fields' => 'Group.group_type'));     
		$group_type=  $arr_group_type['Group']['group_type'];
		
    
        $conditions = "GroupMessage.group_id = '".$group_id."' AND GroupMessage.status = '1'";
        $this->GroupMessage->bindModel(array('hasMany' => array('GroupMessageReply' => array('foreignKey' => 'message_id'))),false);
        $this->GroupMessage->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.fname,User.lname,User.image,User.username'))),false);
        $ArGroupMessages = $this->GroupMessage->find('all',array('conditions'=>$conditions,'order' => 'GroupMessage.id DESC'));
        
/*		echo '<pre>';
        print_r($ArGroupMessages);
        exit;*/
        $message = array(); 
        
        if(count($ArGroupMessages) > 0)
        { 
            foreach($ArGroupMessages as $key=>$GroupMessage)
            {
                $message[$key]['id'] = $GroupMessage['GroupMessage']['id'];
                $message[$key]['topic'] =  ucfirst(stripslashes($GroupMessage['GroupMessage']['topic']));
                $message[$key]['message'] =  ucfirst(stripslashes($GroupMessage['GroupMessage']['message']));

                $message[$key]['created'] =  date('Y-m-d h:i A',strtotime($GroupMessage['GroupMessage']['created']));

                $message[$key]['total_reply'] = count($GroupMessage['GroupMessageReply']);

                //$message[$key]['user_id'] = $GroupMessage['User']['id'];
                $message[$key]['first_name'] = is_null($GroupMessage['User']['fname']) ? '' : ucfirst($GroupMessage['User']['fname']) ;
                $message[$key]['last_name'] = is_null($GroupMessage['User']['lname']) ? '' : ucfirst($GroupMessage['User']['lname']) ;
                $message[$key]['username'] = is_null($GroupMessage['User']['username']) ? '' : $GroupMessage['User']['username'];
                
                if($GroupMessage['User']['image']!="")
                {
                $message[$key]['user_image'] = $base_url.'user_images/thumb/'.$GroupMessage['User']['image'];
                }
                else
                {
                $message[$key]['user_image'] = $base_url.'images/no_profile_img.jpg';
                }
				
				if($group_type=='F'){
					if($user_type=='O' || $user_id == $GroupMessage['GroupMessage']['user_id']){
						$message[$key]['can_edit'] = 1;
						$message[$key]['can_delete'] = 1;
					}
					else{
						$message[$key]['can_edit'] = 0;
						$message[$key]['can_delete'] = 0;
					}
				}
				else if($group_type=='B'){
					if($user_type=='O'){
						$message[$key]['can_edit'] = 1;
						$message[$key]['can_delete'] = 1;
					}
					else{
						$message[$key]['can_edit'] = 0;
						$message[$key]['can_delete'] = 0;
					}
				}
            }
            $response['message_list']= $message; 
            $response['is_error'] = 0;
        }
        
        else {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Messages found';
        }
        //pr($response );exit;
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
     
     }


     /*function update_user_type()
     {
        $conditions = "Group.status = '1'";
        $ArGroups = $this->Group->find('all',array('conditions' =>  $conditions,'fields' =>'Group.group_owners,Group.id'));
        //pr($ArGroups);
        foreach ($ArGroups as $key => $value) 
        {
           $ar_owner_explode = explode(',',$value['Group']['group_owners']);

           foreach($ar_owner_explode as $owner_explode)
           {
                 $group_id = $value['Group']['id'];
                 $owner_id = $owner_explode;

                $this->GroupUser->query("UPDATE `group_users` SET `user_type` = 'O' WHERE `group_id` = '".$group_id."' AND `user_id` = '".$owner_id."'");

           }
        }

        exit;
     }*/




     function group_message_reply_list(){
    
        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $user_id  = $obj->{'user_id'};
        $topic_id = $obj->{'topic_id'};
        $can_edit = 0;
        $can_delete = 0;
        //$topic_id = 1;
        /*$user_id    = 6;
        $topic_id    = 4;*/
    
        $conditions = "GroupMessage.id = '".$topic_id."'";
        $this->GroupMessage->bindModel(array('hasMany' => array('GroupMessageReply' => array('foreignKey' => 'message_id','order' => 'GroupMessageReply.id ASC'))),false);
        $this->GroupMessage->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.fname,User.lname,User.image,User.username'))),false);
        $ArGroupMessages = $this->GroupMessage->find('first',array('conditions'=>$conditions));
        
        //pr($ArGroupMessages);exit;
        //exit;
        $message_reply = array(); 
        
        if(count($ArGroupMessages) > 0)
        {
            $group_id = $ArGroupMessages['GroupMessage']['group_id'];
            $response['id'] = $ArGroupMessages['GroupMessage']['id'];
            $response['topic'] =  ucfirst(stripslashes($ArGroupMessages['GroupMessage']['topic']));
            $response['message'] =  ucfirst(stripslashes($ArGroupMessages['GroupMessage']['message']));

            $response['created'] =  date('Y-m-d h:i A',strtotime($ArGroupMessages['GroupMessage']['created']));

            $response['total_reply'] = count($ArGroupMessages['GroupMessageReply']);

            //$message[$key]['user_id'] = $GroupMessage['User']['id'];
            $response['first_name'] = is_null($ArGroupMessages['User']['fname']) ? '' : ucfirst($ArGroupMessages['User']['fname']) ;
            $response['last_name'] = is_null($ArGroupMessages['User']['lname']) ? '' : ucfirst($ArGroupMessages['User']['lname']) ;
            $response['username'] = is_null($ArGroupMessages['User']['username']) ? '' : $ArGroupMessages['User']['username'];
            
            if($ArGroupMessages['User']['image']!="")
            {
            $response['user_image'] = $base_url.'user_images/thumb/'.$ArGroupMessages['User']['image'];
            }
            else
            {
            $response['user_image'] = $base_url.'images/no_profile_img.jpg';
            } 

            foreach($ArGroupMessages['GroupMessageReply'] as $key=>$GroupMessage)
            {

                $con_user_type = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
                $ArUserType = $this->GroupUser->find('first',array('conditions' => $con_user_type, 'fields' => 'GroupUser.user_type'));

                //pr($ArUserType);

                $conditions1 = "User.id = '".$GroupMessage['replied_by']."'";
                $ReplyUser = $this->User->find('first',array('conditions'=>$conditions1, 'fields'=>'User.fname,User.lname,User.image,User.username'));

                $message_reply[$key]['reply_id'] = $GroupMessage['id'];
                $message_reply[$key]['reply'] =  ucfirst(stripslashes($GroupMessage['reply']));
				
				$now = strtotime(date('Y-m-d H:i:s'));
            	$post_date = strtotime($GroupMessage['created']);
            	$reply_posted_time = $this->TimeCalculate($now,$post_date);
                $message_reply[$key]['created'] =  $reply_posted_time;

                $message_reply[$key]['reply_first_name'] = is_null($ReplyUser['User']['fname']) ? '' : ucfirst($ReplyUser['User']['fname']) ;
                $message_reply[$key]['reply_last_name'] = is_null($ReplyUser['User']['lname']) ? '' : ucfirst($ReplyUser['User']['lname']) ;
                $message_reply[$key]['reply_username'] = is_null($ReplyUser['User']['username']) ? '' : $ReplyUser['User']['username'];


                if($GroupMessage['replied_by'] == $user_id)
                {
                    $can_edit = 1;
                    $can_delete = 1;
                }
                else if($ArUserType['GroupUser']['user_type'] == 'O')
                {
                    $can_edit = 1;
                    $can_delete = 1;
                }



                $message_reply[$key]['can_edit'] = $can_edit;
                $message_reply[$key]['can_delete'] = $can_delete;
                
                if($ReplyUser['User']['image']!="")
                {
                $message_reply[$key]['reply_user_image'] = $base_url.'user_images/thumb/'.$ReplyUser['User']['image'];
                }
                else
                {
                $message_reply[$key]['reply_user_image'] = $base_url.'images/no_profile_img.jpg';
                }
                
            }

            //pr($message_reply);
            $response['reply_list']= $message_reply; 
            $response['is_error'] = 0;
        }
        
        else {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Replies found';
        }
        //pr($response );exit;
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
     
    }




    function send_topic_reply(){
    
        $this->layout = ''; 
        
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        //$json ='{"user_type":"O","group_id":"2","1":"1","group_type":"F","user_id":"2","message":"dfsdsdfs","topic":"ipgddgsdshone"}';
        $obj = json_decode($json);

        $group_id = $obj->{'group_id'};
        $message_id = $obj->{'message_id'};
        $replied_by = $obj->{'replied_by'};
        $reply = $obj->{'reply'};
        
        if($response['is_error'] == 0){

            $this->data['GroupMessageReply']['group_id'] = $group_id;
            $this->data['GroupMessageReply']['message_id'] = $message_id;
            $this->data['GroupMessageReply']['replied_by'] = $replied_by;
            $this->data['GroupMessageReply']['reply'] = $reply;
            $this->GroupMessageReply->create();
            if($this->GroupMessageReply->save($this->data['GroupMessageReply']))
            {
                $response['is_error'] = 0;
            }
            else
            {
                $response['is_error'] = 1;
            }
                     
        }
     
     
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }



    function edit_group_message(){
    
        $this->layout = ''; 
        
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        //$json ='{"topic_id":"O","user_id":"2","user_type":"1","group_type":"F","group_id":"2","message":"updated message","topic":"updated topic"}';
        $obj = json_decode($json);

        $topic_id = $obj->{'topic_id'};
        $user_id = $obj->{'user_id'};
        $user_type = $obj->{'user_type'};
        $group_id = $obj->{'group_id'};
        $group_type = $obj->{'group_type'};
        $message = $obj->{'message'};
        $topic = $obj->{'topic'};

        $conditions = "GroupMessage.id = '".$topic_id."'";
        $ArGroupMessage = $this->GroupMessage->find('first',array('conditions'=>$conditions));
        
        
        if(!empty($ArGroupMessage)){
			
			$condn_group_user_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
			$arr_group_user_status = $this->GroupUser->find('first',array('conditions'=>$condn_group_user_status, 'fields' => 'GroupUser.user_type'));     
			$user_type=  $arr_group_user_status['GroupUser']['user_type'];
			
			$condn_group_type = "Group.id = '".$group_id."'";
			$arr_group_type = $this->Group->find('first',array('conditions'=>$condn_group_type, 'fields' => 'Group.group_type'));     
			$group_type=  $arr_group_type['Group']['group_type'];
			
			if($group_type=='F'){
				if($user_type=='O' || $user_id == $ArGroupMessage['GroupMessage']['user_id']){
					$can_edit = 1;
				}
				else{
					$can_edit = 0;
				}
			}
			else if($group_type=='B'){
				if($user_type=='O'){
					$can_edit = 1;
				}
				else{
					$can_edit = 0;
				}
			}
			
			if($can_edit==1){
				$this->data['GroupMessage']['id'] = $topic_id;
				$this->data['GroupMessage']['topic'] = $topic;
				$this->data['GroupMessage']['message'] = $message;
				if($this->GroupMessage->save($this->data['GroupMessage']))
				{
					$response['is_error'] = 0;
				}
				else
				{
					$response['is_error'] = 1;
				}
			}
			else{
				$response['is_error'] = 1;
            	$response['err_msg'] = 'Sorry !!! You are not authorized to Edit';
			}
                     
        }else{
			$response['is_error'] = 1;
            $response['err_msg'] = 'No Records found';
        }
     
     
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }




    function delete_group_message(){
    
        $this->layout = ''; 
        
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        //$json ='{"user_type":"O","group_id":"2","1":"1","group_type":"F","user_id":"2","message":"dfsdsdfs","topic":"ipgddgsdshone"}';
        $obj = json_decode($json);

        $topic_id = $obj->{'topic_id'};
        $user_id = $obj->{'user_id'};
        //$user_type = $obj->{'user_type'};
		$group_id = $obj->{'group_id'};
        

        $conditions = "GroupMessage.id = '".$topic_id."'";
        $ArGroupMessage = $this->GroupMessage->find('first',array('conditions'=>$conditions));
        
        
        if(!empty($ArGroupMessage)){
		
			$condn_group_user_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
			$arr_group_user_status = $this->GroupUser->find('first',array('conditions'=>$condn_group_user_status, 'fields' => 'GroupUser.user_type'));     
			$user_type=  $arr_group_user_status['GroupUser']['user_type'];
			
			$condn_group_type = "Group.id = '".$group_id."'";
			$arr_group_type = $this->Group->find('first',array('conditions'=>$condn_group_type, 'fields' => 'Group.group_type'));     
			$group_type=  $arr_group_type['Group']['group_type'];
			
			if($group_type=='F'){
				if($user_type=='O' || $user_id == $ArGroupMessage['GroupMessage']['user_id']){
					$can_delete = 1;
				}
				else{
					$can_delete = 0;
				}
			}
			else if($group_type=='B'){
				if($user_type=='O'){
					$can_delete = 1;
				}
				else{
					$can_delete = 0;
				}
			}

			if($can_delete==1){
			
				$this->GroupMessage->id = $topic_id;
				if($this->GroupMessage->delete())
				{
					$response['is_error'] = 0;
				}
				else
				{
					$response['is_error'] = 1;
				}
			}
			else{
				$response['is_error'] = 1;
            	$response['err_msg'] = 'Sorry !!! You are not authorized to Delete';
			}
                     
        }else{
			$response['is_error'] = 1;
            $response['err_msg'] = 'No Records found';
        }
     
     
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }


    function edit_reply_message(){
    
        $this->layout = ''; 
        
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        //$json ='{"topic_id":"O","user_id":"2","user_type":"1","group_type":"F","group_id":"2","message":"updated message","topic":"updated topic"}';
        $obj = json_decode($json);

        $can_edit = 0;
        $reply_id = $obj->{'reply_id'};
        $reply = $obj->{'reply'};
        $user_id = $obj->{'user_id'};
        $group_id = $obj->{'group_id'};
        

        $conditions = "GroupMessageReply.id = '".$reply_id."'";
        $ArGroupMessageReply = $this->GroupMessageReply->find('first',array('conditions'=>$conditions));
        
        
        if(!empty($ArGroupMessageReply))
        {

            $con_user_type = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
            $ArUserType = $this->GroupUser->find('first',array('conditions' => $con_user_type, 'fields' => 'GroupUser.user_type'));

            if($ArGroupMessageReply['GroupMessageReply']['replied_by'] == $user_id)
            {
                $can_edit=1;
            }
            else if($ArUserType['GroupUser']['user_type'] == 'O')
            {
                $can_edit=1;
            }
            
            if($can_edit==1){
                $this->data['GroupMessageReply']['id'] = $reply_id;
                $this->data['GroupMessageReply']['reply'] = $reply;
                if($this->GroupMessageReply->save($this->data['GroupMessageReply']))
                {
                    $response['is_error'] = 0;
                }
                else
                {
                    $response['is_error'] = 1;
                }
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'Sorry !!! You are not authorized to Edit';
            }
                     
        }else{
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Records found';
        }
     
     
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }



    function delete_reply_message(){
    
        $this->layout = ''; 
        
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        //$json ='{"topic_id":"O","user_id":"2","user_type":"1","group_type":"F","group_id":"2","message":"updated message","topic":"updated topic"}';
        $obj = json_decode($json);

        $can_delete = 0;
        $reply_id = $obj->{'reply_id'};
        $user_id = $obj->{'user_id'};
        $group_id = $obj->{'group_id'};

        $conditions = "GroupMessageReply.id = '".$reply_id."'";
        $ArGroupMessageReply = $this->GroupMessageReply->find('first',array('conditions'=>$conditions));
        
        
        if(!empty($ArGroupMessageReply))
        {
            
            $con_user_type = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
            $ArUserType = $this->GroupUser->find('first',array('conditions' => $con_user_type, 'fields' => 'GroupUser.user_type'));

            if($ArGroupMessageReply['GroupMessageReply']['replied_by'] == $user_id)
            {
                $can_delete=1;
            }
            else if($ArUserType['GroupUser']['user_type'] == 'O')
            {
                $can_delete=1;
            }
            
            if($can_delete==1){
                $this->GroupMessageReply->id = $reply_id;
                if($this->GroupMessageReply->delete())
                {
                    $response['is_error'] = 0;
                }
                else
                {
                    $response['is_error'] = 1;
                }
            }
            else{
                $response['is_error'] = 1;
                $response['err_msg'] = 'Sorry !!! You are not authorized to Delete';
            }
                     
        }else{
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Records found';
        }
     
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }


    function organisation_category_list(){
    
        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $user_id    = $obj->{'user_id'};
        //$user_id = 2;
        
    
        $condition2 = "Category.POG_status ='1' AND Category.status ='1'";
        $all_category = $this->Category->find('all',array('conditions'=>$condition2,'order' => 'Category.title ASC'));
        
        //pr($all_category);exit;
        $category = array(); 
        
        if(count($all_category) > 0)
        { 
            $i=0;
            foreach($all_category as $cat)
            {
                $category[$i]['id'] = $cat['Category']['id'];
                $category[$i]['title'] =  ucfirst(stripslashes($cat['Category']['title']));
                $category[$i]['desc'] =  ucfirst(stripslashes($cat['Category']['category_desc']));
                if(!empty($cat['Category']['image']))
                   {
                   $category[$i]['image_url']['thumb'] = $base_url.'category_photos/thumb/'.$cat['Category']['image'];
                   $category[$i]['image_url']['medium'] = $base_url.'category_photos/android/medium/'.$cat['Category']['image'];
                   $category[$i]['image_url']['original'] = $base_url.'category_photos/'.$cat['Category']['image'];
                   }
                else
                   {
                   $category[$i]['image_url']['thumb'] = $base_url.'images/no_profile_img.jpg';
                   $category[$i]['image_url']['medium'] = $base_url.'images/no_profile_img.jpg';
                   $category[$i]['image_url']['original'] = $base_url.'images/no_profile_img.jpg';
                   }
                $i=$i+1;
            }
            $response['category']= $category; 
            $response['is_error'] = 0;
        }
        
        else {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Categories found';
        }
        //pr($response );exit;
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
     
     }
	 
	function business_category_list(){
    
        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $response = array();
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
        
        $user_id    = $obj->{'user_id'};
        //$user_id = 2;
        
    
        $condition2 = "Category.POG_status ='0' AND Category.status ='1'";
        $all_category = $this->Category->find('all',array('conditions'=>$condition2,'order' => 'Category.title ASC'));
        
        //pr($all_category);exit;
        $category = array(); 
        
        if(count($all_category) > 0)
        { 
            $i=0;
            foreach($all_category as $cat)
            {
                $category[$i]['id'] = $cat['Category']['id'];
                $category[$i]['title'] =  ucfirst(stripslashes($cat['Category']['title']));
                $category[$i]['desc'] =  ucfirst(stripslashes($cat['Category']['category_desc']));
                if(!empty($cat['Category']['image']))
                   {
                   $category[$i]['image_url']['thumb'] = $base_url.'category_photos/thumb/'.$cat['Category']['image'];
                   $category[$i]['image_url']['medium'] = $base_url.'category_photos/android/medium/'.$cat['Category']['image'];
                   $category[$i]['image_url']['original'] = $base_url.'category_photos/'.$cat['Category']['image'];
                   }
                else
                   {
                   $category[$i]['image_url']['thumb'] = $base_url.'images/no_profile_img.jpg';
                   $category[$i]['image_url']['medium'] = $base_url.'images/no_profile_img.jpg';
                   $category[$i]['image_url']['original'] = $base_url.'images/no_profile_img.jpg';
                   }
                $i=$i+1;
            }
            $response['category']= $category; 
            $response['is_error'] = 0;
        }
        
        else {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Categories found';
        }
        //pr($response );exit;
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
     
     }
	 
	 
	 function add_sub_group(){
        
     	$this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $response = array();
		$response['is_error'] = 0;
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
       
		$user_id = $_POST['user_id'];  
		$selected_state_id = $_POST['state_id'];
		$selected_city_id = $_POST['city_id'];
		$parent_group_id = $_POST['parent_group_id'];
		$group_title = $_POST['group_title']; 
        $group_desc = $_POST['group_desc'];  
		$group_user_type = $_POST['group_user_type'];  


       
        $condition = "User.status= '1'  AND User.id= '".$user_id."'";
        $user_details = $this->User->find("first",array('conditions'=>$condition));
        
        $upload_image = '';
        
        if(isset($_FILES["upload_image"]) && $_FILES["upload_image"]['name']!= ''){
            
            $image_name = $_FILES["upload_image"]['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'sub_group_images/'.$upload_image;
                        
            $imagelist = getimagesize($_FILES["upload_image"]['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
            if($type == 1 || $type == 2){
            
                if($uploaded_width >=160 && $uploaded_height >= 120){
                
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)){
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'sub_group_images/thumb/'.$upload_image;
                            $upload_target_medium = 'sub_group_images/medium/'.$upload_image;
                            $upload_target_web = 'sub_group_images/web/'.$upload_image;
                            
                            
                            $max_web_width =  262;
                            $max_web_height = 178;
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_web, $max_web_width, $max_web_height, 100, true);
                        
                                                                                    
                    }           
                    else{
                        
                        $response['is_error'] = 1;
                        $response['err_msg'] = 'Image upload failed !!!';
                
                    }
                
                }
                else
                {        
                    $response['is_error'] = 1;
                    $response['err_msg'] = 'Please upload a 200x100 or bigger image only';
                }
                
            }
            else{
            
                $response['is_error'] = 1;
                $response['err_msg'] = 'Please upload jpg,jpeg and gif image only';
            }
        }
        else{
        
            $response['is_error'] = 1;
            $response['err_msg'] = 'Please upload Group image !!!';
        }
		
		
		if($response['is_error'] == 0){
        
			$con_grp = "Group.id = '".$parent_group_id."'"; 
			$arr_grp_detail = $this->Group->find('first',array('conditions'=>$con_grp));  
			$parent_grp_title= stripslashes($arr_grp_detail['Group']['group_title']);
			$sub_grp_title= $parent_grp_title.' - '.$group_title;
				
				
			$this->data['Group']['parent_id'] = $parent_group_id;
			$this->data['Group']['group_title'] = addslashes($sub_grp_title);
			$this->data['Group']['group_desc'] = addslashes($group_desc);
			$this->data['Group']['group_type'] = 'F';    
			$this->data['Group']['group_owners'] = $user_id;
			$this->data['Group']['created_by'] = $user_id;
			$this->data['Group']['category_id'] = '0';
			$this->data['Group']['icon'] = $upload_image;
			$this->data['Group']['state_id'] = $selected_state_id;
			$this->data['Group']['city_id'] = $selected_city_id;
			if($group_user_type=='member'){
				$this->data['Group']['status'] = '0';
			}
			else if($group_user_type=='owner'){
				$this->data['Group']['status'] = '1';
			}
			else if($group_user_type=='nonmember'){
				$this->data['Group']['status'] = '0';
			}

			$this->Group->create();
			
			if($this->Group->save($this->data['Group'])){
			 
				$last_insert_id = $this->Group->getLastInsertId();
					
				if($group_user_type=='member'){
				
					 $condition = "Group.id= '".$parent_group_id."'";
					 $group_details = $this->Group->find("first",array('conditions'=>$condition));
					 $str_group_owners= $group_details['Group']['group_owners'];
					 $arr_group_owners = explode(',',$str_group_owners);
					 
					 for($k=0;$k<count($arr_group_owners);$k++){
						
							 $this->data['Notification']['type'] = 'SG';
							 $this->data['Notification']['sender_id'] = $user_id;
							 $this->data['Notification']['sender_type'] = 'GO';
							 $this->data['Notification']['request_mode'] = 'public';
							 $this->data['Notification']['receiver_id'] = $arr_group_owners[$k];
							 $this->data['Notification']['receiver_type'] =  'NGM';
							 $this->data['Notification']['group_type'] =  'F';
							 $this->data['Notification']['group_id'] = $last_insert_id;
							 $this->data['Notification']['is_receiver_accepted'] = '0';
							 $this->data['Notification']['is_reversed_notification'] =  '0';
							 $this->data['Notification']['is_read'] =  '0';
							 
							 $this->Notification->create();
							 $this->Notification->save($this->data);
							 
							 $last_insert_noti_id = $this->Notification->getLastInsertId(); 

							 $this->notification_email($last_insert_noti_id);  // sending email to notification sender
							  
							 $group_id= $last_insert_id;
							 $page="Request to create Sub Group";
							 $this->send_notification_sub_group_creation_request($user_id, $arr_group_owners[$k], $group_id, $page);
					 }
					 
					 $response['success_msg'] = "The Sub Group Created Successfully !!!";    
				}
				else if($group_user_type=='owner'){
				
					if(!empty($user_details['User']['groups']))
					{

						$this->data['User']['id'] = $user_id;
						$this->data['User']['groups'] = $user_details['User']['groups'].",".$last_insert_id;
						$this->User->save($this->data);
						
					}
					else
					{
					   $this->data['User']['id'] = $user_id;
					   $this->data['User']['groups'] = $last_insert_id;
					   $this->User->save($this->data);
					}

					$this->data['GroupUser']['group_id'] = $last_insert_id;
					$this->data['GroupUser']['user_type'] = 'O';
					$this->data['GroupUser']['user_id'] = $user_id;
					$this->GroupUser->create();
					
				   if($this->GroupUser->save($this->data['GroupUser'])){
				   
						 $response['success_msg'] = "The Sub Group Created Successfully !!!"; 
					}
				}
				else if($group_user_type=='nonmember'){
				
					 $condition = "Group.id= '".$parent_group_id."'";
					 $group_details = $this->Group->find("first",array('conditions'=>$condition));
					 $str_group_owners= $group_details['Group']['group_owners'];
					 $arr_group_owners = explode(',',$str_group_owners);
					 
					 for($k=0;$k<count($arr_group_owners);$k++){
						
							 $this->data['Notification']['type'] = 'SG';
							 $this->data['Notification']['sender_id'] = $user_id;
							 $this->data['Notification']['sender_type'] = 'GO';
							 $this->data['Notification']['request_mode'] = 'public';
							 $this->data['Notification']['receiver_id'] = $arr_group_owners[$k];
							 $this->data['Notification']['receiver_type'] =  'NGM';
							 $this->data['Notification']['group_type'] =  'F';
							 $this->data['Notification']['group_id'] = $last_insert_id;
							 $this->data['Notification']['is_receiver_accepted'] = '0';
							 $this->data['Notification']['is_reversed_notification'] =  '0';
							 $this->data['Notification']['is_read'] =  '0';
							 
							 $this->Notification->create();
							 $this->Notification->save($this->data);
							 
							 $last_insert_noti_id = $this->Notification->getLastInsertId(); 

							 $this->notification_email($last_insert_noti_id);  // sending email to notification sender
							  
							 $group_id= $last_insert_id;
							 $page="Request to create Sub Group";
							 $this->send_notification_sub_group_creation_request($user_id, $arr_group_owners[$k], $group_id, $page);
					 }
					 
					 $response['success_msg'] = "The Sub Group Created Successfully !!!"; 
				} 
			} 
            
        }
     
     
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
		
   }

    
}
?>


