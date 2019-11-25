<!DOCTYPE HTML>
<html class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title>404 page not found</title>

<!-- MAIN style sheet goes here -->
<link href="<?php echo $this->webroot?>css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->webroot?>css/font-awesome.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->webroot?>css/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->webroot?>css/responsive.css" rel="stylesheet" type="text/css">
<link href="<?php echo $this->webroot?>images/fabicon.png" rel="icon" />

<!-----------------up_banner css-------------------->
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.bxslider.css" />

<!-- MAIN style sheet goes here -->
<script>var WEBROOT = '<?php echo $this->webroot;?>';</script>
<!-- MAIN script sheet goes here -->
<script language="javascript" src="<?php echo $this->webroot?>js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/modernizr.js" ></script>

<link rel="stylesheet" href="<?php echo $this->webroot?>css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo $this->webroot;?>js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $this->webroot;?>js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="css/all-ie-only.css" />
<![endif]-->
<!--[if lt IE 10]>
<script src="js/pie.js" type="text/javascript"></script>  
<![endif]-->

<script>
	$(document).ready( function() {
		$("#user_login").validationEngine();
		$("#user_sign_up").validationEngine();
	});
</script>


<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '636644649767238', // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
</script>
<script> 
jQuery(".fblogin").live("click",function(e){
    e.preventDefault();
        FB.login(function(response) {
			$('#floatingBarsG').show();
            if (response.authResponse) {
                var UserAccessToken = response.authResponse.accessToken;
               FB.api('/me', function(response) {
				//console.log(response);return false;	
                    jQuery.ajax({
                        type : 'POST',
                        data : {response:response,UserAccessToken:UserAccessToken},
                        url : '<?php echo $this->webroot?>home/facebooklogin',
                        success: function(data)
			{
				//console.log(data);		
				if(!isNaN(data))
							{
							   var user_id = data;
							   //alert(user_id);
							   /*FB.api('/me/albums', function(response)
							   {
									//console.log(response);
									var i=0;
									for (album in response.data) {

									// Find the Profile Picture album
									if (response.data[album].name == "Profile Pictures") {

										// Get a list of all photos in that album.
										FB.api(response.data[album].id + "/photos", function (response) {
										for(var i = 0; i< 8 ;i++){
										var source=	response['data'][i]['source'];
											
										jQuery.ajax({
										type : 'POST',
										//var response_array = response[data][][source],
										data : {response:source},
										
										url : '<?php echo $this->webroot?>home/fb_album',
										
										
										success: function(data)
										//alert(data); exit;
										{
												$('#floatingBarsG').hide();
												parent.parent.window.location = "<?php echo $this->webroot?>profile/form1";
											
										},
										
									});	}
											
										});
									}
									i=i+1;
								}
								}
								
								
								);*/
								//$('#circularG').hide();
								//parent.parent.window.location = "<?php echo $this->webroot?>profile/form1";
								
								$('#floatingBarsG').hide();
								parent.parent.window.location = "<?php echo $this->webroot?>home/create_event";
							}
							else
							{
								if(data == 'active')
								{
									
									/* FB.api('/me/albums', function(response)
							   		{
									//console.log(response);
									var i=0;
									for (album in response.data) {

									// Find the Profile Picture album
									if (response.data[album].name == "Profile Pictures") {

										// Get a list of all photos in that album.
										FB.api(response.data[album].id + "/photos", function (response) {
										for(var i = 0; i< 8 ;i++){
										var source=	response['data'][i]['source'];
											
										jQuery.ajax({
										type : 'POST',
										//var response_array = response[data][][source],
										data : {response:source},
										
										url : '<?php echo $this->webroot?>home/fb_album',
										
										
										success: function(data)
										//alert(data); exit;
										{
												$('#floatingBarsG').hide();
												parent.parent.window.location = "<?php echo $this->webroot?>profile/index";
										},
										
									});	}
											
										});
									}
									i=i+1;
								}
								});*/
									//$('#circularG').hide();
									//parent.parent.window.location = "<?php echo $this->webroot?>profile/index";
									$('#floatingBarsG').hide();
									parent.parent.window.location = "<?php echo $this->webroot?>home/create_event";
								}
								else
								{ 
									/* FB.api('/me/albums', function(response)
							  		 {
									//console.log(response);
									var i=0;
									for (album in response.data) {

									// Find the Profile Picture album
									if (response.data[album].name == "Profile Pictures") {

										// Get a list of all photos in that album.
										FB.api(response.data[album].id + "/photos", function (response) {
										for(var i = 0; i< 8 ;i++){
										var source=	response['data'][i]['source'];
											
										jQuery.ajax({
										type : 'POST',
										//var response_array = response[data][][source],
										data : {response:source},
										
										url : '<?php echo $this->webroot?>home/fb_album',
										
										
										success: function(data)
										//alert(data); exit;
										{
											$('#floatingBarsG').hide();
											parent.parent.window.location = "<?php echo $this->webroot?>profile/thank_you";
										},
										
									});	}
											
										});
									}
									i=i+1;
								}
								});*/
								$('#floatingBarsG').hide();
								parent.parent.window.location = "<?php echo $this->webroot?>home/create_event";
								}
                            }
							
						}
                    });
                });
				FB.getLoginStatus(function(response) {
                    if (response.status === 'connected') {
                    // the user is logged in and connected to your
                    // app, and response.authResponse supplies
                    // the user?s ID, a valid access token, a signed
                    // request, and the time the access token 
                    // and signed request each expire
                    //var uid = response.authResponse.userID;
                    var UserAccessToken = response.authResponse.accessToken;

                    } else if (response.status === 'not_authorized') {
                    // the user is logged in to Facebook, 
                    //but not connected to the app
                    } else {
                    // the user isn't even logged in to Facebook.
                    }
                });
            } else {
				$('#floatingBarsG').hide();
                console.log('User cancelled login or did not fully authorize.');
            }
    }, {scope: 'email,user_friends,user_hometown'});
});

