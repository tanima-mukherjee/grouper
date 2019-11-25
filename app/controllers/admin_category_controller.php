<?php
/**
 * Admin Dashboard Controller
 */
class AdminCategoryController extends AppController {

  var $name = 'AdminCategory';
  var $uses = array('Admin','Category','User','Group','Event','GroupUser','GroupImage','GroupDoc','Video');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array();

    
    function category_list() {
        
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
        $this->set('pageTitle', 'Category List');
      
     
     // $condition = "Category.status ='1'";
        $all_categories = $this->Category->find("all",array('order' => 'Category.id DESC'));


        $this->set('all_categories', $all_categories);
      
    }
    
  
  


    function add_category(){
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Add Category');
      
      
      if (isset($this->params['form']) && !empty($this->params['form'])) {

       $upload_image = '';
        
     if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= ''){
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'category_photos/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
            if($type == 1 || $type == 2 ){
             if($uploaded_width >=160 && $uploaded_height >= 120){
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
                         	$is_upload = 1;
                                                                                    
                      }       
                    else{
                        
                    $is_upload = 0;
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    $this->redirect(array('controller'=>'admin_category','action'=>'category_list'));
                
                    }
                
             }
             else{        
                 
                $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'admin_category','action'=>'category_list'));
                
                }
                
             }
            else{
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'admin_category','action'=>'category_list'));
              
             }
        
        }
       
        else
        {
            $is_upload = 0;
			$upload_image='';
            //$this->Session->setFlash(__d("statictext", "Please upload image", true));
            //$_SESSION['meesage_type'] = '0';
            //$this->redirect(array('controller'=>'admin_category','action'=>'category_list'));

        }
     // if( $is_upload == 1)
      //{
       
        $this->data['Category']['image'] = $upload_image;
        $this->data['Category']['title'] = $this->params['form']['cat_name'];
        $this->data['Category']['category_desc'] = $this->params['form']['cat_desc'];
        $this->Category->create();
        if($this->Category->save($this->data['Category']))
        {
          $this->Session->setFlash(__d("statictext", "Category Added Successfully !!.", true));
          $this->redirect("/admin_category/category_list"); 
        }
     // }
                   

        }
    }

    function edit_category($category_id){
	
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Edit Category');


      $conditions = "Category.id = '" . $category_id . "'";
      $CategoryDetails = $this->Category->find('first', array('conditions' => $conditions));
      $this->set('CategoryDetails', $CategoryDetails);

      
      
      if (isset($this->params['form']) && !empty($this->params['form'])) {
          $upload_image = '';
        
     if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= '')
        
        {
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'category_photos/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=160 && $uploaded_height >= 120)
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
                         $is_upload = 1;
                                                                                    
                      }         
                    else  
                    {
                        
                    $is_upload = 0;
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    $this->redirect(array('controller'=>'admin_category','action'=>'category_list'));
                
                    }
                
                }
                else
                {        
                 
                 $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'admin_category','action'=>'category_list'));
                
                }
                
             }
             else
             {
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'admin_category','action'=>'category_list'));
              
             }
        
        }
      
       else{

          $is_upload = 1;
          $upload_image = $CategoryDetails['Category']['image'];
      }
      if( $is_upload == 1)
      {
        $this->data['Category']['image'] = $upload_image;
        $this->data['Category']['title'] = $this->params['form']['cat_name'];
        $this->data['Category']['category_desc'] = $this->params['form']['cat_desc'];
         $this->data['Category']['id'] = $category_id;
         if($this->Category->save($this->data['Category']))
        {
          $this->Session->setFlash(__d("statictext", "Category Edited Successfully !!.", true));
          $this->redirect("/admin_category/category_list"); 
        }
      }
                   

        }
    }
	
	function delete_category() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');
      
  
      $categoryIDArrFinal = $_REQUEST['categoryIDArrFinal'];
      $categoryIDArray = explode(',', $categoryIDArrFinal);
	  
      foreach($categoryIDArray as $categoryID) {
        $this->Category->id = $categoryID;
        $this->Category->delete();
        
		$conditions = "Group.category_id = '".$categoryID."'"; 
		$ArAllGroup = $this->Group->find('all',array('conditions' => $conditions)); 
		foreach($ArAllGroup as $Group){
			 
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
		}
		
      }
      echo 'ok';
      exit();
    }
    

  /*   function edit_category($category_id) 
    {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Edit Category');


      $conditions = "Category.id = '" . $category_id . "'";
      $CategoryDetails = $this->Category->find('first', array('conditions' => $conditions));
      $this->set('CategoryDetails', $CategoryDetails);

      
      
      if (isset($this->params['form']) && !empty($this->params['form'])) {

     $upload_image="";

      $is_error=0;
      $file_type = array('image/jpeg', 'image/jpg', 'image/png');

      if (($this->params['form']['upload_image']['name'] != '') && (in_array($this->params['form']['upload_image']['type'], $file_type))) {

        $max_width = "640";

        $max_height = "360";

        $width = 0;

        $height = 0;

        $image_name = $this->params['form']['upload_image']['name'];

        $upload_image = time() . '_' . $image_name;

        $upload_target_thumb = 'category_photos/thumb/' . $upload_image;

       

        $upload_target_original = 'category_photos/' . $upload_image;
        $upload_target_medium = 'category_photos/medium/'.$upload_image;

        
        $max_medium_width =  579;
        $max_medium_height = 327;

        if(move_uploaded_file($this->params['form']['upload_image']['tmp_name'], $upload_target_original))
        {
         
          $this->imgOptCpy($upload_target_original, $upload_target_thumb, 150, 90, 100, true);
          $this->imgOptCpy($upload_target_original, $upload_target_medium,$max_medium_width, $max_medium_height, 100, true);

        }
        else{
           $is_error=1;
        }
        $this->data['Category']['image'] = $upload_image;
      }
       else{
          $this->data['Category']['image'] = $CategoryDetails['Category']['image'];
      }
      if( $is_error == 0)
      {
         $this->data['Category']['id'] = $category_id;
        $this->data['Category']['user_id'] = '0';
        $this->data['Category']['title'] = $this->params['form']['cat_name'];
        $this->data['Category']['category_desc'] = $this->params['form']['cat_desc'];
         if($this->Category->save($this->data['Category']))
        {
          $this->Session->setFlash(__d("statictext", "Category Edited Successfully !!.", true));
          $this->redirect("/admin_category/category_list"); 
        }
      }
                   

        }
    }
    */
    function edit_user($user_id) {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Edit User');
      
      $user_exists = $this->User->find('count', array('conditions'=>array('id'=>$user_id, 'isdeleted'=>'0')));
      
      if((!isset($user_id) && empty($user_id)) || ($user_exists == 0))
        $this->redirect(array('action' => 'user_list'));
      else {
    
    
    $conditions = "User.id = '" . $user_id . "'";
    $UserDetails = $this->User->find('first', array('conditions' => $conditions));
    //pr($UserDetails);    exit();

    $conditions = "Country.isdeleted = '0'";

    $ArCountry = $this->Country->find('all', array('conditions' => $conditions));



    $conditions = "State.isdeleted = '0' AND State.country_id = '" . $UserDetails['User']['country'] . "'";

    $ArState = $this->State->find('all', array('conditions' => $conditions));



    $conditions = "City.isdeleted = '0' AND City.state_id = '" . $UserDetails['User']['state'] . "'";

    $ArCity = $this->City->find('all', array('conditions' => $conditions));



    $this->set('UserDetails', $UserDetails);

    $this->set('ArCountry', $ArCountry);

    $this->set('ArState', $ArState);

    $this->set('ArCity', $ArCity);



    if (!empty($this->params['form']) && isset($this->params['form'])) {
        
             $ArUserDetails = $this->User->find('first', array('conditions' => array('User.id' => $user_id))); 
             
                  
              $this->data['User'] = $this->params['form'];
              $this->data['User']['id'] = $user_id;
              $this->data['User']['username'] = $this->params['form']['first_name'] . ' ' . $this->params['form']['last_name'];
              $this->data['User']['country'] = $this->params['form']['country_id'];
              $this->data['User']['state'] = $this->params['form']['state_id'];
              $this->data['User']['latitude'] = $this->params['form']['lat'];
              $this->data['User']['longitude'] = $this->params['form']['lng'];
              $this->data['User']['city'] = $this->params['form']['city_id'];
              if($this->User->save($this->data['User'])){
                 $this->Session->setFlash(__d("statictext", "Successfully changed!!.", true));
                  $this->redirect("/admin_product/user_list");
             }
        }
      }
    }

      function profile($profile_user_id=NULL){

         $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');


        $this->layout = "home_inner";
      
        $this->set('pageTitle', 'User Detail');
        $this->set('profile_user_id', $profile_user_id);

        $user_id = $this->Session->read('USER_USERID');
        $session_required_user_id = $user_id;
        $this->set('SessionRequiredUserId', $session_required_user_id);
        //$profile_user_id = 71;

        $this->User->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city'))));

        $conditions = "User.id = '".$profile_user_id."'";            
        $UserData = $this->User->find('first', array('conditions' => $conditions));

        if($UserData['User']['type'] == '1')    // customer
        {
            if ($this->Session->check('USER_USERID'))
            {
                $user_id = $this->Session->read('USER_USERID');
                $loggedin_user_type = $this->Session->read('userData.User.type');
                if($loggedin_user_type == '2')    // instructor viewing customer profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }  else if($loggedin_user_type == '3')    // facility viewing customer profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
            }
            
            
        }       
        else if($UserData['User']['type'] == '2')    // instructor
        {
            if ($this->Session->check('USER_USERID'))
            {
                $user_id = $this->Session->read('USER_USERID');
                $loggedin_user_type = $this->Session->read('userData.User.type');
                
                $this->UserReviewComment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.image,User.first_name,User.last_name'              ))));

              $this->UserReview->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'post_by'))));
              $this->UserReview->bindModel(array(
              'hasMany' => array(
              'UserReviewComment' => array('foreignKey' => 'review_id','order'=>'UserReviewComment.created Asc')               
              )
              ));
              
              $conditions = "UserReview.user_id = '".$profile_user_id."'";
            $this->UserReview->recursive = 2;
            $AllReview = $this->UserReview->find('all', array('conditions' => $conditions));
            //pr($user_id);
            //pr($AllReview);exit();
             $this->set('AllReview', $AllReview);

              $this->Class->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
              $condition = "Class.friend_id = '".$profile_user_id."'";
              $new_class_list = $this->Class->find('all', array('conditions'=>$condition,'order' => 'Class.id DESC')); 
             //pr($new_class_list);exit();
              $this->set('new_class_list', $new_class_list);
             

                if($loggedin_user_type == '3')    // facility viewing instructor profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
                  if($loggedin_user_type == '1')    // customer viewing instructor profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
            }
            
            
        }
        else if($UserData['User']['type'] == '3')    // facility
        {
            
            if ($this->Session->check('USER_USERID'))
            {
                $user_id = $this->Session->read('USER_USERID');
        $this->UserReviewComment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.image,User.first_name,User.last_name'))));
                        
                          $this->UserReview->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'post_by'))));
                          $this->UserReview->bindModel(array(
                          'hasMany' => array(
                          'UserReviewComment' => array('foreignKey' => 'review_id','order'=>'UserReviewComment.created Asc')               
                          )
                          ));
                          
                        $conditions = "UserReview.user_id = '".$profile_user_id."'";
                        $this->UserReview->recursive = 2;
                        $AllReview = $this->UserReview->find('all', array('conditions' => $conditions));
                        //pr($user_id);
                        //pr($AllReview);exit();
                         $this->set('AllReview', $AllReview);

                        $this->Class->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
                        $condition = "Class.friend_id = '".$profile_user_id."'";
                        $new_class_list = $this->Class->find('all', array('conditions'=>$condition,'order' => 'Class.id DESC')); 
                        $this->set('new_class_list', $new_class_list);
                        //pr($new_class_list);exit();

                                   
                      $loggedin_user_type = $this->Session->read('userData.User.type');
                      //pr($loggedin_user_type)   ;exit();
                      if($loggedin_user_type == '2')    // instructor viewing facility profile
                
                 {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
                               if($loggedin_user_type == '1')    // customer viewing facility profile
                {
                    // insert
                    $conditions = "UserFavourite.viewer_id = '".$user_id."' AND UserFavourite.user_id = '".$profile_user_id."'";            
                    $UserCount = $this->UserFavourite->find('count', array('conditions' => $conditions));
                    $UserDetail = $this->UserFavourite->find('first', array('conditions' => $conditions));
                    if($UserCount > 0)
                    {
                    $this->data['UserFavourite']['id']= $UserDetail['UserFavourite']['id'];
                    $this->UserFavourite->save($this->data['UserFavourite']);
                    }
                    else
                    {
                     $this->data['UserFavourite']['viewer_id']= $user_id;
                     $this->data['UserFavourite']['user_id']= $profile_user_id;
                     $this->UserFavourite->create();  
                     $this->UserFavourite->save($this->data['UserFavourite']);   
                    }
                    
                }
            }
        }

        $this->set('UserData', $UserData);

        $condition2 = "Job.user_id ='".$profile_user_id."' AND Job.status = '1'";
        $my_jobs = $this->Job->find('all',array('conditions'=>$condition2));
        $this->set('my_jobs', $my_jobs);
        
     if (isset($this->params['form']) && !empty($this->params['form'])) {

      //$user_review_id = $this->params['form']['inst_fac_name'];
      $post_by = $user_id;

      $rating = $this->params['form']['rating_value'];
      $title = $this->params['form']['title'];
      $review = $this->params['form']['review'];
      $recommend = $this->params['form']['recommend'];
      $profile_user_id = $this->params['form']['profile_user_id'];
      
      if(($profile_user_id)==($post_by))
      {
        $this->Session->setFlash(__d("statictext", "Sorry !!You cannot give review to Yourself.", true));
        $_SESSION['meesage_type'] = '1';
        $this->redirect(array('controller' => 'users', 'action' => 'profile', $profile_user_id));
      }
      else
      {
              $this->data['UserReview']['user_id'] = $profile_user_id;
              $this->data['UserReview']['post_by'] = $post_by;
              if(!empty($rating))
              {
                $this->data['UserReview']['rating'] = $rating;
              }
              else{
                $this->data['UserReview']['rating'] = 0;
              }
              
              $this->data['UserReview']['review'] = $review;
              $this->data['UserReview']['title'] = $title;
              $this->data['UserReview']['recommend'] = $recommend;
              $this->UserReview->create();
              $this->UserReview->save($this->data['UserReview']);
              //$this->Session->setFlash(__d("statictext", "Thanks For Your Review!!.", true));
              //$_SESSION['meesage_type'] ='1';
              //echo $profile_user_id;exit;
              
              $this->Session->setFlash(__d("statictext", "Thanks For Your Review!!.", true ));
              $_SESSION['meesage_type'] = '1';  
              $this->redirect(array('controller' => 'users', 'action' => 'profile', $profile_user_id)); 
              
         // $this->redirect("/users/profile/".$this->params['form']['popup-sender-id']);

         }
     }
     
     $this->UserReviewComment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id','fields'=>'User.image,User.first_name,User.last_name'))));
    
      $this->UserReview->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'post_by'))));
      $this->UserReview->bindModel(array(
      'hasMany' => array(
      'UserReviewComment' => array('foreignKey' => 'review_id','order'=>'UserReviewComment.created Asc')               
      )
      ));
      
      $conditions = "UserReview.user_id = '".$profile_user_id."'";
    $this->UserReview->recursive = 2;
    $AllReview = $this->UserReview->find('all', array('conditions' => $conditions));
    //pr($user_id);
    //pr($AllReview);exit();
     $this->set('AllReview', $AllReview);
     
    $this->Article->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
    $condition = "Article.user_id = '".$profile_user_id."' AND Article.is_published = '1'";
    $article_list = $this->Article->find('all', array('conditions'=>$condition));
    //pr($article_list);exit();
    $this->set('article_list', $article_list);
    
    
  }

  

    function view_user($user_id) {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'View User');
      
      $user_exists = $this->User->find('count', array('conditions'=>array('id'=>$user_id, 'isdeleted'=>'0')));
      
     
        
        $conditions = "User.id = '".$user_id."'";
        $this->User->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city'))), false);
        $this->User->bindModel(array('belongsTo' => array('Country' => array('foreignKey' => 'country'))), false);
        $this->User->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'state'))), false);
        $user_details =  $this->User->find('first',array('conditions' => $conditions));
        $this->set('user_details',$user_details);
       
    }

    function change_category_status() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

            
      $userID = $_REQUEST['userID'];
      $userStatus = $_REQUEST['userStatus'];
      
      if($userStatus == '1')
        $this->data['Category']['status'] = '0';
      else
        $this->data['Category']['status'] = '1';
      
      $this->Category->id = $userID;
      if($this->Category->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
    }
  
    function change_category_pog_status() {

      $adminData = $this->Session->read('adminData');
	  if(empty($adminData))
	  $this->redirect('/admins/login');

            
      $categoryID = $_REQUEST['categoryID'];
      $categoryPogStatus = $_REQUEST['categoryPogStatus'];
      
      if($categoryPogStatus == '1')
        $this->data['Category']['POG_status'] = '0';
      else
        $this->data['Category']['POG_status'] = '1';
      
      $this->Category->id = $categoryID;
      if($this->Category->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
    }
  
  
    
}

?>
