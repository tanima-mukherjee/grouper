<?php
/**
 * Admin Dashboard Controller
 */
class UsersController extends AppController {

  var $name = 'Users';
  var $uses = array('Admin','Category','User','EmailTemplate','Notification','State','City','TerritoryAssign','Group');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array("Email");

    
    function user_list() {
        
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
      $this->set('pageTitle', 'User List');

      $selected_state_id = '';
      $selected_city_id = '';
      $total_user = '';


      if($this->Session->read('admin_type') == 'TA')
      {

          $territory_id = $this->Session->read('adminData.Admin.id');
          $con = "TerritoryAssign.territory_id = '".$territory_id."'";
          $fields = array('TerritoryAssign.assign_state','TerritoryAssign.assign_city');
          $ArTerritoryStateCity = $this->TerritoryAssign->find('all',array('conditions' => $con, 'fields' => $fields));
          
          if(!empty($ArTerritoryStateCity))
          {
            $ar_states = array();
            $ar_city = array();
            foreach ($ArTerritoryStateCity as $key => $value) {
                array_push($ar_states, $value['TerritoryAssign']['assign_state']);
                array_push($ar_city, $value['TerritoryAssign']['assign_city']);
            }

            $ar_states = array_unique($ar_states);
            $ar_city = array_unique($ar_city);


            $str_states = implode(',', $ar_states);
            $str_city = implode(',', $ar_city);
          }


          $conditions_state = "State.id IN (".$str_states.") AND State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          $con = "User.state_id IN (".$str_states.") AND User.city_id IN (".$str_city.")";
          $all_users = $this->User->find('all',array('conditions' => $con));

      }
      else
      {
          $conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          $all_users = $this->User->find('all');

          
      }
      
    
    if(isset($this->params['form']['index_city_id']) && $this->params['form']['index_city_id'] != '')
        {
          $conditions_user = "User.city_id = '".$this->params['form']['index_city_id']."' AND User.state_id = '".$this->params['form']['state_id']."' ";
          $all_users = $this->User->find("all",array('conditions'=>$conditions_user,'order' => 'User.fname ASC'));
          $user_state_city = $this->User->find("first",array('conditions'=>$conditions_user));
          $selected_state_id = $this->params['form']['state_id'];
          $selected_city_id = $this->params['form']['index_city_id'];
          $total_user = count($all_users);
          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
        }
        /*else
        {
 
          $selected_state_id = '';
          $selected_city_id = '';
          $total_user = '';
        }*/


      

     /* $conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
      $ArState = $this->State->find('all', array('conditions' => $conditions_state));

      $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
      $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
      $this->set('city_list',$city_list); 
      $this->set('ArState', $ArState);*/

      $this->set('all_users', $all_users);
      $this->set('city_list',$city_list); 
      $this->set('ArState', $ArState);
      $this->set('total_user',$total_user);
      $this->set('SelectedStateId', $selected_state_id);
      $this->set('SelectedCityId', $selected_city_id);
      
    }
    
    
    
