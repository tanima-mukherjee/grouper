<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
<script src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>
 <script>
    $(document).ready(function() {
      $(".eventbox").fancybox({
        type: 'iframe',
        width: 600  
      });
    });
  </script>

<script>
 var WEBROOT = '<?php echo $this->webroot;?>';
function event_calender2(selected_state_id,selected_city_id){

  $('#eve_calender2').datetimepicker({
    format:'Y-m-d ',
    formatDate:'Y-m-d ',
    minDate: 0,
    defaultDate:'<?php echo date('Y-m-d '); ?>', // it's my birthday
    //minDate:'<?php echo date('Y-m-d'); ?>',
    timepicker:false,
  onSelectDate: function (dateText, inst) {
  //alert(dateText);
  date = new Date(dateText);
  var dd = date.getDate(); 
  if(dd<10){ var dd= '0'+dd; }
  var mm = date.getMonth()+1;
  if(mm<10){ var mm= '0'+mm; }
  var yyyy = date.getFullYear();
  
  var total_dt= yyyy+'-'+mm+'-'+dd;
  //alert(total_dt);

   
     jQuery.ajax({
      
          type: "GET",
          url: WEBROOT+"home/community_event_list",
          data: {total_dt:total_dt,selected_state_id:selected_state_id,selected_city_id:selected_city_id},
          success: function(response){
              //      alert(response);
           jQuery("#recent_community_event").html(response);
          }
      });
    }
  
  });

};
</script>

 
 
  <main class="main-body">  
     
  <div class="group-calender">
          <div class="container">
            <h2>Community Event Calendar</h2>
                
          
		<div class="clearfix"></div>
		
            <div id ="recent_community_event" name ="recent_community_event">
            <div class="recent-event">
            <div class="calendar-heading">
              <h4><?php echo date('jS F Y');?></h4>
			  <div class="pull-right">
          <span class="event-calender">
            <i class="fa fa-event-calender" name="eve_calender2" id="eve_calender2" onclick="event_calender2('<?php echo $selected_state_id ?>','<?php echo $selected_city_id ?>')"></i>                            
          </span>
          </div>
            </div>
              <?php   if(!empty($event_list)) {
                 foreach($event_list as $events)
                  {?>   
                     
            <div class="calendar-box">
              <div class="event-location">
               
                 <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $events['latitude'];?>,<?php echo $events['longitude'];?>&amp;output=embed"></iframe>
               
              
               
              </div>
              <div class="event-des">
                <h4><?php echo stripslashes($events['event_name']); ?></h4>
				<?php  if($events['is_multiple_date'] == '1') { ?>
                <div class="event-time"><span><i class="tag-icon"></i> Multiple</span></div>
                <?php } else { ?>
                  <div class="event-time"><span><i class="tag-icon"></i> Single</span></div>
                <?php } ?>

                   <?php if($events['show_edit'] == '1') { ?>

					 <div class="event-edit">
					  
					  <a  class="eventbox event-btn" href="<?php echo $this->webroot?>event/edit_event/<?php echo $events['id']; ?>">
						<span>Edit</span>
						<i class="fa fa-pencil"></i>
					  </a>  
					 </div> 

          			<?php } ?>
                              
				<div class="clearfix"></div>
				<span class="groupname"> GROUP - <?php if($events['group_type'] == 'B') { ?> <a href="<?php echo $this->webroot ?>group/group_detail/<?php echo $events['group_id'];?>">
        <?php } ?><?php echo ucfirst(stripslashes($events['group_name'])); ?></a></span>
				<p><?php echo $events['desc']; ?></p>
				<ul class="event-listing">	
					<li><?php  if($events['is_multiple_date'] == '1') { ?>
						<div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_start_date_time']; ?> - <?php echo $events['event_end_date_time']; ?> </div>
					   <?php } else { ?>
						 <div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_date_time']; ?> </div>
						  <?php } ?>
				    </li>
					<li><i class="fa fa-map-marker"></i> <span class="event-place"><?php echo $events['location']; ?></span></li>					
				</ul>  
                <?php  if($events['group_type'] == 'B' && $events['deal_amount']>0) { ?>
                <div class="deal-amount"><span class="amount-price">$<?php echo $events['deal_amount']; ?></span>Deal amount</div>
                <?php } ?>
              </div>
            <div class="clearfix"></div>  
            </div>
            <?php } } else { ?>
               <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>  No Free group events found </div>
              <?php } ?>
       
            </div>
            </div>

      
    </div>    
  </div>

  </main>
