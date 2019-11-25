
  <div class="panel-heading">
	<div class="message-time">
		<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
		<span class="time-info"><?php echo date('M j, Y, h:i A',strtotime($GroupMessage['GroupMessage']['created']));?></span>
		</a>
	</div>
	
	<div class="reply">
		<ul class="action-list">
		<li>
			<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
				<i class="fa fa-mail-reply"></i>
			</a>
			<span class="reply-counter text-center" id="reply_cnt_<?php echo $GroupMessage['GroupMessage']['id'];?>"><?php echo count($GroupMessage['GroupMessageReply']);?></span>
		</li>
		<?php //$can_edit_delete = $this->requestAction('group/check_edit_delete_auth/'.$GroupMessage['GroupMessage']['id']);
        if($can_edit == 1){
        ?>
		<li><a href="javascript:void(0)" class="Assign_link" data-id="<?php echo $GroupMessage['GroupMessage']['id'];?>" data-topic="<?php echo $GroupMessage['GroupMessage']['topic'];?>" data-groupid="<?php echo $GroupMessage['GroupMessage']['group_id'];?>" data-message="<?php echo $GroupMessage['GroupMessage']['message'];?>" data-toggle="modal" data-target="#edittopicModal"><i class="fa fa-pencil"></i></a></li>
		<li><a href="javascript:void(0)" onclick="delete_topic(<?php echo $GroupMessage['GroupMessage']['id'];?>,<?php echo $GroupMessage['GroupMessage']['group_id'];?>);"><i class="fa fa-trash"></i></a></li>
		<?php } ?>
		</ul>
	</div>
	<div class="postedby">
		<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
		<div class="user-message-img">
			<?php if($GroupMessage['User']['image']!=''){?>
<img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $GroupMessage['User']['image'];?>" alt="">
<?php }else{ ?>
<img  src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
<?php } ?> 
		</div>
		<div class="message-user"><?php echo ucwords($GroupMessage['User']['fname'].' '.$GroupMessage['User']['lname']);?></div>
		</a>
	</div>
	<div class="message-topic">
		<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
	   <p><strong><?php echo (strlen($GroupMessage['GroupMessage']['topic']) > 15) ? substr($GroupMessage['GroupMessage']['topic'],0,15).'...' : $GroupMessage['GroupMessage']['topic'];?></strong></p>
	   </a>
	</div>
	<div class="message-description">
		<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
	<p><?php echo (strlen($GroupMessage['GroupMessage']['message']) > 45) ? substr($GroupMessage['GroupMessage']['message'],0,45).'...' : $GroupMessage['GroupMessage']['message'];?></p>
	</a>
	</div>
	
	</div>
	<div id="messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" class="panel-collapse collapse">
		<div class="message-content">
			<div class="message-description-row">
				<h2><?php echo stripcslashes($GroupMessage['GroupMessage']['topic']);?></h2>
				<p><?php echo stripcslashes($GroupMessage['GroupMessage']['message']);?></p>
			</div>
			
<?php if(($group_user_type=='M' || $group_user_type=='O')){ ?>
			<div class="message-reply" id="message_reply_<?php echo $GroupMessage['GroupMessage']['id'];?>">
				<textarea class="form-control" rows="3" id="reply_<?php echo $GroupMessage['GroupMessage']['id'];?>" name="reply_<?php echo $GroupMessage['GroupMessage']['id'];?>"></textarea>
				<button type="button" class="btn btn-primary" onclick="message_reply_submit(<?php echo $GroupMessage['GroupMessage']['id'];?>)">Submit</button>
			</div>
<?php } ?>

<div class="clearfix"></div>

<div class="message-reply-row">
<h4>Message Reply</h4>
<div class="message-list-row">
<ul id="commentscontainer_<?php echo $GroupMessage['GroupMessage']['id'];?>">

<?php foreach($GroupMessage['GroupMessageReply'] as $MessageReply){?>
  <li>
  <?php $ReplyUserDetail = $this->requestAction('group/getUserDetails/'.$MessageReply['replied_by']);?>
    <div class="reply-user-img">
      <?php if($ReplyUserDetail['User']['image']!=''){?>
      <img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $ReplyUserDetail['User']['image'];?>" alt="">
      <?php }else{ ?>
      <img  src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
      <?php } ?> 
    </div>
    <div class="reply-content">

       <?php if($MessageReply['replied_by'] == $this->Session->read('userData.User.id') || $group_user_type=='O'){?>
            <div class="reply-action">
            <a href="javascript:void(0)" class="Reply_Assign_link" data-id="<?php echo $MessageReply['id'];?>"  data-reply="<?php echo $MessageReply['reply'];?>" data-group_id='<?php echo $GroupMessage['GroupMessage']['group_id'];?>' data-toggle="modal" data-target="#editreplyModal" class="edit-reply">
  						<i class="fa fa-pencil"></i>
  					</a>
  					<a href="javascript:void(0)" onclick="delete_reply(<?php echo $MessageReply['id'];?>,<?php echo $GroupMessage['GroupMessage']['group_id'];?>)" class="trash-reply">
  						<i class="fa fa-trash"></i>
  					</a>
  				  </div>
		<?php } ?>


      <div>
        <span class="user-replyname"><?php echo ucwords($ReplyUserDetail['User']['fname'].' '.$ReplyUserDetail['User']['lname']);?></span>
        <span class="reply-date"><?php echo date('M j, Y, h:i A',strtotime($MessageReply['created']));?></span>
      </div>  
      <div class="clearfix"></div>
      <p><?php echo stripslashes($MessageReply['reply']);?></p>
    </div>
  </li>
  <?php } ?>

  
</ul>
</div>
</div>


		</div>			
	</div>
<div class="clearfix"></div>	
