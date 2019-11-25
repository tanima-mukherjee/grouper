<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grouper</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet"> 
    <!-- magnific-popup css -->
  	<link href="<?php echo $this->webroot?>css/magnific-popup.css" rel="stylesheet">
	<!-- custom css -->  
	<link href="<?php echo $this->webroot?>css/dropzone.css" rel="stylesheet">  
    <link href="<?php echo $this->webroot?>css/dropzone1.css" rel="stylesheet">  

     <link href="<?php echo $this->webroot?>css/player-default.css" rel="stylesheet"> 	
  	<!-- slide css -->  
    <link href="<?php echo $this->webroot?>css/owl.carousel.css" rel="stylesheet">  
    <link href="<?php echo $this->webroot?>css/owl.theme.css" rel="stylesheet">  
	<!-- fancybox-popup css -->
	<link rel="stylesheet" href="<?php echo $this->webroot?>css/jquery.fancybox.css">
   	<!-- custom css -->  
  	<link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet">   
	<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
   
	


 

 
  
<script>
var WEBROOT = '<?php echo $this->webroot;?>';
  function CityList(state_id){
      //alert(state_id);
      if(state_id != ''){
         jQuery.ajax({
            type: "GET",
            url: WEBROOT+"group/show_city",
            data: {state_id:state_id},
            success: function(msg){
            //alert(msg);
            jQuery("#regionslinks").hide();
            jQuery("#subregionslinks").removeClass('hidden');
            jQuery("#subregionslinks").html(msg);
           
            
            }
        });
      }
    }
</script>

<script>
var WEBROOT = '<?php echo $this->webroot;?>';
function StateList(){

		//alert(msg);
		jQuery("#regionslinks").show();
		jQuery("#subregionslinks").addClass('hidden');
		
	 
}
</script>

