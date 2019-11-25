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
   <!-- custom css -->  
  <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet">   
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

  <script>
var WEBROOT = '<?php echo $this->webroot;?>';
  function CityList(state_id){
      //alert(state_id);
      /*var city_default = '<option value="">Select City</option>';
      jQuery("#private_city_id").html(city_default);*/
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
<body>
<!-- Modal -->
<div id="side_nav" class="modal right fade" role="dialog">
  <div class="modal-dialog slidenav-modal">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"></div>
      <div class="modal-body">
      <ul class="slidenav">
        <li><a href="<?php echo $this->webroot.'home/my_group';?>"><i class="fa fgroup"></i>My groups</a></li>
        <li><a href="#"><i class="fa fgroup"></i>My friends</a></li>
        <li><a href="#"><i class="fa fprofile"></i>Profile</a></li>
        <li><a href="#"><i class="fa ftestimonials"></i>Testimonials</a></li>
		<li><a href="<?php echo $this->webroot.'home/logout';?>" class="logout"><i class="fa flogout"></i> Logout</a></li>
      </ul>
      </div>
    </div>
  </div>
</div>

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

					<li><a href="javascript:void(0)" onclick="CityList(<?php echo $list['State']['id'];?>)" ><?php echo ucfirst($list['State']['name'])?></a></li>
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
						  <li class="message-noti"><a href="#"data-toggle="dropdown"><i class="fa noti-icon"></i></a>
							<div class="dropdown-menu noti-dropdown">
									<ul>
										<li>											
											<div class="group-img">
												<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
											</div>
											<div class="message-div">
												<span>Manish Kumar</span> invited you to  <span>Rangrez</span>
												<div class="actions">
													<button type="button" class="confirm-btn">Accept</button>
													<button type="button" class="rej-btn">Reject</button>
												</div>
											</div> 	
										<div class="clearfix"></div>
										</li>
									    <li>											
											<div class="group-img">
												<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
											</div>
											<div class="message-div">
												<span>Manish Kumar</span> recommended to join the <span>Rangrez</span>
												<div class="actions">
													<button type="button" class="confirm-btn">Accept</button>
													<button type="button" class="rej-btn">Reject</button>
												</div>
											</div> 	
										<div class="clearfix"></div>
										</li>
										<li>											
												<div class="group-img">
													<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
												</div>
													<div class="message-div">
														<div class="align-center">
															<span class="user-request">Manish Kumar</span>
															<div class="actions actions-right">
																<button type="button" class="confirm-btn">Accept</button>
																<button type="button" class="rej-btn">Reject</button>
															</div>
														</div>
													</div> 	
												<div class="clearfix"></div>
											</li>
									    <li>											
											<div class="group-img">
												<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
											</div>
											<div class="message-div">
												<span>Manish Kumar</span> rejected you request
												<div class="actions">
													<button type="button" class="confirm-btn">Remove</button>													
												</div>
											</div> 	
										<div class="clearfix"></div>
										</li>
										<li>											
											<div class="group-img">
												<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
											</div>
											<div class="message-div">
												You are now the member of the <span>Rangrez</span>
												<div class="actions">
													<button type="button" class="confirm-btn">Remove</button>													
												</div>
											</div> 	
										<div class="clearfix"></div>
										</li>
										<li>											
												<div class="group-img">
													<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
												</div>
													<div class="message-div">
														<div class="align-center">
															<span class="user-request">Manish Kumar</span>
															<div class="actions actions-right">
																<button type="button" class="confirm-btn">Accept</button>
																<button type="button" class="rej-btn">Reject</button>
															</div>
														</div>
													</div> 	
												<div class="clearfix"></div>
											</li>
									</ul>
									
									<div class="friend-request">
										<h4>Friend Requests</h4>
										<ul>
											<li>											
												<div class="group-img">
													<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
												</div>
													<div class="message-div">
														<div class="align-center">
															<span class="user-request">Manish Kumar</span>
															<div class="actions actions-right">
																<button type="button" class="confirm-btn">Accept</button>
																<button type="button" class="rej-btn">Reject</button>
															</div>
														</div>
													</div> 	
												<div class="clearfix"></div>
											</li>
											<li>											
												<div class="group-img">
													<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
												</div>
													<div class="message-div">
														<div class="align-center">
															<span class="user-request">Manish Kumar</span>
															<div class="actions actions-right">
																<button type="button" class="confirm-btn">Accept</button>
																<button type="button" class="rej-btn">Reject</button>
															</div>
														</div>
													</div> 	
												<div class="clearfix"></div>
											</li>
											<li>											
												<div class="group-img">
													<img src="<?php echo $this->webroot?>images/video-img01.jpg" alt="" />
												</div>
													<div class="message-div">
														<div class="align-center">
															<span class="user-request">Manish Kumar</span>
															<div class="actions actions-right">
																<button type="button" class="confirm-btn">Accept</button>
																<button type="button" class="rej-btn">Reject</button>
															</div>
														</div>
													</div> 	
												<div class="clearfix"></div>
											</li>
										<ul>
									</div>
									
								</div>
						  </li>
						  <?php if ($this->Session->read('userData')) { ?>
						  <!--<li class="login"><a href="<?php echo $this->webroot.'home/logout';?>" class="login-btn">Logout</a></li>-->
						  <?php } else { ?>
							<li class="login"><a href="<?php echo $this->webroot.'home/login';?>" class="login-btn" data-toggle="modal" data-target="#loginModal">Login</a></li>
							<?php }  ?>						  
						</ul>
						<a href="#" data-toggle="modal" data-target="#state" class="select-state"><?php echo $StateName['State']['code'];?> / <?php echo $CityName['City']['name'];?></a>
         
					  </div>
               		<div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right mainnav"> 
                        <li><a href="#">Home</a></li>       
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
