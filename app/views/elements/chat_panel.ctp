<script>
function show_select_chat(friend_id,friend_name){
	//alert(friend_id);
	$.ajax({
	  type: "POST",
	  url: "<?php echo $this->webroot?>chat/show_select_chat/",
	  data: { friend_id: friend_id},
	  success: function(msg)
	  {				
		  //alert(response);
		  
		  /*var expl = response.split('@@@@');	
		  var msg = expl[0];
		  var script = expl[1];*/
		  //alert(script);
			
			$(".actv_all").removeClass('active');
			$("#search").val('');
			$("#chat_replace").html(msg);
			//$("#all_script").html(script);
			$("#main_search_html").hide();
			$("#active_li_"+friend_id).addClass('active');
			$('#cstream').animate({ scrollTop: $('#cstream')[0].scrollHeight}, 2000);
											
	  }
	});
	
}

$(document).ready(function() {
  var selected_friend = <?php echo $friend_id ;?> 
  //alert(selected_friend);
  $('.active').removeClass('active');
  //$("#active_li_"+selected_friend).addClass('active');
  //alert(selected_friend);
   $.ajax({
	  type: "POST",
	  url: "<?php echo $this->webroot?>chat/show_select_chat/",
	  data: { friend_id: selected_friend},
	  success: function(msg)
	  {				
		  //alert(msg);
			
			$(".actv_all").removeClass('active');
			//$("#search").val(friend_name);
			$("#chat_replace").html(msg);
			$("#main_search_html").hide();
			$("#active_li_"+selected_friend).addClass('active');
			$('#cstream').animate({ scrollTop: $('#cstream')[0].scrollHeight}, 2000);
											
	  }
	});
 
});

setInterval(function(){friendChatList();}, 2000);
 
function friendChatList()
{
	

<?php if(!empty($ArFriendlist)){
		foreach($ArFriendlist as $key=>$Friend){ 
	?>
			$.ajax({
					type: 'get',
					url: WEBROOT+'chat/getReceiveChatCount',
					data: { friend_id: <?php echo $Friend['Sender']['id'];?>},
					success: function(response)
					{
						if(response == 0){
						jQuery("#friend_chat_id_<?php echo $Friend['Sender']['id'];?>").removeClass('counter');
						jQuery("#friend_chat_id_<?php echo $Friend['Sender']['id'];?>").html('');
						}else{
						jQuery("#friend_chat_id_<?php echo $Friend['Sender']['id'];?>").addClass('counter');
						jQuery("#friend_chat_id_<?php echo $Friend['Sender']['id'];?>").html(response);
						}
					}
				});
			
			
		<?php }
		
	}?>
	
	
	
}
</script>

		<ul id="friend_chat_list">
		     <?php if(!empty($ArFriendlist)){
					foreach($ArFriendlist as $key=>$Friend){
					$total_count = $this->requestAction('chat/getReceiveChat/'.$Friend['Sender']['id']);
				 ?>
              <li  class="actv_all <?php if($key == 0){?> active <?php } ?>"  id="active_li_<?php echo $Friend['Sender']['id'];?>" >
                <div class="left-memimg">
				<?php  if($Friend['Sender']['image']!='')	
					{ ?>
					<img src="<?php echo $this->webroot;?>user_images/thumb/<?php echo $Friend['Sender']['image'];?>" alt="img">
                <?php }else{ ?>
					<img src="<?php echo $this->webroot;?>images/noimage.jpg" alt="img">
                <?php } ?> 
                </div>
                <p><a href="javascript:void(0)" onclick="show_select_chat(<?php echo $Friend['Sender']['id'];?>,'<?php echo $Friend['Sender']['fname'].' '.$Friend['Sender']['lname'];?>')"><?php echo ucwords($Friend['Sender']['fname'].' '.$Friend['Sender']['lname']);?></a></p>
				<div <?php if($total_count > 0){?> class="counter" <?php } ?> id="friend_chat_id_<?php echo $Friend['Sender']['id'];?>"><?php echo ($total_count > 0) ? $total_count : '';?></div>
              </li>
              <?php } 
              } ?>
              
            </ul>
          
