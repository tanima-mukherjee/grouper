<?php
App::import('Vendor','authnet_aim');
class GroupController extends AppController {

    var $name = 'Group';
    var $uses = array('Category','Group','SubscriptionPlan','User','GroupUser','City','State','GroupImage','GroupDoc','Video','Notification','Friendlist','Event');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    

    function group_list($category_id){
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        $this->_checkSessionUser();

  
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);


         /*     $selected_state_id = '132';
                $selected_city_id = '10541';
        */
        $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
        $ArState = $this->State->find('all', array('conditions' => $conditions));
        $this->set('ArState', $ArState);


         $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
         $StateName = $this->State->find('first', array('conditions' => $conditions));
         $this->set('StateName', $StateName);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
        $CityName = $this->City->find('first', array('conditions' => $conditions));
        $this->set('CityName', $CityName);

       
        $user_id = $this->Session->read('userData.User.id');
        $this->set('user_id',$user_id);

        $this->set('category_id',$category_id);
        
        $condition = "Category.status= '1'  AND Category.id= '".$category_id."'";
        $category_details = $this->Category->find("first",array('conditions'=>$condition));
       // pr($featured_users);exit();
        $this->set('category_details',$category_details);

        $limit = 8;
		$conditions_group = "Group.status= '1'  AND Group.category_id= '".$category_id."' AND Group.state_id= '".$selected_state_id."'  AND Group.city_id= '".$selected_city_id."'";
        $this->paginate = array('conditions' =>$conditions_group,'limit' => $limit,'order'=>'Group.group_title ASC');
		$this->Group->bindModel(array('hasMany' => array('GroupUser' => array('foreignKey' => 'group_id'))),false);
		$group_list = $this->paginate('Group');

       
        //$plan_list = $this->SubscriptionPlan->find("all");
        //$this->set('plan_list',$plan_list);
        $this->set('group_list',$group_list);

    }





	function show_city()
	{
		$stateid = $_REQUEST['state_id'];
		$condition = "City.isdeleted = '0' AND City.state_id='".$stateid."'";
		$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
		$this->set('citylist',$citylist);
       // $this->Session->write('selected_state_id', $stateid);
	}

    function selected_city()
    {
        $cityid = $_REQUEST['city_id'];
        $condition = "City.isdeleted = '0' AND City.id='".$cityid."'";
        $citylist = $this->City->find("first",array('conditions'=>$condition,'fields'=>array('City.id','City.name','City.state_id')));
        $this->Session->write('selected_state_id', $citylist['City']['state_id']);
        $this->Session->write('selected_city_id', $cityid);
        echo "success";exit();
    }


    function group_type($group_id)
	{

	   $this->_checkSessionUser();

		  $this->layout = "";
		  $user_id = $this->Session->read('userData.User.id');
		  $this->set('pageTitle', 'Group Type');
		   
		  $conditions = "GroupUser.group_id =  '".$group_id."' AND GroupUser.user_id =  '".$user_id."' ";

		  $GroupType = $this->GroupUser->find('first', array('conditions' => $conditions));
		
		  return $GroupType;
		   
	}


      function group_is_join($group_id)
      {

          $this->_checkSessionUser();

              $this->layout = "";
              $user_id = $this->Session->read('userData.User.id');
              $this->set('pageTitle', 'Group Is Join');

              $conditions = "Group.id =  '".$group_id."'";
              $groupd = $this->Group->find('first', array('conditions' => $conditions));

              $condition = "Notification.sender_id = '".$user_id."' AND Notification.status = '1' AND Notification.receiver_id IN (".$groupd['Group']['group_owners'].")  AND Notification.group_id = '".$group_id."' AND  Notification.is_receiver_accepted = '0' AND  Notification.is_reversed_notification = '0' AND Notification.type = 'G' ";
              
              $GroupIsJoin = $this->Notification->find('count',array('conditions'=>$condition));  



                            
              return $GroupIsJoin;
               
       }


        function group_member_count($group_id)
        {

          // $this->_checkSessionUser();

              $this->layout = "";
              $user_id = $this->Session->read('userData.User.id');
              $this->set('pageTitle', 'Group Member Count');
              
                $condition4 = "GroupUser.status= '1' AND  GroupUser.group_id = '".$group_id."'";
                $GroupMemberCount = $this->GroupUser->find('count',array('conditions'=>$condition4));
                    
            
               return $GroupMemberCount;
               //pr($UserDetailType);exit();

         
              
        }

    


     /*function add_business_category_group() {

       $this->layout = "";
        $this->set('pagetitle', 'Create Group');
        $sitesettings = $this->getSiteSettings(); 
         $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');

        $condition = "User.status= '1'  AND User.id= '".$user_id."'";
        $user_details = $this->User->find("first",array('conditions'=>$condition));
       // pr($featured_users);exit();
        $this->set('user_details',$user_details);


        
        $upload_image = '';
        //$is_upload = 1; 
     
      if(!empty($this->params['form']) && isset($this->params['form'])){
        if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= '')
        
        {
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'group_images/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=640 && $uploaded_height >= 480)
                {
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'group_images/thumb/'.$upload_image;
                            $upload_target_medium = 'group_images/medium/'.$upload_image;
                            $upload_target_web = 'group_images/web/'.$upload_image;
                           

                            $max_web_width =  262;
                            $max_web_height = 178;
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_web, $max_web_width, $max_web_height, 100, true);
                         $is_upload = 1;
                                                                                    
                      }         
                    else  
                    {
                        
                    $is_upload = 0;
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    
                    $this->redirect("/group/group_list/".$this->params['form']['category_id']);
                
                    }
                
                }
                else
                {        
                 
                 $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect("/group/group_list/".$this->params['form']['category_id']);
                
                }
                
             }
             else
             {
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect("/group/group_list/".$this->params['form']['category_id']);
              
             }
        
        }
       
        
        if(!empty($this->params['form']['plan_id']) && isset($this->params['form']['plan_id']))
        { 
        
          $plan_id = $this->params['form']['plan_id'];
          
          $con_plan = "SubscriptionPlan.id = '".$plan_id."'";
          $ArPlan = $this->SubscriptionPlan->find('first',array('conditions' => $con_plan));
          
          
          $amount = $ArPlan['SubscriptionPlan']['amount'];
          $this->set('amount',$amount);
        }

       
                try
                {
                    /*$user_id = 1;
                    $email   = 'johnny1@example.com';
                    $product = 'A test transaction';
                    $business_firstname = 'John';
                    $business_lastname  = 'Smith';
                    $business_address   = '123 Main Street';
                    $business_city      = 'Townsville';
                    $business_state     = 'NJ';
                    $business_zipcode   = '12345';
                    $business_telephone = '800-555-1234';
                    $shipping_firstname = 'John';
                    $shipping_lastname  = 'Smith';
                    $shipping_address   = '100 Business Rd';
                    $shipping_city      = 'Big City';
                    $shipping_state     = 'NY';
                    $shipping_zipcode   = '10101'; 
                 
                    $creditcard = '4111-1111-1111-1111';
                    $expiration = '12-2016';
                    $total      = 1.00;
                    $cvv        = 123;
                    $invoice    = substr(time(), 0, 6);
                    $tax        = 0.00;  */
