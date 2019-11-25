<!-- gdg -->
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>

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
         city_id:{
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
      city_id:{
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
jQuery(document).ready( function() {
      var validator = jQuery("#login_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
        username :{
        required: true
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
      username : {        
        required:" Provide your username"
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
</script>
 <style>
   .error{
    color: #f00;
   }
 </style>
 <script> var WEBROOT = '<?php echo $this->webroot;?>' </script>
 <script>
  function CityList(state_id){
     // alert(state_id);
      var city_default = '<option value="">Select City</option>';
      jQuery("#private_city_id").html(city_default);
      if(state_id != ''){
         jQuery.ajax({
            type: "GET",
            url: WEBROOT+"home/show_city",
            data: {state_id:state_id},
            success: function(msg){
            //alert(msg);
            jQuery("#city_id").html(msg);
            
            }
        });
      }
    }
</script>
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
       
    <div class="modal-body">
    <div class="signup-left">
    <form action="<?php echo $this->webroot?>home/signup" method="post" id="signup_form" name="sign_form" enctype="multipart/form-data">
	<div class="pop-createg">
	  <!-- <div class="pop-createg-round"><img src="<?php echo $this->webroot?>images/author-img01.jpg"  alt=""></div> -->
	  <div class="upload-img">
	  <div class="pop-createg-round">
						 
							<img id="thumbnil" src="<?php echo $this->webroot?>images/no_profile_img55.jpg" >
		</div>

	   <!--  <div class="cmicon"><img src="<?php echo $this->webroot?>images/cam-icon.png" alt=""></div> -->
	   <span class="btn-bs-file browse-btn">
					Browse
		<input onChange="showMyImage(this)" name="upload_image" id="upload_image" data-badge="false" type="file"/>
		</span>
		</div>
		<div class="clearfix"></div>
		<label for="upload_image" generated="true" style="display:none;" class="error">Please upload  image</label>
	  </div>
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
        <!-- <select class="form-control">
			<option value="" selected></option>
			<option value="">Select One</option>
		</select> -->
    <select class="form-control" name="state_id" id="state_id" onChange="CityList(this.value);">
         <option value="">Select State</option>
          <?php 
                if(isset($ArState)){
              foreach($ArState as $State){?>
                 <option value="<?php echo $State['State']['id'];?>" ><?php echo $State['State']['name'];?></option>
       <?php } ?>
        <?php } ?>
     </select>
         <div class="clearfix"></div>                    
      </div>

      <div class="form-group">
        <label class="label-field">Select City</label>
        <!-- <select class="form-control">
      <option value="" selected></option>
      <option value="">Select One</option>
    </select> -->
        <select class="form-control" name="city_id" id="city_id" >
             <option value="">Select City</option>
               <?php 
                if(isset($citylist)){
                foreach($citylist as $City){?>
              <option value="<?php echo $City['City']['id'];?>"><?php echo $City['City']['name'];?></option>
              <?php } 
             }
          ?>
        </select>
         <div class="clearfix"></div>                    
      </div>
      
      <button type="submit" class="btn signupbtn">Sign Up</button>
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
  <div class="modal fade" id="loginModal" name="loginModal" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->
      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">login</h4>
    </div>
       
    <div class="modal-body">
    <div class="signup-left">
 <form action="<?php echo $this->webroot?>home/login" method="post" id="login_form" name="login_form" enctype="multipart/form-data">
      
      <div class="form-group">
        <label class="label-field">Username</label>
        <input type="text" class="form-control" id="username" name="username"> 
        <div class="clearfix"></div>                    
      </div>
      <div class="form-group">
        <label class="label-field">Password</label>
        <input type="password" class="form-control" id="password" name="password">   
        <div class="clearfix"></div>                  
      </div>
      
      <button type="submit" class="btn signupbtn">Login</button>
      </form>
      
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>

<!-- login end -->
<!-- dfgdg -->
<div class="search-part">
	<div class="container">
<!--  <form id="add_form" action="<?php echo $this->request->webroot;?>" method="post"  name= "add_form"> -->

		<div class="search-inner">
			<input type="text" placeholder="Your email address" id="iemail" name="email" >
			<button data-toggle="modal" data-target="#signupModal" type="submit" class="signup-btn modalLink">Sign up</button>
		</div>
   
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
          <li>Join or Create Groups of any size for Free Messaging and Scheduling Events</li>
          <li>Connect to Local Businesses Offering Great Discounts and Coupons</li>
          <li>Discover Local Events Happening in Your Community</li>
        </ul>
      </div>
      
      <div class="row">
        <div class="col-md-3"> 
          <div class="community-box">
            <div class="box-position">
              <div class="box-icon"></div>
              <div class="icon-position">
                <i class="fa groups-icon"></i>
              </div>
            </div>
            <div class="clearfix"></div>
            <h4>Browse groups</h4>
            <p>It is a long established fact that a reader will be distracted by the readable content of a </p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="community-box">
            <div class="box-position">
              <div class="box-icon"></div>
              <div class="icon-position">
                <i class="fa calender-icon"></i>
              </div>
            </div>
            <div class="clearfix"></div>
            <h4>Community calender</h4>
            <p>When looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less </p>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="community-box">
            <div class="box-position">
              <div class="box-icon"></div>
              <div class="icon-position">
                <i class="fa business-icon"></i>
              </div>
            </div>
            <div class="clearfix"></div>
            <h4>Business deals</h4>
            <p>Distribution of letters, as opposed to using 'Content here, content here', making it</p>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="community-box">
            <div class="box-position">
              <div class="box-icon"></div>
              <div class="icon-position">
                <i class="fa make-friend-icon"></i>
              </div>
            </div>
            <div class="clearfix"></div>
            <h4>Make your friend</h4>
            <p>Content here', making it look like readable English. Many desktop publishing packages</p>
          </div>
        </div>
        
      </div>
      
    </div>
  </div>   
  
  <div class="features-sec">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <img class="img-responsive" src="<?php echo $this->webroot?>images/features-img.png" alt="" />
        </div>
        <div class="col-md-6">
          <div class="features-info">
            <h2>Other Features:</h2>
            <ul>
              <li>Receive Automatic Notifications Prior to Event</li>
              <li>Groups can Share Documents, Images, Videos</li>
              <li>No More "Never Ending" Group Text Message Chains</li>
              <li>Request Special Discounts from Businesses You Like</li>
              <li>Complete Control over who can Send You Messages</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  <div class="manage-group">
    <div class="container">
      <div class="row">
        <div class="col-md-5 pull-right">
          <img class="img-responsive" src="<?php echo $this->webroot?>images/group-img.png" alt="" />
        </div>
        <div class="col-md-7">
          <div class="group-info">
            <h2>Manage Your Group and Leagues On the Go</h2>
            <h4>Free download: iPhone, iPad and Android.</h4>
            <ul class="">
              <li>Contrary to popular belief, Lorem Ipsum is not simply random text.</li>
              <li>Piece of classical Latin literature from 45 BC,</li>
              <li>Making it over 2000 years old. Richard McClintock, </li>
              <li>Hampden-Sydney College in Virginia, looked up one of the more</li> 
            </ul>
            <a href="#" class="download-btn">Download app</a>
          </div>
        </div>
      </div>
    <div class="clearfix"></div>
    </div>
  </div>
  
  <div class="testimonials">
    <div class="container">
      <h2>Our Testimonials</h2>
      <div class="testimonials-item">
        <div id="myCarousel" class="carousel slide vertical">
          <!-- Indicators -->
     <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
          </ol>
           <div class="carousel-inner">
            
            <div class="item active">
              <div class="author-img">
                <img src="<?php echo $this->webroot?>images/author-img01.jpg" alt="" />
              </div>
              <div class="author-des">
                <span class="author-name">Sam Robinson</span>
                <span class="author-position">CEO & Founder <font>Megapixel</font></span>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type </p>
              </div>
            </div>
            <div class="item">
              <div class="author-img">
                <img src="<?php echo $this->webroot?>images/author-img01.jpg" alt="" />
              </div>
              <div class="author-des">
                <span class="author-name">Sam Robinson</span>
                <span class="author-position">CEO & Founder <font>Megapixel</font></span>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type </p>
              </div>
            </div>
            <div class="item">
              <div class="author-img">
                <img src="<?php echo $this->webroot?>images/author-img01.jpg" alt="" />
              </div>
              <div class="author-des">
                <span class="author-name">Sam Robinson</span>
                <span class="author-position">CEO & Founder <font>Megapixel</font></span>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type </p>
              </div>
            </div>
            <div class="item">
              <div class="author-img">
                <img src="<?php echo $this->webroot?>images/author-img01.jpg" alt="" />
              </div>
              <div class="author-des">
                <span class="author-name">Sam Robinson</span>
                <span class="author-position">CEO & Founder <font>Megapixel</font></span>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type </p>
              </div>
            </div>
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
  </div> 


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
