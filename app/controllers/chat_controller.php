<?php
App::import('Vendor','authnet_aim');
class ChatController extends AppController {

    var $name = 'Chat';
    var $uses = array('Chat','User','City','State','Friendlist');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    

    function all_friends($id=NULL) {
        $this->layout = "";

        $this->_checkSessionUser();
        
        $user_id = $this->Session->read('userData.User.id');
        
		
		//echo $user_id;
		$con_friend = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
		$this->Friendlist->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'receiver_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image'),
                  'Chat'=>array('className'=>'Chat','foreignKey'=>'sender_id',
                  'fields'=>'Chat.id')
                )
                ));
		$ArFriendlist = $this->Friendlist->find('all',array('conditions' => $con_friend, 'order' => 'Sender.fname ASC'));
		//pr($ArFriendlist);exit;
		$this->set('ArFriendlist',$ArFriendlist);
		
	}


	function friend_chat_list()
	{
		$user_id = $this->Session->read('userData.User.id');
		$con_friend = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
		$this->Friendlist->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'receiver_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image'),
                  'Chat'=>array('className'=>'Chat','foreignKey'=>'sender_id',
                  'fields'=>'Chat.id')
                )
                ));
		$ArFriendlist = $this->Friendlist->find('all',array('conditions' => $con_friend, 'order' => 'Sender.fname ASC'));
		//pr($ArFriendlist);exit;
		$this->set('ArFriendlist',$ArFriendlist);
	}
	
    function index($id=NULL) {
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Chat');
        $this->_checkSessionUser();
        
        $user_id = $this->Session->read('userData.User.id');
        $this->set('user_id',$user_id);
        $this->set('friend_id',$id);
		
		//echo $user_id;
		
        $selected_state_id = $this->Session->read('selected_state_id');
        $selected_city_id = $this->Session->read('selected_city_id');

        $this->set('selected_state_id',$selected_state_id);
        $this->set('selected_city_id',$selected_city_id);

        $conditions = "State.isdeleted = '0' AND State.country_id = '254'";
        $ArState = $this->State->find('all', array('conditions' => $conditions));
        $this->set('ArState', $ArState);

        $conditions = "State.isdeleted = '0' AND State.id = '".$selected_state_id."'";
        $StateName = $this->State->find('first', array('conditions' => $conditions));
        $this->set('StateName', $StateName);
		 
		$condition = "City.isdeleted = '0' AND City.state_id='".$selected_state_id."'";
		$citylist = $this->City->find("all",array('conditions'=>$condition,'fields'=>array('City.id','City.name'),'order' => array('City.name ASC')));
		$this->set('citylist',$citylist);

        $conditions = "City.isdeleted = '0' AND City.id = '".$selected_city_id."'";
        $CityName = $this->City->find('first', array('conditions' => $conditions));
        $this->set('CityName', $CityName);
		
		
		$con_friend = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
		$this->Friendlist->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'receiver_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image'),
                  'Chat'=>array('className'=>'Chat','foreignKey'=>'sender_id',
                  'fields'=>'Chat.id')
                )
                ));
		$ArFriendlist = $this->Friendlist->find('all',array('conditions' => $con_friend, 'order' => 'Sender.fname ASC'));
		//pr($ArFriendlist);exit;
		$this->set('ArFriendlist',$ArFriendlist);
		
		$con_select_chat = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
		$this->Friendlist->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'sender_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'receiver_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image')
                )
                ));
		$ArChatWith = $this->Friendlist->find('first',array('conditions' => $con_friend, 'order' => 'Sender.fname ASC'));
		//pr($ArChatWith);exit;
		$this->set('ArChatWith',$ArChatWith);
		
		$sender_id = $ArChatWith['Friendlist']['sender_id'];
		
		$con_send = "User.id = '".$sender_id."'";
		$ArSenderDetail = $this->User->find('first',array('conditions' => $con_send));
		$friend_id = $ArSenderDetail['User']['id'];
		$con_user = "User.id = '".$user_id."'";
		$ArUserDetail = $this->User->find('first',array('conditions' => $con_user));
		
		
		
		$condition_chat1 = "((Chat.to_id = '".$user_id."' AND Chat.from_id = '".$sender_id."') OR (Chat.from_id = '".$user_id."' AND Chat.to_id = '".$sender_id."')) AND Chat.status = '1'";
		$this->Chat->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'from_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'to_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image')
                )
                ));
		$ArChatHistories = $this->Chat->find('all',array('conditions' => $condition_chat1));
		if(!empty($ArChatHistories))
		{
			foreach($ArChatHistories as $ChatHistory)
			{
				$this->data['Chat']['id'] = $ChatHistory['Chat']['id'];
				$this->data['Chat']['status'] = '0';
				$this->Chat->save($this->data['Chat']);
				
			}
		}
		
		
		
		$condition_chat = "((Chat.to_id = '".$user_id."' AND Chat.from_id = '".$sender_id."') OR (Chat.from_id = '".$user_id."' AND Chat.to_id = '".$sender_id."'))";
		$this->Chat->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'from_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'to_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image')
                )
                ));
		$ArChatHistory = $this->Chat->find('all',array('conditions' => $condition_chat));
		//pr($ArChatHistory);exit;
		
		
		$this->set('friend_id',$friend_id);
		$this->set('ArChatHistory',$ArChatHistory);
		$this->set('ArUserDetail',$ArUserDetail);
		$this->set('ArSenderDetail',$ArSenderDetail);

    }
    
    function getChatCount()
    {
		$user_id = $this->Session->read('userData.User.id');
		$con_chat_count = "Chat.to_id = '".$user_id."' AND Chat.status = '1'";
		$ArChatCount = $this->Chat->find('count',array('conditions' => $con_chat_count));
		
		return $ArChatCount;
		
	}
    function getReceiveChat($friend_id)
    {
		$my_id = $this->Session->read('userData.User.id');
		$con_chat_count = "Chat.from_id = '".$friend_id."' AND Chat.to_id = '".$my_id."' AND Chat.status = '1'";
		$ArChatCount = $this->Chat->find('count',array('conditions' => $con_chat_count));
		
		return $ArChatCount;
		
	}
	
    function getReceiveChatCount()
    {
		$friend_id = $_REQUEST['friend_id'];
		$my_id = $this->Session->read('userData.User.id');
		$con_chat_count = "Chat.from_id = '".$friend_id."' AND Chat.to_id = '".$my_id."' AND Chat.status = '1'";
		$ArChatCount = $this->Chat->find('count',array('conditions' => $con_chat_count));
		
		echo $ArChatCount;
		exit;
		
	}
	
    function checkChatCount()
    {
		$user_id = $this->Session->read('userData.User.id');
		$con_chat_count = "Chat.to_id = '".$user_id."' AND Chat.status = '1'";
		$ArChatCount = $this->Chat->find('count',array('conditions' => $con_chat_count));
		
		echo $ArChatCount;
		exit;
		
	}

	
	function get_friend_list()
	{
		$this->_checkSessionUser();
		$search_name = $_REQUEST['search_name'];
		$user_id = $this->Session->read('userData.User.id');
		
		$con_friend = "Friendlist.receiver_id = '".$user_id."' AND Friendlist.is_blocked = '0'";
		$ArFriendlist = $this->Friendlist->find('all',array('conditions' => $con_friend));
		
		$ArFriendIds = array();
		foreach($ArFriendlist as $Friendlist){
			
			array_push($ArFriendIds,$Friendlist['Friendlist']['sender_id']);
		}
		
		$StrFriendIds = implode(',',$ArFriendIds);
		$search_user = "User.id in ($StrFriendIds) AND (User.fname LIKE '%".$search_name."%' OR User.lname LIKE '%".$search_name."%' ) AND User.id <> '".$user_id."'";
		$ArUserSearch = $this->User->find('all',array('conditions' => $search_user));
		//pr($ArUserSearch);exit;
		$this->set('ArUserSearch',$ArUserSearch);
	}
    
	
	function show_select_chat()
	{
		$this->layout = '';
		 $this->_checkSessionUser();
		$sender_id = $_REQUEST['friend_id'];
		$user_id = $this->Session->read('userData.User.id');
		//$sender_id = $ArChatWith['Friendlist']['sender_id'];
		
		$condition_chat1 = "((Chat.to_id = '".$user_id."' AND Chat.from_id = '".$sender_id."') OR (Chat.from_id = '".$user_id."' AND Chat.to_id = '".$sender_id."')) AND Chat.status = '1'";
		$this->Chat->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'from_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'to_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image')
                )
                ));
		$ArChatHistories = $this->Chat->find('all',array('conditions' => $condition_chat1));
		if(!empty($ArChatHistories))
		{
			foreach($ArChatHistories as $ChatHistory)
			{
				$this->data['Chat']['id'] = $ChatHistory['Chat']['id'];
				$this->data['Chat']['status'] = '0';
				$this->Chat->save($this->data['Chat']);
				
			}
		}
		
		
		$condition_chat = "(Chat.to_id = '".$user_id."' AND Chat.from_id = '".$sender_id."') OR (Chat.from_id = '".$user_id."' AND Chat.to_id = '".$sender_id."')";
		$this->Chat->bindModel(
              array('belongsTo'=>array(
                  'Sender'=>array('className'=>'User','foreignKey'=>'from_id',
                  'fields'=>'Sender.id,Sender.email,Sender.fname,Sender.lname,Sender.image'),
                  'Receiver'=>array('className'=>'User','foreignKey'=>'to_id',
                  'fields'=>'Receiver.id,Receiver.email,Receiver.fname,Receiver.lname,Receiver.image')
                )
                ));
		$ArChatHistory = $this->Chat->find('all',array('conditions' => $condition_chat));
		
		$con_send = "User.id = '".$sender_id."'";
		$ArSenderDetail = $this->User->find('first',array('conditions' => $con_send));
		
		$con_user = "User.id = '".$user_id."'";
		$ArUserDetail = $this->User->find('first',array('conditions' => $con_user));
		//pr($ArUserSearch);exit;
		//$this->set('ArUserSearch',$ArUserSearch);
		$this->set('ArUserDetail',$ArUserDetail);
		$this->set('ArSenderDetail',$ArSenderDetail);
		
		//pr($ArChatHistory);exit;
		$this->set('ArChatHistory',$ArChatHistory);
		$this->set('user_id',$user_id);
	}
       
	
	

}?>
