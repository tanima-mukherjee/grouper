<!DOCTYPE html>
<html lang="en">
  <head>
	 <meta charset="utf-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grouper</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet"> 
   <!-- custom css -->  
   <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet"> 
	<style>
		body{
			background-color:transparent;
		}
	</style>
	<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function add_friend_request(receiver_id,k){
   //alert(receiver_id);
    if(receiver_id != ''){ 
       jQuery.ajax({

          type: "GET",
          url: WEBROOT+"friend/add_friend_request",
          data: {receiver_id:receiver_id},
          success: function(response){
          if(response == '0'){
            jQuery('#group_user'+k).html('<button type="button" class="confirm-btn">Request Sent</button><div class="clearfix"></div><button type="button" class="confirm-btn" onclick = "add_friend_request_again('+receiver_id+', '+k+');">Send Request Again</button>');
            /*jQuery("#add_friend"+receiver_id).hide();
            jQuery("#request_sent"+receiver_id).show();*/
            }
            else if(response == '1')
            {
 
              alert('Join now request unccessful');

            }else if(response == '2')
            {

              alert('You are already friends');

            }else if(response == '3')
            {

              alert('You have already send a request');

            }
          }
      });
       
   }
   else{
      alert('No receiver found');
    }
  }
  
  function add_friend_request_again(receiver_id,k){
   //alert(receiver_id);
    if(receiver_id != ''){ 
       jQuery.ajax({

          type: "GET",
          url: WEBROOT+"friend/add_friend_request_again",
          data: {receiver_id:receiver_id},
          success: function(response){
          if(response == '0'){
            jQuery('#group_user'+k).html('<button type="button" class="confirm-btn">Request Sent</button><div class="clearfix"></div><button type="button" class="confirm-btn" onclick = "add_friend_request_again('+receiver_id+', '+k+');">Send Request Again</button>');
			
            $('#success_msg').show();
			$('#success_msg').html('Friend request sent successfully !!!');	  
			$("#success_msg").delay(12000).fadeToggle(); 
          }
          else if(response == '1'){
              alert('You have already send a request');
          }
          }
      });
       
   }
   else{
      alert('No receiver found');
    }
  }
</script>

<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function accept_friend_request(notification_id,k){
    //alert(notification_id);
    
    if(notification_id != ''){
      
       jQuery.ajax({

          type: "POST",
          url: WEBROOT+"friend/accept_friend_request",
          data: {notification_id:notification_id},
          
          success: function(response){
         if(response == '0'){
          jQuery('#group_user'+k).html('<button type="button" class="confirm-btn">Friends</button>');
            /*jQuery("#accept_friend"+notification_id).hide();
            jQuery("#reject_friend"+notification_id).hide();           
            jQuery("#friends"+notification_id).show();*/
            }
            else if(response == '1')
            {
 
              alert('Friend Request unaccepted');

            }
            else if(response == '2')
            {
 
              alert('No notification found');

            }
          }
      });
    }
    else{
      alert('Notification id not found');
    }
  }
</script>

