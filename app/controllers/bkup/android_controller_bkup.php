<?php 
class AndroidController  extends AppController 
{
	var $name = 'Android';
	var $helpers = array('Html', 'Form','Javascript','Js','Session');
    var $components = array('RequestHandler', 'Session','Cookie');  
	var $uses = array("Admin","User","Category","Group","Event","GroupImage","City","State");
	
	
    

        function state_list()
        {
             $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
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
   

        function city_list()
        {
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
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



        function registration()
    
    {
         //Configure::write('debug',3);
        $this->layout = ''; 
        $defaultconfig = $this->getDefaultConfig();      
         $base_url = $defaultconfig['base_url'];
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        
        $fname = $_POST['first_name']; 
        $lname = $_POST['last_name'];  
        $username = $_POST['username'] ;
        $email = $_POST['email'];  
        $password = $_POST['password'];
        $device_token = $_POST['device_token']; 
        $device_type = $_POST['device_type'];
        $state_id = $_POST['state_id']; 
        $city_id = $_POST['city_id'];
        
        
    
     
        /*$fname = 'debdfgdlina';   
        $lname = 'dadfgfds';    
        $username = 'deblifgfdfdnadas';
        $email = 'deblibvnbdfvna@gmail.com';
        $password= '123456';
        
        $device_type = 'iphone';
        $device_token = '1234h56';*/
       

        
        $upload_image = '';
        
            if(isset($_FILES["upload_image"]) && $_FILES["upload_image"]['name']!= '')
        {
            $image_name = $_FILES["upload_image"]['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'user_images/'.$upload_image;
                        
            $imagelist = getimagesize($_FILES["upload_image"]['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2)
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
                
        if($response['is_error'] == 0)  
        {

            $is_exist = $this->User->find('count', array('conditions'=>array('email'=>$email)));
            $is_username_exist = $this->User->find('count', array('conditions'=>array('username'=>$username)));
            if($is_exist > 0)
            {
                $response['is_error'] = 1;
                $response['err_msg'] = 'You are already registered in this email';    
            }
            else if($is_username_exist > 0)
            {
                $response['is_error'] = 1;
                $response['err_msg'] = 'You are already registered in this username';    
            }
            else
            {
                $this->data['User']['fname'] = $fname;
                $this->data['User']['lname'] = $lname;
                $this->data['User']['email'] = $email;
                $this->data['User']['password'] = md5($password);
                $this->data['User']['txt_password'] = $password;
                $this->data['User']['username'] = $username;
                $this->data['User']['image'] = $upload_image;
                $this->data['User']['device_type'] = $device_type;
                $this->data['User']['device_token'] = $device_token;
                $this->data['User']['city_id'] = $city_id;
                $this->data['User']['state_id'] = $state_id;

                $this->data['User']['status'] = '1';
                                
                $this->User->create();
                if($this->User->save($this->data))
                {
                    $insert_id = $this->User->getLastInsertID();                    
                    $response['User']['id'] = $insert_id;
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
             
                    
                    
                                
                }
                
            }
        }
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }

	function login()
	{
		$this->layout = '';
		$response = array();
		//$response['is_error'] = 0;
		$defaultconfig = $this->getDefaultConfig();      
         $base_url = $defaultconfig['base_url'];
		
		$json = file_get_contents('php://input');
			
		$obj = json_decode($json);
        
       /* $username = 'tanima';  
        $password = '123456';
        */
        
       
		$username    = $obj->{'username'};
		$password  = $obj->{'password'};
		
        
		$condition = "User.username = '".$username."' AND User.txt_password = '".$password."' AND User.password = '".md5($password)."' AND User.status = '1'";
		$detail = $this->User->find('first',array('conditions'=>$condition));
        $detail_count = $this->User->find('count',array('conditions'=>$condition));			
		//pr($username);exit();
		if($detail_count > 0)
		{
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

            $response['User']['email'] = is_null($detail['User']['email']) ? '' : $detail['User']['email'];
            $response['User']['username'] = ucfirst($detail['User']['username']);
            if($detail['User']['image']!="")
                    {
                    
                    $response['User']['image'] = $base_url.'user_images/thumb/'.$detail['User']['image'];

                    }
                    else
                    {
                    $response['User']['image'] = $base_url.'images/no_profile_img.jpg';
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

	


     function category_list()
          {
            //Configure::write('debug',3);
            $this->layout = ""; 
            $response = array();
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $obj = json_decode($json);
            
                        
        
            $condition2 = "Category.status ='1'";
            $all_category = $this->Category->find('all',array('conditions'=>$condition2));
            
            //pr($all_category);exit;
            $category = array(); 
            
            if(count($all_category) > 0)
            { 
                foreach($all_category as $cat)
                {
                    $list['id'] = $cat['Category']['id'];
                    $list['title'] =  ucfirst($cat['Category']['title']);
                    $list['desc'] =  ucfirst($cat['Category']['category_desc']);
                    if(!empty($cat['Category']['image']))
                       {
                       $list['image_url']['thumb'] = $base_url.'category_photos/thumb/'.$cat['Category']['image'];
                       $list['image_url']['medium'] = $base_url.'category_photos/android/medium/'.$cat['Category']['image'];
                       $list['image_url']['original'] = $base_url.'category_photos/'.$cat['Category']['image'];
                       }
                    else
                       {
                       $list['image_url']['original'] = $base_url.'images/no_profile_img.jpg';
                       }
                    array_push($category,$list);
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

            function category_details()
    {
        $this->layout = '';
         $defaultconfig = $this->getDefaultConfig();      
         $base_url = $defaultconfig['base_url'];
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
       
        $category_id = $obj->{'category_id'};
        
                
        //$category_id = '1';
        
        
         $condition = "Category.id = '".$category_id."' AND Category.status = '1'";
         $category_detail = $this->Category->find('first',array('conditions'=>$condition)); 
         
         if(!empty($category_detail['Category']['id']))
         {

         $response['is_error'] = 0;
         $response['id'] = ucfirst($category_detail['Category']['id']);
         $response['title'] = ucfirst($category_detail['Category']['title']);
         $response['description'] = ucfirst($category_detail['Category']['category_desc']);
          if(!empty($category_detail['Category']['image']))
             {
             $response['image_url']['thumb'] = $base_url.'category_photos/thumb/'.$category_detail['Category']['image'];
             $response['image_url']['medium'] = $base_url.'category_photos/android/medium/'.$category_detail['Category']['image'];
             $response['image_url']['original'] = $base_url.'category_photos/'.$category_detail['Category']['image'];
             }
          else
             {
             $response['image_url']['original'] = $base_url.'images/no_profile_img.jpg';
             }
         $condition = "Group.category_id = '".$category_id."' AND Group.status = '1'";
         $group_detail = $this->Group->find('all',array('conditions'=>$condition)); 
          $groups = array(); 
            
            if(count($group_detail) > 0)
            { 
                foreach($group_detail as $group)
                {
                    $list['group_id'] = $group['Group']['id'];
                    $list['group_title'] = $group['Group']['group_title'];
                    $list['group_desc'] =  $group['Group']['group_desc'];
                   if(!empty($group['Group']['icon']))
             {
             $list['image_url']['thumb'] = $base_url.'group_images/thumb/'.$group['Group']['icon'];
             $list['image_url']['medium'] = $base_url.'group_images/medium/'.$group['Group']['icon'];
             $list['image_url']['original'] = $base_url.'group_images/'.$group['Group']['icon'];
             }
          else
             {
             $list['image_url']['original'] = $base_url.'images/no_profile_img.jpg';
             }

                    array_push($groups,$list);
                }
              
                
            }
            
              $response['groups']= $groups;       
           }
           else
           {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Category found';
           } 
            
           
         
          
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }


     function create_group()
    {
         //Configure::write('debug',3);
        $this->layout = ''; 
        $defaultconfig = $this->getDefaultConfig();      
         $base_url = $defaultconfig['base_url'];
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        
        $group_title = $_POST['group_title']; 
        $group_desc = $_POST['group_desc'];  
        $user_id = $_POST['user_id'];  
        $category_id = $_POST['category_id'];
      
    
     /*
        $group_title = 'dfdfgf'; 
        $short_desc = 'ffgfdfgfd';  
        $long_desc = 'fgf fg  fg dfg dfgdgdfg df dfgd' ;
        $user_id = '2';  
        $category_id = '1';*/
       

        
        $upload_image = '';
        
            if(isset($_FILES["upload_image"]) && $_FILES["upload_image"]['name']!= '')
        {
            $image_name = $_FILES["upload_image"]['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'group_images/'.$upload_image;
                        
            $imagelist = getimagesize($_FILES["upload_image"]['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2)
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
        else
        {
            $response['is_error'] = 1;
            $response['err_msg'] = 'Please upload image';
        }
                
        if($response['is_error'] == 0)  
        {

        
                $this->data['Group']['group_title'] = $group_title;
                $this->data['Group']['group_desc'] = $group_desc;
                $this->data['Group']['icon'] = $upload_image;
                $this->data['Group']['group_owners'] = $user_id;
                $this->data['Group']['category_id'] = $category_id;
               
                $this->Group->create();
                if($this->Group->save($this->data))
                {
                    $insert_id = $this->Group->getLastInsertID();                    
                    $response['group_id'] = $insert_id;
                }
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }


           function group_details()
    {
        $this->layout = '';
         $defaultconfig = $this->getDefaultConfig();      
         $base_url = $defaultconfig['base_url'];
        
        $json = file_get_contents('php://input');
        $obj = json_decode($json);
       
       $group_id = $obj->{'group_id'};
        
                
      // $group_id = '1';
        
        
         $condition = "Group.id = '".$group_id."' AND Group.status = '1'";
         $group_detail = $this->Group->find('first',array('conditions'=>$condition)); 
         
         if(!empty($group_detail['Group']['id']))
         {

         $response['is_error'] = 0;
         $response['id'] = ucfirst($group_detail['Group']['id']);
         $response['group_title'] = ucfirst($group_detail['Group']['group_title']);
         $response['group_desc'] = ucfirst($group_detail['Group']['group_desc']);
         $condition = "Category.id = '".$group_detail['Group']['category_id']."' AND Category.status = '1'";
         $category_detail = $this->Category->find('first',array('conditions'=>$condition)); 
                        
          if(!empty($group_detail['Group']['icon']))
             {
             $response['image_url']['thumb'] = $base_url.'group_images/thumb/'.$group_detail['Group']['icon'];
              $response['image_url']['medium'] = $base_url.'group_images/medium/'.$group_detail['Group']['icon'];
                        
             $response['image_url']['original'] = $base_url.'group_images/'.$group_detail['Group']['icon'];
             }
          else
             {
             $response['image_url']['original'] = $base_url.'images/no_profile_img.jpg';
             }
        $response['category_name'] = ucfirst($category_detail['Category']['title']);
         $condition = "User.id = '".$group_detail['Group']['group_owners']."' AND User.status = '1'";
         $owner_detail = $this->User->find('first',array('conditions'=>$condition));
        $response['owner_id'] = ucfirst($owner_detail['User']['id']); 

         $condition = "Event.group_id = '".$group_id."' AND Event.status = '1'";
         $event_detail = $this->Event->find('all',array('conditions'=>$condition)); 
          $events = array(); 
            
            if(count($event_detail) > 0)
            { 
                foreach($event_detail as $event)
                {
                    $list['event_id'] = $event['Event']['id'];
                    $list['title'] = $event['Event']['title'];
                    $list['type'] =  $event['Event']['type'];
                    $list['date'] =  $event['Event']['date'];
                    $list['address'] =  $event['Event']['address'];
                    $list['state'] =  $event['Event']['state'];
                    $list['city'] =  $event['Event']['city'];
                    $list['zip'] =  $event['Event']['zip'];
                    
                    array_push($events,$list);
                }
              
                
            }
            
              $response['events']= $events;       
           }
           else
           {
            $response['is_error'] = 1;
            $response['err_msg'] = 'No Group found';
           } 
            
           
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }


    function create_category()
    {
         //Configure::write('debug',3);
        $this->layout = ''; 
        $defaultconfig = $this->getDefaultConfig();      
         $base_url = $defaultconfig['base_url'];
        $response = array();
        $response['is_error'] = 0;

        $json = file_get_contents('php://input');
      
        
        $title = $_POST['title']; 
        $desc = $_POST['desc'];  
        $user_id = $_POST['user_id']; 
            
    
     /*
        $title = 'dfdfgf'; 
        $desc = 'ffgfdfgfd dffs  fsd f dsf sdffs sf ';  
        */
       

        
        $upload_image = '';
        
            if(isset($_FILES["upload_image"]) && $_FILES["upload_image"]['name']!= '')
        {
            $image_name = $_FILES["upload_image"]['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'category_photos/'.$upload_image;
                        
            $imagelist = getimagesize($_FILES["upload_image"]['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2)
             {
                if($uploaded_width >=640 && $uploaded_height >= 480)
                {
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'category_photos/thumb/'.$upload_image;
                            $upload_target_android_medium = 'category_photos/android/medium/'.$upload_image;
                            $upload_target_medium = 'category_photos/medium/'.$upload_image;
                            $upload_target_web = 'category_photos/web/'.$upload_image;
                            
                            
                            $max_medium_width =  263;
                            $max_medium_height = 180;


                            $max_web_width = 464;
                            $max_web_height =293;
                                                              
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_android_medium,$uploaded_width, $uploaded_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_medium,$max_medium_width, $max_medium_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_web,$max_web_width, $max_web_height, 100, true);
                        
                                                                                    
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
                    $response['err_msg'] = 'Please upload a bigger image only';
                }
                
             }
             else
             {
                $response['is_error'] = 1;
                $response['err_msg'] = 'Please upload jpg,jpeg and gif image only';
             }
        
        }
        else
        {
            $response['is_error'] = 1;
            $response['err_msg'] = 'Please upload image';
        }
                
        if($response['is_error'] == 0)  
        {

        
                
                $this->data['Category']['title'] = $title;
                $this->data['Category']['category_desc'] = $desc;
                $this->data['Category']['image'] = $upload_image;
                $this->data['Category']['user_id'] = $user_id;
              
               
                $this->Category->create();
                if($this->Category->save($this->data))
                {
                    $insert_id = $this->Category->getLastInsertID();                    
                    $response['category_id'] = $insert_id;
                }
        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }


    function group_image_upload()
          
        {      //Configure::write('debug',3);
            $this->layout = ""; 
            $json = file_get_contents('php://input');
            $obj = json_decode($json);

            $group_id = $_POST['group_id'];
             $response = array();
         
            $response['is_error'] = 0;
            
            //$image_loop = 0;
            //foreach($upload_image['name'] as $image)
            $total_image = $_POST['upload_count'];
            for($i = 0;$i<$total_image;$i++)
            {
              if(isset($_FILES["upload_image".$i]['name']) && !empty($_FILES["upload_image".$i]['name']))
            {
            
              if(isset($_FILES["upload_image".$i]['name']) && $_FILES["upload_image".$i]['name']!= '')
              {
                $image_name = $_FILES["upload_image".$i]['name'];
        
                $extension = end(explode('.',$image_name));           
                $upload_image_name = time().accessCode(5).'.'.$extension;     
                $upload_target_original = 'gallery/'.$upload_image_name;
        
                $imagelist = getimagesize($_FILES["upload_image".$i]['tmp_name']);
                list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
              
                  if($type == 1 || $type == 2)
                  {
                    if($uploaded_width >=640 && $uploaded_height >= 480)
                    {
                      if(move_uploaded_file($_FILES["upload_image".$i]['tmp_name'], $upload_target_original)) 
                      {
                                                                                                        
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
                      if($this->GroupImage->save($this->data))
                       {
                         if($i == $total_image)
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
                         
                    }
          
                  }
                  else
                  {
                    
                    $response['is_error'] = 1;      
                   
                  }
                }
    
    
            

            if($response['is_error']==1)
              {
              $response['err_msg'] = 'All images could not be uploaded Please upload bigger images only';
              }
           }
           else
            {
              $response['is_error'] = 1;
              $response['err_msg'] = 'Please upload image';
            }
          }
            


            header('Content-type: application/json');
            echo json_encode($response);
            exit;

      }


      function get_group_images()
          
        {
            //Configure::write('debug',3);
            $this->layout = ""; 
             $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $json = file_get_contents('php://input');
            $response['is_error'] = 0;
            $obj = json_decode($json);

            $group_id = $obj->{'group_id'};
            //  $group_id = '1';

              $condn = "GroupImage.group_id = '".$group_id."'";
              $photo_list = $this->GroupImage->find('all',array('conditions'=>$condn));   
              if(!empty($photo_list))
              {
                foreach($photo_list as $key => $val)
              
            {   
              
             $response['group_image_url'][$key]['thumb'] = $base_url.'gallery/thumb/'.$val['GroupImage']['image'];
             $response['group_image_url'][$key]['medium'] = $base_url.'gallery/medium/'.$val['GroupImage']['image'];
             $response['group_image_url'][$key]['original'] = $base_url.'gallery/'.$val['GroupImage']['image']; 
                  
            }
          }
               else
            {
              $response['is_error'] = 1;
              $response['err_msg'] = 'No such group exist';
            }

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


}
?>