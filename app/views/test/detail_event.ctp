
<!DOCTYPE html>
<html lang="en">
  <head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grouper</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
      <!-- Bootstrap -->
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet"> 
   <!-- custom css -->  
   <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet"> 
 
  </head>
<body>

    <!-- Modal content-->
    <div class="event-modal-dialog">
      <div class="modal-header">
       
       
        <h4 class="modal-title">GROUP - <?php echo ucfirst(stripslashes($event_detail['Group']['group_title'])); ?></h4>
      </div>
      <div class="modal-body">    
       
        <h2><?php echo ucfirst(stripslashes($event_detail['Event']['title'])); ?></h2>
        <?php  if($event_detail['Event']['is_multiple_date'] == '1') { ?>
           <div class="event-date pull-left"><i class="fa fa-clock-o"></i><?php echo date("jS F Y g:i a",$event_detail['Event']['event_start_timestamp']); ?> - <?php echo date("jS F Y g:i a",$event_detail['Event']['event_end_timestamp']); ?> </div>
             <?php } else { ?>
            <div class="event-date pull-left"><i class="fa fa-clock-o"></i><?php echo date("jS F Y g:i a",$event_detail['Event']['event_timestamp']); ?> </div>

              <?php } ?> 

    <!--  <div class="event-date pull-left"><i class="fa fa-clock-o"></i> Dec 1, 2017, 09:17 AM</div>  -->
    <div class="clearfix"></div>
    <div class="event-date"><i class="fa fa-map-marker"></i> <?php echo $event_detail['Event']['location']; ?></div>
    <p><?php echo stripslashes($event_detail['Event']['desc']); ?></p>
    <div class="event-map">
     <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $event_detail['Event']['latitude'];?>,<?php echo $event_detail['Event']['longitude'];?>&amp;output=embed"></iframe>

      
    </div>
      </div>     
    </div>

    

</body>
</html>


              