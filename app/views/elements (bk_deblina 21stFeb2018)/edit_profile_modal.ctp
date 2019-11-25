 <script>
var WEBROOT = '<?php echo $this->webroot;?>';
  function AllCity(state_id){
     //alert(state_id);
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

   $(document).ready(function() {      
    var validator = $("#edit_profile_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         fname: "required", 
		 lname: "required", 
         email: {
            required: true,
            email: true
          },
          state_id:{
          required: true
         },
         city_id:{
        required: true
         }
         },
        
      errorPlacement: function(label, element) {
            // position error label after generated textarea
            
               label.insertAfter(element.next());
            
         },
      messages: {
	  	 fname : "Please enter your first name",
         lname : "Please enter your last name",
          email: {
            required: "Please enter your email address",
            email:"Please enter a correct email address",
                 },
          state_id:{
        required:" Select a state"
      }, 
      city_id:{
        required:" Select a city"
      }
         
      }
   
  });

  // check email
  var WEBROOT = '<?php echo $this->webroot;?>';
    jQuery('#email').blur(function(){
    //alert(current_pwd);exit();
    var email = jQuery('#email').val();
    
   if(email!=''){
      jQuery.ajax({
          type: "GET",
          url: WEBROOT+"home/check_email",
          data: {email: email},
          success: function(msg){ 
          //alert(msg);
          if(msg == '0'){
           // jQuery('#img_id').hide();
            jQuery('#msg_err').show();
            jQuery('#msg_err').addClass('error');
            jQuery('#msg_err').html('Sorry!! You have already used this mail id in some other account.');
            jQuery("#submit_but").prop( "disabled", true );
          }else{
            jQuery('#msg_err').hide();
           // jQuery('#img_id').show();
            jQuery( "#submit_but" ).prop( "disabled", false );
          }
         }
      });
    }else{
      jQuery('#msg_err').addClass('error');
      jQuery('#msg_err').html('Please enter your email address.');
      jQuery( "#submit_but" ).css('color','red');
      jQuery( "#submit_but" ).prop( "disabled", true );
      return false;
    }
  });
 });
</script>


<style>
  .error{
    font-weight: 700;
  }

</style>

 <div class="modal fade" id="editProfileModal" name="editProfileModal" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->
      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title"> Edit Profile </h4>
    </div>
       
    <div class="modal-body group-modal-body">
    <div class="signup-left">
     <?php if ($this->Session->read('userData')) { ?>
       <?php 
	   	$EditUserProfileDetail = $this->requestAction('home/user_detail');
		$user_state_id = $EditUserProfileDetail['User']['state_id'];
		$state_list = $this->requestAction('home/fetch_states');
		$city_list = $this->requestAction('home/fetch_cities/'.$user_state_id);
			
	   ?>
	<?php } ?> 
 <form action="<?php echo $this->webroot?>home/update_profile" method="post" id="edit_profile_form" name="edit_profile_form" enctype="multipart/form-data">

       <div class="popup-userimg">
    <div class="popup-upload-img">

    <div class="popup-edit-uesrimg">


          
    <?php if($EditUserProfileDetail['User']['image']!=''){?>
                        <img id="thumbnil22" src="<?php echo $this->webroot;?>user_images/<?php echo $EditUserProfileDetail['User']['image'];?>" alt="">
                        
                        <?php }else{ ?>
                        <img id="thumbnil22" src="<?php echo $this->webroot?>images/no_profile_img55.jpg" class="no-profile-img" />
                          <?php } ?>
                     
                        
    </div>

   <span class="btn-bs-file upload-browse-btn">Browse</span>
   
    <input onChange="showMyImage5(this)" name="upload_image" id="upload_image" value="<?php echo $EditUserProfileDetail['User']['image'];?>" data-badge="false" type="file"/>
  <div class="clearfix"></div>
    </div>    
	 <p class="upload-img-info">please upload an image of min 160 px X 120 px</p>

    <div class="clearfix"></div>
    <label for="upload_image" generated="true" style="display:none;" class="error">Please upload  image</label>
    </div>
	<div class="create-group-field">
      <div class="form-group">
        <label class="label-field">First Name</label>
        <input type="text" class="form-control" id="firstname" name="fname" value="<?php echo ucfirst($EditUserProfileDetail['User']['fname'])?>"> 
        <div class="clearfix"></div>           
		             
      </div>
	  
	     
       <div class="form-group">
        <label class="label-field">Last Name</label>
        <input type="text" class="form-control" id="lastname" name="lname" value="<?php echo ucfirst($EditUserProfileDetail['User']['lname'])?>"> 
        <div class="clearfix"></div>                    
      </div>
       <div class="form-group">
        <label class="label-field">Email</label>
        <input type="text" class="form-control" id="email" name="email" value="<?php echo ($EditUserProfileDetail['User']['email'])?>"> 
        <div id="msg_err" class="clearfix"></div>                  
      </div>

      <!--  <div class="form-group">
        <label class="label-field">Select Interval Time</label>
        
    <select class="form-control" name="interval_time" id="interval_time" >
         <option value="">Select Time</option>
         <option value="1" <?php if(($EditUserProfileDetail['User']['prior_notification_time'])=='1') { ?> selected='selected' <?php } ?>>1 hr</option>
         <option value="2" <?php if(($EditUserProfileDetail['User']['prior_notification_time'])=='2') { ?> selected='selected' <?php } ?>>2 hr</option>
         <option value="3" <?php if(($EditUserProfileDetail['User']['prior_notification_time'])=='3') { ?> selected='selected' <?php } ?>>3 hr</option>
          
     </select>
         <div class="clearfix"></div>                    
      </div> -->

          <div class="form-group">
        <label class="label-field">Select State</label>
        
    <select class="form-control" name="state_id" id="state_id" onChange="AllCity(this.value);">
         <option value="">Select State</option>
          <?php 
                
              foreach($state_list as $State){?>
                 <option value="<?php echo $State['State']['id'];?>" <?php if(($EditUserProfileDetail['User']['state_id'])==($State['State']['id'])) { ?> selected='selected' <?php } ?> ><?php echo $State['State']['name'];?></option>
       <?php } ?>
       
     </select>
         <div class="clearfix"></div>                    
      </div>

      <div class="form-group">
        <label class="label-field">Select City</label>
        
        <select class="form-control" name="city_id" id="city_id" >
             <option value="">Select City</option>
               <?php 
               
                foreach($city_list as $City){?>
              <option value="<?php echo $City['City']['id'];?>" <?php if($City['City']['id'] == $EditUserProfileDetail['User']['city_id']){ echo 'selected'; } ?> ><?php echo $City['City']['name'];?></option>
              <?php } 
            
          ?>
        </select>
         <div class="clearfix"></div>                    
      </div>
     
      <button type="submit" id="submit_but" name="submit_but" class="btn signupbtn">Update Profile</button>
	  </div>
      </form>
      
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>

        <script>
  function showMyImage5(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil22");
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