<script>
var WEBROOT = '<?php echo $this->webroot;?>';
function SingleCity(city_id){
   if(city_id != ''){
	 jQuery.ajax({
		type: "GET",
		url: WEBROOT+"group/selected_city",
		data: {city_id:city_id},
		success: function(msg){
			location.reload();
		}
	});
  }
	   
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
            	jQuery("#group_accept_reject"+notification_id).remove();
				if(jQuery('#all_notifications li').length == 0){
					jQuery('#noti_list').html('<ul><li>No notifications yet<div class="clearfix"></div></li></ul>');
					//jQuery('#noti_list').show();
				}
				else{
					//jQuery('#noti_list').show();
				}
            }
			else if(response == '@@0@@'){
				jQuery("#group_accept_reject"+notification_id).html('<div class="message-div">Request has been rejected already !!!</div>');
				setTimeout(remove_notification, 3000, notification_id);
			}
			else if(response == '##0##'){
				jQuery("#group_accept_reject"+notification_id).html('<div class="message-div">Request has been accepted already !!!</div>');
				setTimeout(remove_notification, 3000, notification_id);
			}
          }
      });
    }
    else{
      alert('Notification id not found');
    }
  }
  
  function remove_notification(notification_id){
  	jQuery("#group_accept_reject"+notification_id).remove();
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
				jQuery("#group_accept_reject"+notification_id).remove();
				if(jQuery('#all_notifications li').length == 0){
					jQuery('#noti_list').html('<ul><li>No notifications yet<div class="clearfix"></div></li></ul>');
					//jQuery('#noti_list').show();
				}
				else{
					//jQuery('#noti_list').show();
				}             
		  }
		  else if(response == '@@0@@'){
				jQuery("#group_accept_reject"+notification_id).html('<div class="message-div"><span><i>Request has been rejected already !!!</i></span></div>');
				setTimeout(remove_notification, 3000, notification_id);
		  }
		  else if(response == '##0##'){
				jQuery("#group_accept_reject"+notification_id).html('<div class="message-div"><span><i>Request has been accepted already !!!</i></span></div>');
				setTimeout(remove_notification, 3000, notification_id);
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
  function accept_friend_request(notification_id){
  //  alert(notification_id);
    
    if(notification_id != ''){
      
       jQuery.ajax({

          type: "POST",
          url: WEBROOT+"friend/accept_friend_request",
          data: {notification_id:notification_id},
          
          success: function(response){
          if(response == '0'){
            jQuery("#group_accept_reject"+notification_id).remove();
        if(jQuery('#all_notifications li').length == 0){
          jQuery('#noti_list').html('<ul><li>No notifications yet<div class="clearfix"></div></li></ul>');
          //jQuery('#noti_list').show();
        }
        else{
          //jQuery('#noti_list').show();
        }
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
  function reject_friend_request(notification_id){
  //  alert(notification_id);
    
    if(notification_id != ''){
      
       jQuery.ajax({

          type: "POST",
          url: WEBROOT+"friend/reject_friend_request",
          data: {notification_id:notification_id},
          
          success: function(response){
          if(response == '0'){
            jQuery("#group_accept_reject"+notification_id).remove();
              if(jQuery('#all_notifications li').length == 0){
          jQuery('#noti_list').html('<ul><li>No notifications yet<div class="clearfix"></div></li></ul>');
          //jQuery('#noti_list').show();
        }
        else{
          //jQuery('#noti_list').show();
        }             
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
            jQuery("#group_accept_reject"+notification_id).remove();
				if(jQuery('#all_notifications li').length == 0){
					jQuery('#noti_list').html('<ul><li>No notifications yet<div class="clearfix"></div></li></ul>');
					//jQuery('#noti_list').show();
				}
				else{
					//jQuery('#noti_list').show();
				} 
            
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
  function notification_list(){
 //  alert(user_id);
     
       jQuery.ajax({

          type: "GET",
          url: WEBROOT+"home/notification_list",
          success: function(response){
          //alert(response);
		   jQuery('.noti-count').hide();
           jQuery("#noti_list").html(response);
          }
      });
       
   
  }
</script>
<!-- edit Profile start -->
 <?php echo $this->element('edit_profile_modal'); ?>   
    <!-- edit profile end -->




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
<body>

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
  <a href="javascript:void(0)" onClick="forgot_password()" class="rightlink">Forgot password?</a>
  </form>

    <form action="<?php echo $this->webroot?>category/forgot_password" method="post" id="forgot_pass_form" style="display:none;" name="forgot_pass_form">
  
  <div class="form-group">
  <label class="label-field">Email</label>
  <input type="text" class="form-control" id="forgot_email" name="forgot_email"> 
  <div class="clearfix"></div>                    
  </div>
  
  <button type="submit" class="btn signupbtn">Submit</button>
  <span class="signinlink">Already User <a href="javascript:void(0)" onClick="signin()" class="rightlink22">Sign in?</a></span>
  </form>
  
<div class="clearfix"></div>
</div>

<div class="clearfix"></div>  
</div>
</div>    
  </div>
  
</div>

<?php //echo $this->element('infographic'); ?>
<script>
function forgot_password()
{
  jQuery("#login_form").hide();
  jQuery("#forgot_pass_form").show();
  jQuery("#head1").html("FORGOT PASSWORD");
  
}
function signin()
{
  jQuery("#login_form").show();
  jQuery("#forgot_pass_form").hide();
  jQuery("#head1").html("LOGIN");
  
}
</script>

  <?php echo $this->element('right_nav'); ?>

<div class="modal fade" id="state" role="dialog">
  <div class="modal-dialog statelist">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="state-row">
			
						
			<div id="regionslinks">
				<h2>Select State</h2>
				<div class="clearfix"></div>
				<div class="list-table"> 
					<?php  $counter = 1;?>
					<ul class="list-column">
					<?php  foreach ($ArState as $list) { ?>

					<li><a href="javascript:void(0)" onClick="CityList(<?php echo $list['State']['id'];?>);"><?php echo ucfirst($list['State']['name'])?></a></li>
					<?php 
					$counter ++; 
					if ($counter%13 == 0) {
					echo '</ul>';    
					echo '<ul class="list-column">';
					}

					}  
					?>
					</ul>
				</div>
			</div>
		
		
			<div class="hidden" id="subregionslinks">
				
				
			</div>
		
	
		
		
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
					<?php if ($this->Session->read('userData')) { 
						  $notification_count= $this->requestAction(array('controller' => 'home', 'action' => 'unread_notifications'));
					?>						
					  <li class="message-noti dropdown">
					  <a href="javascript:void(0)">
						<i class="fa noti-icon" onClick="notification_list()"></i>
						<div id="noti_count_div">
            <?php if($notification_count>0){ ?>
						 <span class="noti-count"><?php echo $notification_count; ?></span> 
						<?php } ?>
            </div>
					  </a>
					 
						<div class="dropdown-menu noti-dropdown" id = "noti_list" >	
							
						</div>
					  </li>
					  
					  <?php $ArChatCount = $this->requestAction('chat/getChatCount');?>
					  <li class="message-noti">
						  <a href="<?php echo $this->webroot.'chat/index';?>">
						  <i class="fa chat-icon"></i>
						  <div id="chat_count_div"><?php if($ArChatCount > 0){?><span class="chat-count" id="chat_count"><?php echo $ArChatCount; ?></span><?php } ?></div>
						</a>
					 </li>
					  
					  <?php }  ?>
					  <?php if ($this->Session->read('userData')) { ?>
					  <!--<li class="login"><a href="<?php echo $this->webroot.'home/logout';?>" class="login-btn">Logout</a></li>-->
					  <?php } else { ?>
						<li class="login"><a href="javascript:void(0)" class="login-btn" data-toggle="modal" data-target="#loginModal">Login</a></li>
						<?php }  ?>						  
					</ul>
					
					<?php if(isset($selected_state_id) && $selected_state_id>0 && isset($selected_city_id) && $selected_city_id>0) { ?>
					<a href="javascript:void(0)" data-toggle="modal" data-target="#state" class="select-state"><?php echo $StateName['State']['code'];?> / <?php echo $CityName['City']['name'];?></a>
					<?php }  ?>	
					
		  <?php if ($this->Session->read('userData')) { ?>
		  	<?php $UserProfileImage = $this->requestAction('home/user_detail/');?>
				<div class="user-pro-img">
		  			<a href="javascript:void(0)" data-toggle="modal" data-target="#editProfileModal">
						<?php if($UserProfileImage['User']['image']!=''){?>
						<img  src="<?php echo $this->webroot;?>user_images/medium/<?php echo $UserProfileImage['User']['image'];?>" alt="">
						<?php }else{ ?>
						<img  src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
						<?php } ?>                
		  			</a>
				</div>
			<?php } ?>
			
			<?php if ($this->Session->read('userData')) { ?>	
				<div class="nav-slide">
					<a href="javascript:void(0)" data-toggle="modal" data-target="#side_nav" class="sidenavmenu">Menu</a>
				</div>
			<?php } ?>
		
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right mainnav"> 
					<li><a href="<?php echo $this->webroot?>">Home</a></li>      
					<li><a href="<?php echo $this->webroot.'category/category_list';?>">Categories</a></li>
					<li><a href="<?php echo $this->webroot.'home/faq';?>">Faq</a></li>   
					<li><a href="<?php echo $this->webroot.'home/contact_us';?>">Contact</a></li> 	
					<!--<li><a href="javascript:void(0)" data-toggle="modal" data-target="#infographicModal">About</a></li>	-->					
				</ul>
			</div>
		</div>
		</div>
		
			
	</header>
		
<script>
/*$(document).ready(function() 
{
	$(".region_link").click(function() 
	{
		alert("ghfsdh");
	});
});*/

/*$('#regionslinks').on('click',function(){
    
  $('.list-table').removeClass('hidden');
  $(this).addClass('hidden');
  
});

$('#subregionslinks').on('click',function(){
    
  $('.list-table').removeClass('hidden');
  $(this).addClass('hidden');
  
});*/
/*
$('#regionslinks').on('click',function(){    
  $('.list-table.test1').removeClass('hidden');
  $(this).addClass('hidden');  
});

$('#changelocation').on('click',function(){    
  $('.list-table').removeClass('hidden');
  $('.list-table.test1').addClass('hidden');
  
});*/

$('.region_link').on('click',function(){    
  $('#regionslinks').removeClass('hidden');
  $('#regionslinks').addClass('hidden');
  $('#subregionslinks').removeClass('hidden');
});

$('.changelocation').on('click',function(){    
  $('#subregionslinks').removeClass('hidden');
  $('#subregionslinks').addClass('hidden');
  $('#regionslinks').removeClass('hidden');
});
</script>	

<script>
  function showMyImage1(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil9");
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
