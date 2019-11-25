
<link href="<?php echo $this->webroot?>css/prettyCheckable.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $this->webroot?>css/easy-responsive-tabs.css">
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot ?>js/customer_action.js" type="text/javascript"></script>

<!-- Rating Start -->
<link rel="stylesheet" href="<?php echo $this->webroot?>css/rating-min.css">
<script type="text/javascript" src="<?php echo $this->webroot?>js/tipi.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/rating.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/main2.js"></script>
<script type="text/javascript">
    $(document).ready(function (){
       
        $('#example-inline').rating({
            inline: true,
            showLabel: false
        });
        $('#example-inline').change(function () {
            $('#rating_value_hidden').val($(this).rating('val'));
        });
     });
 
</script>

<!-- Rating End -->
<script>
  $(document).ready(function () {
  $('#horizontalTab').easyResponsiveTabs({
  type: 'default', //Types: default, vertical, accordion           
  width: 'auto', //auto or any width like 600px
  fit: true,   // 100% fit in a container
  closed: 'accordion', // Start closed if in accordion view
  activate: function(event) { // Callback function if tab is switched
  var $tab = $(this);
  var $info = $('#tabInfo');
  var $name = $('span', $info);
  $name.text($tab.text());
  $info.show();
  }
  });
  $('#verticalTab').easyResponsiveTabs({
  type: 'vertical',
  width: 'auto',
  fit: true
  });
  });
  </script>

    <!-- Modal -->    
 <div id="contactModal" class="modal fade" role="dialog">
          <div class="modal-dialog contact-modal">	
	<!-- Modal -->
