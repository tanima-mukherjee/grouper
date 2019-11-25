<?php
/**
 * Admin Dashboard Controller
 */
class AdminEventController extends AppController {

  var $name = 'AdminEvent';
  var $uses = array('Admin','Event','User','Category','Group','State','City','TerritoryAssign');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array();

    
    function event_list() {
        
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
      $this->set('pageTitle', 'Event List');
      
    $selected_state_id = '';
    $selected_city_id = '';
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

             $fields = array('Group.id');
             $conditions_group = "Group.state_id IN (".$str_states.") AND Group.city_id IN (".$str_city.") ";
             $all_groups = $this->Group->find('list',array('conditions' => $conditions_group, 'fields' => $fields)); 
             

             $str_groups = implode(',', $all_groups);
          }

          $conditions_state = "State.id IN (".$str_states.") AND State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

          $con = "Event.group_id IN (".$str_groups.")";
          $all_events = $this->Event->find('all',array('conditions' => $con,'order' => 'Event.id DESC'));  

          //pr($all_events);exit;

          $this->set('city_list',$city_list); 
          $this->set('ArState', $ArState);
      }
      else
      {
          $conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
          $ArState = $this->State->find('all', array('conditions' => $conditions_state));

          $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
          $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));


          $this->Event->bindModel(array('belongsTo'=>array(
          'Group'=>array('foreignKey'=>'group_id',
          'fields'=>'Group.id,Group.group_title')
          )));
          $this->Event->bindModel(array('belongsTo'=>array(
          'User'=>array('foreignKey'=>'created_by_owner_id',
          'fields'=>'User.id,User.fname,User.lname')
          )));
          $this->Event->bindModel(array('belongsTo'=>array(
          'Category'=>array('foreignKey'=>'category_id',
          'fields'=>'Category.id,Category.title')
          )));
          $all_events = $this->Event->find('all',array('order' => 'Event.id DESC'));  


          $this->set('city_list',$city_list); 
          $this->set('ArState', $ArState);
      }
      

      /*$conditions_state = "State.isdeleted = '0' AND State.country_id = '254'";
      $ArState = $this->State->find('all', array('conditions' => $conditions_state));*/

      
        
        if(isset($this->params['form']['group_type_select']) && $this->params['form']['group_type_select'] != '')
        {

            //echo $this->params['form']['group_type_select'];exit;
            $this->Event->bindModel(array('belongsTo'=>array(
            'Group'=>array('foreignKey'=>'group_id',
            'fields'=>'Group.id,Group.group_title')
            )));
            $this->Event->bindModel(array('belongsTo'=>array(
            'User'=>array('foreignKey'=>'created_by_owner_id',
            'fields'=>'User.id,User.fname,User.lname')
            )));
            $this->Event->bindModel(array('belongsTo'=>array(
            'Category'=>array('foreignKey'=>'category_id',
            'fields'=>'Category.id,Category.title')
            )));
            $conditions_event = "Event.group_type = '".$this->params['form']['group_type_select']."'";
            $all_events = $this->Event->find('all',array('conditions'=>$conditions_event,'order' => 'Event.id DESC')); 
            /*pr($all_events);
            exit;*/

        }
        /*else
        {
            $this->Event->bindModel(array('belongsTo'=>array(
            'Group'=>array('foreignKey'=>'group_id',
            'fields'=>'Group.id,Group.group_title')
            )));
            $this->Event->bindModel(array('belongsTo'=>array(
            'User'=>array('foreignKey'=>'created_by_owner_id',
            'fields'=>'User.id,User.fname,User.lname')
            )));
            $this->Event->bindModel(array('belongsTo'=>array(
            'Category'=>array('foreignKey'=>'category_id',
            'fields'=>'Category.id,Category.title')
            )));
            $all_events = $this->Event->find('all',array('order' => 'Event.id DESC'));
        }*/
       //pr($all_events);exit();
       

      if(isset($this->params['form']['group_type_select'])){
      $this->set('groupt_select',$this->params['form']['group_type_select']);
      }else{
       $this->set('groupt_select',''); 
      }

      $total_business_group = 0;
      $total_private_group = 0;

      if(isset($this->params['form']['index_city_id']) && $this->params['form']['index_city_id'] != '')
        {
              $conditions_group = "Group.city_id = '".$this->params['form']['index_city_id']."' AND Group.state_id = '".$this->params['form']['state_id']."' ";
              $all_groups = $this->Group->find("all",array('conditions'=>$conditions_group));
              // $str_group_owners= implode(',', $all_groups);
              $group = array(); 
              foreach($all_groups as $grp)
              {

              array_push($group,$grp['Group']['id']);
              }
              $allstatecitygroups = implode(",",$group);

              if(!empty($allstatecitygroups))
              {
              $condition_of_events = "Event.group_id  IN (".$allstatecitygroups.") ";
              $this->Event->bindModel(array('belongsTo'=>array(
              'Group'=>array('foreignKey'=>'group_id',
              'fields'=>'Group.id,Group.group_title')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'User'=>array('foreignKey'=>'created_by_owner_id',
              'fields'=>'User.id,User.fname,User.lname')
              )));
              $this->Event->bindModel(array('belongsTo'=>array(
              'Category'=>array('foreignKey'=>'category_id',
              'fields'=>'Category.id,Category.title')
              )));

              $all_events = $this->Event->find('all',array('conditions'=>$condition_of_events,'order' => 'Event.id DESC')); 
              }
              else
              {
              $all_events = array();
              }

              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];

              $business_group = array();
              $private_group = array();

              foreach ($all_events as $key => $value) {
              if($value['Event']['group_type'] == 'B')
              {
              array_push($business_group, $value['Event']['id']);
              }
              if($value['Event']['group_type'] == 'F')
              {
              array_push($private_group, $value['Event']['id']);
              }
              }
              $total_business_group = count($business_group);
              $total_private_group = count($private_group);


              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));



        }
        /*else
        {
          
          $this->Event->bindModel(array('belongsTo'=>array(
          'Group'=>array('foreignKey'=>'group_id',
          'fields'=>'Group.id,Group.group_title')
          )));
          $this->Event->bindModel(array('belongsTo'=>array(
          'User'=>array('foreignKey'=>'created_by_owner_id',
          'fields'=>'User.id,User.fname,User.lname')
          )));
          $this->Event->bindModel(array('belongsTo'=>array(
          'Category'=>array('foreignKey'=>'category_id',
          'fields'=>'Category.id,Category.title')
          )));


          
        }*/


      $this->set('city_list',$city_list); 
      $this->set('ArState', $ArState);
      $this->set('total_business_group',$total_business_group);
      $this->set('total_private_group',$total_private_group);
      $this->set('all_events', $all_events);
      $this->set('SelectedStateId', $selected_state_id);
      $this->set('SelectedCityId', $selected_city_id);
      /*$condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
      $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));*/

  }
    


       function edit_event($event_id) 
    {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'Edit Event');

     $conditions_event = "Event.id = '".$event_id."'";
      $this->Event->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id'))), false);
      $this->Event->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'created_by_owner_id'))), false);
      $this->Event->bindModel(array('belongsTo' => array('Category' => array('foreignKey' => 'category_id'))), false);
      $event_details =  $this->Event->find('first',array('conditions' => $conditions_event));
      // pr($event_details);exit();
      $this->set('event_details',$event_details);


       if($event_details['Event']['group_id']!=" ")
      {
        $condition_event = "Group.group_type='".$event_details['Event']['group_type']."' AND Group.status = '1'";
        $selectedgrouplist = $this->Group->find("all",array('conditions'=>$condition_event,'order' => array('Group.id DESC')));
       // pr($citylist);exit();
       $this->set('selectedgrouplist', $selectedgrouplist); 
      }

      
      
      if (isset($this->params['form']) && !empty($this->params['form'])) {
       
                  $this->data['Event']['id'] =  $event_details['Event']['id'];
                  $this->data['Event']['group_id'] =  $this->params['form']['group_name'];
                  $this->data['Event']['group_type'] =  $this->params['form']['new_group_type'];
                  $this->data['Event']['title'] =  addslashes($this->params['form']['title']);
                  $this->data['Event']['desc'] = addslashes($this->params['form']['desc']);
                  $this->data['Event']['location'] = $this->params['form']['address'];
                  $this->data['Event']['latitude'] = $this->params['form']['lat'];
                  $this->data['Event']['longitude'] = $this->params['form']['lng'];
                  $this->data['Event']['place_id'] = $this->params['form']['place_id'];

                   
                   
                   $con_category_detail = "Group.id = '".$this->params['form']['group_name']."' AND Group.status = '1'";
                   $category_detail = $this->Group->find('first',array('conditions'=>$con_category_detail));

                  $this->data['Event']['category_id'] = $category_detail['Group']['category_id']; 
                    if($this->params['form']['new_group_type']=='B')
                   {
                    $this->data['Event']['deal_amount'] = $this->params['form']['amount'];
                    $this->data['Event']['type'] = 'public';
                    
                   }
                   else if($this->params['form']['new_group_type']=='F')
                   {
                    $this->data['Event']['deal_amount'] = '0.00';
                    $this->data['Event']['type'] = $this->params['form']['group_type'];
                   }      
                  
                  if($this->params['form']['is_multiple'] == '1')     
                  {
                    $this->data['Event']['event_timestamp'] = '0';
                    $this->data['Event']['event_date'] = '0000-00-00'; 
                    $this->data['Event']['event_start_timestamp'] = strtotime($this->params['form']['event_start_date']);
                    $this->data['Event']['event_end_timestamp'] = strtotime($this->params['form']['event_end_date']);
                    $this->data['Event']['is_multiple_date'] = $this->params['form']['is_multiple'];
                    $this->data['Event']['event_start_date'] = date("Y-m-d",strtotime($this->params['form']['event_start_date']));  
                    $this->data['Event']['event_end_date'] = date("Y-m-d",strtotime($this->params['form']['event_end_date']));  
                  }    
                  else
                  {
                    $this->data['Event']['event_date'] = date("Y-m-d",strtotime($this->params['form']['event_date'])); 
                    $this->data['Event']['event_timestamp'] = strtotime($this->params['form']['event_date']);
                    $this->data['Event']['event_start_timestamp'] = '0';
                    $this->data['Event']['event_end_timestamp'] = '0';
                    $this->data['Event']['event_start_date'] = '0000-00-00';
                    $this->data['Event']['event_end_date'] = '0000-00-00';
                    $this->data['Event']['is_multiple_date'] = $this->params['form']['is_multiple'];
                  }

        
         if($this->Event->save($this->data['Event']))
        {
          $this->Session->setFlash(__d("statictext", "Event Edited Successfully !!.", true));
          $this->redirect("/admin_event/event_list/".$event_details['Event']['group_id']);
        }
     
                   

        }
    }
    

   
  

    function view_event($event_id) {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'View Event');
      
      
      $conditions_event = "Event.id = '".$event_id."'";
      $this->Event->bindModel(array('belongsTo' => array('Group' => array('foreignKey' => 'group_id'))), false);
      $this->Event->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'created_by_owner_id'))), false);
      $this->Event->bindModel(array('belongsTo' => array('Category' => array('foreignKey' => 'category_id'))), false);
      $event_details =  $this->Event->find('first',array('conditions' => $conditions_event));
      // pr($event_details);exit();
      $this->set('event_details',$event_details);
     
    }

    function change_event_status() {

       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

      $userID = $_REQUEST['userID'];
      $userStatus = $_REQUEST['userStatus'];
      
      if($userStatus == '1')
        $this->data['Event']['status'] = '0';
      else
        $this->data['Event']['status'] = '1';
      
      $this->Event->id = $userID;
      if($this->Event->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
    }


    function show_group()
    {
       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');

		$this->layout = "";
    	$group_type = $_REQUEST['group_type'];

    	$condition_event = "Group.group_type='".$group_type."' AND Group.status = '1'";
        $selectedgrouplist = $this->Group->find("all",array('conditions'=>$condition_event,'order' => array('Group.id DESC')));
       //pr($selectedgrouplist);
    	$this->set('selectedgrouplist', $selectedgrouplist); 

    }

    function delete_event() 
    {
       $adminData = $this->Session->read('adminData');
            if(empty($adminData))
              $this->redirect('/admins/login');
            
      $categoryIDArrFinal = $_REQUEST['categoryIDArrFinal'];
      $categoryIDArray = explode(',', $categoryIDArrFinal);
      
      foreach($categoryIDArray as $categoryID) 
      {
        //pr($categoryID);
        $this->Event->id = $categoryID;
        $this->Event->delete();
      }
      
      echo 'ok';
      exit();
    }




  function delete_this_event()
  {
    $event_id = $_REQUEST['event_id'];
    $this->Event->id = $event_id;
    $this->Event->delete();
    echo "1";
    exit();
  }

    
}

?>
