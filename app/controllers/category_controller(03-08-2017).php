<?php
class CategoryController extends AppController {

    var $name = 'Category';
    var $uses = array('Category','State','City','Group','User','EmailTemplate');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    
    
    function forgot_password(){

        $this->layout = "home_landing";
        $this->set('pageTitle', 'Forgot Password');
        
        if (!empty($this->params['form'])) {
            //pr($this->params['form']);exit;
            $email = $this->params['form']['forgot_email'];
            $ArUserDetails = $this->User->find('first', array('conditions' => array('User.email' => $email, 'User.status' => '1'), 'fields' => array('User.id,User.fname,User.lname,User.txt_password,User.email') ));
            if (!empty($ArUserDetails)) {
                //pr($ArUserDetails);exit();
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
		   
					//pr($user_body);exit;
					$sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                 
					if($sendmail)
					  {
							$this->Session->setFlash("Please check your email to get your password. ");    
							$_SESSION['meesage_type'] = '1';
							$this->redirect(array('controller'=>'home','action'=>'index'));
					  }
					  else
					  {
							$this->Session->setFlash("Failed to send email.");    
							$_SESSION['meesage_type'] = '1';
							$this->redirect(array('controller'=>'home','action'=>'index'));
					  }
					
            } else {

                $this->Session->setFlash(__d('statictext', "This email doesn't exist in our database.", true));
				$_SESSION['meesage_type'] = '0';
				$this->redirect("/home/index");
            }
        }
    }
    
    
    

    function category_list(){
	
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        //$this->_checkSessionUser();
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
       
        $condition = "Category.status ='1'";
        $order_by = 'Category.title ASC';
       /* $category_list = $this->Category->find("all",array('conditions'=>$condition,'order' => 'Category.title ASC'));
       // pr($featured_users);exit();
        $this->set('category_list',$category_list);*/

        $options = array('conditions' => $condition,'order' =>$order_by);
        //echo '<pre>';print_r($options);echo '</pre>';
        $this->paginate = $options ;
        $category_list = $this->paginate('Category');
        $this->set('category_list',$category_list);
    }


	
}?>
