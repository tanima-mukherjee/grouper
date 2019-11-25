<?php
/**
 * Admin Dashboard Controller
 */
class AdminTerritoryController extends AppController {

  var $name = 'AdminTerritory';
  var $uses = array('Admin','Category','User','Group','Event','GroupUser','GroupImage','GroupDoc','Video','State','City','TerritoryAssign','TerritoryMessage','EmailTemplate');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array();

    
    function list_all() {
        
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
        $this->set('pageTitle', 'Territory List');


        $conditions = "Admin.type = 'TA'";
        $fields = array('Admin.id','Admin.status','Admin.name','Admin.username','Admin.txt_password','Admin.state','Admin.city','Admin.address','City.name','State.name');
        $this->Admin->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'state'))));
        $this->Admin->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city'))));
        $all_territories = $this->Admin->find("all",array('conditions' => $conditions,'fields' =>$fields, 'order' => 'Admin.id DESC'));
        $this->set('all_territories', $all_territories);
      
    }
    
  

  function show_city(){

      $state_id = $_REQUEST['state_id'];
      $conditions = "City.state_id = '".$state_id."' AND City.isdeleted = '0'";
      $fields = array('City.id','City.name');
      $ArCity = $this->City->find('all',array('conditions' => $conditions, 'fields' => $fields));
      $this->set('ArCity', $ArCity);
  }
  


    function add(){
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Add Territory');


      $conditions = "State.country_id = '254' AND State.isdeleted = '0'";
      $fieldsS = array('State.id','State.name');
      $ArState = $this->State->find('all',array('conditions' => $conditions, 'fields' => $fieldsS));
      $this->set('ArState',$ArState);

      
      if (isset($this->params['form']) && !empty($this->params['form'])) {

       $upload_image = '';
        
     if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= ''){
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'territory_photos/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
            if($type == 1 || $type == 2 ){
             if($uploaded_width >=160 && $uploaded_height >= 120){
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'territory_photos/thumb/'.$upload_image;
                            $upload_target_android_medium = 'territory_photos/android/medium/'.$upload_image;
                            $upload_target_medium = 'territory_photos/medium/'.$upload_image;
                            $upload_target_web = 'territory_photos/web/'.$upload_image;
                            
                                                       
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
                    $this->redirect(array('controller'=>'admin_territory','action'=>'add'));
                
                    }
                
             }
             else{        
                 
                $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'admin_territory','action'=>'add'));
                
                }
                
             }
            else{
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'admin_territory','action'=>'add'));
              
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
       
        $this->data['Admin']['photo'] = $upload_image;
        $this->data['Admin']['name'] = $this->params['form']['name'];
        $this->data['Admin']['email'] = $this->params['form']['email'];
        $this->data['Admin']['username'] = $this->params['form']['username'];
        $this->data['Admin']['password'] = md5($this->params['form']['password'].'admin');
        $this->data['Admin']['txt_password'] = $this->params['form']['password'];
        $this->data['Admin']['phone_no'] = $this->params['form']['phone_no'];
        $this->data['Admin']['address'] = $this->params['form']['address'];
        $this->data['Admin']['state'] = $this->params['form']['state'];
        $this->data['Admin']['city'] = $this->params['form']['city'];
        $this->data['Admin']['zip'] = $this->params['form']['zip'];
        $this->Admin->create();
        if($this->Admin->save($this->data['Admin']))
        {
          $this->Session->setFlash(__d("statictext", "Territory Added Successfully !!.", true));
          $this->redirect("/admin_territory/list_all"); 
        }
     // }
                   

        }
    }

    function edit($territory_id=NULL){
	
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Edit Territory');


      $conditions = "State.country_id = '254' AND State.isdeleted = '0'";
      $fieldsS = array('State.id','State.name');
      $ArState = $this->State->find('all',array('conditions' => $conditions, 'fields' => $fieldsS));
      $this->set('ArState',$ArState);

      $conditions1 = "Admin.id = '" . $territory_id . "'";
      $fields = array('Admin.id','Admin.photo','Admin.name','Admin.email','Admin.txt_password','Admin.phone_no','Admin.username','Admin.state','Admin.city','Admin.address','Admin.zip','City.name','State.name');
      $this->Admin->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'state'))));
      $this->Admin->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'city'))));
      $TerritoryDetail = $this->Admin->find('first', array('conditions' => $conditions1, 'fields' => $fields));
      $this->set('TerritoryDetail', $TerritoryDetail);

      $conditions2 = "City.state_id = '".$TerritoryDetail['Admin']['state']."' AND City.isdeleted = '0'";
      $fieldsS = array('City.id','City.name');
      $ArCity = $this->City->find('all',array('conditions' => $conditions2, 'fields' => $fieldsS));
      $this->set('ArCity',$ArCity);

      


    if (isset($this->params['form']) && !empty($this->params['form'])) {
          $upload_image = '';
        
     if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= '')
        
        {
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'territory_photos/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=160 && $uploaded_height >= 120)
                {
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'territory_photos/thumb/'.$upload_image;
                            $upload_target_android_medium = 'territory_photos/android/medium/'.$upload_image;
                            $upload_target_medium = 'territory_photos/medium/'.$upload_image;
                            $upload_target_web = 'territory_photos/web/'.$upload_image;
                            
                                                        
                            $max_medium_width =  263;
                            $max_medium_height = 180;


                            $max_web_width = 464;
                            $max_web_height =293;
                                                              
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_android_medium,$uploaded_width, $uploaded_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_medium,$max_medium_width, $max_medium_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_web,$max_web_width, $max_web_height, 100, true);
                         
                         $is_upload = 1;


                         $this->removeFile($TerritoryDetail['Admin']['photo'],'territory_photos/');
                         $this->removeFile($TerritoryDetail['Admin']['photo'],'territory_photos/thumb/');
                         $this->removeFile($TerritoryDetail['Admin']['photo'],'territory_photos/android/medium/');
                         $this->removeFile($TerritoryDetail['Admin']['photo'],'territory_photos/medium/');
                         $this->removeFile($TerritoryDetail['Admin']['photo'],'territory_photos/web/');

                    }         
                    else  
                    {
                        
                    $is_upload = 0;
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    $this->redirect(array('controller'=>'admin_territory','action'=>'list_all'));
                
                    }
                
                }
                else
                {        
                 
                 $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'admin_territory','action'=>'edit', $territory_id));
                
                }
                
             }
             else
             {
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'admin_territory','action'=>'edit', $territory_id));
              
             }
        
        }
      
       else{

          $is_upload = 1;
          $upload_image = $TerritoryDetail['Admin']['photo'];


      }

          $this->data['Admin']['id'] = $TerritoryDetail['Admin']['id'];
          $this->data['Admin']['photo'] = $upload_image;
          $this->data['Admin']['name'] = $this->params['form']['name'];
          $this->data['Admin']['email'] = $this->params['form']['email'];
          $this->data['Admin']['username'] = $this->params['form']['username'];
          $this->data['Admin']['password'] = md5($this->params['form']['password'].'admin');
          $this->data['Admin']['txt_password'] = $this->params['form']['password'];
          #$this->data['Admin']['txt_password'] = $this->params['form']['password'].'admin';
          $this->data['Admin']['phone_no'] = $this->params['form']['phone_no'];
          $this->data['Admin']['address'] = $this->params['form']['address'];
          $this->data['Admin']['state'] = $this->params['form']['state'];
          $this->data['Admin']['city'] = $this->params['form']['city'];
          $this->data['Admin']['zip'] = $this->params['form']['zip'];
          if($this->Admin->save($this->data['Admin']))
          {
            $this->Session->setFlash(__d("statictext", "territory Edited Successfully !!.", true));
            $this->redirect("/admin_territory/list_all"); 
          }
        }
      }
	
	function delete_territory() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');
      
  
      $territoryIDArrFinal = $_REQUEST['categoryIDArrFinal'];
      $territoryIDArray = explode(',', $territoryIDArrFinal);
	  
      foreach($territoryIDArray as $territoryID) {

        $conditions = "Admin.id = '".$territoryID."'"; 
        $Territory = $this->Admin->find('all',array('conditions' => $conditions)); 

        //echo $territoryID;exit;

        $this->Admin->id = $territoryID;
        $this->Admin->delete();
        
    		
    		

              $this->removeFile($Territory['Admin']['photo'],'territory_photos/');
              $this->removeFile($Territory['Admin']['photo'],'territory_photos/thumb/');
              $this->removeFile($Territory['Admin']['photo'],'territory_photos/android/medium/');
              $this->removeFile($Territory['Admin']['photo'],'territory_photos/medium/');
              $this->removeFile($Territory['Admin']['photo'],'territory_photos/web/');


              $this->TerritoryAssign->deleteAll(['TerritoryAssign.territory_id'=>$territoryID]);
         
		
      }
      echo 'ok';
      exit();
    }
    

  

    function change_status() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

            
      $terID = $_REQUEST['terID'];
      $terStatus = $_REQUEST['terStatus'];
      
      if($terStatus == '1')
        $this->data['Admin']['status'] = '0';
      else
        $this->data['Admin']['status'] = '1';
      
      $this->Admin->id = $terID;
      if($this->Admin->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
    }
  
    



    function assign_territory($territory_id){
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Assign Territory');

      $this->set('territory_id',$territory_id);



      $conditions = "Admin.id = '" . $territory_id . "'";
      $fields = array('Admin.id');
      $TerritoryDetail = $this->Admin->find('first', array('conditions' => $conditions, 'fields' => $fields));
      $this->set('TerritoryDetail', $TerritoryDetail);


      $conditions1 = "TerritoryAssign.territory_id = '".$territory_id."'";
      $fields1 = array('State.id','State.name','TerritoryAssign.id','TerritoryAssign.territory_id');
      $this->TerritoryAssign->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'assign_state'))));
      //$this->TerritoryAssign->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'assign_city'))));
      $TerritoryAssign = $this->TerritoryAssign->find('all',array('conditions' => $conditions1, 'group' => 'TerritoryAssign.assign_state'));
      //pr($TerritoryAssign);exit;
      $this->set('TerritoryAssign',$TerritoryAssign);


  }

  function getCityList($territory_id,$state_id)
  {

      $conditions1 = "TerritoryAssign.territory_id = '".$territory_id."' AND TerritoryAssign.assign_state = '".$state_id."'";
      $fields1 = array('City.id','City.name','TerritoryAssign.id','TerritoryAssign.territory_id');
      //$this->TerritoryAssign->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'assign_state'))));
      $this->TerritoryAssign->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'assign_city'))));
      $TerritoryAssign = $this->TerritoryAssign->find('all',array('conditions' => $conditions1, 'fields' =>$fields1, 'order' => 'City.name ASC'));
      //pr($TerritoryAssign);exit;
      return $TerritoryAssign;
  }

  function search_state(){
    $this->layout = '';
    $state = $_REQUEST['state'];
    $territory_id = $_REQUEST['territory_id'];
    $conditions = "State.isdeleted = '0' AND State.name LIKE '%".$state."%' AND State.country_id = '254'";
    $ArSearchStates = $this->State->find('all', array('conditions'=> $conditions, 'order' => 'State.name ASC'));
    $this->set('ArSearchStates',$ArSearchStates);
    $this->set('territory_id',$territory_id);
  }
  


  function search_cities(){
    $this->layout = '';
    $state_id = $_REQUEST['state_id'];
    $territory_id = $_REQUEST['territory_id'];


    $conditions1 = "TerritoryAssign.assign_state = '".$state_id."'";
    //$conditions1 = "TerritoryAssign.territory_id = '1' AND TerritoryAssign.assign_state = '".$state_id."'";
    $fields1 = array('TerritoryAssign.assign_city');
    $TerritoryAssignCity = $this->TerritoryAssign->find('list',array('conditions' => $conditions1, 'fields' =>$fields1));
    $explode_city = implode(',', $TerritoryAssignCity);

    if(!empty($TerritoryAssignCity))
    {
        $conditions = "City.isdeleted = '0' AND City.state_id = '".$state_id."' AND City.id NOT IN (".$explode_city.")";
    }else{
        $conditions = "City.isdeleted = '0' AND City.state_id = '".$state_id."'";
    }
   
    
    $ArSearchCity = $this->City->find('all', array('conditions'=> $conditions));
    $this->set('ArSearchCity',$ArSearchCity);
  }


  function filter_city(){

    $this->layout = '';
    $state_id = $_REQUEST['state_id'];
    $city_name = $_REQUEST['city_name'];
    $city_ids = explode(',',$_REQUEST['city_ids']);
    //pr($city_ids);
    
    if($_REQUEST['city_name']!=''){
      $conditions = "City.isdeleted = '0' AND City.state_id = '".$state_id."' AND City.name LIKE '%".$city_name."%'";
    }else{
      $conditions = "City.isdeleted = '0' AND City.state_id = '".$state_id."'";
    }
    
    $ArSearchCity = $this->City->find('all', array('conditions'=> $conditions));
    $this->set('ArSearchCity',$ArSearchCity);
    $this->set('city_ids',$city_ids);
  }
  

  function save_assign_territory()
  {
    $this->layout = '';
    $state_id = $_REQUEST['state_id'];
    $city_ids = $_REQUEST['city_ids'];
    $territory_id = $_REQUEST['territory_id'];

      $explode_city_ids = explode(',', $city_ids);
      
      if($state_id!='' && $city_ids!=''){      
          foreach ($explode_city_ids as $city_id) {
            
                $this->data['TerritoryAssign']['territory_id'] = $territory_id;
                $this->data['TerritoryAssign']['assign_state'] = $state_id;
                $this->data['TerritoryAssign']['assign_city'] = $city_id;
                $this->TerritoryAssign->create();
                $this->TerritoryAssign->save($this->data['TerritoryAssign']);
          }
      }


      $conditions1 = "TerritoryAssign.territory_id = '".$territory_id."'";
      $fields1 = array('State.id','State.name','TerritoryAssign.id','TerritoryAssign.territory_id');
      $this->TerritoryAssign->bindModel(array('belongsTo' => array('State' => array('foreignKey' => 'assign_state'))));
      //$this->TerritoryAssign->bindModel(array('belongsTo' => array('City' => array('foreignKey' => 'assign_city'))));
      $TerritoryAssign = $this->TerritoryAssign->find('all',array('conditions' => $conditions1, 'group' => 'TerritoryAssign.assign_state'));
      //pr($TerritoryAssign);exit;
      $this->set('TerritoryAssign',$TerritoryAssign);

      //echo "1";
      //exit;

  }


  function delete_state()
  {
    $this->layout = '';
    $state_id = $_REQUEST['state_id'];
    $territory_id = $_REQUEST['territory_id'];
    $conditions = "TerritoryAssign.territory_id = '".$territory_id."' AND TerritoryAssign.assign_state = '".$state_id."'";

    //$this->TerritoryAssign->deleteAll(array('conditions'=>$conditions),false);

    $this->TerritoryAssign->deleteAll(['TerritoryAssign.territory_id'=>$territory_id,'TerritoryAssign.assign_state'=>$state_id]);
      
    echo "1";
    exit;

  }


  function delete_city()
  {
    $this->layout = '';
    
    $assign_city_id = $_REQUEST['assign_city_id'];

    $this->TerritoryAssign->id = $assign_city_id;
    $this->TerritoryAssign->delete();
      
    echo "1";
    exit;

  }



  function send_message() 
    {
          
        $this->layout = ""; 
        $defaultconfig = $this->getDefaultConfig();      
        $base_url = $defaultconfig['base_url'];
        $sitesettings = $this->getSiteSettings();

        $adminData = $this->Session->read('adminData');
        $admin_type = $this->Session->read('admin_type');
        

        $territory_id = $_REQUEST['territory_id'];
        $message = $_REQUEST['message'];

        $this->data['TerritoryMessage']['territory_id'] = $territory_id;
        $this->data['TerritoryMessage']['message'] = $message;
        $this->TerritoryMessage->create();
        if($this->TerritoryMessage->save($this->data['TerritoryMessage']))
        {

          $condition = "Admin.id = '".$territory_id."'";
          $receiver_details = $this->Admin->find('first', array('conditions'=>$condition));

            $user_name = $receiver_details['Admin']['name'];
            $admin_sender_email = $sitesettings['site_email']['value'];
            $site_url = $sitesettings['site_url']['value'];
            $sender_name = 'Administrator';
            $message = stripslashes($message);

            $condition = "EmailTemplate.id = '19'";
            $mailDataRS = $this->EmailTemplate->find("first", array('conditions' => $condition));
            
            $to = $receiver_details['Admin']['email'];

            
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

            echo 'ok';    
            exit;         
            
        }
    }



    function inbox() {
        
      $adminData = $this->Session->read('adminData');
      $territory_id = $this->Session->read('adminData.Admin.id');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
        $this->set('pageTitle', 'Inbox');


        $conditions = "TerritoryMessage.territory_id = '".$territory_id."'";
        $territory_messages = $this->TerritoryMessage->find("all",array('conditions' => $conditions, 'order' => 'TerritoryMessage.created DESC'));
        $this->set('territory_messages', $territory_messages);
      
    }


    function delete_message() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');
      
  
      $territoryIDArrFinal = $_REQUEST['categoryIDArrFinal'];
      $territoryIDArray = explode(',', $territoryIDArrFinal);
    
      foreach($territoryIDArray as $territoryMessageID) {

        $this->TerritoryMessage->id = $territoryMessageID;
        $this->TerritoryMessage->delete();

      }
      echo 'ok';
      exit();
    }



    function view_message($territory_message_id) {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'View Message');
      
      
      $conditions = "TerritoryMessage.id = '".$territory_message_id."'";
      $message_details =  $this->TerritoryMessage->find('first',array('conditions' => $conditions));
      $this->set('message_details',$message_details);
     
    }
    
}

?>
