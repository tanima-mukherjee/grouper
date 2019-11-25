<div id="ajax_message">

</div> 
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<!--date picker start-->
<script src="<?php echo $this->webroot?>js/moment.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/pignose.calendar.css">
<script src="<?php echo $this->webroot?>js/pignose.calendar.js"></script>


<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
<script src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>

<!--date picker ends-->
<script>
    $(document).ready(function() {
      $(".eventbox").fancybox({
        'type': 'iframe',
        'width': 600,
		'autoSize': true
      });
    });
</script>

<!-- google geo complete starts -->
<!-- edit group starts -->
<script>

  function ChangeGroup(group_type){
      //alert(group_type);
      
      
        if(group_type == 'B')
        {
          jQuery("#category").show();
          
        }
        else if(group_type == 'F' || group_type == '')
        {
          jQuery("#category").hide();
         // jQuery('#category_id').val('0');
         
        }
       
    }
</script>
<!-- edit group ends -->
  <!--   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->
<!-- 
  <script src="<?php  echo $this->webroot;?>js/jquery.geocomplete.js"></script> -->
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEvzyehZshLrLP25yXeGUfgxRqu8tsZ_U&libraries=places"></script> 

<!-- <script src="http://maps.googleapis.com/maps/api/js?libraries=places" type="text/javascript"></script> -->

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
 var WEBROOT = '<?php echo $this->webroot;?>';
function event_calender(){

  
  $('#eve_calender').datetimepicker({
    format:'Y-m-d ',
    formatDate:'Y-m-d ',
    minDate: 0,
    defaultDate:'<?php echo date('Y-m-d '); ?>', // it's my birthday
    
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
  
  })

};


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

<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function page_reload(){
  location.reload();
  }
</script>
<script >
  $(document).ready( function() {
 
      var validator = $("#video_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
         video_file: "required"
                 
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         video_file:"Please upload video"
         
      }
   });
});
</script> 
<!-- validation for create event starts -->
 <script >
  $(document).ready( function() {
     
      var validator = $("#add_event").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
        title: "required",
        address: "required",
		desc: "required", 
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
		 desc : "Please enter event description",
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

 <script >
  $(document).ready( function() {
     
      var validator = $("#edit_group_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {  
        new_group_type: {
            required: true
        }, 
        group_name: {
            required: true
        },
        g_desc: {
            required: true
        },
          g_purpose: {
              required: true
          },
		  g_url: {
            url : true
         } 
        } ,
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        new_group_type: {
            required: "Please select a group"
           }, 
           group_name: {
            required: "Please enter a group name"
           }, 
           g_desc: {
            required: "Please enter a group description"
           },
           g_purpose: {
            required: "Please enter a group purpose"
           },
		   g_url: {
            url : "Please enter a valid URL"
         }
      }
   });


    var validator = $("#create_post").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {  
          topic: {
              required: true
          }, 
          message: {
              required: true
          }
        } ,
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        topic: {
            required: "Please enter topic"
           }, 
        message: {
          required: "Please enter message"
        }
      }
   });
});
</script>  
<!-- validation for edit group ends -->
<script>
function delete_doc(docID) {
  var confirm = window.confirm('Are you sure you want to delete this Doc?');
  if(confirm) {
        jQuery.ajax({
          type: 'POST',
          url: '<?php echo $this->webroot; ?>group/delete_doc',
          data: {docID: docID},
          success: function(resp) {
            if(resp == 'ok')
              window.location.reload();
          }
        });
      }
    }
function delete_image(imageID) {
  var confirm = window.confirm('Are you sure you want to delete the Image?');
  if(confirm) {
        jQuery.ajax({
          type: 'POST',
          url: '<?php echo $this->webroot; ?>group/delete_image',
          data: {imageID: imageID},
          success: function(resp) {
            if(resp == 'ok')
              window.location.reload();
          }
        });
      }
    }

function delete_video(videoID) {
  //alert(videoID);
  var confirm = window.confirm('Are you sure you want to delete the Video?');
  if(confirm) {
        jQuery.ajax({
          type: 'POST',
          url: '<?php echo $this->webroot; ?>group/delete_video',
          data: {videoID: videoID},
          success: function(resp) {
            if(resp == 'ok')
              window.location.reload();
          }
        });
      }
    }

function upload_video() {
	
  var group_id = $('#grp_id').val();
  var youtube = $('#youtube_url').val();
  if(youtube != '')
  					{										
			
		  jQuery.ajax({
		  	
          type: 'POST',
          url: '<?php echo $this->webroot?>group/add_group_video',
          data: {group_id: group_id,youtube: youtube},
          success: function(resp) {
            if(resp == '1'||resp == 1)
            {
            	jQuery("#error_msg_video").show();
            }
            else
            {
                window.location.reload();
            }
            	}
        	  });
							
			}
      else
      {
        jQuery("#error_msg_video").show();
      }  
   		 }
</script>




<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<style type="text/css">
  .event a {
    background-color: #5FBA7D !important;
    color: #ffffff !important;
}
</style>
<script type="text/javascript">
  $( function() {
    // An array of dates
    var eventDates = {};
    eventDates[ new Date( '08/07/2016' )] = new Date( '08/07/2016' );
    eventDates[ new Date( '08/12/2016' )] = new Date( '08/12/2016' );
    eventDates[ new Date( '08/18/2016' )] = new Date( '08/18/2016' );
    eventDates[ new Date( '08/23/2016' )] = new Date( '08/23/2016' );
    
    // datepicker
    $('#datepicker').datepicker({
        beforeShowDay: function( date ) {
            var highlight = eventDates[date];
            if( highlight ) {
                 return [true, "event", 'Tooltip text'];
            } else {
                 return [true, '', ''];
            }
        }
    });
});

</script>
Date: <div id="datepicker"></div> -->


 <style>
   .error{
    color: #f00;
   }
   .fancybox-outer .fancybox-inner{ height:500px!important;}
 </style>
 <!-- Compose Modal -->
 <div id="composeModal" class="modal fade" role="dialog">
  <div class="modal-dialog compose-modal-dialog">
    <!-- Modal content-->
    <div class="modal-content"> 
		<div class="modal-header"> 
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Compose Message</h4>		
		</div>

    <form action="<?php echo $this->webroot.'group/post_message';?>" method="POST" id="create_post">
    <input type="hidden" name="group_id" value="<?php echo $group_details['Group']['id'];?>">
    <input type="hidden" name="group_type" value="<?php echo $group_details['Group']['group_type'];?>">
    <input type="hidden" name="user_type" value="<?php echo $group_user_type;?>">
      <div class="modal-body">
		<div class="form-group">
			<label class="label-field">Message Topic</label>
			<input type="text" name="topic" id="topic" class="form-control">
      <div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="label-field">Message</label>
			<textarea class="form-control" name="message" id="message" rows="4"></textarea>
      <div class="clearfix"></div>
		</div>
		<button type="submit" class="btn signupbtn">Submit</button>
      </div>   


    </div>
  </div>
</div>
 <!-- Compose Modal -->


 <!-- view user Modal -->
 <!-- Join Now Modal start -->
