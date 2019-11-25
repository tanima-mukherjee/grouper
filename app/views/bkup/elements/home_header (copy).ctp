  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grouper</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet"> 
  <!-- soldier css -->  
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
 
 <script>
 	function divFunction(){
    //Some code
    	//alert();
      jQuery("#infographic").hide();
      
}
 </script>
 
 <div class="" style="background:#fff; position:fixed; width:100%; height:100%; background:rgba(0,0,0,.9); z-index:1200;" onload="oQuickReply.swap();" id = "infographic">
	<div id="somid" class="popup-box">
<div class="owl-carousel owl-theme">
	<div class="item">
		<div class="step-box step1-bg-color">
			<div class="step-info">
				<img src="images/step-img01.png" alt="" />
				<h2>So what can GrouperUSA do for you? A lot! Let us show you</h2>
				<p>GrouperUSA differs from other apps and websites because we focus on your entire community and not just your team or league. Not only can you join and set up an unlimited number of Groups for communicating with you friends, family, neighbors, teams, coworkers, sports leagues and more, but we allow Group Leaders to make events
				"public" if they wish because we believe everyone benefits from knowing "what else" is going on in our community! So we let you quickly and easily see other activities and events in your community. But more than that, we also promote local businesses so now you can get great discounts and coupons anytime while supporting those businesses that help make your community Great!</p>
			</div> 
		</div>
	</div>
	<div class="item">
		<div class="step-box step2-bg-color">
			<div class="step-info">
			<img src="images/step-img02.png" alt="" />
			<h2>Whether from our Website or App, you can :</h2>
			<p>Create Groups, Join Groups, and Receive messages from your Group Leaders. You can receive Automatic Event Reminders prior to your group events and last minute Event Cancellation Notifications from your league or group leaders.</p>
			<p>Group leaders can post documents, photos, and videos to their Group Page as well as schedule events which automatically are linked to Group Member's Calendars.</p>
			<p>Users get a free Personal Calendar which collects all events from their various groups and puts them on one easy to read Calendar complete with an interactive map for finding the event location.</p>
			</div>
		</div>
	</div>
	<div class="item">
		<div class="step-box step3-bg-color">
			<div class="step-info">
			<img src="images/step-img03.png" alt="" />
			<h2>You can also :</h2>
			<p>Everyone can see other Public Events happening on our Community Calendar so you can learn “what else is going on” around town as well as when, where, event details, and even directions to the events.</p>
			<p>Users can have a Friends List so you can send/receive Push Messages from your Friends regardless of group affiliations.</p>
			<p>And Anyone can get Great Discounts and Information from Local Businesses by Searching our Business Group Pages and downloading a Discount Voucher from the Business Groups on our site. Users can even Join a Business Group in order to get amazing last minute Special Discounts only Business Group Members can receive.</p>
			</div>
		</div>
	</div>
	<div class="item">
		<div class="step-box step4-bg-color">
			<div class="step-info">
			<img src="images/step-img04.png" alt="" />
			<h2>Serving the Community :</h2>
			<p>And for those in need of some kind of assistance or for those interested in helping others in need, we offer a “Meet the Need” portal to make it easy to find ways of truly serving your community. </p>
			<p>Getting discounts, staying organized, keeping in touch with friends, helping others, and staying connected to your community have never been so fun!</p>
			</div>
		</div>
	</div>
</div>
<div class="last-step">
	<i class="fa fa-right-tick" onClick="divFunction()"></i>
</div>
</div>
 </div>

 <script type="text/javascript">
   oQuickReply.swap('somid');
</script>
 
 

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
							<li class="message-noti"><a href="#"><i class="fa noti-icon"></i></a></li>
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
                        </ul>
              		</div>
			</div>
			</div>	
			<div class="nav-slide">
				<a href="#" data-toggle="modal" data-target="#side_nav">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
			</div>	
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

