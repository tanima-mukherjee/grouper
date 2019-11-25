  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grouper</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet"> 
  <!-- slider css -->  
  <link href="<?php echo $this->webroot?>css/landing/owl.theme.css" rel="stylesheet">
  <link href="<?php echo $this->webroot?>css/landing/owl.carousel.css" rel="stylesheet">  
   <!-- custom css -->  
  <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet">  
  
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
 <script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  
  </head>
  <style>
.message_divred_green{
  font-size:16px;
  color: #fff;
  text-align:center;
  position:absolute;
  top:85px;
  left:0px;
  background:#1f1c7a;
  /*border:solid 2px #fff;*/
  border-radius:3px;
  text-align:center;
  padding:6px 0px;
  -webkit-box-shadow: 3px 3px 4px #636363;
-moz-box-shadow: 3px 3px 4px #636363;
box-shadow: 3px 3px 4px #636363; width:100%; z-index:9999;
}

.message_divred_red{
    font-size:16px;
    color: #fff;
    text-align:center;
    position:absolute;
    top:85px;
    left:0px;
    background:#A70E13;
    /*border:solid 2px #fff;*/
    border-radius:3px;
    text-align:center;
    padding:6px 0px;
    -webkit-box-shadow: 3px 3px 4px #636363;
-moz-box-shadow: 3px 3px 4px #636363;
box-shadow: 3px 3px 4px #636363; width:100%; z-index:9999;
}
#token-input-location{ background-color: inherit!important;}
.searchbox ul.token-input-list-facebook{ background: none!important; box-shadow: none!important; border:none!important; display: inline-block;}
.searchbox ul.token-input-list-facebook li{ float: none; display: inline-block;}
#token-input-demo-input-facebook-theme{ background: none!important;}
div.token-input-dropdown-facebook{ width: 31%!important;}
</style>
<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function notification_list(){
 //  alert(user_id);
     
       jQuery.ajax({

          type: "GET",
          url: WEBROOT+"home/notification_list",
          success: function(response){
          //alert(response);
           jQuery("#noti_list").html(response);
          }
      });
       
   
  }
</script>
<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function accept_group_request(notification_id){
  //  alert(notification_id);
    
    if(notification_id != ''){
      
       jQuery.ajax({

          type: "POST",
          url: WEBROOT+"group/accept_group_request",
          data: {notification_id:notification_id},
          
          success: function(response){
          if(response == '1'){
            jQuery("#noti_list").show();
            jQuery("#group_accept_reject").remove();
            //
            
             }
          }
      });
    }
    else{
      alert('Notification id not found');
    }
  }
</script>

<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function reject_group_request(notification_id){
  //  alert(notification_id);
    
    if(notification_id != ''){
      
       jQuery.ajax({

          type: "POST",
          url: WEBROOT+"group/reject_group_request",
          data: {notification_id:notification_id},
          
          success: function(response){
          if(response == '1'){
             jQuery("#noti_list").show();
            jQuery("#group_accept_reject").remove();

             }
          }
      });
    }
    else{
      alert('Notification id not found');
    }
  }
</script>


<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function remove_noti(notification_id){
   // alert(notification_id);
    
    if(notification_id != ''){
      
       jQuery.ajax({

          type: "POST",
          url: WEBROOT+"group/remove_notification",
          data: {notification_id:notification_id},
          
          success: function(response){
          if(response == '1'){
            jQuery("#noti_list").show();
            jQuery("#group_accept_reject").remove();
            //jQuery("#noti_list").show();
             }
          }
      });
    }
    else{
      alert('Notification id not found');
    }
  }
</script>
<script>
jQuery( document ).ready( function(){
  setTimeout(fade_out, 3000);
});

function fade_out() {
  jQuery("#message_div").fadeOut();
}

