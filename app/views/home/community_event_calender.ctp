<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<!--date picker start-->
<script src="<?php echo $this->webroot?>js/moment.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/pignose.calendar.css">
<script src="<?php echo $this->webroot?>js/pignose.calendar.js"></script>

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



 <style>
   .error{
    color: #f00;
   }
   .fancybox-outer .fancybox-inner{ height:500px!important;}
   
   .calendar .pignose-calendar{ height:412px;}
   .calendar .pignose-calendar .pignose-calendar-header{ margin-top:3.2em;}
   .calendar .pignose-calendar .pignose-calendar-body .pignose-calendar-row{padding: 5px 0;}
 </style>
 
  <main class="main-body">  
     
  <div class="group-calender">
          <div class="container">
            <h2>Community Event Calendar</h2>
                
          
		<div class="clearfix"></div>
		
            <div id ="recent_community_event" name ="recent_community_event">
            <div class="recent-event">
			
			<div class="calendar-heading">
              <h4 id="cal_date_selected"><?php echo date('jS F Y');?></h4>
			  <div class="pull-right">
			  
			  </div>
			</div>
			<div class="col-md-3">
            <div class="calendar-heading">
             
		  <div class="calendar"></div>

		  <script type="text/javascript">
			//<![CDATA[
			$(function () {

        $('#wrapper .version strong').text('v' + $.fn.pignoseCalendar.version);

				function onSelectHandler(date, context) {
				
					
					var selected_state_id = '<?php echo $selected_state_id; ?>';
					var selected_city_id = '<?php echo $selected_city_id; ?>';
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
					jQuery.ajax({
					  type: "GET",
					  url: WEBROOT+"home/community_event_list",
					  data: {selected_state_id:selected_state_id,selected_city_id:selected_city_id,total_dt:text},
					  success: function(response){
					  	  arr= response.split('@@$$%%#');
						  jQuery("#recent_event").html(arr['0']);
						  jQuery("#cal_date_selected").html(arr['1']);
					  }
					});
				}
				
				function onNext(info, context){
					/*for(var key in context) {
						var value = context[key];
						alert(key+'===='+value);
					}*/
					var selected_state_id = '<?php echo $selected_state_id; ?>';
					var selected_city_id = '<?php echo $selected_city_id; ?>';
					var next_month_date= context.dateManager.toString();
					
					//alert(next_month_date);
					
					var today_date= new Date();
					var curMonth= today_date.getMonth()+1;
					curMonth = curMonth.toString().length > 1 ? curMonth : '0' + curMonth;
					
					var curDate= today_date.getDate();
					curDate = curDate.toString().length > 1 ? curDate : '0' + curDate;
					
					var today= today_date.getFullYear()+'-'+curMonth+'-'+curDate;
					//alert('===='+today);
					
					
					jQuery.ajax({
					  type: "GET",
					  url: WEBROOT+"home/fetch_event_dates",
					  data: {selected_state_id:selected_state_id,selected_city_id:selected_city_id, other_month:next_month_date},
					  success: function(response){
					  //alert(response);
					  		if(response!=''){
							
								jQuery("#cal"+today).css("background-color", "#FF0000");
								jQuery("#cal"+today).css("color", "#ffffff");
								
								response_arr_dates=response.split(',');	
								
								for(k=0; k<response_arr_dates.length; k++){	
								
									if(response_arr_dates[k]==today){
										jQuery("#cal"+response_arr_dates[k]).css("background-color", "#FF0000");
										jQuery("#cal"+response_arr_dates[k]).css("color", "#ffffff");	
									}
									else{
										jQuery("#cal"+response_arr_dates[k]).css("background-color", "#7B68EE");
										jQuery("#cal"+response_arr_dates[k]).css("color", "#ffffff");
									}
								}
							}
					  }
					});
				}
				
				function onPrev(info, context){
				
					/*for(var key in context) {
						var value = context[key];
						alert(key+'===='+value);
					}*/
					
					var selected_state_id = '<?php echo $selected_state_id; ?>';
					var selected_city_id = '<?php echo $selected_city_id; ?>';
					var prev_month_date= context.dateManager.toString(); ;
					
					var today_date= new Date();
					var curMonth= today_date.getMonth()+1;
					curMonth = curMonth.toString().length > 1 ? curMonth : '0' + curMonth;
					
					var curDate= today_date.getDate();
					curDate = curDate.toString().length > 1 ? curDate : '0' + curDate;
					
					var today= today_date.getFullYear()+'-'+curMonth+'-'+curDate;
					//alert(today);
					
					jQuery.ajax({
					  type: "GET",
					  url: WEBROOT+"home/fetch_event_dates",
					  data: {selected_state_id:selected_state_id,selected_city_id:selected_city_id, other_month:prev_month_date},
					  success: function(response){
					  		if(response!=''){
								
								jQuery("#cal"+today).css("background-color", "#FF0000");
								jQuery("#cal"+today).css("color", "#ffffff");
								
								response_arr_dates=response.split(',');	
								
								for(k=0; k<response_arr_dates.length; k++){
								
									if(response_arr_dates[k]==today){
										jQuery("#cal"+response_arr_dates[k]).css("background-color", "#FF0000");
										jQuery("#cal"+response_arr_dates[k]).css("color", "#ffffff");	
									}
									else{
										jQuery("#cal"+response_arr_dates[k]).css("background-color", "#7B68EE");
										jQuery("#cal"+response_arr_dates[k]).css("color", "#ffffff");
									}
								}
							}
					  }
					});
				}

				// Default Calendar
				$('.calendar').pignoseCalendar({
					select: onSelectHandler,
					next: onNext,
					prev: onPrev
				});
    });
			//]]>
			
			
			$(document).ready(function(){
			<?php 
				$today= date('Y-m-d');
			?>
				jQuery("#cal<?php echo $today; ?>").css("background-color", "#FF0000");
				jQuery("#cal<?php echo $today; ?>").css("color", "#ffffff");
			<?php
				if(count($arr_event_dates) > 0){
				for($k=0; $k<count($arr_event_dates); $k++){
				
				if($arr_event_dates[$k]==$today){
			?>				
					jQuery("#cal<?php echo $arr_event_dates[$k] ?>").css("background-color", "#FF0000");
			<?php
				}
			else{
			?>
					jQuery("#cal<?php echo $arr_event_dates[$k] ?>").css("background-color", "#7B68EE");
					jQuery("#cal<?php echo $arr_event_dates[$k] ?>").css("color", "#ffffff");
			<?php
				}
				
			 	} } ?>
				
			});
			</script>
            </div>
			</div>
			<div class="col-md-9" id="recent_event">
              <?php   if(!empty($event_list)) {
                 foreach($event_list as $events)
                  {?>   
                     
            <div class="calendar-box">
              <div class="event-location">
               
                 <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $events['latitude'];?>,<?php echo $events['longitude'];?>&amp;output=embed"></iframe>
               
              
               
              </div>
              <div class="event-des">
                <h4><?php echo stripslashes($events['event_name']); ?></h4>
				<?php  /*if($events['is_multiple_date'] == '1') { ?>
                <div class="event-time"><span><i class="tag-icon"></i> Multiple</span></div>
                <?php } else { ?>
                  <div class="event-time"><span><i class="tag-icon"></i> Single</span></div>
                <?php }*/ ?>

                   <?php if($events['show_edit'] == '1') { ?>

					 <div class="event-edit">
					  
					  <a  class="eventbox event-btn" href="<?php echo $this->webroot?>event/edit_event/<?php echo $events['id']; ?>">
						<span>Edit</span>
						<i class="fa fa-pencil"></i>
					  </a>  
					 </div> 

          			<?php } ?>
                              
				<div class="clearfix"></div>
				<span class="groupname"> GROOP - <?php if($events['group_type'] == 'B') { ?> <a href="<?php echo $this->webroot ?>group/group_detail/<?php echo $events['group_id'];?>">
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
               <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>  No Free groop events found </div>
              <?php } ?>
       		</div>
            </div>
            </div>

      
    </div>    
  </div>

  </main>
