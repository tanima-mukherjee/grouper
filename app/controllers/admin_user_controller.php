<?php
/**
 * Admin Dashboard Controller
 */
class AdminUserController extends AppController {

  var $name = 'AdminUser';
  var $uses = array('Admin','User','Group','GroupUser','State','City','TerritoryAssign','GroupMessage','GroupMessageReply','GroupDoc','GroupImage','Video','Friendlist','Event');
  var $helpers = array('Html', 'Form', 'Javascript', 'Fck');
  var $components = array('CustomAvi');


  function delete_user()
  {
    $user_id = $_REQUEST['user_id'];
    /*$conditions = "FIND_IN_SET('".$user_id."',Group.group_owners) AND Group.status = '1'";
    $ArGroups = $this->Group->find('all',array('conditions' => $conditions));
    */
    $conditions = "GroupUser.user_id = '".$user_id."'";
    $ArGroupUsers = $this->GroupUser->find('all',array('conditions' => $conditions));

    if(count($ArGroupUsers) > 0)
    {
        foreach($ArGroupUsers as $GroupUser)
        {
            if($GroupUser['GroupUser']['user_type'] == 'O')
            {
              $group_id = $GroupUser['GroupUser']['group_id'];
              $con_grp = "Group.id = '".$group_id."'";
              $ArGroup = $this->Group->find('first',array('conditions' => $con_grp));
              $StrGroupOwners = $ArGroup['Group']['group_owners'];
              $ArGroupOwners = explode(',', $ArGroup['Group']['group_owners']);

                if(count($ArGroupOwners) == 1)
                {

                  /*************Group related everything and user************/
                  $this->Group->id = $group_id;
                  $this->Group->delete();

                  //$this->GroupUser->deleteAll(array('GroupUser.group_id'=>$group_id),false);
                  $this->GroupUser->deleteAll(array('GroupUser.group_id'=>$group_id),false);
                  $this->GroupMessage->deleteAll(array('GroupMessage.group_id'=>$group_id),false);
                  $this->GroupMessageReply->deleteAll(array('GroupMessageReply.group_id'=>$group_id),false);
                  $this->Event->deleteAll(array('Event.group_id'=>$group_id),false);

                  $con_grp_doc = "GroupDoc.group_id = '".$group_id."'";
                  $ArGroupDocs = $this->GroupDoc->find('all',array('conditions' => $con_grp_doc, 'fields' => 'GroupDoc.id,GroupDoc.docname'));

                  foreach($ArGroupDocs as $GroupDoc)
                  {
                      $folder='gallery/doc';
                      $this->removeFile($GroupDoc['GroupDoc']['docname'],$folder);

                      $this->GroupDoc->id = $GroupDoc['GroupDoc']['id'];
                      $this->GroupDoc->delete();
                  }

                  $con_grp_img = "GroupImage.group_id = '".$group_id."'";
                  $ArGroupImgs = $this->GroupImage->find('all',array('conditions' => $con_grp_img, 'fields' => 'GroupImage.id,GroupImage.image'));

                  foreach($ArGroupImgs as $value)
                  {
                      $folder='group_images/';
                      $folder_web='group_images/web';
                      $folder_thumb='group_images/thumb';
                      $folder_medium='group_images/medium';

                      $this->removeFile($value['GroupImage']['image'],$folder);
                      $this->removeFile($value['GroupImage']['image'],$folder_web);
                      $this->removeFile($value['GroupImage']['image'],$folder_thumb);
                      $this->removeFile($value['GroupImage']['image'],$folder_medium);

                      $this->GroupImage->id = $value['GroupImage']['id'];
                      $this->GroupImage->delete();
                  }


                  $con_grp_vdo = "Video.group_id = '".$group_id."'";
                  $ArGroupVideos = $this->Video->find('all',array('conditions' => $con_grp_vdo, 'fields' => 'Video.id,Video.video,Video.v_image'));

                  foreach($ArGroupVideos as $value)
                  {
                      $folder='group_videos/';
                      $folder_img='group_videos/images';
                      
                      
                      $this->removeFile($value['Video']['video'],$folder);
                      $this->removeFile($value['Video']['v_image'],$folder_img);
                      
                      $this->Video->id = $value['Video']['id'];
                      $this->Video->delete();
                  }

                  $this->User->id = $user_id;
                  $this->User->delete();

                  $con_frndlist_snder = "Friendlist.sender_id = '".$user_id."'";
                  $con_frndlist_rcvr = "Friendlist.receiver_id = '".$user_id."'";
                  $ArFrndSendr = $this->Friendlist->find('first',array('conditions' => $con_frndlist_snder, 'fields' => 'Friendlist.id'));
                  $ArFrndRcvr = $this->Friendlist->find('first',array('conditions' => $con_frndlist_rcvr, 'fields' => 'Friendlist.id'));

                  $this->Friendlist->id = $ArFrndSendr['Friendlist']['id'];
                  $this->Friendlist->delete();
                  $this->Friendlist->id = $ArFrndRcvr['Friendlist']['id'];
                  $this->Friendlist->delete();

                  //$this->GroupDoc->deleteAll(array('GroupDoc.group_id'=>$group_id),false);
                  //$this->GroupImage->deleteAll(array('GroupImage.group_id'=>$group_id),false);
                  //$this->Video->deleteAll(array('Video.group_id'=>$group_id),false);

                  /*************Group related everything and user************/

                  /*echo "1";
                    exit();*/

                }
                else if(count($ArGroupOwners) >= 2)
                {
                  $ArUpdatedGroupOwner = array();
                  foreach($ArGroupOwners as $GroupOwner)
                  {
                    if($GroupOwner!=$user_id)
                    {
                      array_push($ArUpdatedGroupOwner,$GroupOwner);
                    }
                  }

                  $StrUpdatedGroupOwner = implode(',',$ArUpdatedGroupOwner);

                  $this->data['Group']['id'] = $group_id;
                  $this->data['Group']['group_owners'] = $StrUpdatedGroupOwner;
                  $this->Group->save($this->data['Group']);

                  $con_frndlist_snder = "Friendlist.sender_id = '".$user_id."'";
                  $con_frndlist_rcvr = "Friendlist.receiver_id = '".$user_id."'";
                  $ArFrndSendr = $this->Friendlist->find('first',array('conditions' => $con_frndlist_snder, 'fields' => 'Friendlist.id'));
                  $ArFrndRcvr = $this->Friendlist->find('first',array('conditions' => $con_frndlist_rcvr, 'fields' => 'Friendlist.id'));

                  $this->Friendlist->id = $ArFrndSendr['Friendlist']['id'];
                  $this->Friendlist->delete();
                  $this->Friendlist->id = $ArFrndRcvr['Friendlist']['id'];
                  $this->Friendlist->delete();
                  
                  $this->GroupUser->deleteAll(array('GroupUser.user_id'=>$user_id),false);

                  $this->User->id = $user_id;
                  $this->User->delete();

                   /*echo "1";
                    exit();*/


                }

            }
            else if($GroupUser['GroupUser']['user_type'] == 'M')
            {

                $this->GroupUser->id = $GroupUser['GroupUser']['id'];
                $this->GroupUser->delete();

                $this->GroupMessage->deleteAll(array('GroupMessage.user_id'=>$user_id),false);

                $this->GroupMessageReply->deleteAll(array('GroupMessageReply.replied_by'=>$user_id),false);

                $con_frndlist_snder = "Friendlist.sender_id = '".$user_id."'";
                $con_frndlist_rcvr = "Friendlist.receiver_id = '".$user_id."'";
                $ArFrndSendr = $this->Friendlist->find('first',array('conditions' => $con_frndlist_snder, 'fields' => 'Friendlist.id'));
                $ArFrndRcvr = $this->Friendlist->find('first',array('conditions' => $con_frndlist_rcvr, 'fields' => 'Friendlist.id'));

                $this->Friendlist->id = $ArFrndSendr['Friendlist']['id'];
                $this->Friendlist->delete();
                $this->Friendlist->id = $ArFrndRcvr['Friendlist']['id'];
                $this->Friendlist->delete();

                $this->User->id = $user_id;
                $this->User->delete();

               /* echo "1";
                    exit();*/

            }
        }

       echo "1";
    exit(); 

    }else{

      $con_user = "User.id = '".$user_id."'";
      $ArUser = $this->User->find('all',array('conditions' => $con_user, 'fields' => 'User.image'));

      $folder='user_images/';
      $folder_thumb='user_images/medium';
      $folder_medium='user_images/thumb';
      
      $this->removeFile($ArUser['User']['image'],$folder);
      $this->removeFile($ArUser['User']['image'],$folder_thumb);
      $this->removeFile($ArUser['User']['image'],$folder_medium);

      $con_frndlist_snder = "Friendlist.sender_id = '".$user_id."'";
      $con_frndlist_rcvr = "Friendlist.receiver_id = '".$user_id."'";
      $ArFrndSendr = $this->Friendlist->find('first',array('conditions' => $con_frndlist_snder, 'fields' => 'Friendlist.id'));
      $ArFrndRcvr = $this->Friendlist->find('first',array('conditions' => $con_frndlist_rcvr, 'fields' => 'Friendlist.id'));

      $this->Friendlist->id = $ArFrndSendr['Friendlist']['id'];
      $this->Friendlist->delete();
      $this->Friendlist->id = $ArFrndRcvr['Friendlist']['id'];
      $this->Friendlist->delete();


      $this->User->id = $user_id;
      $this->User->delete();

      
    }

    echo "1";
    exit();



    //exit;
    
    /*$this->User->id = $user_id;
    $this->User->delete();

    echo "1";
    exit();*/
  }



