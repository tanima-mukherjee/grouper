<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/style.default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/prettify/prettify.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/prettify/prettify.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-ui-1.9.2.min.js"></script>
    
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.validate.min.js"></script>

    <!-- <script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script> -->
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/charCount.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/ui.spinner.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/forms.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.dataTables.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>

<!--date picker ends-->

 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEvzyehZshLrLP25yXeGUfgxRqu8tsZ_U&libraries=places"></script> 

<script type="text/javascript">
    function initialize() {
        var input = document.getElementById('geocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function ()
         {
            var place = autocomplete.getPlace();
            document.getElementById('lat').value = place.geometry.location.lat();
            document.getElementById('lng').value = place.geometry.location.lng();
            document.getElementById('place_id').value = place.place_id;
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize); 
</script>

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
        //alert(0);
        if(value == '1'){
       jQuery("#single_day_event").hide();
      jQuery("#multiple_day_event").show();
       //$('#event_date').val('');   
        

        }else  if(value == '0'){
         
      jQuery("#multiple_day_event").hide();
      jQuery("#single_day_event").show();
       //$('#event_start_date').val('');   
        // $('#event_end_date').val('');
        }
    });
});
</script>
<script>

  function ChangeGroup(group_type){
      //alert(group_type);
      var group_default = '<option value="">Select One</option>';
      jQuery("#group_name").html(group_default);

      if(group_type != ''){
      	if(group_type == 'B')
      	{
      		jQuery("#damnt").show();
      		jQuery("#etype").hide();
      	}
      	else if(group_type == 'F')
      	{
      		jQuery("#damnt").hide();
      		jQuery("#etype").show();
      	}
         jQuery.ajax({
            type: "GET",
            url: '<?php echo $this->webroot; ?>admin_event/show_group',
            data: {group_type:group_type},
            success: function(msg){
             // alert(group_type)
           //alert(msg);
            jQuery("#group_name").html(msg);
            
            }
        });
      }
     
    }
</script>

<script>
	 

     $(document).ready(function() {
   var gtype =  $("#new_group_type").val();
    if (gtype == 'B') {
                jQuery("#damnt").show();
                jQuery("#etype").hide();
            } else {
                jQuery("#damnt").hide();
                jQuery("#etype").show();
            }
});
</script>