<div id="leave_group" class="modal fade" role="dialog">
  <div class="modal-dialog group-modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">      
      <div class="modal-body">

    <form action="<?php echo $this->webroot?>group/leave_group/<?php echo $group_details['Group']['id'];?>" method="post" id="leave_group_form" name="leave_group_form" >  
    <div class="leave-group-box">
      <span class="leave-message">     
       Do you really want to leave this group ?
      </span>     
      <div class="clearfix"></div>
      <div class="leave-group-button">         
         <a href="<?php echo $this->webroot;?>group/group_detail/<?php echo $group_details['Group']['id'];?>">Cancel</a>
        <button type="submit">Leave</button>
      </div>
    </div>

    </form>

      </div>     
    </div>
  </div>
</div>
 <!-- Join Now modal end -->
 
  <!-- Stop Notification Modal start -->
<div id="stop_notification" class="modal fade" role="dialog">
  <div class="modal-dialog group-modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">      
      <div class="modal-body">

    <form action="<?php echo $this->webroot?>group/is_group_notification_stop/<?php echo $group_details['Group']['id'];?>" method="post" id="stop_notification_form" name="stop_notification_form" >  
    <div class="leave-group-box">
      <span class="leave-message">     
       Do you want to stop the notification for this group ?
      </span>     
      <div class="clearfix"></div>
      <div class="leave-group-button">         
         <a href="<?php echo $this->webroot;?>group/group_detail/<?php echo $group_details['Group']['id'];?>">Cancel</a>
        <button type="submit">Confirm</button>
      </div>
    </div>

    </form>

      </div>     
    </div>
  </div>
</div>
 <!-- Stop Notification modal end -->

   <!-- Start Notification Modal start -->
<div id="start_notification" class="modal fade" role="dialog">
  <div class="modal-dialog group-modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">      
      <div class="modal-body">

    <form action="<?php echo $this->webroot?>group/is_group_notification_start/<?php echo $group_details['Group']['id'];?>" method="post" id="start_notification_form" name="start_notification_form" >  
    <div class="leave-group-box">
      <span class="leave-message">     
       Do you want to start the notification for this group ?
      </span>     
      <div class="clearfix"></div>
      <div class="leave-group-button">         
         <a href="<?php echo $this->webroot;?>group/group_detail/<?php echo $group_details['Group']['id'];?>">Cancel</a>
        <button type="submit">Confirm</button>
      </div>
    </div>

    </form>

      </div>     
    </div>
  </div>
</div>
 <!-- Start Notification modal end -->
 
 
<!-- Image Modal -->
<div id="uploadimage" class="modal fade" role="dialog">
  <div class="modal-dialog upload-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Images</h4>
      </div>
      <div class="modal-body">
       <div class="image_upload_div">
			<form action="<?php echo $this->webroot.'group/upload_gallery_image/'.$group_id;?>" class="dropzone">
				
			</form>
		<div class="upload-bottom"> <button type="submit" class="" onclick="page_reload()">Upload</button> </div>			
		</div> 	
      </div>
    </div>

  </div>
</div>


<!-- Image Modal -->
<div id="uploaddoc" class="modal fade" role="dialog">
  <div class="modal-dialog upload-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Documents</h4>
      </div>
      <div class="modal-body">
       <div class="image_upload_div">
      <form action="<?php echo $this->webroot.'group/upload_gallery_doc/'.$group_id;?>" class="dropzone1">
      </form>
	  <div class="upload-bottom"> <button type="submit" class="" onclick="page_reload()" >Upload </button> </div>
    </div>  
      </div>
    </div>

  </div>
</div>

<!-- Video Modal -->
<div id="upvideo" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Youtube Link</h4>
      </div>
      <div class="modal-body">
      <!-- <form action="<?php echo $this->webroot?>group/add_group_video" method="post" id="video_form" 
      name="video_form" enctype="multipart/form-data">  -->
      <input type="hidden" name="grp_id" id="grp_id" value="<?php echo $group_id; ?>" />
       <div class="upload-video">
			<label class="label-video">Upload Youtube Link</label>
			<span class="video-file">
				<input type="text" name="youtube_url" id="youtube_url" placeholder="https://www.youtube.com/watch?v=kJQP7kiw5Fk" class="form-control" />
	         	<div class="" id="error_msg_video" name="error_msg_video" style="display: none">Please upload proper youtube link </div>        
		    </span>	
      
       		<button type="submit" class="btn signupbtn" onclick="upload_video()">Submit</button>		
	   			<div class="clearfix"></div>		
	   </div>
    <!--  </form> -->
	   
	  
	   
      </div>
    </div>

  </div>
