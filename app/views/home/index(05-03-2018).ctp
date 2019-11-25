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
          foreach($ArState as $State){  ?>
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





<div class="banner-wrap">
    <div class="container">
      <h2>We've got our eyes on the community!</h2>
      <div class="container">
        <div class="banner-content">
          <img src="images/banner_group_img.png" alt="" />
          <h4>ONE Great Communication Tool For All the Groops In Your Life</h4>
        </div>
      </div>
    
      <div class="banner-overlay-wrap scene">
        <div id="scene">
          <div class="layer" data-depth="0.50">
            <img src="<?php echo $this->webroot?>images/banner_mobile_img.png" alt="" />
          </div>
        </div>
      </div>
      
    <div class="clearfix"></div>
    </div>
  </div>
  

  <div class="community-login">
    <div class="container">
    <?php if($this->Session->check('userData'))
    { ?>
    
        <div class="community-login-inner">     
          <h3>Hi <?php echo $loggedinuser['User']['fname'].' '.$loggedinuser['User']['lname']?>, you are now logged in to the <?php echo $CityName; ?>, <?php echo $StateName; ?> Community!</h3> 
        </div>
        <div class="community-change">
          <a href="javascript:void(0)" data-toggle="modal" data-target="#state">(Click Here to Change City/State)</a>
        </div>
      <div class="clearfix"></div>
      
    <?php }else{ ?>

    <div class="search-inner">
      <input type="text" placeholder="Your email address" id="iemail" name="email" >
      <button data-toggle="modal" data-target="#signupModal" type="submit" class="signup-btn modalLink">Sign up</button>
    </div>

    <?php } ?>
    </div>
  </div>





  
  <div class="home-body-wrap">
    <div class="community-wrap">
      <div class="container">
        <div class="group-community-info">
          <ul>
            <li>Create Groops of any size for Document Sharing / Messaging / Scheduling Events</li>
            <li>Connect to Local Businesses Offering Great Discounts and Coupons</li>
            <li>Discover Local Events Happening in Your Community</li>
          </ul>
        </div>
      <div class="clearfix"></div>
      </div>
    </div>
    
    <div class="how-communicate">
      <div class="container">
        <div class="communicate-img-box">
          <img src="<?php echo $this->webroot;?>images/communicate-img.jpg" alt="" />
        </div>
        <div class="communicate-content">
          <h3><span>THIS</span> is how Communities Communicate!</h3>
           <p>Your life isn't about just one Groop. Your life is about ALL the Groops you interact with and how those Groops improve the quality of your life. At GroopZilla our mission is to bring "good" back to our 
            Communities by providing a fun, useful, and free communication tool 
            to help people reconnect with each other, local events, and the local businesses that enable our communities to thrive. Join us for FREE 
            and Start a Groop.</p>
           <h4>It really is Fun! </h4>
        </div>        
      </div>
    </div>
    
    <div class="manage-group">
      <div class="container">
        <div class="leagues-box">
          <img src="<?php echo $this->webroot;?>images/leagues_img.jpg" alt="" />
        </div>
        <div class="leagues-content">
          <h3>Manage Your Groop and Leagues On the Go</h3>
          <h4>Free download: iPhone, iPad and Android.</h4>
          <ul>
            <li>Push Groop Message for Rain Outs or last minute changes</li>
            <li>Groops can Share Documents, Photos, and Videos</li>
            <li>Schedule Private Events for just your Groop OR Public events for the Community to see</li>
            <li>Receive Automated Event Reminder Notifications</li>
            <li>Message Individual Groop Members or Entire Groop</li>
            <li>Automated Map Directions to Each Event</li>
            <li>Announce Registration Events to the Entire Community</li>
          </ul>
          <div class="app-download">
            <a href="https://itunes.apple.com/us/app/grouperusa/id1299757057?ls=1&mt=8" target="_blank">
              <img src="<?php echo $this->webroot;?>images/app_store_icon.png" alt="" />
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.ogma.grouperusa&hl=en" target="_blank">
              <img src="<?php echo $this->webroot;?>images/play_store_icon.png" alt="" />
            </a>
          </div>
        </div>        
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

  