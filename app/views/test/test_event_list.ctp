<div id ="recent_event" name ="recent_event">
        <div class="events-scroll">
          <ul>
            <?php   if(!empty($event_list)) {
                     foreach($event_list as $events)
                      { ?> 
            <li>
              <div class="event-title">
               <!--  <h4><a href="#" data-toggle="modal" data-target="#eventModal" onclick="$('#popup-event-id').val('<?php echo $events['id'];?>');" ><?php echo $events['event_name']; ?></a></h4> -->
               <h4>
                <a  class="event_details" href="<?php echo $this->webroot?>test/detail_event/<?php echo $events['id'];?>" ><?php echo $events['event_name']; ?></a>
                </h4>

              </div>
              <div class="event-right">
                  <?php if($events['show_edit'] == '1') { ?>
              <a href="<?php echo $this->webroot?>event/edit_event/<?php echo $events['id']; ?>" class="event-edit-box"><i class="fa fa-pencil"></i></a>
               <?php } ?>



                <?php  if($events['is_multiple_date'] == '1') { ?>
            <span class="event-time"><?php echo $events['event_start_date_time']; ?> - <?php echo $events['event_end_date_time']; ?></span>
             <?php } else { ?>
             <span class="event-time"><?php echo $events['event_date_time']; ?></span>
              <?php } ?>
                  </div>
              
            <div class="clearfix"></div>  
            </li>
            <?php } } else { ?>
                     <div class="event-right"> No events found </div>
                    <?php } ?>
          
          </ul>
        </div>
        </div>