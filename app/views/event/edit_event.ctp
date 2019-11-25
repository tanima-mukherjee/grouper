<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
<script src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>

 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEvzyehZshLrLP25yXeGUfgxRqu8tsZ_U&libraries=places"></script> 

<script type="text/javascript">
    function initialize() {
        var input = document.getElementById('geocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function ()
         {
            var place = autocomplete.getPlace();
            //document.getElementById('city2').value = place.name;
            document.getElementById('lat').value = place.geometry.location.lat();
            document.getElementById('lng').value = place.geometry.location.lng();
            document.getElementById('place_id').value = place.place_id;
            //alert("This function is working!");
            //alert(place.name);
           //alert(place.address_components[0].long_name);

        });
    }
    google.maps.event.addDomListener(window, 'load', initialize); 
</script>

 <!--  <script>
$(function(){
$("#geocomplete").geocomplete();
});
</script> -->
<!-- google geo complete ends -->
<!-- getting lat long using onblur start -->
 <script>
function check_address(address){
  // alert(address);
      if(address == ''){
         $('#lat').val('');   
         $('#lng').val('');
         $('#place_id').val('');

      }
      else
      {
        var latitude = $('#lat').val();
        var longitude = $('#lng').val();
        var place_id = $('#place_id').val();

 
      }
}
</script> 
<!-- getting lat long using onblur end  -->

<script>
$(function() {
  $('#event_date').datetimepicker({
    format:'Y-m-d h:i A',
    formatDate:'y-m-d h:i',
    minDate: 0,
    defaultDate:'<?php echo date('y-m-d h:i'); ?>', // it's my birthday
    //minDate:'<?php echo date('Y-m-d'); ?>',
    timepicker:true,
    formatTime: 'h:i A',

  });
});
</script>
<script>
$(function() {
  $('#event_start_date').datetimepicker({
    format:'Y-m-d h:i A',
    formatDate:'y-m-d h:i',
    minDate: 0,
    defaultDate:'<?php echo date('y-m-d h:i'); ?>', // it's my birthday
    //minDate:'<?php echo date('Y-m-d'); ?>',
    timepicker:true,
    formatTime: 'h:i A',

  });
});
</script>
<script>
$(function() {
  $('#event_end_date').datetimepicker({
    format:'Y-m-d h:i A',
    formatDate:'y-m-d h:i',
    minDate: 0,
    defaultDate:'<?php echo date('y-m-d h:i'); ?>', // it's my birthday
    //minDate:'<?php echo date('Y-m-d'); ?>',
    timepicker:true,
    formatTime: 'h:i A',

  });
});
</script>

<script>
  
jQuery(document).ready(function(){
  jQuery("input:radio[name=is_multiple]").click(function() {
  var value = $(this).val();
  //alert(1);
  if(value == '1'){
  	jQuery("#single_day_event").hide();
    jQuery("#multiple_day_event").show();
    $('#event_date').val('');   
        

        }else{
         
      jQuery("#multiple_day_event").hide();
      jQuery("#single_day_event").show();
       $('#event_start_date').val('');   
         $('#event_end_date').val('');
        }
    });
});
</script>

<script >
  $(document).ready( function() {
     
      var validator = $("#edit_event").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
        title: "required",
        address: "required",
        desc: {
            required: true
         },
         event_date: {
          required: '#is_multiple1:checked',   
         },
		 event_start_date: {
          required: '#is_multiple2:checked',   
         },
         event_end_date: {
          required: '#is_multiple2:checked',  
         }
        } ,
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         title : "Please enter event name",
        address : "Please select an address",
         desc: {
            required : "Please enter event description"
            },
          event_date: {
            required: "Please select an event date"
           }, 
         event_start_date: {
            required: "Please select a start date"
           }, 
         event_end_date:{
            required: "Please select an end date"
           }     
      }
   });
});
</script> 
<script>
 var WEBROOT = '<?php echo $this->webroot;?>';
function delete_event(event_id){
      //alert(event_id);
       var confirm = window.confirm("Are you sure you want to delete this event?");
       if(confirm){
     
        jQuery.ajax({

          type: "GET",
          url: WEBROOT+"event/delete_event",
          data: {event_id:event_id},
          success: function(response){
          if(response == '1' || response == 1){
             parent.$.fancybox.close();
             window.location.reload();      
            }
          }
      });
    }
  }
</script>
 <style>
   .error{
    color: #f00;
   }
 </style>

<!DOCTYPE html>
<html lang="en">
  <head>
	 <meta charset="utf-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grooper</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">   
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet">   
   <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet"> 
	<style>
		body{
			background-color:transparent;
		}
		.event-field .col-sm-4{ width:33.33333333%; float:left;}
   		.event-field .col-sm-8{ width:66.66666667%; float:left;}
		
		.fancybox-inner{ height:601px!important;}
	</style>
	
  </head>
