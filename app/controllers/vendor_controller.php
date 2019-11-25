<?php
class VendorController extends AppController {

  var $name = 'Vendor';
  var $uses = array('Admin','User','State','Job','City','Contact','Product','Banner','ProductCategory','SocialLink','UserReviewComment','EmailTemplate','UserInvite','UserRelation','AllContact','Message','UserSubscription','SubscriptionPlan','UserReview','Article','DanceClassCategory','Class',"DanceClass","SpecialityGroup","Credential","DanceClassCategory");
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array();

  function banner_ad() {
               $this->_checkSessionUser();
      $this->layout = "home_inner";
      
    $sitesettings = $this->getSiteSettings();
  //  pr($sitesettings);

    $user_id = $this->Session->read('USER_USERID');

    $conditions = "User.id =  '".$user_id."' AND User.status = '1' AND User.isdeleted = '0'";

    $all_data = $this->User->find('first', array('conditions' => $conditions));
    
   $banner_standard =  $sitesettings['banner_standard_ads_price'];
   $banner_premium = $sitesettings['banner_premium_ads_price'];
   
   $banner_standard_ads_days =  $sitesettings['banner_standard_ads_days']['value'];
   $banner_premium_ads_days = $sitesettings['banner_premium_ads_days']['value'];
    
      $this->set('pagetitle', 'Sign Up');
      $this->set('banner_standard', $banner_standard);
    $this->set('banner_premium', $banner_premium);
    $this->set('banner_standard_ads_days', $banner_standard_ads_days);
    $this->set('banner_premium_ads_days', $banner_premium_ads_days);

      

         if (!empty($this->params['form']) && isset($this->params['form'])) {
     
				$bannerAdData = array();							
     
					 if($this->params['form']['subscription_pack'] == 'standard')
					 {
					  	$price = $banner_standard['value'];			
						 $bannerAdData['subscription_pack'] = $this->params['form']['subscription_pack'];
						 $bannerAdData['limit_days'] = $banner_standard_ads_days; 
						 $bannerAdData['price'] = $price;
						 
					 }
					 else
					 {
					  	$price = $banner_premium['value'];						
						$bannerAdData['subscription_pack'] = $this->params['form']['subscription_pack'];
						$bannerAdData['limit_days'] = $banner_premium_ads_days; 
						$bannerAdData['price'] = $price;
						
					 }
    
                    
                    $file_type = array('image/jpeg', 'image/jpg', 'image/png');

					$is_error = 0 ;
					$upload_image = '';
					
      				if (($this->params['form']['banner_image']['name'] != '') && (in_array($this->params['form']['banner_image']['type'], $file_type))) 
	  				{

						$max_width = "300";
				
						$max_height = "300";
				
						$width = 0;
				
						$height = 0;
				
						$image_name = $this->params['form']['banner_image']['name'];
				
						$upload_image = time() . '_' . $image_name;
				
				
				
						$upload_target_thumb = 'banner_image/thumb/' . $upload_image;
				
						$upload_target_original = 'banner_image/' . $upload_image;
				
						if(move_uploaded_file($this->params['form']['banner_image']['tmp_name'], $upload_target_original))
						{
				
							$this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);																	
						}
						else
						{
							$is_error = 1 ;
							$err_msg = "Failed to upload image";
						}				
				
					  }
	  				else
					{
						$is_error = 1 ;
						$err_msg = "Please upload jpg, jpeg, png, gif image only";
					}
					
					if($is_error == 0)
					{
						$this->data['Banner']['user_id'] = $user_id;                  
						$this->data['Banner']['subscription_pack'] = $this->params['form']['subscription_pack'];                  
						$this->data['Banner']['status'] = '1';
						$this->data['Banner']['payment_status'] = 'unpaid';
						$this->data['Banner']['image'] = $upload_image;
						
						$this->Banner->save($this->data['Banner']);
						$last_insert_id = $this->Banner->getLastInsertId();						
						$bannerAdData['banner_id'] = $last_insert_id;
						
						$this->Session->write('bannerAdData',$bannerAdData);
						
						$this->set('last_insert_id', $last_insert_id); 
						$this->redirect("/vendor/banner_ad_payment");
			   
							
					}
					
    }
  }

  function banner_ad_payment()
    {
         $this->layout = "login_inner";
         $this->set('pagetitle', 'Banner Ad Payment');
         $user_id = $this->Session->read('USER_USERID');

         $sitesettings = $this->getSiteSettings();  
         
           $privateableKey= $sitesettings['privateableKey']['value'];
            $publishableKey= $sitesettings['publishableKey']['value']; 

            $this->set('privateableKey', $privateableKey);
            $this->set('publishableKey', $publishableKey);

             $pay_amount =  $this->Session->read('bannerAdData.price');
             $this->set('pay_amount',$pay_amount);

       $sitesettings = $this->getSiteSettings();  
         
         
         if (isset($this->params['form']) && !empty($this->params['form'])) {  
           
           {
                    $response = array();
                    $amount =  $this->Session->read('bannerAdData.price');
                    $amount = number_format($amount,2,'','');
                    $token  = $_REQUEST['token_id'];

                     App::import('Vendor', 'lib', array('file' => 'stripe.php'));
                    Stripe::setApiKey($privateableKey);
                  
                    $customer = Stripe_Customer::create(array(
                    //'email' => 'customer@example.com',
                    'email' => trim($_POST['email']),
                    'card'  => $token
                    ));

                    $charge = Stripe_Charge::create(array(
                    'customer' => $customer->id,
                    'amount'   => $amount,
                    'currency' => 'usd'
                    ));
                    
                    $success_note ="";
                    if ($charge->status == 'succeeded'){ 
                
                  /* $subscription_pack = $this->Session->read('bannerAdData.subscription_pack');
                    $this->data['Banner']['subscription_pack'] = $subscription_pack;*/
                    
                    $curr_date=date("Y/m/d");
                    
                    
                     if($this->Session->read('bannerAdData.subscription_pack')=='standard') 
                     {
                      $exp_date=date("Y/m/d", strtotime("+30 days", strtotime(date('Y-m-d'))));
                  

                     }
                     elseif($this->Session->read('bannerAdData.subscription_pack')=='premium')
                     {
                      $exp_date=date("Y/m/d", strtotime("+30 days", strtotime(date('Y-m-d'))));
                    

                     }
                     
                      $this->data['Banner']['subscription_date'] = $curr_date;
                      $this->data['Banner']['expire_date'] = $exp_date;
                      $this->data['Banner']['paid_amount'] = $this->Session->read('bannerAdData.price');
                      $this->data['Banner']['payment_token']=$token;
                      $this->data['Banner']['payment_status']='paid';
                      $this->data['Banner']['id']=$this->Session->read('bannerAdData.banner_id');
                    if ($this->Banner->save($this->data)) 
                    {
                         $conditions = "User.id = '".$user_id."'";

                         $UserDetail = $this->User->find('first', array('conditions' => $conditions));
                              
                        $user_name = $UserDetail['User']['first_name'] . ' ' . $UserDetail['User']['last_name']; 
                        $admin_sender_email = $sitesettings['site_email']['value'];
                        $site_url = $sitesettings['site_url']['value'];
                        $sender_name = $sitesettings['email_sender_name']['value'];

                        $condition = "EmailTemplate.id = '11'";
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                       
                        $user_type = 'Vendor';
                        $type=$this->Session->read('bannerAdData.subscription_pack'); 
                        $date=$exp_date;

                        
                        $amount = $this->Session->read('bannerAdData.price');
                        
                        $to = $UserDetail['User']['email'];

                        $user_name = $user_name;
                        $user_subject = $mailDataRS['EmailTemplate']['subject'];
                        $user_subject = str_replace('[SITE NAME]', 'Look4fitness | Banner Ad', $user_subject);
                                   
                     
                        $user_body = $mailDataRS['EmailTemplate']['content'];
                        $user_body = str_replace('[USER TYPE]', $user_type, $user_body);
                        $user_body = str_replace('[TYPE]', $type, $user_body);
                        $user_body = str_replace('[NAME]', $user_name, $user_body);
                        $user_body = str_replace('[DATE]', $date, $user_body);
                        $user_body = str_replace('[AMOUNT]', $amount, $user_body);
                      
                        $user_message = stripslashes($user_body);
                        $user_header = "From: ".$sender_name."<" . $admin_sender_email . "> \r\n";
                        $user_header .= "MIME-Version: 1.0\r\n";
                        $user_header .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
                        
                       $sendmail = sendmail($to,$user_subject,$user_message,$user_header);
                     // exit;
                     if($sendmail)
                      {
                        $this->Session->setFlash(__d("statictext", "Thank you for Banner Ad", true));
                                $_SESSION['meesage_type'] = '1';
                                $this->redirect("/vendor/banner_ad");
                      }
                      else
                      {
                        $this->Session->setFlash(__d("statictext", "Sorry!!! Your Banner Ad is not sucessful", true));
                                $_SESSION['meesage_type'] = '1';
                                $this->redirect("/vendor/banner_ad");
                      }
                                     
                               $this->Session->delete('bannerAdData');
                                
                         }
                         
                }
            }
            
         }
         
         
    }


  function test()
  {
    $this->layout = '';
    $a = 'a:7:{s:6:"monday";a:3:{s:7:"is_open";i:1;s:10:"start_time";s:10:"1452713400";s:8:"end_time";s:10:"1452727800";}s:7:"tuesday";a:1:{s:7:"is_open";i:0;}s:9:"wednesday";a:3:{s:7:"is_open";i:1;s:10:"start_time";s:10:"1452727800";s:8:"end_time";s:10:"1452735000";}s:8:"thursday";a:1:{s:7:"is_open";i:0;}s:6:"friday";a:1:{s:7:"is_open";i:0;}s:8:"saturday";a:1:{s:7:"is_open";i:0;}s:6:"sunday";a:1:{s:7:"is_open";i:0;}}';
    pr(unserialize($a));
    
    echo date('H:i','1452713400');
    echo date('H:i','1452727800');
  }
  function index() {
    $adminData = $this->Session->read('adminData');
    if(empty($adminData))
      $this->redirect('/admins/login');
    
    $this->layout = "";
    $this->set('pageTitle', 'Admin Dashboard');
  }
  
  function __index() {
    
    $adminData = $this->Session->read('adminData');
    if(empty($adminData))
      $this->redirect('/admins/login');
    
    $this->layout = "";
    $this->set('pageTitle', 'Admin Dashboard');
    }


   

    function update_list() {

      $this->_checkSessionUser();
      $this->layout = "home_inner";
      $this->set('pagetitle', '');

      $user_id = $this->Session->read('USER_USERID');
   
    //$user_id = $_REQUEST[''];

      $conditions = "UserInvite.is_read = '0' AND UserInvite.receiver_id = '" . $user_id . "'";

      $UserInviteAll = $this->UserInvite->find('all', array('conditions' => $conditions));

     foreach($UserInviteAll as $invite)
          {  
            $this->data['UserInvite']['id'] = $invite['UserInvite']['id'];
            $this->data['UserInvite']['is_read'] = '1';
            $this->UserInvite->save($this->data['UserInvite']);
          }
        echo 'ok';
         exit();
    }

     function review()
    {
    
      $this->_checkSessionUser();
      $this->layout = "home_inner";
      $this->set('pageTitle', 'Review');
      
      $user_id = $this->Session->read('USER_USERID');
    
      $this->UserReviewComment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.image,User.first_name,User.last_name'))));
    
      $this->UserReview->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'post_by'))));
      $this->UserReview->bindModel(array(
      'hasMany' => array(
      'UserReviewComment' => array('foreignKey' => 'review_id','order'=>'UserReviewComment.created Asc')               
      )
      ));
      
      $conditions = "UserReview.user_id = '".$user_id."'";
    $this->UserReview->recursive = 2;
    $AllReview = $this->UserReview->find('all', array('conditions' => $conditions));
    //pr($user_id);
    //pr($AllReview);exit();
     $this->set('AllReview', $AllReview);
    
    }

    function profile() 
    {
         $this->_checkSessionUser();
         $this->layout = "home_inner";
         $this->set('pagetitle', 'Customer Dashboard');

         $user_id = $this->Session->read('USER_USERID');



    $options = array();

    $where = array();

    $where[] = "User.id = '" . $user_id . "' AND User.status = '1'";

    $options['conditions'] = $where;

    $UserDetails = $this->User->find('first', $options);

    //pr($UserDetails);
    $conditions = "SpecialityGroup.status = '1'";

      $SpecialityGroups = $this->SpecialityGroup->find('all', array('conditions' => $conditions));

      $this->set('SpecialityGroups', $SpecialityGroups);

      
       $conditions = "Credential.status = '1'";

      $Credentials = $this->Credential->find('all', array('conditions' => $conditions));

      $this->set('Credentials', $Credentials);


    $this->set('UserDetails', $UserDetails);



    if (!empty($this->params['form']) && isset($this->params['form'])) {

      $this->data['User'] = $this->params['form'];

      $this->data['User']['id'] = $user_id;

      $this->data['User']['username'] = $this->params['form']['first_name'] . ' ' . $this->params['form']['last_name'];

      $this->data['User']['first_name'] = $this->params['form']['first_name'];

      $this->data['User']['last_name'] = $this->params['form']['last_name'];

      $this->data['User']['state'] = $this->params['form']['state'];

      $this->data['User']['address'] = $this->params['form']['address'];

      $this->data['User']['city'] = $this->params['form']['city'];

        $this->data['User']['phone_no'] = $this->params['form']['phone_no'];

          $this->data['User']['zipcode'] = $this->params['form']['zipcode'];

          $this->data['User']['company_name'] = $this->params['form']['company_name'];

          


      $this->User->save($this->data['User']);
/*
      $this->Session->setFlash(__d("statictext", "Successfully changed!!.", true));

      $this->redirect("/instructor/about_us");*/
         $this->Session->setFlash("Successfully changed!!");    
         $_SESSION['meesage_type'] = '1';
         $this->redirect("/vendor/about_us");

     }
     $UserData = $this->User->find('first', array('fields'=>'User.workhour','conditions' => array('User.id'=>$user_id)));
     
      $data = $UserData['User']['workhour'];
    $open_days = array();  
         
    if(!is_null($data) && $data!='' )
    {
          $data_array = unserialize($data);
        //pr($data_array);
          foreach($data_array as $key=>$array)
          {  
           if($array['is_open'] == 1)
           {
            $open_days[$key] = $array;
           }
          }
    }
    //pr($open_days);
       $this->set('open_days', $open_days);
    }

    function about_us() 
    {

          $this->_checkSessionUser();
         $this->layout = "home_inner";
         $this->set('pagetitle', 'Instructor About Us');

         $user_id = $this->Session->read('USER_USERID');

         $where[] = "User.id = '" . $user_id . "' AND User.status = '1'";

         $options['conditions'] = $where;

          $UserDetails = $this->User->find('first', $options);
          $this->set('UserDetails', $UserDetails);

    $options = array();

    $where = array();

    $where[] = "User.id = '" . $user_id . "' AND User.status = '1'";

    $options['conditions'] = $where;

    $UserAboutUs = $this->User->find('first', $options);

    


    $this->set('UserAboutUs', $UserAboutUs);


    $conditions = "SocialLink.user_id = '" . $user_id . "'";

    $social_link_list = $this->SocialLink->find('first', array('conditions' => $conditions));

    $this->set('social_link_list', $social_link_list);

    if (!empty($this->params['form']) && isset($this->params['form'])) {

       /*$this->data['SocialLink']['id'] =  $social_link_list['SocialLink']['id'];
     $this->data['SocialLink']['user_id'] = $user_id;
     $this->data['SocialLink']['own_site'] = $this->params['form']['own_site'];
     $this->data['SocialLink']['blog_link'] = $this->params['form']['blog_link'];
     $this->data['SocialLink']['youtube_link'] = $this->params['form']['youtube_link'];
     $this->data['SocialLink']['pinterest_link'] = $this->params['form']['pinterest_link'];
     $this->data['SocialLink']['twitter_link'] = $this->params['form']['twitter_link'];
     $this->data['SocialLink']['facebook_link'] = $this->params['form']['facebook_link'];
     $this->data['SocialLink']['linkedin_link'] = $this->params['form']['linkedin_link'];
     $this->data['SocialLink']['google_link'] = $this->params['form']['google_link'];
     $this->data['SocialLink']['instagram_link'] = $this->params['form']['instagram_link'];
                                           
    $this->SocialLink->save($this->data['SocialLink']);*/

    $this->data['User'] = $this->params['form'];

      $this->data['User']['id'] = $user_id;

      $this->data['User']['hear_about_us'] = $this->params['form']['about_us'];



      $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['profile_image']['name'] != '') && (in_array($this->params['form']['profile_image']['type'], $file_type))) {

        $max_width = "300";

        $max_height = "300";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['profile_image']['name'];

        $upload_image = time() . '_' . $image_name;



        $upload_target_thumb = 'profile_images/thumb/' . $upload_image;

        $upload_target_small = 'profile_images/small/' . $upload_image;

        $upload_target_big = 'profile_images/big/' . $upload_image;

        $upload_target_original = 'profile_images/' . $upload_image;

        move_uploaded_file($this->params['form']['profile_image']['tmp_name'], $upload_target_original);

        $this->imgOptCpy($upload_target_original, $upload_target_big, 300, 300, 100, true);

        $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);

        $this->imgOptCpy($upload_target_original, $upload_target_small, 80, 80, 57, true);

        $this->data['User']['image'] = $upload_image;

      }else{

        $this->data['User']['image'] = $UserDetails['User']['image'];
      }

      if (($this->params['form']['vendor_logo']['name'] != '') && (in_array($this->params['form']['vendor_logo']['type'], $file_type))) {

        $max_width = "300";

        $max_height = "300";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['vendor_logo']['name'];

        $upload_image = time() . '_' . $image_name;



        $upload_target_thumb = 'vendor_logo/thumb/' . $upload_image;

        $upload_target_small = 'vendor_logo/small/' . $upload_image;

        $upload_target_big = 'vendor_logo/big/' . $upload_image;

        $upload_target_original = 'vendor_logo/' . $upload_image;

        move_uploaded_file($this->params['form']['vendor_logo']['tmp_name'], $upload_target_original);

        $this->imgOptCpy($upload_target_original, $upload_target_big, 300, 300, 100, true);

        $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);

        $this->imgOptCpy($upload_target_original, $upload_target_small, 80, 80, 57, true);



        $this->data['User']['vendor_logo'] = $upload_image;

      }else{
          $this->data['User']['vendor_logo'] = $UserDetails['User']['vendor_logo'];
      }
      
      /*
       if ($this->User->save($this->data['User'])) {
          $this->Session->setFlash(__d("statictext", "Successfully changed!!.", true));

          $this->redirect("/instructor/edit_photo");

        } */


     /* $this->User->save($this->data['User']);*/
            if ($this->User->save($this->data['User'])) {
                $this->Session->setFlash("Successfully changed!!"); 
             $_SESSION['meesage_type'] = '1';
            $var='1';
            $this->redirect("/vendor/profile");

            }
    }
  }

     function create_message() 
    {
         $this->_checkSessionUser();
         $this->layout = "home_inner";
         $this->set('pagetitle', 'Customer Create Message');

         $user_id = $this->Session->read('USER_USERID');
         $conditions = "User.id = '".$user_id."'";

         $UserData = $this->User->find('first', array('conditions' => $conditions));
        // pr($UserData);exit();

    if (!empty($this->params['form']) && isset($this->params['form'])) {

      $this->data['Message']['parent_id'] = '0';
      $this->data['Message']['sender_id'] = $user_id;
      $this->data['Message']['receiver_id'] = $this->params['form']['inst_name'];
      $this->data['Message']['message'] = $this->params['form']['message'];
      $this->data['Message']['sender_type'] = $UserData['User']['type'];
      $inst = $this->params['form']['inst_name'];
      $user_id = $this->Session->read('USER_USERID');
         $conditions = "User.id = '".$inst."'";
         $InstData = $this->User->find('first', array('conditions' => $conditions));
      $this->data['Message']['receiver_type'] = $InstData['User']['type'];
      $this->data['Message']['is_readed'] = '0';
      $this->Message->save($this->data['Message']);

      $this->Session->setFlash(__d("statictext", "Notification Send Successfully!!.", true));
      $var='1';
      $this->redirect("/vendor/create_message");

    }
    }

     function inbox_message_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Inbox');
    $user_id = $this->Session->read('USER_USERID');
  
    $condition = "Message.receiver_id = '".$user_id."' AND Message.is_receiver_delete = '0'";
    $this->Message->bindModel(array('belongsTo'=>array(
          'User'=>array('foreignKey'=>'sender_id',
          'fields'=>'User.id,User.first_name,User.image,User.last_name')
        )));
    $inbox_list = $this->Message->find('all', array('conditions'=>$condition));
    //pr($inbox_list);exit();
   $this->set('inbox_list', $inbox_list);
    }

    function sent_message_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Sent');
    $user_id = $this->Session->read('USER_USERID');
  
    $condition = "Message.sender_id = '".$user_id."' AND Message.is_sender_delete = '0'";
    $this->Message->bindModel(array('belongsTo'=>array(
          'User'=>array('foreignKey'=>'receiver_id',
          'fields'=>'User.id,User.first_name,User.image,User.last_name')
        )));
    $outbox_list = $this->Message->find('all', array('conditions'=>$condition));
   // pr($outbox_list);exit();
   $this->set('outbox_list', $outbox_list);
    }

    function trash_message_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Trash');
    $user_id = $this->Session->read('USER_USERID');
  
    $condition = "(Message.receiver_id = '".$user_id."' AND Message.is_receiver_delete = '1') OR (Message.sender_id = '".$user_id."' AND Message.is_sender_delete = '1')";
    $this->Message->bindModel(
      array('belongsTo'=>array(
          'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
          'fields'=>'Sender.id,Sender.first_name,Sender.image,Sender.last_name'),
          'Receiver'=>array('className'=>'User','foreignKey'=>'receiver_id',
          'fields'=>'Receiver.id,Receiver.first_name,Receiver.image,Receiver.last_name')
        )
        ));
    $trash_list = $this->Message->find('all', array('conditions'=>$condition));
   //pr($trash_list);exit();
   $this->set('trash_list', $trash_list);
   $this->set('user_id', $user_id);
   
    }
    

    function replymessage($message_id){
        
        $this->_checkSessionUser();
         $this->layout = "home_inner";
         $this->set('pagetitle', 'Instructor Reply Message');

         $user_id = $this->Session->read('USER_USERID');
         $conditions = "User.id = '".$user_id."'";

         $UserData = $this->User->find('first', array('conditions' => $conditions));

         $conditions = "Message.id = '".$message_id."'";
         $this->Message->bindModel(
          array('belongsTo'=>array(
          'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
          'fields'=>'Sender.id,Sender.first_name,Sender.image,Sender.last_name'),
          'Receiver'=>array('className'=>'User','foreignKey'=>'receiver_id',
          'fields'=>'Receiver.id,Receiver.first_name,Receiver.image,Receiver.last_name')
        )
        ));
         $MessageData = $this->Message->find('first', array('conditions' => $conditions));
       // pr($MessageData);exit();
        $this->set('MessageData', $MessageData);

            if (!empty($this->params['form']) && isset($this->params['form'])) {

      $this->data['Message']['parent_id'] = $message_id;
      $this->data['Message']['sender_id'] = $user_id;
      $this->data['Message']['receiver_id'] = $this->params['form']['receiver_id'];
      $this->data['Message']['message'] = $this->params['form']['message'];
      $this->data['Message']['sender_type'] = $UserData['User']['type'];
      $inst = $this->params['form']['receiver_id'];
         $conditions = "User.id = '".$inst."'";
         $InstData = $this->User->find('first', array('conditions' => $conditions));
      $this->data['Message']['receiver_type'] = $InstData['User']['type'];
      $this->data['Message']['is_readed'] = '0';
      $this->Message->save($this->data['Message']);

      $this->Session->setFlash(__d("statictext", "Message Replied Successfully!!.", true));
      $var='1';
      $this->redirect("/vendor/sent_message_list");

    }

    }

    function reject_invitation(){
        
         $user_invite_id = $_REQUEST['user_invite_id'];
         $this->data['UserInvite']['id'] = $user_invite_id;
         $this->data['UserInvite']['status'] = 'reject';
         $this->UserInvite->save($this->data['UserInvite']);
      $this->Session->setFlash(__d("statictext", "Invitation Rejected!!.", true));
         echo 'ok';
         exit();
        
    }


    function accept_invitation(){
        
         $user_invite_id = $_REQUEST['user_invite_id'];
         $this->data['UserInvite']['id'] = $user_invite_id;
         $this->data['UserInvite']['status'] = 'accept';
         if($this->UserInvite->save($this->data['UserInvite']))
         {
           $conditions = "UserInvite.id = '".$user_invite_id."'";
           $accept_info = $this->UserInvite->find('first', array('conditions' => $conditions));
           $user_relation1 = array();
        $user_relation1['sender_id'] = $accept_info['UserInvite']['receiver_id'];
        $user_relation1['receiver_id']=$accept_info['UserInvite']['sender_id'];        
        $this->UserRelation->create();
        $this->UserRelation->save($user_relation1);
              
        $user_relation2 = array();
        $user_relation2['sender_id'] = $accept_info['UserInvite']['sender_id'];
        $user_relation2['receiver_id']=$accept_info['UserInvite']['receiver_id'];
        $this->UserRelation->create();      
        $this->UserRelation->save($user_relation2);
        
         }
      
      $this->Session->setFlash(__d("statictext", "Invitation Accepted!!.", true));
         echo 'ok';
         exit();
        
    }



    function deletemessage(){
        
         $message_id = $_REQUEST['message_id'];
         $this->data['Message']['id'] = $message_id;
         $this->data['Message']['is_receiver_delete'] = '1';
         $this->Message->save($this->data['Message']);
      $this->Session->setFlash(__d("statictext", "Message Successfully deleted!!.", true));
         echo 'ok';
         exit();
        
    }


    function deletesentmessage(){
        
         $message_id = $_REQUEST['message_id'];
         $this->data['Message']['id'] = $message_id;
         $this->data['Message']['is_sender_delete'] = '1';
         $this->Message->save($this->data['Message']);
      $this->Session->setFlash(__d("statictext", "Message Successfully deleted!!.", true));
         echo 'ok';
         exit();
        
    }


    function deletetrash(){
        
         $message_id = $_REQUEST['message_id'];
         $this->Message->id = $message_id;
         $this->Message->delete();
      $this->Session->setFlash(__d("statictext", "Message Successfully deleted!!.", true));
         echo 'ok';
         exit();
        
    }

    function deletefriend(){
         $user_id = $this->Session->read('USER_USERID');
          $relation_id = $_REQUEST['relation_id'];

        $conditions = "UserRelation.id = '".$relation_id."'";
         $friend_detail = $this->UserRelation->find('first', array('conditions' => $conditions));
                $conditions = "(UserInvite.sender_id = '".$friend_detail['UserRelation']['sender_id']."' AND UserInvite.receiver_id = '".$friend_detail['UserRelation']['receiver_id']."')OR(UserInvite.sender_id = '".$friend_detail['UserRelation']['receiver_id']."' AND UserInvite.receiver_id = '".$friend_detail['UserRelation']['sender_id']."')"; 
         $invite_detail = $this->UserInvite->find('first', array('conditions' => $conditions));
         if(!empty($invite_detail))
          {
            $this->UserInvite->id = $invite_detail['UserInvite']['id'];
            $this->UserInvite->delete();
          }
         $conditions = "UserRelation.receiver_id = '".$user_id."'";
         $friend_detail1 = $this->UserRelation->find('first', array('conditions' => $conditions));
         $this->UserRelation->id = $friend_detail1['UserRelation']['id'];
         $this->UserRelation->delete();
         
         $this->UserRelation->id = $relation_id;
         $this->UserRelation->delete();
         $this->Session->setFlash(__d("statictext", "Friend Successfully deleted!!.", true));
         echo 'ok';
         exit();
        
    }

      


    function contact() 
    {
         $this->_checkSessionUser();
         $this->layout = "home_inner";
         $this->set('pagetitle', 'Vendor Contact Us');

         $user_id = $this->Session->read('USER_USERID');
         $conditions = "User.id = '".$user_id."'";

         $UserData = $this->User->find('first', array('conditions' => $conditions));
        // pr($UserData);exit();

    if (!empty($this->params['form']) && isset($this->params['form'])) {

      $this->data['Contact']['email'] = $this->params['form']['email'];
      $this->data['Contact']['phone_no'] = $this->params['form']['phone'];
      $this->data['Contact']['subject'] = $this->params['form']['subject'];
      $this->data['Contact']['message'] = $this->params['form']['message'];
      $this->data['Contact']['user_type'] = $UserData['User']['type'];
      $this->data['Contact']['user_id'] = $user_id;
      $this->data['Contact']['is_read'] = '0';

      
      $this->Contact->save($this->data['Contact']);

      $this->Session->setFlash(__d("statictext", "Message Send Successfully!!.", true));
      $var='1';
      $this->redirect("/vendor/contact");

    }
    }

    function classes(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";

    $this->set('pageTitle', 'Classes');
    
    $user_id = $this->Session->read('USER_USERID');



      $conditions = "State.isdeleted = '0' AND State.country_id = '254'";

      $ArState = $this->State->find('all', array('conditions' => $conditions));
      $this->set('ArState', $ArState);

      $conditions = "DanceClassCategory.status = '1'";

      $DanceCategories = $this->DanceClassCategory->find('all', array('conditions' => $conditions));

      $this->set('DanceCategories', $DanceCategories);


      $conditions = "SpecialityGroup.status = '1'";

      $SpecialityGroups = $this->SpecialityGroup->find('all', array('conditions' => $conditions));

      $this->set('SpecialityGroups', $SpecialityGroups);

      $conditions = "SpecialityGroup.status = '1'";

      $SpecialityGroups = $this->SpecialityGroup->find('all', array('conditions' => $conditions));

      $this->set('SpecialityGroups', $SpecialityGroups);

      
       $conditions = "Credential.status = '1'";

      $Credentials = $this->Credential->find('all', array('conditions' => $conditions));

      $this->set('Credentials', $Credentials);



      $conditions = "User.status = '1' AND User.id = '".$user_id."'";

      $UserData = $this->User->find('first', array('conditions' => $conditions));
     
      $data = $UserData['User']['workhour'];
      
          $data_array = unserialize($data);
          $open_days = array();  
          foreach($data_array as $key=>$array)
          {  
           if($array['is_open'] == 1)
           {
            $open_days[$key] = $array;
           }
          }
         
         $this->set('open_days', $open_days);


        // pr($this->params['form']);exit();
    if (!empty($this->params['form']) && isset($this->params['form'])) {

    
      $upload_image="";

      $is_error=0;
      $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['class_photo']['name'] != '') && (in_array($this->params['form']['class_photo']['type'], $file_type))) {

        $max_width = "370";

        $max_height = "245";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['class_photo']['name'];

        $upload_image = time() . '_' . $image_name;

        $upload_target_thumb = 'class_photos/thumb/' . $upload_image;

       

        $upload_target_original = 'class_photos/' . $upload_image;

        if(move_uploaded_file($this->params['form']['class_photo']['tmp_name'], $upload_target_original))
        {
         
          $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);

        }
        else{
           $is_error=1;
        }
      
      }
      if( $is_error == 0)
      {
       $this->data['Class']['user_id'] = $user_id;
        $this->data['Class']['photo'] = $upload_image;
        $this->data['Class']['name'] = $this->params['form']['class_name'];
        $this->data['Class']['description'] = $this->params['form']['class_desc'];
        if($this->params['form']['selecetone']=='1')
        {
          $this->data['Class']['friend_id'] = $this->params['form']['facility'];
           $this->data['Class']['address']='0';
            $this->data['Class']['city_id']='0';
             $this->data['Class']['state_id']='0';
              $this->data['Class']['zipcode']='0';

        }

        else if($this->params['form']['selecetone']=='0')
        {
          $this->data['Class']['friend_id'] ='0';
           $this->data['Class']['address'] = $this->params['form']['address'];
            $this->data['Class']['state_id'] = $this->params['form']['state_id'];
              $this->data['Class']['city_id'] = $this->params['form']['city_id'];
              $this->data['Class']['zipcode'] = $this->params['form']['zip_id'];

        }
   
        if(!empty($this->params['form']['class_id']))
        {
          $class_id = $this->params['form']['class_id'];
          $this->DanceClass->bindModel(array('belongsTo' => array('DanceClassCategory' => array('foreignKey' => 'class_category_id'))));
           $conditions = "DanceClass.id = '" . $class_id . "' AND DanceClass.status ='1'";
            $DanceClassList = $this->DanceClass->find('first', array('conditions' => $conditions));
            if(($DanceClassList['DanceClass']['parent_id'])=='0')
            {
              // pr($DanceClassList);
              $this->data['Class']['dance_class_category_id'] = $DanceClassList['DanceClassCategory']['id'];
              $this->data['Class']['dance_class_id']=$DanceClassList['DanceClass']['id'];
              $this->data['Class']['dance_subclass_id'] = '0';
            }
            else
            {
              $this->DanceClass->bindModel(array('belongsTo' => array('DanceClassCategory' => array('foreignKey' => 'class_category_id'))));
           $conditions = "DanceClass.id = '" . $DanceClassList['DanceClass']['parent_id'] . "' AND DanceClass.status ='1'";
           $DanceClassList1 = $this->DanceClass->find('first', array('conditions' => $conditions));
           
           // pr($DanceClassList1);exit();
              $this->data['Class']['dance_class_category_id'] = $DanceClassList['DanceClassCategory']['id'];
              $this->data['Class']['dance_class_id']=$DanceClassList1['DanceClass']['id'];
              $this->data['Class']['dance_subclass_id'] = $DanceClassList['DanceClass']['id'];
              }
          }
         $this->data['Class']['is_facility'] = $this->params['form']['selecetone'];       
        $this->data['Class']['class_day'] = $this->params['form']['day'];

        $class_start_time= date("H:i", strtotime($this->params['form']['time_start']));
        $class_end_time= date("H:i", strtotime($this->params['form']['time_end']));

        $this->data['Class']['start_time'] = $class_start_time;
        $this->data['Class']['end_time'] =$class_end_time;

        $this->data['Class']['is_public'] = $this->params['form']['Test4'];
        if(isset($this->params['form']['price_id']) && !empty($this->params['form']['price_id']))
        {
          $this->data['Class']['price'] = $this->params['form']['price_id']; 
        }
        else
        {
           $this->data['Class']['price'] = '0';
        }
       //pr($this->params['form']) ;exit();
        $this->Class->create();
        $this->Class->save($this->data['Class']);
          $this->Session->setFlash(__d("statictext", "Class Successfully Added!!.", true));
          $this->redirect("/vendor/classes");
        }
        else{
          $this->Session->setFlash(__d("statictext", "Failed to upload image!!.", true));
          $this->redirect("/vendor/classes");
        }
         

          

    }



  }

  function listing_details(){

    $this->_checkSessionUser();

    $this->layout = "home_inner";

    $this->set('pageTitle', 'User');
    //pr($this->params['form']);exit();
    $user_id = $this->Session->read('USER_USERID');

    
    $options = array();

    $where = array();

    $where[] = "User.id = '" . $user_id . "' AND User.status = '1'";

    $options['conditions'] = $where;

    $UserDetails = $this->User->find('first', $options);

    
    $conditions = "SpecialityGroup.status = '1'";

      $SpecialityGroups = $this->SpecialityGroup->find('all', array('conditions' => $conditions));

      $this->set('SpecialityGroups', $SpecialityGroups);

      
       $conditions = "Credential.status = '1'";

      $Credentials = $this->Credential->find('all', array('conditions' => $conditions));

      $this->set('Credentials', $Credentials);


    $this->set('UserDetails', $UserDetails);
    $ClassData = '';
         
          if($UserDetails['User']['class_type']!='')
          {
           $conditions = "DanceClass.id IN(".$UserDetails['User']['class_type'].")";  
           $ClassData = $this->DanceClass->find('list', array('conditions' => $conditions));
                            
          } 
          $this->set('ClassData', $ClassData);

  
  if (!empty($this->params['form']) && isset($this->params['form'])) {
  //pr($this->params['form']);exit();
    $speciality = '';
    if(isset($this->params['form']['speciality']) && !empty($this->params['form']['speciality'])) {
    $speciality = implode(',',$this->params['form']['speciality']);
    }
    $this->data['User']['speciality'] = $speciality;
    
    $credential = '';
    if(isset($this->params['form']['credential']) && !empty($this->params['form']['credential'])) {
    $credential = implode(',',$this->params['form']['credential']);
    }
    $this->data['User']['credential'] = $credential;
    
    if(isset($this->params['form']['permanent_job']) && (($this->params['form']['permanent_job'])=='1'))
    {
      $this->data['User']['permanent_job'] = '1'; 
    }
    else
    {
       $this->data['User']['permanent_job'] = '0';
    }
    
    if(isset($this->params['form']['sub_jobs']) && (($this->params['form']['sub_jobs'])=='1'))
    {
      $this->data['User']['sub_job'] = '1'; 
    }
    else
    {
       $this->data['User']['sub_job'] = '0';
    }
    if(isset($this->params['form']['libility_insurance']) && ($this->params['form']['libility_insurance']=='1'))
    {
      //pr($this->params['form']['date1']);exit();
      $this->data['User']['libility_insurance'] = '1';
      $this->data['User']['libility_data'] = $this->params['form']['libility_name']; 
      $this->data['User']['libility_expire'] = $this->params['form']['date1']; 
    }
    else
    {
       $this->data['User']['libility_insurance'] = '0';
       $this->data['User']['libility_data'] = '';
       $this->data['User']['libility_expire'] = ''; 
    }
    
    if(isset($this->params['form']['cpr_aed']) && (($this->params['form']['cpr_aed'])=='1'))
    {
      $this->data['User']['cpr'] = '1'; 
      $this->data['User']['cpr_data'] = $this->params['form']['cpr_name'];
      $this->data['User']['cpr_aed_expire'] = $this->params['form']['date']; 
    }
    else
    {
       $this->data['User']['cpr'] = '0';
       $this->data['User']['cpr_data'] = '';
       $this->data['User']['cpr_aed_expire'] = ''; 
    }
    
    if(isset($this->params['form']['first_aid']) && (($this->params['form']['first_aid'])=='1'))
    {
      $this->data['User']['first_aid'] = '1'; 
    }
    else
    {
       $this->data['User']['first_aid'] = '0';
    }
    $this->data['User']['status'] = '1';
    $this->data['User']['class_type'] = $this->params['form']['inst'];
    $this->data['User']['insurance_type'] = $this->params['form']['insurance_type'];
    $this->data['User']['certified_by'] = $this->params['form']['certified_by'];
    $this->data['User']['teaching_since'] = $this->params['form']['teach_since'];
    $this->data['User']['min_cost_of_class'] = $this->params['form']['min_cost'];
    $this->data['User']['max_cost_of_class'] = $this->params['form']['max_cost'];
    $this->data['User']['travel_distance'] = $this->params['form']['distance'];
    if(isset($this->params['form']['credit_card']) && (($this->params['form']['credit_card'])=='1'))
    {
      $this->data['User']['credit_card'] = '1'; 
    }
    else
    {
       $this->data['User']['credit_card'] = '0';
    }
    if(isset($this->params['form']['paypal']) && (($this->params['form']['paypal'])=='1'))
    {
      $this->data['User']['paypal_payment'] = '1'; 
    }
    else
    {
       $this->data['User']['paypal_payment'] = '0';
    }
    if(isset($this->params['form']['check']) && (($this->params['form']['check'])=='1'))
    {
      $this->data['User']['cheque'] = '1'; 
    }
    else
    {
       $this->data['User']['cheque'] = '0';
    }
    if(isset($this->params['form']['cash']) && (($this->params['form']['cash'])=='1'))
    {
      $this->data['User']['cash'] = '1'; 
    }
    else
    {
       $this->data['User']['cash'] = '0';
    }
        
      $this->data['User']['honor_award'] = $this->params['form']['honor_award'];
      
      if(isset($this->params['form']['cv']['name']) && $this->params['form']['cv']['name'] !='')
      {
        $regis_cv = $this->params['form']['cv']['name']; 
        $regis_cvType = $this->params['form']['cv']['type'];
        $regis_cv_arr = explode('.',$regis_cv);
        $regis_cv_arr_count = sizeof($regis_cv_arr);
        $upload_cv = 'cv_'.time().".".$regis_cv_arr[$regis_cv_arr_count-1];
        $upload_target_original = 'uploaded_cv/'.$upload_cv;
        move_uploaded_file($this->params['form']['cv']['tmp_name'],$upload_target_original);
        $this->data['User']['cv'] = $upload_cv;
      }
      
      $this->data['User']['id'] = $user_id;
      $this->User->save($this->data['User']);
      $this->Session->setFlash(__d("statictext", "Listing Done Successfully !!.", true));
      $_SESSION['meesage_type'] = '1';
      $this->redirect("/vendor/workhour");
     
     }
      }

      function change_password() {

    $this->_checkSessionUser();

    $this->layout = "home_inner";



    $this->set('pageTitle', 'Change Password');

    $user_id = $this->Session->read('USER_USERID');



    if (!empty($this->params['form']) && isset($this->params['form'])) {

      $raw_pass = $this->params['form']['current_pwd'];
      $current_pwd = md5($this->params['form']['current_pwd']);

      $new_pwd = $this->params['form']['new_pwd'];
      $password = md5($this->params['form']['new_pwd']);

      $conditions = "User.id = '" . $user_id . "' AND User.password = '" . $current_pwd . "'";

      $ChangePassword = $this->User->find('first', array('conditions' => $conditions));

        //$ArUserDetails = $this->User->find('first', array('conditions' => array('User.id' => $user_id, 'User.status' => '1', 'User.isdeleted' => '0'))); 
        $AfUserHis =    $this->User->find('first', array('conditions' => array('User.email' => $ChangePassword['User']['email'])));  
        //pr($AfArUserHis);exit();
        $this->User->query("UPDATE users SET `txt_password` = '".$new_pwd."'  WHERE `user_id` = '".$AfUserHis['User']['id']."' ");    
            

          $this->data['User']['id'] = $user_id;

          $this->data['User']['password'] = $password;

          $this->data['User']['txt_password'] = $new_pwd;

            if ($this->User->save($this->data['User'])) {

              $this->Session->setFlash(__d("statictext", "Password successfully changed!!.", true));
              $_SESSION['meesage_type'] ='1';
              $this->redirect("/vendor/change_password");

              }
          }
    
  }

    function social_link(){
    $this->_checkSessionUser();

    $this->layout = "home_inner";

    $this->set('pageTitle', 'Friend List');
    //pr($this->params['form']);
    $user_id = $this->Session->read('USER_USERID');

    $conditions = "SocialLink.user_id = '" . $user_id . "'";

    $social_link_list = $this->SocialLink->find('first', array('conditions' => $conditions));

    $this->set('social_link_list', $social_link_list);




    if (!empty($this->params['form']) && isset($this->params['form'])) {

     $this->data['SocialLink']['id'] =  $social_link_list['SocialLink']['id'];
     $this->data['SocialLink']['user_id'] = $user_id;
     $this->data['SocialLink']['own_site'] = $this->params['form']['own_site'];
     $this->data['SocialLink']['blog_link'] = $this->params['form']['blog_link'];
     $this->data['SocialLink']['youtube_link'] = $this->params['form']['youtube_link'];
     $this->data['SocialLink']['pinterest_link'] = $this->params['form']['pinterest_link'];
     $this->data['SocialLink']['twitter_link'] = $this->params['form']['twitter_link'];
     $this->data['SocialLink']['facebook_link'] = $this->params['form']['facebook_link'];
     $this->data['SocialLink']['linkedin_link'] = $this->params['form']['linkedin_link'];
     $this->data['SocialLink']['google_link'] = $this->params['form']['google_link'];
     $this->data['SocialLink']['instagram_link'] = $this->params['form']['instagram_link'];
                                           
      if ($this->SocialLink->save($this->data['SocialLink'])) {

      $this->Session->setFlash(__d("statictext", "SocialLink Added Successfully!!.", true));
      $_SESSION['meesage_type'] ='1';
      $this->redirect("/vendor/social_link");

    }
  }

  }


  function friends_list(){
    $this->_checkSessionUser();

    $this->layout = "home_inner";

    $this->set('pageTitle', 'Friend List');
    //pr($this->params['form']);
    $user_id = $this->Session->read('USER_USERID');

    $this->UserRelation->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'receiver_id'))));
    $condition = "UserRelation.sender_id = '".$user_id."'";
    $friend_list = $this->UserRelation->find('all', array('conditions'=>$condition));
    $this->set('friend_list', $friend_list);


  }

  function add_facility(){

    $this->_checkSessionUser();

    $this->layout = "home_inner";

    $this->set('pageTitle', 'User');
    //pr($this->params['form']);
    $user_id = $this->Session->read('USER_USERID');

    $this->UserInvite->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'receiver_id'))));
    $condition = "UserInvite.sender_id = '".$user_id."' AND UserInvite.status = 'pending'";
    $facility_list = $this->UserInvite->find('all', array('conditions'=>$condition));
    $this->set('facility_list', $facility_list);


     if (!empty($this->params['form']) && isset($this->params['form'])) {
    $facility = $this->params['form']['facility'];
    //echo($facility);exit();
    $facility_number=explode(',',$facility);
    foreach($facility_number as $number)
          {  
            $this->data['UserInvite']['sender_id'] = $user_id;
            $this->data['UserInvite']['receiver_id'] = $number;
            $this->UserInvite->create();
            $this->UserInvite->save($this->data['UserInvite']);
          }
          $this->Session->setFlash(__d("statictext", "Facility Added Sucessfully !!!", true));
           $_SESSION['meesage_type'] ='1';
           $this->redirect("/vendor/add_facility");
         }
      }

    
        function add_jobs()
        {
           $this->_checkSessionUser();

              $this->layout = "home_inner";
            
              $this->set('pageTitle', 'Classes');
              
                $user_id = $this->Session->read('USER_USERID');

                $conditions = "State.isdeleted = '0' AND State.country_id = '254'";

              $ArState = $this->State->find('all', array('conditions' => $conditions));
               $this->set('ArState', $ArState);

               $conditions = "User.id = '" . $user_id . "'";

              $ArUser = $this->User->find('first', array('conditions' => $conditions));
               $this->set('ArUser', $ArUser);
              
               
                  if (!empty($this->params['form']) && isset($this->params['form'])) {
                     
                $user_classes_id = $this->params['form']['inst'];
                    
                    $str_format = strtotime($this->params['form']['date']);
                    $date_format = date('Y-m-d',$str_format);

                    $this->data['Job']['user_id'] = $user_id;
                    $this->data['Job']['tags'] = $user_classes_id;
                    $this->data['Job']['name'] = $this->params['form']['job_name'];
                    $this->data['Job']['min_cost'] = $this->params['form']['min_cost_id'];
                    $this->data['Job']['max_cost'] = $this->params['form']['max_cost_id'];
                    $this->data['Job']['start_date'] = $date_format;
                    $this->data['Job']['employment_type'] = $this->params['form']['e_type'];
                    $this->data['Job']['city'] = $this->params['form']['city_id'];
                    $this->data['Job']['state'] = $this->params['form']['state_id'];
                    $this->data['Job']['zipcode'] = $this->params['form']['zip_id'];
                    $this->data['Job']['url'] = $this->params['form']['url'];
                    $this->data['Job']['post_by'] = $this->params['form']['post_by'];
                    $this->data['Job']['description'] = $this->params['form']['description'];
                                        
                          if ($this->Job->save($this->data['Job'])) {

                        $this->Session->setFlash(__d("statictext", "Job Added Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        $this->redirect("/vendor/add_jobs");

              }
            
          }

        

        }

        function left_panel()
        {

           $this->_checkSessionUser();

              $this->layout = "";
            
              $this->set('pageTitle', 'Left Panel');
              
                $user_id = $this->Session->read('USER_USERID');

                $conditions = "UserInvite.receiver_id =  '".$user_id."' AND UserInvite.is_read = '0'";

              $Facility = $this->UserInvite->find('all', array('conditions' => $conditions));
              $all_facility = count($Facility);
              return $all_facility;
             // pr($all_facility);exit();
             $this->set('user_id', $user_id);

        }

        function edit_jobs($job_id)
        {
           $this->_checkSessionUser();

              $this->layout = "home_inner";
            
              $this->set('pageTitle', 'Classes');
              
                $user_id = $this->Session->read('USER_USERID');

                $conditions = "State.isdeleted = '0' AND State.country_id = '254'";

              $ArState = $this->State->find('all', array('conditions' => $conditions));
               $this->set('ArState', $ArState);
              


               
               $conditions = "Job.id = '".$job_id."'";

                $JobData = $this->Job->find('first', array('conditions' => $conditions));
                $this->set('JobData', $JobData);
        
                  $ClassData = '';
                  if($JobData['Job']['tags']!='')
                  {
                    $conditions = "DanceClass.id IN(".$JobData['Job']['tags'].")";  
                    $ClassData = $this->DanceClass->find('list', array('conditions' => $conditions));
                            
                    
                  } 
                  $this->set('ClassData', $ClassData);


                
                $condition = "City.state_id='".$JobData['Job']['state']."' AND City.isdeleted = '0'";
                  $citylist = $this->City->find("all",array('conditions'=>$condition,'order' => array('City.name ASC')));
                 $this->set('citylist', $citylist); 

                  if (!empty($this->params['form']) && isset($this->params['form'])) {
                      //pr($this->params['form']);
                     $user_classes_id = $this->params['form']['inst'];
                    
                    $str_format = strtotime($this->params['form']['date']);
                    $date_format = date('Y-m-d',$str_format);

                    $this->data['Job']['id'] = $job_id;
                    $this->data['Job']['user_id'] = $user_id;
                    $this->data['Job']['tags'] = $user_classes_id;
                    $this->data['Job']['name'] = $this->params['form']['job_name'];
                    $this->data['Job']['min_cost'] = $this->params['form']['min_cost_id'];
                    $this->data['Job']['max_cost'] = $this->params['form']['max_cost_id'];
                    $this->data['Job']['start_date'] = $date_format;
                    $this->data['Job']['employment_type'] = $this->params['form']['e_type'];
                    $this->data['Job']['city'] = $this->params['form']['city_id'];
                    $this->data['Job']['state'] = $this->params['form']['state_id'];
                    $this->data['Job']['zipcode'] = $this->params['form']['zip_id'];
                    $this->data['Job']['url'] = $this->params['form']['url'];
                    $this->data['Job']['post_by'] = $this->params['form']['post_by'];
                    $this->data['Job']['description'] = $this->params['form']['description'];
                                        
                          if ($this->Job->save($this->data['Job'])) {

                        $this->Session->setFlash(__d("statictext", "Job Edited Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        $this->redirect("/vendor/job_list");

              }
            
          }

        

        }

        function copy_jobs($job_id)
        {
           $this->_checkSessionUser();

              $this->layout = "home_inner";
            
              $this->set('pageTitle', 'Classes');
              
                $user_id = $this->Session->read('USER_USERID');

                $conditions = "State.isdeleted = '0' AND State.country_id = '254'";

              $ArState = $this->State->find('all', array('conditions' => $conditions));
               $this->set('ArState', $ArState);
              


               
               $conditions = "Job.id = '".$job_id."'";

                $JobData = $this->Job->find('first', array('conditions' => $conditions));
                $this->set('JobData', $JobData);
        
                  $ClassData = '';
                  if($JobData['Job']['tags']!='')
                  {
                    $conditions = "DanceClass.id IN(".$JobData['Job']['tags'].")";  
                    $ClassData = $this->DanceClass->find('list', array('conditions' => $conditions));
                            
                    
                  } 
                  $this->set('ClassData', $ClassData);


                
                $condition = "City.state_id='".$JobData['Job']['state']."' AND City.isdeleted = '0'";
                  $citylist = $this->City->find("all",array('conditions'=>$condition,'order' => array('City.name ASC')));
                 $this->set('citylist', $citylist); 

                  if (!empty($this->params['form']) && isset($this->params['form'])) {
                      //pr($this->params['form']);exit();
                     $user_classes_id = $this->params['form']['inst'];
                    
                    $str_format = strtotime($this->params['form']['date']);
                    $date_format = date('Y-m-d',$str_format);

                    /*$this->data['Job']['id'] = $job_id;*/
                    $this->data['Job']['user_id'] = $user_id;
                    $this->data['Job']['tags'] = $user_classes_id;
                    $this->data['Job']['name'] = $this->params['form']['job_name'];
                    $this->data['Job']['min_cost'] = $this->params['form']['min_cost_id'];
                    $this->data['Job']['max_cost'] = $this->params['form']['max_cost_id'];
                    $this->data['Job']['start_date'] = $date_format;
                    $this->data['Job']['employment_type'] = $this->params['form']['e_type'];
                    $this->data['Job']['city'] = $this->params['form']['city_id'];
                    $this->data['Job']['state'] = $this->params['form']['state_id'];
                    $this->data['Job']['zipcode'] = $this->params['form']['zip_id'];
                    $this->data['Job']['url'] = $this->params['form']['url'];
                    $this->data['Job']['post_by'] = $this->params['form']['post_by'];
                    $this->data['Job']['description'] = $this->params['form']['description'];

                    $this->Job->create();
                    if ($this->Job->save($this->data['Job'])) {

                        $this->Session->setFlash(__d("statictext", "Job Copied Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        $this->redirect("/vendor/job_list");

              }
            
          }

        

        }

        function get_job_class($id)
        {
    
      $conditions = "Job.id = '".$id."'";
      $job_detail = $this->Job->find('first', array('conditions' => $conditions));          
      
      $data = '';
      if($job_detail['Job']['tags']!='')
      {
        $conditions = "DanceClass.id IN(".$job_detail['Job']['tags'].")"; 
        $ClassData = $this->DanceClass->find('list', array('conditions' => $conditions));
                
        if(!empty($ClassData))
        {
          $data = '<li>'.implode('</li><li>',$ClassData).'</li>';
        } 
      } 
      return $data;
     }




  function edit_classes($class_id){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Classes');
    
      $user_id = $this->Session->read('USER_USERID');

           $conditions = "Class.id = '".$class_id."'";

      $ClassData = $this->Class->find('first', array('conditions' => $conditions));

      $this->set('ClassData', $ClassData);

		$conditions = "State.isdeleted = '0' AND State.country_id = '254'";

      $ArState = $this->State->find('all', array('conditions' => $conditions));
      $this->set('ArState', $ArState);

	if($ClassData['Class']['state_id']>0)
	{
		$condition = "City.state_id='".$ClassData['Class']['state_id']."' AND City.isdeleted = '0'";
		$citylist = $this->City->find("all",array('conditions'=>$condition,'order' => array('City.name ASC')));
		$this->set('citylist', $citylist); 
	}	

          $FriendData = '';
         
          if($ClassData['Class']['friend_id']!='0')
          {
           $conditions = "User.id IN(".$ClassData['Class']['friend_id'].")";  
           $FriendData = $this->User->find('first', array('conditions' => $conditions));
                            
          } 
          $this->set('FriendData', $FriendData);


          $ClassDetail = '';
          if($ClassData['Class']['dance_subclass_id']!='0')
          {
           $conditions = "DanceClass.id IN(".$ClassData['Class']['dance_subclass_id'].")";  
           $ClassDetail = $this->DanceClass->find('list', array('conditions' => $conditions));
           $this->set('ClassDetail', $ClassDetail);
          } 
          
          else
          {
            $conditions = "DanceClass.id IN(".$ClassData['Class']['dance_class_id'].")";  
           $ClassDetail = $this->DanceClass->find('list', array('conditions' => $conditions));
            $this->set('ClassDetail', $ClassDetail);
          }
         
     

      
     /* $condition = "DanceClass.status = '1' AND DanceClass.class_category_id='".$ClassData['Class']['dance_class_category_id']."' AND DanceClass.parent_id = '0' AND DanceClass.status = '1'";
      $classlist = $this->DanceClass->find("all",array('conditions'=>$condition,'order' => 'DanceClass.name ASC'));
      $this->set('classlist',$classlist);


      

      $condition = "DanceClass.status = '1' AND DanceClass.parent_id='".$ClassData['Class']['dance_class_id']."' AND DanceClass.status = '1'";
      $subclasslist = $this->DanceClass->find("all",array('conditions'=>$condition,'order' => 'DanceClass.name ASC'));
      $this->set('subclasslist',$subclasslist);*/

		
      $conditions = "User.id = '".$user_id."'";

      $UserData = $this->User->find('first', array('conditions' => $conditions));
     
      $data = $UserData['User']['workhour'];
     
          $data_array = unserialize($data);
          $open_days = array();  
          foreach($data_array as $key=>$array)
          {  
           if($array['is_open'] == 1)
           {
              $open_days[$key] = $array;        
        if($key == $ClassData['Class']['class_day'])
        {
          $selected_start_time = $array['start_time'];
                    $selected_end_time = $array['end_time'];
          
        }
           }
          }

        $this->set('open_days', $open_days);
    
    $this->set('selected_start_time',$selected_start_time);
    $this->set('selected_end_time',$selected_end_time);

    if (!empty($this->params['form']) && isset($this->params['form'])) {

      $upload_image="";

      $is_error=0;
      $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['edit_class_photo']['name'] != '') && (in_array($this->params['form']['edit_class_photo']['type'], $file_type))) {

        $max_width = "370";

        $max_height = "245";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['edit_class_photo']['name'];

        $upload_image = time() . '_' . $image_name;

        $upload_target_thumb = 'class_photos/thumb/' . $upload_image;

       

        $upload_target_original = 'class_photos/' . $upload_image;
       move_uploaded_file($this->params['form']['edit_class_photo']['tmp_name'], $upload_target_original);
       $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);
        $this->data['Class']['photo'] = $upload_image;

      }
      else{
          $this->data['Class']['photo'] = $ClassData['Class']['photo'];
      }
      if( $is_error == 0)
      {

        $this->data['Class']['id'] = $class_id;
       $this->data['Class']['user_id'] = $user_id;
        $this->data['Class']['name'] = $this->params['form']['class_name'];
        $this->data['Class']['description'] = $this->params['form']['class_desc'];
        
        /* $this->data['Class']['dance_class_category_id'] = $this->params['form']['category_id'];
       
         
          $this->data['Class']['dance_class_id'] = $this->params['form']['class_id'];
        
        if(isset($this->params['form']['sub_class_id']) >='0' && !empty($this->params['form']['sub_class_id']))
          
        {
          $this->data['Class']['dance_subclass_id'] = $this->params['form']['sub_class_id'];
          
        }
        else{
          $this->data['Class']['dance_subclass_id']='0';
        }*/
        if($this->params['form']['selecetone']=='1')
        {
          $this->data['Class']['friend_id'] = $this->params['form']['facility'];
           $this->data['Class']['address']='0';
            $this->data['Class']['city_id']='0';
             $this->data['Class']['state_id']='0';
              $this->data['Class']['zipcode']='0';

        }

        else if($this->params['form']['selecetone']=='0')
        {
          $this->data['Class']['friend_id'] ='0';
           $this->data['Class']['address'] = $this->params['form']['address'];
            $this->data['Class']['state_id'] = $this->params['form']['state_id'];
              $this->data['Class']['city_id'] = $this->params['form']['city_id'];
              $this->data['Class']['zipcode'] = $this->params['form']['zip_id'];

        }
   
        if(!empty($this->params['form']['class_id']))
        {
          $class_id = $this->params['form']['class_id'];
          $this->DanceClass->bindModel(array('belongsTo' => array('DanceClassCategory' => array('foreignKey' => 'class_category_id'))));
           $conditions = "DanceClass.id = '" . $class_id . "' AND DanceClass.status ='1'";
            $DanceClassList = $this->DanceClass->find('first', array('conditions' => $conditions));
            if(($DanceClassList['DanceClass']['parent_id'])=='0')
            {
              // pr($DanceClassList);
              $this->data['Class']['dance_class_category_id'] = $DanceClassList['DanceClassCategory']['id'];
              $this->data['Class']['dance_class_id']=$DanceClassList['DanceClass']['id'];
              $this->data['Class']['dance_subclass_id'] = '0';
            }
            else
            {
              $this->DanceClass->bindModel(array('belongsTo' => array('DanceClassCategory' => array('foreignKey' => 'class_category_id'))));
           $conditions = "DanceClass.id = '" . $DanceClassList['DanceClass']['parent_id'] . "' AND DanceClass.status ='1'";
           $DanceClassList1 = $this->DanceClass->find('first', array('conditions' => $conditions));
           
           // pr($DanceClassList1);exit();
              $this->data['Class']['dance_class_category_id'] = $DanceClassList['DanceClassCategory']['id'];
              $this->data['Class']['dance_class_id']=$DanceClassList1['DanceClass']['id'];
              $this->data['Class']['dance_subclass_id'] = $DanceClassList['DanceClass']['id'];
              }
          }
         $this->data['Class']['is_facility'] = $this->params['form']['selecetone'];       
        $this->data['Class']['class_day'] = $this->params['form']['day'];

      $class_start_time= date("H:i", strtotime($this->params['form']['time_start']));
          $this->data['Class']['start_time'] = $class_start_time;
          $class_end_time= date("H:i", strtotime($this->params['form']['time_end']));
          $this->data['Class']['end_time'] =$class_end_time;
          if(isset($this->params['form']['price_id']) && !empty($this->params['form']['price_id']))
        {
          $this->data['Class']['price'] = $this->params['form']['price_id']; 
        }
        else
        {
           $this->data['Class']['price'] = '0';
        }


        $this->Class->save($this->data['Class']);
          $this->Session->setFlash(__d("statictext", "Class Successfully Edited!!.", true));
          $this->redirect("/vendor/class_list");
        }
        else{
          $this->Session->setFlash(__d("statictext", "Failed to upload image!!.", true));
          $this->redirect("/vendor/edit_classes/".$class_id);
        }
          

          

    }



  }






    function workhour(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";

    $this->set('pageTitle', 'Work Hour');
    $user_id = $this->Session->read('USER_USERID');

      if($this->params['form'])
      {
    
        $monday = $this->params['form']['monday'];
        $tuesday = $this->params['form']['tuesday'];
        $wednesday = $this->params['form']['wednesday'];
        $thursday = $this->params['form']['thursday'];
        $friday = $this->params['form']['friday'];
        $saturday = $this->params['form']['saturday'];
        $sunday = $this->params['form']['sunday'];
        $work_hour = array();
        if($monday == 1)
        {
          $work_hour['monday']['is_open'] = 1;
          $work_hour['monday']['start_time'] = $this->params['form']['monday_start'];
          $work_hour['monday']['end_time'] = $this->params['form']['monday_end'];
        }
        else
        {
          $work_hour['monday']['is_open'] = 0;
         
        }

        if($tuesday == 1)
        {
          $work_hour['tuesday']['is_open'] = 1;
          $work_hour['tuesday']['start_time'] = $this->params['form']['tuesday_start'];
          $work_hour['tuesday']['end_time'] = $this->params['form']['tuesday_end'];
        }
        else
        {
          $work_hour['tuesday']['is_open'] = 0;
          
        }

        if($wednesday == 1)
        {
          $work_hour['wednesday']['is_open'] = 1;
          $work_hour['wednesday']['start_time'] = $this->params['form']['wednesday_start'];
          $work_hour['wednesday']['end_time'] = $this->params['form']['wednesday_end'];
        }
        else
        {
          $work_hour['wednesday']['is_open'] = 0;
          
        }

        if($thursday == 1)
        {
          $work_hour['thursday']['is_open'] = 1;
          $work_hour['thursday']['start_time'] = $this->params['form']['thursday_start'];
          $work_hour['thursday']['end_time'] = $this->params['form']['thursday_end'];
        }
        else
        {
          $work_hour['thursday']['is_open'] = 0;
          
        }

        if($friday == 1)
        {
          $work_hour['friday']['is_open'] = 1;
          $work_hour['friday']['start_time'] = $this->params['form']['friday_start'];
          $work_hour['friday']['end_time'] = $this->params['form']['friday_end'];
        }
        else
        {
          $work_hour['friday']['is_open'] = 0;
          
        }

        if($saturday == 1)
        {
          $work_hour['saturday']['is_open'] = 1;
          $work_hour['saturday']['start_time'] = $this->params['form']['saturday_start'];
          $work_hour['saturday']['end_time'] = $this->params['form']['saturday_end'];
        }
        else
        {
          $work_hour['saturday']['is_open'] = 0;
          
        }

        if($sunday == 1)
        {
          $work_hour['sunday']['is_open'] = 1;
          $work_hour['sunday']['start_time'] = $this->params['form']['sunday_start'];
          $work_hour['sunday']['end_time'] = $this->params['form']['sunday_end'];
        }
        else
        {
          $work_hour['sunday']['is_open'] = 0;
          
        }

        $ser = serialize($work_hour);
         $this->data['User']['id'] = $user_id;
         $this->data['User']['workhour'] = $ser;

            if ($this->User->save($this->data['User'])) {

            $this->Session->setFlash(__d("statictext", "WorkHour Saved!!", true));
      $_SESSION['meesage_type'] = '1';
      $this->redirect("/vendor/workhour");

            }
      }
    
    $UserData = $this->User->find('first', array('fields'=>'User.workhour','conditions' => array('User.id'=>$user_id)));
     
      $data = $UserData['User']['workhour'];
    $open_days = array();  
         
    if(!is_null($data) && $data!='' )
    {
          $data_array = unserialize($data);
        //pr($data_array);
          foreach($data_array as $key=>$array)
          {  
           if($array['is_open'] == 1)
           {
            $open_days[$key] = $array;
           }
          }
    }
    //pr($open_days);
       $this->set('open_days', $open_days);



    }

    function class_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Classes');
    $user_id = $this->Session->read('USER_USERID');
  
    $condition = "Class.user_id = '".$user_id."'";
    $class_list = $this->Class->find('all', array('conditions'=>$condition));
    $this->set('class_list', $class_list);
    }

     function facility_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Customer List');
    $user_id = $this->Session->read('USER_USERID');
  
    $this->AllContact->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'contact_id'))));
    $condition = "AllContact.user_id = '".$user_id."' AND AllContact.contact_type = 'facility'";
    $facility_list = $this->AllContact->find('all', array('conditions'=>$condition));
   $this->set('facility_list', $facility_list);
   // pr($facility_list);exit();
    }

     function customer_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Customer List');
    $user_id = $this->Session->read('USER_USERID');
  
    $this->AllContact->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'contact_id'))));
    $condition = "AllContact.user_id = '".$user_id."' AND AllContact.contact_type = 'customer'";
    $customer_list = $this->AllContact->find('all', array('conditions'=>$condition));
    $this->set('customer_list', $customer_list);
    // pr($customer_list);
    // pr($user_id);exit();
    }


    function job_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Classes');
    $user_id = $this->Session->read('USER_USERID');
  
    $condition = "Job.user_id = '".$user_id."'";
    $job_list = $this->Job->find('all', array('conditions'=>$condition));
    $this->set('job_list', $job_list);
    }

    function account(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
    $user_id = $this->Session->read('USER_USERID');

    $this->UserSubscription->bindModel(array('belongsTo' => array('SubscriptionPlan' => array('foreignKey' => 'subscription_plan_id'))));
    $condition = "UserSubscription.user_id = '".$user_id."'";
    $user_detail = $this->UserSubscription->find('first', array('conditions'=>$condition));
   //pr($user_detail);exit();
     $this->set('user_detail', $user_detail);

     }

     function premium_subscription($subscript_id){
            $this->_checkSessionUser();

             $this->layout = "";
            $user_id = $this->Session->read('USER_USERID');

                     //pr($this->params['form']);
             $conditions = "SubscriptionPlan.id = '5'";

         $UserData = $this->SubscriptionPlan->find('first', array('conditions' => $conditions));
                     
           if(isset($this->params['form']))
                 {
                     if($this->params['form']['upgrade']=='1')
                     {
                      $vendorData = array();
             
                       $vendorData['plan_type'] = $UserData['SubscriptionPlan']['type'];     
                       $vendorData['pack'] = "monthly";   
                       $vendorData['fee_amnt'] = $UserData['SubscriptionPlan']['monthly_fees'];   
                             $vendorData['plan_id'] =  $UserData['SubscriptionPlan']['id'];    
                       $vendorData['type'] = $UserData['SubscriptionPlan']['type'];  
                       $vendorData['subscript_id'] = $subscript_id;
                       $this->Session->write('vendorData',$vendorData);
                       $this->redirect(array('controller' => 'vendor', 'action' => 'payment'));
                  }
                  else if($this->params['form']['upgrade']=='0')
                   {
                    $vendorData = array();
                     
                     $vendorData['plan_type'] = $UserData['SubscriptionPlan']['type'];    
                     $vendorData['pack'] = "anual";   
                     $vendorData['fee_amnt'] =  $UserData['SubscriptionPlan']['anual_fees'];   
                     $vendorData['plan_id'] = $UserData['SubscriptionPlan']['id'];    
                     $vendorData['type'] = $UserData['SubscriptionPlan']['type'];   
                     $vendorData['subscript_id'] = $subscript_id;
                     $this->Session->write('vendorData',$vendorData);
                     $this->redirect(array('controller' => 'vendor', 'action' => 'payment'));
                }
        
                 }
              }


  



     function payment()
    {
         $this->layout = "login_inner";
         $this->set('pagetitle', 'Payment');
         $user_id = $this->Session->read('USER_USERID');
         $sitesettings = $this->getSiteSettings();  
         
            $privateableKey= $sitesettings['privateableKey']['value'];
            $publishableKey= $sitesettings['publishableKey']['value'];
          
            
            $this->set('privateableKey', $privateableKey);
            $this->set('publishableKey', $publishableKey);
     // $plan_id =  $this->Session->read('vendorData.plan_id');
      $pay_amount =  $this->Session->read('vendorData.fee_amnt');     
      $this->set('pay_amount',$pay_amount);
    
                  
        if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'payment') {             
           {    
          $response = array();
                       
                    $amount =  $this->Session->read('vendorData.fee_amnt');
                    $amount = number_format($amount,2,'','');
                    $token  = $_REQUEST['token_id'];
                 
                    App::import('Vendor', 'lib', array('file' => 'stripe.php'));
                    Stripe::setApiKey($privateableKey);
                  
                    $customer = Stripe_Customer::create(array(                    
                    'email' => trim($_POST['email']),
                    'card'  => $token
                    ));

                    $charge = Stripe_Charge::create(array(
                    'customer' => $customer->id,
                    'amount'   => $amount,
                    'currency' => 'usd'
                    ));

                    $success_note ="";
                    if ($charge->status == 'succeeded'){ 
                
                   $plan_id = $this->Session->read('vendorData.plan_id');
                    $this->data['User']['subscription_plan_id'] = $plan_id;
                    
                    $curr_date=date("Y/m/d");
                    
                     $this->data['User']['subscription_pack'] =$this->Session->read('vendorData.pack');
                     if($this->Session->read('vendorData.pack')=='monthly')
                     {
                      $ren_date=date("Y/m/d", strtotime("+1 months", strtotime(date('Y-m-d'))));

                     }
                     elseif($this->Session->read('vendorData.pack')=='anual')
                     {
                      $ren_date=date("Y/m/d", strtotime("+12 month", strtotime(date('Y-m-d'))));

                     }
                     
                      $this->data['User']['subscription_date'] = $curr_date;
                      $this->data['User']['renewal_date'] = $ren_date;
                      $this->data['User']['paid_amount'] = $this->Session->read('vendorData.fee_amnt');
                    
                      $this->data['User']['id']=$user_id;
                      $this->User->save($this->data);
            
              if($this->Session->read('vendorData.pack')=='monthly')
                {
                 $ren_date=date("Y/m/d", strtotime("+1 months", strtotime(date('Y-m-d'))));
               
                }
                elseif($this->Session->read('vendorData.pack')=='anual')
                {
                  $ren_date=date("Y/m/d", strtotime("+12 month", strtotime(date('Y-m-d'))));
               
                }
                  $this->usubscription['UserSubscription']['id']=$this->Session->read('vendorData.subscript_id');   
                  $this->usubscription['UserSubscription']['user_id']=$user_id;
                  $this->usubscription['UserSubscription']['subscription_plan_id']=$this->Session->read('vendorData.plan_id');
                  $this->usubscription['UserSubscription']['subscription_pack']=$this->Session->read('vendorData.pack');   
                  $this->usubscription['UserSubscription']['amount']=$this->Session->read('vendorData.fee_amnt');   
                  $this->usubscription['UserSubscription']['subscription_date']=$curr_date;
                  $this->usubscription['UserSubscription']['renewal_date']=$ren_date;
                  $this->usubscription['UserSubscription']['payment_token']=$token;
                  $this->usubscription['UserSubscription']['payment_status']= 'paid';
                  
                  $this->UserSubscription->save($this->usubscription);
                  $conditions = "User.id = '".$user_id."'";
  
               $UserDetail = $this->User->find('first', array('conditions' => $conditions));
                  
              $user_name = $UserDetail['User']['first_name'] . ' ' . $UserDetail['User']['last_name']; 
              $admin_sender_email = $sitesettings['site_email']['value'];
              $site_url = $sitesettings['site_url']['value'];
              $sender_name = $sitesettings['email_sender_name']['value'];
  
              $condition = "EmailTemplate.id = '9'";
              $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
               
              $user_type = 'Instructor';
  
              $time = ucfirst($this->Session->read('vendorData.pack'));
              $amount = $this->Session->read('vendorData.fee_amnt'); 
              
              $to = $UserDetail['User']['email'];
  
              $user_name = $user_name;
              $user_subject = $mailDataRS['EmailTemplate']['subject'];
              $user_subject = str_replace('[SITE NAME]', 'Look4fitness | New Registration', $user_subject);
                     
             
              $user_body = $mailDataRS['EmailTemplate']['content'];
              $user_body = str_replace('[USER TYPE]', $user_type, $user_body);
              $user_body = str_replace('[NAME]', $user_name, $user_body);
              $user_body = str_replace('[TIME]', $time, $user_body);
              $user_body = str_replace('[AMOUNT]', $amount, $user_body);
              
              $user_message = stripslashes($user_body);
               $string = '';
               $filepath = '';
               $filename = '';
               $sendCopyTo = '';
                              
              $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);               
              $this->Session->delete('vendorData');
        
               $this->Session->setFlash("Thank you for upgrading your account");
                $_SESSION['meesage_type'] = '1';
              $response['is_error'] = 0;                            
                  
               
                          
                }
          else
          {
            $this->Session->setFlash("Failed to upgrade your subscription");
                        $_SESSION['meesage_type'] = '2';
            
            $response['is_error'] = 1;    
            $response['err_msg'] = "Failed to upgrade your subscription";                                                   
                      
          }
            
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
      }
            
         }
         
         
    }



    function user_account(){
      $this->_checkSessionUser();

    $this->layout = "";
    $user_id = $this->Session->read('USER_USERID');

      if(isset($this->params['form']))
            {
             
            $vendorData = array();
             
             $vendorData['plan_type'] = $this->params['form']['type'];   
             $vendorData['pack'] = $this->params['form']['pack'];   
             $vendorData['fee_amnt'] = $this->params['form']['fees'];   
             $vendorData['plan_id'] = $this->params['form']['plan_id'];   
             $vendorData['type'] = $this->params['form']['type'];  
             $vendorData['subscript_id'] = $this->params['form']['subscript_id'];
             $this->Session->write('vendorData',$vendorData);
             $this->redirect(array('controller' => 'vendor', 'action' => 'payment'));
        
            }



    }


   

    function basic_subscription(){
      $this->_checkSessionUser();

    $this->layout = "";
    $subscript_id = $_REQUEST['subscription_id'];
    $user_id = $this->Session->read('USER_USERID');
    $sitesettings = $this->getSiteSettings(); 
                     //pr($this->params['form']);
                     $curr_date=date("Y/m/d");


                  
                    $this->data['UserSubscription']['id']=$subscript_id;   
                    $this->data['UserSubscription']['user_id'] = $user_id;
                    $this->data['UserSubscription']['subscription_plan_id'] = 1;
                    $this->data['UserSubscription']['subscription_pack'] = "";
                    $this->data['UserSubscription']['amount'] = 0;
                    $this->data['UserSubscription']['subscription_date'] = $curr_date;
                    $this->data['UserSubscription']['payment_token'] = "";
                    $this->data['UserSubscription']['payment_status'] = "not paid";
                    $this->data['UserSubscription']['renewal_date'] = "";
                                                          
                          if ($this->UserSubscription->save($this->data['UserSubscription'])) {
                         
                          $this->data['User']['subscription_plan_id']='1';   
                          $this->data['User']['subscription_pack']="";   
                          $this->data['User']['subscription_date']=$curr_date;   
                          $this->data['User']['renewal_date']="";   
                          $this->data['User']['paid_amount']="0"; 
                          $this->data['User']['id']=$user_id;
                        if($this->User->save($this->data))
                        {
                           $conditions = "User.id = '".$user_id."'";

                         $UserDetail = $this->User->find('first', array('conditions' => $conditions));
                              
                        $user_name = $UserDetail['User']['first_name'] . ' ' . $UserDetail['User']['last_name']; 
                        $admin_sender_email = $sitesettings['site_email']['value'];
                        $site_url = $sitesettings['site_url']['value'];
                        $sender_name = $sitesettings['email_sender_name']['value'];

                        $condition = "EmailTemplate.id = '10'";
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                       
                        $user_type = 'Instructor';
                        
                        $to = $UserDetail['User']['email'];

                        $user_name = $user_name;
                        $user_subject = $mailDataRS['EmailTemplate']['subject'];
                        $user_subject = str_replace('[SITE NAME]', 'Look4fitness | New Registration', $user_subject);
                                   
                     
                        $user_body = $mailDataRS['EmailTemplate']['content'];
                        $user_body = str_replace('[USER TYPE]', $user_type, $user_body);
                        $user_body = str_replace('[NAME]', $user_name, $user_body);
                        
                      
                        $user_message = stripslashes($user_body);
                        $user_header = "From: ".$sender_name."<" . $admin_sender_email . "> \r\n";
                        $user_header .= "MIME-Version: 1.0\r\n";
                        $user_header .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
                        
                       $sendmail = sendmail($to,$user_subject,$user_message,$user_header);
                     // exit;
                     if($sendmail)
                      {
                                 echo 'ok';
                     exit();
                                }
                      else{
                            $this->Session->setFlash(__d("statictext", "Sorry!!! Your account cannot be downgraded", true));
                                $_SESSION['meesage_type'] = '1';
                                $this->redirect("/vendor/account");
                          }
                     
                    }
                }
              
              }


          

  






    

     function deleteclassphoto() {

    $this->_checkSessionUser();
    $this->layout = "";
    $this->set('pageTitle', 'Delete Image');

    $user_id = $this->Session->read('USER_USERID');

    $condition = "Class.user_id = '".$user_id."'";
    $user_detail = $this->Class->find('first', array('conditions'=>$condition));
    
    if(count($user_detail) > 0)
    {
      $this->data['Class']['photo'] = '';
      $this->Class->id = $user_id;
      $this->Class->save($this->data['Class']);
      $this->Session->setFlash(__d("statictext", "Image Successfully deleted!!.", true));

      $this->redirect("/vendor/classes");
    }    

  }

  function delete_editclassphoto($class_id) {

    $this->_checkSessionUser();
    $this->layout = "";
    $this->set('pageTitle', 'Delete Image');

    $user_id = $this->Session->read('USER_USERID');

    $condition = "Class.user_id = '".$user_id."' AND Class.id='".$class_id."'";
    $user_detail = $this->Class->find('first', array('conditions'=>$condition));
    
    if(count($user_detail) > 0)
    {
      $this->data['Class']['photo'] = '';
      $this->Class->id = $class_id;
      $this->Class->save($this->data['Class']);
      $this->Session->setFlash(__d("statictext", "Image Successfully deleted!!.", true));

      $this->redirect("/vendor/edit_classes/".$class_id);
    }    

  }

  function deleteimage($user_id) {

    $this->_checkSessionUser();
    $this->layout = "";
    $this->set('pageTitle', 'Delete Image');

    $condition = "User.id = '".$user_id."'";
    $user_detail = $this->User->find('first', array('conditions'=>$condition));
    
    if(count($user_detail) > 0)
    {
      $this->data['User']['image'] = '';
      $this->User->id = $user_id;
      $this->User->save($this->data['User']);
      $this->Session->setFlash("Image Successfully deleted!!!"); 
      $_SESSION['meesage_type'] = '1';
      $this->redirect("/vendor/about_us/".$user_id);
    }    

  }

    function add_product(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Products');
    $user_id = $this->Session->read('USER_USERID');

    $condition = "ProductCategory.status = '1'";
    $product_category_detail = $this->ProductCategory->find('all', array('conditions'=>$condition));
   //pr($product_category_detail);exit();
    $this->set('product_category_detail', $product_category_detail);

         if (!empty($this->params['form']) && isset($this->params['form'])) {
          //pr($this->params['form']);exit();
                    $this->data['Product']['user_id'] = $user_id;
					$this->data['Product']['price'] = $this->params['form']['product_price'];
                    $this->data['Product']['name'] = $this->params['form']['product_name'];
                    $this->data['Product']['website_link'] = $this->params['form']['website_link'];
                    $this->data['Product']['category_id'] = $this->params['form']['category_id'];
                    $this->data['Product']['tag'] = $this->params['form']['tag'];
                    $this->data['Product']['is_published'] = $this->params['form']['is_published'];
                    $this->data['Product']['product_description'] = $this->params['form']['product_info'];
                    $this->data['Product']['status'] = '1';
                    $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['product_photo']['name'] != '') && (in_array($this->params['form']['product_photo']['type'], $file_type))) {

        $max_width = "300";

        $max_height = "300";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['product_photo']['name'];

        $upload_image = time() . '_' . $image_name;



        $upload_target_thumb = 'product_photo/thumb/' . $upload_image;

        $upload_target_original = 'product_photo/' . $upload_image;

        move_uploaded_file($this->params['form']['product_photo']['tmp_name'], $upload_target_original);

        $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);
    

        $this->data['Product']['photo'] = $upload_image;

      }
                      if ($this->Product->save($this->data['Product'])) {

                        $this->Session->setFlash(__d("statictext", "Product Added Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        $this->redirect("/vendor/add_product");

              }
            
          }

    }



    function edit_products($product_id)
        {
           $this->_checkSessionUser();

              $this->layout = "home_inner";
            
              $this->set('pageTitle', 'Products');
              
                $user_id = $this->Session->read('USER_USERID');


                 $condition = "ProductCategory.status = '1'";
                 $product_category_detail = $this->ProductCategory->find('all', array('conditions'=>$condition));
                  //pr($product_category_detail);exit();
                 $this->set('product_category_detail', $product_category_detail); 

               $conditions = "Product.id = '".$product_id."'";

                $ProductData = $this->Product->find('first', array('conditions' => $conditions));
                $this->set('ProductData', $ProductData);
        

                   if (!empty($this->params['form']) && isset($this->params['form'])) {
                      //pr($this->params['form']);
           
                    $this->data['Product']['id'] = $product_id;
                    $this->data['Product']['user_id'] = $user_id;
					$this->data['Product']['price'] = $this->params['form']['product_price'];
                    $this->data['Product']['name'] = $this->params['form']['product_name'];
                    $this->data['Product']['website_link'] = $this->params['form']['website_link'];
                    $this->data['Product']['category_id'] = $this->params['form']['category_id'];
                    $this->data['Product']['tag'] = $this->params['form']['tag'];
                    $this->data['Product']['is_published'] = $this->params['form']['is_published'];
                    $this->data['Product']['product_description'] = $this->params['form']['product_info'];
                    $this->data['Product']['status'] = '1';
                    $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['product_photo']['name'] != '') && (in_array($this->params['form']['product_photo']['type'], $file_type))) {

        $max_width = "300";

        $max_height = "300";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['product_photo']['name'];

        $upload_image = time() . '_' . $image_name;



        $upload_target_thumb = 'product_photo/thumb/' . $upload_image;

        $upload_target_original = 'product_photo/' . $upload_image;

        move_uploaded_file($this->params['form']['product_photo']['tmp_name'], $upload_target_original);

        $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);
    

        $this->data['Product']['photo'] = $upload_image;

      }
      else{
        $this->data['Product']['photo'] = $ProductData['Product']['photo'];
      }
      
                      if ($this->Product->save($this->data['Product'])) {

                        $this->Session->setFlash(__d("statictext", "Product Edited Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        $this->redirect("/vendor/product_list");

              }
            
          }
        

        }
		
		
		function copy_products($product_id)
        {
           $this->_checkSessionUser();

              $this->layout = "home_inner";
            
              $this->set('pageTitle', 'Products');
              
                $user_id = $this->Session->read('USER_USERID');


                 $condition = "ProductCategory.status = '1'";
                 $product_category_detail = $this->ProductCategory->find('all', array('conditions'=>$condition));
                  //pr($product_category_detail);exit();
                 $this->set('product_category_detail', $product_category_detail); 

               $conditions = "Product.id = '".$product_id."'";

                $ProductData = $this->Product->find('first', array('conditions' => $conditions));
                $this->set('ProductData', $ProductData);
        

                   if (!empty($this->params['form']) && isset($this->params['form'])) {
                      //pr($this->params['form']);
           
                    //$this->data['Product']['id'] = $product_id;
                    $this->data['Product']['user_id'] = $user_id;
					$this->data['Product']['price'] = $this->params['form']['product_price'];
                    $this->data['Product']['name'] = $this->params['form']['product_name'];
                    $this->data['Product']['website_link'] = $this->params['form']['website_link'];
                    $this->data['Product']['category_id'] = $this->params['form']['category_id'];
                    $this->data['Product']['tag'] = $this->params['form']['tag'];
                    $this->data['Product']['is_published'] = $this->params['form']['is_published'];
                    $this->data['Product']['product_description'] = $this->params['form']['product_info'];
                    $this->data['Product']['status'] = '1';
                    $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['product_photo']['name'] != '') && (in_array($this->params['form']['product_photo']['type'], $file_type))) {

        $max_width = "300";

        $max_height = "300";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['product_photo']['name'];

        $upload_image = time() . '_' . $image_name;



        $upload_target_thumb = 'product_photo/thumb/' . $upload_image;

        $upload_target_original = 'product_photo/' . $upload_image;

        move_uploaded_file($this->params['form']['product_photo']['tmp_name'], $upload_target_original);

        $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);
    

        $this->data['Product']['photo'] = $upload_image;

      }
      else{
        $this->data['Product']['photo'] = $ProductData['Product']['photo'];
      }
      
                      if ($this->Product->save($this->data['Product'])) {

                        $this->Session->setFlash(__d("statictext", "Product Copied Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        $this->redirect("/vendor/product_list");

              }
            
          }
        

        }
		
		
	function email_ad()
	{
		$this->_checkSessionUser();

              $this->layout = "home_inner";
            
              $this->set('pageTitle', 'Products');
              
                $user_id = $this->Session->read('USER_USERID');


                 $condition = "ProductCategory.status = '1'";
                 $product_category_detail = $this->ProductCategory->find('all', array('conditions'=>$condition));
                  //pr($product_category_detail);exit();
                 $this->set('product_category_detail', $product_category_detail); 

               $conditions = "Product.isdeleted = '0'";

                $ProductData = $this->Product->find('all', array('conditions' => $conditions));
                $this->set('ProductData', $ProductData);
    }

        function copy_articles($article_id)
        {
           $this->_checkSessionUser();

              $this->layout = "home_inner";
            
              $this->set('pageTitle', 'Articles');
              
                $user_id = $this->Session->read('USER_USERID');


               $conditions = "Article.id = '".$article_id."'";

                $ArticleData = $this->Article->find('first', array('conditions' => $conditions));
                $this->set('ArticleData', $ArticleData);
        

                   if (!empty($this->params['form']) && isset($this->params['form'])) {
                      //pr($this->params['form']);
           
                   // $this->data['Article']['id'] = $article_id;
                    $this->data['Article']['user_id'] = $user_id;
                    $this->data['Article']['article_title'] = $this->params['form']['article_title'];
                    $this->data['Article']['article_by'] = $this->params['form']['article_by'];
                    $this->data['Article']['youtube_link'] = $this->params['form']['youtube_link'];
                    $this->data['Article']['tag_title'] = $this->params['form']['article_tag'];
                    $this->data['Article']['is_published'] = $this->params['form']['is_published'];
                    $this->data['Article']['description'] = $this->params['form']['description'];
                    $this->data['Article']['status'] = '1';
                    $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['article_image']['name'] != '') && (in_array($this->params['form']['article_image']['type'], $file_type))) {

        $max_width = "300";

        $max_height = "300";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['article_image']['name'];

        $upload_image = time() . '_' . $image_name;



        $upload_target_thumb = 'article_image/thumb/' . $upload_image;

        $upload_target_original = 'article_image/' . $upload_image;

        move_uploaded_file($this->params['form']['article_image']['tmp_name'], $upload_target_original);

        $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);
    

        $this->data['Article']['image'] = $upload_image;

      }
      else{
        $this->data['Article']['image'] = $ArticleData['Article']['image'];
      }
                      $this->Article->create();
                      if ($this->Article->save($this->data['Article'])) {

                        $this->Session->setFlash(__d("statictext", "Article Copied Successfully!!.", true));
                        $_SESSION['meesage_type'] ='1';
                        $this->redirect("/vendor/article_list");

              }
            
          }
        

        }

    function product_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Articles');
    $user_id = $this->Session->read('USER_USERID');
  
    $condition = "Product.user_id = '".$user_id."' AND Product.isdeleted = '0'";
    $product_list = $this->Product->find('all', array('conditions'=>$condition));
    $this->set('product_list', $product_list);
    }
      
    

  function deleteclass(){
        
         $class_id = $_REQUEST['class_id'];
         $this->Class->id = $class_id;
         $this->Class->delete();
         echo 'ok';
         exit();
        
    }

    function deletejob(){
        
         $job_id = $_REQUEST['job_id'];
         $this->Job->id = $job_id;
         $this->Job->delete();
         echo 'ok';
         exit();
        
    }

    function delete_product(){
        $product_id = $_REQUEST['product_id'];
        //pr($product_id);exit();
        $this->Product->id = $product_id;
        $this->data['Product']['isdeleted'] = '1';
        $this->Product->save($this->data['Product']);
        echo 'ok';
        exit();
    }




        function vendor_account_type($fees,$type) {
            $this->layout = "login_inner";
      $user_id = $this->Session->read('USER_USERID');      
      $this->set('pagetitle', 'Account Type');
      $this->set('plan_type', $type);
      $plan_detail = $this->SubscriptionPlan->find('all',array('conditions'=>'SubscriptionPlan.plan_for="vendor"'));
          $this->set('plan_detail',$plan_detail);
          $this->set('SERVER_IMG_PATH',"https://www.grouperusa.com/");
            
            /***************************STRIPE PAYMENT***********************************/
            $privateableKey= 'sk_test_vHvxIsu7Fgu9qRAPhAqBWqYo';
            $publishableKey= 'pk_test_1f8lrPGlIdY7HKgPjYx2ZfDT';  // TEST STRIPE PAYMENT
            
            /*$privateableKey= 'sk_live_xFXDtVMsSH0zx9fXf5p2SOC1';
            $publishableKey= 'pk_live_Qv8SOZYBvi3lPlpIUPN4o5FS';*/  // LIVE STRIPE PAYMENT
            
            $this->set('privateableKey', $privateableKey);
            $this->set('publishableKey', $publishableKey);
            /***************************STRIPE PAYMENT***********************************/
            $this->set('type',$type);
            $this->set('fees',$fees);

             $condition = "User.id = '".$user_id."'";
            $user_detail = $this->User->find('first', array('conditions'=>$condition));
    
            
         if (isset($this->params['form']) && !empty($this->params['form']) && $this->params['form']['action'] == 'vendor_account_type') {   
            
             $vendorData = array();
             $vendorData['fee_amnt'] = $this->params['form']['fees'];   
             $vendorData['type'] = $this->params['form']['type']; 
             $vendorData['email'] = $user_detail['User']['email'];   
             $vendorData['first_name'] = $user_detail['User']['first_name'];   
             $vendorData['last_name'] = $user_detail['User']['last_name'];     
             $this->Session->write('vendorData',$vendorData);
             $this->redirect(array('controller' => 'vendor', 'action' => 'payment'));
          
         }
    }

     function receive_invitation_list(){
      $this->_checkSessionUser();

    $this->layout = "home_inner";
  
    $this->set('pageTitle', 'Inbox');
    $user_id = $this->Session->read('USER_USERID');
  
    $condition = "UserInvite.receiver_id = '".$user_id."' AND UserInvite.status = 'pending'";
    $this->UserInvite->bindModel(array('belongsTo'=>array(
          'User'=>array('foreignKey'=>'sender_id',
          'fields'=>'User.id,User.first_name,User.image,User.last_name')
        )));
    $invitation_list = $this->UserInvite->find('all', array('conditions'=>$condition));
    //pr($invitation_list);exit();
   $this->set('invitation_list', $invitation_list);
    }

   
  function deleteimage_1($user_id) {

    $this->_checkSessionUser();
    $this->layout = "";
    $this->set('pageTitle', 'Delete Image');

    $user_id = $this->Session->read('USER_USERID');

    $condition = "User.id = '".$user_id."'";
    $user_detail = $this->User->find('first', array('conditions'=>$condition));
    
    if(count($user_detail) > 0)
    {
      $this->data['User']['vendor_logo'] = '';
      $this->User->id = $user_id;
      $this->User->save($this->data['User']);
      $this->Session->setFlash("Image Successfully deleted!!!"); 
      $_SESSION['meesage_type'] = '1';
      $this->redirect("/vendor/about_us/".$user_id);
    }    

  }

   function edit_photo() {

    $this->_checkSessionUser();

    $this->layout = "home_inner";



    $this->set('pageTitle', 'Edit Photo');

    $user_id = $this->Session->read('USER_USERID');



    $options = array();

    $where = array();

    $where[] = "User.id = '" . $user_id . "' AND User.status = '1'";

    $options['conditions'] = $where;

    $UserDetails = $this->User->find('first', $options);
    $this->set('UserDetails', $UserDetails);



    if (!empty($this->params['form']) && isset($this->params['form'])) {

      $this->data['User'] = $this->params['form'];


      $this->data['User']['id'] = $user_id;



      $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['profile_image']['name'] != '') && (in_array($this->params['form']['profile_image']['type'], $file_type))) {

        $max_width = "300";

        $max_height = "300";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['profile_image']['name'];

        $upload_image = time() . '_' . $image_name;



        $upload_target_thumb = 'profile_images/thumb/' . $upload_image;

        $upload_target_small = 'profile_images/small/' . $upload_image;

        $upload_target_big = 'profile_images/big/' . $upload_image;

        $upload_target_original = 'profile_images/' . $upload_image;

        move_uploaded_file($this->params['form']['profile_image']['tmp_name'], $upload_target_original);

        $this->imgOptCpy($upload_target_original, $upload_target_big, 300, 300, 100, true);

        $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);

        $this->imgOptCpy($upload_target_original, $upload_target_small, 80, 80, 57, true);

        $this->data['User']['image'] = $upload_image;

      }else{

        $this->data['User']['image'] = $UserDetails['User']['image'];
      }

      if (($this->params['form']['vendor_logo']['name'] != '') && (in_array($this->params['form']['vendor_logo']['type'], $file_type))) {

        $max_width = "300";

        $max_height = "300";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['vendor_logo']['name'];

        $upload_image = time() . '_' . $image_name;



        $upload_target_thumb = 'vendor_logo/thumb/' . $upload_image;

        $upload_target_small = 'vendor_logo/small/' . $upload_image;

        $upload_target_big = 'vendor_logo/big/' . $upload_image;

        $upload_target_original = 'vendor_logo/' . $upload_image;

        move_uploaded_file($this->params['form']['vendor_logo']['tmp_name'], $upload_target_original);

        $this->imgOptCpy($upload_target_original, $upload_target_big, 300, 300, 100, true);

        $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);

        $this->imgOptCpy($upload_target_original, $upload_target_small, 80, 80, 57, true);



        $this->data['User']['vendor_logo'] = $upload_image;

      }else{
          $this->data['User']['vendor_logo'] = $UserDetails['User']['vendor_logo'];
      }
      
      
       if ($this->User->save($this->data['User'])) {
          $this->Session->setFlash(__d("statictext", "Successfully changed!!.", true));

          $this->redirect("/vendor/edit_photo");

        } 
    }

  }
}?>