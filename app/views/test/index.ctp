
 <script src="<?php echo $this->webroot?>js/moment.min.js"></script> 
<!--     <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet">    -->
 <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/pignose.calendar.css">
<script src="<?php echo $this->webroot?>js/pignose.calendar.js"></script>

<script>
 var WEBROOT = '<?php echo $this->webroot;?>';
function event_calender(){

  $('#eve_calender').datetimepicker({
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
    var group_id = $('#event_calender_group_id').val();
   
     jQuery.ajax({
      
          type: "GET",
          url: WEBROOT+"event/event_list",
          data: {group_id:group_id,total_dt:total_dt},
          success: function(response){
              //      alert(response);
           jQuery("#recent_event").html(response);
          }
      });
    }
  
  });

};
</script>


<!-- Modal -->
<!-- <div id="eventModal" class="modal fade" role="dialog">
  <div class="modal-dialog event-modal-dialog">

    <!-- Modal content-->
   <!-- <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       
        <h4 class="modal-title">GROUP - Table</h4>
      </div>
      <div class="modal-body">		
       <input type="text" name="popup-event-id" id="popup-event-id" value="" />
        <h2>Lorem Ipsum is simply dummy text of the printing</h2>
		<div class="event-date pull-left"><i class="fa fa-clock-o"></i> Dec 1, 2017, 09:17 AM</div>
		<div class="event-date"><i class="fa fa-map-marker"></i> Kolkata, West Bengal, India</div>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
		<div class="event-map">
			 <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=22.572646,88.36389499999996&amp;output=embed"></iframe>
		</div>
      </div>     
    </div>

  </div>
</div> -->


<main class="main-body">
	<div class="container">
	<div class="row">
	<div class="col-md-3">
		<div class="calendar"></div>
		  <script type="text/javascript">
			//<![CDATA[
			$(function () {
				$('#wrapper .version strong').text('v' + $.fn.pignoseCalendar.version);

				function onSelectHandler(date, context) {
				  

					/**
					 * @date is an array which be included dates(clicked date at first index)
					 * @context is an object which stored calendar interal data.
					 * @context.calendar is a root element reference.
					 * @context.calendar is a calendar element reference.
					 * @context.storage.activeDates is all toggled data, If you use toggle type calendar.
					 * @context.storage.events is all events associated to this date
					 */

					var $element = context.element;
					var $calendar = context.calendar;
					var $box = $element.siblings('.box').show();
					var text = '';

					if (date[0] !== null) {
						text += date[0].format('YYYY-MM-DD');
					}

					if (date[0] !== null && date[1] !== null) {
						text += ' ~ ';
					}
					else if (date[0] === null && date[1] == null) {
						text += 'nothing';
					}

					if (date[1] !== null) {
						text += date[1].format('YYYY-MM-DD');
					}

					//alert(text);
				   // $box.text(text);
				    jQuery.ajax({
      
		          type: "GET",
		          url: WEBROOT+"test/test_event_list",
		          data: {total_dt:text},
		          success: function(response){
		                   // alert(response);
		           jQuery("#recent_event").html(response);
		          }
		      });
				}

				
				// Default Calendar
				$('.calendar').pignoseCalendar({
					select: onSelectHandler
				});

				
			  

			});
			//]]>
		</script>
		</div>
		<div class="col-md-9">
			<div class="upcoming-events-list">
				<h2>Upcoming events/Details</h2>
				<div id ="recent_event" name ="recent_event">
				<div class="events-scroll">
					<ul>
						<?php   if(!empty($event_list)) {
		                 foreach($event_list as $events)
		                  { ?> 
						<li>
							<div class="event-title">
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
					<!--<li>
								<div class="event-title">
									<h4><a href="#">dummy text of the printing</a></h4>
								</div>
							  <span class="event-time">Dec 1, 2017, 09:17 AM</span>
						    <div class="clearfix"></div>	
						</li>
						<li>
							<div class="event-title">
								<h4><a href="#">event1</a></h4>
							</div>
							<span class="event-time">Dec 1, 2017, 09:17 AM</span>
						<div class="clearfix"></div>	
						</li>
						<li>
							<div class="event-title">
								<h4><a href="#">the printing</a></h4>
							</div>
							<span class="event-time">Dec 1, 2017, 09:17 AM</span>
						<div class="clearfix"></div>	
						</li>
						<li>
							<div class="event-title">
								<h4><a href="#">the printing</a></h4>
							</div>
							<span class="event-time">Dec 1, 2017, 09:17 AM</span>
						<div class="clearfix"></div>	
						</li>
						<li>
							<div class="event-title">
								<h4><a href="#">Lorem Ipsum is simply dummy text of the printing</h4>
							</div>
							<span class="event-time">Dec 1, 2017, 09:17 AM</span>
						<div class="clearfix"></div>	
						</li> -->
					</ul>
				</div>
				</div>
			<div class="clearfix"></div>	
			</div>
		</div>
	</div>

	</div>	
</main>



<div class="group-calender">
    <div class="container">
      <h3>Group Calendar</h3>
          
      <div id ="recent_event" name ="recent_event">
     
<div class="recent-event">
            <div class="calendar-heading">
              <h4><?php echo date('jS F Y');?></h4>
                    </div>
              <?php   if(!empty($event_list)) {
                 foreach($event_list as $events)
                  {?>   
                     
            <div class="calendar-box">
            
               
              <div class="event-des">
                <h4><?php echo $events['event_name']; ?></h4>
             <?php if($group_member_type == 'owner'){ ?>
                 <div class="event-edit">
                    
                    <a  class="eventbox event-btn" href="<?php echo $this->webroot?>event/edit_event/<?php echo $events['id']; ?>">
                        <span>Edit</span>
                        <i class="fa fa-pencil"></i>
                    </a>    
                 </div> 
                 <?php } ?>
        <div class="clearfix"></div>
        <span class="groupname"> GROUP - <?php echo ucfirst(stripslashes($events['group_name'])); ?></span>
        
              </div>
            <div class="clearfix"></div>  
            </div>
            <?php } } else { ?>
               <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>  No events found </div>
              <?php } ?>
       
            </div>
      </div>

      
    </div>    
  </div>
  