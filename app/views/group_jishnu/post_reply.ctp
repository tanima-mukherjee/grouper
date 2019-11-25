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