<div class="modal-content signin-model">
              <div class="modal-header signin-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Message</h4>
              </div>
              <div class="modal-body">
              <form action="<?php echo $this->webroot?>home/profile_contact" method="post" id="profile_user_message" name="profile_user_message">
              <input type="hidden" name="popup-sender-id" id="popup-sender-id" value="" />

            
                <div class="message-modal">
                    <div class="message-field">
                        <label>To</label>
                        <input type="text" class="profileform-control" id="popup-sender-name" readonly="readonly" />
                    </div>
                    
                    <div class="message-field">
                        <label>Message</label>
                        <textarea name="message" class="profileform-control msg" value=""> </textarea>
                         <div class="clearfix"></div>
                    </div>
                    <div class="message-field">
                        <input type="submit" value="Submit" class="savebutton pull-left">
                    </div>
                <div class="clearfix"></div>
                </div>
                </form>
              <div class="clearfix"></div>    
              </div>
              
            </div>
     </div>
        </div> 
    
		
	<div class="clearfix"></div>	
	

	<section class="profile-con">
                                          
    	<div class="container">

        	<div class="profile-bg">
            	<div class="profile-left">
                
                	<div class="profile-bgimg">
                	<!-- <img src="images/profile-img.jpg" alt="" /> -->
                  <?php if($UserData['User']['image']!=''){?>
                        <img  src="<?php echo $this->webroot;?>profile_images/<?php echo $UserData['User']['image'];?>" alt="">
                        
                        <?php }else{ ?>
                        <img  src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="">
                          <?php } ?>
                  
                	</div>
                    <div class="clearfix"></div>
                    <?php if($UserData['User']['type'] == '2') { ?>
                    <h3><?php echo ucfirst($UserData['User']['username']); ?></h3>
                    <?php } else  if ($UserData['User']['type'] == '3'){ ?>
                     <h3><?php echo ucfirst($UserData['User']['facility_name']); ?></h3>   
                     <?php } ?>
                    <p><?php echo ucfirst($UserData['User']['designation']); ?></p>
                    <span class="location"> <i class="fa fa-map-marker"></i><?php echo ucfirst($UserData['City']['name']); ?></span>
                   <?php if($UserData['User']['type']=='2')  {?> 
                     <?php if($UserData['User']['instructor_logo']!=''){?>
                    <div class="job-ads">
                      <img  src="<?php echo $this->webroot;?>instructor_logo/<?php echo $UserData['User']['instructor_logo'];?>" alt="">
                       </div>          
                    <?php } } ?>
                  
                    
                   <?php  if($UserData['User']['type']=='3')  {?> 
                     <?php if($UserData['User']['facility_logo']!=''){?>  
                      <div class="job-ads">              
                        <img  src="<?php echo $this->webroot;?>facility_logo/<?php echo $UserData['User']['facility_logo'];?>" alt="">
                        </div>
                          <?php }  }?>
                         
                          
                          <?php if($UserData['User']['type']=='4')  { ?> 
                           <?php if($UserData['User']['vendor_logo']!=''){?>  
                          <div class="job-ads">                             
                        <img  src="<?php echo $this->webroot;?>vendor_logo/<?php echo $UserData['User']['vendor_logo'];?>" alt="">
                        </div>
                   <?php } } ?>
                     <div class="clearfix"></div> 
                  
                        <div id="googleMap" style="width:100%;height:280px;"></div>
                         <div class="clearfix"></div>  
                         
                        
                         <?php if($SessionRequiredUserId != $UserData['User']['id'] ) { ?>  
                   <div class="class-contact">
                  
                          <?php if($this->Session->check('userData')){?> 
                     <a class="contactBtn" href="#" <?php if($UserData['User']['type']=='2')  {?> onclick="$('#popup-sender-id').val('<?php echo $UserData['User']['id'];?>');$('#popup-sender-name').val('<?php echo $UserData['User']['username'];?>');" title="Contact Details" data-toggle="modal" data-target="#contactModal" <?php } else if($UserData['User']['type']=='3'){ ?> onclick="$('#popup-sender-id').val('<?php echo $UserData['User']['id'];?>');$('#popup-sender-name').val('<?php echo $UserData['User']['facility_name'];?>');" title="Contact Details" data-toggle="modal" data-target="#contactModal" <?php }?>>Contact</a>
               <?php } ?>
                                     
                   </div> 
                   <?php } ?>
                    </div>
                   
                  
                        
                <div class="profile-dtails">
                	
                    <?php if($UserData['User']['type'] == '2') { ?> 
                      <h3>Profile Details</h3>        
                       <?php } else {?>  
                       <h3>Facility Description</h3>    
                       <?php } ?>

                    <?php if($UserData['User']['hear_about_us'] !='') {?>    
                    <div class="contact-details">
                       <?php if($UserData['User']['type'] == '2') { ?> 
                       <h4>About Me</h4>    
                       <?php } else {?>  
                       <h4>About Facility</h4>
                       <?php } ?>
                        <div class="details-info">
                        	<span class="labelinfo2"><?php echo ucfirst($UserData['User']['hear_about_us']); ?></span>
                        </div>
                    <div class="clearfix"></div>   
                    </div>
                    <?php } ?>
                    <div class="contact-details">
                    <?php if($UserData['User']['type'] == '2') { ?> 
                       <h4>My Work Experience</h4>   
                       <?php } else {?>  
                       <h4>Facility Details</h4>
                       <?php } ?>   
                         <div class="clearfix"></div>  
                          <?php 
                      $speciality_list = $this->requestAction('users/get_speciality_list/'.$UserData['User']['id']);
                      if($speciality_list!='')
                      {
                    ?>                        
                    
                      <!-- <h4>Speciality Groups</h4> -->
                        <div class="work-groups"><span class="labelinfo1">Speciality Groups:</span></div>
					<div class="work-list">		
                      <ul class="skill-list">
                      <?php echo $speciality_list;?>
					  <div class="clearfix"></div>
                      </ul>
					 </div> 
                  
                    <?php } ?> 
                    <div class="clearfix"></div>  
                    <?php 
                      $credential_list = $this->requestAction('users/get_credential_list/'.$UserData['User']['id']);
                      if($credential_list!='')
                      {
                    ?>                        
                    
                      <!-- <h4>Credentials</h4> -->
                       <div class="work-groups"><span class="labelinfo1">Credentials:</span></div>
                      
					  <div class="work-list">
                      <ul class="skill-list">
                      <?php echo $credential_list;?>
					  <div class="clearfix"></div>
                      </ul>
                  </div>
                    <?php } ?> 
                     <?php 
                      $class_list = $this->requestAction('users/get_class_list/'.$UserData['User']['id']);
                      if($class_list!='')
                      {
                    ?>                        
                    
                      <!-- <h4>Credentials</h4> -->
                       <div class="work-groups"><span class="labelinfo1">Class Type:</span></div>
                      
            <div class="work-list">
                      <ul class="skill-list">
                      <?php echo $class_list;?>
            <div class="clearfix"></div>
                      </ul>
                  </div>
                    <?php } ?> 
                       <div class="clearfix"></div>  

                         <?php if(count($new_class_list)>0){?>
                    <div class="contact-details">
                        <h4>Class List</h4>
						<div class="class-table">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tbody><tr class="first-row">
                                    <td width="45%">Class Details</td>                                    
                                    <td>Instructor/Facility</td>                                                                    
                                    <td>Price</td>                                    
                                   
                                   
                                  </tr>
                       <?php  foreach ($new_class_list as $list) {?>          
								     <tr>
										<td>
										<span class="class-des"><strong>Class Name:</strong> <span><a href="<?php echo $this->webroot.'search/class_details/'.$list['Class']['id'];?>"><?php echo ucfirst($list['Class']['name']);?></a></span> </span>
										<span class="class-des"><strong>Day:</strong> <span><?php echo ucfirst($list['Class']['class_day']);?></span> </span>
										<span class="class-des"><strong>Time:</strong> <span><?php echo date('h:i A', strtotime($list['Class']['start_time'])). ' - '.date('h:i A', strtotime($list['Class']['end_time']));?></span> </span>
										</td>       

                                         <?php if($list['User']['type'] == '3') { ?>
                                         <td><a href="<?php echo $this->webroot.'users/profile/'.$list['User']['id'];?>"><?php echo ucfirst($list['User']['facility_name']);?></a></td>
                                         <?php } else { ?>
                                          <td><a href="<?php echo $this->webroot.'users/profile/'.$list['User']['id'];?>"><?php echo ucfirst($list['User']['first_name'].' '.$list['User']['last_name']);?></a></td>
                                          <?php } ?> 
                                         <?php if(!empty($list['Class']['price'])) {?>
                                        <td> $<?php echo ucfirst($list['Class']['price']); ?></td>
                                        <?php } else { ?>
                                         <td>-</td>
                                        <?php }?>         
									</tr>
                  <?php } ?>
									  </tbody></table>
						</div>		  
            </div>
            <?php } ?> 
						<div class="clearfix"></div>
                        					                 
					<div class="contact-details">
						<h4>Reviews</h4>
                          <form action="<?php echo $this->webroot;?>users/profile" method="post" id="profile_review_form" name="profile_review_form">
                          <input type="hidden" value="<?php echo $profile_user_id;?>" class="profileform-control" id="profile_user_id" name="profile_user_id" />
						<div class="edit-info">
						<h3 class="givereview-heading">Give Review</h3>
									<div class="clearfix"></div>
						<div class="row">
							<div class="col-sm-12 fields-margin">
								<label class="profilelabel-review">Enter a title for your review *</label>
								<div class="profilefield">
									<input type="text" value="" class="profileform-control" id="title" name="title" />
                                    <div class="clearfix"></div>
								</div>
							</div>
							
							<div class="clearfix"></div>
							
							<div class="col-sm-12">
								<label class="profilelabel-review">Enter your review *</label>
								<div class="profilefield">
								 <textarea name="review" class="profileform-control msg"> </textarea>
                                 <div class="clearfix"></div>
								</div>
							</div>
							
							<div class="col-sm-12">
								<label class="profilelabel-review">Would You Recommend this (Instructor/Facility)? *</label>
								<div class="profilefield">
                                <span><input name="recommend" type="radio" value="1"> <font>Yes</font></span>  <input name="recommend" type="radio" checked value="0"> No
								</div>
							</div>
							<div class="clearfix"></div>
							<br>
              <div class="col-sm-12">
                  <label class="profilelabel-review">Enter your review *</label>
                  <div class="profilefield">
                  <input style="" id="example-inline" type="number" min="1" max="5" step="1">
                  <input type="hidden" id="rating_value_hidden" name="rating_value" />
                  </div>
              </div>
							<div class="clearfix"></div>
							
							<div class="col-sm-12">											
								<div class="profilefield11">
									<input type="submit" value="Submit" class="savebutton"/>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
                    </form>
                   
                     
						<ul class="commentlist review-list">
                         <?php if(count($AllReview)>0){
                          foreach ($AllReview as $list) {?>
							<li>
								<div class="commentlist-left">
									<div class="comment-img">
									<?php if(!empty($list['User']['image'])){?>
                                     <img src="<?php echo $this->webroot.'profile_images/thumb/'.$list['User']['image'];?>" alt=""/> <?php } else {?><img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> <?php } ?>           										
									</div>											
									
								</div>
								  <div class="commentlist-right">
                                      <div class="comment_author">
                                      <?php if($list['User']['type'] == '3') { ?>
                                       <a href="#"><?php echo (stripslashes(ucfirst($list['User']['facility_name']))); ?></a> 
                                      <?php  } else {  ?>
                                     <a href="#"><?php echo (stripslashes(ucfirst($list['User']['first_name'])).' '.(ucfirst($list['User']['last_name'])))?></a>  
                                       <?php } ?>
                                     <span><?php echo date('F j, Y')?> at <?php echo date('g:i a',strtotime($list['UserReview']['created']));?></span>
                                       

                                   <?php 
                                   if($list['UserReview']['recommend'] == '1')
                                   {
                                   ?>
                                    <i class="recommended-icon"></i>
                                    <?php } ?>
                                    </div>
                                    <h4><?php echo ucfirst($list['UserReview']['title']);?></h4>
                                    <p><?php echo ucfirst($list['UserReview']['review']);?></p>    
                                    <div class="clearfix"></div>
                                    <div class="star-rating">
                                <?php $rates = $list['UserReview']['rating'];
                                if (count($rates)>0){
                            for($i=$rates; $i>=1 ;$i--){ ?>
                                    <i class="fa fa-star"></i>
                                    <?php } } ?>
                                    </div>
                                    
							<div class="clearfix"></div>	
								
							</li>
                            <?php }  } ?>
									</ul>
                                                        
					</div>
                    <?php if(count($article_list)>0){?>
                    <div class="contact-details">
                        <h4>Articles</h4>
                         <ul class="commentlist review-list">
     
                             
                         <?php foreach ($article_list as $list) { ?>
                            <li>
                                <div class="commentlist-left">
                                    <div class="comment-img">
                                    <?php if(!empty($list['User']['image'])){?>
                                     <img src="<?php echo $this->webroot.'profile_images/thumb/'.$list['User']['image'];?>" alt=""/> <?php } else {?><img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> <?php } ?> 
                                     </div>                                            
                                    
                                </div>
                                <div class="commentlist-right">
                                    <div class="comment_author"> <?php if($list['User']['type'] == '3') { ?>
                                       <a href="#"><?php echo (stripslashes(ucfirst($list['User']['facility_name']))); ?></a> 
                                      <?php  } else {  ?>
                                     <a href="#"><?php echo (stripslashes(ucfirst($list['User']['first_name'])).' '.(ucfirst($list['User']['last_name'])))?></a>  
                                       <?php } ?>
                                         <span><?php echo date('F j, Y')?> at <?php echo date('g:i a',strtotime($list['Article']['created']));?></span>
                                          
                                </div>
                                <h4><?php echo ucfirst($list['Article']['article_title']);?></h4>
                                    <p><?php echo ucfirst($list['Article']['description']);?></p>    
                                    <div class="clearfix"></div>
                                    <?php if ($list['Article']['youtube_link'] != ' ') { ?>
                                    <p><a href="<?php echo ($list['Article']['youtube_link']);?>" target='_blank'><?php echo ($list['Article']['youtube_link']);?></a></p>
                                 <?php } ?>   
                            <div class="clearfix"></div>                                    
                            </li>
                               <?php }  ?>                               
                                    </ul>
                    </div>    
                    <?php } ?>
                	
                </div>
            </div>
        <div class="clearfix"></div>
        </div>
    </section>
			
	
	<div class="clearfix"></div>


<script>
	$(document).ready(function() {
  //Set the carousel options
  $('#quote-carousel').carousel({
    pause: true,
    interval: 5000,
  });
});
</script>

  <!-- JS Custom -->
   <script src="<?php echo $this->webroot?>js/prettyCheckable.js"></script>     
   <script src="<?php echo $this->webroot?>js/main.js"></script>
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

<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery-filestyle.min.js"></script>
<script>
            $(":file").filestyle({input: false});
</script>

 <script
src="http://maps.googleapis.com/maps/api/js">
</script>

 <script>
var myCenter=new google.maps.LatLng(<?php echo $UserData['City']['latitude'] ?>,<?php echo $UserData['City']['longitude'] ?>);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:9,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,
  });

marker.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>  