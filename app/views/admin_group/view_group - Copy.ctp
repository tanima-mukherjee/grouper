<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<!--date picker start-->

<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
<script src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>

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
</script> 
<!-- validation for create event ends -->
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
</script>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/magnific-popup.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/style.default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/style.default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/prettify/prettify.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/prettify/prettify.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-ui-1.9.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.magnific-popup.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/charCount.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/ui.spinner.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/forms.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.dataTables.min.js"></script>
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/excanvas.min.js"></script><![endif]-->
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    
  <script>var WEBROOT = '<?php echo $this->webroot?>';</script>
  <link rel="stylesheet" href="<?php echo $this->webroot?>admin/css/validationEngine.jquery.css" type="text/css"/>
  <script src="<?php echo $this->webroot?>admin/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
  </script>
  <script src="<?php echo $this->webroot?>admin/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
  <script>
  function submit_form(){
    var valid = jQuery("#edit_form").validationEngine('validate');
    if(valid == true){
      jQuery('#submt_butt').attr('disabled',true);
      jQuery('#edit_form').submit();
    }else{
      return false;
    }
  }

  function submit_form1(){

    window.location.href="<?php echo $this->webroot?>admin_group/group_list";
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
          <h1><?php echo $pageTitle; ?></h1> 
		  <button class="btn btn-warning" type="button" id="submt_butt1" onClick="Javascript: submit_form1();" style="position: absolute; right:15px; top:15px;">Back</button>
		  
        </div><!--pagetitle-->
     <form class="stdform ws-validate" id="edit_form" action="" method="post" enctype="multipart/form-data">
          
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
               
                  <div class="span12">
                   
                     <!--start-->
                    

  
  <div class="category-details-content"> 
		 
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
        </div>
    
        <div class="group-details-content">
          <h4><?php echo ucfirst(stripslashes($group_details['Group']['group_title']))?></h4>
          <p><?php echo ucfirst(stripslashes($group_details['Group']['group_desc']))?>
          </p>
        </div>
      <div class="clearfix"></div>  
      </div>

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
            <a href="javascript:void(0)" onClick="delete_image(<?php echo $val['GroupImage']['id']; ?>);"><i class="fa fa-trash"></i></a>
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


      <div class="slide popup-youtube owl-carousel owl-theme">
    <?php foreach($video_list as $valu){  ?>
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
          <div class="slide" class="owl-carousel owl-theme">
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
 
  <div class="group-calender">   
      <h3>Group Calendar</h3>
          
    <div class="pull-right">
    <span class="event-calender group-event-calender">
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
                  {?>   
                     
            <div class="calendar-box">
              <div class="event-location">               
                 <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $events['latitude'];?>,<?php echo $events['longitude'];?>&amp;output=embed"></iframe>
              </div>
                <?php  if($events['group_type'] == 'B' && $events['deal_amount']>0){ ?>
               <div class="event-amount">         
          <div class="deal-amount"><span class="amount-price"> <span class="currency">$</span><?php echo $events['deal_amount']; ?></span>Deal amount</div>
               </div>
                <?php } ?>
              <div class="event-des">
                <h4><?php echo $events['event_name']; ?></h4>
        <?php  if($events['is_multiple_date'] == '1') { ?>
                <div class="event-time"><span><i class="tag-icon"></i> Multiple</span></div>
                <?php } else { ?>
                  <div class="event-time"><span><i class="tag-icon"></i> Single</span></div>
                <?php } ?>
        
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
              </div>
            <div class="clearfix"></div>  
            </div>
            <?php } } else { ?>
               <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>  No events found </div>
              <?php } ?>
       
            </div>
      </div>

  </div>

                    
                   
                     <!-- end -->

                    
                   
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

      <div class="footer">
        <div class="footerleft">Ogma Conceptions</div>
        <div class="footerright">&copy; Copyrights 2014</div>
      </div><!--footer-->


    </div><!--mainwrapper-->

   
   
  </body>
</html>