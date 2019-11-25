<?php
App::import('Vendor','authnet_aim');
class GroupController extends AppController {

    var $name = 'Group';
    var $uses = array('Category','Group','SubscriptionPlan','User','GroupUser','City','State','GroupImage','GroupDoc','Video','Notification','Friendlist','Event','EmailTemplate','GroupMessage','GroupMessageReply');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    

    function group_list($category_id){
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        //$this->_checkSessionUser();

  
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);


        /******************************starts *****************************/
		
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
		
		/******************************Ends *****************************/

       
        $user_id = $this->Session->read('userData.User.id');
        $this->set('user_id',$user_id);

        $this->set('category_id',$category_id);
        
        $condition = "Category.status= '1'  AND Category.id= '".$category_id."'";
        $category_details = $this->Category->find("first",array('conditions'=>$condition));
       // pr($category_details);exit();
        $this->set('category_details',$category_details);

        $limit = 4;
		$conditions_group = "Group.status= '1'  AND Group.category_id= '".$category_id."' AND Group.state_id= '".$selected_state_id."'  AND Group.city_id= '".$selected_city_id."'";
        $this->paginate = array('conditions' =>$conditions_group,'limit' => $limit,'order'=>'Group.group_title ASC');
		$this->Group->bindModel(array('hasMany' => array('GroupUser' => array('foreignKey' => 'group_id'))),false);
		$group_list = $this->paginate('Group');

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

    function show_all_city()
    {
        $stateid = $_REQUEST['state_id'];
        $condition = "City.isdeleted = '0' AND City.state_id='".$stateid."'";
        $citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        $this->set('citylist',$citylist);
         /*return $city_list;
         exit;*/
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
		  $this->layout = "";
		  $user_id = $this->Session->read('userData.User.id');
		  $this->set('pageTitle', 'Group Type');
		   
		  $conditions = "GroupUser.group_id =  '".$group_id."' AND GroupUser.user_id =  '".$user_id."' ";

		  $GroupType = $this->GroupUser->find('first', array('conditions' => $conditions));
		
		  return $GroupType;
		   
	}


	function group_is_join($group_id){
	
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
      

  */

  function add_business_category_group(){
        
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
        if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= ''){
        
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'group_images/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=160 && $uploaded_height >= 120)
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
                        $this->data['Group']['group_title'] = addslashes($this->params['form']['group_name']);
                        $this->data['Group']['group_desc'] = addslashes($this->params['form']['g_desc']);
                        //$this->data['Group']['group_purpose'] = addslashes($this->params['form']['g_purpose']);
                        $this->data['Group']['group_type'] = 'B';    
                        $this->data['Group']['group_owners'] = $user_id;
                        $this->data['Group']['created_by'] = $user_id;
                        $this->data['Group']['category_id'] = $this->params['form']['category_id'];
                        $this->data['Group']['icon'] = $upload_image;
                        $this->data['Group']['group_url'] = $this->params['form']['g_url'];
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
                        if($this->Group->save($this->data['Group'])){
                        
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
                            
                            if($this->GroupUser->save($this->data['GroupUser'])){
                                 $this->Session->setFlash(__d("statictext", "Group added sucessfully", true));
                                 $_SESSION['meesage_type'] = '1';
                                 $this->redirect("/group/group_list/".$this->params['form']['category_id']);
                            }
                       } 
                 
      }
      
   }


  function add_public_organization_category_group(){
        
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
        if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= ''){
		
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'group_images/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=160 && $uploaded_height >= 120)
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
                        $this->data['Group']['group_title'] = addslashes($this->params['form']['group_name']);
                        $this->data['Group']['group_desc'] = addslashes($this->params['form']['g_desc']);
                        //$this->data['Group']['group_purpose'] = addslashes($this->params['form']['g_purpose']);
                        $this->data['Group']['group_type'] = 'PO';    
                        $this->data['Group']['group_owners'] = $user_id;
                        $this->data['Group']['created_by'] = $user_id;
                        $this->data['Group']['category_id'] = $this->params['form']['category_id'];
                        $this->data['Group']['icon'] = $upload_image;
						$this->data['Group']['group_url'] = $this->params['form']['g_url'];
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
                        if($this->Group->save($this->data['Group'])){
						
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
							
                       		if($this->GroupUser->save($this->data['GroupUser'])){
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
        $this->set('user_details',$user_details);


        
        $upload_image = '';
        //$is_upload = 1; 
     
      if(!empty($this->params['form']) && isset($this->params['form'])){
				if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= ''){
					$image_name = $this->params['form']['upload_image']['name'];
					
					$extension = end(explode('.',$image_name));               
					$upload_image = time().accessCode(5).'.'.$extension;          
					$upload_target_original = 'group_images/'.$upload_image;
								
					$imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
					list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
					
					
					 if($type == 1 || $type == 2 ){
						if($uploaded_width >=160 && $uploaded_height >= 120)
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
       
        
				/*$this->data['Group']['phone'] = $this->params['form']['phn_no1'].'-'.$this->params['form']['phn_no2'].'-'.$this->params['form']['phn_no3'];*/
				/*$this->data['Group']['plan_id'] = '0';
				$this->data['Group']['amount'] = '0';   */                     
				$this->data['Group']['group_title'] = addslashes($this->params['form']['group_name']);
				$this->data['Group']['group_desc'] = addslashes($this->params['form']['g_desc']);
				//$this->data['Group']['group_purpose'] = addslashes($this->params['form']['g_purpose']);
				$this->data['Group']['group_type'] = 'F';    
				$this->data['Group']['group_owners'] = $user_id;
				$this->data['Group']['created_by'] = $user_id;
				$this->data['Group']['category_id'] = '0';
				$this->data['Group']['icon'] = $upload_image;
				/*$this->data['Group']['card_number'] = '0';
				$this->data['Group']['expiry_month'] = '0';
				$this->data['Group']['expiry_year'] = '0';
				$this->data['Group']['cvv_number'] = '0';
				$this->data['Group']['approval_code'] = '0';
				$this->data['Group']['avs_result'] = '0';
				$this->data['Group']['cvv_result'] = '0';
				$this->data['Group']['transaction_id'] = '0';*/
				$this->data['Group']['state_id'] = $selected_state_id;
				$this->data['Group']['city_id'] = $selected_city_id;
				$this->Group->create();
				if($this->Group->save($this->data['Group'])){
				
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
					
				   if($this->GroupUser->save($this->data['GroupUser'])){
						 $this->Session->setFlash(__d("statictext", "Group added sucessfully", true));
						 $_SESSION['meesage_type'] = '1';
						 $this->redirect("/category/category_list/"); 
					}
			   } 
         
        
      }
      
   }
   
   
  function add_sub_group(){
        
       $this->layout = "";
        $this->set('pagetitle', 'Create Group');
        $sitesettings = $this->getSiteSettings(); 
         $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $upload_image = '';
        $is_upload = 1; 
     
      if(!empty($this->params['form']) && isset($this->params['form'])){
				if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= ''){
					$image_name = $this->params['form']['upload_image']['name'];
					
					$extension = end(explode('.',$image_name));               
					$upload_image = time().accessCode(5).'.'.$extension;          
					$upload_target_original = 'sub_group_images/'.$upload_image;
								
					$imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
					list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
					
					
					 if($type == 1 || $type == 2 ){
						if($uploaded_width >=160 && $uploaded_height >= 120)
						{
							if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
																																																									
									$upload_target_thumb = 'sub_group_images/thumb/'.$upload_image;
									$upload_target_medium = 'sub_group_images/medium/'.$upload_image;
									$upload_target_web = 'sub_group_images/web/'.$upload_image;
								   
		
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
							
							$this->redirect("/group/group_detail/".$this->params['form']['parent_id']);
						
							}
						
						}
						else
						{        
						 
						 $is_upload = 0;
						$this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
						$_SESSION['meesage_type'] = '0';
						$this->redirect("/group/group_detail/".$this->params['form']['parent_id']);
						
						}
						
					 }
					 else
					 {
						$is_upload = 0;
						$this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
						$_SESSION['meesage_type'] = '0';
						$this->redirect("/group/group_detail/".$this->params['form']['parent_id']);
					  
					 }
				
				}
       
        		$con_grp = "Group.id = '".$this->params['form']['parent_id']."'"; 
				$arr_grp_detail = $this->Group->find('first',array('conditions'=>$con_grp));  
				$parent_grp_title= $arr_grp_detail['Group']['group_title'];
				$sub_grp_title= $parent_grp_title.' - '.$this->params['form']['group_name'];
				                     
				$this->data['Group']['parent_id'] = addslashes($this->params['form']['parent_id']);
				$this->data['Group']['group_title'] = addslashes($sub_grp_title);
				$this->data['Group']['group_desc'] = addslashes($this->params['form']['g_desc']);
				$this->data['Group']['group_type'] = 'F';    
				$this->data['Group']['group_owners'] = $user_id;
				$this->data['Group']['created_by'] = $user_id;
				$this->data['Group']['category_id'] = '0';
				$this->data['Group']['icon'] = $upload_image;
				$this->data['Group']['state_id'] = $selected_state_id;
				$this->data['Group']['city_id'] = $selected_city_id;
				if($this->params['form']['group_user_type']=='member'){
					$this->data['Group']['status'] = '0';
				}
				else if($this->params['form']['group_user_type']=='owner'){
					$this->data['Group']['status'] = '1';
				}
				else if($this->params['form']['group_user_type']=='nonmember'){
					$this->data['Group']['status'] = '0';
				}
				$this->Group->create();
				if($this->Group->save($this->data['Group'])){
				
					$last_insert_id = $this->Group->getLastInsertId();
					
					if($this->params['form']['group_user_type']=='member'){
						 $condition = "Group.id= '".$this->params['form']['parent_id']."'";
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
						 
						 $this->Session->setFlash(__d("statictext", "Request for Sub Group creation sent sucessfully", true));
						 $_SESSION['meesage_type'] = '1';
						 $this->redirect("/group/group_detail/".$this->params['form']['parent_id']); 
					}
					else if($this->params['form']['group_user_type']=='owner'){
					
						$condition = "User.status= '1'  AND User.id= '".$user_id."'";
						$user_details = $this->User->find("first",array('conditions'=>$condition));
						//$this->set('user_details',$user_details);
					
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
					   
							 $this->Session->setFlash(__d("statictext", "Sub Group added sucessfully", true));
							 $_SESSION['meesage_type'] = '1';
							 $this->redirect("/group/group_detail/".$this->params['form']['parent_id']); 
						}
					}
					else if($this->params['form']['group_user_type']=='nonmember'){
						 $condition = "Group.id= '".$this->params['form']['parent_id']."'";
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
						 
						 $this->Session->setFlash(__d("statictext", "Request for Sub Group creation sent sucessfully", true));
						 $_SESSION['meesage_type'] = '1';
						 
						 /*$page="Waiting for approval the newly created Sub Group: ".$sub_grp_title;
						 $message= "A new Sub Group ".$sub_grp_title." has been created under the Parent Group: "$group_details['Group']['group_title'];
						 $this->send_notification($user_id, $arr_group_owners[$k], $last_insert_id, $message, $page); 
						 */
						 $this->redirect("/group/group_detail/".$this->params['form']['parent_id']); 
					}
					
			   } 
         
        
      }
      
   }