    function add_user() {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Add User');
      
        $conditions = "Country.isdeleted = '0'";
        $ArCountry = $this->Country->find('all', array('conditions' => $conditions));
        $this->set('ArCountry', $ArCountry);
      
      if (isset($this->params['form']) && !empty($this->params['form'])) {
            
            //echo '<pre>';print_r($this->params['form']);exit();
            $sitesettings = $this->getSiteSettings();

            $user_name = $this->params['form']['first_name'] . ' ' . $this->params['form']['last_name'];
            $email = $this->params['form']['email'];
            $txt_password = $this->params['form']['password'];
            $password = md5($this->params['form']['password']);

            $countUser = $this->User->find('count', array('conditions' => array('User.email' => $email, 'User.isdeleted' => '0', 'User.status' => '1')));

            if ($countUser > 0) {
                $this->Session->setFlash(__d("statictext", "Email-id already exists.", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect("/signup");
                
            } else {

                $email = $this->params['form']['email'];
                $username = $this->params['form']['first_name'] . ' ' . $this->params['form']['last_name'];
                $password = md5($this->params['form']['password']);
                $txt_password = $this->params['form']['password'];
                $this->data['User'] = $this->params['form'];
                $this->data['User']['username'] = $this->params['form']['first_name'] . ' ' . $this->params['form']['last_name'];
                $this->data['User']['address'] = $this->params['form']['location'];
                $this->data['User']['country'] = $this->params['form']['country_id'];
                $this->data['User']['state'] = $this->params['form']['state_id'];
                $this->data['User']['latitude'] = $this->params['form']['lat'];
                $this->data['User']['longitude'] = $this->params['form']['lng'];
                $this->data['User']['city'] = $this->params['form']['city_id'];
                $this->data['User']['password'] = $password;
                $this->data['User']['txt_password'] = $txt_password;
                if ($this->User->save($this->data)) {
                    $this->Session->setFlash('User Successfully Added!');
                    $this->redirect(array('action' => 'user_list'));
                }
            }
        }
    }
    
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

    function change_user_status() {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');

      $userID = $_REQUEST['userID'];
      $userStatus = $_REQUEST['userStatus'];
      
      if($userStatus == '1')
        $this->data['User']['status'] = '0';
      else
        $this->data['User']['status'] = '1';
      
      $this->User->id = $userID;
      if($this->User->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
    }

    //$this->data['Notification']['sender_id'] = $user_id;

    function check_password() {

      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');

    
    $current_pwd = $_REQUEST['current_pwd'];
    $id_user = $_REQUEST['id_user'];



    $conditions = "User.id = '" . $id_user . "' AND User.password = '" . md5($current_pwd) . "'";

    $CheckPassword = $this->User->find('first', array('conditions' => $conditions));

    //pr($CheckPassword);

      if ($CheckPassword) {

        echo "1";

        exit();

      } else {

        echo "0";

        exit();

      }

    }


    function update_password() 
    {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $sitesettings = $this->getSiteSettings();

            if(!empty($this->params['form']) && isset($this->params['form'])){
              //pr($this->params['form']);exit();
            $this->data['User']['id'] = $this->params['form']['receiver_id'];
            $this->data['User']['txt_password'] = $this->params['form']['con_pwd']; 
            $this->data['User']['password'] = md5($this->params['form']['con_pwd']); 
               
          
            if($this->User->save($this->data['User']))
            {

              $condition_receiver_detail = " User.id = '".$this->params['form']['receiver_id']."' AND User.status = '1' ";
              $receiver_details = $this->User->find('first', array('conditions'=>$condition_receiver_detail));

                 

              $user_name = $receiver_details['User']['fname'].' '.$receiver_details['User']['lname']; 
                $admin_sender_email = $sitesettings['site_email']['value'];
                $site_url = $sitesettings['site_url']['value'];
                $sender_name = "Admin";
                
                $condition = "EmailTemplate.id = '22'";
                $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
                
                $to = $receiver_details['User']['email'];
                $password = $this->params['form']['con_pwd'];

                
                $user_subject = $mailDataRS['EmailTemplate']['subject'];
                $user_subject = str_replace('[SITE NAME]', 'Grouper | Admin Message', $user_subject);
                       
               
                $user_body = $mailDataRS['EmailTemplate']['content'];
                $user_body = str_replace('[USERNAME]', $user_name, $user_body);
                $user_body = str_replace('[PASSWORD]', $password, $user_body);
                               
                         
                $user_message = stripslashes($user_body);
                
             
                 $string = '';
                 $filepath = '';
                 $filename = '';
                 $sendCopyTo = '';
             
             
                $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$user_message,$string,$filepath,$filename,$sendCopyTo);                 
                     // exit;
               if($sendmail)
                {
                $this->Session->setFlash("Your password is updated. ");    
                $_SESSION['meesage_type'] = '1';
                   $this->redirect(array('controller'=>'users','action'=>'user_list'));

                
                }
                else
                {
                $this->Session->setFlash("Failed to send email.");    
                $_SESSION['meesage_type'] = '1';
                  $this->redirect(array('controller'=>'users','action'=>'user_list'));
                }
                $this->redirect(array('controller'=>'users','action'=>'user_list'));
              
            }
          }
          else{
         $this->Session->setFlash(__d("statictext", "No message sent", true));
         $_SESSION['meesage_type'] = '0';
         $this->redirect(array('controller'=>'users','action'=>'user_list'));
          } 
  

      
    }

    function send_message() 
    {
           $adminData = $this->Session->read('adminData');
           if(empty($adminData))
              $this->redirect('/admins/login');

            $this->layout = ""; 
            $defaultconfig = $this->getDefaultConfig();      
            $base_url = $defaultconfig['base_url'];
            $sitesettings = $this->getSiteSettings();

            $receiver_id = $_REQUEST['receiver_id'];
            $message = $_REQUEST['message'];

            $this->data['Notification']['sender_id'] = '0';
            $this->data['Notification']['type'] = 'P';
            $this->data['Notification']['group_id'] = '0';
            $this->data['Notification']['message'] = addslashes($message);              
            $this->data['Notification']['receiver_id'] = $receiver_id;
            $this->data['Notification']['receiver_type'] = 'NGM';
            $this->data['Notification']['sender_type'] = 'NGM';
            $this->data['Notification']['is_read'] = 0;
            $this->data['Notification']['is_receiver_accepted'] = 2;
            $this->data['Notification']['is_reversed_notification'] = 0;
            $this->data['Notification']['status'] = 1;
            
            $this->Notification->create();
            if($this->Notification->save($this->data['Notification']))
            {

                  $condition_receiver_detail = " User.id = '".$receiver_id."'";
                  $receiver_details = $this->User->find('first', array('conditions'=>$condition_receiver_detail));
                  
                  $user_name = $receiver_details['User']['fname'].' '.$receiver_details['User']['lname']; 
                  $admin_sender_email = $sitesettings['site_email']['value'];
                  $site_url = $sitesettings['site_url']['value'];
                  $sender_name = 'Administrator';
                  $message = stripslashes($message);

                  $condition = "EmailTemplate.id = '19'";
                  $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));

                  $to = $receiver_details['User']['email'];

                  $user_subject = $mailDataRS['EmailTemplate']['subject'];
                  $user_subject = str_replace('[SITE NAME]', 'Grouper | Admin Message', $user_subject);

                  $user_body = $mailDataRS['EmailTemplate']['content'];
                  $user_body = str_replace('[USERNAME]', $user_name, $user_body);
                  $user_body = str_replace('[SENDERNAME]', $sender_name, $user_body);
                  $user_body = str_replace('[MESSAGE]', $message, $user_body);

                  $user_message = stripslashes($user_body);
                  
                  $string = '';
                  $filepath = '';
                  $filename = '';
                  $sendCopyTo = '';

                  $sendmail = sendmail($admin_sender_email,$sender_name,$to,$user_subject,$message,$string,$filepath,$filename,$sendCopyTo);                 
                  // exit;
                  if($sendmail)
                  {

                      $page="Push message from Admin";
                      $message= "Admin has sent you push notification \n".$message; 

                      $this->send_push_notification($message,$receiver_details['User']['device_token'],$page);

                      echo 'ok';
                  }
                  else
                  {
                      echo 'err';
                  }
            }
            else
            {
                echo 'err';
            }

            exit;
    }

  

  function getUserGrpCount($user_id)
  {
      $conditions = "Group.created_by = '".$user_id."' AND Group.status = '1'";
      $ArGroupCount = $this->Group->find('count',array('conditions' => $conditions));
      return $ArGroupCount;
  }
	
	function send_push_notification($message=NULL,$registatoin_ids=NULL,$page=NULL){
        /*$message = 'Sajjad Mistri invited you to join Private group Alovera';
        $registatoin_ids ='dKH2FWX6DtQ:APA91bHchG6PmkkWWic1S_BcBwiYPMt1dFjhlbilgQ1wXv0symPqwsNqHkFLBEClgb88YMa92weHi-JDefahsIESD3Ib5Wg2TKEeprwAjQYxsX9Nej60ghr8czb_qnisnSY3WmBZtusD';
        $page = 'Group Joining Invitation To Friends';
*/
        //$this->_checkSession();
        $messageArr = array();       
        $messageArr['to']  =  $registatoin_ids;       
        $messageArr['notification']['title']  =  $page;     
        $messageArr['notification']['body']   =  $message;
      
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


    
}

?>
