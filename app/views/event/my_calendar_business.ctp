<div class="recent-event">
<div class="calendar-heading">
	  <h4 id="cal_date_selected"><?php echo date('jS F Y', strtotime(date('Y-m-d')));?></h4>
		<div class="pull-right">
		  <span class="event-calender">
		  <i class="fa fa-event-calender"></i>
		  </span>
		</div>
</div>

<div class="col-md-3">
<div class="calendar-heading">
 
<div class="calendar" id="cal_business"></div>

<script type="text/javascript">
//<![CDATA[
$(function () {

$('#wrapper .version strong').text('v' + $.fn.pignoseCalendar.version);

	function onSelectHandlerBusiness(date, context) {
	
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
		  url: WEBROOT+"event/business_group_event_list",
		  data: {total_dt:text, allgroups: '<?php echo $allgroups; ?>'},
		  success: function(response){
			  arr= response.split('@@$$%%#');
			  jQuery("#recent_event_business").html(arr['0']);
			  jQuery("#cal_date_selected").html(arr['1']);
		  }
		});
	}
	
	function onNextBusiness(info, context){
		/*for(var key in context) {
			var value = context[key];
			alert(key+'===='+value);
		}*/
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
		  url: WEBROOT+"event/fetch_event_dates_business_grp",
		  data: {other_month:next_month_date, allgroups: '<?php echo $allgroups; ?>'},
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
	
	function onPrevBusiness(info, context){
	
		/*for(var key in context) {
			var value = context[key];
			alert(key+'===='+value);
		}*/
		
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
		  url: WEBROOT+"event/fetch_event_dates_business_grp",
		  data: {other_month:prev_month_date, allgroups: '<?php echo $allgroups; ?>'},
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
	$('#cal_business').pignoseCalendar({
		select: onSelectHandlerBusiness,
		next: onNextBusiness,
		prev: onPrevBusiness
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
	if(count($arr_event_dates_business) > 0){
	for($k=0; $k<count($arr_event_dates_business); $k++){
	
	if($arr_event_dates_business[$k]==$today){
?>				
		jQuery("#cal<?php echo $arr_event_dates_business[$k] ?>").css("background-color", "#FF0000");
<?php
	}
else{
?>
		jQuery("#cal<?php echo $arr_event_dates_business[$k] ?>").css("background-color", "#7B68EE");
		jQuery("#cal<?php echo $arr_event_dates_business[$k] ?>").css("color", "#ffffff");
<?php
	}
	
	} } ?>
	
});
</script>
</div>
</div>
<div class="col-md-9" id="recent_event_business">
<?php   if(!empty($event_business_group_list)) {
foreach($event_business_group_list as $events)
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
<span class="groupname"> GROOP - <a href="<?php echo $this->webroot ?>group/group_detail/<?php echo $events['group_id'];?>"><?php echo ucfirst(stripslashes($events['group_name'])); ?></a></span>

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


<?php  if($events['group_type'] == 'B') { ?>
<div class="deal-amount"><span class="amount-price">$<?php echo $events['deal_amount']; ?></span>Deal amount</div>
<?php } ?>

</div>
<div class="clearfix"></div>  
</div>
<?php } } else { ?>
<div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>  No Business group events found </div>
<?php } ?>
</div>
</div>