<?php if(count($ArGroupMessage)>0){?>
      <?php foreach($ArGroupMessage as $key=>$GroupMessage){?>
				<li>
					<a href="javascript:void(0)" data-toggle="collapse" data-target="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>">
					<div class="message-time">
						<span class="time-info"><?php echo date('M j, Y, h:i A',strtotime($GroupMessage['GroupMessage']['created']));?></span>
					</div>
					<div class="reply text-center">
						<span class="reply-icon">							
							<a href="javascript:void(0)" onclick="$('#reply_<?php echo $GroupMessage['GroupMessage']['id'];?>').focus()"><img src="<?php echo $this->webroot?>images/reply-icon.png"></a>						
						</span>
						<span class="reply-counter"><?php echo count($GroupMessage['GroupMessageReply']);?></span>
					</div>
					<div class="postedby">
						<div class="user-message-img">
							<?php if($GroupMessage['User']['image']!=''){?>
              <img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $GroupMessage['User']['image'];?>" alt="">
              <?php }else{ ?>
              <img  src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
              <?php } ?> 
						</div>
						<div class="message-user"><?php echo ucwords($GroupMessage['User']['fname'].' '.$GroupMessage['User']['lname']);?></div>
					</div>
					<div class="message-topic">
					   <p><strong><?php echo (strlen($GroupMessage['GroupMessage']['topic']) > 15) ? substr($GroupMessage['GroupMessage']['topic'],0,15).'...' : $GroupMessage['GroupMessage']['topic'];?></strong></p>
					</div>
					<div class="message-description">
					<p><?php echo (strlen($GroupMessage['GroupMessage']['message']) > 45) ? substr($GroupMessage['GroupMessage']['message'],0,45).'...' : $GroupMessage['GroupMessage']['message'];?></p>
					</div>
					
					<div class="clearfix"></div>
					</a>
					<div class="clearfix"></div>
					<div id="messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" class="collapse">
						<div class="message-content">
							<div class="message-description-row">
								<h2><?php echo stripcslashes($GroupMessage['GroupMessage']['topic']);?></h2>
								<p><?php echo stripcslashes($GroupMessage['GroupMessage']['message']);?></p>
							</div>
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
							<div class="message-reply">
								<textarea class="form-control" rows="5" id="reply_<?php echo $GroupMessage['GroupMessage']['id'];?>" name="reply_<?php echo $GroupMessage['GroupMessage']['id'];?>"></textarea>
								<button type="button" class="btn btn-primary" onclick="message_reply_submit(<?php echo $GroupMessage['GroupMessage']['id'];?>)">Submit</button>
							</div>
						</div>			
					</div>
				<div class="clearfix"></div>	
				</li>
        <?php } ?>

        <?php 
              $paginationData = paging_ajax($lastpage,$page,$prev,$next,$lpm1,"paging_f");
          ?>
        
          <script language="javascript">
          function paging_f(val)
          {   
            // ajax json
            if(val!=''){
            var group_id = '<?php echo $group_id; ?>';
            $.ajax({
                 type: "GET",
                 url: WEBROOT+"group/post_all_reply",
                 data: { page: val, group_id: group_id },
                 success: function(msg){
                 //alert(msg);
                 if(msg!=0){
                  $('#free').html(msg); 
                }
                
                 }
              });
            }
          }
          </script>
        
          <?php     
            if(isset($paginationData))
            {
              echo $paginationData;
            }
          ?>
          <form name="frm_act" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <input type="hidden" name="mode" value="">
            <input type="hidden" name="page" value="">    
          </form>


        <?php }else{
          echo 'No Posts Found!!';
        } ?>