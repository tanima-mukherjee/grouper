<?php
  class AdminContentController extends AppController {
    
    var $name = 'AdminContent';
    var $uses = array('Admin','User','Content','EmailTemplate','StaticContent');
    var $helpers = array('Html', 'Form', 'Javascript', 'Fck', 'Js');
    var $components = array();


    function all_contents(){
    $adminData = $this->Session->read('adminData');
    if(empty($adminData))
      $this->redirect('/admins/login');
    
    $this->layout = "";
    $this->set('pageTitle', 'Contents');

    $condition= "Content.is_deleted = '0'";
	  $all_contents = $this->Content->find('all',array('conditions'=>array($condition)));
  //pr($all_contents);exit();
	  $this->set('all_contents', $all_contents);
   
  	}

  	function about_us()
      
	   {  
	   
       if($this->Session->read('USER_USERID')){
            $this->layout = "home_inner";
       }else{
            $this->layout = "home_inner";
       }
       //$id = '1';
       $condition = "Content.id = '3' AND Content.is_deleted = '0' ";
       $ContentArr = $this->Content->find("first",array("conditions"=>$condition)) ;
       $this->set('ContentArr',$ContentArr);
      
			
    }
			
    
    
        function faq()
     
       {  
       
       if($this->Session->read('USER_USERID')){
            $this->layout = "home_inner";
       }else{
            $this->layout = "home_inner";
       }
       //$id = '1';
       $condition = "Content.id = '4' AND Content.is_deleted = '0' ";
       $ContentArr = $this->Content->find("first",array("conditions"=>$condition)) ;
       $this->set('ContentArr',$ContentArr); 
       } 
       
   


    function privacy_policy()
      
	   {  
	   
      if($this->Session->read('USER_USERID')){
            $this->layout = "home_inner";
       }else{
            $this->layout = "home_inner";
       }
       //$id = '1';
       $condition = "Content.id = '1' AND Content.is_deleted = '0' ";
       $ContentArr = $this->Content->find("first",array("conditions"=>$condition)) ;
       $this->set('ContentArr',$ContentArr);
      
			
    }
	    

    

    function terms_conditions()
      
	   {  
	   
       if($this->Session->read('USER_USERID')){
            $this->layout = "home_inner";
       }else{
            $this->layout = "home_inner";
       }
       //$id = '1';
       $condition = "Content.id = '2' AND Content.is_deleted = '0' ";
       $ContentArr = $this->Content->find("first",array("conditions"=>$condition)) ;
       $this->set('ContentArr',$ContentArr);
      
		
		}	
    

    function add()
  {
    $this->_checkSession();
    $this->layout = "";
    $this->set('pageTitle', 'Add Content');
    
    
    if(!empty($this->params['form']))
    {
      $this->data['Content']['title'] = $this->params['form']['title']; 
      $this->data['Content']['content'] = $this->params['form']['content']; 
      $this->Content->create();
      $this->Content->save($this->data);
      $this->Session->setFlash("Content Added Successfully.");
      $this->redirect('/admin_content/all_contents');  
    }
  }


    	function edit($id=null)
	{
		$this->_checkSession();
		$this->layout = "";
		$this->set('pageTitle', 'Edit Content');
		
		$condition= "Content.id = '".$id."'";
		$detail = $this->Content->find('first',array('conditions'=>array($condition)));
		$this->set('detail', $detail);
		
		$this->Content->id = $id;
		if(!empty($this->params['form']))
		{
			$this->data['Content'] = $this->params['form'];	
			$this->Content->save($this->data);
			$this->Session->setFlash("Content Edited Successfully.");
			$this->redirect('/admin_content/all_contents');  
		}
	}

  function contact_us() 
    {
         
         $this->layout = "home_inner";
         $this->set('pagetitle', 'Contact Us');
         $this->set('SERVER_IMG_PATH'," https://www.grouperusa.com/");
         $sitesettings = $this->getSiteSettings();

         
         
        // pr($UserData);exit();
          if(!empty($this->params['form']))
         {  

                          $email = $this->params['form']['email'];
                          $phone_no = $this->params['form']['phone'];
                          $subject = $this->params['form']['subject'];
                          $message = $this->params['form']['message'];
                  
                        $admin_sender_email = $email;
                       /*$admin_sender_email =  $sitesettings['site_email']['value'];*/

                        $site_url = $sitesettings['site_url']['value'];
                       
                        $condition = "EmailTemplate.id = '3'";
                        $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                       
                       $to = $sitesettings['site_email']['value'];
                        /* $to = $email;*/

                       
                        $user_subject = $mailDataRS['EmailTemplate']['subject'];
                        $user_subject = str_replace('[SITE NAME]', 'Look4fitness | Contact Us Enquiry', $user_subject);
                                   
                     
                        $user_body = $mailDataRS['EmailTemplate']['content'];
                       // $user_body = str_replace('[SUBJECT]', $subject, $user_body);
                        $user_body = str_replace('[MESSAGE]', $message, $user_body);
                        $user_body = str_replace('[EMAIL]', $email, $user_body);
                       // $user_body = str_replace('[PHONE]', $phone_no, $user_body);
                        
                      
                       /* $user_message = stripslashes($user_body);*/
                        
                  
       
                    /*$sendmail = sendmail($admin_sender_email,$to,$user_subject,$user_message);         */
                    $user_message = stripslashes($user_body);
                    $string = '';
                    $filepath = '';
                    $filename = '';
                    $sendCopyTo = '';
                    $sender_name = $email;
                                  
                    $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);      
                          // $sendmail = sendmail($to,$user_subject,$user_message,$user_header);
                          //echo $sendmail;
                          //exit;
                          if($sendmail)
                           {
                              $this->Session->setFlash("Message Send Successfully!!.");
                              $_SESSION['meesage_type'] = '1';
                              $this->redirect("/home/index");
                            
                          }

                    
        }
    }


     function all_static_contents(){
    $adminData = $this->Session->read('adminData');
    if(empty($adminData))
      $this->redirect('/admins/login');
    
    $this->layout = "";
    $this->set('pageTitle', 'Static Contents');

    $condition= "StaticContent.is_deleted = '0'";
    $all_static_contents = $this->StaticContent->find('all',array('conditions'=>array($condition)));
  //pr($all_static_contents);exit();
    $this->set('all_static_contents', $all_static_contents);
   
    }

      function edit_static_content($id=null)
  {
    $this->_checkSession();
    $this->layout = "";
    $this->set('pageTitle', 'Edit Static Content');
    
    $condition= "StaticContent.id = '".$id."'";
    $detail = $this->StaticContent->find('first',array('conditions'=>array($condition)));
    $this->set('detail', $detail);
    
    $this->StaticContent->id = $id;
    if(!empty($this->params['form']))
    {
      $this->data['StaticContent'] = $this->params['form']; 
      $this->StaticContent->save($this->data);
      $this->Session->setFlash("Static Content Edited Successfully.");
      $this->redirect('/admin_content/all_static_contents');  
    }
  }

}
?>
