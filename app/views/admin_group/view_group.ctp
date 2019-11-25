<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/magnific-popup.css" type="text/css" />
	<!-- slide css -->  
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/owl.carousel.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>css/owl.theme.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/style.default.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>    
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/excanvas.min.js"></script><![endif]-->
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
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
		  <!--<button class="btn btn-warning" type="button" id="submt_butt1" onClick="Javascript: submit_form1();" style="position: absolute; right:15px; top:15px;">Back</button>-->
		  <a href="<?php echo $this->webroot.'admin_group/user_list/'.$group_id;?>" class="bigviewuser">View Users</a>
		  
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
          <p><?php echo ucfirst(stripslashes($group_details['Group']['group_desc']))?></p>
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
      <i class="fa fa-event-calender" name="eve_calender" id="eve_calender" onClick="event_calender()"></i>
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
	<script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/bootstrap.min.js"></script>	
	<script type="text/javascript" src="<?php echo $this->webroot; ?>js/owl.carousel.min.js"></script>
   <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.magnific-popup.min.js"></script>
   <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
	<script src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>
	<script>
		$(".slide").each(function() {
		  var $this = $(this);
		  $this.owlCarousel({
			items : 6, 
				itemsDesktop : [992,4],
				itemsDesktopSmall : [768,3], 
				itemsTablet: [450,2], 
				itemsMobile : false,
				pagination : false,
				autoPlay:false
		  });
		  // Custom Navigation Events
		  $this.parent().find(".next").click(function(){
			$this.trigger('owl.next');
		  });
		  $this.parent().find(".prev").click(function(){
			$this.trigger('owl.prev');
		  });
		});
	</script>

   <script>
	$(document).ready(function() {
		$('.zoom-gallery').magnificPopup({
			delegate: 'a.imgpopup',
			type: 'image',
			closeOnContentClick: false,
			closeBtnInside: false,
			mainClass: 'mfp-with-zoom mfp-img-mobile',
			gallery: {
				enabled: true
			},
			zoom: {
				enabled: true,
				duration: 300, // don't foget to change the duration also in CSS
				opener: function(element) {
					return element.find('img');
				}
			}
			
		});
	});
   </script>
   <script>
	$(document).ready(function() {
		$('.popup-youtube, .popup-vimeo').magnificPopup({
		  delegate: 'a',
		  type: 'iframe',
		  mainClass: 'mfp-img-mobile',
		  removalDelay: 160,
		 gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		  },

		  fixedContentPos: false
		});
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
    defaultDate:'17-07-25 08:50', // it's my birthday
    //minDate:'2017-07-25',
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
    defaultDate:'17-07-25 08:50', // it's my birthday
    //minDate:'2017-07-25',
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
    defaultDate:'17-07-25 08:50', // it's my birthday
    //minDate:'2017-07-25',
    timepicker:true,
    formatTime: 'h:i A',

  });
});
</script>
<script>
 var WEBROOT = '/grouper/';
function event_calender(){

  $('#eve_calender').datetimepicker({
    format:'Y-m-d ',
    formatDate:'Y-m-d ',
    minDate: 0,
    defaultDate:'2017-07-25 ', // it's my birthday
    //minDate:'2017-07-25',
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


   
  </body>
</html>