<!-- google geo complete ends -->
 <!-- <script >
  $(document).ready( function() {
     
      var validator = $("#edit_event").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
        title: "required",
        address: "required",
        desc: {
            required: true,
            minlength: 100
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
            required : "Please enter event description",
            minlength : "Description should be of min 100 char"
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
</script>   -->
<!-- getting lat long using onblur start -->

    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="<?php echo $this->webroot?>admin/css/validationEngine.jquery.css" type="text/css"/>
	<script src="<?php echo $this->webroot?>admin/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
	</script>
	<script src="<?php echo $this->webroot?>admin/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
	 <script>
	function submit_form(){
		var valid = jQuery("#edit_event").validationEngine('validate');
		if(valid == true){
			jQuery('#submt_butt').attr('disabled',true);
			jQuery('#edit_event').submit();
		}else{
			return false;
		}
	}
  </script> 
<script>
	function submit_form1(){

		window.location.href="<?php echo $this->webroot?>admin_event/event_list";
	}
</script>
	
    <!-- webshim End -->

  </head>

  <body>

    <div class="mainwrapper fullwrapper" style="background-position: 0px 0px;">
      <?php echo $this->element('admin_left'); ?>
      <!-- START OF RIGHT PANEL -->
      <div class="rightpanel">
        <?php echo $this->element('admin_header'); ?>
        <div class="pagetitle">
          <h1><?php echo $pageTitle; ?></h1> <span></span>
        </div><!--pagetitle-->
		 <form class="stdform ws-validate" id="edit_event" name ="edit_event" action="<?php echo $this->webroot.'admin_event/edit_event/'.$event_details['Event']['id'];?>" method="post" enctype="multipart/form-data">
				
        <div class="maincontent">
          <div class="contentinner content-dashboard">            	                
            <div class="row-fluid">
              <!--span8-->
              <div class="widgetcontent">
                <?php if ($this->Session->check('Message.flash')) : ?>
                  <div style="color: red; font-size: 14px; margin-bottom: 10px;">
                    <?php echo $this->Session->flash(); ?>
                  </div>
                <?php endif; ?>

                <p>
                      <label class="profilelabel">Group Type</label>
                       <select class="class-input  validate[required]" name="new_group_type" id="new_group_type" onChange="ChangeGroup(this.value);" >
                        <option value="">Select One</option>
                        <option value="B" <?php if($event_details['Event']['group_type']=="B"){echo "selected='selected'";}?>>BUSINESS</option>
                        <option value="F" <?php if($event_details['Event']['group_type']=="F"){echo "selected='selected'";}?>>FREE</option>
                      </select>  
                       
                </p>

                <p>
                      <label class="profilelabel">Groups</label>
                       <select class="class-input  validate[required]" name="group_name" id="group_name" >
                        <option value="">Select One</option>
                        <?php 
                            if(isset($selectedgrouplist)) {
                        foreach($selectedgrouplist as $data1){?>
                        <option value="<?php echo $data1['Group']['id'];?>"  <?php if($event_details['Event']['group_id'] == $data1['Group']['id']){ echo "selected=selected";}?>><?php echo $data1['Group']['group_title'];?></option>
                         <?php } } ?>
                      </select>  
                       
                </p>
               
                    <p>

                        <label >Event Type: </label>
                    
                        <span class="field">
                        <span style="display:inline-block; position:relative;">     
                          <input style="position:absolute; left:0; top:4px;" type="radio" name="is_multiple" value="0" <?php if(($event_details['Event']['is_multiple_date'])=='0'){ ?> checked ='checked' <?php } ?> id="is_multiple">
                          <label for="is_single" style="width:auto; float:none; padding-left:20px;">Single Day</label>
                      </span> 
                       <span style="display:inline-block; position:relative;"> 
                          <input style="position:absolute; left:0; top:4px;" type="radio" name="is_multiple" value="1"  <?php if(($event_details['Event']['is_multiple_date'])=='1'){ ?> checked ='checked' <?php } ?> id="is_multiple">
                          <label for="is_multiple" style="width:auto; float:none; padding-left:20px;">Multiple Day</label>
                          </span>
                        </span>
                    </p>
					          <p>
                      <label>Title</label>
                      <span class="field">
                        <input type="text" class="input-large  validate[required]" name="title" id="title" value="<?php echo $event_details['Event']['title'];?>"/>
                      </span>
                    </p>
                      
                          <p id ="etype" name ="etype" >
                          <label>Type: </label>
                            
                          <span class="field">
                        <span style="display:inline-block; position:relative;">     
                         <input style="position:absolute; left:0; top:4px;" type="radio" id="group_type" name="group_type" value="public" <?php if(($event_details['Event']['type'])=='public'){ ?> checked ='checked' <?php } ?> >
                          <label for="public" style="width:auto; float:none; padding-left:20px;">Public</label>
                      </span> 
                       <span style="display:inline-block; position:relative;"> 
                          <input style="position:absolute; left:0; top:4px;" type="radio" id="group_type" name="group_type" value="private"  <?php if(($event_details['Event']['type'])=='private'){ ?> checked ='checked' <?php } ?> >
                          <label for="private" style="width:auto; float:none; padding-left:20px;">Private</label>
                          </span>
                        </span>  
                       </p>       
                  
                     <p>
                      <label>Location: </label>
                      <span class="field">
                       
                     
                       <input id="geocomplete" type="text" size="250" placeholder="Type in an address" name="address" autocomplete="on" class="form-control  validate[required]" onblur="check_address(this.value);" value="<?php echo $event_details['Event']['location'];?>"/>  
                      </span>
                    </p>
                    <input type="hidden" id="lat" name="lat" value="<?php echo $event_details['Event']['latitude'];?>"/>
				<input type="hidden" id="lng" name="lng" value="<?php echo $event_details['Event']['longitude'];?>" /> 
				<input name="place_id" id="place_id" type="hidden" value="<?php echo $event_details['Event']['place_id'];?>">
                    

                    
                    <div id="single_day_event" <?php if($event_details['Event']['is_multiple_date'] == '1'){?> style="display:none" <?php } ?> >
                     <p>
                      <label>Date</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="event_date" id="event_date" <?php if($event_details['Event']['event_timestamp'] != '0') { ?> value="<?php echo date("Y-m-d h:i A",$event_details['Event']['event_timestamp']);?>" <?php }  else { ?> value =" " <?php } ?>  />
                      </span>
                    </p>
                    </div>
                    
                       <div id="multiple_day_event"  <?php if($event_details['Event']['is_multiple_date'] == '0'){?> style="display:none" <?php } ?> >
                      <p>
                      <label>Start Time</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="event_start_date" id="event_start_date"  <?php if($event_details['Event']['event_start_timestamp'] != '0') { ?> value="<?php echo date("Y-m-d h:i A",$event_details['Event']['event_start_timestamp']);?>" <?php }  else { ?> value =" " <?php } ?>/>
                      </span>
                    </p>
                    <p>
                      <label>End Time</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="event_end_date" id="event_end_date" <?php if($event_details['Event']['event_end_timestamp'] != '0') { ?> value="<?php echo date("Y-m-d h:i A",$event_details['Event']['event_end_timestamp']);?>" <?php }  else { ?> value =" " <?php } ?>  />
                      </span>
                    </p>
                    </div>
                    
                      <p id ="damnt" name ="damnt">
                      <label>Deal Amount</label>
                      <span class="field">
                        <input type="text" class="input-large  validate[required]" name="amount" id="amount" value="<?php echo $event_details['Event']['deal_amount'];?>"/>
                      </span>
                    </p >
                    
                     <!--start-->
                    <p>
                      <label class="profilelabel"> Description </label>
                      <span class="field">
                        <textarea class="input-large validate[required]" name="desc" id="desc" ><?php echo $event_details['Event']['desc'];?></textarea>
                       </span>
                    </p>
                
                <!-- end -->


                    
                    
                    <p class="stdformbutton">
                      <button class="btn btn-warning" type="button" id="submt_butt" onClick="Javascript: submit_form();">Update</button>
                      <button class="btn btn-warning" type="button" id="submt_butt1" onClick="Javascript: submit_form1();">Back</button>
                    </p>
                  </div>
                  <div class="span6">
				
				  </div>                                                    	                           
                </form>
              </div>
              <!--span4-->
            </div><!--row-fluid-->
          </div><!--contentinner-->
        </div><!--maincontent-->
      </div><!--mainright-->
      <!-- END OF RIGHT PANEL -->

      <div class="clearfix"></div>


    </div><!--mainwrapper-->

   
   
  </body>
</html>