<?php
App::import('Vendor','authnet_aim');
class GroupController extends AppController {

    var $name = 'Group';
    var $uses = array('Category','Group','SubscriptionPlan','User','GroupUser');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    

    function group_list($category_id) {
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');
        $this->set('user_id',$user_id);

        $this->set('category_id',$category_id);
        
        $condition = "Category.status= '1'  AND Category.id= '".$category_id."'";
        $category_details = $this->Category->find("first",array('conditions'=>$condition));
       // pr($featured_users);exit();
        $this->set('category_details',$category_details);

        $condition1 = "Group.status= '1'  AND Group.category_id= '".$category_id."'";
        $group_list = $this->Group->find("all",array('conditions'=>$condition1));
        //pr($group_list);exit();
        $this->set('group_list',$group_list);

       
        $plan_list = $this->SubscriptionPlan->find("all");
       // pr($featured_users);exit();
        $this->set('plan_list',$plan_list);

    }

    


     function add_business_category_group() {

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
                    $email = $user_details['User']['email'];
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
                    $creditcard =  $this->params['form']['card_no']; 
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
                    $payment->setParameter("x_ship_to_first_name", $first_name);
                    $payment->setParameter("x_ship_to_last_name", $last_name);
                   // $payment->setParameter("x_description", $product);
                    //$payment->setParameter("x_phone", $business_telephone);
                    /*$payment->setParameter("x_ship_to_first_name", $shipping_firstname);
                    $payment->setParameter("x_ship_to_last_name", $shipping_lastname);
                    $payment->setParameter("x_ship_to_address", $shipping_address);
                    $payment->setParameter("x_ship_to_city", $shipping_city);
                    $payment->setParameter("x_ship_to_state", $shipping_state);
                    $payment->setParameter("x_ship_to_zip", $shipping_zipcode);  */
                    
                    $payment->process();
                 
                    if ($payment->isApproved())
                    {
                        // Get info from Authnet to store in the database
                        $approval_code  = $payment->getAuthCode();
                        $avs_result     = $payment->getAVSResponse();
                        $cvv_result     = $payment->getCVVResponse();
                        $transaction_id = $payment->getTransactionID();
                        //echo $approval_code.'<br>'.$avs_result.'<br>'.$cvv_result.'<br>'.$transaction_id;exit;
                        
                        /*if($this->Session->check('userData')){
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
                        */
                       
                        
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
                       } 
                     
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
                    }
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
                 
                        // OR
                 
                        // Capture a detailed error message. No need to refer to the manual
                        // with this one as it tells you everything the manual does.
                        $full_error_message =  $payment->getResponseMessage();
                        
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
                        }
                 
                        // Report the error to someone who can investigate it
                        // and hopefully fix it
                 
                        // Notify the user of the error and request they contact
                        // us for further assistance
                    }
                }
                catch (AuthnetAIMException $e)
                {
                    echo 'There was an error processing the transaction. Here is the error message: ';
                    echo $e->__toString();
                }
        
        
      }
      
   }
  


  function add_free_group() {
        
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
                             $this->Session->setFlash(__d("statictext", "Group added sucessfully", true));
                             $_SESSION['meesage_type'] = '1';
                            //$this->redirect(array("controller" => "group", "action" => "group_list"));
                            $this->redirect("/category/category_list/"); 
                          }
                       } 
         
        
      }
      
   }

  function group_detail($group_id) {
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');

        $this->set('group_id',$group_id);
        
        $condition = "Group.status= '1'  AND Group.id= '".$group_id."'";
        $group_details = $this->Group->find("first",array('conditions'=>$condition));
       // pr($featured_users);exit();
        $this->set('group_details',$group_details);

    }

}?>
