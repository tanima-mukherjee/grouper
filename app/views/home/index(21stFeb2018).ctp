<!-- gdg -->


<script>
jQuery(document).ready( function() {
      var validator = jQuery("#signup_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
        first_name :{
        required: true
         },
        last_name:{
        required: true
         },         
        username:{
        required: true
         },
         state_id:{
        required: true
         },
         index_city_id:{
        required: true
         },
        email: {
            required: true,
            email: true
         },
        password: {
            required: true,
            minlength: 6
        }
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
       messages: {
      first_name : {        
        required:" Provide your first name"
      }, 
      last_name:{
        required:" Provide your last name"
      }, 

      username:{
        required:" Provide your username"
      }, 
      state_id:{
        required:" Select a state"
      }, 
      index_city_id:{
        required:" Select a city"
      }, 
      
      email: {
        required: "Please enter your email address",
        email:"Please a correct email address"
            },
       password:{
        required: "Please enter your password",
        minlength: "Password must have the following:<br> More than 6 character long"
      }
      
      }
   });

     });
 </script> 
 
  <script>
    $(":file").filestyle({badge: false});
  </script>

    <script>
  function showMyCatImage(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil1");
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
  
   function SignupCityList(state_id){
      //alert(state_id);
      if(state_id != ''){
         jQuery.ajax({
            type: "GET",
            url: WEBROOT+"home/show_index_city",
            data: {state_id:state_id},
            success: function(msg){
            jQuery("#index_city_id").html(msg);
           
            
            }
        });
      }
    }
</script>
 <style>
   .error{
    color: #f00;
   }
   .fancybox-type-iframe .fancybox-inner{ height:450px!important;}
 </style>
 
<!-- Modal -->
<div id="side_nav" class="modal right fade" role="dialog">
  <div class="modal-dialog slidenav-modal">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"></div>
      <div class="modal-body">
      <ul class="slidenav">
        <li><a href="#"><i class="fa fgroup"></i>My groups</a></li>
        <li><a href="#"><i class="fa fgroup"></i>My friends</a></li>
        <li><a href="#"><i class="fa fprofile"></i>Profile</a></li>
        <li><a href="#"><i class="fa ftestimonials"></i>Testimonials</a></li>
      </ul>
      </div>
    </div>

  </div>
</div>

<!-- Sign up Modal -->
  <div class="modal fade" id="signupModal" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->
      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">sign up</h4>
    </div>
       
    <div class="modal-body group-modal-body">
    <div class="signup-left">
    <form action="<?php echo $this->webroot?>home/signup" method="post" id="signup_form" name="sign_form" enctype="multipart/form-data">
	<div class="popup-userimg">
	  <!-- <div class="pop-createg-round"><img src="<?php echo $this->webroot?>images/author-img01.jpg"  alt=""></div> -->
	  <div class="popup-upload-img">
	  <div class="popup-edit-uesrimg">
						 
							<img id="thumbnil5" class="no-profile-img" src="<?php echo $this->webroot?>images/no_profile_img55.jpg" >
		</div>

	   <!--  <div class="cmicon"><img src="<?php echo $this->webroot?>images/cam-icon.png" alt=""></div> -->
	   <span class="btn-bs-file upload-browse-btn">Browse</span>
		<input onChange="showMyImage(this)" name="upload_image" id="upload_image" data-badge="false" type="file"/>
    <div class="clearfix"></div>

		</div>
    <p class="upload-img-info">please upload an image of min 160 px X 120 px</p>
		<div class="clearfix"></div>
		<label for="upload_image" generated="true" style="display:none;" class="error">Please upload  image</label>
	  </div>
	  <div class="create-group-field">
      <div class="form-group">
        <label class="label-field">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name">   
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name">  
         <div class="clearfix"></div>                   
      </div>    
      <div class="form-group">
        <label class="label-field">Username</label>
        <input type="text" class="form-control" id="username" name="username">   
         <div class="clearfix"></div>                  
      </div>
      <div class="form-group">
        <label class="label-field">Email</label>
        <input type="text" class="form-control" id="email" name="email" >  
         <div class="clearfix"></div>                   
      </div>      
      <div class="form-group">
        <label class="label-field">Password</label>
        <input type="password" class="form-control" id="password" name="password"> 
         <div class="clearfix"></div>                    
      </div>
	  
	  <div class="form-group">
        <label class="label-field">Select State</label>
		<select class="form-control" name="state_id" id="state_id" onChange="SignupCityList(this.value);">
			 <option value="">Select State</option>
			  <?php 
					if(isset($ArState)){
					foreach($ArState as $State){	?>
					 <option value="<?php echo $State['State']['id'];?>" ><?php echo $State['State']['name'];?></option>
		   <?php } ?>
			<?php } ?>
		 </select>
         <div class="clearfix"></div>                    
      </div>

      <div class="form-group">
        <label class="label-field">Select City</label>      
        <select class="form-control" name="index_city_id" id="index_city_id" >
             <option value="">Select City</option>
        </select>
         <div class="clearfix"></div>                    
      </div>
      
      <button type="submit" class="btn signupbtn">Sign Up</button>
	  </div>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>
    <!-- Sign up modal end -->
<!-- login start -->
 

<!-- login end -->

 <div class="search-part">
	<div class="container">
  <?php if(!$this->Session->check('userData'))
{ ?>
		<div class="search-inner">
			<input type="text" placeholder="Your email address" id="iemail" name="email" >
			<button data-toggle="modal" data-target="#signupModal" type="submit" class="signup-btn modalLink">Sign up</button>
		</div>
  <?php } else {?> 
   <div class="login-text-info">Hi <?php echo $loggedinuser['User']['fname'].' '.$loggedinuser['User']['lname']?>, you are now logged in to the <?php echo $CityName; ?>, <?php echo $StateName; ?> Community!  <a href="javascript:void(0)" data-toggle="modal" data-target="#state" class="select-state-btn">(Click Here to Change City/State)</a></div> 
   <?php } ?>
	</div>
</div>


<script type="text/javascript">
     jQuery(document).ready(function($) {
		$(document).on("click", ".modalLink", function () {
			var reg_email = jQuery("#iemail").val();
			$("#signup_form #email").val(reg_email);
		});
	});
</script>
  
  <div class="">
    <div class="container">
      <div class="group-list">
        <ul>
          <li>Create Groups of any size for Document Sharing / Messaging / Scheduling
Events</li>
          <li>Connect to Local Businesses Offering Great Discounts and Coupons</li>
          <li>Discover Local Events Happening in Your Community</li>
        </ul>
      </div>
      
      <div class="row">
        <div class="col-md-3"> 
          <div class="community-box">
		  <?php if(isset($selected_state_id) && $selected_state_id>0 && isset($selected_city_id) && $selected_city_id>0){ ?>
           <a href="<?php echo $this->webroot?>home/featured_group">
		   <?php
		   }
		   else{
		  ?>
		  		<a href="javascript:void(0)" data-toggle="modal" data-target="#state" class="select-state">
		  <?php } ?>
            <div class="box-position">
              <div class="box-icon"></div>
              <div class="icon-position">
                <i class="fa groups-icon"></i>
              </div>
            </div>
            <div class="clearfix"></div>
            </a>
            <h4><?php echo $contents[0]['StaticContent']['title'];?></h4>
            <p><?php echo $contents[0]['StaticContent']['content'];?></p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="community-box">
            <?php if(isset($selected_state_id) && $selected_state_id>0 && isset($selected_city_id) && $selected_city_id>0){ ?>
          <!--<a href="<?php echo $this->webroot?>home/featured_group">-->
            <a href="<?php echo $this->webroot?>home/community_event_calender">
       <?php
       }
       else{
      ?>
          <a href="javascript:void(0)" data-toggle="modal" data-target="#state" class="select-state">
      <?php } ?>
          
            <div class="box-position">
              <div class="box-icon"></div>
              <div class="icon-position">
                <i class="fa calender-icon"></i>
              </div>
            </div>
            <div class="clearfix"></div>
           <h4><?php echo $contents[1]['StaticContent']['title'];?></h4>
            <p><?php echo $contents[1]['StaticContent']['content'];?></p>
           </a>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="community-box">
           <?php if(isset($selected_state_id) && $selected_state_id>0 && isset($selected_city_id) && $selected_city_id>0){ ?>
         <a href="<?php echo $this->webroot?>home/business_event_calender">
       <?php
       }
       else{
      ?>
          <a href="javascript:void(0)" data-toggle="modal" data-target="#state" class="select-state">
      <?php } ?>
          
            <div class="box-position">
              <div class="box-icon"></div>
              <div class="icon-position">
                <i class="fa business-icon"></i>
              </div>
            </div>
            <div class="clearfix"></div>
            <h4><?php echo $contents[2]['StaticContent']['title'];?></h4>
            <p><?php echo $contents[2]['StaticContent']['content'];?></p>
            </a>
          </div>
        </div>
        
        <div class="col-md-3">
          <?php if ($this->Session->read('userData')) { ?>
          <a class="outerbox" href= "<?php echo $this->webroot; ?>group/invite_to_friend"  >
          
          <?php } else { ?>
           <a data-toggle="modal" data-target="#loginModal" style="cursor:pointer" > 
          <?php } ?>
          <div class="community-box">          
            <div class="box-position">
              <div class="box-icon"></div>
              <div class="icon-position">
                <i class="fa make-friend-icon"></i>
              </div>
            </div>
           
            <div class="clearfix"></div>
            <h4><?php echo $contents[3]['StaticContent']['title'];?></h4>
            <p><?php echo $contents[3]['StaticContent']['content'];?></p>
          </div>
           </a>
        </div>
        
      </div>
      
    </div>
  </div>   
  
  <div class="features-sec">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
         <div class="community-img">
			<img src="<?php echo $this->webroot?>images/community-img1.jpg" alt="" />
		</div> 
        </div>
        <div class="col-md-6">
          <div class="features-info">  
			<h2><b>THIS</b> is how Communities Communicate!</h2>
            <p>
                Your life isn't about just one Group.  Your life is about
				ALL the Groups you interact with and how those Groups
				improve the quality of your life.  At GrouperUSA our
				mission is to bring "good" back to our Communities by
				providing a fun, useful, and free communication tool to
				help people reconnect with each other, local events, and
				the local businesses that enable our communities to thrive.
				Join us for FREE and Start a Group.  <br> It really is Fun! 
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  <div class="manage-group">
    <div class="container">
      <div class="row">
        <div class="col-md-6 pull-right">
			<div class="community-img">
				<img src="<?php echo $this->webroot?>images/community-img2.jpg" alt="" />
		    </div>
        </div>
        <div class="col-md-6">
          <div class="group-info">
            <h2>Manage Your Group and Leagues On the Go</h2>
            <h4>Free download: iPhone, iPad and Android.</h4>
            <ul class="">
            <li>Push Group Message for Rain Outs or last minute changes</li>
            <li>Groups can Share Documents, Photos, and Videos</li>
            <li>Schedule Private Events for just your Group OR Public Events for the Community to see</li>
            <li>Receive Automated Event Reminder Notifications</li>
            <li>Message Individual Group Members or Entire Group</li>
            <li>Automated Map Directions to Each Event</li>
			<li>Announce Registration Events to the Entire Community</li>
            </ul>
            <!--<a href="https://play.google.com/store/apps/details?id=com.ogma.grouperusa&hl=en" class="download-btn">Download app</a>-->
			
			<div class="app-button">
				<a href="https://play.google.com/store/apps/details?id=com.ogma.grouperusa&hl=en" target="_blank" ><img src="<?php echo $this->webroot?>images/google-playbutton.png" alt="" /></a>
				<a href="https://itunes.apple.com/us/app/grouperusa/id1299757057?ls=1&mt=8" target="_blank"><img src="<?php echo $this->webroot?>images/app-storebutton.png" alt="" /></a>
			</div>
          </div>
        </div>
      </div>
    <div class="clearfix"></div>
    </div>
  </div>
  
  <!-- <div class="testimonials">
    <div class="container">
      <h2>Our Testimonials</h2>
      <div class="testimonials-item">
        <div id="myCarousel" class="carousel slide vertical">
          <!-- Indicators -->
    <!-- <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
            <li data-target="#myCarousel" data-slide-to="4"></li>
          </ol>
           <div class="carousel-inner">
             
              <?php   $count = 0; ?>
              <?php foreach ($testimonial_list as $list) { ?> 
              <?php  $count++;
            
            if($count == 1) { ?>
            <div class="item active">
            <?php } else { ?>
              <div class="item">
              <?php } ?>
              <div class="author-img">
                <?php if(!empty($list['User']['image'])) { ?>
            
            <img src="<?php echo $this->webroot.'user_images/'.$list['User']['image'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> <?php } ?>
              </div>
              <div class="author-des">
                <span class="author-name"><?php echo ucfirst($list['User']['fname'].' '.$list['User']['lname']); ?></span>
                <span class="author-position">Posted on :<?php echo date("jS M Y",strtotime($list['Testimonial']['created'])); ?> </span>
                <p><?php echo stripcslashes($list['Testimonial']['desc']); ?></p>
              </div>
            </div>
             <?php } ?>  
        
          </div>  
           <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
            <i class="fa left-arrow"></i>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
            <i class="fa right-arrow"></i>
            </a>
        </div>
      <div class="clearfix"></div>  
      </div>
    </div>
  </div>  -->


  <script>
  function showMyImage(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil5");
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