  function getOwnerGrpCount($user_id)
  {

      $ArSingleOwnerGroupCount = 0;
      $conditions = "FIND_IN_SET('".$user_id."',Group.group_owners) AND Group.status = '1'";
      $ArGroups = $this->Group->find('all',array('conditions' => $conditions));
      //pr($ArGroups);
      foreach($ArGroups as $Group)
      {
          $group_owners = explode(',',$Group['Group']['group_owners']);

          if(count($group_owners) == 1)
          {
            $ArSingleOwnerGroupCount = 1;
          }
      }
      
      return $ArSingleOwnerGroupCount;
  }

  function user_list() {
        
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
        $this->layout = "";
      $this->set('pageTitle', 'User List');

      $selected_state_id = '';
      $selected_city_id = '';
      $total_user = '';

      $search_by = 0;
      if($this->Session->read('admin_type') == 'TA')
      {

          $territory_id = $this->Session->read('adminData.Admin.id');
          $con = "TerritoryAssign.territory_id = '".$territory_id."'";
          $fields = array('TerritoryAssign.assign_state','TerritoryAssign.assign_city');
          $ArTerritoryStateCity = $this->TerritoryAssign->find('all',array('conditions' => $con, 'fields' => $fields));
          //pr($ArTerritoryStateCity);
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
      
    //pr($this->params['form']);exit;
      if(!empty($this->params['form']))
      {
          //pr($this->params['form']);exit;
          if(isset($this->params['form']['index_city_id']) && $this->params['form']['index_city_id'] != '')
          {
            //pr($this->params['form']);exit;
              $conditions_user = "User.city_id = '".$this->params['form']['index_city_id']."' AND User.state_id = '".$this->params['form']['state_id']."' ";
              $all_users = $this->User->find("all",array('conditions'=>$conditions_user,'order' => 'User.fname ASC'));
              $user_state_city = $this->User->find("first",array('conditions'=>$conditions_user));
              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = $this->params['form']['index_city_id'];
              $total_user = count($all_users);
              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

              $search_by = 2;
          }
          else if((isset($this->params['form']['state_id']) && $this->params['form']['state_id'] != ''))
          {
            
            //echo "==================";exit;
              $conditions_user = "User.state_id = '".$this->params['form']['state_id']."' ";
              $all_users = $this->User->find("all",array('conditions'=>$conditions_user,'order' => 'User.fname ASC'));
              $user_state_city = $this->User->find("first",array('conditions'=>$conditions_user));
              $selected_state_id = $this->params['form']['state_id'];
              $selected_city_id = '';
              $total_user = count($all_users);
              $condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
              $city_list = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));

              $search_by = 1;
          }
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
      $this->set('search_by',$search_by);
      
    }

