<div class="events-scroll">
					<ul>
						<?php   if(!empty($event_list)) {
		                 foreach($event_list as $events)
		                  { 
						  ?> 
						<li>
							<div class="calendar-box">
              <div class="event-location">               
                 <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $events['latitude'];?>,<?php echo $events['longitude'];?>&amp;output=embed"></iframe>
              </div>
               <?php  if($events['group_type'] == 'B' && $events['deal_amount']>0){ ?>
               <div class="event-amount">         
					<div class="deal-amount"><span class="amount-price"> <span class="currency">$</span><?php echo $events['deal_amount']; ?></span>Deal amount</div>
               </div>
			   
			   <div class="event-des">
			  	<div>
                <h4><?php echo $events['event_name']; ?></h4>
        		<!--<span class="groupname"> <?php echo stripslashes($events['group_name']); ?></span>-->
				
				<?php if($events['show_edit'] == '1') { ?>
				 <div class="event-edit">
					
          			<a  class="eventbox event-btn" href="<?php echo $this->webroot?>event/edit_event/<?php echo $events['id']; ?>">
						<span>Edit</span>
						<i class="fa fa-pencil"></i>
					</a>	
				 </div>	
				 <?php } ?>
				 </div>
       			<div class="clearfix"></div>
        		<p><?php echo stripslashes($events['desc']); ?></p>
          		<ul class="event-listing">  
				  <li><?php  if($events['is_multiple_date'] == '1') { ?>
					<div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_start_date_time']; ?> - <?php echo $events['event_end_date_time']; ?> </div>
					 <?php } else { ?>
					 <div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_date_time']; ?> </div>
					  <?php } ?>
					</li>
				  <li><i class="fa fa-map-marker"></i> <span class="event-place"><?php echo $events['location']; ?></span></li>         
				</ul>
              </div>
              <?php } 
			  else { ?>
			  
			  <div class="event-des no-deal-amount">
			  	<div>
                <h4><?php echo $events['event_name']; ?></h4>
        		<!--<span class="groupname"> <?php echo stripslashes($events['group_name']); ?></span>-->
				
				<?php if($events['show_edit'] == '1') { ?>
				 <div class="event-edit">
					
          			<a  class="eventbox event-btn" href="<?php echo $this->webroot?>event/edit_event/<?php echo $events['id']; ?>">
						<span>Edit</span>
						<i class="fa fa-pencil"></i>
					</a>	
				 </div>	
				 <?php } ?>
				 </div>
       			<div class="clearfix"></div>
        		<p><?php echo stripslashes($events['desc']); ?></p>
          		 <ul class="event-listing">  
				  <li><?php  if($events['is_multiple_date'] == '1') { ?>
					<div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_start_date_time']; ?> - <?php echo $events['event_end_date_time']; ?> </div>
					 <?php } else { ?>
					 <div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_date_time']; ?> </div>
					  <?php } ?>
					</li>
				  <li><i class="fa fa-map-marker"></i> <span class="event-place"><?php echo $events['location']; ?></span></li>         
				</ul>
              </div>
			  
			  <?php
			  }
			  ?>
              
			 
            <div class="clearfix"></div>  
            </div>
							
						<div class="clearfix"></div>	
						</li>
						<?php } } else { ?>
			               <div class="event-right"> No events found </div>
			              <?php } ?>
					
					</ul>
				</div>