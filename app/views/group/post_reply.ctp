<?php echo $MessageReplyCount;?>
@@@
<?php if(!empty($MessageReply)){
?>
<li>
<div class="reply-user-img">
<?php if($MessageReply['User']['image']!=''){?>
<img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $MessageReply['User']['image'];?>" alt="">
<?php }else{ ?>
<img  src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
<?php } ?>   
</div>
<div class="reply-content">
<?php if($MessageReply['GroupMessageReply']['replied_by'] == $this->Session->read('userData.User.id') || $group_user_type=='O'){?>
<div class="reply-action">
	<a href="javascript:void(0)" class="Reply_Assign_link" data-id="<?php echo $MessageReply['GroupMessageReply']['id'];?>"  data-reply="<?php echo $MessageReply['GroupMessageReply']['reply'];?>" data-group_id='<?php echo $group_id;?>' data-toggle="modal" data-target="#editreplyModal" class="edit-reply">
		<i class="fa fa-pencil"></i>
	</a>
	<a href="javascript:void(0)" onclick="delete_reply(<?php echo $MessageReply['GroupMessageReply']['id'];?>,<?php echo $group_id;?>)" class="trash-reply">
		<i class="fa fa-trash"></i>
	</a>
</div>
<?php } ?>
<?php //$ReplyUserDetail = $this->requestAction('group/getUserDetails/'.$MessageReply['replied_by']);?>
<div>
<span class="user-replyname"><?php echo ucwords($MessageReply['User']['fname'].' '.$MessageReply['User']['lname']);?></span>
<span class="reply-date"><?php echo date('M j, Y, h:i A',strtotime($MessageReply['GroupMessageReply']['created']));?></span>
</div>	
<div class="clearfix"></div>
<p><?php echo stripslashes($MessageReply['GroupMessageReply']['reply']);?></p>
</div>
</li>

<?php } ?>