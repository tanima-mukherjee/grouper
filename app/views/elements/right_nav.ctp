<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<script >
  $(document).ready( function() {
 
      var validator = $("#group_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
         group_name: "required",
         upload_image: "required",
         g_desc: "required",
         //g_purpose:"required"
        
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         group_name : "Please enter groop name",
         upload_image:"Please upload  image",
         g_desc : "Please enter groop description",
         //g_purpose : "Please enter a groop purpose"
    
      }
   });
        var WEBROOT = '<?php echo $this->webroot;?>';
    jQuery('#current_pwd').blur(function(){
    var current_pwd = jQuery('#current_pwd').val();
    
    //alert(current_pwd);exit();
   if(current_pwd!=''){
      jQuery.ajax({
          type: "GET",
          url: WEBROOT+"home/check_password",
          data: {current_pwd: current_pwd},
          success: function(msg){ 
          //alert(msg);
          if(msg == '0'){
           // jQuery('#img_id').hide();
            jQuery('#msg_err').show();
            jQuery('#msg_err').addClass('show_err');
            jQuery('#msg_err').html('Sorry!! This is not your current password.');
            jQuery("#submit_but").prop( "disabled", true );
          }else{
            jQuery('#msg_err').hide();
            //jQuery('#img_id').show();
            jQuery( "#submit_but" ).prop( "disabled", false );
          }
         }
      });
    }else{
      jQuery('#msg_err').addClass('show_err');
      jQuery('#msg_err').html('Please enter your current password.');
      jQuery( "#submit_but" ).css('color','gray');
      jQuery( "#submit_but" ).prop( "disabled", true );
      return false;
    }
  });
    var validator = jQuery("#update_password_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
     
      rules: {

          current_pwd :{
            required: true
          },
         new_pwd: {
            required: true
         },
         con_pwd: {
            equalTo: "#new_pwd"
         }
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
       
        messages: {
          current_pwd: {
            required: "Please enter current password"
         },
                 
         new_pwd: {
            required: "Please enter new password"
         },
         con_pwd: {
            equalTo: "Please enter the same as new password"
         }
      }
   });
});
</script> 
<script >
  $(document).ready( function() {
 
      var validator = $("#business_group_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
         group_name: "required",
         upload_image: "required",
         g_desc: "required",
         //g_purpose:"required",
         category_id:"required",
		 g_url:{
		  url: true
		}
        
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         group_name : "Please enter groop name",
         upload_image:"Please upload  image",
         g_desc : "Please enter groop description",
         //g_purpose : "Please enter a groop purpose",
         category_id:"Please select a category",
		 g_url: {
            url : "Please enter a valid URL"
         }
      }
   });
});
</script> 

