<link href="<?php echo $this->webroot?>css/prettyCheckable.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $this->webroot?>css/easy-responsive-tabs.css">
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<link href="<?php echo $this->webroot?>css/ui.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->webroot?>css/jquery-filestyle.min.css" rel="stylesheet">
<script src="<?php echo $this->webroot ?>js/instructor_action.js" type="text/javascript"></script>

 
 
 <!-- Autocomplete End -->
<script type="text/javascript">

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


		
	<div class="clearfix"></div>	
	
	<section class="advance-serach">
		<div class="container">
			<div class="breadcrumbs">
				<ul>
				</ul>
			</div>
			<div class="serching-part">
				<div class="advance-serachleft">
				 <?php echo $this->element('instructor/left_panel'); ?>	
				<div class="clearfix"></div>	
				</div>
				<div class="advance-serachright">
					<div class="manage-profile">
						<h2><i class="fa fa-suitcase manageprof"></i> Contact Us</h2>
						<div class="articles">
							<div class="article-inner">
								<?php if ($this->Session->check('Message.flash')) : ?>
						                    <div style="color: #78b32b; font-size: 14px; margin-bottom: 10px;">
						                      <?php echo $this->Session->flash(); ?>
						                    </div>
						                  <?php endif; ?>
							<form action=""  method="post" enctype="multipart/form-data" id="contact" name="contact">
								<div class="row">
								<div class="col-sm-12 fields-margin">
										<label class="profilelabel-review">Email </label>
										<div class="profilefield">
											<input type="text" value="" name="email" id="email" class="profileform-control" />
											<div class="clearfix"></div>	
										</div>
									</div>
									<div class="col-sm-12 fields-margin">
										<label class="profilelabel-review">Phone No. </label>
										<div class="profilefield">
											<input type="text" value="" name="phone" id="phone" class="profileform-control" />
											<div class="clearfix"></div>	
										</div>
									</div>
									<div class="col-sm-12 fields-margin">
										<label class="profilelabel-review">Subject </label>
										<div class="profilefield">
											<input type="text" value="" name="subject" id="subject" class="profileform-control" />
											<div class="clearfix"></div>	
										</div>
									</div>
																	
									<div class="col-sm-12 fields-margin">
										<label class="profilelabel-review"> Message </label>
										<div class="profilefield">
											<textarea class="profileform-control msg" name="message" id="message"> </textarea>
											<div class="clearfix"></div>	
										</div>
									</div>

															
									<div class="col-sm-12 fields-margin">											
										<div class="profilefield11 ">
											<input type="submit" value="Submit" class="savebutton"/>
											
											<div class="clearfix"></div>
										</div>
									</div>
										
								</div>
								</form>
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

 <script language="javascript" src="<?php echo $this->webroot?>js/jquery-ui.js"></script>
<!-- JS Custom -->
   <script src="<?php echo $this->webroot?>js/jquery-filestyle.min.js"></script>     





   