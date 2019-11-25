<h3>To: <span><?php echo ucwords($ArSenderDetail['User']['fname'].' '.$ArSenderDetail['User']['lname']);?></span></h3>
            
           <div id="chat">
            <div class="upperchat-part" id="cstream">
            
             
             <?php 
             $last_chat_count = 0;
             if(!empty($ArChatHistory)){
				 foreach($ArChatHistory as $ChatHistory){
			  ?>
              <div class="<?php if($user_id != $ChatHistory['Chat']['from_id']){?> chat-odd <?php }else{ ?> chat-even <?php } ?>">
                <div class="<?php if($user_id != $ChatHistory['Chat']['from_id']){?> chatper-img <?php }else{ ?> chatper-imgeven <?php } ?>">
                  
                  <?php if($user_id != $ChatHistory['Chat']['from_id']){
						if($ChatHistory['Sender']['image']!='')	
						{
					  ?>
                  <img src="<?php echo $this->webroot;?>user_images/thumb/<?php echo $ChatHistory['Sender']['image'];?>" alt="image">
				  <?php }else{ ?>
				<img src="<?php echo $this->webroot;?>images/noimage.jpg" alt="image">
				  <?php } ?>  
                  <?php }else if($user_id == $ChatHistory['Chat']['from_id']){
					  if($ChatHistory['Sender']['image']!='')	
						{
					  ?>
				  
				  <img src="<?php echo $this->webroot;?>user_images/thumb/<?php echo $ChatHistory['Sender']['image'];?>" alt="image">  
				   <?php }else{ ?>
				  <img src="<?php echo $this->webroot;?>images/noimage.jpg" alt="image">
				  <?php } ?>  
				  <?php } ?>
				  
                </div>
                <div class="<?php if($user_id != $ChatHistory['Chat']['from_id']){?> chattext-part <?php }else{ ?> chattext-partright <?php } ?>">
                  <p><?php echo $ChatHistory['Chat']['conversation'];?></p>
                  <h4 class="msg-time time-"><?php echo date('Y-m-d h:i A',strtotime($ChatHistory['Chat']['created']));?></h4>
                </div>
              </div>
              <?php 
				$last_chat_count++;
				}
              } ?>
              
              
			<?php 
			if($last_chat_count > 0)
			{
			$last_chat_count = $last_chat_count-1;
			}
			?>
            </div>
            
           </div>
           
            <div class="textpost-part">
              <form method="post" id="msenger" action="">
					<textarea name="msg" id="msg-min"></textarea>
					<input type="hidden" name="mid" id="mid" value="<?php echo $user_id;?>">
					<input type="hidden" name="fid" id="fid" value="<?php echo $ArSenderDetail['User']['id'];?>">
					<input type="hidden" name="to_name" id="to_name"  value="<?php echo ($ArSenderDetail['User']['fname'].' '.$ArSenderDetail['User']['lname']);?>">
					<input type="hidden" name="from_name" id="from_name" value="<?php echo ($ArUserDetail['User']['fname'].' '.$ArUserDetail['User']['lname']);?>">
					<input type="submit" value="Send" id="sb-mt" class="submit-btnarchitech">
				</form>
			<div id="dataHelper" last-id="<?php echo $ArChatHistory[$last_chat_count]['Chat']['id'];?>"></div>
			<div class="clearfix"></div>
            </div>
          
          
<script>
//$('#msg-min').focus();
 //$('#msg-focus-14').focus();
 //$('#test').focus();
 $('#msenger').submit(function(e){
 //alert(1);
 $('#msenger textarea').attr('readonly', 'readonly');
 $('#sb-mt').attr('disabled', 'disabled'); // Disable submit button
 sendMsg();
 e.preventDefault(); 
 });

 $('#cstream').animate({ scrollTop: $('#cstream')[0].scrollHeight}, 2000);
</script>        
      