jQuery("#fbLogout").live('click',function(e)
{
	e.preventDefault();
	FB.logout(function(response) 
	{
		window.location.href='<?php echo $this->webroot.'home';?>'; return false;
	});
	window.location.href='<?php echo $this->webroot.'home';?>';
});
jQuery(document).ready( function() {
		jQuery("#form_invite").validationEngine();
		});
</script>

<style>
.message_divred{
	font-size:18px;
	color: #FF0000;
	text-align:center;
	position:absolute;
	top:350px;
	left:30%;
	background:#FFCC66;
	border:solid 2px #fff;
	border-radius:3px;
	text-align:center;
	padding:15px 100px;
	-webkit-box-shadow: 3px 3px 4px #636363;
-moz-box-shadow: 3px 3px 4px #636363;
box-shadow: 3px 3px 4px #636363;
}
.message_divred .close{position:absolute; z-index:9999; right:4px; top:-5px; width:10px; height:10px;}

.message_divgreen{
	font-size:18px;
	color: #fff;
	text-align:center;
	position:absolute;
	top:350px;
	left:30%;
	background:#9ACB34;
	border:solid 2px #fff;
	border-radius:3px;
	text-align:center;
	padding:15px 100px;
	-webkit-box-shadow: 3px 3px 4px #636363;
-moz-box-shadow: 3px 3px 4px #636363;
box-shadow: 3px 3px 4px #636363;
}
.message_divgreen .close{position:absolute; z-index:9999; right:4px; top:-5px; width:10px; height:10px;}
</style>

<style>
.msg {
	background-color: #3FB8AF;
    border: 1px solid #3FB8AF;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 1px 1px 10px 5px #3FB8AF;
	border-top-width: 5px;
	border-bottom-width: 5px;
    color: #000000;
    font-weight: bold;
	display:inline-block;
	padding:0 25px;
}
#floatingBarsG{
position:relative;
width:30px;
height:37px}

