<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
<script src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>
<script>
 var WEBROOT = '<?php echo $this->webroot;?>';
function event_calender1(){

  $('#eve_calender1').datetimepicker({
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
          url: WEBROOT+"event/free_group_event_list",
          data: {total_dt:total_dt},
          success: function(response){
              //      alert(response);
           jQuery("#recent_free_group_event").html(response);
          }
      });
    }
  
  });

};
</script>

<script>
 var WEBROOT = '<?php echo $this->webroot;?>';
function event_calender2(){

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
          url: WEBROOT+"event/business_group_event_list",
          data: {total_dt:total_dt},
          success: function(response){
              //      alert(response);
           jQuery("#recent_business_group_event").html(response);
          }
      });
    }
  
  });

};
</script>

 
 
  <main class="main-body">  
  <div class="groups-member">
    <div class="container" style="position:relative;">
     
      <div class="group-tab">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#group">Free Group(s)</a></li>
          <li><a data-toggle="tab" href="#member">Business Group(s)</a></li>
        </ul>
      </div>
      <div class="tab-content">
        <div id="group" class="tab-pane fade in active">
       
       
        <div class="group-calender">
          <div class="container">
            <h3>Group Calendar</h3>
                
          <div class="pull-right">
          <span class="event-calender">
            <i class="fa fa-event-calender" name="eve_calender1" id="eve_calender1" onclick="event_calender1()"></i>
                            
          </span>
            
          </div>

            <div id ="recent_free_group_event" name ="recent_free_group_event">
            <div class="recent-event">
            <div class="calendar-heading">
              <h4><?php echo date('jS F Y');?></h4>
            </div>
              <?php   if(!empty($event_free_group_list)) {
                 foreach($event_free_group_list as $events)
                  {?>   
                     
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
                   <?php  if($events['group_type'] == 'B') { ?>
                <div class="deal-amount"><span class="amount-price">$<?php echo $events['deal_amount']; ?></span>Deal amount</div>
                <?php } ?>
              </div>
            <div class="clearfix"></div>  
            </div>
            <?php } } else { ?>
               <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>  No Free group events found</div>
              <?php } ?>
       
            </div>
            </div>

      
    </div>    
  </div>
       
     
      </div>
        
    <div class="clearfix"></div>
   


        <div id="member" class="tab-pane fade">
       
        <div class="group-calender">
          <div class="container">
            <h3>Group Calendar</h3>
                
          <div class="pull-right">
          <span class="event-calender">
            <i class="fa fa-event-calender" name="eve_calender2" id="eve_calender2" onclick="event_calender2()"></i>
                            
          </span>
            
          </div>

            <div id ="recent_business_group_event" name ="recent_business_group_event">
            <div class="recent-event">
            <div class="calendar-heading">
              <h4><?php echo date('jS F Y');?></h4>
            </div>
              <?php   if(!empty($event_business_group_list)) {
                 foreach($event_business_group_list as $events)
                  {?>   
                     
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

      
    </div>    
  </div>
       
            
      </div>
        
    <div class="clearfix"></div>



        </div>
      </div>
      
    </div>
  </div>
    </main>
