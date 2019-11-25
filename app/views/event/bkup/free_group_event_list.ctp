<div class="recent-event">
      <div class="calendar-heading">
        <h4><?php echo date('jS F Y', strtotime($selectdate));?></h4>
      </div>
            <?php   if(!empty($event_free_group_list)) {
           foreach($event_free_group_list as $events)
            {  
         ?>      
      <div class="calendar-box">
        <div class="event-location">
         
           <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $events['latitude'];?>,<?php echo $events['longitude'];?>&amp;output=embed"></iframe>
         
        
         
        </div>
        <div class="event-des">
          <h4><?php echo $events['event_name']; ?></h4>
           <span class="event-place"> GROUP - <?php echo ucfirst($events['group_name']); ?></span>
         <?php  if($events['is_multiple_date'] == '1') { ?>
          <div class="event-time"><span>Multiple</span></div>
          <?php } else { ?>
            <div class="event-time"><span>Single</span></div>
          <?php } ?>

          <p><?php echo $events['desc']; ?></p>
          <span class="event-place"><?php echo $events['location']; ?></span>
          <div class="clearfix"></div>
            <?php  if($events['is_multiple_date'] == '1') { ?>
       <div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_start_date_time']; ?> - <?php echo $events['event_end_date_time']; ?> </div>
         <?php } else { ?>
           <div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_date_time']; ?> </div>
            <?php } ?>
          
        </div>
      <div class="clearfix"></div>  
      </div>
      <?php } } else { ?>
         <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br> No Free group events found </div>
        <?php } ?>
 
      </div>