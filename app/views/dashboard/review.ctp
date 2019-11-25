<link href="<?php echo $this->webroot?>css/prettyCheckable.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $this->webroot?>css/easy-responsive-tabs.css">
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot ?>js/user_action.js" type="text/javascript"></script>

<!-- Rating Start -->
<link rel="stylesheet" href="<?php echo $this->webroot?>css/rating-min.css">
<script type="text/javascript" src="<?php echo $this->webroot?>js/tipi.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/rating.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/main2.js"></script>
<script type="text/javascript">
	$(document).ready(function (){
	   
        $('#example-inline').rating({
            inline: true,
            showLabel: false
        });
        $('#example-inline').change(function () {
            $('#rating_value_hidden').val($(this).rating('val'));
        });
     });
 
</script>

<!-- Rating End -->


<!-- Autocomplete Start -->
<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.tokeninput.js"></script>
 <link rel="stylesheet" href="<?php echo $this->webroot?>css/token-input-facebook.css" type="text/css" />
 
 <script>
 $(document).ready(function() {
	$("#demo-input-facebook-theme").tokenInput("<?php echo $this->webroot.'manage/get_user_list'?>", 
	{
		theme: "facebook",
		tokenLimit: 1,
		preventDuplicates: true
		
	});
});
 </script>
 
 <!-- Autocomplete End -->
 	
<script>
$(document).ready(function () {
$('#horizontalTab').easyResponsiveTabs({
type: 'default', //Types: default, vertical, accordion           
width: 'auto', //auto or any width like 600px
fit: true,   // 100% fit in a container
closed: 'accordion', // Start closed if in accordion view
activate: function(event) { // Callback function if tab is switched
var $tab = $(this);
var $info = $('#tabInfo');
var $name = $('span', $info);
$name.text($tab.text());
$info.show();
}
});
$('#verticalTab').easyResponsiveTabs({
type: 'vertical',
width: 'auto',
fit: true
});
});
</script>


 <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.tokeninput.js"></script>
 <link rel="stylesheet" href="<?php echo $this->webroot?>css/token-input-facebook.css" type="text/css" /> 
 

