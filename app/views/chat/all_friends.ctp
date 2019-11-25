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