</div>
<!-- category modal start -->
<div class="modal fade" id="editgroup" role="dialog">
    <div class="modal-dialog signup-model">    
 
    <div class="modal-content">
      <!-- Modal content-->

    <div class="modal-header signup-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Edit Group</h4>
    </div>
       
   <div class="modal-body group-modal-body">
	   <form action="<?php echo $this->webroot.'group/edit_group/'.$group_details['Group']['id'];?>" method="post" id="edit_group_form" name="edit_group_form" enctype="multipart/form-data">  
	   	<input type="hidden" name="old_icon" id="old_icon" value="<?php echo $group_details['Group']['icon'];?>" />
	
		<div class="pop-createg">
		
			<div class="upload-img">
			
			<div class="pop-createg-round">
			
			
				  
			<?php if($group_details['Group']['icon']!=''){?>
				<img id="thumbnil8" src="<?php echo $this->webroot;?>group_images/<?php echo $group_details['Group']['icon'];?>" alt="">
				
			<?php }else{ ?>
				<img id="thumbnil8" src="<?php echo $this->webroot?>images/no-group-img.jpg">
			<?php } ?>
							 
								
			</div>
			
			<span class="btn-bs-file browse-btn">Browse</span>
			
			<input onChange="showMyImage(this)" name="upload_image" id="upload_image" value="<?php echo $group_details['Group']['icon'];?>" data-badge="false" type="file"/>
			<div class="clearfix"></div>
			 <p class="upload-img-info">please upload an image of min 160 px X 120 px</p>
			</div>
		
		</div>
	  
		<div class="signup-left">
		<div class="create-group-field">
    <div class="form-group">
      <label class="label-field">Group Type</label>
      <select class="form-control" name="new_group_type" id="new_group_type" onChange="ChangeGroup(this.value);" >
                        <option value="">Select One</option>
                        <option value="B" <?php if($group_details['Group']['group_type']=="B"){echo "selected='selected'";}?>>BUSINESS</option>
                        <option value="F" <?php if($group_details['Group']['group_type']=="F"){echo "selected='selected'";}?>>PRIVATE</option>
                      </select>   
       <div class="clearfix"></div>          
    </div>
    <div class="form-group" id ="category" name ="category" <?php if($group_details['Group']['group_type']=="F") { ?> style=display:none <?php } ?>>
      <label class="label-field">Category</label>
      <select class="form-control" name="category_id" id="category_id" >
                         <option value="0">Select One</option>
                        <?php 
                            if(isset($all_categories)) {
                               foreach($all_categories as $data1) { ?>
                  <option value="<?php echo $data1['Category']['id'];?>"  <?php if($group_details['Group']['category_id'] == $data1['Category']['id']){ echo "selected=selected";}?>><?php echo $data1['Category']['title'];?></option>
                   <?php }}  ?>
                      </select>   
       <div class="clearfix"></div>          
    </div>
		<div class="form-group">
			<label class="label-field">Title</label>
			<input type="text" class="form-control" id="group_name" name="group_name" value="<?php echo ucfirst(stripslashes($group_details['Group']['group_title']))?>">   
			 <div class="clearfix"></div>          
		</div>
		<div class="form-group">
			<label class="label-field">Description</label>
			<textarea name="g_desc" id="g_desc" class="form-control" cols="" rows=""><?php echo ucfirst(stripslashes($group_details['Group']['group_desc']))?></textarea>
			 <div class="clearfix"></div>                   
		</div> 
		<div class="form-group">
		  <label class="label-field">Purpose</label>
		   <textarea name="g_purpose" id="g_purpose" class="form-control" cols="" rows=""><?php echo ucfirst(stripslashes($group_details['Group']['group_purpose']))?></textarea>
		   <div class="clearfix"></div>                   
		</div>
		<div class="form-group">
			<label class="label-field">URL</label>
			<input type="text" class="form-control" id="g_url" name="g_url" value="<?php echo stripslashes($group_details['Group']['group_url'])?>">   
			 <div class="clearfix"></div>          
		</div> 
		<p style="color:#555555">If you would like to provide a link to your website please enter the complete URL to your site above (i.e.   https://www.grouperusa.com).</p>				  
				
		<button type="submit" class="btn signupbtn">Update Group</button>
		</div>
		</div>
		</form>
	   <div class="clearfix"></div>
   </div>

   <div class="clearfix"></div>  
   </div>
    </div>    
</div>
      

<!-- group modal end -->

<!-- Create Event Modal Start -->
<div id="eventModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Event</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo $this->webroot;?>event/add_event" method="post" id="add_event" name="add_event">
      
      <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Event Type: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
          <span class="group-type">     
            <input type="radio" name="is_multiple" value="0" id="is_multiple1" checked="checked">
            <label for="is_single">Single Day</label>
          </span>
          <span class="group-type">     
            <input type="radio" name="is_multiple" value="1" id="is_multiple2">
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
					<input type="text" class="form-control" id="title" name = "title"/> 
            		<input type="hidden" class="form-control" id="event_group_id" name = "event_group_id" value="<?php echo $group_id?>"/> 
         <div class="clearfix"></div>  
				</div>
			</div>
		<div class="clearfix"></div>	
		</div>

      <?php if($group_details['Group']['group_type']=='F' || $group_details['Group']['group_type']=='PO'){ ?>
		<div class="event-field">
			<div class="row">
				<div class="col-sm-4">
					<label class="label-display">Type: <b class="required">*</b></label>
				</div>
				<div class="col-sm-8">
					<span class="group-type">			
						<input type="radio" name="group_type" value="public" id="group_type" checked="checked">
						<label for="public">Public</label>
					</span>
					<span class="group-type">			
						<input type="radio" name="group_type" value="private" id="group_type">
						<label for="private">Private</label>
					</span>
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
				 
      
    			<input id="geocomplete" type="text" size="90" placeholder="Enter a location" name="address" autocomplete="on" class="form-control" onblur="check_address(this.value);"/>  
				<input type="hidden" id="lat" name="lat" value=""/>
				<input type="hidden" id="lng" name="lng" value="" /> 
				<input name="place_id" id="place_id" type="hidden" value="">
				
            	<div class="clearfix"></div>  
				</div>
			</div>
		<div class="clearfix"></div>	
		</div>		
    <div id="single_day_event" >
		<div class="event-field">
			<div class="row">
				<div class="col-sm-4">
					<label class="label-display">Date: <b class="required">*</b></label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="event_date" id="event_date" autocomplete="off"   /> 
          			<div class="clearfix"></div>  
				</div>
			</div>
		<div class="clearfix"></div>	
		</div>
    </div>
    <div id="multiple_day_event" style="display:none" >
    <div class="event-field">
      <div class="row">
        <div class="col-sm-4">
          <label class="label-display">Start Date: <b class="required">*</b></label>
        </div>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="event_start_date" id="event_start_date" autocomplete="off"   /> 
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
          <input type="text" class="form-control" name="event_end_date" id="event_end_date" autocomplete="off" /> 
          <div class="clearfix"></div>  
        </div>
      </div>
         <div class="clearfix"></div> 
    </div>
    </div>
    
  <?php if($group_details['Group']['group_type']=='B')
                   { ?>
		<div class="event-field">
			<div class="row">
				<div class="col-sm-4">
					<label class="label-display">Deal Amount:<b class="required"></b></label>
				</div>
				<div class="col-sm-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
						<input type="text" id="amount" name="amount" class="form-control" /> 
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
					<textarea class="form-control comment" id="desc" name="desc" ></textarea> 
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
           
    </div>

  </div>
</div>
<!-- Create Event Modal End -->

<!-- Edit Event Modal Start -->

<!-- Edit Event Modal End -->
<main class="main-body">

  
  <div class="category-details-content">
    <div class="container"> 
      <div class="heading-title">
        <h2>Group details</h2>
      </div>

      <div class="page-top-line">
      <?php if($group_member_type == 'owner') { ?>
        <div class="page-left-nav">
          <ul>           
             <li><a href="javascript:void(0)" data-toggle="modal" data-target="#editgroup"> Edit <i class="fa create-icon"></i></a></li>
            <li><a class="userbox view-user" href="<?php echo $this->webroot?>group/push_message/<?php echo $group_details['Group']['id'];?>">Push your message <i class="fa create-icon"></i></a></li>
          </ul>
        </div>
        <?php } ?>
      <?php if($group_member_type == 'owner'){ ?>
        <div class="page-right-nav">
          <ul>
           <!--  <li><a href="#" data-toggle="modal" data-target="#usermodal">View users <i class="fa create-icon"></i></a></li> -->
            <li><a  class="userbox view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $group_details['Group']['id'];?>" > View users <i class="fa create-icon"></i></a></li>

            <li><a class="userbox view-user" href="<?php echo $this->webroot?>group/invite_list/<?php echo $group_details['Group']['id'];?>">Send Invitation <i class="fa create-icon"></i></a></li>
          </ul>
        </div>
      <?php } else if($group_member_type == 'member'){ ?>
	  <div class="page-left-nav">
			  <ul>
				<?php if($is_notification_stop == '0')
            { ?>
               <li><a href="javascript:void(0)" data-toggle="modal" data-target="#stop_notification"> Stop Notification  <i class="fa noti-red"></i></a></li> 
           <?php } else if($is_notification_stop == '1'){ ?>
          

           <li><a href="javascript:void(0)" data-toggle="modal" data-target="#start_notification"> Start Notification  <i class="fa noti-red"></i></a></li> 
           <?php } ?>
			  </ul>
		  </div>
        <div class="page-right-nav">
          <ul>
           <!--  <li><a href="#" data-toggle="modal" data-target="#usermodal">View users <i class="fa create-icon"></i></a></li> -->
             <?php if($group_details['Group']['group_type'] == 'F'){ ?>
            <li><a  class="userbox view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $group_details['Group']['id'];?>" > View users <i class="fa create-icon"></i></a></li>
			 
            <?php } ?>            

           <li><a href="#" data-toggle="modal" data-target="#leave_group"> Leave Group  <i class="fa create-icon"></i></a></li>
            <li><a class="userbox view-user" href="<?php echo $this->webroot?>group/recommend_list/<?php echo $group_details['Group']['id'];?>">Send Recommendation <i class="fa create-icon"></i></a></li>
          </ul>
        </div>
      <?php } else {
	  		if($this->Session->read('userData')){
			if($group_details['Group']['group_type']=='B'){
	   ?>
		
		  
          <div class="page-right-nav">
          <ul>
          <?php  $GroupIsJoin = $this->requestAction('group/group_is_join/'.$group_details['Group']['id'].'/');
                 if($GroupIsJoin > 'O') { ?>
                <li><a href="javascript:void(0)" class="group-btn invite">Request Sent <i class="fa create-icon"></i></a></li>
                <?php }else{ ?>
                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#joinnow" data-id="<?php echo $group_details['Group']['id'];?>" >Join Now <i class="fa create-icon"></i></a></li>
                <?php } ?>

          
           
          </ul>
        </div>
     <?php }}} ?>
      <div class="clearfix"></div>  
      </div>

      <div class="group-details">
      <div class="group-details-info">
        <div class="group-details-img">
			<?php 
				// $file_path_group = $this->webroot."group_images/".$group_details['Group']['icon'];
				
				if($group_details['Group']['icon']!=''){?>
					<img id="thumbnil" src="<?php echo $this->webroot;?>group_images/<?php echo $group_details['Group']['icon']; ?>" alt="<?php echo ucfirst(stripslashes($group_details['Group']['group_title'])); ?>">
				
				<?php }else{ ?>
					<img id="thumbnil" src="<?php echo $this->webroot?>images/no-group-img.jpg">
				<?php } ?>
				
				<a href="<?php echo stripslashes($group_details['Group']['group_url']); ?>" target="_blank"><?php echo stripslashes($group_details['Group']['group_url']); ?></a>
        </div>
		
        <div class="group-details-content">
          <h4><?php echo ucfirst(stripslashes($group_details['Group']['group_title']))?></h4>
          <p><?php echo ucfirst(stripslashes($group_details['Group']['group_desc']))?>
          </p>
        </div>
      <div class="clearfix"></div>  
      </div>
	  
	  <div class="row">
	<div class="col-md-3">
		<div class="calendar"></div>

    <style type="text/css">
      .event a {
    background-color: #5FBA7D !important;
    color: #ffffff !important;
}
    </style>
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
					
					var group_id = '<?php echo $group_details['Group']['id']; ?>';
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
              url: WEBROOT+"event/event_list",
              data: {group_id:group_id,total_dt:text},
              success: function(response){
                  jQuery("#recent_event").html(response);
              }
		      });
				}

				// Default Calendar
				$('.calendar').pignoseCalendar({
					select: onSelectHandler,					
					/*scheduleOptions: {
						colors: {
							offer: '#FF0000',
							ad: '#5c6270'
						}
					},
					schedules: [{
						name: 'offer',
						date: '2017-12-26'
					}, {
						name: 'ad',
						date: '2017-12-28'
					}]*/
				});
    });
			//]]>
			
			
			$(document).ready(function(){
			<?php 
				if(count($arr_event_dates) > 0){
				for($k=0; $k<count($arr_event_dates); $k++){ ?>
				jQuery("#cal"<?php echo $arr_event_dates[$k] ?>).css("background-color", "#2fabb7");
			<?php } } ?>
				
			});


