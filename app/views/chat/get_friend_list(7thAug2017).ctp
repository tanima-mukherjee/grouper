<script>
function show_select_chat(friend_id,friend_name){
	
	$.ajax({
	  type: "POST",
	  url: "<?php echo $this->webroot?>chat/show_select_chat/",
	  data: { friend_id: friend_id},
	  success: function(msg)
	  {				
		  //alert(msg);
		  $(".actv_all").removeClass('active');
			$("#search").val(friend_name);
			$("#chat_replace").html(msg);
			$("#main_search_html").hide();
			$("#active_li_"+friend_id).addClass('active');
											
	  }
	});
	
}
</script>

<div class="frndlist">
	<ul>
		<?php if(!empty($ArUserSearch)){?>
		<?php foreach($ArUserSearch as $UserSearch){?>
		<li>
			<div class="left-memimg">
				<?php  if($UserSearch['User']['image']!='')	
				{ ?>
					<img src="<?php echo $this->webroot;?>user_images/thumb/<?php echo $UserSearch['User']['image'];?>" alt="img">
                <?php }else{ ?>
					<img src="<?php echo $this->webroot;?>images/noimage.jpg" alt="img">
                <?php } ?> 
            </div>
			<p><a href="javascript:void(0)" onclick="show_select_chat(<?php echo $UserSearch['User']['id'];?>,'<?php echo $UserSearch['User']['fname'].' '.$UserSearch['User']['lname'];?>')"><?php echo $UserSearch['User']['fname'].' '.$UserSearch['User']['lname'];?></a></p>
		</li>
		<?php } ?>  
		<?php }else{ ?>
		No Friends found!	
		<?php } ?>
		
	</ul>
</div>