<!-- Private Organization form -->
<script >
  $(document).ready( function() {
 
      var validator = $("#private_organization_group_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
         group_name: "required",
         upload_image: "required",
         g_desc: "required",
         //g_purpose:"required",
         category_id:"required",
		 g_url:{
		  url: true
		}
        
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         group_name : "Please enter groop name",
         upload_image:"Please upload  image",
         g_desc : "Please enter groop description",
         //g_purpose : "Please enter a groop purpose",
         category_id:"Please select a category",
     g_url: {
            url : "Please enter a valid URL"
         }
      }
   });
});
</script> 
<!-- Modal -->
 <div class="modal fade" id="creategroup" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->

      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Create Private Groop</h4>
    </div>
       
    <div class="modal-body group-modal-body">
   <form action="<?php echo $this->webroot?>group/add_free_group" method="post" id="group_form" name="group_form" enctype="multipart/form-data">  

  <div class="pop-createg">
  
  <div class="upload-img">
  <div class="pop-createg-round">
                     
                        <img id="thumbnil10" class="no-profile-img" src="<?php echo $this->webroot?>images/no-group-img.jpg" />
  </div>

   
   <span class="btn-bs-file browse-btn">Browse</span>
   <input onChange="showMyImagePrivate(this)" name="upload_image" id="image455" data-badge="false" type="file"/>
  <div class="clearfix"></div>
  <p class="upload-img-info">please upload an image of min 160 px X 120 px</p>
    </div>
    
  </div>
  <div class="clearfix"></div>
    <div class="create-group-field">
      <div class="form-group">
        <label class="label-field">Title</label>
        <input type="text" class="form-control" id="group_name" name="group_name">   
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Description</label>
       <textarea name="g_desc" id="g_desc" class="form-control" cols="" rows=""></textarea>
         <div class="clearfix"></div>                   
      </div> 
	   <!-- <div class="form-group">
		<label class="label-field">Purpose</label>
	   <textarea name="g_purpose" id="g_purpose" class="form-control" cols="" rows=""></textarea>
		 <div class="clearfix"></div>                   
	  </div>  -->

             
            
      <button type="submit" class="btn signupbtn">Create Groop</button>
      
    <div class="clearfix"></div>
    </div>
	
    <div class="clearfix"></div>  
	</form>
    </div>
    </div>    
      </div>
      
    </div>


    <!-- business group pop up start -->
    <div class="modal fade" id="createbusinessgroup" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->

      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Create Business Groop</h4>
    </div>
       
    <div class="modal-body group-modal-body">
   <form action="<?php echo $this->webroot?>group/add_business_category_group" method="post" id="business_group_form" name="business_group_form" enctype="multipart/form-data">  

  <div class="pop-createg">
  <!-- <div class="pop-createg-round"><img src="<?php echo $this->webroot?>images/author-img01.jpg"  alt=""></div> -->
  <div class="upload-img">
  <div class="pop-createg-round">
                     
                        <img id="thumbnil14" class="no-profile-img" src="<?php echo $this->webroot?>images/no-group-img.jpg" />
  </div>

   <!--  <div class="cmicon"><img src="<?php echo $this->webroot?>images/cam-icon.png" alt=""></div> -->
   <span class="btn-bs-file browse-btn">Browse</span>
   <input onChange="showMyImageBusiness(this)" name="upload_image" id="image455" data-badge="false" type="file"/>
  <div class="clearfix"></div>
  <p class="upload-img-info">please upload an image of min 160 px X 120 px</p>
    </div>
    
  </div>
  <div class="clearfix"></div>
    <div class="create-group-field">
      <div class="form-group">
        <label class="label-field">Title</label>
        <input type="text" class="form-control" id="group_name" name="group_name">   
         <div class="clearfix"></div>          
      </div>

       <?php 
	   
	   		$CategoryDetail = $this->requestAction('home/category_list');
			
	   ?>
      <div class="form-group">
   
        <label class="label-field">Category List</label>
        <select class="form-control" name="category_id"  id="category_id">
          <option value="">Select Category</option>
          <?php 
             foreach($CategoryDetail as $cat)
             {?>
                 <option value="<?php echo $cat['Category']['id'];?>"><?php echo $cat['Category']['title'];?>
                 </option>
       <?php } ?>
       
          
        </select> 
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Description</label>
       <textarea name="g_desc" id="g_desc" class="form-control" cols="" rows=""></textarea>
         <div class="clearfix"></div>                   
      </div> 
       <!-- <div class="form-group">
        <label class="label-field">Purpose</label>
       <textarea name="g_purpose" id="g_purpose" class="form-control" cols="" rows=""></textarea>
         <div class="clearfix"></div>                   
      </div> --> 
	  <div class="form-group">
		<label class="label-field">URL</label>
		<input type="text" class="form-control" id="g_url" name="g_url">   
		 <div class="clearfix"></div>          
	  </div>
		<p style="color:#555555">If you would like to provide a link to your website please enter the complete URL to your site above (i.e.   https://www.groopzilla.com).</p>
      <!-- testing purpose phone -->
     <!--  <div class="form-group">
        <label class="label-field">Phone</label>
       <input type="text" id="phn_no1" name="phn_no1" style="width:30px;" maxlength="3" size="3">-
       <input type="text" id="phn_no2" name="phn_no2" style="width:30px;" maxlength="3" size="3">-
       <input type="text" id="phn_no3" name="phn_no3" style="width:50px;" maxlength="4" size="4">
         <div class="clearfix"></div>                   
      </div> -->
        <!-- testing purpose phone -->       
            
      <button type="submit" class="btn signupbtn">Create Groop</button>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>
    <!-- business group pop up ends -->

    <!-- create private organization group pop up start -->
    <div class="modal fade" id="createpublicorganizationgroup" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->

      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Create Public Organization Groop</h4>
    </div>
       
    <div class="modal-body group-modal-body">
   <form action="<?php echo $this->webroot?>group/add_public_organization_category_group" method="post" id="public_organization_group_form" name="public_organization_group_form" enctype="multipart/form-data">  

  <div class="pop-createg">
  <!-- <div class="pop-createg-round"><img src="<?php echo $this->webroot?>images/author-img01.jpg"  alt=""></div> -->
  <div class="upload-img">
  <div class="pop-createg-round">
                     
                        <img id="thumbnil81" class="no-profile-img" src="<?php echo $this->webroot?>images/no-group-img.jpg" />
  </div>

   <!--  <div class="cmicon"><img src="<?php echo $this->webroot?>images/cam-icon.png" alt=""></div> -->
   <span class="btn-bs-file browse-btn">Browse</span>
   <input onChange="showMyImagePrivateOrganization(this)" name="upload_image" id="image455" data-badge="false" type="file"/>
  <div class="clearfix"></div>
  <p class="upload-img-info">please upload an image of min 160 px X 120 px</p>
    </div>
    
  </div>
  <div class="clearfix"></div>
    <div class="create-group-field">
      <div class="form-group">
        <label class="label-field">Title</label>
        <input type="text" class="form-control" id="group_name" name="group_name">   
         <div class="clearfix"></div>          
      </div>

       <?php $CategoryDetail = $this->requestAction('home/category_list_POG'); ?>
      <div class="form-group">
   
        <label class="label-field">Category List</label>
        <select class="form-control" name="category_id"  id="category_id">
          <option value="">Select Category</option>
          <?php 
             foreach($CategoryDetail as $cat)
             {?>
                 <option value="<?php echo $cat['Category']['id'];?>"><?php echo $cat['Category']['title'];?>
                 </option>
       <?php } ?>
       
          
        </select> 
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Description</label>
       <textarea name="g_desc" id="g_desc" class="form-control" cols="" rows=""></textarea>
         <div class="clearfix"></div>                   
      </div> 
       <!-- <div class="form-group">
        <label class="label-field">Purpose</label>
       <textarea name="g_purpose" id="g_purpose" class="form-control" cols="" rows=""></textarea>
         <div class="clearfix"></div>                   
      </div>  -->
    <div class="form-group">
    <label class="label-field">URL</label>
    <input type="text" class="form-control" id="g_url" name="g_url">   
     <div class="clearfix"></div>          
    </div>
    <p style="color:#555555">If you would like to provide a link to your website please enter the complete URL to your site above (i.e.   https://www.grouperusa.com).</p>
     
            
      <button type="submit" class="btn signupbtn">Create Groop</button>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>
    <!-- create organization group pop up ends -->

    <!-- change password modal start -->
    <div class="modal fade" id="changePassword" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->

      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Change Password</h4>
    </div>
       
    <div class="modal-body group-modal-body">
   <form action="<?php echo $this->webroot?>home/change_password" method="post" id="update_password_form" name="update_password_form" enctype="multipart/form-data">  

    <div class="create-group-field">
      <div class="form-group">
        <label class="label-field">Current Password</label>
        <input type="text" class="form-control" id="current_pwd" name="current_pwd">   
        <div class="clearfix"></div>
       <div id="msg_err"></div>        
      </div>

      <div class="form-group">
        <label class="label-field">New Password</label>
        <input type="text" class="form-control" id="new_pwd" name="new_pwd">   
         <div class="clearfix"></div>          
      </div>

      <div class="form-group">
        <label class="label-field">Confirm Password</label>
        <input type="text" class="form-control" id="con_pwd" name="con_pwd">   
         <div class="clearfix"></div>          
      </div>
            
      <button type="submit" id="submit_but" name="submit_but" class="btn signupbtn">Update</button>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>
    <!-- change password ens modal -->





	<div id="error_non_login" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header signup-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">ALERT !!!</h4>
		  </div>
		  <div class="modal-body">
			<p style="font-size:14px; color:#FF0000; font-weight:bold;">Hi Grooper!  Please log in to use this feature or SIGN UP to create a free User Account!</p>
		  </div>
		</div>
	
	  </div>
	</div>


 <?php 

    
    /*if((($this->params['controller'] == 'home') && ($this->params['action'] == 'index'))|| (($this->params['controller'] == 'home') && ($this->params['action'] == 'community_event_calender'))|| (($this->params['controller'] == 'home') && $this->params['action'] == 'business_event_calender'))
    {
      $is_nav = '0';
    }
    else{
      $is_nav = '1';
    } */
	
	if($this->params['controller'] == 'home' && $this->params['action'] == 'index'){
		$is_nav = '0';
	}
	else{
		$is_nav ='1';
	}

   ?>
<div id="side_nav" class="modal left fade" role="dialog">
  <div class="modal-dialog slidenav-modal">
    <!-- Modal content-->
    <div class="modal-content">     
      <div class="modal-body">

     <?php if($this->Session->read('userData')) { ?>
       <?php $UserDetail = $this->requestAction('home/user_detail/');?>

    <div class="user-des">
    <div class="user-img">
    
     <?php if($UserDetail['User']['image'] != '') { ?>
      <img src="<?php echo $this->webroot.'user_images/thumb/'.$UserDetail['User']['image'];?>" alt="" />
     <?php } else { ?>
      <img class="no-profile-img" src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" />
     <?php  } ?>
    </div>
    <div class="user-name">
      <span><?php echo ucfirst($UserDetail['User']['fname'].' '.$UserDetail['User']['lname'])?></span>
    </div>
    <div class="clearfix"></div>
    </div>

    <?php } else { ?>
      <div class="user-des">
    <div class="user-img">
      <img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" />
    </div>
    <div class="user-name">
      <span>Profile Name</span>
    </div>
	<div class="clearfix"></div>
	<?php if ($this->Session->read('selected_state_id') && $this->Session->read('selected_city_id')){ ?>
	  	<a href="javascript:void(0)" data-toggle="modal" data-target="#state" style="color:#fff; padding-top:7px; display:block;"><?php echo $CityName; ?>, <?php echo $StateName; ?></a> 
	  <?php } ?>
    <div class="clearfix"></div>
    </div>
    <?php } ?>


      <ul class="slidenav">
        <li><a href="<?php echo $this->webroot;?>"><i class="fa fa-home"></i><strong>Home</strong></a></li>
        <li><a href="<?php echo $this->webroot.'category/category_list';?>"><i class="fa fa-list"></i><strong>Categories</strong></a></li>
		
		<?php if ($this->Session->read('userData')) { ?>
        <li><a href="<?php echo $this->webroot.'event/my_calender';?>"><i class="fa fa-fa-calender"></i><strong>My Calender</strong></a></li>
        <li><a href="<?php echo $this->webroot.'home/my_group';?>"><i class="fa fgroup"></i><strong>My Groops</strong></a></li>
        <li><a href="<?php echo $this->webroot.'home/friends';?>"><i class="fa fa-users"></i><strong>My Friends</strong></a></li>
		<?php }
		else{ ?>
		<li><a href="javascript:void(0)" data-toggle="modal" data-target="#error_non_login" data-dismiss="modal"><i class="fa fa-fa-calender"></i>My Calender</a></li>
        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#error_non_login" data-dismiss="modal"><i class="fa fgroup"></i>My Groops</a></li>
        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#error_non_login" data-dismiss="modal"><i class="fa fa-users"></i>My Friends</a></li>
		<?php
		}
		?>
		
		 
		<?php if ($this->Session->read('selected_state_id') && $this->Session->read('selected_city_id')){ ?>
        <li><a href="<?php echo $this->webroot.'home/community_event_calender';?>"><i class="fa fa-calendar"></i><strong>Community Calendar</strong></a></li>
        <li><a href="<?php echo $this->webroot.'home/business_event_calender';?>"><i class="fa businessdeal"></i><strong>Business Deals</strong></a></li>
		<?php }
		else{ ?>
		<li><a href="javascript:void(0)" data-toggle="modal" data-target="#state"><i class="fa fa-calendar"></i><strong>Community Calendar</strong></a></li>
        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#state"><i class="fa businessdeal"></i><strong>Business Deals</strong></a></li>
		<?php
		}
		?>
		
		
        <?php if ($this->Session->read('userData')) { ?>
        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#creategroup" data-dismiss="modal"><i class="fa private-group"></i><strong>Create Private Groop</strong> </a></li>
        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#createbusinessgroup" data-dismiss="modal"><i class="fa fgroup"></i><strong>Create Business Groop</strong> </a></li>
		<li><a href="javascript:void(0)" data-toggle="modal" data-target="#createpublicorganizationgroup" data-dismiss="modal"><i class="fa forganisation"></i><strong>Create Public Organization Groop</strong> </a></li>
        <?php } 
		else{ ?>
		<li><a href="javascript:void(0)" data-toggle="modal" data-target="#error_non_login" data-dismiss="modal"><i class="fa private-group"></i>Create Private Groop</a></li>
        <li><a href="javascript:void(0)" data-toggle="modal" data-target="#error_non_login" data-dismiss="modal"><i class="fa fgroup"></i>Create Business Groop</a></li>
		<li><a href="javascript:void(0)" data-toggle="modal" data-target="#error_non_login" data-dismiss="modal"><i class="fa forganisation"></i>Create Public Organization Groop</a></li>
		<?php
		}
		?>
        
        <li><a href="<?php echo $this->webroot.'home/faq';?>"><i class="fa fa-question-circle-o"></i><strong>FAQ</strong></a></li>
        <li><a href="<?php echo $this->webroot.'home/contact_us';?>"><i class="fa fa-phone"></i><strong>Contact</strong></a></li>
		<li><a href="javascript:void(0)" data-toggle="modal" data-target="#infographicModal" data-dismiss="modal"><i class="fa fa-exclamation-circle"></i><strong>About</strong></a></li>
        

        <?php if ($this->Session->read('userData')) { ?>
         <li><a href="javascript:void(0)" data-toggle="modal" data-target="#changePassword" data-dismiss="modal"><i class="fa fa-lock"></i><strong>Change Password</strong> </a></li>
         <?php } 
		 else{ ?>
		 <li><a href="javascript:void(0)" data-toggle="modal" data-target="#error_non_login" data-dismiss="modal"><i class="fa fa-lock"></i>Change Password</a></li>
		 <?php
		 }
		 ?>
       
        <?php if($this->Session->read('userData') && $is_nav=='1') { ?>
          <li><a href="<?php echo $this->webroot.'home/logout';?>" class="logout"><i class="fa flogout"></i> <strong>Logout</strong></a></li>
        <?php } ?>





       

      </ul>
      </div>
    </div>
  </div>
</div>


<div class="owl-carousel owl-theme owl-loaded">
    <div class="owl-stage-outer">
        <div class="owl-stage">
            <div class="owl-item">...</div>
            <div class="owl-item">...</div>
            <div class="owl-item">...</div>
        </div>
    </div>
    <div class="owl-controls">
        <div class="owl-nav">
            <div class="owl-prev">prev</div>
            <div class="owl-next">next</div>
        </div>
        <div class="owl-dots">
            <div class="owl-dot active"><span></span></div>
            <div class="owl-dot"><span></span></div>
            <div class="owl-dot"><span></span></div>
        </div>
    </div>
</div>

   <script>
  function showMyImagePrivate(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil10");
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

 


   <script>
  function showMyImageBusiness(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil14");
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

<script>  
   function showMyImagePrivateOrganization(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil81");
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

 