function hide_message_div_manually(){
  jQuery("#message_div").fadeOut();
}
</script>
<body>
  <?php 
  if($this->Session->check("Message.flash")){ 
  //echo $_SESSION['meesage_type'];exit; 
  $message_class = '';
  if($_SESSION['meesage_type'] == 1){
    $message_class = 'message_divred_green';
  }else{
    $message_class = 'message_divred_red';
  }
  //$message_class = 'message_divgreen';
  unset($_SESSION['meesage_type']);
?>  

<?php if ($this->Session->check('Message.flash')) : ?>
<div id="message_div" class="<?php echo $message_class;?>">
<div class="message" id="flashMessage"><?php echo $this->Session->flash(); ?></div>   
</div>
<?php endif; ?> 
  <div class="clr"></div>
<?php } ?>  
 
 <script type="text/javascript">    
    $(window).load(function(){
        $('#infographicModal').modal('show');
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
  <!-- edit Profile start -->
     <div class="modal fade" id="editProfileModal" name="editProfileModal" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->
      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title"> Edit Profile </h4>
    </div>
       
    <div class="modal-body">
    <div class="signup-left">
     <?php if ($this->Session->read('userData')) { ?>
       <?php $EditUserProfileDetail = $this->requestAction('home/user_detail/');?>
<?php } ?> 
 <form action="<?php echo $this->webroot?>home/update_profile" method="post" id="edit_profile_form" name="edit_profile_form" enctype="multipart/form-data">

        <div class="popup-userimg">
    <div class="popup-upload-img">

    <div class="popup-edit-uesrimg">



          
    <?php if($EditUserProfileDetail['User']['image']!=''){?>
                        <img id="thumbnil" src="<?php echo $this->webroot;?>user_images/<?php echo $EditUserProfileDetail['User']['image'];?>" alt="">
                        
                        <?php }else{ ?>
                        <img id="thumbnil" src="<?php echo $this->webroot?>images/no_profile_img55.jpg" class="no-profile-img" />
                          <?php } ?>
                     
                        
    </div>

   <span class="btn-bs-file upload-browse-btn">Browse</span>
   
    <input onChange="showMyImage2(this)" name="upload_image" id="upload_image" value="<?php echo $EditUserProfileDetail['User']['image'];?>" data-badge="false" type="file"/>
  <div class="clearfix"></div>
    </div>
    
  </div>
    <div class="clearfix"></div>
    <label for="upload_image" generated="true" style="display:none;" class="error">Please upload  image</label>
    </div>    
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
     
      <button type="submit" class="btn signupbtn">Update Profile</button>
      </form>
      
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>
    <!-- edit profile end -->

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


<!-- Modal -->
<div id="side_nav_after_login" class="modal right fade" role="dialog">
  <div class="modal-dialog slidenav-modal">
    <!-- Modal content-->
    <div class="modal-content">     
      <div class="modal-body">

     <?php if ($this->Session->read('userData')) { ?>
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
    </div>
    <?php } ?>


      <ul class="slidenav">
        <li><a href="<?php echo $this->webroot.'home/my_group';?>"><i class="fa fgroup"></i>My groups</a></li>
        <li><a href="<?php echo $this->webroot.'home/friends';?>"><i class="fa fgroup"></i>My friends</a></li>
        <li><a href="<?php echo $this->webroot.'home/testimonials';?>"><i class="fa ftestimonials"></i>Testimonials</a></li>
        <li><a href="<?php echo $this->webroot.'event/my_calender';?>"><i class="fa fa-fa-calender"></i>My Calender</a></li>
      </ul>
      </div>
    </div>
  </div>
</div>

 
 

<div class="wrapper">

	<header class="header navbar navbar-default navbar-fixed-top" role="banner">
		<div class="container">
			<div class="header-part">				
					<div class="navbar-header">
                    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          					<span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
        				</button>
                        <a class="navbar-brand nav-logo" href="<?php echo $this->webroot?>">
                          <h1><img class="img-responsive" src="<?php echo $this->webroot?>images/logo.png" alt="logo"></h1>
                        </a>                    
               		</div>
					<div class="navright">
						<ul>
						 <?php if ($this->Session->read('userData')) { ?>
					
							 <li class="dropdown message-noti"><a href="javascript:void(0)">
							 <i class="fa noti-icon" onclick="notification_list()"></i>
							 <!--<span class="noti-count">12</span>-->
							 </a>
							 <div class="dropdown-menu noti-dropdown" id = "noti_list" >
								
							</div>

                


							<?php } ?>
							 <?php if ($this->Session->read('userData')) { ?>
							<li class="login"><a href="<?php echo $this->webroot.'home/logout';?>" class="login-btn" >Logout</a></li>
							<?php } else { ?>
								<li class="login"><a href="javascript:void(0)" class="login-btn" data-toggle="modal" data-target="#loginModal">Login</a></li>
								<?php }  ?>
						</ul>
            <?php if ($this->Session->read('userData')) { ?>
              <?php $UserProfileImage = $this->requestAction('home/user_detail/');?>
						<div class="user-pro-img">
							<a href="javascript:void(0)" data-toggle="modal" data-target="#editProfileModal">
               <?php if($UserProfileImage['User']['image']!=''){?>
                        <img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $UserProfileImage['User']['image'];?>" alt="">
                        
                        <?php }else{ ?>
                        <img id="thumbnil" src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
                          <?php } ?>								
							</a>
						</div>
            <?php } ?>
					</div>
               		<div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right mainnav"> 
						 
                          	 <?php if ($this->Session->read('userData')) { ?>
              <li><a href="<?php echo $this->webroot.'category/category_list';?>">Categories</a></li>
              <?php } else { ?>
              <li><a href="javascript:void(0)">Categories</a></li>
              <?php }  ?>
                          	<li><a href="javascript:void(0)">Search</a></li>                     
                          	<li><a href="javascript:void(0)">Teams</a></li> 
							<li><a href="javascript:void(0)">Contact</a></li> 	
                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#infographicModal">About</a></li> 						
                        </ul>
              		</div>
			</div>
			</div>	
       <?php if ($this->Session->read('userData')) { ?>
			<div class="nav-slide">
				<a href="javascript:void(0)" data-toggle="modal" data-target="#side_nav_after_login">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
			</div>
      <?php } ?>	
		</header>
		
<script>
  function showMyImage2(fileInput) {
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



