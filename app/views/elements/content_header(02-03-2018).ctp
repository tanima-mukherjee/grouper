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
        username: {
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
       username: {
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
  <!-- edit Profile start -->
 <?php echo $this->element('edit_profile_modal'); ?>   
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
        <label class="label-field">Email</label>
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


  <?php echo $this->element('right_nav'); ?>

 

<div class="wrapper">
  <header class="header-wrap navbar-fixed-top">
    <div class="header-top">
      <div class="container">
        <div class="header-left-nav">
          <ul>
          <?php if ($this->Session->read('userData')) { ?>
            <li style="margin-top: 7px;">
              <a href="javascript:void(0)">
                <i class="fa fa-menu"></i><span class="menu-text">Menu</span>
              </a>
            </li>
          <?php } ?>
          <?php if ($this->Session->read('userData')) { 
              $notification_count= $this->requestAction(array('controller' => 'home', 'action' => 'unread_notifications'));
          ?>   

            <li>
              <a href="javascript:void(0)" onClick="notification_list()">
                <i class="fa noti-icon"></i>
                <div id="noti_count_div">
                <?php if($notification_count>0){ ?>
                 <span class="noti-count"><?php echo $notification_count; ?></span> 
                <?php } ?>
                </div>
              </a>
            </li>
            <?php $ArChatCount = $this->requestAction('chat/getChatCount');?>
            <li>
              <a href="<?php echo $this->webroot.'chat/index';?>">
                <i class="fa chat-icon"></i>
                <div id="chat_count_div"><?php if($ArChatCount > 0){?><span class="chat-count" id="chat_count"><?php echo $ArChatCount; ?></span><?php } ?></div>
              </a>
            </li>
          <?php } ?>

          </ul>
        </div>
        <div class="logo-wrap">
          <a href="<?php echo $this->webroot;?>">
            <img src="<?php echo $this->webroot;?>images/logo.png" alt="" />
          </a>
        </div>
        <div class="user-top-info">
        <?php if ($this->Session->read('userData')) { ?>
        <?php $UserProfileImage = $this->requestAction('home/user_detail/');?>
          <div class="user-wrap">
            <Span class="user-profile">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#editProfileModal">
              <?php if($UserProfileImage['User']['image']!=''){?>
                <img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $UserProfileImage['User']['image'];?>" alt="">
              <?php }else{ ?>
                <img id="thumbnil" src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
              <?php } ?>    
            </a>  
            </span>

            <?php 
              $loggedUserState = $this->requestAction('home/getUserSateName/');
              $loggedUserCity = $this->requestAction('home/getUserCityName/');
            ?>
            <span class="user-location"><?php echo $loggedUserState;?> / <?php echo $loggedUserCity;?> </span>
          </div>
          <?php } ?>
          <span class="logout-wrap">
          <?php if ($this->Session->read('userData')) { ?>
            <a href="<?php echo $this->webroot.'home/logout';?>">Logout</a>
          <?php }else{ ?>
            <a href="javascript:void(0)" class="login-btn" data-toggle="modal" data-target="#loginModal">Login</a>
          <?php } ?>
          </span>
        </div>
      <div class="clearfix"></div>
      </div>
    </div>
    <div class="navbar navbar-default">
      <div class="container">       
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand nav-logo" href="index.html" style="display: none;">
            <h1><img class="img-responsive" src="<?php echo $this->webroot;?>images/logo.png" alt="logo"></h1>
          </a>                    
        </div>   





        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav main-nav"> 
            <li><a href="<?php echo $this->webroot?>" <?php if($this->params['controller'] == 'home' && $this->params['action'] == 'index'){?> class="active" <?php } ?>>Home</a></li> 
            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#infographicModal">About Us</a></li>                     
            <li><a href="<?php echo $this->webroot.'category/category_list';?>" <?php if($this->params['controller'] == 'category'){?> class="active" <?php } ?>>Categories</a></li> 
            <li><a href="<?php echo $this->webroot.'home/faq';?>" <?php if($this->params['action'] == 'faq'){?> class="active" <?php } ?>>Faq</a></li>
            <li><a href="<?php echo $this->webroot.'home/contact_us';?>" <?php if($this->params['action'] == 'contact_us'){?> class="active" <?php } ?>>Contact Us</a></li>                 
          </ul>
        </div>
      </div>
    </div>  
      
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


setInterval(function(){checkChatCount();}, 800);
 
function checkChatCount()
{
	
$.ajax({
	type: 'get',
	//url: WEBROOT+'gossip/detail_chat_newmsg',
	url: WEBROOT+'chat/checkChatCount',
	//data:  {fid: $fid, mid: $mid},
	//dataType: 'json',
	success: function(chat_count)
	{
		 //alert(rsp);
		if(chat_count == 0){
			 jQuery('#chat_count_div').html('');
		}else{
			jQuery('#chat_count_div').html('<span class="chat-count" id="chat_count">'+chat_count+'</span>');
		}
	}
});
	
	
}


setInterval(function(){checkNotiCount();}, 800);
 
function checkNotiCount()
{
  
$.ajax({
  type: 'get',
  //url: WEBROOT+'gossip/detail_chat_newmsg',
  url: WEBROOT+'home/check_unread_notifications',
  //data:  {fid: $fid, mid: $mid},
  //dataType: 'json',
  success: function(noti_count)
  {
     //alert(rsp);
    if(noti_count == 0){
    jQuery('#noti_count_div').html('');
    }else{

    jQuery('#noti_count_div').html('<span class="noti-count" id="noti_count">'+noti_count+'</span>');
    }
  }
});
  
}
</script>