<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function reject_friend_request(notification_id, k, receiver_id){
    //alert(receiver_id);

    
    if(notification_id != ''){
      
       jQuery.ajax({

          type: "POST",
          url: WEBROOT+"friend/reject_friend_request",
          data: {notification_id:notification_id},
          
          success: function(response){
         if(response == '0'){
          //alert(receiver_id);
          jQuery('#group_user'+k).html('<button  type="button" class="confirm-btn" onclick = "add_friend_request('+receiver_id+','+k+');" id = "add_friend">Add Friend</button>');
            /*jQuery("#accept_friend"+notification_id).hide();
            jQuery("#reject_friend"+notification_id).hide();           
            jQuery("#add_reject_friend"+notification_id).show();*/
            }
            else if(response == '1')
            {
 
              alert('Friend Request not rejected');

            }
            else if(response == '2')
            {
 
              alert('No notification found');

            }
          }
      });
    }
    else{
      alert('Notification id not found');
    }
  }
  
  function make_owner(sender_id,receiver_id,group_id,member_mode,k){
  		var member_mode_old= jQuery('#mode_member'+k).html();
		jQuery.ajax({

          type: "GET",
          url: WEBROOT+"group/make_owner",
          data: {sender_id:sender_id, receiver_id:receiver_id, group_id:group_id},
          success: function(response){
          if(response == '1'){
		  	jQuery('#mode_member'+k).html(member_mode);
            jQuery('#group_owner_previlige'+k).html('<button  type="button" class="owner-btn" id="" onClick="remove_owner('+sender_id+','+receiver_id+','+group_id+','+'\''+member_mode_old+'\''+','+k+')" >Remove Owner</button>');
          }
          }
      });
  }
  
  function remove_owner(sender_id,receiver_id,group_id,member_mode,k){
  		var member_mode_old= jQuery('#mode_member'+k).html();

		jQuery.ajax({

          type: "GET",
          url: WEBROOT+"group/remove_owner",
          data: {sender_id:sender_id, receiver_id:receiver_id, group_id:group_id},
          success: function(response){
          if(response == '1'){
		  	jQuery('#mode_member'+k).html(member_mode);
            jQuery('#group_owner_previlige'+k).html('<button  type="button" class="owner-btn" id="" onClick="make_owner('+sender_id+','+receiver_id+','+group_id+','+'\''+member_mode_old+'\''+','+k+')" >Make Owner</button>');
          }
          }
      });
  }

  function remove_member(sender_id,receiver_id,group_id,k){
     // var member_mode_old= jQuery('#mode_member'+k).html();
       //alert(member_mode_old);exit();
       var confirm = window.confirm("Are you sure you want to remove this member?");
       if(confirm){
	   
    	  jQuery.ajax({

          type: "GET",
          url: WEBROOT+"group/remove_member",
          data: {sender_id:sender_id, receiver_id:receiver_id, group_id:group_id},
          success: function(response){
          if(response == '1'){
            jQuery("#group_owner_member"+k).remove();
      
            }
          }
      });
    }
  }

  function send_sms(group_user_id,group_id,k,status){
     // var member_mode_old= jQuery('#mode_member'+k).html();
       //alert(member_mode_old);exit();
       var confirm = window.confirm("Are you sure you want to send sms to this member?");
       if(confirm){
     
        jQuery.ajax({

          type: "GET",
          url: WEBROOT+"group/send_sms",
          data: {group_user_id:group_user_id, group_id:group_id,status:status},
          success: function(response){
            //alert(response);
              if(jQuery.trim(response) == 1)
              {
                  if(status == '1')
                  {
                    post_status = '0';
                    txt_show = 'Can Send SMS?';
                  }else
                  {
                    post_status = '1';
                    txt_show = 'Send SMS';
                  }
                  //alert(txt_show+','+post_status);
                  jQuery('#sms_send_'+k).html('<button  type="button" class="owner-btn" id="" onClick="send_sms('+group_user_id+','+group_id+','+k+','+post_status+')" >'+txt_show+'</button>');
                
              }
          }
      });
    }
  }
</script>

  </head>