<body>


   
    
      <div class="modal-header">       
        <h4 class="modal-title pull-left">Edit Event</h4>

		<button type="button" class="btn btn-primary delete-btn pull-right" onclick = "delete_event(<?php echo $event_details['Event']['id']; ?>);" >Delete</button>
		<div class="clearfix"></div>	
      </div>
      <div class="modal-body">
      <form action="<?php echo $this->webroot.'event/submit_edit_event/'.$event_details['Event']['id'];?>" method="post" id="edit_event" name="edit_event">
      
  <div class="event-field">
  <div class="row">
	<div class="col-sm-4">
	  <label class="label-display">Event Type: <b class="required">*</b></label>
	</div>
	<div class="col-sm-8">
	  <span class="group-type">     
		<input type="radio" name="is_multiple" id="is_multiple1" value="0" <?php if($event_details['Event']['is_multiple_date']=='0'){ ?> checked ='checked' <?php } ?>>
		<label for="is_single">Single Day</label>
	  </span>
	  <span class="group-type">     
		<input type="radio" name="is_multiple" id="is_multiple2" value="1" <?php if($event_details['Event']['is_multiple_date']=='1'){ ?> checked ='checked' <?php } ?>>
		<label for="is_multiple">Multiple Day</label>
	  </span>
	</div>
  </div>
<div class="clearfix"></div>  
</div>
    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Title: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="title" name = "title" value="<?php echo stripslashes($event_details['Event']['title']);?>"/> 
          <input type="hidden" class="form-control" id="event_group_id" name = "event_group_id" value="<?php echo $event_details['Event']['group_id'];?>"/> 
         <div class="clearfix"></div>  
        </div>
      </div>
    <div class="clearfix"></div>  
    </div>

    <?php if($event_details['Event']['group_type']=='F' || $event_details['Event']['group_type']=='PO'){ ?>
    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Type: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
          <span class="group-type">     
            <input type="radio" name="group_type" value="public" id="group_type" <?php if(($event_details['Event']['type'])=='public'){ ?> checked ='checked' <?php } ?>>
            <label for="public">Public</label>
          </span>
          <span class="group-type">     
            <input type="radio" name="group_type" value="private" id="group_type" <?php if(($event_details['Event']['type'])=='private'){ ?> checked ='checked' <?php } ?> >
            <label for="private">Private</label>
          </span>
		  <?php if($event_details['Group']['parent_id']!='0'){ ?>
		  <span class="group-type">     
            <input type="radio" name="group_type" value="semi_private" id="group_type" <?php if(($event_details['Event']['type'])=='semi_private'){ ?> checked ='checked' <?php } ?> >
            <label for="semi_private">Semi Private</label>
          </span>
		  <?php } ?>
        </div>
      </div>
    <div class="clearfix"></div>  
    </div>
    <?php } ?>
    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Location: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
         
      
    <input id="geocomplete" type="text" size="90" placeholder="Enter a location" name="address" autocomplete="on" class="form-control" onBlur="check_address(this.value);" value="<?php echo $event_details['Event']['location'];?>" />  
    <!-- <input type="text" id="city2" name="city2" value=""/> -->
    <input type="hidden" id="lat" name="lat" value="<?php echo $event_details['Event']['latitude'];?>"/>
    <input type="hidden" id="lng" name="lng" value="<?php echo $event_details['Event']['longitude'];?>" /> 
    <input name="place_id" id="place_id" type="hidden" value="<?php echo $event_details['Event']['place_id'];?>">

            <div class="clearfix"></div>  
        </div>
      </div>
    <div class="clearfix"></div>  
    </div>  


    <div id="single_day_event" <?php if($event_details['Event']['is_multiple_date'] == '1'){ ?> style="display:none" <?php } ?> >
    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Date: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="event_date" id="event_date" value="<?php if($event_details['Event']['event_timestamp'] != '0'){ echo date("Y-m-d h:i A",$event_details['Event']['event_timestamp']); } else{ echo " "; } ?>" /> 
          <div class="clearfix"></div>  
        </div>
      </div>
    <div class="clearfix"></div>  
    </div>
    </div>
    <div id="multiple_day_event" <?php if($event_details['Event']['is_multiple_date'] == '0'){?> style="display:none" <?php } ?>>
    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Start Date: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="event_start_date" id="event_start_date" value="<?php if($event_details['Event']['event_start_timestamp'] != '0'){ echo date("Y-m-d h:i A",$event_details['Event']['event_start_timestamp']); } else{ echo ""; } ?>" /> 
          <div class="clearfix"></div>  
        </div>
      </div>
    <div class="clearfix"></div>  
    </div>
      <div class="event-field">
       <div class="row">
        <div class="col-sm-4">
          <label class="label-display">End Date: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="event_end_date" id="event_end_date" value="<?php if($event_details['Event']['event_end_timestamp'] != '0'){ echo date("Y-m-d h:i A",$event_details['Event']['event_end_timestamp']); } else{ echo " "; } ?>" /> 
          <div class="clearfix"></div>  
        </div>
      </div>
         <div class="clearfix"></div> 
    </div>
    </div>
    
   <?php if($event_details['Group']['group_type']=='B'){ ?>
    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Deal Amount:<b class="required"></b></label>
        </div>
        <div class="col-sm-8">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
            <input type="text" id="amount" name="amount" class="form-control" value="<?php echo $event_details['Event']['deal_amount'];?>" /> 
            <div class="clearfix"></div>  
          </div>

        </div>
      </div>
    <div class="clearfix"></div>  
    </div>
    <?php } ?>

    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Description: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
          <textarea class="form-control comment" id="desc" name="desc" ><?php echo $event_details['Event']['desc'];?></textarea> 
          <div class="clearfix"></div>  
        </div>
      </div>
    <div class="clearfix"></div>  
    </div>
    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">&nbsp;</div>
        <div class="col-sm-8">
          <button type="submit" class="btn signupbtn">Submit</button> 
        </div>
      </div>
    <div class="clearfix"></div>  
    </div>
    </form>
      </div> 
           
   




</body>
</html>


							