  function index() {
      //Configure::write('debug', 2);
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
      $this->redirect('/admins/login');

      $this->layout = "";
      $this->set('pageTitle', 'Admin User');

      $productAll = $this->FitnessProduct->find('all', array('conditions'=>array('isdeleted' => '0')));
      $this->set('Allproduct', $productAll);
  }

  
  function view_user($user_id,$search_by) {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
      $this->redirect('/admins/login');

      $this->layout = "";
      $this->set('pageTitle', 'Admin Customer');

      $conditions = "User.id = '" . $user_id . "'";
      $UserDetails = $this->User->find('first', array('conditions' => $conditions));
      // pr($UserDetails);exit();
      $this->set('UserDetails',$UserDetails); 
      $this->set('search_by',$search_by);
  }



    function view_groups($user_id) {
      $adminData = $this->Session->read('adminData');
      if(empty($adminData))
        $this->redirect('/admins/login');
      
      $this->layout = "";
      $this->set('pageTitle', 'View Groups');
      
      
    
      //free grp start//
      $condition_my_groups = "GroupUser.status= '1'  AND ( GroupUser.user_type = 'O' OR GroupUser.user_type = 'M') AND GroupUser.user_id = '".$user_id."' ";
      $all_my_groups = $this->GroupUser->find('all',array('conditions'=>$condition_my_groups ));
         
    if(empty($all_my_groups)){
       $this->set('all_free_groups','');  
       $this->set('all_business_groups','');  
    }
    else{
      $groups = array(); 
      
      foreach($all_my_groups as $grp){
        array_push($groups, $grp['GroupUser']['group_id']);
      }
      
      $str_allgroups = implode(",",$groups);
      
      /************          Fetch free groups starts      ***************/
        $cond_free_grps = "Group.status= '1'  AND Group.group_type='F'  AND Group.id IN (".$str_allgroups.")";
      $count_free_groups=  $this->Group->find('count',array('conditions'=>$cond_free_grps ));
       
        if($count_free_groups>0){
      
        $limit_f = 12;
        $lastpage_f = ceil($count_free_groups/$limit_f);
        $start_f=0;
        $page_f = 1;
        $prev_f = $page_f - 1;
        $next_f = $page_f + 1;
        $lpm1_f = $lastpage_f - 1;
        
        $this->set('limit_f',$limit_f); 
        $this->set('lastpage_f',$lastpage_f); 
        $this->set('start_f',$start_f); 
        $this->set('page_f',$page_f); 
        $this->set('prev_f',$prev_f); 
        $this->set('next_f',$next_f); 
        $this->set('lpm1_f',$lpm1_f);
        
        $all_free_groups = $this->Group->find('all',array('conditions' =>$cond_free_grps, 'offset' => $start_f, 'limit' => $limit_f, 'order' => 'Group.group_title ASC'));
  
        $this->set('all_free_groups',$all_free_groups); 
      }
      else{
        $this->set('all_free_groups',''); 
      } 

      //pr($all_free_groups);exit();
      
      /************          Fetch free groups ends      *****************/
      
      /************          Fetch business groups starts      ***************/
        $cond_business_grps = "Group.status= '1'  AND Group.group_type='B'  AND Group.id IN (".$str_allgroups.")";
        $count_business_groups=  $this->Group->find('count',array('conditions'=>$cond_business_grps ));
      
      if($count_business_groups>0){
      
        $limit_b = 12;
        $lastpage_b = ceil($count_business_groups/$limit_b);
        $start_b=0;
        $page_b = 1;
        $prev_b = $page_b - 1;
        $next_b = $page_b + 1;
        $lpm1_b = $lastpage_b - 1;
        
        $this->set('limit_b',$limit_b); 
        $this->set('lastpage_b',$lastpage_b); 
        $this->set('start_b',$start_b); 
        $this->set('page_b',$page_b); 
        $this->set('prev_b',$prev_b); 
        $this->set('next_b',$next_b); 
        $this->set('lpm1_b',$lpm1_b);
        
        $all_business_groups = $this->Group->find('all',array('conditions' =>$cond_business_grps, 'offset' => $start_b, 'limit' => $limit_b, 'order' => 'Group.group_title ASC'));
  
        $this->set('all_business_groups',$all_business_groups); 
      }
      else{
        $this->set('all_business_groups',''); 
      }
      
      //pr($all_business_groups);exit;
      /************          Fetch business groups ends      *****************/


      /************          Fetch PO groups starts      ***************/
        $cond_po_grps = "Group.status= '1'  AND Group.group_type='PO'  AND Group.id IN (".$str_allgroups.")";
        $count_po_groups=  $this->Group->find('count',array('conditions'=>$cond_po_grps ));
      
      if($count_po_groups>0){
      
        $limit_po = 12;
        $lastpage_po = ceil($count_po_groups/$limit_po);
        $start_po=0;
        $page_po = 1;
        $prev_po = $page_po - 1;
        $next_po = $page_po + 1;
        $lpm1_po = $lastpage_po - 1;
        
        $this->set('limit_po',$limit_po); 
        $this->set('lastpage_po',$lastpage_po); 
        $this->set('start_po',$start_po); 
        $this->set('page_po',$page_po); 
        $this->set('prev_po',$prev_po); 
        $this->set('next_po',$next_po); 
        $this->set('lpm1_po',$lpm1_po);
        
        $all_po_groups = $this->Group->find('all',array('conditions' =>$cond_po_grps, 'offset' => $start_po, 'limit' => $limit_po, 'order' => 'Group.group_title ASC'));
  
        $this->set('all_po_groups',$all_po_groups); 
      }
      else{
        $this->set('all_po_groups',''); 
      }
      
      //pr($all_business_groups);exit;
      /************          Fetch PO groups ends      *****************/
    }
       
    }


    
    
}

?>