<body>
	<div class="user-dialog">
     <div class="modal-header">        
        <h4 class="modal-title">User List</h4>
		
      </div>
	<div id="success_msg" style="display:none; background:#ccc; padding:10px 12px; font-weight:bold; font-size:16px"></div>
      <div class="modal-body" id = "user_list">
       <?php  if($is_auth == '1') { ?>
      <?php 
      if(count($group_members) > '0')
      { ?>
      <ul class="userlist">
      <?php  
	  //echo '<pre>';
	  //print_r($group_members);
    //echo '<pre>';exit;
      $k=1;
      foreach ($group_members as $list) { 



      ?>
      <li id = "group_owner_member<?php echo $k;?>">
        <div class="view-userimg">
          <?php if($list['image_url']!='') { ?>
          <img src="<?php echo $this->webroot.'user_images/thumb/'.$list['image_url'];?>" alt="" />
          <?php } else { ?>
             <img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" />
          <?php } ?>
        </div>
        <div class="view-userdes">
          <div class="align-center">
            <span class="user-request-display"><?php echo ucfirst($list['user_name']) ;?>

             <?php if($list['user_type'] == 'O') { ?>
					<span class="user-group-post" id="mode_member<?php  echo $k; ?>"> Owner </span>
        	 <?php } else { ?>
          			<span class="user-group-post" id="mode_member<?php  echo $k; ?>"><?php echo ucfirst($list['member_mode']);?> </span>
        	 <?php } ?>
			</span>
			
		  <div class="user-list-action actions actions-right">
      
      <?php if($list['user_id'] != $session_user_id ) { ?>
		  <div id = "group_user<?php echo $k;?>" >
          <?php if($list['is_friend'] == '0') { ?>
              <button  type="button" class="confirm-btn" onclick = "add_friend_request(<?php  echo $list['user_id']?>, <?php  echo $k; ?>);"
              id = "add_friend" >Add Friend</button>
            <?php } else if($list['is_friend'] == '1') { ?>
              <button type="button" class="confirm-btn">Friends</button>             
            <?php } else if($list['is_friend'] == '2') { ?>
              <button type="button" class="confirm-btn">Request Sent</button>   
			  <div class="clearfix"></div> 
			  <button type="button" class="confirm-btn" onclick = "add_friend_request_again(<?php  echo $list['user_id']?>, <?php  echo $k; ?>);">Send Request Again</button>         
            <?php } else if($list['is_friend'] == '3'){ ?>
                          <button type="button" class="confirm-btn accept"  onclick = "accept_friend_request(<?php  echo $list['notification_id'] ?>, <?php  echo $k; ?>);" id = "accept_friend<?php echo $list['notification_id'];?>" >Accept</button>
                          
                          <button type="button" class="rej-btn reject" onclick = "reject_friend_request(<?php echo $list['notification_id'] ?>, <?php  echo $k; ?>,<?php echo $list['user_id']?>);" id = "reject_friend<?php echo $list['notification_id'];?>">Reject</button>
            <?php } ?>
			</div>
	  <?php } ?>
			
			
			
	  <?php if($session_user_id == $created_by && $session_user_id!=$list['user_id']){ ?>
			<div class="clearfix"></div>
			<div id = "group_owner_previlige<?php echo $k;?>" >
			<?php if($list['user_type']=='O'){ ?>
				<button  type="button" class="owner-btn" id="" onClick="remove_owner(<?php echo $session_user_id; ?>,<?php  echo $list['user_id']; ?>,<?php  echo $list['group_id']; ?>, '<?php echo ucfirst($list['member_mode']);?>', <?php  echo $k; ?>)" >Remove Owner</button>
			<?php }
				else{
			?>
				<button  type="button" class="owner-btn" id="" onClick="make_owner(<?php echo $session_user_id; ?>,<?php  echo $list['user_id']; ?>,<?php  echo $list['group_id']; ?>, 'Owner', <?php  echo $k; ?>)" >Make Owner</button>
			<?php	
				}?>
      </div>
		  <?php } ?>

      <?php if($list['user_type']=='M' && $is_owner==1){ ?>
      <button  type="button" class="owner-btn" id="" onClick="remove_member(<?php echo $session_user_id; ?>,<?php  echo $list['user_id']; ?>,<?php  echo $list['group_id']; ?>, <?php  echo $k?>)" >Remove Member</button>
      <?php } ?>


      <?php if($list['group_type']=='F' && $list['user_type']=='M' && $is_owner==1){ ?>
      <div id="sms_send_<?php echo $k;?>">       
      
      <?php if($list['can_post_topic'] == '0'){ ?>
        
        <button type="button" class="owner-btn" id="" onClick="send_sms(<?php echo $list['id']; ?>,<?php  echo $list['group_id']; ?>, <?php  echo $k?>,'0')" >Can Send SMS?</button>
        
        <?php }else{ ?>

        <button type="button" class="owner-btn" id="" onClick="send_sms(<?php echo $list['id']; ?>,<?php  echo $list['group_id']; ?>, <?php  echo $k?>,'1')" >Send SMS</button>

        <?php } ?>
      </div>
      <?php } ?>

            
          </div>
        </div>
		</div>
        <div class="clearfix"></div>
      </li>
       <?php 
          $k++;
          } ?>
    </ul>
    <?php } else { ?>
                    <ul class="userlist" >
                    <li>                      
                      No user found
                    <div class="clearfix"></div>
                    </li>
                    </ul>
                  <?php  } ?>
                  <?php } else if ($is_auth == '0') { ?>
                    <div class="no-authorized" >
						<img src="<?php echo $this->webroot?>images/authorized-img.png" alt="" />						                               
                    </div>
                  <?php  } ?>  
      </div>
	</div>  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

</body>
</html>


							