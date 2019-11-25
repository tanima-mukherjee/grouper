								<div class="list-height">
								<?php	if(count($notification_list) > '0')
										{ ?>
									<ul id="all_notifications">
									
									<?php  foreach ($notification_list as $list) { ?>
										<li id = "group_accept_reject<?php echo $list['Notification']['id'];?>">	
                  
										 <?php $sender_details = $this->requestAction('group/sender_detail/'.$list['Notification']['sender_id']);?>
                     										
											<div class="group-img">
											<?php if($sender_details['User']['image']!='') { ?>
											
												<img src="<?php echo $this->webroot.'user_images/thumb/'.$sender_details['User']['image'];?>" alt="<?php echo ucfirst(stripslashes($sender_details['User']['fname'])).' '.ucfirst(stripslashes($sender_details['User']['lname']))?>" />
											<?php } else { ?>
												<img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="<?php echo ucfirst(stripslashes($sender_details['User']['fname'])).' '.ucfirst(stripslashes($sender_details['User']['lname']))?>" />
												<?php } ?>
											</div>											 

											<?php 
											if($list['Notification']['type']== 'G') {
											
											$group_details = $this->requestAction('group/group_details/'.$list['Notification']['group_id']);
											
											if(($list['Notification']['is_receiver_accepted'] == '0')&& ($list['Notification']['is_reversed_notification']== '0')) { ?>

											<div class="message-div" >
											<?php if(($list['Notification']['sender_type']== 'NGM')&& ($list['Notification']['receiver_type']== 'GO')) { ?>
												<span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])) ;?></i></span> requested to join your <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i> <?php } else { ?><i> Business </i><?php } ?> group <span><?php  echo ucfirst(stripslashes($group_details['Group']['group_title']));?></span>
												<?php } 
												
												else if (($list['Notification']['sender_type']== 'GO')&& ($list['Notification']['receiver_type']== 'NGM')) { ?>
													<span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname']));?></i></span> invited you to join <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i><?php } else { ?><i> Business </i><?php } ?> group <span><?php echo ucfirst(stripslashes($group_details['Group']['group_title']));?></span>
												<?php } 
												
												else if (($list['Notification']['sender_type']== 'GM')&& ($list['Notification']['receiver_type']== 'NGM')) { ?>
													<span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname']) );?></i></span> recommended you to join  <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i><?php } else { ?><i> Business </i><?php } ?> group <span><?php echo ucfirst($group_details['Group']['group_title']);?></span>
													<?php } ?>
												<div class="clearfix"></div>	
                       <?php $time = $this->requestAction('home/post_tym/'.stripcslashes($list['Notification']['id']));?>
												<div class="livetimes pull-right"><?php echo $time; ?></div>	

												<div class="actions pull-left">
													<button type="button" class="confirm-btn" onclick = "accept_group_request(<?php  echo $list['Notification']['id'] ?>);" >Accept</button>
                          
													<button type="button" class="rej-btn" onclick = "reject_group_request(<?php  echo $list['Notification']['id'] ?>);">Reject</button>
												</div>
											</div>
                      

											<div class="clearfix"></div>
											<?php } 
											
											else if (($list['Notification']['is_receiver_accepted']== '1')&& ($list['Notification']['is_reversed_notification']== '1')) { ?>

                      <div class="message-div">
                      <?php if(($list['Notification']['sender_type']== 'NGM')&& ($list['Notification']['receiver_type']== 'GO')) { ?>
                        <span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])) ;?></i></span> rejected your invitation to join <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i> <?php } else { ?><i> Business </i><?php } ?> group <span><?php  echo ucfirst($group_details['Group']['group_title']);?></span>
                        <?php } 
						else if(($list['Notification']['sender_type']== 'GO')&& ($list['Notification']['receiver_type']== 'NGM')) { ?>
                          <span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname']) );?></i></span> rejected your request to join their <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i><?php } else { ?><i> Business </i><?php } ?> group <span><?php echo ucfirst(stripslashes($group_details['Group']['group_title']));?></span>
                        <?php } 
						else if (($list['Notification']['sender_type']== 'NGM')&& ($list['Notification']['receiver_type']== 'GM')) { ?>
                          <span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname']) );?></i></span> rejected your recommendation to join <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i><?php } else { ?><i> Business </i><?php } ?> group <span><?php echo ucfirst($group_details['Group']['group_title']);?></span>
                          <?php } ?>
           

                          <div class="clearfix"></div>  
                           <?php $time = $this->requestAction('home/post_tym/'.stripcslashes($list['Notification']['id']));?>
                        <div class="livetimes pull-right"><?php echo $time; ?></div>  

                        <div class="actions">
                          <button type="button" class="confirm-btn" onclick = "remove_noti(<?php  echo $list['Notification']['id'] ?>);" >Remove</button>                         
                        </div>
                      </div>

                        <div class="clearfix"></div>
                        <?php } 
						
						
											else if (($list['Notification']['is_receiver_accepted']== '2')&& ($list['Notification']['is_reversed_notification']== '1')) { ?>
                          
                      <div class="message-div">
                      <?php if(($list['Notification']['sender_type']== 'NGM')&& ($list['Notification']['receiver_type']== 'GO')) { ?>
                        <span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname'])) ;?></i></span> accepted your invitation to join <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i> <?php } else { ?><i> Business </i><?php } ?> group <span><?php  echo ucfirst($group_details['Group']['group_title']);?></span>
                        <?php } 
						else if(($list['Notification']['sender_type']== 'GO')&& ($list['Notification']['receiver_type']== 'NGM')) { ?>
                          <span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname']));?></i></span> accepted your request to join their <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i><?php } else { ?><i> Business </i><?php } ?> group <span><?php echo ucfirst(stripslashes($group_details['Group']['group_title']));?></span>
                        <?php } 
						else if(($list['Notification']['sender_type']== 'NGM')&& ($list['Notification']['receiver_type']== 'GM')) { ?>
                          <span><i><?php echo ucfirst(stripslashes($sender_details['User']['fname']) .' '. stripslashes($sender_details['User']['lname']));?></i></span> accepted your recommendation to join <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i><?php } else { ?><i> Business </i><?php } ?> group <span><?php echo ucfirst(stripslashes($group_details['Group']['group_title']));?></span>
                          <?php } ?>
                          <div class="clearfix"></div>  
                           <?php $time = $this->requestAction('home/post_tym/'.stripcslashes($list['Notification']['id']));?>
                        <div class="livetimes pull-right"><?php echo $time; ?></div>  


                       <div class="actions">
                          <button type="button" class="confirm-btn" onclick = "remove_noti(<?php  echo $list['Notification']['id'] ?>);">Remove</button>                         
                        </div>
                      </div>

                        <div class="clearfix"></div>
                        <?php }
                         } else if($list['Notification']['type']== 'F') { ?>
											   
                           <?php if(($list['Notification']['is_receiver_accepted']== '0')&& ($list['Notification']['is_reversed_notification']== '0')) { ?>

                      <div class="message-div" >
                     
                        <span><i><?php echo ucfirst($sender_details['User']['fname'] .' '. $sender_details['User']['lname']) ;?></i></span> sent you a friend request .    

                        <div class="clearfix"></div>  
                           <?php $time = $this->requestAction('home/post_tym/'.stripcslashes($list['Notification']['id']));?>
                        <div class="livetimes pull-right"><?php echo $time; ?></div>  
                  
                        <div class="actions" >

                          
                          <button type="button" class="confirm-btn" onclick = "accept_friend_request(<?php  echo $list['Notification']['id'] ?>);" >Accept</button>
                          
                          <button type="button" class="rej-btn" onclick = "reject_friend_request(<?php  echo $list['Notification']['id'] ?>);">Reject</button>
                          
                        </div>
                      </div>
                        <div class="clearfix"></div>
                        <?php } else if(($list['Notification']['is_receiver_accepted']== '1')&& ($list['Notification']['is_reversed_notification']== '1')) { ?>

                      <div class="message-div" >
                     
                        <span><i><?php echo ucfirst($sender_details['User']['fname'] .' '. $sender_details['User']['lname']) ;?></i></span> rejected your friend request .   
                        <div class="clearfix"></div>  
                           <?php $time = $this->requestAction('home/post_tym/'.stripcslashes($list['Notification']['id']));?>
                        <div class="livetimes pull-right"><?php echo $time; ?></div>  
                   
                        <div class="actions">
                          <button type="button" class="confirm-btn" onclick = "remove_noti(<?php  echo $list['Notification']['id'] ?>);">Remove</button>                         
                        </div>
                      </div>
                        <div class="clearfix"></div>
                        <?php } else if(($list['Notification']['is_receiver_accepted']== '2')&& ($list['Notification']['is_reversed_notification']== '1')) { ?>

                      <div class="message-div" >
                     
                        <span><i><?php echo ucfirst($sender_details['User']['fname'] .' '. $sender_details['User']['lname']) ;?></i></span> accepted your friend request .   
                        <div class="clearfix"></div>  
                          <?php $time = $this->requestAction('home/post_tym/'.stripcslashes($list['Notification']['id']));?>
                        <div class="livetimes pull-right"><?php echo $time; ?></div>  
                   
                        <div class="actions">
                          <button type="button" class="confirm-btn" onclick = "remove_noti(<?php  echo $list['Notification']['id'] ?>);">Remove</button>                         
                        </div>
                      </div>
                        <div class="clearfix"></div>
                        <?php } ?>


											 	<?php } else  if($list['Notification']['type']== 'E') { 

                            $group_details = $this->requestAction('group/group_details/'.$list['Notification']['group_id']);

											 if(($list['Notification']['is_receiver_accepted']== '0')&& ($list['Notification']['is_reversed_notification']== '0')) { ?>

                      <div class="message-div" >
                     
                        <span><i><?php echo ucfirst($sender_details['User']['fname'] .' '. $sender_details['User']['lname']) ;?></i></span> sent you an event reminder for <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i><?php } else { ?><i> Business </i><?php } ?> group <span><?php  echo $group_details['Group']['group_title'];?>.</span>   
                        <div class="clearfix"></div>  
                         <?php $time = $this->requestAction('home/post_tym/'.stripcslashes($list['Notification']['id']));?>
                        <div class="livetimes pull-right"><?php echo $time; ?></div>  
                   
                        <div class="actions">
                          <button type="button" class="confirm-btn" onclick = "remove_noti(<?php  echo $list['Notification']['id'] ?>);">Remove</button>                         
                        </div>
                      </div>
                        <div class="clearfix"></div>
                        <?php } ?>


											 	<?php } else if  ($list['Notification']['type']== 'P') { 

                          $group_details = $this->requestAction('group/group_details/'.$list['Notification']['group_id']);

                          if(($list['Notification']['is_receiver_accepted']== '2')&& ($list['Notification']['is_reversed_notification']== '0')) { ?>

                      <div class="message-div" >
                    
                        <span><i><?php echo ucfirst($sender_details['User']['fname'] .' '. $sender_details['User']['lname']) ;?></i></span> owner of  <?php if($group_details['Group']['group_type'] == 'F' ) { ?><i> Free </i> <?php } else { ?><i> Business </i><?php } ?> group <span><?php  echo ucfirst($group_details['Group']['group_title']);?></span> sent you push notification . 
                        <div class="clearfix"></div>  
                        <span> Message : </span> <?php echo $list['Notification']['message'] ; ?>
                        <div class="clearfix"></div>  
                           <?php $time = $this->requestAction('home/post_tym/'.stripcslashes($list['Notification']['id']));?>
                        <div class="livetimes pull-right"><?php echo $time; ?></div>  
                   
                        <div class="actions">
                          <button type="button" class="confirm-btn" onclick = "remove_noti(<?php  echo $list['Notification']['id'] ?>);">Remove</button>                         
                        </div>
                      </div>
                     
                        <div class="clearfix"></div>
                        <?php } ?>
											

											 	<?php }?>
										
										</li>
									<?php } ?>

									</ul>
									<?php } else { ?>
										<ul>
										<li>											
											No notifications yet
										<div class="clearfix"></div>
										</li>
										</ul>
									<?php  } ?>
</div>


							