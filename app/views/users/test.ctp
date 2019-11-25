
 	<link href="<?php echo $this->webroot?>css/prettyCheckable.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo $this->webroot?>css/easy-responsive-tabs.css">
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<!--<script src="<?php echo $this->webroot ?>js/instructor_action.js" type="text/javascript"></script>-->


	
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
	$(function() {
	
	$.validator.addMethod('isGreater', function(value, element, param) {
		  return this.optional(element) || value >= $(param).val();
	}, 'End time should be greater than start time');

    var validator = $("#workhour").submit(function() {
			// update underlying textarea before submit validation
		}).validate({
		rules: {
		
			monday_start: { required: function(element) { return $("#monday").val() == "1"; } },
			monday_end: { 
						required: function(element) { return $("#monday").val() == "1"; } ,
						isGreater: '#monday'			
			}

			
			
        
        },
		errorPlacement: function(label, element) {
				// position error label after generated textarea
				
					label.insertAfter(element.next());
				
			},
		messages: {
			
			
			
			monday_start: "Please select monday start time",
			monday_end: {
					required:"Select a monday end time",
					isGreater:"Monday end time should be greater than start time"
			}
             }
		
	});
});
</script>
<script >
		function getDayStatus(status_id,day){
		if(status_id != '' ){
		    if(status_id == '1')
		    {
              	$('#'+day+'_start').show();
             	$('#'+day+'_end').show();
			     
		    }
		    else 
		    {
             	$('#'+day+'_start').hide();
             	$('#'+day+'_end').hide();
			   
		    }
		}
	}
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
								<h4><a href="<?php echo $this->webroot?>instructor/profile">Profile</a></h4>
							</div>
						</div>

						<div class="accountlistpart">
							<div class="upperheading">
								<i class="fa fa-star"></i>
								<h4><a href="#">Work Hour</a></h4>
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
						<h2><i class="fa fa-user manageprof"></i>Work Hour</h2>
						
						<div class="tabpart">
							<div id="horizontalTab">
								<ul class="resp-tabs-list">
								<li>Work Hour</li>
								
								
								</ul>
								<div class="resp-tabs-container">
									<div>
												<div class="manage-inner">
												<?php if ($this->Session->check('Message.flash')) : ?>
						                    <div style="color: #78b32b; font-size: 14px; margin-bottom: 10px;">
						                      <?php echo $this->Session->flash(); ?>
						                    </div>
						                  <?php endif; ?>	
								<h3>What hours do you work?</h3>
								<form action="" method="post" id="workhour" name="workhour">
								<div class="edit-info">
									<div class="row">
										
										<?php
										$start=strtotime('00:00');
										$end=strtotime('23:30');
										?>

										<div class="clearfix"></div>
										
										
										<div class="col-sm-12">
											<label class="profilelabel">Monday *</label>
											<div class="profilefield">
												<div class="row">
												<div class="col-sm-4">
													<select class="profileform-control" name="monday"id="monday" onChange="getDayStatus(this.value,'monday');">
														<option value="0">Close</option>
														<option value="1">Open</option>
													</select>
													<br>
												</div>
												<div class="col-sm-4 padding1">
												<!-- <select class="profileform-control" id="mon1_id" disabled="disabled"> -->
													<select class="profileform-control" id="monday_start" style="display: none" name="monday_start">
													<option value="" selected>Start Time</option>
														<?php  
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													</select>
													<br>
												</div>
												<div class="col-sm-4 padding1">
													<select class="profileform-control" id="monday_end"  style="display: none" name="monday_end">
													<option value="" selected>End Time</option>
														<?php  
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													
													</select>
													<br>
												</div>
											<div class="clearfix"></div>	
											</div>	
											</div>
										</div>
										<div class="col-sm-12">
											<label class="profilelabel">Tuesday *</label>
											<div class="profilefield">
												<div class="row">
												<div class="col-sm-4">
													<select class="profileform-control" name="tuesday" id="tuesday" onChange="getDayStatus(this.value,'tuesday');">
													
														<option value="0">Close</option>
														<option value="1">Open</option>
													</select>
												</div>
												<div class="col-sm-4 padding1">												
													<select class="profileform-control" id="tuesday_start" name="tuesday_start" style="display: none">
													<option value="" selected>Start Time</option>

												<?php  
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													</select>
													<br>
												</div>
												<div class="col-sm-4 padding1">
													<select class="profileform-control" id="tuesday_end" name="tuesday_end" style="display: none" >
														<option value="" selected>End Time</option>
													<?php 
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													
													</select>
													<br>
												</div>
											<div class="clearfix"></div>	
											</div>	
											</div>
										</div>
										<div class="col-sm-12">
											<label class="profilelabel">Wednesday *</label>
											<div class="profilefield">
												<div class="row">
												<div class="col-sm-4">
													<select class="profileform-control" name="wednesday" id="wednesday" onChange="getDayStatus(this.value,'wednesday');">
													
														<option value="0">Close</option>
														<option value="1">Open</option>
														
													</select>
												</div>
												<div class="col-sm-4 padding1">												
													<select class="profileform-control" id="wednesday_start" name="wednesday_start" style="display: none">
													<option value="" selected>Start Time</option>
	
												<?php  
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													</select>
													<br>
												</div>
												<div class="col-sm-4 padding1">
													<select class="profileform-control" id="wednesday_end" name="wednesday_end" style="display: none" >
													<option value="" selected>End Time</option>
													<?php 
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													
													</select>
													<br>
												</div>
											<div class="clearfix"></div>	
											</div>	
											</div>
										</div>
										<div class="col-sm-12">
											<label class="profilelabel">Thursday *</label>
											<div class="profilefield">
												<div class="row">
												<div class="col-sm-4">
													<select class="profileform-control" name="thursday" id="thursday" onChange="getDayStatus(this.value,'thursday');">
													
														<option value="0">Close</option>
														<option value="1">Open</option>
														
													</select>
												</div>
												<div class="col-sm-4 padding1">												
													<select class="profileform-control" id="thursday_start" name="thursday_start" style="display: none">
													<option value="" selected>Start Time</option>

												<?php  
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													</select>
													<br>
												</div>
												<div class="col-sm-4 padding1">
													<select class="profileform-control" id="thursday_end" name="thursday_end" style="display: none" >
														<option value="" selected>End Time</option>
													<?php 
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													
													</select>
													<br>
												</div>
											<div class="clearfix"></div>	
											</div>	
											</div>
										</div>
										<div class="col-sm-12">
											<label class="profilelabel">Friday *</label>
											<div class="profilefield">
												<div class="row">
												<div class="col-sm-4">
													<select class="profileform-control" name="friday" id="friday" onChange="getDayStatus(this.value,'friday');">
													
														<option value="0">Close</option>
														<option value="1">Open</option>
														
													</select>
												</div>
												<div class="col-sm-4 padding1">												
													<select class="profileform-control" id="friday_start" name="friday_start" style="display: none">
													<option value="" selected>Start Time</option>

												<?php  
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													</select>
													<br>
												</div>
												<div class="col-sm-4 padding1">
													<select class="profileform-control" id="friday_end" name="friday_end" style="display: none" >
														<option value="" selected>End Time</option>
													<?php 
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													
													</select>
													<br>
												</div>
											<div class="clearfix"></div>	
											</div>	
											</div>
										</div>
										<div class="col-sm-12">
											<label class="profilelabel">Saturday *</label>
											<div class="profilefield">
												<div class="row">
												<div class="col-sm-4">
													<select class="profileform-control" name="saturday" id="saturday" onChange="getDayStatus(this.value,'saturday');">
													
														<option value="0">Close</option>
														<option value="1">Open</option>
														
													</select>
												</div>
												<div class="col-sm-4 padding1">												
													<select class="profileform-control" id="saturday_start" name="saturday_start" style="display: none">
													<option value="" selected>Start Time</option>
	
												<?php  
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													</select>
													<br>
												</div>
												<div class="col-sm-4 padding1">
													<select class="profileform-control" id="saturday_end" name="saturday_end" style="display: none" >
													<option value="" selected>End Time</option>
													<?php 
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													
													</select>
													<br>
												</div>
											<div class="clearfix"></div>	
											</div>	
											</div>
										</div>
										<div class="col-sm-12">
											<label class="profilelabel">Sunday *</label>
											<div class="profilefield">
												<div class="row">
												<div class="col-sm-4">
													<select class="profileform-control" name="sunday" id="sunday" onChange="getDayStatus(this.value,'sunday');">
													
														<option value="0">Close</option>
														<option value="1">Open</option>
													</select>
												</div>
												<div class="col-sm-4 padding1">												
													<select class="profileform-control" id="sunday_start" name="sunday_start" style="display: none">
													<option value="" selected>Start Time</option>
	
												<?php  
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													</select>
													<br>
												</div>
												<div class="col-sm-4 padding1">
													<select class="profileform-control" id="sunday_end" name="sunday_end" style="display: none" >
													<option value="" selected>End Time</option>
													<?php 
													 for ($i=$start;$i<=$end;$i = $i + 30*60)
													  {?><option value="<?php echo $i + 30*60;?>"><?php echo date('g:i A',$i);?></option>
													<?php }?>
													
													</select>
													<br>
												</div>
											<div class="clearfix"></div>	
											</div>	
											</div>
										</div>
										
																		
										<div class="col-sm-12">											
											<div class="profilefield11">
												<div class="rightbutton">
												
												<input type="submit" value="Save" class="savebutton"/>
												</div>
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

   <script>
  function showMyLogo(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil1");
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

