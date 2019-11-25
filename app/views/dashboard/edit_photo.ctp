 <script src="<?php echo $this->webroot?>js/easy-responsive-tabs.js"></script>
 	<link href="<?php echo $this->webroot?>css/prettyCheckable.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo $this->webroot?>css/easy-responsive-tabs.css">

	
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
								<h4><a href="#">Profile Photo</a></h4>
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
						<h2><i class="fa fa-user manageprof"></i> Profile Photo</h2>
						
						<div class="tabpart">
							<div id="horizontalTab">
								<ul class="resp-tabs-list">
								<li>Profile Photo</li>
								
								</ul>
								<div class="resp-tabs-container">
									
									<div>
										<div class="manage-inner">
										<?php if ($this->Session->check('Message.flash')) : ?>
						                    <div style="color: #78b32b; font-size: 14px; margin-bottom: 10px;">
						                      <?php echo $this->Session->flash(); ?>
						                    </div>
						                  <?php endif; ?>	
								<h3>Upload Profile Picture &amp; Company Logo </h3>
							    <p>Accepted file format:*jpg. gif and *png.File size sould be 550KB</p>
								<div class="edit-info">
									<div class="row">
									<!-- <form action="<?php echo $this->webroot;?>home/edit_photopost" method="post" enctype="multipart/form-data" name="frmsharephoto" id="frmsharephoto" > -->
									 <form action="<?php echo $this->webroot;?>dashboard/edit_photo" method="post" enctype="multipart/form-data" name="edit_photo" id="edit_photo">  
										<div class="col-sm-8">
											<label class="profilelabel">Upload Profile Picture</label>
											 <div class="profilefield">
											  <input type="file" onChange="showMyImage(this)" class="filestyle" name="profile_image" id="image" data-input="false" >
												<div class="browseimgsection">
												<?php if($UserDetails['User']['image']!=''){?>
												<img id="thumbnil" src="<?php echo $this->webroot;?>profile_images/big/<?php echo $UserDetails['User']['image'];?>" alt="">
												
												<?php }else{ ?>
												<img id="thumbnil" src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="">
													<?php } ?>
													<div class="deletepart">
														<a href="<?php echo $this->webroot;?>dashboard/deleteimage"><i class="fa fa-trash-o"></i></a>
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
										</form>										
									</div>
								</div>
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
<script>
  function showMyImage(fileInput) {
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
</script>

<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery-filestyle.min.js"></script>
<script>
            $(":file").filestyle({input: false});
</script>