<?php
/**
 * Admin Dashboard Controller
 */
class DashboardController extends AppController {

  var $name = 'Dashboard';
  var $uses = array('Admin','User');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array();

  function index() {
    //Configure::write('debug', 2);
    $adminData = $this->Session->read('adminData');
    if(empty($adminData))
      $this->redirect('/admins/login');
    
    $this->layout = "";
    $this->set('pageTitle', 'Admin Dashboard');
  }
  
  function __index() {
    //Configure::write('debug', 2);
    $adminData = $this->Session->read('adminData');
    if(empty($adminData))
      $this->redirect('/admins/login');
    
    $this->layout = "";
    $this->set('pageTitle', 'Admin Dashboard');
    }


    function contact_detail() 
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

      $this->User->save($this->data['User']);

      $this->Session->setFlash(__d("statictext", "Successfully changed!!.", true));

      $this->redirect("/dashboard/contact_detail");

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
              $this->redirect("/dashboard/change_password");

              }
          }
     /* }*/
  }




  function check_password() {

    $user_id = $this->Session->read('USER_USERID');

    $cuurent_pwd = $_REQUEST['cuurent_pwd'];



    $conditions = "User.id = '" . $user_id . "' AND User.password = '" . md5($cuurent_pwd) . "'";

    $CheckPassword = $this->User->find('first', array('conditions' => $conditions));

    //pr($EmailCheck);

    if ($CheckPassword) {

      echo "1";

      exit();

    } else {

      echo "0";

      exit();

    }

  }



   function check_pswrd() {

    //$user_id = $this->Session->read('USER_USERID');

    //pr($_REQUEST['current_pwd'];)
    //pr($_REQUEST['userid'];) exit();

    $current_pwd = $_REQUEST['current_pwd'];
    $userid = $_REQUEST['userid'];



    $conditions = "User.id = '" . $userid . "' AND User.password = '" . md5($current_pwd) . "'";

    $CheckPassword = $this->User->find('first', array('conditions' => $conditions));

    //pr($CheckPassword);exit();

    if ($CheckPassword) {

      echo "1";

      exit();

    } else {

      echo "0";

      exit();

    }

  }





  function deleteimage() {

    $this->_checkSessionUser();
    $this->layout = "";
    $this->set('pageTitle', 'Delete Image');

    $user_id = $this->Session->read('USER_USERID');

    $condition = "User.id = '".$user_id."'";
    $user_detail = $this->User->find('first', array('conditions'=>$condition));
    
    if(count($user_detail) > 0)
    {
      $this->data['User']['image'] = '';
      $this->User->id = $user_id;
      $this->User->save($this->data['User']);
      $this->Session->setFlash(__d("statictext", "Image Successfully deleted!!.", true));

      $this->redirect("/dashboard/edit_photo");
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

    //pr($UserDetails);

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

        if ($this->User->save($this->data['User'])) {

          $this->Session->setFlash(__d("statictext", "Successfully changed!!.", true));

          $this->redirect("/dashboard/edit_photo");

        }

      }

    }

  }



     
}

?>