.blockG{
position:absolute;
background-color:#FFFFFF;
width:5px;
height:12px;
-moz-border-radius:4px 4px 0 0;
-moz-transform:scale(0.4);
-moz-animation-name:fadeG;
-moz-animation-duration:0.8800000000000001s;
-moz-animation-iteration-count:infinite;
-moz-animation-direction:linear;
-webkit-border-radius:4px 4px 0 0;
-webkit-transform:scale(0.4);
-webkit-animation-name:fadeG;
-webkit-animation-duration:0.8800000000000001s;
-webkit-animation-iteration-count:infinite;
-webkit-animation-direction:linear;
-ms-border-radius:4px 4px 0 0;
-ms-transform:scale(0.4);
-ms-animation-name:fadeG;
-ms-animation-duration:0.8800000000000001s;
-ms-animation-iteration-count:infinite;
-ms-animation-direction:linear;
-o-border-radius:4px 4px 0 0;
-o-transform:scale(0.4);
-o-animation-name:fadeG;
-o-animation-duration:0.8800000000000001s;
-o-animation-iteration-count:infinite;
-o-animation-direction:linear;
border-radius:4px 4px 0 0;
transform:scale(0.4);
animation-name:fadeG;
animation-duration:0.8800000000000001s;
animation-iteration-count:infinite;
animation-direction:linear;
}

#rotateG_01{
left:0;
top:13px;
-moz-animation-delay:0.33s;
-moz-transform:rotate(-90deg);
-webkit-animation-delay:0.33s;
-webkit-transform:rotate(-90deg);
-ms-animation-delay:0.33s;
-ms-transform:rotate(-90deg);
-o-animation-delay:0.33s;
-o-transform:rotate(-90deg);
animation-delay:0.33s;
transform:rotate(-90deg);
}

#rotateG_02{
left:4px;
top:5px;
-moz-animation-delay:0.44000000000000006s;
-moz-transform:rotate(-45deg);
-webkit-animation-delay:0.44000000000000006s;
-webkit-transform:rotate(-45deg);
-ms-animation-delay:0.44000000000000006s;
-ms-transform:rotate(-45deg);
-o-animation-delay:0.44000000000000006s;
-o-transform:rotate(-45deg);
animation-delay:0.44000000000000006s;
transform:rotate(-45deg);
}

#rotateG_03{
left:13px;
top:1px;
-moz-animation-delay:0.55s;
-moz-transform:rotate(0deg);
-webkit-animation-delay:0.55s;
-webkit-transform:rotate(0deg);
-ms-animation-delay:0.55s;
-ms-transform:rotate(0deg);
-o-animation-delay:0.55s;
-o-transform:rotate(0deg);
animation-delay:0.55s;
transform:rotate(0deg);
}

#rotateG_04{
right:4px;
top:5px;
-moz-animation-delay:0.66s;
-moz-transform:rotate(45deg);
-webkit-animation-delay:0.66s;
-webkit-transform:rotate(45deg);
-ms-animation-delay:0.66s;
-ms-transform:rotate(45deg);
-o-animation-delay:0.66s;
-o-transform:rotate(45deg);
animation-delay:0.66s;
transform:rotate(45deg);
}

#rotateG_05{
right:0;
top:13px;
-moz-animation-delay:0.7700000000000001s;
-moz-transform:rotate(90deg);
-webkit-animation-delay:0.7700000000000001s;
-webkit-transform:rotate(90deg);
-ms-animation-delay:0.7700000000000001s;
-ms-transform:rotate(90deg);
-o-animation-delay:0.7700000000000001s;
-o-transform:rotate(90deg);
animation-delay:0.7700000000000001s;
transform:rotate(90deg);
}

#rotateG_06{
right:4px;
bottom:3px;
-moz-animation-delay:0.8800000000000001s;
-moz-transform:rotate(135deg);
-webkit-animation-delay:0.8800000000000001s;
-webkit-transform:rotate(135deg);
-ms-animation-delay:0.8800000000000001s;
-ms-transform:rotate(135deg);
-o-animation-delay:0.8800000000000001s;
-o-transform:rotate(135deg);
animation-delay:0.8800000000000001s;
transform:rotate(135deg);
}

