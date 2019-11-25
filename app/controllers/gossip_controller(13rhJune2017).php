<?php
class GossipController extends AppController{
	var $name = 'Gossip';
	var $uses = array("SiteSetting", "EmailTemplate","Chat","User");
	var $helpers = array('Html', 'Form','Javascript','Fck','Js'); 
	var $components = array('CustomAvi');
	
	
function detail_chat_new(){
$json = '';

$msg = $_POST['msg'];
$myid = $_POST['mid'];
$fid = $_POST['fid'];
$to_name = $_POST['to_name'];
$from_name = $_POST['from_name'];



 
 if(empty($msg)){
 //$json = array('status' => 0, 'msg'=> 'Enter your message!.');
 }else{
	
	$this->data['Chat']['from'] = $from_name; 
	$this->data['Chat']['to'] = $to_name; 
	$this->data['Chat']['from_id'] = $myid; 
	$this->data['Chat']['to_id'] = $fid; 
	$this->data['Chat']['conversation'] = $msg;
	$this->data['Chat']['is_viewed_to'] = '0';
	$this->data['Chat']['is_viewed_from'] = '1';	
	$this->Chat->create(); 
	//if($qur){
	if($this->Chat->save($this->data['Chat'])){
	$last_insert_id = $this->Chat->getLastInsertId();
	//$qurGet = mysql_query("select * from msg where id='".mysql_insert_id()."'");
	
	//$qurGet = mysql_query("select * from chats,users where chats.id='".$last_insert_id."' && chats.to_id=users.id");	
	
	
	
	$con = "Chat.id = '".$last_insert_id."'";
	$this->Chat->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'from_id'))),false);
    $ArChat = $this->Chat->find('first',array('conditions' => $con, 'fields' => 'User.image,Chat.conversation,Chat.created'));
	//pr($ArChat);exit;	
	$user_image = $ArChat['User']['image']; 
	$conversation = $ArChat['Chat']['conversation']; 
	$created = $ArChat['Chat']['created']; 
	//echo $qurGet;exit;
		 //while($row = mysql_fetch_array($qurGet))
		 if(!empty($ArChat))
		 {
			//$json = array('status' => 1, 'msg' => $row['msg'], 'lid' => mysql_insert_id(), 'time' => $row['time']);
			//$json = array('status' => 1, 'from_name' => $from_name, 'profile_image' => $row['image'], 'msg' => $row['conversation'], 'lid' => $last_insert_id, 'time' => $row['created']);
			$json = array('status' => 1, 'from_name' => $from_name, 'profile_image' => $user_image, 'msg' => $conversation, 'lid' => $last_insert_id, 'time' => $created);
			
		 }
	 }else{
		$json = array('status' => 0, 'msg'=> 'Unable to process request.');
	 }
 }
 
 //print_r($json);exit;
 
header('Content-type: application/json');
echo json_encode($json);
exit;
}

function detail_chat_msg(){
$json = '';	 
 $myid = $_REQUEST['mid'];
 $fid = $_REQUEST['fid'];
 $lid = $_REQUEST['lid'];
 
	 
	 //if(empty($myid)){
	 if($myid ==''){

	 }else{
	 //$qur = mysql_query("select * from msg where `to`='$myid' && `from`='$fid' && `status`=1");
	 
	 //$qur = mysql_query("select chats.conversation from chats where ((chats.from_id = '".$myid."' AND chats.to_id = '".$fid."') OR (chats.from_id = '".$fid."' AND chats.to_id = '".$myid."')) AND chats.status = '1'");

	 $con ="(Chat.from_id = '".$myid."' AND Chat.to_id = '".$fid."') OR (Chat.from_id = '".$fid."' AND Chat.to_id = '".$myid."') AND Chat.status = 1";
	 $ArChat = $this->Chat->find('first',array('conditions' => $con, 'fields' => 'Chat.id'));
	 //print_r($qur);exit;
	 
	 //if(mysql_num_rows($qur) > 0){
	 if(count($ArChat) > 0){
	 $json = array('status' => 1);
	 }else{
	 $json = array('status' => 0);
	 }
	 }
//$json = array('status' => 1);
header('Content-type: application/json');
echo json_encode($json);
exit;
}

function detail_chat_newmsg(){
$json = '';	
$myid = $_REQUEST['mid'];
$fid = $_REQUEST['fid'];
 
 
 //$rw = mysql_fetch_array($qur);
 //print_r($rw);exit; 
 //$qur = mysql_query("select * from msg where `to`='$myid' && `from`='$fid' && `status`=1 order by id desc limit 1");
 
 //$qur = mysql_query("select chats.from,users.image,chats.conversation,chats.created from chats,users where chats.from_id = users.id AND chats.`to_id` = '".$myid."' AND chats.`from_id` = '".$fid."' AND chats.`status` = '1' order by chats.id desc limit 1");
 
 
 $con = "Chat.to_id = '".$myid."' AND Chat.from_id = '".$fid."' AND Chat.status = '1'";
 $this->Chat->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'from_id'))),false);
 $ArChat = $this->Chat->find('first',array('conditions' => $con, 'fields' => 'Chat.to_id,Chat.from_id,Chat.id,Chat.from,User.image,Chat.conversation,Chat.created', 'order' => 'Chat.id DESC', 'limit' => '0,1'));
 
 $lid = $ArChat['Chat']['id'];
 $from_name = $ArChat['Chat']['from'];
 $user_image = $ArChat['User']['image'];
 $conversation = $ArChat['Chat']['conversation'];
 $created = $ArChat['Chat']['created'];
 //while($rw = mysql_fetch_array($qur)){
 if(!empty($ArChat)){
	 
	//$json = array('status' => 1, 'from_name' => $rw['from'], 'profile_image' => $rw['profile_image'], 'msg' => $rw['conversation'], 'lid' => $rw['id'], 'time'=> $rw['created']);
	$json = array('status' => 1, 'from_name' => $from_name, 'profile_image' => $user_image, 'msg' => $conversation, 'lid' => $lid, 'time'=> $created);

}

//$qur = mysql_query("select * from chats where `to_id` = '".$myid."' AND `from_id` = '".$fid."' AND `status` = '1' order by id desc limit 1");



 //while($rw = mysql_fetch_array($qur)){	 
 $this->data['Chat']['id'] = $ArChat['Chat']['id'];
 $this->data['Chat']['to_id'] = $ArChat['Chat']['to_id'];
 $this->data['Chat']['from_id'] = $ArChat['Chat']['from_id'];
 $this->data['Chat']['status'] = '0';
 $this->Chat->save($this->data['Chat']);
//}
 
 header('Content-type: application/json');
 echo json_encode($json);
 exit;
}


}
?>