/*$(function () {
      $('.calendar-schedules').pignoseCalendar({
  scheduleOptions: {
    colors: {
        offer: '#2fabb7',
      ad: '#5c6270'
    }
  },
  schedules: [{
    name: 'offer',
      date: '2017-02-08'
  }, {
    name: 'ad',
      date: '2017-02-09'
  }, {
    name: 'offer',
      date: '2017-02-05',
  }],
  select: function(date, context) {
    alert(context.storage.schedules[0].date);
    console.log('events for this date', context.storage.schedules);
  }
});
      });*/
		</script>




<!-- <h3><span>Schedule Calendar</span></h3>
<div class="calendar-schedules"></div> -->


		</div>
		<div class="col-md-9">
			<div class="upcoming-events-list">
				<h2>Upcoming Events</h2>
				<?php if($group_member_type == 'owner') { ?>
					<a href="javascript:void(0)" class="event-btn createevent" data-toggle="modal" data-target="#eventModal">Create Event</a>
				<?php } ?>
					
					
				<div id ="recent_event" name ="recent_event">
				<div class="events-scroll">
					<ul>
						<?php   if(!empty($event_list)) {
		                 foreach($event_list as $events)
		                  { ?> 
						<li>
							<div class="calendar-box">
              <div class="event-location">               
                 <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $events['latitude'];?>,<?php echo $events['longitude'];?>&amp;output=embed"></iframe>
              </div>
               <?php  if($events['group_type'] == 'B' && $events['deal_amount']>0){ ?>
               <div class="event-amount">         
					<div class="deal-amount"><span class="amount-price"> <span class="currency">$</span><?php echo $events['deal_amount']; ?></span>Deal amount</div>
               </div>
			   
			   <div class="event-des">
			  	<div>
                <h4><?php echo $events['event_name']; ?></h4>
        		
				
				<?php if($group_member_type == 'owner'){ ?>
				 <div class="event-edit">
					
          			<a  class="eventbox event-btn" href="<?php echo $this->webroot?>event/edit_event/<?php echo $events['id']; ?>">
						<span>Edit</span>
						<i class="fa fa-pencil"></i>
					</a>	
				 </div>	
				 <?php } ?>
				 </div>
       
        		<p><?php echo stripslashes($events['desc']); ?></p>
          
              </div>
              <?php } 
			  else { ?>
			  
			  <div class="event-des no-deal-amount" style="border-right:none;">
			  	<div>
                <h4><?php echo $events['event_name']; ?></h4>
        		
				
				<?php if($group_member_type == 'owner'){ ?>
				 <div class="event-edit">
					
          			<a  class="eventbox event-btn" href="<?php echo $this->webroot?>event/edit_event/<?php echo $events['id']; ?>">
						<span>Edit</span>
						<i class="fa fa-pencil"></i>
					</a>	
				 </div>	
				 <?php } ?>
				 </div>
       
        		<p><?php echo stripslashes($events['desc']); ?></p>
          
              </div>
			  
			  <?php
			  }
			  ?>
              
			  <ul class="event-listing">  
          <li><?php  if($events['is_multiple_date'] == '1') { ?>
            <div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_start_date_time']; ?> - <?php echo $events['event_end_date_time']; ?> </div>
             <?php } else { ?>
             <div class="event-date"><i class="fa fa-clock-o"></i> <?php echo $events['event_date_time']; ?> </div>
              <?php } ?>
            </li>
          <li><i class="fa fa-map-marker"></i> <span class="event-place"><?php echo $events['location']; ?></span></li>         
        </ul>
            <div class="clearfix"></div>  
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
	
	
	<div class="message-board">
	<div class="pull-left">
		<h3>Message Board</h3>
	</div>	

<?php if($group_details['Group']['group_type']=='B' && $group_user_type=='O'){?>
	<div class="compose-right">
		<button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#composeModal">Compose</button>
	</div>		
<?php }else if($group_details['Group']['group_type']=='F' && ($authorized_member == '1' || $group_user_type=='O')){ ?>
<div class="compose-right">
  <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#composeModal">Compose</button>
</div>
<?php } ?>







<script type="text/javascript">
jQuery(document).ready(function($) {
  $(document).on("click", ".Assign_link", function () {
    var message_id = jQuery(this).data('id');
    var group_id = jQuery(this).data('groupid');
    var topic = jQuery(this).data('topic');
    var message = jQuery(this).data('message');
    //alert(message_id);
    $("#edit_message #edit_message_id").val(message_id);
    $("#edit_message #edit_group_id").val(group_id);
    $("#edit_message #edit_topic").val(topic);
    $("#edit_message #message_content").val(message);
  });

  $(document).on("click", ".Reply_Assign_link", function () {
    var reply_id = jQuery(this).data('id');
    var group_id = jQuery(this).data('group_id');
    var reply = jQuery(this).data('reply'); 
    //alert(message_id);
    $("#edit_reply_form #edit_reply_id").val(reply_id);
    $("#edit_reply_form #reply_content").val(reply);
    $("#edit_reply_form #reply_group_id").val(group_id);
  });

});

</script>

<!-- Edit Message Modal -->
<div id="edittopicModal" class="modal fade" role="dialog">
<div class="modal-dialog compose-modal-dialog">
<!-- Modal content-->
<div class="modal-content"> 
<div class="modal-header"> 
  <button type="button" class="close" data-dismiss="modal">×</button>
  <h4 class="modal-title">Edit Message</h4>    
</div>

<form method="POST" id="edit_message">
<input type="hidden" name="message_id" id="edit_message_id" value="">
<input type="hidden" name="group_id" id="edit_group_id" value="">
  <div class="modal-body">
<div class="form-group">
  <label class="label-field">Message Topic</label>
  <input type="text" name="topic" id="edit_topic" class="form-control">
  <div class="clearfix"></div>
</div>
<div class="form-group">
  <label class="label-field">Message</label>
  <textarea class="form-control" name="message" id="message_content" rows="4"></textarea>
  <div class="clearfix"></div>
</div>
<button type="button" onclick="editmessage()" class="btn signupbtn">Submit</button>
  </div>   
</form>

</div>
</div>
</div>
<!-- Edit Message Modal -->


<script>
function editmessage()
{

jQuery('#ajax_message').removeClass('ajax-message');
jQuery('#ajax_message').html("");
jQuery('#edittopicModal').modal('hide');
var message_id = jQuery("#edit_message_id").val();
var group_id = jQuery("#edit_group_id").val();
var message = jQuery("#message_content").val();
var topic = jQuery("#edit_topic").val();
//alert(message_id+','+message+','+topic);
jQuery.ajax({
type: 'GET',
url: '<?php echo $this->webroot; ?>group/edit_message',
data: {message_id: message_id, topic: topic, group_id: group_id, message: message},
success: function(resp) 
{
	//alert(resp);
	if(resp == "0")
	{
	  jQuery('#ajax_message').addClass('ajax-message');
	  jQuery('#ajax_message').html("Sorry!! You are not authorized to edit.");
	  setTimeout(hide_alert, 4000);
	}
	else
	{
	  jQuery('#ajax_message').removeClass('ajax-message');
	  jQuery('#ajax_message').html("");
	  jQuery('#topic_div_'+message_id).html(resp);
	}
}
});
}

function delete_topic(topic_id, group_id)
{
jQuery('#ajax_message').removeClass('ajax-message');
jQuery('#ajax_message').html("");
var confirm = window.confirm('Are you sure you want to delete that topic?');
if(confirm) {
  jQuery.ajax({
	type: 'POST',
	url: '<?php echo $this->webroot; ?>group/delete_message',
	data: {topic_id: topic_id, group_id: group_id},
	success: function(resp) {
	  //alert(resp);
	  if(jQuery.trim(resp) == '0')
	  {
		jQuery('#ajax_message').removeClass('ajax-message');
		jQuery('#ajax_message').html("");
		jQuery('#topic_div_'+topic_id).remove(); 
	  }else{
		jQuery('#ajax_message').addClass('ajax-message');
		jQuery('#ajax_message').html("Sorry!! You are not authorized to delete.");
		setTimeout(hide_alert, 4000);
	  }
	}
  });
}
}


function hide_alert(){
jQuery('#ajax_message').removeClass('ajax-message');
		jQuery('#ajax_message').html("");
    window.location.reload();
}
</script>









	<div class="clearfix"></div>
	<div class="message-list">
		<div class="message-header">
			<div class="message-time">
				<span>Date/Time</span>
			</div>
			<div class="reply">
				<span>&nbsp;</span>
			</div>
			<div class="postedby">
				<span>Posted By</span>
			</div>				
			<div class="message-topic">
				<span>Message Topic</span>
			</div>	
			<div class="message-description">
				<span>Message Description</span>
			</div>
		<div class="clearfix"></div>	
		</div>
		
		<div id="free">			
		<div class="panel-group message-goup" id="accordion">

	<?php if(count($ArGroupMessage)>0){?>
  <?php foreach($ArGroupMessage as $key=>$GroupMessage){?>
			<div class="panel panel-default" id="topic_div_<?php echo $GroupMessage['GroupMessage']['id'];?>">
			  <div class="panel-heading">
				<div class="message-time">
					<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
					<span class="time-info"><?php echo date('M j, Y, h:i A',strtotime($GroupMessage['GroupMessage']['created']));?></span>
					</a>
				</div>
				
				<div class="reply">
					<ul class="action-list">
					<li>
						<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
							<i class="fa fa-mail-reply"></i>
						</a>
						<span class="reply-counter text-center" id="reply_cnt_<?php echo $GroupMessage['GroupMessage']['id'];?>"><?php echo count($GroupMessage['GroupMessageReply']);?></span>
					</li>

		<?php $can_edit_delete = $this->requestAction('group/check_edit_delete_auth/'.$GroupMessage['GroupMessage']['id']);
		if($can_edit_delete == 1){
		?>
					<li><a href="javascript:void(0)" class="Assign_link" data-id="<?php echo $GroupMessage['GroupMessage']['id'];?>" data-groupid="<?php echo $GroupMessage['GroupMessage']['group_id'];?>" data-topic="<?php echo $GroupMessage['GroupMessage']['topic'];?>" data-message="<?php echo $GroupMessage['GroupMessage']['message'];?>" data-toggle="modal" data-target="#edittopicModal"><i class="fa fa-pencil"></i></a></li>
					<li><a href="javascript:void(0)" onclick="delete_topic(<?php echo $GroupMessage['GroupMessage']['id'];?>,<?php echo $GroupMessage['GroupMessage']['group_id'];?>);"><i class="fa fa-trash"></i></a></li>
		<?php } ?>

					</ul>
				</div>
				<div class="postedby">
					<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
					<div class="user-message-img">
						<?php if($GroupMessage['User']['image']!=''){?>
		  <img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $GroupMessage['User']['image'];?>" alt="">
		  <?php }else{ ?>
		  <img  src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
		  <?php } ?> 
					</div>
					<div class="message-user"><?php echo ucwords($GroupMessage['User']['fname'].' '.$GroupMessage['User']['lname']);?></div>
					</a>
				</div>
				<div class="message-topic">
					<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
				   <p><strong><?php echo (strlen($GroupMessage['GroupMessage']['topic']) > 15) ? substr($GroupMessage['GroupMessage']['topic'],0,15).'...' : $GroupMessage['GroupMessage']['topic'];?></strong></p>
				   </a>
				</div>
				<div class="message-description">
					<a href="#messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" data-toggle="collapse" data-parent="#accordion">
				<p><?php echo (strlen($GroupMessage['GroupMessage']['message']) > 45) ? substr($GroupMessage['GroupMessage']['message'],0,45).'...' : $GroupMessage['GroupMessage']['message'];?></p>
				</a>
				</div>
				
				</div>
				<div id="messageList_<?php echo $GroupMessage['GroupMessage']['id'];?>" class="panel-collapse collapse">
					<div class="message-content">
						<div class="message-description-row">
							<h2><?php echo stripcslashes($GroupMessage['GroupMessage']['topic']);?></h2>
							<p><?php echo stripcslashes($GroupMessage['GroupMessage']['message']);?></p>
						</div>
						
		  <?php if(($group_user_type=='M' || $group_user_type=='O')){ ?>
						<div class="message-reply" id="message_reply_<?php echo $GroupMessage['GroupMessage']['id'];?>">
							<textarea class="form-control" rows="3" id="reply_<?php echo $GroupMessage['GroupMessage']['id'];?>" name="reply_<?php echo $GroupMessage['GroupMessage']['id'];?>"></textarea>
							<button type="button" class="btn btn-primary" onclick="message_reply_submit(<?php echo $GroupMessage['GroupMessage']['id'];?>,<?php echo $GroupMessage['GroupMessage']['group_id'];?>)">Submit</button>
						</div>
		  <?php } ?>

		  <div class="clearfix"></div>

		  <div class="message-reply-row">
			<h4>Message Reply</h4>
			<div class="message-list-row">
			<ul id="commentscontainer_<?php echo $GroupMessage['GroupMessage']['id'];?>">

			<?php foreach($GroupMessage['GroupMessageReply'] as $MessageReply){?>
			  <li id="topic_reply_div_<?php echo $MessageReply['id'];?>">
			  <?php $ReplyUserDetail = $this->requestAction('group/getUserDetails/'.$MessageReply['replied_by']);?>
				<div class="reply-user-img">
				  <?php if($ReplyUserDetail['User']['image']!=''){?>
				  <img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $ReplyUserDetail['User']['image'];?>" alt="">
				  <?php }else{ ?>
				  <img  src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
				  <?php } ?> 
				</div>
				<div class="reply-content">
				  <?php if($MessageReply['replied_by'] == $this->Session->read('userData.User.id') || $group_user_type=='O'){?>
            <div class="reply-action">
            <a href="javascript:void(0)" class="Reply_Assign_link" data-id="<?php echo $MessageReply['id'];?>"  data-reply="<?php echo $MessageReply['reply'];?>" data-group_id='<?php echo $GroupMessage['GroupMessage']['group_id'];?>' data-toggle="modal" data-target="#editreplyModal" class="edit-reply">
  						<i class="fa fa-pencil"></i>
  					</a>
  					<a href="javascript:void(0)" onclick="delete_reply(<?php echo $MessageReply['id'];?>,<?php echo $GroupMessage['GroupMessage']['group_id'];?>)" class="trash-reply">
  						<i class="fa fa-trash"></i>
  					</a>
  				  </div>
				  <?php } ?>
          <div>
					<span class="user-replyname"><?php echo ucwords($ReplyUserDetail['User']['fname'].' '.$ReplyUserDetail['User']['lname']);?></span>
					<span class="reply-date"><?php echo date('M j, Y, h:i A',strtotime($MessageReply['created']));?></span>
				  </div>  
				  <div class="clearfix"></div>
				  <p><?php echo stripslashes($MessageReply['reply']);?></p>
				</div>
			  </li>
			  <?php } ?>

			  
			</ul>
			</div>
		  </div>


					</div>			
				</div>
			<div class="clearfix"></div>	
			</div>
	<?php } ?>





<!-- Edit Message Modal -->
<div id="editreplyModal" class="modal fade" role="dialog">
<div class="modal-dialog compose-modal-dialog">
<!-- Modal content-->
<div class="modal-content"> 
<div class="modal-header"> 
  <button type="button" class="close" data-dismiss="modal">×</button>
  <h4 class="modal-title">Edit Reply</h4>    
</div>

<form method="POST" id="edit_reply_form">
<input type="hidden" name="reply_id" id="edit_reply_id">
<input type="hidden" name="group_id" id="reply_group_id">
<div class="modal-body">

<div class="form-group">
  <label class="label-field">Reply</label>
  <textarea class="form-control" name="reply" id="reply_content" rows="4"></textarea>
  <div class="clearfix"></div>
</div>
<button type="button" onclick="edit_reply()" class="btn signupbtn">Submit</button>
</div>

</form>
</div>
</div>
</div>
<!-- Edit Message Modal -->


<script>
function edit_reply()
{

jQuery('#ajax_message').removeClass('ajax-message');
jQuery('#ajax_message').html("");
jQuery('#edittopicModal').modal('hide');
var reply_id = jQuery("#edit_reply_id").val();
var reply = jQuery("#reply_content").val();
var group_id = jQuery('#reply_group_id').val();
//alert(message_id+','+message+','+topic);
jQuery.ajax({
type: 'GET',
url: '<?php echo $this->webroot; ?>group/edit_reply',
data: {reply_id: reply_id, reply: reply, group_id: group_id},
success: function(resp) 
{
  //alert(resp);
  jQuery('#editreplyModal').modal('hide');
  if(resp == "1")
  {
    jQuery('#ajax_message').addClass('ajax-message');
    jQuery('#ajax_message').html("Sorry!! You are not authorized to edit.");
    setTimeout(hide_alert, 4000);
  }
  else if(resp == "2")
  {
    jQuery('#ajax_message').addClass('ajax-message');
    jQuery('#ajax_message').html("Sorry!! Record doesn't exists.");
    setTimeout(hide_alert, 4000);
  }
  else
  {
    jQuery('#ajax_message').removeClass('ajax-message');
    jQuery('#ajax_message').html("");
    jQuery('#topic_reply_div_'+reply_id).html(resp);
  }
}
});
}
</script>




















	<?php 
		  $paginationData_f = paging_ajax($lastpage_f,$page_f,$prev_f,$next_f,$lpm1_f,"paging_f"); 
	  ?>
	
	  <script language="javascript">
    function delete_reply(reply_id,group_id)
    {   

      jQuery('#ajax_message').removeClass('ajax-message');
      jQuery('#ajax_message').html("");
      var confirm = window.confirm('Are you sure you want to delete this reply?');
      if(confirm) {
        $.ajax({
           type: "GET",
           url: WEBROOT+"group/delete_reply",
           data: { reply_id: reply_id, group_id: group_id },
           success: function(msg){
              //alert(msg);
              if(msg==0)
              {
                jQuery('#topic_reply_div_'+reply_id).remove(); 
                window.location.reload();
              }
              else if(msg==1)
              {
                jQuery('#ajax_message').addClass('ajax-message');
                jQuery('#ajax_message').html("Sorry!! You are not authorized to edit.");
                setTimeout(hide_alert, 4000);
              }
              else if(msg==2)
              {
                jQuery('#ajax_message').addClass('ajax-message');
                jQuery('#ajax_message').html("Sorry!! Record doesn't exists.");
                setTimeout(hide_alert, 4000);
              }
           }
          });
      }
    }

	  function paging_f(val)
	  {   
  		if(val!=''){
  		var group_id = '<?php echo $group_details['Group']['id']; ?>';
  		//alert(group_id);
  		$.ajax({
  			 type: "GET",
  			 url: WEBROOT+"group/post_all_reply",
  			 data: { page: val, group_id: group_id },
  			 success: function(msg){
  			   if(msg!=0){
  				//alert(msg);
  				$('#free').html(msg); 
  			  }
  			 }
  		  });
  		}
	  }
    </script>
	
	  <?php     
		if(isset($paginationData_f))
		{
		  echo $paginationData_f;
		}
	  ?>
	  <form name="frm_act" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<input type="hidden" name="mode" value="">
		<input type="hidden" name="page" value="">    
	  </form>


	<?php }else{
	  echo 'No Posts Found!!';
	} ?>

		</div>

	</div>

 

  <script type="text/javascript">
function message_reply_submit(message_id,group_id){
var commentString = $("textarea#reply_"+message_id).val();
if(commentString!=""){
$.ajax({
	url: '<?php echo $this->webroot; ?>group/post_reply',
	type: 'POST',
	 data: {
	  comment : commentString,
	  message_id : message_id,
    group_id: group_id
	},
	success: function(data) {
	  var html = data.split('@@@');
	  jQuery('#reply_cnt_'+message_id).html(html[0]);
	  $("textarea#reply_"+message_id).val('');         
	  $('#commentscontainer_'+message_id).append(html[1]);
	  
	}
});
}else{
$("textarea#reply_"+message_id).val('');
}
}

</script>
	</div>
	
	</div>
		
	<div class="clearfix"></div>
	
   <div class="documents">
<h3>Gallery</h3>
  <?php   if(!empty($photo_list)) { 
  
if(count($photo_list)<'6'){ ?>
<div class="zoom-gallery">

<ul>
<?php foreach($photo_list as $val)
	  
	   {  ?>
		<li>
		   <div class="gallery-box">
			<a class="imgpopup" href="<?php echo $this->webroot.'gallery/'.stripslashes($val['GroupImage']['image']);?>">
			<img src="<?php echo $this->webroot.'gallery/web/'.stripslashes($val['GroupImage']['image']);?>"alt="" />					
			</a>
			<?php if($group_member_type == 'owner'){ ?>
			<span class="remove-gallery-img">
				<a href="javascript:void(0)" onClick="delete_image(<?php echo $val['GroupImage']['id']; ?>);">
	<i class="fa fa-trash"></i></a>
			</span>
  <?php } ?>
		   </div>
	  </li>
   
  <?php } ?>
   
</ul>


</div>
<div class="clearfix"></div>  
 <?php } else { ?>

<div class="video-slide">
  <div class="slide zoom-gallery owl-carousel owl-theme">
 
		<?php foreach($photo_list as $val){  ?>
				  <div class="item">
				   <div class="gallery-box">
					<a class="imgpopup" href="<?php echo $this->webroot.'gallery/'.stripslashes($val['GroupImage']['image']); ?>">
					<img src="<?php echo $this->webroot.'gallery/web/'.stripslashes($val['GroupImage']['image']); ?>"alt="" />						
					</a>
	  <?php if($group_member_type == 'owner'){ ?>
					<span class="remove-gallery-img">
						<a href="javascript:void(0)" onClick="delete_image(<?php echo $val['GroupImage']['id']; ?>);"><i class="fa fa-trash"></i></a>
					</span>
	  <?php } ?>
				   </div>
				   </div>
			 
		  <?php } ?>
		 
	
</div>
<div class="customNavigation">
<a class="btn prev"><i class="fa fa-angle-left"></i></a>
<a class="btn next"><i class="fa fa-angle-right"></i></a>
</div>
</div>
<?php } ?>
<?php } else { ?>
<div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br> NO GALLERY IMAGES</div>
<?php } ?>
<?php if($group_member_type == 'owner') { ?>
<div class="group-bottom-line">
 <a href="javascript:void(0)" class="upload-btn" data-toggle="modal" data-target="#uploadimage">Upload Images</a>       
</div>
<?php } ?>
</div>




      <div class="clearfix"></div>
      <div class="videos">
        <h3>Videos</h3>
         <?php   if(!empty($video_list)){ ?>   
          <?php   if(count($video_list)>'6'){ ?>   
	
		<div class="video-slide">
		<div class="slide popup-youtube owl-carousel owl-theme">
    <?php foreach($video_list as $valu)
            {  ?>
       <div class="item">
				<div class="video-img">
				  <img src="<?php echo $valu['Video']['v_image']; ?>" alt="" />
				 	<div class="video-overlay">
					<a href="<?php echo $valu['Video']['video']; ?>">
           <i class="fa video-icon"></i>
          </a>
          <?php if($group_member_type == 'owner'){ ?>
          <span class="remove-gallery-img">
            <a href="javascript:void(0)" onClick="delete_video(<?php echo $valu['Video']['id']; ?>);">
            <i class="fa fa-trash"></i></a>
          </span>
          <?php } ?>
					 
				  </div>
           
				</div>				
			</div>
      <?php } ?>
			
						
		</div>
		<div class="customNavigation">
			<a class="btn prev"><i class="fa fa-angle-left"></i></a>
			<a class="btn next"><i class="fa fa-angle-right"></i></a>
		</div>
		</div>
    
     <?php } else { ?>


      <div class="slide popup-youtube owl-carousel owl-theme">
    <?php foreach($video_list as $valu){  ?>
     <div class="item">
        
        <div class="video-img">
				  <img src="<?php echo $valu['Video']['v_image']; ?>" alt="" />
				 	<div class="video-overlay">
					<a href="<?php echo $valu['Video']['video']; ?>">
					  <i class="fa video-icon"></i>
					</a>
           <?php if($group_member_type == 'owner'){ ?>
          <span class="remove-gallery-img">
            <a href="javascript:void(0)" onClick="delete_video(<?php echo $valu['Video']['id']; ?>);">
            <i class="fa fa-trash"></i></a>
          </span>
          <?php } ?>
				  </div>
           
				</div>	       
      </div>
      <?php } ?>
     
    </div>
    <?php } ?>
		<?php }else { ?>
      <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>NO GALLERY VIDEOS</div>
      <?php } ?>
        <?php if($group_member_type == 'owner'){ ?>
      <div class="group-bottom-line">
        <a href="javascript:void(0)" class="upload-btn" data-toggle="modal" data-target="#upvideo">Upload Videos</a>
      </div>
      <?php } ?>
      </div>  
      
      <div class="clearfix"></div>

      <div class="documents">
        <h3>Documents</h3>
          <?php   if(!empty($doc_list)) { ?>      
         <?php   if(count($doc_list)<'6'){ ?>
        <div>
        
        <ul>
        <?php foreach($doc_list as $val){  ?>
            
          <li>
           <?php if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'docx') || (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'doc')){ ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.stripslashes($val['GroupDoc']['docname']); ?>"target="_blank">
            <div class="document-box doc">
            
              <div class="document-icon">
               
             <i class="fa doc-icon"></i>
                
              </div>
               <span class="document-name"><?php echo stripslashes($val['GroupDoc']['docname']); ?></span>
            </div>
            </a>
            <?php if($group_member_type == 'owner'){ ?>
			<span class="remove-gallery-img">
				<a href="javascript:void(0)" onClick="delete_doc(<?php echo $val['GroupDoc']['id']; ?>);"><i class="fa fa-trash"></i></a>
			</span>
      <?php } ?>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'pdf') )  
              { ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.stripslashes($val['GroupDoc']['docname']); ?>"target="_blank">
            <div class="document-box pdf">
           
              <div class="document-icon">
              <i class="fa pdf-icon"></i>
               </div>
                <span class="document-name"><?php echo stripslashes($val['GroupDoc']['docname']); ?></span>
            </div>
            </a>
            <?php if($group_member_type == 'owner'){ ?>
			<span class="remove-gallery-img">
				<a href="javascript:void(0)" onClick="delete_doc(<?php echo $val['GroupDoc']['id']; ?>);"><i class="fa fa-trash"></i></a>
			</span>
      <?php } ?>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'xls') )  
              { ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.stripslashes($val['GroupDoc']['docname']);?>"target="_blank">
             <div class="document-box xls">
              
              <div class="document-icon">
              <i class="fa xls-icon"></i>
              </div>
              <span class="document-name"><?php echo stripslashes($val['GroupDoc']['docname']); ?></span>
            </div>
             </a>
             <?php if($group_member_type == 'owner'){ ?>
			 <span class="remove-gallery-img">
				<a href="javascript:void(0)" onClick="delete_doc(<?php echo $val['GroupDoc']['id']; ?>);"><i class="fa fa-trash"></i></a>
			</span>
      <?php } ?>
            <?php } ?>
          </li>
          <?php } ?>
           
        </ul>
       

        </div>
        <div class="clearfix"></div>  
         <?php } else { ?>

        <div class="video-slide">
          <div class="slide owl-carousel owl-theme">
        <?php foreach($doc_list as $val)
              
               {  ?>
                   
           <?php if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'docx') || (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'doc') ) { ?>
            <div class="item">
				<a href="<?php echo $this->webroot.'gallery/doc/'.stripslashes($val['GroupDoc']['docname']); ?>"target="_blank">
					<div class="document-box doc">
					  <div class="document-icon">                
						 <i class="fa doc-icon"></i>
					  </div>
					  <span class="document-name"><?php echo stripslashes($val['GroupDoc']['docname']); ?></span>
					</div>
				</a>
        <?php if($group_member_type == 'owner'){ ?>
				<span class="remove-gallery-img">
					<a href="javascript:void(0)" onClick="delete_doc(<?php echo $val['GroupDoc']['id']; ?>);"><i class="fa fa-trash"></i></a>
				</span>
        <?php } ?>
            </div>
            <?php }
            else if((substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'pdf') )  
              { ?>
                <div class="item">
				<a href="<?php echo $this->webroot.'gallery/doc/'.stripslashes($val['GroupDoc']['docname']);?>"target="_blank">
				<div class="document-box pdf">
				  <div class="document-icon">
				 
				  <i class="fa pdf-icon"></i>
								
				  </div>
				  <span class="document-name"><?php echo stripslashes($val['GroupDoc']['docname']); ?></span>
				</div>
				</a>
      <?php if($group_member_type == 'owner'){ ?>
				<span class="remove-gallery-img">
					<a href="javascript:void(0)" onClick="delete_doc(<?php echo $val['GroupDoc']['id']; ?>);"><i class="fa fa-trash"></i></a>
				</span>
        <?php } ?>
            </div>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'xls') )  
              { ?>
              <div class="item">
             <a href="<?php echo $this->webroot.'gallery/doc/'.stripslashes($val['GroupDoc']['docname']);?>"target="_blank">
             <div class="document-box xls">
              <div class="document-icon">
              
             <i class="fa xls-icon"></i>
                
                
              </div>
              <span class="document-name"><?php echo stripslashes($val['GroupDoc']['docname']); ?></span>
            </div>
            </a>
            <?php if($group_member_type == 'owner'){ ?>
			<span class="remove-gallery-img">
				<a href="javascript:void(0)" onClick="delete_doc(<?php echo $val['GroupDoc']['id']; ?>);"><i class="fa fa-trash"></i></a>
			</span>
      <?php } ?>
            </div>
            <?php } ?>
          
          <?php } ?>
         
     
    </div>
     <div class="customNavigation">
      <a class="btn prev"><i class="fa fa-angle-left"></i></a>
      <a class="btn next"><i class="fa fa-angle-right"></i></a>
    </div>
    </div>
    <?php } ?>
    	<?php } else { ?>
       <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>NO GALLERY DOCUMENTS</div>
      <?php } ?>
        <?php if($group_member_type == 'owner') { ?>
    <div class="group-bottom-line">
        <a href="https://jsfiddle.net/user/login/" class="upload-btn fancybox" data-toggle="modal" data-target="#uploaddoc">Upload DOC</a>        
      </div>
      <?php } ?>
    </div>
    
      
      <div class="clearfix"></div>  
      </div>
    </div>
  </div>  
 
  
  

  
</main>   

<script>
  function showMyImage(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil8");
      img.file = file;
      var reader = new FileReader();
      reader.onload = (function (aImg) {
        return function (e) {
          aImg.src = e.target.result;
        };
      })(img);
      reader.readAsDataURL(file);
    }
  }
</script>


