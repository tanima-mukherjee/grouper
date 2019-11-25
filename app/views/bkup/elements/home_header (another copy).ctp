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


<div id="infographicModal" class="modal fade" role="dialog">
<div class="popup-box">
	 <button type="button" class="close slideclose" data-dismiss="modal">&times;</button>
<div class="owl-carousel owl-theme">
	<div class="item">
		<div class="step-box step1-bg-color">
			<div class="step-info">
				<img src="<?php echo $this->webroot?>images/step-img01.png" alt="" />				
				<h3>GrouperUSAmanages ALL your Groups and Promotes Your Community.</h3>
				<h3>Manage Your Schedule.  Communicate with Your Groups.  Engage Your Community.</h3>
				<ul>
					<li>Create Groups for Communicating, Scheduling, Messaging, Event Notifications</li>
					<li>Community Calendar of events to show you “what else” is happening in your Community</li>
					<li>Personal Calendar collects all your activities, regardless of group, to one Calendar</li>
					<li>Get Great Discounts from Local Businesses</li>
					<li>Community Focused….not focused on just one team or group</li>
					<li>Fun Website and Free Mobile App for “On the Go” Communications</li>
				</ul>
			</div> 
		</div>
	</div>
	<div class="item">
		<div class="step-box step2-bg-color">
			<div class="step-info">
			<img src="<?php echo $this->webroot?>images/step-img02.png" alt="" />
			<h2>GROUPS!</h2>
			<ul>
				<li>Create/Join Groups of all kinds:  family, sports teams, neighborhoods, friends, school, work, etc.</li>
				<li>Post Events to the Group Calendar AND (if you wish) Community Calendar!</li>
				<li>Post Documents, Photos, and Videoson your Group Pages!</li>
				<li>Send/Receive Messages from Group Leader</li>
				<li>Receive Event Reminder Notifications, Event Directions, Last Minute Rainout or Cancelation Alerts</li>
				<li>Pause or Silence Messages or Notifications as necessary</li>
			</ul>
			</div>
		</div>
	</div>
	<div class="item">
		<div class="step-box step3-bg-color">
			<div class="step-info">
				<img src="<?php echo $this->webroot?>images/step-img03.png" alt="" />
				<h2>COMMUNITY CALENDAR!</h2>
				<h3>You know what YOU do…</h3>
				<h3>Now find out “What ELSE” is going on in your town at a glance EVERY DAY!</h3>
				<ul>
					<li>Event Calendar with Date/Time/Event Description and Map to the Events for each day!</li>
					<li>Great for announcing League Registrations, School Events, Civic Events, Local Sporting Events, When Volunteers are Needed, Parades, and all Public Events</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="item">
		<div class="step-box step4-bg-color">
			<div class="step-info">
				<img src="<?php echo $this->webroot?>images/step-img04.png" alt="" />
				<h2>Personal Calendar and Friends List!</h2>
				<ul>
					<li>Personal Calendar automatically ImportsEvents from all your Groups and Displays them on ONE EASY TO READ Calendar!</li>
					<li>Manage Your Daily Events at a Glance</li>
					<li>Receive Reminder Notifications prior to Each Event</li>
					<li>Create a Friends List for Easy Messaging</li>
					<li>Control who can send you messages!</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="item">
		<div class="step-box step2-bg-color">
			<div class="step-info">
			<img src="<?php echo $this->webroot?>images/step-img05.png" alt="" />
			<h2>Business Deals Calendar!!</h2>
			<ul>
				<li>Search Local Businesses for Great Daily Discounts!</li>
				<li>Join Business Groups for Special Members Only Discounts and Last Minute Deals!</li>
				<li>Support and Find Local Businesses by Category Search</li>
				<li>Recommend Businesses to Friends with One Click</li>
				<li>View # of Group Members for Confidence Rating</li>				
			</ul>
			</div>
		</div>
	</div>
</div>
<div class="last-step">	
	<a href="#" data-dismiss="modal"> <i class="fa fa-right-tick" ></i></a>
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
      <img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" />
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
        <li><a href="#"><i class="fa fgroup"></i>My friends</a></li>
        <li><a href="#"><i class="fa fprofile"></i>Profile</a></li>
        <li><a href="#"><i class="fa ftestimonials"></i>Testimonials</a></li>
   
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
					
							 <li class="dropdown message-noti"><a href="#"><i class="fa noti-icon" onclick="notification_list()"></i></a>
							 <div class="dropdown-menu noti-dropdown" id = "noti_list" >

								</div>
							<?php } ?>
							 <?php if ($this->Session->read('userData')) { ?>
							<li class="login"><a href="<?php echo $this->webroot.'home/logout';?>" class="login-btn" >Logout</a></li>
							<?php } else { ?>
								<li class="login"><a href="<?php echo $this->webroot.'home/login';?>" class="login-btn" data-toggle="modal" data-target="#loginModal">Login</a></li>
								<?php }  ?>
						</ul>
					</div>
               		<div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right mainnav"> 
						 
                          	 <?php if ($this->Session->read('userData')) { ?>
              <li><a href="<?php echo $this->webroot.'category/category_list';?>">Categories</a></li>
              <?php } else { ?>
              <li><a href="#">Categories</a></li>
              <?php }  ?>
                          	<li><a href="#">Search</a></li>                     
                          	<li><a href="#">Teams</a></li> 
							<li><a href="#">Contact</a></li> 	
                <li><a href="#" data-toggle="modal" data-target="#infographicModal">About</a></li> 						
                        </ul>
              		</div>
			</div>
			</div>	
       <?php if ($this->Session->read('userData')) { ?>
			<div class="nav-slide">
				<a href="#" data-toggle="modal" data-target="#side_nav_after_login">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
			</div>
      <?php } ?>	
		</header>
			

		<div class="banner-bg">
			<div class="container">
				<div class="banner-part">
					<div class="hand-mobile">
						<img src="<?php echo $this->webroot?>images/mobile-group-chat.png" alt="" />
					</div>
					<div class="banner-caption">
						<h1><span><font>Join</font> a Group,</span> Organize your Life</h1>
					</div>
					<div class="banner-group">
						<img src="<?php echo $this->webroot?>images/banner-group-img.png" alt="" />
					</div>
				<div class="clearfix"></div>	
				</div>
			</div>
		</div>