<!--<script>
jQuery(document).ready(function(){
  jQuery('#demo-input-facebook-theme').blur(function(){
    var instructor = jQuery('#demo-input-facebook-theme').val();
   if(instructor ==''){
      jQuery('#msg_err').addClass('show_err');
      jQuery('#msg_err').html('Please select an instructor or facility.');
      jQuery( "#submit_but" ).prop( "disabled", true );
    }else{
     
      jQuery( "#submit_but" ).prop( "disabled", false );
      return false;
    }
  });
});
</script>-->


	
	<!-- Modal -->
	
		
	<div class="clearfix"></div>	
	
	<section class="advance-serach">
		<div class="container">
			<div class="breadcrumbs">
				<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Search</a></li>
					<li>Search By Location</li>
				</ul>
			</div>
			<div class="serching-part">
				<div class="advance-serachleft">
					<div class="account-module">
						<h3>Account Menu</h3>
						<!-- <div class="accountlistpart">
							<div class="upperheading">
								<i class="fa fa-dashboard"></i>
								<h4>Dashboard</h4>
							</div>
							<ul>
								<li><a href="#">- Power Search</a></li>
							</ul>
						</div> -->
						
						<div class="accountlistpart">
							<div class="upperheading">
								<i class="fa fa-envelope"></i>
								<h4>Notifications</h4>
							</div>
							<ul>
								<li><a href="#">- Inbox</a></li>
								<li><a href="#">- Sent</a></li>
								<li><a href="#">- Trash</a></li>
							</ul>
						</div>

						 <div class="accountlistpart">
							<div class="upperheading">
								<i class="fa fa-dashboard"></i>
								<h4>Class</h4>
							</div>
							<ul>
								<li><a href="#">- Search</a></li>
								<li><a href="#">- My Favorites:</a></li>
							</ul>
						</div> 

						
						<div class="accountlistpart">
							<div class="upperheading">
								<i class="fa fa-user"></i>
								<h4><a href="<?php echo $this->webroot?>dashboard/contact_detail">Contact Details</a></h4>
							</div>
						</div>
					
						<div class="accountlistpart">
							<div class="upperheading">
								<i class="fa fa-star"></i>
								<h4><a href="#">Reviews</a></h4>
							</div>
						</div>
						
						<div class="accountlistpart">
							<div class="upperheading">
								<i class="fa fa-user"></i>
								<h4><a href="<?php echo $this->webroot?>dashboard/edit_photo">Profile Photo</a></h4>
							</div>
						</div>
						
						<div class="accountlistpart">
							<div class="upperheading">
								<i class="fa fa-cog"></i>
								<h4><a href="<?php echo $this->webroot?>dashboard/change_password">Change Password</a></h4>
							</div>
						</div>


						<div class="accountlistpart" style="border-bottom:none;">
							<div class="upperheading">
								<i class="fa fa-cog"></i>
								<h4><a href="<?php echo $this->webroot?>home/logout">Logout</a></h4>
							</div>
						</div>

					</div>
					
				<div class="clearfix"></div>	
				</div>
				<div class="advance-serachright">
					<div class="manage-profile">
						<h2><i class="fa fa-pencil manageprof"></i> Review </h2>
						
						<div class="tabpart">
							<div class="resp-tabs-container">
									<div>
										<div class="manage-inner">
										<?php if ($this->Session->check('Message.flash')) : ?>
						                    <div style="color: #78b32b; font-size: 14px; margin-bottom: 10px;">
						                      <?php echo $this->Session->flash(); ?>
						                    </div>
						                  <?php endif; ?>	
								<h3>Edit Review information</h3>
							    <p>To activate your listing. Please fill in all of the required fields below:</p>
							    <form action="" method="post" id="review_form" name="review_form">
								<div class="edit-info">
									<div class="row">

										<div class="col-sm-12 fields-margin">
											<label class="profilelabel-review">Instructor/Facility</label>
											<div class="profilefield">
												<input type="text" id="demo-input-facebook-theme"  value="" class="profileform-control" name="inst_fac_name" required='required'/>
												<!-- <input type="" id="user_detail" name="user_detail" value ="" /> -->
											</div>
										</div>

										<div class="col-sm-12 fields-margin">
											<label class="profilelabel-review">Enter a title for your review </label>
											<div class="profilefield">
												<input type="text" value="" class="profileform-control" id="title" name="title" required/>
											</div>
										</div>
										
										<div class="clearfix"></div>
										
										<div class="col-sm-12 fields-margin">
											<label class="profilelabel-review">Enter your review </label>
											<div class="profilefield">
												<textarea name="review" class="profileform-control msg" required> </textarea>
											</div>
										</div>
										
										<div class="col-sm-12 fields-margin">
											<label class="profilelabel-review">Would You Recommend this (Instructor/Facility)? </label>
											<div class="profilefield">
												<span><input name="recommend" type="radio" value="1"> <font>Yes</font></span>  <input name="recommend" type="radio" value="0"> No
											</div>
										</div>
										<div class="clearfix"></div>
										<br>

										<div class="col-sm-12 fields-margin">
											<label class="profilelabel-review">Enter your review *</label>
											<div class="profilefield">
											
												<input style="" id="example-inline" type="number" min="1" max="5" step="1">
												<input type="hidden" id="rating_value_hidden" name="rating_value" />
												

											</div>
										</div>
										<div class="clearfix"></div><br>


										
										<div class="col-sm-12">											
											<div class="profilefield11">
												<input type="submit" value="Submit" class="savebutton"/>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
								</div>
								</form>
							</div>
									</div>
									
								</div>
						</div>
					
					</div>
				<div class="clearfix"></div>	
				</div>				
			<div class="clearfix"></div>
			</div>
		<div class="clearfix"></div>
		</div>
	</section>
			
	
	<div class="clearfix"></div>


<script>
	$(document).ready(function() {
          //Set the carousel options
          $('#quote-carousel').carousel({
            pause: true,
            interval: 5000,
          });
    });
</script>


  <!-- JS Custom -->
   <script src="<?php echo $this->webroot?>js/prettyCheckable.js"></script>     
   <script src="<?php echo $this->webroot?>js/main.js"></script>
