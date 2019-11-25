<?php   if(!empty($event_PO_group_list)) {
 foreach($event_PO_group_list as $events)
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
 <span class="groupname"> GROUP - <a href="<?php echo $this->webroot ?>group/group_detail/<?php echo $events['group_id'];?>"><?php echo ucfirst(stripslashes($events['group_name'])); ?></a></span>
 
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


<?php  if($events['group_type'] == 'B') { ?>
	<div class="deal-amount"><span class="amount-price">$<?php echo $events['deal_amount']; ?></span>Deal amount</div>
<?php } ?>

</div>
<div class="clearfix"></div>  
</div>
<?php } } else { ?>
<div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>  No PO group events found </div>
<?php } 
echo '@@$$%%#';
echo date('jS F Y', strtotime($selectdate));
?>