   function post_message()
   {

    
        if(isset($this->params['form']) && !empty($this->params['form']))
        {
            $group_id = $this->params['form']['group_id'];
            $group_type = $this->params['form']['group_type'];
            $user_type = $this->params['form']['user_type'];
            $user_id = $this->Session->read('userData.User.id');
            $message = $this->params['form']['message'];
            $topic = $this->params['form']['topic'];

            $this->data['GroupMessage']['group_id'] = $group_id;
            $this->data['GroupMessage']['group_type'] = $group_type;
            $this->data['GroupMessage']['user_type'] = $user_type;
            $this->data['GroupMessage']['user_id'] = $user_id;
            $this->data['GroupMessage']['message'] = $message;
            $this->data['GroupMessage']['topic'] = $topic;
            $this->GroupMessage->create();
            if($this->GroupMessage->save($this->data['GroupMessage']))
            {

                $this->Session->setFlash(__d("statictext", "Topic added sucessfully", true));
                $_SESSION['meesage_type'] = '1';
                $this->redirect("/group/group_detail/".$group_id); 

            }
            else
            {
                $this->Session->setFlash(__d("statictext", "Try again!", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect("/group/group_detail/".$group_id); 
            }

                    
        } 

   }



   function check_edit_delete_auth($group_message_id)
   {

    $user_id = $this->Session->read('userData.User.id');

    $conditions = "GroupMessage.id = '".$group_message_id."'";
        $ArGroupMessage = $this->GroupMessage->find('first',array('conditions'=>$conditions));
        $group_id = $ArGroupMessage['GroupMessage']['group_id'];
    /***************   Group Member Type Starts    **************/
        $condn_group_user_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
        $arr_group_user_status = $this->GroupUser->find('first',array('conditions'=>$condn_group_user_status, 'fields' => 'GroupUser.user_type'));     
        $user_type=  $arr_group_user_status['GroupUser']['user_type'];
        
        $condn_group_type = "Group.id = '".$group_id."'";
        $arr_group_type = $this->Group->find('first',array('conditions'=>$condn_group_type, 'fields' => 'Group.group_type'));     
        $group_type=  $arr_group_type['Group']['group_type'];
        
        $this->set('group_user_type',$user_type);

        if($group_type=='F'){
            if($user_type=='O' || $user_id == $ArGroupMessage['GroupMessage']['user_id']){
                $can_edit_delete = 1;
                
            }
            else{
                $can_edit_delete = 0;
                
            }
        }
        else if($group_type=='B'){
            if($user_type=='O'){
                $can_edit_delete = 1;
                
            }
            else{
                $can_edit_delete = 0;
                
            }
        }
        /***************   Group Member Type Ends    **************/
        return $can_edit_delete;
   }


   function edit_message()
   {

    
        $message_id = $_REQUEST['message_id'];
        $user_id = $this->Session->read('userData.User.id');
        $message = $_REQUEST['message'];
        $group_id = $_REQUEST['group_id'];
        $topic = $_REQUEST['topic'];
        $authorized_member = '0';
        $conditions = "GroupMessage.id = '".$message_id."'";
        $ArGroupMessage = $this->GroupMessage->find('first',array('conditions'=>$conditions));


        /***************   Group Member Type Starts    **************/
        $condn_group_user_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
        $arr_group_user_status = $this->GroupUser->find('first',array('conditions'=>$condn_group_user_status, 'fields' => 'GroupUser.user_type'));     
        $user_type=  $arr_group_user_status['GroupUser']['user_type'];
        
        $condn_group_type = "Group.id = '".$group_id."'";
        $arr_group_type = $this->Group->find('first',array('conditions'=>$condn_group_type, 'fields' => 'Group.group_type'));     
        $group_type=  $arr_group_type['Group']['group_type'];
        
        $this->set('group_user_type',$user_type);

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
        
        /***************   Group Member Type Ends    **************/

        if($can_edit == 1)
        {


            if($_REQUEST['topic']!='' && $_REQUEST['message']!='')
            {
                $this->data['GroupMessage']['id'] = $message_id;
                //$this->data['GroupMessage']['user_id'] = $user_id;
                $this->data['GroupMessage']['message'] = $message;
                $this->data['GroupMessage']['topic'] = $topic;
                //$this->data['GroupMessage']['created'] = date('Y-m-d H:i:s');
                $this->GroupMessage->save($this->data['GroupMessage']);


                $this->GroupMessage->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.fname,User.lname,User.image'))),false);
                $this->GroupMessage->bindModel(array('hasMany' => array('GroupMessageReply' => array('foreignKey' => 'message_id'))),false);
                $ArGroupMessage = $this->GroupMessage->find('first',array('conditions' => $conditions));
                //pr($ArGroupMessage);exit;
                $this->set('GroupMessage',$ArGroupMessage);
                $this->set('can_edit',$can_edit);
            }else{
                echo "0";
                exit;
            }
            
        }else{

            echo "0";
            exit;
        }
   }



   function delete_message()
   {

        $topic_id = $_REQUEST['topic_id'];
        $group_id = $_REQUEST['group_id'];
        $user_id = $this->Session->read('userData.User.id');
        
        $conditions = "GroupMessage.id = '".$topic_id."'";
        $ArGroupMessage = $this->GroupMessage->find('first',array('conditions'=>$conditions));

        if(!empty($ArGroupMessage))
        {

            $condn_group_user_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
            $arr_group_user_status = $this->GroupUser->find('first',array('conditions'=>$condn_group_user_status, 'fields' => 'GroupUser.user_type'));     
            $user_type=  $arr_group_user_status['GroupUser']['user_type'];
            
            $condn_group_type = "Group.id = '".$group_id."'";
            $arr_group_type = $this->Group->find('first',array('conditions'=>$condn_group_type, 'fields' => 'Group.group_type'));     
            $group_type=  $arr_group_type['Group']['group_type'];
            
            $this->set('group_user_type',$user_type);
            
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

            if($can_delete == 1){
                $this->GroupMessage->id = $topic_id;
                if($this->GroupMessage->delete())
                {

                    $this->GroupMessageReply->deleteAll(array('GroupMessageReply.message_id'=>$topic_id),false);

                    echo "0";

                }
                else
                {
                    echo "1";
                }
            }else{
                echo "1";
            }
        }
        else
        {
            echo "1";
        }

        exit();
   }


   function post_reply()
   {

        $group_id = $_REQUEST['group_id'];
        $message_id = $_REQUEST['message_id'];
        $reply = $_REQUEST['comment'];
        $replied_by = $this->Session->read('userData.User.id');
        

        $condn_group_user_status = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."'";
        $arr_group_user_status = $this->GroupUser->find('first',array('conditions'=>$condn_group_user_status, 'fields' => 'GroupUser.user_type'));     
        $group_user_type=  $arr_group_user_status['GroupUser']['user_type'];


        $this->data['GroupMessageReply']['group_id'] = $group_id;
        $this->data['GroupMessageReply']['message_id'] = $message_id;
        $this->data['GroupMessageReply']['replied_by'] = $replied_by;
        $this->data['GroupMessageReply']['reply'] = $reply;
        $this->GroupMessageReply->create();
        $this->GroupMessageReply->save($this->data['GroupMessageReply']);

        $last_comment_id = $this->GroupMessageReply->getLastInsertID();
        
        $conditions = "GroupMessageReply.id = '".$last_comment_id."'";
        $conditions1 = "GroupMessageReply.message_id = '".$message_id."'";
        $this->GroupMessageReply->bindModel(array('belongsTo' => array('User' => array('className' => 'User','foreignKey' => 'replied_by'))), false);      
        $MessageReply = $this->GroupMessageReply->find('first', array('conditions' => $conditions));
        $MessageReplyCount = $this->GroupMessageReply->find('count', array('conditions' => $conditions1));
        $this->set('MessageReply',$MessageReply);   
        $this->set('MessageReplyCount',$MessageReplyCount);
        $this->set('group_id',$group_id);
        $this->set('group_user_type',$group_user_type);



   }


   function edit_reply()
   {

        $reply_id = $_REQUEST['reply_id'];
        $reply = $_REQUEST['reply'];
        $group_id = $_REQUEST['group_id'];
        $replied_by = $this->Session->read('userData.User.id');
        $can_edit = 0;

        $this->set('group_id',$group_id);

        $conditions = "GroupMessageReply.id = '".$reply_id."'";
        $ArGroupMessageReply = $this->GroupMessageReply->find('first',array('conditions'=>$conditions));

        if(!empty($ArGroupMessageReply)){

            $con_user_type = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$replied_by."'";
            $ArUserType = $this->GroupUser->find('first',array('conditions' => $con_user_type, 'fields' => 'GroupUser.user_type'));

            if($ArGroupMessageReply['GroupMessageReply']['replied_by'] == $replied_by)
            {
                $can_edit = 1;
            }
            else if($ArUserType['GroupUser']['user_type'] == 'O')
            {
                $can_edit = 1;
            }

            if($can_edit == 1){

                $this->data['GroupMessageReply']['id'] = $reply_id;
                $this->data['GroupMessageReply']['reply'] = $reply;
                $this->GroupMessageReply->save($this->data['GroupMessageReply']);

                $last_comment_id = $reply_id;
                
                $conditions = "GroupMessageReply.id = '".$last_comment_id."'";
                
                $this->GroupMessageReply->bindModel(array('belongsTo' => array('User' => array('className' => 'User','foreignKey' => 'replied_by'))), false);      
                $MessageReply = $this->GroupMessageReply->find('first', array('conditions' => $conditions));
                $this->set('MessageReply',$MessageReply);   
                
            }
            else
            {
                echo 1;
                exit();
            }
        }
        else
        {
            echo 2;
            exit();
        }


   }



   function delete_reply()
   {

        $reply_id = $_REQUEST['reply_id'];
        $replied_by = $this->Session->read('userData.User.id');
        $group_id = $_REQUEST['group_id'];
        $can_delete = 0;
        
        $conditions = "GroupMessageReply.id = '".$reply_id."'";
        $ArGroupMessageReply = $this->GroupMessageReply->find('first',array('conditions'=>$conditions));

        if(!empty($ArGroupMessageReply))
        {

            if($ArGroupMessageReply['GroupMessageReply']['replied_by'] == $replied_by)
            {

                $con_user_type = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$replied_by."'";
                $ArUserType = $this->GroupUser->find('first',array('conditions' => $con_user_type, 'fields' => 'GroupUser.user_type'));

                if($ArGroupMessageReply['GroupMessageReply']['replied_by'] == $replied_by)
                {
                    $can_delete = 1;
                }
                else if($ArUserType['GroupUser']['user_type'] == 'O')
                {
                    $can_delete = 1;
                }

                if($can_delete == 1)
                {
               
                    $this->GroupMessageReply->id = $reply_id;
                    $this->GroupMessageReply->delete();
                
                    echo 0;
                }
                else
                {
                    echo 1;
                }
            }
            else
            {
                echo 1;
            }
        }
        else
        {
            echo 2;
        }

        exit();
   }


   function get_message_replies($message_id)
   {
        $conditions = "GroupMessageReply.message_id = '".$message_id."' AND GroupMessageReply.status = '1' AND GroupMessageReply.isdeleted = '0'";
        $this->GroupMessageReply->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'replied_by','fields'=>'User.fname,User.lname,User.image'))),false);
        $ArMessageReply = $this->GroupMessageReply->find('all',array('conditions' => $conditions));
        return $ArMessageReply;
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
        /************************Category listing for edit group starts**************************/
        $condition = "Category.status ='1' AND Category.POG_status ='0'";
        $all_categories = $this->Category->find("all",array('conditions'=>$condition,'order' => 'Category.id DESC'));
        $this->set('all_categories', $all_categories);
        /************************Category listing for edit group ends**************************/
		
		/************************Category listing for edit group starts**************************/
        $condition_POG = "Category.status ='1' AND Category.POG_status ='1'";
        $all_categories_POG = $this->Category->find("all",array('conditions'=>$condition_POG,'order' => 'Category.id DESC'));
        $this->set('all_categories_POG', $all_categories_POG);
        /************************Category listing for edit group ends**************************/
       
        /***************   Group Detail Starts    **************/
		
        $condition = "Group.status= '1'  AND Group.id= '".$group_id."'";
        $group_details = $this->Group->find("first",array('conditions'=>$condition));
      	$this->set('group_details',$group_details);
		
		if($group_details['Group']['parent_id']!='0'){
			$parent_grp_id= $group_details['Group']['parent_id'];
			
			$condition_parent_grp = "Group.status = '1' AND (find_in_set('".$user_id."',Group.`group_owners`)) AND Group.id = '".$parent_grp_id."'";
			$is_parent_grp_owner = $this->Group->find('count',array('conditions'=>$condition_parent_grp));  
		}
		else{
			$is_parent_grp_owner = 0;
		}
		$this->set('is_parent_grp_owner',$is_parent_grp_owner);
		
		
		##################################         Check the list of sub Groups of the Group starts        ###########################
		
		if($group_details['Group']['group_type']!='B'){
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
		
		
		if($group_details['Group']['parent_id']!='0'){			// Sub Group
			if($group_details['Group']['created_by'] == $user_id || $is_parent_grp_owner>0){
				$show_delete=1;
			}
			else{
				$show_delete=0;
			}
		}
		else{
			if($group_details['Group']['created_by'] == $user_id){
				$show_delete=1;
			}
			else{
				$show_delete=0;
			}
		}
		$this->set('show_delete',$show_delete);
		
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
        

        //echo $group_user_type;exit;
		
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
        
            $limit_f = 4;
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

        }
        else
        {
            //$this->set('all_free_groups',''); 
            $this->set('ArGroupMessage', '');
        } 


        /****************** Group Message End ********************/

        
        /***********************      event list starts       **********************/
		if($str_subgroups!=''){
		$event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE (`group_id` = '".$group_id."' OR (`group_id` IN (".$str_subgroups.") AND (`type` = 'public' OR `type` = 'semi_private'))) AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE (`group_id` = '".$group_id."' OR (`group_id` IN (".$str_subgroups.") AND (`type` = 'public' OR `type` = 'semi_private'))) AND `status` = '1' AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
		}
		else{
        $event_details = $this->Event->query("SELECT * FROM ( SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND `event_date` = '".$date."' UNION SELECT `id`,`title`,`desc`,`created_by_owner_id`,`group_type`,`type`,`event_start_timestamp` as `sort_time`,`group_id`,`status` ,`event_date`,`event_start_date` ,`event_end_date`,`deal_amount`,`location`,`latitude`,`longitude`,`is_multiple_date` FROM `events` WHERE `group_id` = '".$group_id."' AND `status` = '1' AND`event_start_date` <= '".$date."' AND `event_end_date` >= '".$date."') AS `Event`ORDER BY `Event`.`sort_time`ASC");
		}
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
			if($events['Event']['group_id']!=$group_id){
					
				$condition_grp_name = "Group.id = '".$events['Event']['group_id']."'";
				$arr_grp_name = $this->Group->find('first',array('conditions'=>$condition_grp_name)); 
				$list['group_name'] =  $arr_grp_name['Group']['group_title'];
			}
			else{
				$list['group_name'] =  $group_details['Group']['group_title'];
			}
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
		 //pr($event_list);exit();
	 	 $this->set('event_list',$event_list);

		/***********************      event list ends       **********************/
		
		/***********************      Fetch the dates with Events starts       **********************/
		
		
		$first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
		$last_day_this_month  = date('Y-m-t');
		$days_this_month= date('t');
		$current_day_this_month= $first_day_this_month;
		$arr_event_dates= array();
		
		for($i=1;$i<=$days_this_month;$i++){
			
			 if($current_day_this_month<= $last_day_this_month){
				 if($str_subgroups!=''){
					 $con_count_events = "((Event.`event_date` = '".$current_day_this_month."') OR (Event.`event_start_date` <= '".$current_day_this_month."' AND Event.`event_end_date` >= '".$current_day_this_month."')) AND (Event.`group_id` = '".$group_id."' OR (Event.`group_id` IN (".$str_subgroups.") AND `type` = 'public')) AND Event.`status` = '1'";
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
		
		$this->set('arr_event_dates',$arr_event_dates);
		/***********************      Fetch the dates with Events ends       **********************/
  }
  
  function get_grp_name($grp_id){
  		
		$condition_grp_name = "Group.id = '".$grp_id."'";
		$arr_grp_name = $this->Group->find('first',array('conditions'=>$condition_grp_name));  
		
		return $arr_grp_name['Group']['group_title'];
  }
  
  function delete_group() {

        $group_id = $_REQUEST['group_id'];
        $user_id = $this->Session->read('userData.User.id');
      
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
		
      
      echo 'ok';
      exit();
    }
  
  function fetch_event_dates(){
  	
		$this->layout = ""; 
		$group_id = $_REQUEST['group_id'];
		$next_month_date = $_REQUEST['other_month'];
		
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
		
		$imploded_str_event_dates= implode(',', $arr_event_dates);
		echo $imploded_str_event_dates;
		exit;
		//$this->set('arr_event_dates',$arr_event_dates);
		/***********************      Fetch the dates with Events ends       **********************/
		
  }


  	  function post_all_reply(){
    
        $this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');

        $group_id = $_REQUEST['group_id'];

        $this->set('group_id',$group_id);


        /***************   Group Member Type Starts    **************/
        $group_member_type = 'nonmember';
        $edit_delete_topic_permission = '0';
        $condition_group_owner_count = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_id."'  AND GroupUser.user_id = '".$user_id."' AND GroupUser.user_type= 'O'";   //Check the logged in user is owner or not 
        $group_owner_count = $this->GroupUser->find("count",array('conditions'=>$condition_group_owner_count));
        if($group_owner_count > 0){
            $group_member_type = 'owner';
            $group_user_type = 'O';
            $edit_delete_topic_permission = '1';
        }

        $condition_group_member_count = "GroupUser.status= '1'  AND GroupUser.group_id= '".$group_id."'  AND GroupUser.user_id = '".$user_id."' AND GroupUser.user_type= 'M'";  //Check the logged in user is member or not 
        $arr_group_member_count = $this->GroupUser->find("first",array('conditions'=>$condition_group_member_count));
        if(!empty($arr_group_member_count))
        {    

            if($arr_group_member_count['GroupUser']['can_post_topic'] == '1')
            {
                $authorized_member = '1';
                $edit_delete_topic_permission = '1';
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
        $this->set('edit_delete_topic_permission',$edit_delete_topic_permission);

        //echo $group_user_type;exit;
        
        /***************   Group Member Type Ends    **************/
        
        $con_grp_msg = "GroupMessage.group_id = '".$group_id."'";
        $ArGroupMessageCount = $this->GroupMessage->find('count',array('conditions' => $con_grp_msg));
        //echo $ArGroupMessageCount;
        if($ArGroupMessageCount>0){
        
            $limit = 4;
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
		if($group_detail['Group']['group_type']=='F'){
			$grp_type= 'Private';	
		}
		else if($group_detail['Group']['group_type']=='B'){
			$grp_type= 'Business';	
		}
		else if($group_detail['Group']['group_type']=='PO'){
			$grp_type= 'Public Organisation Group';	
		}

		
		$condition_check_group_member_owner = "GroupUser.group_id = '".$this->params['form']['group_of_id']."' AND GroupUser.user_id = '".$user_id."' AND (GroupUser.user_type = 'O' OR GroupUser.user_type = 'M')"; 	//Check whether the user is Group Owner/ Member
		
		$is_owner_or_member = $this->GroupUser->find('count',array('conditions'=>$condition_check_group_member_owner));  

		if($is_owner_or_member > 0)
		{

			 $this->Session->setFlash(__d("statictext", "You are already a member of this group", true));
			 $_SESSION['meesage_type'] = '0';
			 $this->redirect("/group/group_list/".$group_detail['Group']['category_id']);
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
						
						$this->Session->setFlash(__d("statictext", "You have successfully joined to ".$grp_type." group - ".$group_detail['Group']['group_title'], true));
						$_SESSION['meesage_type'] = '1';
						$this->redirect("/group/group_list/".$group_detail['Group']['category_id']);  
					}
					else
					{
						$this->data['User']['id'] = $user_details['User']['id'];
						$this->data['User']['groups'] = $group_detail['Group']['id'];
						$this->User->save($this->data['User']);
						
						$this->Session->setFlash(__d("statictext", "You have successfully joined to ".$grp_type." group - ".$group_detail['Group']['group_title'], true));
						$_SESSION['meesage_type'] = '1';
						$this->redirect("/group/group_list/".$group_detail['Group']['category_id']);  
					}
				}
				
			//////////////////////////   Insert to Groups field in User table ends      //////////////////////   
			
		}
		
  }


  function group_detail_join_now_request($group_id){
  
   $this->layout = "";
      $user_id = $this->Session->read('userData.User.id');
      $this->set('pageTitle', 'Group Type');
     
        $condition = "Group.id = '".$group_id."'";
        $group_detail = $this->Group->find('first',array('conditions'=>$condition));  

        
        $condition_check_group_member_owner = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$user_id."' AND (GroupUser.user_type = 'O' OR GroupUser.user_type = 'M')";    //Check whether the user is Group Owner/ Member
        
        $is_owner_or_member = $this->GroupUser->find('count',array('conditions'=>$condition_check_group_member_owner));  

        if($is_owner_or_member > 0)
        {

             $this->Session->setFlash(__d("statictext", "You are already a member of this group", true));
             $_SESSION['meesage_type'] = '0';
             $this->redirect("/group/group_list/".$group_detail['Group']['category_id']);
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
                        $this->redirect("/group/group_detail/".$group_id);  
                    }
                    else
                    {
                        $this->data['User']['id'] = $user_details['User']['id'];
                        $this->data['User']['groups'] = $group_detail['Group']['id'];
                        $this->User->save($this->data['User']);
                        
                        $this->Session->setFlash(__d("statictext", "You have successfully joined to Business group - ".$group_detail['Group']['group_title'], true));
                        $_SESSION['meesage_type'] = '1';
                        $this->redirect("/group/group_detail/".$group_id);  
                    }
                }
                
            //////////////////////////   Insert to Groups field in User table ends      //////////////////////   
            
        }
   
  }



  /*function add_group_video(){
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

            
        }*/

        function add_group_video(){
        $this->layout = "";
        $this->set('pagetitle', 'Create Group');
        $sitesettings = $this->getSiteSettings(); 
         $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $group_id = $_REQUEST['group_id'];
        $youtube = $_REQUEST['youtube'];
        /*$group_id = '7';
        $youtube = ' https://youtu.be/SCaKuAdKumA';
*/
       
             
        if($youtube!='')
        {
					$applink= "https://youtu.be/";
					if(strpos($youtube, $applink) !== false){
						$arr_youtube_link= explode("youtu.be/", $youtube);
						$video_code= $arr_youtube_link[1];
						
						$youtube= "http://www.youtube.com/watch?v=".$video_code;
					}
					
					
            		

                            
                            $queryString = parse_url($youtube, PHP_URL_QUERY);

                            parse_str($queryString, $params);

                            $v = $params['v'];  

                                                        
                            if(strlen($v)>0)
                            {
                                $thumbURL = "http://i3.ytimg.com/vi/$v/default.jpg";

                            }

                            // YouTube video url
                            
                           /* $urlArr = explode("/",$youtube);
                            $urlArrNum = count($urlArr);

                            // Youtube video ID
                            $youtubeVideoId = $urlArr[$urlArrNum - 1];

                            // Generate youtube thumbnail url
                            $thumbURL = 'http://i3.ytimg.com/vi/'.$youtubeVideoId.'/0.jpg';*/


                           // echo ($thumbURL);


                            $this->data['Video']['group_id'] =  $group_id;
                            $this->data['Video']['v_image'] = $thumbURL;
                            $this->data['Video']['video'] = $youtube;
                            $this->Video->create();
                            if($this->Video->save($this->data['Video']))
                            {
							   $this->Session->setFlash(__d("statictext", "Video uploaded sucessfully", true));
                               $_SESSION['meesage_type'] = '1';
                               echo '0';
                               exit;   
                               
                               /*$$this->redirect("/group/group_detail/".$group_id);*/ 
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


 	function group_details($group_id=NULL){
	
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

		if($old_noti_detail['Notification']['is_receiver_accepted']=='0' && $old_noti_detail['Notification']['is_reversed_notification']=='0'){    
			$this->data['Notification']['is_read'] = '1';
			$this->data['Notification']['is_receiver_accepted'] = '2';
			$this->Notification->id = $notification_id;                 
			if($this->Notification->save($this->data))			// Update the Notification
			{
              
             // exit();            
			//echo $old_noti_detail['Notification']['sender_type'].'=========='.$old_noti_detail['Notification']['receiver_type'];
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
				/*********************	 Update the notifications to other Admins ends    ******************/
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
							  
							  echo '1';
							  exit;
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
					  }
					  else if($noti_detail['Notification']['sender_type']=='NGM' && $noti_detail['Notification']['receiver_type']=='GO'){	//Notification by accepting the Group Invitation Request
					      
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
							  
							  echo '1';
							  exit;	
							  
							  			
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
					  }
					  else if($noti_detail['Notification']['sender_type']=='NGM' && $noti_detail['Notification']['receiver_type']=='GM'){
							
							  $cond_groupAdmins = "Group.id = '".$noti_detail['Notification']['group_id']."'";
							  $group_detail = $this->Group->find('first',array('conditions'=>$cond_groupAdmins, 'fields'=>'Group.group_owners, Group.group_type'));
							  
							  if(!empty($group_detail)){
							  	
								  if($group_detail['Group']['group_type']=='B'){	//For Business Groups, the NGM will become member directly
								  		
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
								  else{						//For Free Groups, the request will be sent from NGM to all GO
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
			else{
				echo '0';
				exit;
			} 
		} 
		else{
			if($old_noti_detail['Notification']['is_receiver_accepted']=='1'){
				echo '@@0@@';
				exit;
			}
			else if($old_noti_detail['Notification']['is_receiver_accepted']=='2'){
				echo '##0##';
				exit;
			}	
		} 
	 }


    function reject_group_request(){
       
            $this->layout = ""; 
            $notification_id = $_REQUEST['notification_id'];
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            

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
					/*********************	 Update the notifications to other Admins ends    ******************/
				
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

                         $last_insert_id = $this->Notification->getLastInsertId(); 
                         $this->notification_email($last_insert_id);  
						 
						 $page="Group request rejected";
					  	 $this->send_reverse_notification_group($old_noti_detail['Notification']['receiver_id'], $old_noti_detail['Notification']['sender_id'], $old_noti_detail['Notification']['group_id'], $old_noti_detail['Notification']['receiver_type'], $old_noti_detail['Notification']['sender_type'], 'Reject',$page);
                            
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
			else{
				if($old_noti_detail['Notification']['is_receiver_accepted']=='1'){
					echo '@@0@@';
					exit;
				}
				else if($old_noti_detail['Notification']['is_receiver_accepted']=='2'){
					echo '##0##';
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
	
	

	function accept_subgroup_request()
	{
   
		$this->layout = ""; 
		$notification_id = $_REQUEST['notification_id'];
		$defaultconfig = $this->getDefaultConfig();      
		$base_url = $defaultconfig['base_url'];
		   
		$condition = "Notification.id = '".$notification_id."' AND Notification.type = 'SG' AND Notification.status = '1'";
		//$noti_detail = $this->Notification->find('first',array('conditions'=>$condition1));  
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
							$this->data['GroupUser']['user_id'] =  $admins['Notification']['receiver_id'];		// All the other Parent Group Owners have become member . Bcoz, one parent Grou Owner already accepted the sub Group Request.
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
					  else{
						  echo '0';
						   exit;
					  }
				  }
			  }
			else{
				echo '0';
				exit;
			} 
		} 
		else{
			if($old_noti_detail['Notification']['is_receiver_accepted']=='1'){
				echo '@@0@@';
				exit;
			}
			else if($old_noti_detail['Notification']['is_receiver_accepted']=='2'){
				echo '##0##';
				exit;
			}	
		} 
	 }


    function reject_subgroup_request(){
       
            $this->layout = ""; 
            $notification_id = $_REQUEST['notification_id'];
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            

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
			else{
				if($old_noti_detail['Notification']['is_receiver_accepted']=='1'){
					echo '@@0@@';
					exit;
				}
				else if($old_noti_detail['Notification']['is_receiver_accepted']=='2'){
					echo '##0##';
					exit;
				}	
			}
    }


    function remove_subgroup_notification()
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
		
		$condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";
		$group_details = $this->Group->find('first',array('conditions'=>$condition2));
        $this->set('group_owners',$group_details['Group']['group_owners']);
		
		$arr_group_owners= explode(',', $group_details['Group']['group_owners']);
		if(in_array($user_id, $arr_group_owners)){
			$is_owner=1;
		}
		else{
			$is_owner=0;
		}
		$this->set('is_owner',$is_owner);		  
		if($group_details['Group']['group_type'] == 'B'){			//B for Business group

		  $group_members = array(); 

		  $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";		//Condition to check Group Owner
		  $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));

		  if($count_chk_owner > 0){

			$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."'";	// Fetches the all members (public+private) of the Group
			$all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));

			foreach($all_group_members as $groups)
			{
				$list['id'] = $groups['GroupUser']['id'];
				$list['user_id'] = $groups['GroupUser']['user_id'];
				$list['user_type'] = $groups['GroupUser']['user_type'];
				$list['member_mode'] = $groups['GroupUser']['member_mode'];
				$list['group_id'] = $groups['GroupUser']['group_id'];
				$list['group_title'] = $group_details['Group']['group_title'];
                $list['group_type'] = $group_details['Group']['group_type'];
                $list['can_post_topic'] = $groups['GroupUser']['can_post_topic'];

				$condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
				$check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
			   
				$is_friend = '0';

				if($check_friend > 0 )
				{
				  $is_friend = '1'; 	// The Group member is my friend.
				}                   
				else {			//If Group Member is not my friend
				
				   $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";		//Check whether logged in user(owner) has sent the request already or not
				   $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));

				  if(($count_request_sent > 0)){
					$is_friend = '2'; //Request sent by logged in user(owner) ,not accepted or rejected by other user
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
				$this->set('created_by',$group_details['Group']['created_by']); 
		}
		else 
		{
		  $group_members = array(); 
		  $is_auth = '0';
		  $this->set('is_auth',$is_auth);
		  $this->set('group_members',$group_members);
		  $this->set('session_user_id',$session_user_id); 
		  $this->set('created_by',$group_details['Group']['created_by']);
		}
	}
		else if($group_details['Group']['group_type'] == 'F'){		//F for the Free Group

		  $group_members = array(); 

		  $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";		//Condition to check Group Owner
		  $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));

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
				$list['group_id'] = $groups['GroupUser']['group_id'];
				$list['group_title'] = $group_details['Group']['group_title'];
                $list['group_type'] = $group_details['Group']['group_type'];
                $list['can_post_topic'] = $groups['GroupUser']['can_post_topic'];

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
						$is_friend = '2'; //Request sent by logged in user (owner) ,not accepted or rejected by other user
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
            $this->set('created_by',$group_details['Group']['created_by']); 


		}else{			// If the logged in user is the group member of Free Group 
		  
				$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."' AND GroupUser.member_mode = 'public'";

				$all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));
	
				foreach($all_group_members as $groups)
				{
					$list['id'] = $groups['GroupUser']['id'];
					$list['user_id'] = $groups['GroupUser']['user_id'];
					$list['user_type'] = $groups['GroupUser']['user_type'];
					$list['member_mode'] = $groups['GroupUser']['member_mode'];
					$list['group_id'] = $groups['GroupUser']['group_id'];
                    $list['group_type'] = $group_details['Group']['group_type'];
					$list['group_title'] = $group_details['Group']['group_title'];
                    $list['can_post_topic'] = $groups['GroupUser']['can_post_topic'];

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
						 	if(($detail_notification > 0)){
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
                $this->set('created_by',$group_details['Group']['created_by']); 
		
		}

		}  
		else if($group_details['Group']['group_type'] == 'PO'){			//PO for Public Organization group

		  $group_members = array(); 

		  $con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";		//Condition to check Group Owner
		  $count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));

		  if($count_chk_owner > 0){

			$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."'";	// Fetches the all members (public+private) of the Group
			$all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));

			foreach($all_group_members as $groups)
			{
				$list['id'] = $groups['GroupUser']['id'];
				$list['user_id'] = $groups['GroupUser']['user_id'];
				$list['user_type'] = $groups['GroupUser']['user_type'];
				$list['member_mode'] = $groups['GroupUser']['member_mode'];
				$list['group_id'] = $groups['GroupUser']['group_id'];
				$list['group_title'] = $group_details['Group']['group_title'];
                $list['group_type'] = $group_details['Group']['group_type'];
                $list['can_post_topic'] = $groups['GroupUser']['can_post_topic'];

				$condition8 =  "Friendlist.is_blocked= '0' AND  ((Friendlist.sender_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.receiver_id = '".$user_id."') OR (Friendlist.receiver_id = '".$groups['GroupUser']['user_id']."' AND Friendlist.sender_id = '".$user_id."'))";  // fetch the friends who belongs to the group
				$check_friend = $this->Friendlist->find('count',array('conditions'=>$condition8));
			   
				$is_friend = '0';

				if($check_friend > 0 )
				{
				  $is_friend = '1'; 	// The Group member is my friend.
				}                   
				else {			//If Group Member is not my friend
				
				   $con_chk_request_sent = "Notification.status = '1' AND  Notification.type = 'F' AND Notification.sender_id = '".$user_id."' AND Notification.receiver_id = '".$groups['GroupUser']['user_id']."' AND Notification.is_receiver_accepted = '0' AND Notification.is_reversed_notification = '0' ";		//Check whether logged in user(owner) has sent the request already or not
				   $count_request_sent = $this->Notification->find('count',array('conditions'=>$con_chk_request_sent));

				  if(($count_request_sent > 0)){
					$is_friend = '2'; //Request sent by logged in user(owner) ,not accepted or rejected by other user
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
				$this->set('created_by',$group_details['Group']['created_by']); 
		}
		else 
		{
		  $group_members = array(); 
		  $is_auth = '0';
		  $this->set('is_auth',$is_auth);
		  $this->set('group_members',$group_members);
		  $this->set('session_user_id',$session_user_id); 
		  $this->set('created_by',$group_details['Group']['created_by']);
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

     
	  
	/*function get_suggestion_list_friends(){
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
	 }*/
	 
	 
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
		
		if(count($search_friend_list) > 0)
		{
		
			############   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet starts  ###############
			foreach($search_friend_list as $key => $val){
				
				$con_is_member = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$val['Friendlist']['receiver_id']."' AND GroupUser.status = '1'";
				$count_is_member = $this->GroupUser->find('count',array('conditions'=>$con_is_member));
				if($count_is_member == 0)			// Remove the friend who does not belong to selected group
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
			
	}*/
	
	function get_suggestion_list_users(){
	  
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$search_text = $_REQUEST['search_text']; 
			$group_id = $_REQUEST['group_id']; 
			$str_selected_users = $_REQUEST['str_selected_users'];
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
					if($count_is_member == 0){		// Those who are not the friend
					
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
				$this->set('arr_search_result',$arr_not_group_users);	
			}
			else{			//when search text does not match or all users belong to the group already
				$this->set('arr_search_result',$arr_not_group_users);	
			}
			
	}
	

    function notification_email($notification_id)
        {
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

                     $condition = "EmailTemplate.id = '7'";//  invited you to join Business  group
                    $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                } else if (($notification_detail['Notification']['sender_type']== 'GM')&& ($notification_detail['Notification']['receiver_type']== 'NGM')) { 

                    $condition = "EmailTemplate.id = '8'";// recommended you to join Free group
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
			 
			 
			  if($notification_detail['Notification']['type'] == 'SG')
              {

               

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
                        $group_type = 'FREE';
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
                                       
                                     
                    $user_message = stripslashes($user_body);
                    
           
                   $string = '';
                   $filepath = '';
                   $filename = '';
                   $sendCopyTo = '';
           
           
                    $sendmail = sendmail($sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                
                     
                              
         

    }

	  
	/*function submit_invitation(){
	  
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
		
			$group_id = $_REQUEST['group_id'];	
			$mode = $_REQUEST['mode'];
			$sender_type = $_REQUEST['sender_type'];
			$group_type = $_REQUEST['group_type'];
			
			if(isset($mode) && $mode =='invite_friends'){
				
				$str_users= $_REQUEST['str_users'];	
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
                    if($this->Notification->save($this->data['Notification']))
					{
					  $notification_id = $this->Notification->getLastInsertId();  
					  $this->notification_email($notification_id);
					  
					  $page="Group invitation";
					  $this->send_notification_group_invitation($user_id, $arr_users[$i], $group_id, $mode,$page);
					}
				}
				echo "1";
				exit;
			}	
			else if(isset($mode) && $mode=='invite_users'){
				
				$str_users= $_REQUEST['str_users'];	
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
				echo "1";
				exit;
			}		
			else if(isset($mode) && $mode=='invite_emails'){
			
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
							array_push($arr_site_users,$arr_emails[$i]);		//Already site users
						}
						else{
							$cond_notification_check = "Notification.sender_id = '".$user_id."' AND Notification.group_id = '".$group_id."' AND Notification.receiver_id = '".$email_check['User']['id']."' AND Notification.type = 'G'";   // Check notification already exists for that group from the same owner 
							$notification_arr = $this->Notification->find('first',array('conditions'=>$cond_notification_check));	
							
							if(!empty($notification_arr)){
								array_push($arr_notified_users,$arr_emails[$i]);		//Already site users

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
								
								if($this->Notification->save($this->data['Notification'])){		//Send the notification to the dummy user
									
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
							$this->User->save($this->data['User']);			// Creates a dummy User
							$last_insert_id = $this->User->getLastInsertId();
							
							####################   User creation ends    ########################
							
							####################	Sending Notification starts     ####################
							
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
							
							####################	Sending Notification ends        #####################
					 }
					
				}
				
				if(!empty($arr_site_users) || !empty($arr_notified_users)){
					
					if(!empty($arr_site_users)){
						$str_site_users= implode(',', $arr_site_users);
						$str_err= "Existing active users: ".$str_site_users;
					}
					if(!empty($arr_notified_users)){
						$str_notified_users= implode(',', $arr_notified_users);
						$str_err.= "<br>"."Existing notified users: ".$str_notified_users;
					}
					echo $str_err;
				}
				else{
					echo "1";	
				}
				
				exit;
			}
	}*/
	
	
	function submit_invitation(){
	  
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
		
			$group_id = $_REQUEST['group_id'];	
			$mode = $_REQUEST['mode'];
			$sender_type = $_REQUEST['sender_type'];
			$group_type = $_REQUEST['group_type'];
			
			if(isset($mode) && $mode =='invite_friends'){
				
				$str_users= $_REQUEST['str_users'];	
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
                    if($this->Notification->save($this->data['Notification']))
					{
					  $notification_id = $this->Notification->getLastInsertId();  
					  $this->notification_email($notification_id);
					  
					  $page="Group invitation";
					  $this->send_notification_group_invitation($user_id, $arr_users[$i], $group_id, $mode,$page);
					}
				}
				echo "1";
				exit;
			}	
			else if(isset($mode) && $mode=='invite_users'){
				
				$str_users= $_REQUEST['str_users'];	
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
				echo "1";
				exit;
			}		
			else if(isset($mode) && $mode=='invite_emails'){
			
				$sitesettings = $this->getSiteSettings();
				$str_users= $_REQUEST['str_users'];	
				
				$arr_emails= explode(',', $str_users);
				
				$arr_site_users = array();
								
				for($i=0; $i<count($arr_emails); $i++){
				
					 $cond_email_check = "User.email = '".$arr_emails[$i]."'";   // Check whether the email exists
					 $email_check = $this->User->find('first',array('conditions'=>$cond_email_check));
					 
					 if(!empty($email_check)){
					 		
						if($email_check['User']['status']=='1'){
							array_push($arr_site_users,$arr_emails[$i]);		//Already site users
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
								
								if($this->Notification->save($this->data['Notification'])){		//Send the notification to the dummy user
									
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
							$this->User->save($this->data['User']);			// Creates a dummy User
							$last_insert_id = $this->User->getLastInsertId();
							
							####################   User creation ends    ########################
							
							####################	Sending Notification starts     ####################
							
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
							
							####################	Sending Notification ends        #####################
					 }
					
				}
				
				if(!empty($arr_site_users)){

					$str_site_users= implode(',', $arr_site_users);
					$str_err= "Existing active users: ".$str_site_users;
					
					echo $str_err;
				}
				else{
					echo "1";	
				}
				
				exit;
			}
	}
	  
	 
	function recommend_list($group_id){
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
	
	
	/*function get_recommended_list_friends(){
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
	 }*/
	 
	 function get_recommended_list_friends(){
		$this->_checkSessionUser();
		$user_id = $this->Session->read('userData.User.id');
		$search_text = $_REQUEST['search_text']; 
		$group_id = $_REQUEST['group_id']; 
		$str_selected_users = $_REQUEST['str_selected_users']; 
		
		$condition_friend_list= "Friendlist.sender_id = '".$user_id."' AND Friendlist.is_blocked = '0' AND Friendlist.friend_name LIKE '%".$search_text."%'";
		$this->Friendlist->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'receiver_id','fields'=>'User.id,User.fname,User.lname,User.image'))),false);
		$search_friend_list = $this->Friendlist->find('all',array('conditions'=>$condition_friend_list));  
		$arr_not_group_friends=array();
		
		if(count($search_friend_list) > 0)
		{
		
			############   Fetch the users who are in friendlist, but not belong to the Group and have not being notified yet starts  ###############
			foreach($search_friend_list as $key => $val){
				
				$con_is_member = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_id = '".$val['Friendlist']['receiver_id']."' AND GroupUser.status = '1'";
				$count_is_member = $this->GroupUser->find('count',array('conditions'=>$con_is_member));
				if($count_is_member == 0)			// Remove the friend who does not belong to selected group
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
			
		}
		else{
				$arr_search_result= array();
		}
		
		$this->set('arr_search_result',$arr_search_result);		
	 }
	 
	/*function get_recommended_list_users(){
	  
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
			
	}*/
	
	function get_recommended_list_users(){
	  
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$search_text = $_REQUEST['search_text']; 
			$group_id = $_REQUEST['group_id']; 
			$str_selected_users = $_REQUEST['str_selected_users'];
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
					if($count_is_member == 0){		// Those who are not the friend
					
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
				$this->set('arr_search_result',$arr_not_group_users);	
			}
			else{			//when search text does not match or all users belong to the group already
				$this->set('arr_search_result',$arr_not_group_users);	
			}
			
	}
	 
	 
	function submit_recommendation(){
	  
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
		
			$group_id= $_REQUEST['group_id'];	
			$mode= $_REQUEST['mode'];
			$sender_type= $_REQUEST['sender_type'];
			$group_type= $_REQUEST['group_type'];
			
			if(isset($mode) && $mode=='recommend_friends'){
				
				$str_users= $_REQUEST['str_users'];	
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
                    if($this->Notification->save($this->data['Notification']))
                        {
                          $notification_id = $this->Notification->getLastInsertId();  
                          $this->notification_email($notification_id);
						  
						   $page="Group recommendation";
						   $this->send_notification_group_recommendation($user_id, $arr_users[$i], $group_id, $mode,$page);
                        }
				}
				echo "1";
				exit;
			}	
			else if(isset($mode) && $mode=='recommend_users'){
				
				$str_users= $_REQUEST['str_users'];	
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
				echo "1";
				exit;
			}		
			else if(isset($mode) && $mode=='recommend_emails'){
			
				$sitesettings = $this->getSiteSettings();
				$str_users= $_REQUEST['str_users'];	
				
				$arr_emails= explode(',', $str_users);
				
				$arr_site_users = array();
				
				for($i=0; $i<count($arr_emails); $i++){
				
					 $cond_email_check = "User.email = '".$arr_emails[$i]."'";   // Check whether the email exists
					 $email_check = $this->User->find('first',array('conditions'=>$cond_email_check));
					 
					 if(!empty($email_check)){
					 		
						if($email_check['User']['status']=='1'){
							array_push($arr_site_users,$arr_emails[$i]);		//Already site users
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
								
								if($this->Notification->save($this->data['Notification'])){		//Send the notification to the dummy user
							
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
							$this->User->save($this->data['User']);		// Creates a dummy User
							$last_insert_id = $this->User->getLastInsertId();
							
							####################   User creation ends    ########################
							
							####################	Sending Notification starts     ####################
							
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
							
							if($this->Notification->save($this->data['Notification'])){		//Send the notification to the dummy user
							
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
							
							####################	Sending Notification ends        #####################
					 }
					
				}

				
				if(!empty($arr_site_users)){
					
					$str_site_users= implode(',', $arr_site_users);
					$str_err= "Existing active users: ".$str_site_users;
					
					echo $str_err;
				}
				else{
					echo "1";	
				}
				
				exit;
			}
	}
	
	function edit_group($group_id){
		$this->layout = "home_inner";
		$this->set('pagetitle', 'Welcome to Grouper');
		$this->_checkSessionUser();
		$user_id = $this->Session->read('userData.User.id');


			if (!empty($this->params['form']) && isset($this->params['form'])) {
			
				//echo 'Alok-------------'.$this->params['form']['alok'];exit;
                // pr($this->params['form']);exit();
			  	if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= ''){

				
				$image_name = $this->params['form']['upload_image']['name'];
				
				$extension = end(explode('.',$image_name));               
				$upload_image = time().accessCode(5).'.'.$extension;          
				$upload_target_original = 'group_images/'.$upload_image;
							
				$imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
				list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
				
				
				if($type == 1 || $type == 2 ){
				 
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
							 $is_upload = 1;
							 $this->data['Group']['icon'] = $upload_image;    
																						
						  }     
						else{
							
						$is_upload = 0;
						$this->Session->setFlash(__d("statictext", "Image upload failed", true));
						$_SESSION['meesage_type'] = '0';
						
						 $this->redirect("/group/group_detail/".$group_id);
					
						  }
					}
					else{        
					 	$is_upload = 0;
						$this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
						$_SESSION['meesage_type'] = '0';
						$this->redirect("/group/group_detail/".$group_id);
					}
			   }
			   	else{
					$is_upload = 0;
				    $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
					$_SESSION['meesage_type'] = '0';
					$this->redirect("/group/group_detail/".$group_id); 
				 }
				}
	  			else{
					$is_upload = 1;
					$this->data['Group']['icon'] = $this->params['form']['old_icon'];
	  			}
				
			  	if($is_upload == 1){                
										 

						$this->data['Group']['group_title'] = addslashes($this->params['form']['group_name']);
						$this->data['Group']['group_desc'] = addslashes($this->params['form']['g_desc']);
						//$this->data['Group']['group_purpose'] = addslashes($this->params['form']['g_purpose']);
						$this->data['Group']['group_url'] = addslashes($this->params['form']['g_url']);
                        $this->data['Group']['group_type'] = $this->params['form']['new_group_type'];
                        if($this->params['form']['new_group_type'] == 'B' || $this->params['form']['new_group_type'] == 'PO'){
                            $this->data['Group']['category_id'] = $this->params['form']['alok'];
                         }
                         else 
                         {
                            $this->data['Group']['category_id'] = '0';
                         }
						
		
						$this->data['Group']['id'] = $group_id;
						if($this->Group->save($this->data['Group'])) {
                            if($this->params['form']['new_group_type'] == 'B' || $this->params['form']['new_group_type'] == 'PO'){

                                if($this->params['form']['new_group_type'] == 'B'){
									$this->Event->query("UPDATE `events` SET `group_type` = '".$this->params['form']['new_group_type']."' , `type` = 'public', `category_id` = '".$this->params['form']['alok']."' WHERE `group_id` = '".$group_id."'");
								}
								else{
									$this->Event->query("UPDATE `events` SET group_type = '".$this->params['form']['new_group_type']."' , category_id = '".$this->params['form']['alok']."' WHERE `group_id` = '".$group_id."'");
								}
                           }
                           else
                           {
                                 $this->Event->query("UPDATE `events` SET group_type = '".$this->params['form']['new_group_type']."' , category_id = '0' WHERE `group_id` = '".$group_id."'");
                           }

                              $this->Notification->query("UPDATE `notifications` SET group_type = '".$this->params['form']['new_group_type']."' WHERE `group_id` = '".$group_id."'");
                    		
							$this->Session->setFlash(__d("statictext", "Group Updated Successfully!!.", true));
							$_SESSION['meesage_type'] ='1';
							$this->redirect("/group/group_detail/".$group_id);
				  		}
			  	}
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
			
			
			$condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";
			$group_details = $this->Group->find('first',array('conditions'=>$condition2));
			$parent_grp_id = $group_details['Group']['parent_id'];
			
			$group_members = array(); 
	
			$con_chk_owner = "GroupUser.status= '1' AND  GroupUser.user_type = 'O' AND  GroupUser.user_id = '".$user_id."' AND  GroupUser.group_id = '".$group_id."'";//Condition to check Group Owner
			$count_chk_owner = $this->GroupUser->find('count',array('conditions'=>$con_chk_owner ));
			
			
			
			if($parent_grp_id!='0'){			//Determines the logged in user is the owner of the Parent Group/ Not
				
				$condition_parent_grp = "Group.status = '1' AND (find_in_set('".$user_id."',Group.`group_owners`)) AND Group.id = '".$parent_grp_id."'";
				$is_parent_grp_owner = $this->Group->find('count',array('conditions'=>$condition_parent_grp));  	
			}
			else{
				$is_parent_grp_owner=0;
			}
		//echo '----'.$is_parent_grp_owner;exit;
		
			############# If user is the member , check whether he is
	
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
					
			 		$all_group_members = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));

					foreach($all_group_members as $groups){
							
							$list['id'] = $groups['GroupUser']['id'];
							$list['user_id'] = $groups['GroupUser']['user_id'];
							$list['user_type'] = $groups['GroupUser']['user_type'];
							$list['member_mode'] = $groups['GroupUser']['member_mode'];
			
							$con_user = "User.status= '1' AND  User.id = '".$groups['GroupUser']['user_id']."'";
							$user_detail = $this->User->find('first',array('conditions'=>$con_user));
							$list['user_name'] = ucfirst(stripslashes($user_detail['User']['fname']).' '.stripslashes($user_detail['User']['lname'])) ;
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
				  

				    $this->set('group_id',$group_id);
					$this->set('group_type',$group_details['Group']['group_type']);
					$this->set('group_title',$group_details['Group']['group_title']);
					$this->set('group_members',$group_members);
				    $this->set('session_user_id',$session_user_id); 
			}
			else{
			  $group_members = array(); 
		  
			  $this->set('group_members',$group_members);
			  $this->set('session_user_id',$session_user_id); 
			}
	
		 
	  }
	  
	  
	function push_message_to_owners($group_id){
			//Configure::write('debug',3);
			$this->layout = ""; 
			$response = array();
			$defaultconfig = $this->getDefaultConfig();      
			$base_url = $defaultconfig['base_url'];
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
			$notification_id = 0;
			
			$condition2 = "Group.status= '1' AND  Group.id = '".$group_id."'";
			$group_details = $this->Group->find('first',array('conditions'=>$condition2));
			
			$group_owners = array(); 
	
			$condition = "GroupUser.status= '1'  AND  GroupUser.group_id = '".$group_id."' AND  GroupUser.user_type = 'O'";  // Fetches the all owners of the Group
					
			$all_group_owners = $this->GroupUser->find('all',array('conditions'=>$condition,'order' => 'GroupUser.id DESC'));
			
			if(count($all_group_owners)>0){
			foreach($all_group_owners as $owners){
					
					$list['id'] = $owners['GroupUser']['id'];
					$list['user_id'] = $owners['GroupUser']['user_id'];
	
					$con_owner = "User.status= '1' AND  User.id = '".$owners['GroupUser']['user_id']."'";
					$owner_detail = $this->User->find('first',array('conditions'=>$con_owner));
					$list['user_name'] = ucfirst(stripslashes($owner_detail['User']['fname']).' '.stripslashes($owner_detail['User']['lname'])) ;
					if(!empty($owner_detail['User']['image']))
					{
					  $list['image_url'] = $owner_detail['User']['image'];  
					}
					else
					{
					  $list['image_url'] = '';
					}

					array_push($group_owners,$list);

			}
				  

				    $this->set('group_id',$group_id);
					$this->set('group_type',$group_details['Group']['group_type']);
					$this->set('group_title',$group_details['Group']['group_title']);
					$this->set('group_owners',$group_owners);
				    $this->set('session_user_id',$session_user_id); 
			}
			else{
			  $group_owners = array(); 
		  
			  $this->set('group_owners',$group_owners);
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
			$group_type    = $this->params['form']['group_type'];
			$group_title    = $this->params['form']['group_title'];
            $message = $this->params['form']['message'];
            $member_list    = $this->params['form']['push'];

            if(!empty($member_list)){
				foreach($member_list as $members){
					
						$this->data['Notification']['sender_id'] = $sender_id;
						$this->data['Notification']['type'] = 'P';
						$this->data['Notification']['group_id'] = $group_id;
						$this->data['Notification']['message'] = $message;                     
						$this->data['Notification']['receiver_id'] = $members;
						
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
	
						$this->data['Notification']['sender_type'] = 'GO';	
						$this->data['Notification']['is_read'] = 0;
						$this->data['Notification']['is_receiver_accepted'] = 2;
						$this->data['Notification']['is_reversed_notification'] = 0;
						$this->data['Notification']['status'] = 1;
						$this->Notification->create();
						
                        if($this->Notification->save($this->data['Notification'])){

                              // send notification email for friend request
                                 $notification_id = $this->Notification->getLastInsertId();  
                                 $this->notification_email($notification_id);
                              
							  	 if($member_detail['GroupUser']['user_type'] == 'O' || ($member_detail['GroupUser']['user_type'] == 'M' && $member_detail['GroupUser']['is_notification_stop'] == '0')){
										$page="Push message from ".$group_title;
								 		$this->send_notification($sender_id, $members, $group_id, $message, $page,'o_m'); 
								 }
                             	 
                         }
					  
				}
				
				 
				 $this->Session->setFlash(__d("statictext", "Message sent successfully", true));
				 $_SESSION['meesage_type'] = '1';
				 $this->redirect("/group/group_detail/".$group_id);

        	}
        	else{
				 $this->Session->setFlash(__d("statictext", "No member available into Group", true));
				 $_SESSION['meesage_type'] = '0';
				 $this->redirect("/group/group_detail/".$group_id);
        	}   
     }
	 
	 
	function push_message_owner_sent(){
           
            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');
            
            //pr($this->params['form']);exit();
            $sender_id    = $user_id;
            $group_id    = $this->params['form']['group_id'];
			$group_type    = $this->params['form']['group_type'];
			$group_title    = $this->params['form']['group_title'];
            $message = $this->params['form']['message'];
            $owner_list    = $this->params['form']['push'];

            if(!empty($owner_list)){
				foreach($owner_list as $owners){
					
						$this->data['Notification']['sender_id'] = $sender_id;
						$this->data['Notification']['type'] = 'P';
						$this->data['Notification']['group_id'] = $group_id;
						$this->data['Notification']['message'] = $message;                     
						$this->data['Notification']['receiver_id'] = $owners;
						
						/*$con_owner_detail = "User.id = '".$owners."'";
						$owner_detail = $this->User->find('first',array('conditions'=>$con_owner_detail));
						$owner_name= stripslashes($owner_detail['User']['fname']).' '.stripslashes($owner_detail['User']['lname']);*/
	
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
				
				 
				 $this->Session->setFlash(__d("statictext", "Message sent successfully", true));
				 $_SESSION['meesage_type'] = '1';
				 $this->redirect("/group/group_detail/".$group_id);

        	}
        	else{
				 $this->Session->setFlash(__d("statictext", "No owner available into Group", true));
				 $_SESSION['meesage_type'] = '0';
				 $this->redirect("/group/group_detail/".$group_id);
        	}   
     }
	 
	
	
	 function invite_to_friend(){
      
            $this->layout = ""; 
            $sitesettings = $this->getSiteSettings();
            $this->_checkSessionUser();
            $user_id = $this->Session->read('userData.User.id');
            
    }
	
	function submit_email_invitation(){
			
			$this->_checkSessionUser();
			$user_id = $this->Session->read('userData.User.id');
			$session_user_id = $this->Session->read('userData.User.id');
            $sitesettings = $this->getSiteSettings();
			
			if(isset($_REQUEST['mode']) && $_REQUEST['mode']=='invite_emails'){
				
				$str_users= $_REQUEST['str_users']; 
				$arr_emails= explode(',', $str_users);
					
				$reject_email_list = array();
				$already_request_sent = array();
					
				for($i=0; $i<count($arr_emails); $i++){
			
				 $cond_email_check = "User.email = '".$arr_emails[$i]."'";   // Check whether the email exists
				 $email_check = $this->User->find('first',array('conditions'=>$cond_email_check));
				
				 if(!empty($email_check))
				 {
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
					echo "These are already your friends ".$str_reject_emails;
					
				}
				else{
					echo "1"; 
					 
				}
				exit;
			}
	}
	
	function make_owner(){
       
		$this->layout = "";
		$this->_checkSessionUser();
		$user_id = $this->Session->read('userData.User.id');

		
		$sender_id = $_REQUEST['sender_id'];
		$receiver_id = $_REQUEST['receiver_id'];
		$group_id = $_REQUEST['group_id'];
		
		$con_group_dtls = "Group.id = '".$group_id."'";
       	$group_dtls = $this->Group->find("first",array('conditions'=>$con_group_dtls,'fields'=>array('Group.id','Group.group_title','Group.group_owners','Group.group_type'))); 
		$arr_group_owners= explode(',', $group_dtls['Group']['group_owners']); 
		
		if(!in_array($receiver_id, $arr_group_owners)){
			array_push($arr_group_owners, $receiver_id);
			
			$str_group_owners= implode(',', $arr_group_owners);
		
			$this->data['Group']['id'] = $group_id; 
			$this->data['Group']['group_owners'] = $str_group_owners;  
			if($this->Group->save($this->data['Group'])){
			
					$this->GroupUser->query("UPDATE `group_users` SET `user_type` ='O', `can_post_topic` = '1' WHERE `group_id` = '".$group_id."' AND `user_id` = '".$receiver_id."'");
					
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
					
					echo '1';
					exit;
			}
		}
  		
		
    }
	
	function remove_owner(){
       
		$this->layout = "";
		$this->_checkSessionUser();
		$user_id = $this->Session->read('userData.User.id');

		
		$sender_id = $_REQUEST['sender_id'];
		$receiver_id = $_REQUEST['receiver_id'];
		$group_id = $_REQUEST['group_id'];

		
		$con_group_dtls = "Group.id = '".$group_id."'";
       	$group_dtls = $this->Group->find("first",array('conditions'=>$con_group_dtls,'fields'=>array('Group.id','Group.group_owners','Group.group_title','Group.group_type'))); 
		$arr_group_owners= explode(',', $group_dtls['Group']['group_owners']); 
        
        $group_type = $group_dtls['Group']['group_type'];
		
		if(in_array($receiver_id, $arr_group_owners)){
			unset($arr_group_owners[array_search($receiver_id,$arr_group_owners)]);
			
			$str_group_owners= implode(',', $arr_group_owners);
		
			$this->data['Group']['id'] = $group_id; 
			$this->data['Group']['group_owners'] = $str_group_owners;  
			if($this->Group->save($this->data['Group'])){
					
					if($group_type == 'B')
                    {
                        $this->GroupUser->query("UPDATE `group_users` SET `user_type` ='M', `can_post_topic` ='0' WHERE `group_id` = '".$group_id."' AND `user_id` = '".$receiver_id."'");
                    }
                    else
                    {
                        $this->GroupUser->query("UPDATE `group_users` SET `user_type` ='M' WHERE `group_id` = '".$group_id."' AND `user_id` = '".$receiver_id."'");
                    }
					
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
					
					echo '1';
					exit;
			}
		}
  		      
		
    }

    function remove_member(){
       
        $this->layout = "";
        $this->_checkSessionUser();
        $user_id = $this->Session->read('userData.User.id');

        
        $sender_id = $_REQUEST['sender_id'];
        $receiver_id = $_REQUEST['receiver_id'];
        $group_id = $_REQUEST['group_id'];

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
                    echo '1';
                    exit;

                    }
                }
            }
        }
        else
        {
            echo '0';
            exit;
        }
  
        
    }



    function update_message_posting_status()
    {
        $group_user_id = $_REQUEST['group_user_id'];
        $group_id = $_REQUEST['group_id'];
        $status = $_REQUEST['status'];

        if($status == '1')
        {
            $posting_status = '0';
        }
        else
        {
            $posting_status = '1';
        }

        $this->data['GroupUser']['id'] = $group_user_id;
        $this->data['GroupUser']['can_post_topic'] = $posting_status;
        $this->GroupUser->save($this->data['GroupUser']);

        echo 1;
        exit;
    }
	 
	 
	function getLastQuery() {
		  $dbo = ConnectionManager::getDataSource('default');
		  $logs = $dbo->getLog();
		  $lastLog = end($logs['log']);

		  return $lastLog['query'];
	  }

      

      function event_details($event_id=NULL)
      {
    
      // $this->_checkSessionUser();
    
          $this->layout = "";
          $this->_checkSessionUser();
          $user_id = $this->Session->read('userData.User.id');
          $this->set('pageTitle', 'Group Member Count');
          
            $condition4 = "Event.id = '".$event_id."'";
            $event_details = $this->Event->find('first',array('conditions'=>$condition4));
                
        
           return $event_details;
           
    
       }

       function delete_doc() 
       {
      
  
       $docID = $_REQUEST['docID'];
      
        $conditions = "GroupDoc.id = '".$docID."'"; 
        $GrpDocdetail = $this->GroupDoc->find('first',array('conditions' => $conditions)); 
                  
             $folder='gallery/doc';
            
             $this->removeFile($GrpDocdetail['GroupDoc']['docname'],$folder);
         
             $this->GroupDoc->id = $docID;
             $this->GroupDoc->delete();
             
             
              echo 'ok';
              exit();
         }

       function delete_image(){
      
  
       $imageID = $_REQUEST['imageID'];
      
        $conditions = "GroupImage.id = '".$imageID."'"; 
        $GrpDocdetail = $this->GroupImage->find('first',array('conditions' => $conditions)); 
                  
             $folder='gallery/';
             $folder_medium= 'gallery/medium/';
             $folder_thumb= 'gallery/thumb/';
             $folder_web= 'gallery/web/';
            
             $this->removeFile($GrpDocdetail['GroupImage']['image'],$folder);
             $this->removeFile($GrpDocdetail['GroupImage']['image'],$folder_medium);
             $this->removeFile($GrpDocdetail['GroupImage']['image'],$folder_thumb);
             $this->removeFile($GrpDocdetail['GroupImage']['image'],$folder_web);
             
             $this->GroupImage->id = $imageID;
             $this->GroupImage->delete();
             
             
              echo 'ok';
              exit();
         }




       function delete_video(){
        
       $videoID = $_REQUEST['videoID'];
      
        $conditions = "Video.id = '".$videoID."'"; 
        $GrpVideodetail = $this->Video->find('first',array('conditions' => $conditions)); 
                  
                          
             $this->Video->id = $videoID;
             $this->Video->delete();
             
             
              echo 'ok';
              exit();
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
				
				
				###################		Get the Notification Counter Starts 	################
					
				$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
		  
				$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
				
				###################		Get the Notification Counter Starts 	################
				//echo '===========>'.$message.'--'.$user_details['User']['device_token'].'--'.$page.'--'.$notification_count;exit;						
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
				
				###################		Get the Notification Counter Starts 	################
					
				$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
		  
				$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
				
				###################		Get the Notification Counter Starts 	################
			   
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
				 
				###################		Get the Notification Counter Starts 	################
					
				$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
		  
				$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
				
				###################		Get the Notification Counter Starts 	################
								   
				$this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);

			}
	   }
	   
		/*---------------------------  Push Notification to send Recommendation to Join Group to Friends / Users ends    -----------------------*/
	
	
		/*---------------------  Push Notification to send Joining Request Via Recommendation to Group Owner starts    -----------------------*/
	 
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
					
				###################		Get the Notification Counter Starts 	################
					
				$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
		  
				$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
				
				###################		Get the Notification Counter Starts 	################
			   
			   /* Sajjad Mistri invited you to join Private group AloveradNHH1oizSpk:APA91bF4vfxVTfX30Mj3lJ1H57dwkeoQhgLsRqu3uPzUTzGCtbNOQBhUsjGT0Zf1Eqpgl0Q1Vo6CXFlStL8yOxGERbg3OjB6R7Xfv2K0Js-VSAIPd2F0PJAOrpxmRV_K-ISR4-yxAmdmGroup Joining Invitation To Friends*/
				$this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);

			   
			}
	   }
		
		
		/*-------------------  Push Notification to send Joining Request Via Recommendation to Group Owner Ends  -----------------------*/
		
		/*-------------------  Reverse Push Notification By clicking on Accept/ Reject button for any Group starts  -----------------------*/

	
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
				
				###################		Get the Notification Counter Starts 	################
					
				$condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
		  
				$notification_count = $this->Notification->find('count',array('conditions'=>$condition));
				
				###################		Get the Notification Counter Starts 	################
				
				$this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
              
			}
	   }
	   
	   /*-------------------  Reverse Push Notification By clicking on Accept/ Reject button for any Group ends  -----------------------*/
	   
	   
	   
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


    function leave_group($group_id){
    
          $this->layout = "";
          $user_id = $this->Session->read('userData.User.id');
          $this->set('pageTitle', 'Group Type');
       

         $user_id = $this->Session->read('userData.User.id');
         

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
                        
                        $page="Quit from ".$group_details['Group']['group_title'];
                        $this->send_notification_leave_from_group($user_id, $arr_group_owners[$i], $group_id, $notification_message, $page);
                          
                     }
            }
         }
         
         $this->Session->setFlash(__d("statictext", "You have left the group successfully!!", true));
         $_SESSION['meesage_type'] ='1';
         $this->redirect("/group/group_detail/".$group_id);
   

    }

    /*---------------------------  Push Notification from Group Details screen to Group Users starts    -----------------------*/
    
    function send_notification_leave_from_group($sender_id=NULL, $receiver_id=NULL, $group_id=NULL, $message=NULL, $page=NULL){
        //$this->_checkSession();
        $condition_receiver = "User.id = '".$receiver_id."'";
        $user_details= $this->User->find('first',array('conditions' => $condition_receiver));
        if($user_details['User']['device_token']!='')
        {
          
            ###################     Get the Notification Counter Starts     ################
                
            $condition = "Notification.receiver_id = '".$receiver_id."' AND Notification.status = '1' AND  ((Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND Notification.type = 'G' AND Notification.sender_type = 'NGM' AND Notification.receiver_type = 'GO') OR (Notification.is_reversed_notification = '0' AND Notification.is_receiver_accepted = '0' ) OR (Notification.is_reversed_notification = '1' AND Notification.is_receiver_accepted != '0') OR (Notification.is_receiver_accepted = '2' AND Notification.is_reversed_notification = '0' AND( (Notification.type = 'E')OR (Notification.type = 'P')))) AND Notification.is_read='0'";
      
            $notification_count = $this->Notification->find('count',array('conditions'=>$condition));
            
            ###################     Get the Notification Counter Starts     ################
                                    
            $this->send_push_notification($message,$user_details['User']['device_token'],$page,$notification_count);
        }
   }
   
   /*---------------------------  Push Notification from Group Details screen to Group Users ends    -----------------------*/


    /*---------------------------  Mute notification for a group start    -----------------------*/

   function is_mute_group($group_id){
    
          $this->layout = "";
          $user_id = $this->Session->read('userData.User.id');
          $this->set('pageTitle', 'Group Type');
       
         $condition_get_group_user = "GroupUser.group_id = '".$group_id."' AND GroupUser.user_type = 'M' AND GroupUser.user_id = '".$user_id."'";
         $group_details= $this->GroupUser->find('first',array('conditions' => $condition_get_group_user));

                    $this->data['GroupUser']['id'] =  $group_details['GroupUser']['id'];
                    $this->data['GroupUser']['is_notification_mute'] = '1';                    

                   if($this->GroupUser->save($this->data['GroupUser']))
                   {
                    $this->Session->setFlash(__d("statictext", "You have muted the notification for this group successfully!!", true));
                         $_SESSION['meesage_type'] ='1';
                         $this->redirect("/group/group_detail/".$group_id);
                   }
                    
            }
    /*---------------------------  Mute notification for a group end    -----------------------*/  

     /*---------------------------  Start notification for a group block start   -----------------------*/

   function is_group_notification_stop($group_id){
    
          $this->layout = "";
          $user_id = $this->Session->read('userData.User.id');
          $this->set('pageTitle', 'Group Type');
       
         $data = $this->GroupUser->query("update  group_users set is_notification_stop ='1' where `group_id`= ".$group_id."  and `user_type`='M'  and `user_id`= ".$user_id."");
         
                   if($data)
                   {
                    $this->Session->setFlash(__d("statictext", "You have stopped notification for this group successfully!!", true));
                         $_SESSION['meesage_type'] ='1';
                         $this->redirect("/group/group_detail/".$group_id);
                   }
                    
            }
    /*---------------------------  Start notification for a group block end    -----------------------*/ 

    /*---------------------------  Start notification for a group block start   -----------------------*/

   function is_group_notification_start($group_id){
    
          $this->layout = "";
          $user_id = $this->Session->read('userData.User.id');
          $this->set('pageTitle', 'Group Type');
       
         $data = $this->GroupUser->query("update  group_users set is_notification_stop ='0' where `group_id`= ".$group_id."  and `user_type`='M'  and `user_id`= ".$user_id."");
         
                   if($data)
                   {
                    $this->Session->setFlash(__d("statictext", "You have started the notification for this group successfully!!", true));
                         $_SESSION['meesage_type'] ='1';
                         $this->redirect("/group/group_detail/".$group_id);
                   }
                    
            }
    /*---------------------------  Start notification for a group block end    -----------------------*/ 
	
	function getUserDetails($user_id)
    {
    $conditions = "User.id = '".$user_id."'";
    $ArGroupMessage = $this->User->find('first',array('conditions' => $conditions, 'fields'=>'User.fname,User.lname,User.image'));
    return $ArGroupMessage;
    }
    
       
}
?>
