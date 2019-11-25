 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grouper</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet"> 
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
        <li><a href="#"><i class="fa fgroup"></i>My groups</a></li>
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
			<ul class="list-column">
				<li><a href="#" class="region_link">Alabama</a></li>
				<li><a href="#">Alaska</a></li>
				<li><a href="#">Arizona</a></li>
				<li><a href="#">Arkansas</a></li>
				<li><a href="#">California</a></li>
				<li><a href="#">Colorado</a></li>
				<li><a href="#">Connecticut</a></li>
				<li><a href="#">Delaware</a></li>
				<li><a href="#">District of Columbia cccccc</a></li>
				<li><a href="#">Florida</a></li>
				<li><a href="#">Georgia</a></li>
				<li><a href="#">Hawaii</a></li>
				<li><a href="#">Idaho</a></li>
			</ul>
			<ul class="list-column">				
				<li><a href="#">Illinois</a></li>
				<li><a href="#">Indiana</a></li>
				<li><a href="#">Iowa</a></li>
				<li><a href="#">Kansas</a></li>
				<li><a href="#">Kentucky</a></li>
				<li><a href="#">Louisiana</a></li>
				<li><a href="#">Maine</a></li>
				<li><a href="#">Maryland</a></li>
				<li><a href="#">Massachusetts</a></li>
				<li><a href="#">Michigan</a></li>
				<li><a href="#">Minnesota</a></li>
				<li><a href="#">Mississippi</a></li>
				<li><a href="#">Missouri</a></li>
			</ul>
			<ul class="list-column">				
				<li><a href="#">Montana</a></li>
				<li><a href="#">Nebraska</a></li>
				<li><a href="#">Nevada</a></li>
				<li><a href="#">New Hampshire</a></li>
				<li><a href="#">New Jersey</a></li>
				<li><a href="#">New Mexico</a></li>
				<li><a href="#">New York</a></li>
				<li><a href="#">North Carolina</a></li>
				<li><a href="#">North Dakota</a></li>
				<li><a href="#">Ohio</a></li>
				<li><a href="#">Oklahoma</a></li>
				<li><a href="#">Oregon</a></li>
				<li><a href="#">Pennsylvania</a></li>
			</ul>
			<ul class="list-column">
				
				<li><a href="#">South Carolina</a></li>
				<li><a href="#">South Dakota</a></li>
				<li><a href="#">Tennessee</a></li>
				<li><a href="#">Texas</a></li>
				<li><a href="#">Utah</a></li>
				<li><a href="#">Vermont</a></li>
				<li><a href="#">Virginia</a></li>
				<li><a href="#">Washington</a></li>
				<li><a href="#">West Virginia</a></li>
				<li><a href="#">Minnesota</a></li>
				<li><a href="#">Wyoming</a></li>
				<li><a href="#">Territories</a></li>
			</ul>
			</div>
		</div>
		
        
		<div class="hidden" id="subregionslinks">
			<h2>Select City</h2>
			<div class="clearfix"></div>
			<div class="city-list">			
			<ul class="city-column">
                <li><a href="#" class="changelocation"><strong>Change Location</strong></a></li>				
				<li><a href="#">Birmingham</a></li>
				<li><a href="#">Montgomery</a></li>
				<li><a href="#">Mobile</a></li>
				<li><a href="#">Huntsville</a></li>
				<li><a href="#">Tuscaloosa</a></li>
				<li><a href="#">Hoover</a></li>
				<li><a href="#">Dothan</a></li>
				<li><a href="#">Auburn</a></li>
				<li><a href="#">Decatur</a></li>
				<li><a href="#">Madison</a></li>
			</ul>
			<ul class="city-column">                				
				<li><a href="#">Birmingham</a></li>
				<li><a href="#">Montgomery</a></li>
				<li><a href="#">Mobile</a></li>
				<li><a href="#">Huntsville</a></li>
				<li><a href="#">Tuscaloosa</a></li>
				<li><a href="#">Hoover</a></li>
				<li><a href="#">Dothan</a></li>
				<li><a href="#">Auburn</a></li>
				<li><a href="#">Decatur</a></li>
				<li><a href="#">Madison</a></li>
			</ul>
			</div>
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
						  <li class="message-noti"><a href="#"><i class="fa noti-icon"></i></a></li>
						  <?php if ($this->Session->read('userData')) { ?>
						  <!--<li class="login"><a href="<?php echo $this->webroot.'home/logout';?>" class="login-btn">Logout</a></li>-->
						  <?php } else { ?>
							<li class="login"><a href="<?php echo $this->webroot.'home/login';?>" class="login-btn" data-toggle="modal" data-target="#loginModal">Login</a></li>
							<?php }  ?>						  
						</ul>
						<a href="#" data-toggle="modal" data-target="#state" class="select-state">CA/ Santaana</a>
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