#rotateG_07{
bottom:0;
left:13px;
-moz-animation-delay:0.99s;
-moz-transform:rotate(180deg);
-webkit-animation-delay:0.99s;
-webkit-transform:rotate(180deg);
-ms-animation-delay:0.99s;
-ms-transform:rotate(180deg);
-o-animation-delay:0.99s;
-o-transform:rotate(180deg);
animation-delay:0.99s;
transform:rotate(180deg);
}

#rotateG_08{
left:4px;
bottom:3px;
-moz-animation-delay:1.1s;
-moz-transform:rotate(-135deg);
-webkit-animation-delay:1.1s;
-webkit-transform:rotate(-135deg);
-ms-animation-delay:1.1s;
-ms-transform:rotate(-135deg);
-o-animation-delay:1.1s;
-o-transform:rotate(-135deg);
animation-delay:1.1s;
transform:rotate(-135deg);
}

@-moz-keyframes fadeG{
0%{
background-color:#473A47}

100%{
background-color:#FFFFFF}

}

@-webkit-keyframes fadeG{
0%{
background-color:#473A47}

100%{
background-color:#FFFFFF}

}

@-ms-keyframes fadeG{
0%{
background-color:#473A47}

100%{
background-color:#FFFFFF}

}

@-o-keyframes fadeG{
0%{
background-color:#473A47}

100%{
background-color:#FFFFFF}

}

@keyframes fadeG{
0%{
background-color:#473A47}

100%{
background-color:#FFFFFF}

}
</style>

<script>
$( document ).ready( function(){
	setTimeout(fade_out, 3000);
});

function fade_out() {
  $("#message_div").fadeOut();
}

function hide_message_div_manually(){
	$("#message_div").fadeOut();
}
</script>

<?php 
	if($this->Session->check("Message.flash")){ 
	//echo $_SESSION['meesage_type'];exit; 
	$message_class = '';
	if($_SESSION['meesage_type'] == 1){
		$message_class = 'message_divgreen';
	}else{
		$message_class = 'message_divred';
	}
	//$message_class = 'message_divgreen';
	unset($_SESSION['meesage_type']);
?>			
	<div class="<?php echo $message_class; ?>" id="message_div">
		<?php echo $this->Session->flash(); ?>
		<div class="close"><img src="<?php echo $this->webroot?>images/cross_icon.png" alt="" onClick="hide_message_div_manually();"/></div>
	</div>
	<div class="clr"></div>
<?php } ?>



</head>
<body>
<!--------------------------------------------------------------wrapper start -------->
<div id="wrapper">
<!--------------------------------------------------------------banner start -------->
	
    	<?php echo $this->element('home_header')?>
        
        
        
        <section class="body_part_404not">        	
        	<div class="container">				
				<div class="col-md-12">
					<div class="col-md-7 errormasgpart">
						<h2>Don't Worry you will be back to track in no time!</h2>
						<div class="eroormasmiddiv">
							<img src="<?php echo $this->webroot;?>images/404msgwright.png" alt="">
						</div>
						<h2>Page doesn't exit or some other error occured.Go to our <a href="<?php echo $this->webroot;?>">Home Page</a> or go back to <a href="<?php echo $_SERVER['HTTP_REFERER'];?>">Previous Page</a></h2>
					
					</div>
					
					<div class="col-md-5 mainleferorimg">
					
						<img src="<?php echo $this->webroot;?>images/404image.jpg" alt="">
					</div>
				</div>
                
                
            	<div class="clearfix"></div>
            </div>        	
        </section>
		
    
   
  
    
    
    <!--------------------------------------------------------------footer start -------->	
    
    <?php echo $this->element('home_footer')?>
	
	<!--------------------------------------------------------------footer end -------->	
	<div class="clearfix"></div>
</div>
	
<!--------------------------------------------------------------wrapper end -------->


<!--scroller script start-->



</body>
</html>
