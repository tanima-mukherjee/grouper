 


<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<!--date picker start-->

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
<script src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>

<!--date picker ends-->
<!-- google geo complete starts -->

  <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
<!--   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->

  <script src="<?php  echo $this->webroot;?>js/jquery.geocomplete.js"></script>

  <script>
$(function(){
$("#geocomplete").geocomplete();
});
</script>
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

 
          var geocoder = new google.maps.Geocoder();
          var address = $('#geocomplete').val();
         
          geocoder.geocode( { 'address': address}, function(results, status) {
          
            if (status == google.maps.GeocoderStatus.OK) {
             var latitude = results[0].geometry.location.lat();
             var longitude = results[0].geometry.location.lng();
             var place_id = results[0].place_id;
            // var datatlocation = results[0]->address_components;
            
             $('#lat').val(latitude);   
             $('#lng').val(longitude);
             $('#place_id').val(place_id);
            
            }
            else{
               $('#lat').val('');   
               $('#lng').val('');
               $('#place_id').val('');

            }
          }); 

      }
}
</script>
<!-- getting lat long using onblur end  -->

<script>
$(function() {
  $('#event_date').datetimepicker({
    format:'Y-m-d h:m A',
    formatDate:'y-m-d h:m',
    minDate: 0,
    defaultDate:'<?php echo date('y-m-d h:m'); ?>', // it's my birthday
    //minDate:'<?php echo date('Y-m-d'); ?>',
    timepicker:true,
    formatTime: 'h:i A',

  });
});
</script>
<script>
$(function() {
  $('#event_start_date').datetimepicker({
    format:'Y-m-d h:m A',
    formatDate:'y-m-d h:m',
    minDate: 0,
    defaultDate:'<?php echo date('y-m-d h:m'); ?>', // it's my birthday
    //minDate:'<?php echo date('Y-m-d'); ?>',
    timepicker:true,
    formatTime: 'h:i A',

  });
});
</script>
<script>
$(function() {
  $('#event_end_date').datetimepicker({
    format:'Y-m-d h:m A',
    formatDate:'y-m-d h:m',
    minDate: 0,
    defaultDate:'<?php echo date('y-m-d h:m'); ?>', // it's my birthday
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
        //address: "required",
        desc: "required",
         amount: {
            required: true,
            number: true
         },
             
         event_date: {
          required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == '0';

                    }
                }  
         },
          
         lat: {
          required: {
              depends: function(element){ //Missing colon here and no opening braces required
                  return ($('#address').val() != ""); 
                  }
               }
          },

          event_start_date: {
          required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == '1';
                    }
                } 
         },
         event_end_date: {
          required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == '1';
                    }
                } 
         }
        } ,
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         title : "Please enter event name",
        // address : "Please select an address",
         lat: {
            required: "Please select a proper address"
           },
         desc : "Please enter event description of min 100 char",
         amount:{
         required: "Please mention a deal amount",
         number:"Please enter correct amount "
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
<!-- validation for create event ends -->
 
 <style>
   .error{
    color: #f00;
   }
 </style>

 <!-- view user Modal -->
 <!-- Join Now Modal start -->
<div id="joinnow" class="modal fade" role="dialog">
  <div class="modal-dialog group-modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">      
      <div class="modal-body">

    <form action="<?php echo $this->webroot.'group/group_detail_join_now_request/'.$group_details['Group']['id'];?>" method="post" id="join_request_form" name="join_request_form" >  
    <div class="group-join">
      <span class="group-type">     
        <input type="radio" name="group_type"  value="public" id="public" checked="checked" />
        <label for="public">Public</label>
      </span>
      <span class="group-type">     
        <input type="radio" name="group_type" value="private" id="private" />
        <label for="private">Private</label>
      </span>
      <div class="clearfix"></div>
      <!-- <input type="hidden" name="id_of_group" id="id_of_group" value="">   -->
      <div class="group-type-submit">
        <button type="submit">Submit</button>
      </div>
    </div>

    </form>

      </div>     
    </div>
  </div>
</div>
 <!-- Join Now modal end -->
 
 
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
        <h4 class="modal-title">Upload Video</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo $this->webroot?>group/add_group_video" method="post" id="video_form" 
      name="video_form" enctype="multipart/form-data"> 
      <input type="hidden" name="grp_id" id="grp_id" value="<?php echo $group_id; ?>" />
       <div class="upload-video">
			<label class="label-video">Upload Video</label>
			<span class="video-file">
				<input type="file" name="video_file" id="video_file" />
         <div class="clearfix"></div>   
       
		  </span>	
      <label class="label-video">Upload Image</label>
      <span class="video-file">
        <input type="file" name="image_file" id="image_file" />
         <div class="clearfix"></div>   
       
      </span> 
       <button type="submit" class="btn signupbtn">Submit</button>		
	   <div class="clearfix"></div>		
	   </div>
     </form>
	   
	  
	   
      </div>
    </div>

  </div>
</div>
<!-- category modal start -->
<div class="modal fade" id="editgroup" role="dialog">
    <div class="modal-dialog signup-model">    
 <!--    <div class="modal-content">
     
      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Edit Category</h4>
    </div>

    <div class="modal-body">  
    <form action="<?php echo $this->webroot?>group/edit_category/<?php echo $category_details['Category']['id']; ?>" method="post" id="edit_category_form" name="edit_category_form" enctype="multipart/form-data">
    <div class="pop-createg2">

    <div class="upload-img">
    <div class="pop-createg-img">
    <?php if($category_details['Category']['image']!=''){?>
                        <img id="thumbnil1" src="<?php echo $this->webroot;?>category_photos/<?php echo $category_details['Category']['image'];?>" alt="">
                        
                        <?php }else{ ?>
                        <img id="thumbnil1" src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
                          <?php } ?>
                     
                        
    </div>
    
    
    <span class="btn-bs-file cmicon2">
                Browse
    <input onChange="showMyCatImage(this)" name="c_upload_image" id="c_upload_image" value="<?php echo $category_details['Category']['image'];?>" data-badge="false" type="file"/>
    </span>
    </div>
     <div class="clearfix"></div>
    <label for="c_upload_image" generated="true" class="error" style="display:none;">Please upload  image</label>
  
     
    </div>
  
    <div class="signup-left">
    
      <div class="form-group">
        <label class="label-field">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo ucfirst($category_details['Category']['title'])?>">   
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Description</label>
    <textarea id="desc" name="desc" class="form-control" cols="" rows=""><?php echo ucfirst($category_details['Category']['category_desc'])?></textarea>  
       
         <div class="clearfix"></div>                   
      </div>    
                  
            
      <button type="submit" class="btn signupbtn">Update</button>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div> -->
    <div class="modal-content">
      <!-- Modal content-->

      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Edit Group</h4>
    </div>
       
    <div class="modal-body">
   <form action="<?php echo $this->webroot.'category/edit_group/'.$group_details['Group']['id'];?>" method="post" id="group_form" name="group_form" enctype="multipart/form-data">  

  <div class="pop-createg">
  
  <div class="upload-img">

    <div class="pop-createg-round">


          
    <?php if($group_details['Group']['icon']!=''){?>
                        <img id="thumbnil" src="<?php echo $this->webroot;?>group_images/<?php echo $group_details['Group']['icon'];?>" alt="">
                        
                        <?php }else{ ?>
                        <img id="thumbnil" src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
                          <?php } ?>
                     
                        
    </div>

   <span class="btn-bs-file browse-btn">Browse</span>
   
    <input onChange="showMyImage(this)" name="upload_image" id="upload_image" value="<?php echo $group_details['Group']['icon'];?>" data-badge="false" type="file"/>
  <div class="clearfix"></div>
    </div>
    
  </div>
  
    <div class="signup-left">
  
      <div class="form-group">
        <label class="label-field">Title</label>
        <input type="text" class="form-control" id="group_name" name="group_name" value="<?php echo ucfirst($group_details['Group']['group_title'])?>">   
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Description</label>
       <textarea name="g_desc" id="g_desc" class="form-control" cols="" rows=""><?php echo ucfirst($group_details['Group']['group_desc'])?></textarea>
         <div class="clearfix"></div>                   
      </div> 
       <div class="form-group">
        <label class="label-field">Purpose</label>
       <textarea name="g_purpose" id="g_purpose" class="form-control" cols="" rows=""><?php echo ucfirst($group_details['Group']['group_purpose'])?></textarea>
         <div class="clearfix"></div>                   
      </div> 
              
            
      <button type="submit" class="btn signupbtn">Edit Group</button>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>
<!-- group modal end -->

<!-- Event Modal -->
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
            <input type="radio" name="is_multiple" value="0" id="is_multiple" checked="checked">
            <label for="is_single">Single Day</label>
          </span>
          <span class="group-type">     
            <input type="radio" name="is_multiple" value="1" id="is_multiple">
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

      <?php if($group_details['Group']['group_type']=='F')
                   { ?>
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
				 
       <!--   <input id="geocomplete" type="text" name="location" class="form-control"/> -->

        <input id="geocomplete" type="text" placeholder="Type in an address" size="90" class="form-control"
        name="address" onblur="check_address(this.value);" />
                        
            <input name="lat" id="lat" type="hidden" value="">
            <input name="lng" id="lng" type="hidden" value="">
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
					<input type="text" class="form-control" name="event_date" id="event_date"  /> 
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
          <input type="text" class="form-control" name="event_start_date" id="event_start_date"  /> 
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
          <input type="text" class="form-control" name="event_end_date" id="event_end_date"  /> 
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
					<label class="label-display">Deal Amount:<b class="required">*</b></label>
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
					<textarea class="form-control comment" id="desc" name="desc" minlength="50"></textarea> 
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
		
      </div> 
      </form>     
    </div>

  </div>
</div>

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
           
             <li><a href="#" data-toggle="modal" data-target="#editgroup"> Edit <i class="fa create-icon"></i></a></li>
            <li><a class="userbox view-user" href="<?php echo $this->webroot?>group/push_message/<?php echo $group_details['Group']['id'];?>">Push your message <i class="fa create-icon"></i></a></li>
          </ul>
        </div>
        <?php } ?>
         <?php if($group_member_type == 'owner') { ?>
        <div class="page-right-nav">
          <ul>
           <!--  <li><a href="#" data-toggle="modal" data-target="#usermodal">View users <i class="fa create-icon"></i></a></li> -->
            <li><a  class="userbox view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $group_details['Group']['id'];?>" > View users <i class="fa create-icon"></i></a></li>

            <li><a href="#">Send Invitation <i class="fa create-icon"></i></a></li>
          </ul>
        </div>
        <?php } else if($group_member_type == 'member') { ?>
        <div class="page-right-nav">
          <ul>
           <!--  <li><a href="#" data-toggle="modal" data-target="#usermodal">View users <i class="fa create-icon"></i></a></li> -->
             <?php if($group_details['Group']['group_type'] == 'F') { ?>
            <li><a  class="userbox view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $group_details['Group']['id'];?>" > View users <i class="fa create-icon"></i></a></li>
            <?php } ?>
            <li><a href="#">Send Recommendation <i class="fa create-icon"></i></a></li>
          </ul>
        </div>
        <?php } else { ?>
          <div class="page-right-nav">
          <ul>
          <?php  $GroupIsJoin = $this->requestAction('group/group_is_join/'.$group_details['Group']['id'].'/');
                 if($GroupIsJoin > 'O') { ?>
                <li><a href="javascript:void(0)" class="group-btn invite">Request Sent <i class="fa create-icon"></i></a></li>
                <?php }else{ ?>
                <li><a href="#" data-toggle="modal" data-target="#joinnow" data-id="<?php echo $group_details['Group']['id'];?>" >Join Now <i class="fa create-icon"></i></a></li>
                <?php } ?>

          
           
          </ul>
        </div>
        <?php } ?>
      <div class="clearfix"></div>  
      </div>

      <div class="group-details">
      <div class="group-details-info">
        <div class="group-details-img">
          <img src="<?php echo $this->webroot.'group_images/medium/'.$group_details['Group']['icon'];?>"alt="" />
        </div>
        <div class="group-details-content">
          <h4><?php echo ucfirst($group_details['Group']['group_title'])?></h4>
          <p><?php echo ucfirst($group_details['Group']['group_desc'])?>
          </p>
        </div>
      <div class="clearfix"></div>  
      </div>

           <div class="documents">
        <h3>Gallery</h3>
          <?php   if(!empty($photo_list)) { ?>      
         <?php   if(count($photo_list)<'6')
              { ?>
        <div class="zoom-gallery">
        
        <ul>
        <?php foreach($photo_list as $val)
              
               {  ?>
                <li>
                   <div class="gallery-box">
                    <a href="<?php echo $this->webroot.'gallery/'.$val['GroupImage']['image'];?>">
                   <img src="<?php echo $this->webroot.'gallery/web/'.$val['GroupImage']['image'];?>"alt="" />
                    </a>
                   </div>
              </li>
           
          <?php } ?>
           
        </ul>
       

        </div>
        <div class="clearfix"></div>  
         <?php } else { ?>

        <div class="video-slide">
          <div class="slide zoom-gallery" class="owl-carousel owl-theme">
         
				<?php foreach($photo_list as $val)
					  
					   {  ?>
						  <div class="item">
						   <div class="gallery-box">
							<a href="<?php echo $this->webroot.'gallery/'.$val['GroupImage']['image'];?>">
						    <img src="<?php echo $this->webroot.'gallery/web/'.$val['GroupImage']['image'];?>"alt="" />
							</a>
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
         <a href="#" class="upload-btn" data-toggle="modal" data-target="#uploadimage">Upload Images</a>       
      </div>
      <?php } ?>
    </div>




      <div class="clearfix"></div>
      <div class="videos">
        <h3>Videos</h3>
         <?php   if(!empty($video_list)) { ?>   
          <?php   if(count($video_list)>'6')
              { ?>   
	
		<div class="video-slide">
		<div class="slide popup-youtube" class="owl-carousel owl-theme">
    <?php foreach($video_list as $valu)
            {  ?>
       <div class="item">
				<div class="video-img">
				  <img src="<?php echo $this->webroot;?>group_videos/images/<?php echo $valu['Video']['v_image']; ?>" alt="" />
				 <div class="video-overlay">
					<a href="<?php echo $this->webroot;?>group/popup_video/<?php echo $valu['Video']['id']; ?>">
					  <i class="fa video-icon"></i>
					</a>
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


      <div class="slide popup-youtube" class="owl-carousel owl-theme">
    <?php foreach($video_list as $valu)
            {  ?>
     <div class="item">
        <div class="video-img">
          <img src="<?php echo $this->webroot;?>group_videos/images/<?php echo $valu['Video']['v_image']; ?>" alt="" />
          <div class="video-overlay">
          <a href="<?php echo $this->webroot;?>group/popup_video/<?php echo $valu['Video']['id']; ?>">
            <i class="fa video-icon"></i>
          </a>
          </div>
           

      </div>        
      </div>
      <?php } ?>
     
    </div>
    <?php } ?>
		<?php }else { ?>
      <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>NO GALLERY VIDEOS</div>
      <?php } ?>
        <?php if($group_member_type == 'owner') { ?>
      <div class="group-bottom-line">
        <a href="#" class="upload-btn" data-toggle="modal" data-target="#upvideo">Upload Videos</a>
      </div>
      <?php } ?>
      </div>  
      
      <div class="clearfix"></div>

      <div class="documents">
        <h3>Documents</h3>
          <?php   if(!empty($doc_list)) { ?>      
         <?php   if(count($doc_list)<'6')
              { ?>
        <div>
        
        <ul>
        <?php foreach($doc_list as $val)
              
               {  ?>
            
          <li>
           <?php if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'docx') || (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'doc') ) { ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
            <div class="document-box doc">
            
              <div class="document-icon">
               
             <i class="fa doc-icon"></i>
                
              </div>
               <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
            </a>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'pdf') )  
              { ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
            <div class="document-box pdf">
           
              <div class="document-icon">
              <i class="fa pdf-icon"></i>
               </div>
                <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
            </a>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'xls') )  
              { ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
             <div class="document-box xls">
              
              <div class="document-icon">
              <i class="fa xls-icon"></i>
              </div>
              <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
             </a>
            <?php } ?>
          </li>
          <?php } ?>
           
        </ul>
       

        </div>
        <div class="clearfix"></div>  
         <?php } else { ?>

        <div class="video-slide">
          <div class="slide" class="owl-carousel owl-theme">
        <?php foreach($doc_list as $val)
              
               {  ?>
                   
           <?php if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'docx') || (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'doc') ) { ?>
            <div class="item">
				<a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
					<div class="document-box doc">
					  <div class="document-icon">                
						 <i class="fa doc-icon"></i>
					  </div>
					  <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
					</div>
				</a>
            </div>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'pdf') )  
              { ?>
                <div class="item">
            <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
            <div class="document-box pdf">
              <div class="document-icon">
             
              <i class="fa pdf-icon"></i>
                            
              </div>
              <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
            </a>
            </div>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'xls') )  
              { ?>
              <div class="item">
             <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
             <div class="document-box xls">
              <div class="document-icon">
              
             <i class="fa xls-icon"></i>
                
                
              </div>
              <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
            </a>
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
        <a href="http://jsfiddle.net/user/login/" class="upload-btn fancybox" data-toggle="modal" data-target="#uploaddoc">Upload DOC</a>        
      </div>
      <?php } ?>
    </div>
    
      
      <div class="clearfix"></div>  
      </div>
    </div>
  </div>  
 
  <div class="group-calender">
    <div class="container">
      <h3>Group Calendar</h3>
          
	  <div class="pull-right">
		<span class="event-calender">
			<i class="fa fa-event-calender" name="eve_calender" id="eve_calender" onclick="event_calender()"></i>
      <input type="hidden" class="form-control" id="event_calender_group_id" name = "event_calender_group_id" value="<?php echo $group_id?>"/> 
      		
		</span>
      <?php if($group_member_type == 'owner') { ?>
		<a href="javascript:void(0)" class="event-btn" data-toggle="modal" data-target="#eventModal">Create Event</a>
    <?php } ?>
	  </div>

      <div id ="recent_event" name ="recent_event">
      <div class="recent-event">
      <div class="calendar-heading">
        <h4><?php echo date('jS F Y');?></h4>
      </div>
        <?php   if(!empty($event_list)) {
           foreach($event_list as $events)
            {  
         ?>      
      <div class="calendar-box">
        <div class="event-location">
         
           <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $events['latitude'];?>,<?php echo $events['longitude'];?>&amp;output=embed"></iframe>
         
        
         
        </div>
        <div class="event-des">
          <h4><?php echo $events['event_name']; ?></h4>
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
         <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br> NO EVENTS</div>
        <?php } ?>
 
      </div>
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
      var img = document.getElementById("thumbnil");
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