/*                    $email = $user_details['User']['email'];
                    //$password = 'landscape'; 
                    $admin_email = $sitesettings['sender_email']['value'];
                    $to =  $email;
                    $site_url = $sitesettings['site_url']['value'];
                    $plan_id = $plan_id;
                    $amount = $amount;
                    $first_name = $user_details['User']['fname']; 
                    $last_name = $user_details['User']['lname']; 
                    /*$business_address =  $this->params['form']['address']; 
                    $business_country =  $this->params['form']['country']; 
                    $business_city =  $this->params['form']['city']; 
                    $business_state =  $this->params['form']['state']; 
                    $business_zipcode =  $this->params['form']['zip']; */
                 /*   $creditcard =  $this->params['form']['card_no']; 
                    $expiry_month = $this->params['form']['expiry_month'];
                    $expiry_year = $this->params['form']['expiry_year'];
                    $expiration =  $expiry_month.'-'.$expiry_year; 
                    $cvv =  $this->params['form']['cvv_number']; 
                    $total =  $amount; 
                    $invoice    = substr(time(), 0, 6); 
                    $tax   = 0.00; 
                    
                    $api_login_id = '5q5kK375PG2';
                    $transaction_key = '6298HY7cxU6M3z2K';
                    //echo $total;exit;
                    //echo $email;exit;
                    //$payment = new AuthnetAIM('myapilogin', 'mYtRaNsaCTiOnKEy', true);
                    //$payment = new AuthnetAIM('5q5kK375PG2', '6298HY7cxU6M3z2K', true);
                    $payment = new AuthnetAIM($api_login_id, $transaction_key, true);
                    $payment->setTransaction($creditcard, $expiration, $total, $cvv, $invoice, $tax);
                    $payment->setParameter("x_duplicate_window", 180);
                    //$payment->setParameter("x_cust_id", $user_id);
                    $payment->setParameter("x_customer_ip", $_SERVER['REMOTE_ADDR']);
                    $payment->setParameter("x_email", $email);
                    $payment->setParameter("x_email_customer", FALSE);
                    $payment->setParameter("x_first_name", $first_name);
                    $payment->setParameter("x_last_name", $last_name); 
                  /*  $payment->setParameter("x_address", $business_address);
                    $payment->setParameter("x_city", $business_city);
                    $payment->setParameter("x_state", $business_state);
                    $payment->setParameter("x_zip", $business_zipcode);*/
                   /* $payment->setParameter("x_ship_to_first_name", $first_name);
                    $payment->setParameter("x_ship_to_last_name", $last_name);*/
                   // $payment->setParameter("x_description", $product);
                    //$payment->setParameter("x_phone", $business_telephone);
                    /*$payment->setParameter("x_ship_to_first_name", $shipping_firstname);
                    $payment->setParameter("x_ship_to_last_name", $shipping_lastname);
                    $payment->setParameter("x_ship_to_address", $shipping_address);
                    $payment->setParameter("x_ship_to_city", $shipping_city);
                    $payment->setParameter("x_ship_to_state", $shipping_state);
                    $payment->setParameter("x_ship_to_zip", $shipping_zipcode);  */
                    
                  /*  $payment->process();
                 
                    if ($payment->isApproved())
                    {
                        // Get info from Authnet to store in the database
                        $approval_code  = $payment->getAuthCode();
                        $avs_result     = $payment->getAVSResponse();
                        $cvv_result     = $payment->getCVVResponse();
                        $transaction_id = $payment->getTransactionID();
                        //echo $approval_code.'<br>'.$avs_result.'<br>'.$cvv_result.'<br>'.$transaction_id;exit;
                        
                        if($this->Session->check('userData')){
                           $cust_id = $this->Session->read('USER_USERID'); 
                        }else{
                            $conditions = "Customer.email = '".$email."'"; 
                            $ArEmailCheck = $this->Customer->find('first',array('conditions' => $conditions));
                            
                            if($ArEmailCheck){
                                  $cust_id = $ArEmailCheck['Customer']['id'];
                            }else{
                                $this->data['Customer']['fname'] = $first_name;
                                $this->data['Customer']['lname'] = $last_name;
                                $this->data['Customer']['email'] = $email;
                                $this->data['Customer']['password'] = md5($password);
                                $this->data['Customer']['txt_password'] = $password;
                                $this->data['Customer']['address'] = $plan_id;
                                $this->data['Customer']['country'] = $business_country;
                                $this->data['Customer']['state'] = $business_state;
                                $this->data['Customer']['city'] = $business_city;
                                $this->data['Customer']['zip'] = $business_zipcode;
                                $this->data['Customer']['address'] = $business_address;
                                $this->Customer->create();
                                if($this->Customer->save($this->data['Customer']))
                                {
                                 $cust_id = $this->Customer->getLastInsertID();             
                                }
                            }
                        }
                        
                       
                        
                        $this->data['Group']['plan_id'] = $plan_id;
                        $this->data['Group']['amount'] = $amount;
                        $this->data['Group']['group_title'] = $this->params['form']['group_name'];
                        $this->data['Group']['group_desc'] = $this->params['form']['g_desc'];
                        $this->data['Group']['group_type'] = 'B'; 
                        $this->data['Group']['group_owners'] = $user_id;
                        $this->data['Group']['created_by'] = $user_id;
                        $this->data['Group']['category_id'] = $this->params['form']['category_id'];
                        $this->data['Group']['icon'] = $upload_image;
                        $this->data['Group']['card_number'] = $creditcard;
                        $this->data['Group']['expiry_month'] = $expiry_month;
                        $this->data['Group']['expiry_year'] = $expiry_year;
                        $this->data['Group']['cvv_number'] = $cvv;
                        $this->data['Group']['approval_code'] = $approval_code;
                        $this->data['Group']['avs_result'] = $avs_result;
                        $this->data['Group']['cvv_result'] = $cvv_result;
                        $this->data['Group']['transaction_id'] = $transaction_id;
                        $this->data['Group']['state_id'] = $user_details['User']['state_id'];
                        $this->data['Group']['city_id'] = $user_details['User']['city_id'];
                        $this->Group->create();
                        if($this->Group->save($this->data['Group']))
                       {
                        $last_insert_id = $this->Group->getLastInsertId();
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
                       if($this->GroupUser->save($this->data['GroupUser']))
                          {
                            $this->Session->setFlash(__d("statictext", "GroupUser added sucessfully", true));
                             $_SESSION['meesage_type'] = '1';
                            //$this->redirect(array("controller" => "group", "action" => "group_list")); 
                            $this->redirect("/group/group_list/".$this->params['form']['category_id']);
                          }
                       } */
                     
                            /*$updateCust = array();
                            $updateCust['CustomerProject']['id'] = $cust_project_id;
                            $updateCust['CustomerProject']['cust_id'] = $cust_id;
                            $this->CustomerProject->save($updateCust);*/
                            
                            
                            /************************* Notification Part ***************************/
                          /*  $this->data['Notification']['type'] = 'client pays for plan';   
                            $this->data['Notification']['from_id'] = $cust_id;
                            $this->data['Notification']['project_id'] = $cust_project_id;
                            $this->data['Notification']['to_id'] = '0';
                            $this->data['Notification']['cust_id'] = $cust_id;
                            $this->data['Notification']['notification'] = 'Client '.$first_name.' '.$last_name.' has paid for project '.$product;   
                            $this->data['Notification']['is_viewed_c'] = '1';
                            $this->data['Notification']['created'] = date('Y-m-d H:i:s.'.$micro_time);
                            $this->Notification->create();
                            $this->Notification->save($this->data['Notification']);
                            /************************* Notification Part ***************************/
                            
                             /*  $condition = "EmailTemplate.id = '17'";
                               $mailDataRS = $this->EmailTemplate->find("first",array('conditions'=>$condition));
                               
                               
                               $user_subject =$mailDataRS['EmailTemplate']['subject'];
                               $user_subject=str_replace('[SITE NAME]','LandscapePlan.com',$user_subject);
                               $user_body=$mailDataRS['EmailTemplate']['content'];
                               
                               $name = $first_name.' '.$last_name;
                               $user_body=str_replace('[NAME]',$name,$user_body);
                               $user_body=str_replace('[EMAIL]',$email,$user_body);
                               $user_body=str_replace('[PASSWORD]',$password,$user_body);
                               $user_message = stripslashes($user_body);
                               //print_r($user_message);
                               //exit();
                               
                               $user_header   = "From: ".$admin_email." \r\n";
                               $user_header  .= "MIME-Version: 1.0\r\n"; 
                               $user_header  .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 

                               @mail($to, $user_subject, $user_message, $user_header); 
                               //exit();
                           
                            $this->Session->setFlash(__d("statictext", "Your payment is successfull. Please check your email!", true));
                            $_SESSION['message_type'] = '1';
                            $this->redirect(array('controller' => 'home', 'action' => '/'));    
                        }*/
                        
                 
                        // Do stuff with this. Most likely store it in a database.
                        // Direct the user to a receipt or something similiar.
              /*      }
                    else if ($payment->isDeclined())
                    {
                        // Get reason for the decline from the bank. This always says,
                        // "This credit card has been declined". Not very useful.
                        $reason = $payment->getResponseText();
                        echo $reason;
                        exit;
                        // Politely tell the customer their card was declined
                        // and to try a different form of payment.
                    }
                    else if ($payment->isError())
                    {
                        // Get the error number so we can reference the Authnet
                        // documentation and get an error description.
                        $error_number  = $payment->getResponseSubcode();
                        $error_message = $payment->getResponseText();
                 */
                        // OR
                 
                        // Capture a detailed error message. No need to refer to the manual
                        // with this one as it tells you everything the manual does.
                       /* $full_error_message =  $payment->getResponseMessage();
                        
                        echo $full_error_message;
                        exit;
                 
                        // We can tell what kind of error it is and handle it appropriately.
                        if ($payment->isConfigError())
                        {
                            // We misconfigured something on our end.
                        }
                        else if ($payment->isTempError())
                        {
                            // Some kind of temporary error on Authorize.Net's end. 
                            // It should work properly "soon".
                        }
                        else
                        {
                            // All other errors.
                        }*/
                 
                        // Report the error to someone who can investigate it
                        // and hopefully fix it
                 
                        // Notify the user of the error and request they contact
                   /*     // us for further assistance
                    }
                }
                catch (AuthnetAIMException $e)
                {
                    echo 'There was an error processing the transaction. Here is the error message: ';
                    echo $e->__toString();
                }
        
        
      }
      
   }
  */


  function add_business_category_group() {
        
       $this->layout = "";
        $this->set('pagetitle', 'Create Group');
        $sitesettings = $this->getSiteSettings(); 
         $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');


        $condition = "User.status= '1'  AND User.id= '".$user_id."'";
        $user_details = $this->User->find("first",array('conditions'=>$condition));
       // pr($featured_users);exit();
        $this->set('user_details',$user_details);


        
        $upload_image = '';
        //$is_upload = 1; 
     
      if(!empty($this->params['form']) && isset($this->params['form'])){
        if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= '')
        
        {
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'group_images/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=640 && $uploaded_height >= 480)
                {
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'group_images/thumb/'.$upload_image;
                            $upload_target_medium = 'group_images/medium/'.$upload_image;
                            $upload_target_web = 'group_images/web/'.$upload_image;
                           

                            $max_web_width =  262;
                            $max_web_height = 178;
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_web, $max_web_width, $max_web_height, 100, true);
                         $is_upload = 1;
                                                                                    
                      }         
                    else  
                    {
                        
                    $is_upload = 0;
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    
                    $this->redirect("/group/group_list/".$this->params['form']['category_id']);
                
                    }
                
                }
                else
                {        
                 
                 $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
               $this->redirect("/group/group_list/".$this->params['form']['category_id']);
                
                }
                
             }
             else
             {
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect("/group/group_list/".$this->params['form']['category_id']);
              
             }
        
        }
       
        
       
                        $this->data['Group']['plan_id'] = '0';
                        $this->data['Group']['amount'] = '0';
                        $this->data['Group']['group_title'] = $this->params['form']['group_name'];
                        $this->data['Group']['group_desc'] = $this->params['form']['g_desc'];
                        $this->data['Group']['group_purpose'] = $this->params['form']['g_purpose'];
                        $this->data['Group']['group_type'] = 'B';    
                        $this->data['Group']['group_owners'] = $user_id;
                        $this->data['Group']['created_by'] = $user_id;
                        $this->data['Group']['category_id'] = $this->params['form']['category_id'];
                        $this->data['Group']['icon'] = $upload_image;
                        $this->data['Group']['card_number'] = '0';
                        $this->data['Group']['expiry_month'] = '0';
                        $this->data['Group']['expiry_year'] = '0';
                        $this->data['Group']['cvv_number'] = '0';
                        $this->data['Group']['approval_code'] = '0';
                        $this->data['Group']['avs_result'] = '0';
                        $this->data['Group']['cvv_result'] = '0';
                        $this->data['Group']['transaction_id'] = '0';
                        $this->data['Group']['state_id'] = $selected_state_id;
                        $this->data['Group']['city_id'] = $selected_city_id;
                        $this->Group->create();
                        if($this->Group->save($this->data['Group']))
                       {
                        $last_insert_id = $this->Group->getLastInsertId();
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
                       if($this->GroupUser->save($this->data['GroupUser']))
                          {
                             $this->Session->setFlash(__d("statictext", "Group added sucessfully", true));
                             $_SESSION['meesage_type'] = '1';
                             $this->redirect("/group/group_list/".$this->params['form']['category_id']);
                          }
                       } 
                 
      }
      
   }


   function add_free_group() {
        
       $this->layout = "";
        $this->set('pagetitle', 'Create Group');
        $sitesettings = $this->getSiteSettings(); 
         $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $condition = "User.status= '1'  AND User.id= '".$user_id."'";
        $user_details = $this->User->find("first",array('conditions'=>$condition));
       // pr($featured_users);exit();
        $this->set('user_details',$user_details);


        
        $upload_image = '';
        //$is_upload = 1; 
     
      if(!empty($this->params['form']) && isset($this->params['form'])){
        if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= '')
        
        {
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'group_images/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=640 && $uploaded_height >= 480)
                {
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'group_images/thumb/'.$upload_image;
                            $upload_target_medium = 'group_images/medium/'.$upload_image;
                            $upload_target_web = 'group_images/web/'.$upload_image;
                           

                            $max_web_width =  262;
                            $max_web_height = 178;
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_web, $max_web_width, $max_web_height, 100, true);
                         $is_upload = 1;
                                                                                    
                      }         
                    else  
                    {
                        
                    $is_upload = 0;
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    
                    $this->redirect("/category/category_list/");
                
                    }
                
                }
                else
                {        
                 
                 $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect("/category/category_list/");
                
                }
                
             }
             else
             {
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect("/category/category_list/");
              
             }
        
        }
       
        
       
                        $this->data['Group']['plan_id'] = '0';
                        $this->data['Group']['amount'] = '0';
                        $this->data['Group']['group_title'] = $this->params['form']['group_name'];
                        $this->data['Group']['group_desc'] = $this->params['form']['g_desc'];
                        $this->data['Group']['group_purpose'] = $this->params['form']['g_purpose'];
                        $this->data['Group']['group_type'] = 'F';    
                        $this->data['Group']['group_owners'] = $user_id;
                        $this->data['Group']['created_by'] = $user_id;
                        $this->data['Group']['category_id'] = '0';
                        $this->data['Group']['icon'] = $upload_image;
                        $this->data['Group']['card_number'] = '0';
                        $this->data['Group']['expiry_month'] = '0';
                        $this->data['Group']['expiry_year'] = '0';
                        $this->data['Group']['cvv_number'] = '0';
                        $this->data['Group']['approval_code'] = '0';
                        $this->data['Group']['avs_result'] = '0';
                        $this->data['Group']['cvv_result'] = '0';
                        $this->data['Group']['transaction_id'] = '0';
                        $this->data['Group']['state_id'] = $selected_state_id;
                        $this->data['Group']['city_id'] = $selected_city_id;
                        $this->Group->create();
                        if($this->Group->save($this->data['Group']))
                       {
                        $last_insert_id = $this->Group->getLastInsertId();
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
                       if($this->GroupUser->save($this->data['GroupUser']))
                          {
                             $this->Session->setFlash(__d("statictext", "Group added sucessfully", true));
                             $_SESSION['meesage_type'] = '1';
                             $this->redirect("/category/category_list/"); 
                          }
                       } 
         
        
      }
      
   }

  function group_detail($group_id) {
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        $this->_checkSessionUser();

         $this->set('group_id',$group_id);
         $date = date('Y-m-d');
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);


         /*     $selected_state_id = '132';
                $selected_city_id = '10541';
        */
        $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
            $ArState = $this->State->find('all', array('conditions' => $conditions));
            $this->set('ArState', $ArState);


         $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
            $StateName = $this->State->find('first', array('conditions' => $conditions));
            $this->set('StateName', $StateName);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
            $CityName = $this->City->find('first', array('conditions' => $conditions));
            $this->set('CityName', $CityName);

       
        
        $condition = "Group.status= '1'  AND Group.id= '".$group_id."'";
        $group_details = $this->Group->find("first",array('conditions'=>$condition));
        //pr($group_details);
        
      $this->set('group_details',$group_details);

      $group_member_type = 'nonmember';
      $condition_group_owner_count = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_details['Group']['id']."'  AND GroupUser.user_id = '".$user_id."' AND GroupUser.user_type= 'O'";
      $group_owner_count = $this->GroupUser->find("count",array('conditions'=>$condition_group_owner_count));
      if($group_owner_count > 0)
      {
        $group_member_type = 'owner';
      }
      $condition_group_member_count = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_details['Group']['id']."'  AND GroupUser.user_id = '".$user_id."' AND GroupUser.user_type= 'M'";
      $group_member_count = $this->GroupUser->find("count",array('conditions'=>$condition_group_member_count));
      if($group_member_count > 0)
      {
        $group_member_type = 'member';
      }
      //pr($group_member_type);exit();
      $this->set('group_member_type',$group_member_type);

        $condn = "GroupImage.group_id = '".$group_id."'";
        $photo_list = $this->GroupImage->find('all',array('conditions'=>$condn));
        $this->set('photo_list',$photo_list);

        $condn = "GroupDoc.group_id = '".$group_id."'";
        $doc_list = $this->GroupDoc->find('all',array('conditions'=>$condn));
        //pr($doc_list);exit();
        $this->set('doc_list',$doc_list);

         $condn = "Video.group_id = '".$group_id."'";
        $video_list = $this->Video->find('all',array('conditions'=>$condn));
       // pr($video_list);exit();
        $this->set('video_list',$video_list);


        //event list
        $condition_event_list = "Event.group_id = '".$group_id."' AND Event.status = '1' AND  ((Event.event_date = '".$date."') OR (Event.event_start_date <= '".$date."' AND Event.event_end_date >= '".$date."'))";
                $event_details = $this->Event->find('all',array('conditions'=>$condition_event_list,'order'=>'Event.id DESC'));  
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
                    if($events['Event']['is_multiple_date'] == '1')
                    {
                        $list['event_start_date_time'] =  date("jS F Y ga",$events['Event']['event_start_timestamp']);
                        $list['event_end_date_time'] = date("jS F Y ga",$events['Event']['event_end_timestamp']);
                    }
                    else
                    {
                       $list['event_date_time'] = date("jS F Y ga",$events['Event']['event_timestamp']);        

                    }

                   
                    $list['location'] = $events['Event']['location'];
                    $list['latitude'] =  $events['Event']['latitude'];
                    $list['longitude'] =  $events['Event']['longitude'];
                    
                    
                    array_push($event_list,$list);
                }
              // pr($event_list);exit();
                 $this->set('event_list',$event_list);
                  
       

    }

    function popup_video($video_id)
	{

	  // $this->_checkSessionUser();

		  $this->layout = "";
		  $user_id = $this->Session->read('userData.User.id');
		  $this->set('pageTitle', 'Group Type');
		   
		  $conditions = "Video.id =  '".$video_id."'";

			$d_video = $this->Video->find('first', array('conditions' => $conditions));
			 
		   $extension = substr(strtolower(strrchr($d_video['Video']['video'], '.')), 1);
		   //pr($UserDetailType);exit();
		   $this->set('d_video',$d_video);
		   $this->set('extension',$extension);
	 
		  
	}

  function join_request()
  {
	
	 $this->layout = "";
		  $user_id = $this->Session->read('userData.User.id');
		  $this->set('pageTitle', 'Group Type');
	   
			$condition = "Group.id = '".$this->params['form']['group_of_id']."'";
			$group_detail = $this->Group->find('first',array('conditions'=>$condition));  

			$condition_check_request_sent_already = "Notification.group_id = '".$this->params['form']['group_of_id']."' AND Notification.sender_id = '".$user_id."' AND Notification.sender_type = 'NGM' AND Notification.receiver_id IN (". $group_detail['Group']['group_owners'].") AND Notification.receiver_type = 'GO' AND Notification.group_type = '".$group_detail['Group']['group_type']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0'";	//Request to join already sent or not starts 
			$is_request = $this->Notification->find('count',array('conditions'=>$condition_check_request_sent_already));  
			if($is_request > 0)
			{

			   $this->Session->setFlash(__d("statictext", "You have already sent a request to this group", true));
			   $_SESSION['meesage_type'] = '0';
			   $this->redirect("/group/group_list/".$group_detail['Group']['category_id']);
			}
			else 
			{
			 /*$condition_check_group_member_owner = "GroupUser.group_id = '".$this->params['form']['group_of_id']."' AND GroupUser.user_id = '".$user_id."' AND (GroupUser.user_type = 'O' OR GroupUser.user_type = 'M')"; 	//Check whether the user is Group Owner/ Member
			
			$is_owner_or_member = $this->GroupUser->find('count',array('conditions'=>$condition_check_group_member_owner));  

			if($is_owner_or_member > 0)
			{

				 $this->Session->setFlash(__d("statictext", "You are already a member of this group", true));
				 $_SESSION['meesage_type'] = '0';
				 $this->redirect("/group/group_list/".$group_detail['Group']['category_id']);
			}
			else
			{*/
			 
				/*$this->data['Notification']['type'] = 'G';
				$this->data['Notification']['sender_id'] = $user_id;
				$this->data['Notification']['sender_type'] = 'NGM';
				$this->data['Notification']['request_mode'] = $this->params['form']['group_type'];
				$this->data['Notification']['receiver_id'] = $group_detail['Group']['created_by'];
				$this->data['Notification']['receiver_type'] =  'GO';
				$this->data['Notification']['group_type'] =  $group_detail['Group']['group_type'];
				$this->data['Notification']['group_id'] = $this->params['form']['group_of_id'];
					   
				$this->Notification->create();
				if($this->Notification->save($this->data))
				{
					 $this->Session->setFlash(__d("statictext", "Request sent successfully", true));
					 $_SESSION['meesage_type'] = '1';
					 $this->redirect("/group/group_list/".$group_detail['Group']['category_id']);
				}
				else
				{
				 
				  $this->Session->setFlash(__d("statictext", "Join now request unccessful", true));
				  $_SESSION['meesage_type'] = '0';
				  $this->redirect("/group/group_list/".$group_detail['Group']['category_id']);
				}*/

				  $cur_date_time= date('Y-m-d H:i:s');
				  if(!empty($group_detail)){
				  
					  $arr_group_owners= explode(',', $group_detail['Group']['group_owners']);
					  
					  for($i=0; $i<count($arr_group_owners); $i++){
						  $this->data['Notification']['type'] = 'G';
						  $this->data['Notification']['sender_id'] = $user_id;
						  $this->data['Notification']['sender_type'] = 'NGM';
						  $this->data['Notification']['request_mode'] = $this->params['form']['group_type'];
						  $this->data['Notification']['receiver_id'] = $arr_group_owners[$i];
						  $this->data['Notification']['receiver_type'] =  'GO';
						  $this->data['Notification']['group_type'] =  $group_detail['Group']['group_type'];
						  $this->data['Notification']['group_id'] = $this->params['form']['group_of_id'];
						  $this->data['Notification']['is_receiver_accepted'] = '0';
						  $this->data['Notification']['is_reversed_notification'] =  '0';
						  $this->data['Notification']['is_read'] =  '0';	
						  $this->data['Notification']['status'] =  '1';	
						  $this->data['Notification']['created'] =  $cur_date_time;	
					  
						  $this->Notification->create();
						  $this->Notification->save($this->data);
					  }
					  
					  $this->Session->setFlash(__d("statictext", "Request sent successfully", true));
					  $_SESSION['meesage_type'] = '1';
					  $this->redirect("/group/group_list/".$group_detail['Group']['category_id']);
				  
				  }	
			//}
		}

   
  }



    function add_group_video(){
        $this->layout = "";
        $this->set('pagetitle', 'Create Group');
        $sitesettings = $this->getSiteSettings(); 
         $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

      
         $is_upload_video = 0; 
        if(!empty($this->params['form']['video_file']))
        {
           
                
                $video_name = $this->params['form']['video_file']['name'];
                $extension = end(explode('.',$video_name));               
                $upload_video = time().accessCode(5).'.'.$extension; 
                $file_type = array('mp4','3gp','avi','mov','wmv','flv','ogg','ogv');
                $ext = substr(strtolower(strrchr($this->params['form']['video_file']['name'], '.')), 1);
                if(in_array($ext, $file_type))  
               {
                $upload_target_original = 'group_videos/'.$upload_video;


              if(move_uploaded_file($this->params['form']['video_file']['tmp_name'],$upload_target_original))
              {
                    // $response['is_error'] = 0;
                     $is_upload_video = 1; 
              }
                else
                {
                   $this->Session->setFlash(__d("statictext", "Video uploaded failed", true));
                   $_SESSION['meesage_type'] = '0';
                   $this->redirect("/group/group_detail/".$this->params['form']['grp_id']);
                }
              }
            else
                {
                   $this->Session->setFlash(__d("statictext", "Please upload video file only", true));
                   $_SESSION['meesage_type'] = '0';
                   $this->redirect("/group/group_detail/".$this->params['form']['grp_id']);
                }
             
        }
            $is_upload_image = 0;  
            if(!empty($this->params['form']['image_file'])){
                
                
              //  $image_name = $_FILES["upload_image"]['name'];
               
             
                $image_name = $this->params['form']['image_file']['name'];
                $extension = end(explode('.',$image_name));               
                $upload_image = time().accessCode(5).'.'.$extension;          
                $upload_image_target_original = 'group_videos/images/'.$upload_image;
                             

                if(move_uploaded_file($this->params['form']['image_file']['tmp_name'], $upload_image_target_original)) {
                                                                                                                                                                                                                            
                           
                          // $response['is_error'] = 0; 
                           $is_upload_image = 1;                                                         
                      }           
                    else  
                    {
                     $this->Session->setFlash(__d("statictext", "Image uploaded failed", true));
                     $_SESSION['meesage_type'] = '0';
                     $this->redirect("/group/group_detail/".$this->params['form']['grp_id']);
                
                    }
                
                               
               }
            
            
                if(($is_upload_video == 1) && ($is_upload_image == 1))
            {
               
                $this->data['Video']['group_id'] =  $this->params['form']['grp_id'];
                $this->data['Video']['v_image'] = $upload_image;
                $this->data['Video']['video'] = $upload_video;
                $this->data['Video']['v_name'] = '';
                
                
                $this->Video->create();
                
                
                 if($this->Video->save($this->data['Video']))
                 {
                   $this->Session->setFlash(__d("statictext", "Video uploaded sucessfully", true));
                   $_SESSION['meesage_type'] = '1';
                   $this->redirect("/group/group_detail/".$this->params['form']['grp_id']);
                 }
            
           }

            
        }

  function upload_gallery_image1($group_id) {



    $this->layout = "";
    $this->set('pageTitle', 'Login');
     $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);

                
    if(!empty($_FILES)){
    
    //database configuration
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = 'mysqlpass';
    $dbName = 'grouppers';
    //connect with the database
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    if($mysqli->connect_errno){
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    //print_r($_FILES);exit;
    $file_type = array('jpg','jpeg','png');
    $targetDir = "gallery/";
    $fileName = $_FILES['file']['name'];
    $ext = substr(strtolower(strrchr($_FILES['file']['name'], '.')), 1);
    if(in_array($ext, $file_type))
        {
    $targetFile = $targetDir.$fileName;
    $imagelist = getimagesize($_FILES['file']['tmp_name']);
    list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;


    if(move_uploaded_file($_FILES['file']['tmp_name'],$targetFile)){
        //insert file information into db table
          $upload_target_thumb = 'gallery/thumb/'.$fileName;
          $upload_target_web = 'gallery/web/'.$fileName;
          $upload_target_medium = 'gallery/medium/'.$fileName;
              
                        
          $max_web_width =  180;
          $max_web_height = 122; 

          $this->imgAndroidThumbOptCpy($targetDir, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
          $this->imgAndroidMediumOptCpy($targetDir, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);  
          $this->imgOptCpy($targetDir, $upload_target_web, $max_web_width, $max_web_height, 100, true);
        $conn->query("INSERT INTO group_images (image, group_id) VALUES('".$fileName."','".$group_id."')");
       }

    
    
      }
   }
}



function upload_gallery_image($group_id) {



    $this->layout = "";
    $this->set('pageTitle', 'Login');
     $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);

                
    if(!empty($_FILES)){
    
            $image_name = $_FILES['file']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'gallery/'.$upload_image;
        
            $imagelist = getimagesize($_FILES['file']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
                    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                              $upload_target_thumb = 'gallery/thumb/'.$upload_image;
                              $upload_target_web = 'gallery/web/'.$upload_image;
                              $upload_target_medium = 'gallery/medium/'.$upload_image;
                                  
                                            
                              $max_web_width =  180;
                              $max_web_height = 122; 

                              $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                              $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_medium,$uploaded_width, $uploaded_height, 100, true);  
                              $this->imgOptCpy($upload_target_original, $upload_target_web, $max_web_width, $max_web_height, 100, true);
                               

                        $this->data['GroupImage']['group_id'] = $group_id;
                        $this->data['GroupImage']['image'] = $upload_image;

                        
                        $this->GroupImage->create();
                      if($this->GroupImage->save($this->data))
                      {
                        $this->Session->setFlash(__d("statictext", "Image upload sucessfully", true));
                        $_SESSION['meesage_type'] = '1';
                        $this->redirect(array('controller'=>'group','action'=>'group_detail'));
                      }
                      else  
                    {
                        
                    
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    $this->redirect(array('controller'=>'group','action'=>'group_detail'));
                
                    }

                      }      
                    else  
                    {
                        
                    
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                   $this->redirect(array('controller'=>'group','action'=>'group_detail'));
                
                    }
         
                }
        }



