<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/style.default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/prettify/prettify.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/prettify/prettify.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-ui-1.9.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/charCount.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/ui.spinner.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/forms.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.dataTables.min.js"></script>
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/excanvas.min.js"></script><![endif]-->
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    
	<script>var WEBROOT = '<?php echo $this->webroot?>';</script>
	<link rel="stylesheet" href="<?php echo $this->webroot?>admin/css/validationEngine.jquery.css" type="text/css"/>
	<script src="<?php echo $this->webroot?>admin/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
	</script>
	<script src="<?php echo $this->webroot?>admin/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
	
	<script>
	function submit_form(){
		var valid = jQuery("#add_form").validationEngine('validate');
		if(valid == true){
			jQuery('#submt_butt').attr('disabled',true);
			jQuery('#add_form').submit();
		}else{
			return false;
		}
	}

  function submit_form1(){

    window.location.href="<?php echo $this->webroot?>admin_territory/list_all";
  }


  function show_city(state_id) {
    //alert(state_id);
        jQuery.ajax({
          type: 'GET',
          url: '<?php echo $this->webroot; ?>admin_territory/show_city',
          data: {state_id: state_id},
          success: function(resp) {
            //alert(resp);
            jQuery("#city").html(resp);
          }
        });
      }
</script>
		
    <!-- webshim End -->

  </head>

  <body>

    <div class="mainwrapper fullwrapper" style="background-position: 0px 0px;">
      <?php echo $this->element('admin_left'); ?>
      <!-- START OF RIGHT PANEL -->
      <div class="rightpanel">
        <?php echo $this->element('admin_header'); ?>
        <div class="pagetitle">
          <h1><?php echo $pageTitle; ?></h1> <span></span>
        </div><!--pagetitle-->

        <div class="maincontent">
          <div class="contentinner content-dashboard">            	                
            <div class="row-fluid">
              <!--span8-->
              <div class="widgetcontent">
                <?php if ($this->Session->check('Message.flash')) : ?>
                  <div style="color: red; font-size: 14px; margin-bottom: 10px;">
                    <?php echo $this->Session->flash(); ?>
                  </div>
                <?php endif; ?>
                <form class="stdform ws-validate" id="add_form" action="" method="post" enctype="multipart/form-data">
                 
                  <div class="span6">

                    <p>
                      <label class="profilelabel">Photo</label>                      
                        <input type="file"  class="filestyle validate[required]" name="upload_image" id="upload_image" data-input="false" >                        
                    </p>
                   
                    <p>
                      <label class="profilelabel">&nbsp;</label>
                         <span>please upload an image of min 160 px X 120 px</span>
                    </p>


  					       <p>
                        <label>Name</label>
                        <span class="field">
                          <input type="text" class="input-large validate[required]" name="name"  id="name" value="" />
                        </span>
                  </p>

                <!--start-->
                    <p>
                        <label>Username</label>
                        <span class="field">
                          <input type="text" class="input-large validate[required]" name="username"  id="username" value="" />
                        </span>
                    </p>


                    <p>
                        <label>Email</label>
                        <span class="field">
                          <input type="text" class="input-large validate[required]" name="email"  id="email" value="" />
                        </span>
                    </p>


                  <p>
                        <label>Password</label>
                        <span class="field">
                          <input type="password" class="input-large validate[required]" name="password"  id="password" value="" /><span style="display: inline-block; background: #c8c9ff; margin-left: 5px; padding: 7px 10px 8px; color: #0d0f5b; font-size: 12px;">admin</span>

                          <input type='checkbox' id='toggle' value='0' onchange='togglePassword(this);'>&nbsp; <span id='toggleText'>Show</span>
                        </span>
                  </p>
                  
<script>
function togglePassword(el){
  var checked = el.checked;

  if(checked){

   document.getElementById("password").type = 'text';
   document.getElementById("toggleText").textContent= "Hide";

  }else{

   document.getElementById("password").type = 'password';
   document.getElementById("toggleText").textContent= "Show";

  }
}
</script>

                  <p>
                        <label>Phone Number</label>
                        <span class="field">
                          <input type="text" class="input-large validate[required]" name="phone_no"  id="phone_no" value="" />
                        </span>
                  </p>


                  <p>
                        <label>Address</label>
                        <span class="field">
                          <textarea class="input-large validate[required]" name="address"  id="address"></textarea>
                        </span>
                  </p>


                  <p>
                        <label>State</label>
                        <span class="field">
                          <select name="state" id="state" onchange="show_city(this.value)" class="input-large validate[required]">
                            <option value="">Select State</option>
                            <?php foreach($ArState as $State){ ?>
                            <option value="<?php echo $State['State']['id'];?>"><?php echo $State['State']['name'];?></option>
                            <?php } ?>
                          </select>
                        </span>
                  </p>


                  <p>
                        <label>City</label>
                        <span class="field">
                          <select name="city" id="city" class="input-large validate[required]">
                            <option value="">Select City</option>
                          </select>
                        </span>
                  </p>


                  <p>
                        <label>Zip</label>
                        <span class="field">
                          <input type="text" class="input-large validate[required]" name="zip"  id="zip" value="" />
                        </span>
                  </p>

                    
                <!-- end -->


                     <p class="stdformbutton">
                      <button class="btn btn-warning" type="button" id="submt_butt" onClick="Javascript: submit_form();">Save</button>
                      <button class="btn btn-warning" type="button" id="submt_butt1" onClick="Javascript: submit_form1();">Back</button>
                    </p>
				  </div>                                                    	                           
                </form>
              </div>
              <!--span4-->
            </div><!--row-fluid-->
          </div><!--contentinner-->
        </div><!--maincontent-->
      </div><!--mainright-->
      <!-- END OF RIGHT PANEL -->

      <div class="clearfix"></div>

      <div class="footer">
        <div class="footerleft">Ogma Conceptions</div>
        <div class="footerright">&copy; Copyrights 2016</div>
      </div><!--footer-->


    </div><!--mainwrapper-->

   
  </body>
</html>