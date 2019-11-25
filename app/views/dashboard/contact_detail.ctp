
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
								<h4><a href="#">Contact Details</a></h4>
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
						<h2><i class="fa fa-user manageprof"></i> Contact Details</h2>
						
						<div class="tabpart">
							<div id="horizontalTab">
								<ul class="resp-tabs-list">
								<li>Contact Details</li>
								
								</ul>
								<div class="resp-tabs-container">
									<div>
										<div class="manage-inner">
										<?php if ($this->Session->check('Message.flash')) : ?>
						                    <div style="color: #78b32b; font-size: 14px; margin-bottom: 10px;">
						                      <?php echo $this->Session->flash(); ?>
						                    </div>
						                  <?php endif; ?>	
										<h3>Edit Profile information</h3>
							    <p>To activate your listing. Please fill in all of the required fields below:</p>
							     <form action="" method="post" id="edit_form" name="edit_form">

								<div class="edit-info">
									<div class="row">
										<div class="clearfix"></div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">First Name *</label>
											<div class="profilefield">
												<input type="text" name="first_name" id="first_name" value="<?php echo stripslashes($UserDetails['User']['first_name']); ?>" class="profileform-control" required/>
											</div>
										</div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">Last Name *</label>
											<div class="profilefield">
												<input type="text" name="last_name" id="last_name" value="<?php echo stripslashes($UserDetails['User']['last_name']); ?>" class="profileform-control" required/>

											</div>
										</div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">Email Address *</label>
											<div class="profilefield">
											<input type="text" name="email" id="email" value="<?php echo $UserDetails['User']['email']; ?>" class="profileform-control validate[required,email]">	
											</div>
										</div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">Phone/Mobile No. </label>
											<div class="profilefield">
										<input type="text" name="phone_no" id="phone_no" value="<?php echo stripslashes($UserDetails['User']['phone_no']); ?>" class="profileform-control">
											</div>
										</div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">Address </label>
											<div class="profilefield">
											<input type="text" name="address" id="address" value="<?php echo stripslashes($UserDetails['User']['address']); ?>" class="profileform-control" >
											</div>
										</div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">City </label>
											<div class="profilefield">
											<input type="text" name="city" id="city" value="<?php echo stripslashes($UserDetails['User']['city']); ?>" class="profileform-control" >
											</div>
										</div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">State </label>
											<div class="profilefield">
												
											<input type="text" name="state" id="state" value="<?php echo stripslashes($UserDetails['User']['state']); ?>" class="profileform-control" >
											</div>
										</div>
										<div class="col-sm-6 fields-margin">
											<label class="profilelabel">Postal Code </label>
											<div class="profilefield">
											<input type="text" name="zipcode" id="zipcode" value="<?php echo $UserDetails['User']['zipcode']; ?>" class="profileform-control" >
											</div>
										</div>
										<div class="clearfix"></div>
										
										<div class="col-sm-12">
											
											<div class="profilefield11">
												<input type="submit" value="Save" class="savebutton"/>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
								</div>
								</form>
							</div>
									</div>
								<!-- 	<div>
										<div class="manage-inner">
								<h3>Upload Profile Pcture &amp; Company Logo </h3>
							    <p>Accepted file format:*jpg. gif and *png.File size sould be 550KB</p>
								<div class="edit-info">
									<div class="row">
										<div class="col-sm-8">
											<label class="profilelabel">Upload Profile Picture</label>
											<div class="profilefield">
												<input type="file"/>
												<div class="browseimgsection">
													<img src="images/member-img01.jpg" alt="">
													<div class="deletepart">
														<a href="#"><i class="fa fa-trash-o"></i></a>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-sm-12 mrtop">
											
											<div class="profilefield11">
												<input type="submit" value="Submit" class="savebutton"/>
												<div class="clearfix"></div>
											</div>
										</div>
										
										
									</div>
								</div>
							</div>
									</div> -->
							
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