function upload_gallery_doc($group_id) {



    $this->layout = "";
    $this->set('pageTitle', 'Login');
     $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);

        if(!empty($_FILES)){
    
            $doc_name = $_FILES['file']['name'];
            
            //$extension = end(explode('.',$doc_name));               
           // $upload_doc = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'gallery/doc/'.$doc_name;
        
                         
                    if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                              
                        $this->data['GroupDoc']['group_id'] = $group_id;
                        $this->data['GroupDoc']['docname'] = $doc_name;

                        
                        $this->GroupDoc->create();
                      if($this->GroupDoc->save($this->data))
                      {
                        $this->Session->setFlash(__d("statictext", "Document upload sucessfully", true));
                        $_SESSION['meesage_type'] = '1';
                        $this->redirect(array('controller'=>'group','action'=>'group_detail'));
                      }
                      else  
                    {
                        
                    
                    $this->Session->setFlash(__d("statictext", "Document upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    $this->redirect(array('controller'=>'group','action'=>'group_detail'));
                
                    }

                      }      
                    else  
                    {
                        
                    
                    $this->Session->setFlash(__d("statictext", "Document upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                   $this->redirect(array('controller'=>'group','action'=>'group_detail'));
                
                    }
         
                }

   }

   


 function sender_detail($sender_id=NULL)
 {

  // $this->_checkSessionUser();

	  $this->layout = "";
	  $this->_checkSessionUser();
	  $user_id = $this->Session->read('userData.User.id');
	  $this->set('pageTitle', 'Group Member Count');
	  
		$condition4 = "User.status= '1' AND  User.id = '".$sender_id."'";
		$sender_details = $this->User->find('first',array('conditions'=>$condition4));
			
	
	   return $sender_details;
	   //pr($UserDetailType);exit();

 
	  
  }


        function group_details($group_id=NULL)
        {

          // $this->_checkSessionUser();

              $this->layout = "";
              $this->_checkSessionUser();
              $user_id = $this->Session->read('userData.User.id');
              $this->set('pageTitle', 'Group Member Count');
              
                $condition4 = "Group.id = '".$group_id."'";
                $group_details = $this->Group->find('first',array('conditions'=>$condition4));
                    
            
               return $group_details;
               //pr($UserDetailType);exit();
  
        }


        function accept_group_request()
    	{
       
            $this->layout = ""; 
            $notification_id = $_REQUEST['notification_id'];
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
               
            $condition = "Notification.id = '".$notification_id."' AND Notification.type = 'G' AND Notification.status = '1'";
            //$noti_detail = $this->Notification->find('first',array('conditions'=>$condition1));  
            $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition));  

                
            $this->data['Notification']['is_read'] = '1';
            $this->data['Notification']['is_receiver_accepted'] = '2';
            $this->Notification->id = $notification_id;                 
            if($this->Notification->save($this->data))			// Update the Notification
            {
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
                      //pr($last_insert_id);exit();
                      $condition_lastNotification = "Notification.id = '".$last_insert_id."'";
                      $noti_detail = $this->Notification->find('first',array('conditions'=>$condition_lastNotification)); //Details of reversed notification
					  
					  if($noti_detail['Notification']['sender_type']=='GO' && $noti_detail['Notification']['receiver_type']=='NGM'){
					  
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
									
									echo '1';
							  		exit;   
								}
								else
								{
							   		$this->data['User']['id'] = $user_details['User']['id'];
							  		$this->data['User']['groups'] = $noti_detail['Notification']['group_id'];
							   		$this->User->save($this->data['User']);
									
							   		echo '1';
									exit;
								}
							}
							
						  //////////////////////////   Insert to Groups field in User table ends      //////////////////////   
					  }
					  else if($noti_detail['Notification']['sender_type']=='NGM' && $noti_detail['Notification']['receiver_type']=='GO'){
					  
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
									
									echo '1';
							  		exit;   
								}
								else
								{
							   		$this->data['User']['id'] = $user_details['User']['id'];
							  		$this->data['User']['groups'] = $noti_detail['Notification']['group_id'];
							   		$this->User->save($this->data['User']);
									
							   		echo '1';
									exit;
								}
							}
							
						  //////////////////////////   Insert to Groups field in User table ends      //////////////////////  
					  }
					  else if($noti_detail['Notification']['sender_type']=='NGM' && $noti_detail['Notification']['receiver_type']=='GM'){
					  		
						      $cond_groupAdmins = "Group.id = '".$noti_detail['Notification']['group_id']."'";
							  $group_detail = $this->Group->find('first',array('conditions'=>$cond_groupAdmins, 'fields'=>'Group.group_owners'));
							  
							  if(!empty($group_detail)){
							  
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
								  }
							  
							  }		
							  
							  echo '1';
							  exit;	
					  }
					  else{
						  echo '0';
						   exit;
					  }
                  }
                }
				else
				{
				  echo '0';
				   exit;
				}   
    }


    function reject_group_request(){
       
            $this->layout = ""; 
            $notification_id = $_REQUEST['notification_id'];
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            


            
            /*$notification_id    = '6';
            $type    = 'G';*/
            
            
            

                $condition1 = "Notification.id = '".$notification_id."' AND Notification.type = 'G' AND Notification.status = '1'";
                $old_noti_detail = $this->Notification->find('first',array('conditions'=>$condition1));  


                $this->data['Notification']['is_read'] = '1';
                $this->data['Notification']['is_receiver_accepted'] = '1';
                
                $this->Notification->id = $notification_id;                 
                if($this->Notification->save($this->data))
                {
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
                 if($this->Notification->save($this->data))
                    {
                      echo '1';
                      exit;
                  }
				  else
				 {
				  echo '0';
				  exit;
				 }
			}
    }


    function remove_notification()
      {
       
            $this->layout = ""; 
            $notification_id = $_REQUEST['notification_id'];
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            
            
            
           
                $this->data['Notification']['status'] = '0';
                $this->data['Notification']['is_read'] = '1';
               
                 $this->Notification->id = $notification_id;                 
                if($this->Notification->save($this->data))
                {
                  echo '1';
                  exit;
                 
                }

                else
                {
                  echo '0';
                  exit;
                }

      }



      function group_member_list($group_id){
            //Configure::write('debug',3);
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');
            $session_user_id = $this->Session->read('userData.User.id');
            $notification_id = 0;
            
            // $group_id = $_REQUEST['group_id'];
            
           // $user_id    = '2';
            //$group_id    = '23';
           
           
            

            $condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";

            $group_details = $this->Group->find('first',array('conditions'=>$condition2));
                      

            

            if ($group_details['Group']['group_type'] == 'B') {			//B for Business group

              $group_members = array(); 

              $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";		//Condition to check Group Owner
              $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));

              if($count_chk_owner > 0)
              {


            	$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."'";	// Fetches the all members (public+private) of the Group
            	$all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));

            
                foreach($all_group_members as $groups)
                {
                    $list['id'] = $groups['GroupUser']['id'];
                    $list['user_id'] = $groups['GroupUser']['user_id'];
                    $list['user_type'] = $groups['GroupUser']['user_type'];
                    $list['member_mode'] = $groups['GroupUser']['member_mode'];
					
                    $condition4 = "Group.status= '1' AND  Group.id = '".$groups['GroupUser']['group_id']."'";
                    $group_detail = $this->Group->find('first',array('conditions'=>$condition4));
                    $list['group_id'] = $groups['GroupUser']['group_id'];
                    $list['group_title'] = $group_detail['Group']['group_title'];

                    $condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
                   
                    $check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
                   
                    $is_friend = '0';

                    if($check_friend > 0 )
                    {
                      $is_friend = '1'; 	// The Group member is my friend.
                    }                   
                    else {			//If Group Member is not my friend
					
                       $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";		//Check whether logged in user has sent the request already or not
                       $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));

                          if(($count_request_sent > 0))
                          {
                            $is_friend = '2'; //Request sent by logged in user ,not accepted or rejected by other user
                          }
                          else
                          {

                           $condition9 = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.receiver_id = '".$user_id."' AND Notification.sender_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";
                          $detail_notification = $this->Notification->find('count',array('conditions'=>$condition9));
                          $notification_detail = $this->Notification->find('first',array('conditions'=>$condition9));
                             if(($detail_notification > 0))
                              {
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

                    	$list['image_url'] = $user_detail['User']['image'];
                       
                    }
                    else
                    {
          						$list['image_url'] = '';
          					}
					
					array_push($group_members,$list);
         // pr($group_members);exit();
                }
                	$is_auth = '1';
                	$this->set('is_auth',$is_auth);
                  //pr($group_members);exit();
                	$this->set('group_members',$group_members);
                  $this->set('session_user_id',$session_user_id); 
            }
            else 
            {
              $group_members = []; 
              $is_auth = '0';
              $this->set('is_auth',$is_auth);
              $this->set('group_members',$group_members);
              $this->set('session_user_id',$session_user_id); 
            }
     	}
		
			else if($group_details['Group']['group_type'] == 'F'){		//F for the Free Group

              $group_members = array(); 

              $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";		//Condition to check Group Owner
           	  $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$condition4 ));

              if($count_chk_owner > 0)
              {		//if Logged in user is the Group owner of the selected Free group

			
            	$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."'"; 	// Fetches the all members (public+private) of the Group
				$all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));

                foreach($all_group_members as $groups)
                {
                    $list['id'] = $groups['GroupUser']['id'];
                    $list['user_id'] = $groups['GroupUser']['user_id'];
                    $list['user_type'] = $groups['GroupUser']['user_type'];
                    $list['member_mode'] = $groups['GroupUser']['member_mode'];
                    $condition4 = "Group.status= '1' AND  Group.id = '".$groups['GroupUser']['group_id']."'";
                    $group_detail = $this->Group->find('first',array('conditions'=>$condition4));
                    $list['group_id'] = $groups['GroupUser']['group_id'];
                    $list['group_title'] = $group_detail['Group']['group_title'];

                    $condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
                    $check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
                   
                    $is_friend = '0';

                    if($check_friend > 0 )
                    {
                      $is_friend = '1'; 	// The Group member is my friend.
                    }                   
                    else {			//If Group Member is not my friend
					
                       $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";		//Check whether logged in user has sent the request already or not
                       $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));

                          if(($count_request_sent > 0))
                          {
                            $is_friend = '2'; //Request sent by logged in user ,not accepted or rejected by other user
                          }
                          else
                          {

                           $condition9 = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.receiver_id = '".$user_id."' AND Notification.sender_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";
                          $detail_notification = $this->Notification->find('count',array('conditions'=>$condition9));
                          $notification_detail = $this->Notification->find('first',array('conditions'=>$condition9));

                             if(($detail_notification > 0))
                              {
                                $is_friend = '3';  
                                 $notification_id =  $notification_detail['Notification']['id'];
                                  //Whether the logged in user have got the Notification with accept or reject button
                              }
                          }
                     }
                    
                    $list['is_friend'] = $is_friend;  
                    $list['notification_id'] = $notification_id;  
                    

                    $condition3 = "User.status= '1' AND  User.id = '".$groups['GroupUser']['user_id']."'";
                    $user_detail = $this->User->find('first',array('conditions'=>$condition3));
                    $list['user_name'] = ucfirst($user_detail['User']['fname'].' '.$user_detail['User']['lname']) ;
                     if(!empty($user_detail['User']['image']))
                       {

                    $list['image_url'] = $user_detail['User']['image'];
                       
                       }
                     else
                       {

                    $list['image_url'] = '';

                       }
                    
                    
                    array_push($group_members,$list);
                }
                
                 $is_auth = '1';
                $this->set('is_auth',$is_auth);
                $this->set('group_members',$group_members);
                $this->set('session_user_id',$session_user_id);


            }else{			// If the logged in user is the group member of Free Group 
			  
             		$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."' AND GroupUser.member_mode = 'public'";

            		$all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));
        
          			foreach($all_group_members as $groups)
                	{
                    $list['id'] = $groups['GroupUser']['id'];
                    $list['user_id'] = $groups['GroupUser']['user_id'];
                    $list['user_type'] = $groups['GroupUser']['user_type'];
                    $list['member_mode'] = $groups['GroupUser']['member_mode'];
                    $condition4 = "Group.status= '1' AND  Group.id = '".$groups['GroupUser']['group_id']."'";
                    $group_detail = $this->Group->find('first',array('conditions'=>$condition4));
                    $list['group_id'] = $groups['GroupUser']['group_id'];
                    $list['group_title'] = $group_detail['Group']['group_title'];

                    $condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
                    $check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
                   
                    $is_friend = '0';

                    if($check_friend > 0 )
                    {
                      $is_friend = '1'; 	// The Group member is my friend.
                    }                   
                    else{			//If Group Member is not my friend
					
                       $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";		//Check whether logged in user has sent the request already or not
                       $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));

                          if(($count_request_sent > 0))
                          {
                            $is_friend = '2'; //Request sent by logged in user ,not accepted or rejected by other user
                          }
                          else
                          {

                           $condition9 = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.receiver_id = '".$user_id."' AND Notification.sender_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";
                          $detail_notification = $this->Notification->find('count',array('conditions'=>$condition9));
                          $notification_detail = $this->Notification->find('first',array('conditions'=>$condition9));
                             if(($detail_notification > 0))
                              {
                                $is_friend = '3'; 
                                 $notification_id =  $notification_detail['Notification']['id'];
                                   //Whether the logged in user have got the Notification with accept or reject button
                              }
                          }
                     }
                    
                    $list['is_friend'] = $is_friend; 
                    $list['notification_id'] = $notification_id; 
     

                    $condition3 = "User.status= '1' AND  User.id = '".$groups['GroupUser']['user_id']."'";
                    $user_detail = $this->User->find('first',array('conditions'=>$condition3));
                    $list['user_name'] = ucfirst($user_detail['User']['fname'].' '.$user_detail['User']['lname']) ;
                    if(!empty($user_detail['User']['image']))
                    {

                    	$list['image_url'] = $user_detail['User']['image'];
                       
                    }
                    else
                    {

                    	$list['image_url'] = '';

                     }
                    
                    
                    array_push($group_members,$list);
                }
                 $is_auth = '1';
                $this->set('is_auth',$is_auth);
                $this->set('group_members',$group_members);
                $this->set('session_user_id',$session_user_id);
            
            }

            }  
      }


      function invite_list($group_id){
		//Configure::write('debug',3);
		$this->layout = ""; 
		
		$this->_checkSessionUser();
		$user_id = $this->Session->read('userData.User.id');
		$session_user_id = $this->Session->read('userData.User.id');
		$this->set('group_id',$group_id);
		
		$cond_group_detail = "Group.id = '".$group_id."' AND Group.status = '1'";
        $group_detail = $this->Group->find('first',array('conditions'=>$cond_group_detail));
		$this->set('group_detail',$group_detail);
	  }
	  
	  
	  function get_suggestion_list_friends(){
	  	$this->_checkSessionUser();
		$user_id = $this->Session->read('userData.User.id');
	  	$search_text = $_REQUEST['search_text']; 
		$group_id = $_REQUEST['group_id']; 
		$str_selected_users = $_REQUEST['str_selected_users']; 
		
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
			foreach($search_friend_list as $key => $val){
				
				$con_is_member = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$val['Friendlist']['receiver_id']."' AND GroupUser.status = '1'";
				$count_is_member = $this->GroupUser->find('count',array('conditions'=>$con_is_member));
				if($count_is_member == 0)			// Remove the friend who does not belong to selected group
				{
					if(!empty($arr_notified_users)){
						if(!in_array($val['Friendlist']['receiver_id'], $arr_notified_users)){	// Remove the friend to whom the notification is sent already 
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
			
		}
		else{
				$arr_search_result= array();
		}
		
		$this->set('arr_search_result',$arr_search_result);		
	  }
	  
	  /*function get_suggestion_list_users(){
	  	$this->_checkSessionUser();
		$user_id = $this->Session->read('userData.User.id');
	  	$search_text = $_REQUEST['search_text']; 
		$group_id = $_REQUEST['group_id']; 
		$str_selected_users = $_REQUEST['str_selected_users']; 
		
		/-------------------------------    code to fetch the friend list starts   -------------------------/
		$condition_friend_list= "Friendlist.sender_id = '".$user_id."'";
		$search_friend_list = $this->Friendlist->find('all',array('conditions'=>$condition_friend_list));  
		$arr_friends= array();
		
		if(count($search_friend_list) > 0){
			foreach($search_friend_list as $key => $val){
					array_push($arr_friends, $val['Friendlist']['receiver_id']);	
			}		
		}
		/-------------------------------     code to fetch the friend list ends     -------------------------/
		
		/----------------    code to fetch the users who belongs to the specified Group starts    -------------------/
		
		$condition_group_users= "GroupUser.group_id = '".$group_id."' AND GroupUser.status = '1'";
		$group_user_list = $this->GroupUser->find('all',array('conditions'=>$condition_group_users, 'fields'=>array('GroupUser.id','GroupUser.user_id'))); 
		$arr_group_users= array();
		
		if(count($group_user_list) > 0){
			foreach($group_user_list as $key1 => $val1){
					array_push($arr_group_users, $val1['GroupUser']['user_id']);	
			}		
		} 
		
		/------------------    code to fetch the users who belongs to the specified Group ends    ---------------------/
		
		
		if(!empty($arr_friends)){
			$str_friends= implode(',', $arr_friends);
			
			if(!empty($arr_group_users)){
				$str_users= implode(',', $arr_group_users);
				$condition_user_list= "(User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND User.status='1' AND User.id NOT IN ($str_friends) AND User.id NOT IN ($str_users)";
			}
			else{
				$condition_user_list= "(User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND User.status='1' AND User.id NOT IN ($str_friends)";
			}
			$search_user_list = $this->User->find('all',array('conditions'=>$condition_user_list ,'order' => array('User.fname ASC', 'User.lname ASC')));  
		}
		else{
			if(!empty($arr_group_users)){
				$str_users= implode(',', $arr_group_users);
				$condition_user_list= "(User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND User.status='1' AND User.id NOT IN ($str_users)";
			}
			else{
				$condition_user_list= "(User.fname LIKE '%".$search_text."%' OR User.lname LIKE '%".$search_text."%') AND User.status='1'";
			}
			$search_user_list = $this->User->find('all',array('conditions'=>$condition_user_list ,'order' => array('User.fname ASC', 'User.lname ASC')));
			//echo '====>'.$this->getLastQuery();exit;
		}

		//echo $str_selected_users;
		if($str_selected_users!='' && $str_selected_users!='0'){
			$arr_selected_users= explode(',', $str_selected_users);
			$arr_search_result= array();
			
			foreach($search_user_list as $key2 => $val2){	
				if(!in_array($val2['User']['id'], $arr_selected_users)){
					array_push($arr_search_result, $val2);		
				}
			}
		}
		else{
			$arr_search_result= $search_user_list;
		}
		
		$this->set('arr_search_result',$arr_search_result);		
	  }*/
	  
	  function get_suggestion_list_users(){
	  
	  		$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$search_text = $_REQUEST['search_text']; 
			$group_id = $_REQUEST['group_id']; 
			$str_selected_users = $_REQUEST['str_selected_users'];
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
			else{			//when search text does not match or all users belong to the group already
				$this->set('arr_search_result',$arr_not_group_users);	
			}
			
	  }
	  
	  function submit_invitation(){
	  
	  		$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
		
	  		$group_id= $_REQUEST['group_id'];	
			$mode= $_REQUEST['mode'];
			$sender_type= $_REQUEST['sender_type'];
			$group_type= $_REQUEST['group_type'];
			
			if(isset($mode) && $mode=='invite_friends'){
				
				$str_users= $_REQUEST['str_users'];	
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
                    $this->Notification->save($this->data['Notification']);
				}
				echo "1";
				exit;
			}	
			else if(isset($mode) && $mode=='invite_users'){
				
				$str_users= $_REQUEST['str_users'];	
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
                    $this->Notification->save($this->data['Notification']);
				}
				echo "1";
				exit;
			}		
	  }

	  function push_message($group_id){
			//Configure::write('debug',3);
			$this->layout = ""; 
			$response = array();
			$defaultconfig = $this->getDefaultConfig();      
			$base_url = $defaultconfig['base_url'];
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
			$notification_id = 0;
			
			 			
			//$user_id    = '2';
			//$group_id    = '2';
		   
		   
			
	
			$condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";
	
			$group_details = $this->Group->find('first',array('conditions'=>$condition2));
		
			  $group_members = array(); 
	
			  $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";    //Condition to check Group Owner
			  $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));
	
			  if($count_chk_owner > 0)
			  {
	
	
			  $condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."' AND  GroupUser.user_id != '".$user_id."'";  // Fetches the all members (public+private) of the Group
			  $all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));
	
			  //pr($all_group_members);exit();
				foreach($all_group_members as $groups)
				{
					$list['id'] = $groups['GroupUser']['id'];
					$list['user_id'] = $groups['GroupUser']['user_id'];
					$list['user_type'] = $groups['GroupUser']['user_type'];
					$list['member_mode'] = $groups['GroupUser']['member_mode'];
		  
					$condition4 = "Group.status= '1' AND  Group.id = '".$groups['GroupUser']['group_id']."'";
					$group_detail = $this->Group->find('first',array('conditions'=>$condition4));
					$list['group_id'] = $groups['GroupUser']['group_id'];
					$list['group_title'] = $group_detail['Group']['group_title'];
	
					$con_user = "User.status= '1' AND  User.id = '".$groups['GroupUser']['user_id']."'";
					$user_detail = $this->User->find('first',array('conditions'=>$con_user));
					$list['user_name'] = ucfirst($user_detail['User']['fname'].' '.$user_detail['User']['lname']) ;
					if(!empty($user_detail['User']['image']))
					{
	
					  $list['image_url'] = $user_detail['User']['image'];
					   
					}
					else
					{
					  $list['image_url'] = '';
					}
	
				   //
				  array_push($group_members,$list);
	
				  
				}
				  
				  //pr($group_members);exit();
				  $this->set('group_members',$group_members);
				  $this->set('session_user_id',$session_user_id); 
			}
			else 
			{
			  $group_members = []; 
		  
			  $this->set('group_members',$group_members);
			  $this->set('session_user_id',$session_user_id); 
			}
	
		 
	  }


      function push_message_sent(){
           
            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');
            
            //pr($this->params['form']);exit();
            $sender_id    = $user_id;
            $group_id    = $this->params['form']['group_id'];
            $message = $this->params['form']['message'];
            $member_list    = $this->params['form']['push'];

            //pr($member_list);exit;
           /* $sender_id = '2';
            
            $group_id = '2';
            $message = "dfgdfgdfgffdggdfdfgdfgdfgdfdfgdfgdfgdfggdfgdf";
            $member_list = array("7","8","9");*/

            if(!empty($member_list))
            {
            foreach($member_list as $members)
            
            {
                
                    $this->data['Notification']['sender_id'] = $sender_id;
                    $this->data['Notification']['type'] = 'P';
                    $this->data['Notification']['group_id'] = $group_id;
                    $this->data['Notification']['message'] = $message;                     
                    $this->data['Notification']['receiver_id'] = $members;
                    $cond_group_detail = "Group.id = '".$group_id."' AND Group.status = '1'";
                    $group_detail = $this->Group->find('first',array('conditions'=>$cond_group_detail));
                    $this->data['Notification']['group_type'] = $group_detail['Group']['group_type'];

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

                    $con_sender_detail = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$sender_id."'";
                    $sender_detail = $this->GroupUser->find('first',array('conditions'=>$con_sender_detail));

                    if($sender_detail['GroupUser']['user_type'] == 'O')
                    {
                    $this->data['Notification']['sender_type'] = 'GO';
                    }
                    else if($sender_detail['GroupUser']['user_type'] == 'M')
                    {
                    //$this->data['Notification']['sender_type'] = 'GM'; 
                        $response['is_error'] = 1;  
                        $response['err_msg'] = 'You are not authorized'; 
                        exit;  
                    }

                    $this->data['Notification']['is_read'] = 0;
                    $this->data['Notification']['is_receiver_accepted'] = 2;
                    $this->data['Notification']['is_reversed_notification'] = 0;
                    $this->data['Notification']['status'] = 1;
                    $this->Notification->create();
                    $this->Notification->save($this->data['Notification']);
                  
            }
            /*$response['is_error'] = 0;  
            $response['success_msg'] = 'Message sent successfully';*/

             $this->Session->setFlash(__d("statictext", "Message sent successfully", true));
             $_SESSION['meesage_type'] = '1';
             $this->redirect("/group/group_detail/".$group_id);

        }
        else
        {

           /* $response['is_error'] = 1;  
            $response['err_msg'] = 'Message sent failed';*/

             $this->Session->setFlash(__d("statictext", "Message sent failed", true));
             $_SESSION['meesage_type'] = '0';
             $this->redirect("/group/group_detail/".$group_id);

        }

       
     }
	 
	 
	  function getLastQuery() {
		  $dbo = ConnectionManager::getDataSource('default');
		  $logs = $dbo->getLog();
		  $lastLog = end($logs['log']);
		  return $lastLog['query'];
	  }

       
}?>
