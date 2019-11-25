
 	<link href="<?php echo $this->webroot?>css/prettyCheckable.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo $this->webroot?>css/easy-responsive-tabs.css">
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot ?>js/user_action.js" type="text/javascript"></script>


	
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

<script>
jQuery(document).ready(function(){
  jQuery('#current_pwd').blur(function(){
    var cuurent_pwd = jQuery('#current_pwd').val();
   if(cuurent_pwd!=''){
      jQuery.ajax({
				  type: "GET",
				  url: WEBROOT+"dashboard/check_password",
				  data: {
							cuurent_pwd : cuurent_pwd
						},
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
      //jQuery( "#submit_but" ).css('color','gray');
      jQuery( "#submit_but" ).prop( "disabled", true );
      return false;
    }
  });
});
</script>



	
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
								<h4><a href="<?php echo $this->webroot?>dashboard/review">Reviews</a></h4>
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
								<h4><a href="#">Change Password</a></h4>
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
						<h2><i class="fa fa-user manageprof"></i> Change Password</h2>
						
						<div class="tabpart">
							<div id="horizontalTab">
								<ul class="resp-tabs-list">
								<li> Change Password </li>
								
								</ul>
								<div class="resp-tabs-container">
									<div>
										<div class="manage-inner">
										<?php if ($this->Session->check('Message.flash')) : ?>
						                    <div style="color: #78b32b; font-size: 14px; margin-bottom: 10px;">
						                      <?php echo $this->Session->flash(); ?>
						                    </div>
						                  <?php endif; ?>	
										<h3>Edit Profile Password</h3>

							    <p>To change your password.Please fill in all of the required fields below:</p>
							     
							     <form action="" method="post" name="change_password_form" id="change_password_form">

								<div class="edit-info">
									<div class="row">
										<div class="clearfix"></div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">Current Password *</label>
											<div class="profilefield">
												<input type="password" name="current_pwd" id="current_pwd" class="profileform-control"/>
												<img src="<?php echo $this->webroot;?>images/tick.jpg" alt="right" id="img_id" height="16px;" width="16px;" style="display: none;">
											</div>
											<div class="clearfix"></div>
                     					     <div id="msg_err"></div>
										</div>

										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">New Password *</label>
											<div class="profilefield">
												 <input type="password" name="new_pwd" id="new_pwd" class="profileform-control">
										    </div>
										    
                        					  <div class="clearfix"></div>
										</div>
										<div class="col-sm-12 fields-margin">
											<label class="profilelabel">Confirm New Password * </label>
											<div class="profilefield">
											<input type="password" name="con_pwd" id="con_pwd" class="profileform-control" >		
                         					</div>
                         					 <div class="clearfix"></div>
										</div>
									
										<div class="clearfix"></div>
										
										<div class="col-sm-12">
											
											<div class="profilefield11">
												<input type="submit" class="savebutton" value="SAVE" id="submit_but"/>
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
