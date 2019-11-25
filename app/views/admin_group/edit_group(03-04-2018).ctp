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
		var valid = jQuery("#edit_form").validationEngine('validate');
		if(valid == true){
			jQuery('#submt_butt').attr('disabled',true);
			jQuery('#edit_form').submit();
		}else{
			return false;
		}
	}

	function submit_form1(){

		window.location.href="<?php echo $this->webroot?>admin_group/group_list";
	}
</script>

<script>

  function ChangeGroup(group_type){
      //alert(group_type);
      
      
        if(group_type == 'B')
        {
          jQuery("#category").show();
          
        }
        else if(group_type == 'F')
        {
          jQuery("#category").hide();
          jQuery('#category_id').val('0');
         
        }
       
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
		 <form class="stdform ws-validate" id="edit_form" action="" method="post" enctype="multipart/form-data">
					
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

                  <div class="span6">

                 <p>
                      <label class="profilelabel">Group Type</label>
                       <select class="class-input  validate[required]" name="new_group_type" id="new_group_type" onChange="ChangeGroup(this.value);" >
                        <option value="">Select One</option>
                        <option value="B" <?php if($GroupDetails['Group']['group_type']=="B"){echo "selected='selected'";}?>>BUSINESS</option>
                        <option value="F" <?php if($GroupDetails['Group']['group_type']=="F"){echo "selected='selected'";}?>>PRIVATE</option>
                      </select>  
                       
                </p>
                
                <p id ="category" name ="category" <?php if($GroupDetails['Group']['group_type']=="F") { ?> style=display:none <?php } ?>>

                      <label class="profilelabel">Category</label>
                       <select class="class-input  validate[required]" name="category_id" id="category_id" >
                        <option value="0">Select One</option>
                        <?php 
                            if(isset($all_categories)) {
                               foreach($all_categories as $data1) { ?>
                  <option value="<?php echo $data1['Category']['id'];?>"  <?php if($GroupDetails['Group']['category_id'] == $data1['Category']['id']){ echo "selected=selected";}?>><?php echo $data1['Category']['title'];?></option>
                   <?php }}  ?>
                      </select>  
                       
                </p>
               
               
                
					          <p>
                      <label>Group Name</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="group_title" id="group_title" value="<?php echo stripslashes($GroupDetails['Group']['group_title']);?>"/>
                      </span>
                    </p>
                     <!--start-->
                    <p>
                      <label class="profilelabel">Group Description </label>
                      <span class="field">
                        <textarea class="input-large validate[required]" name="group_desc" id="group_desc" ><?php echo stripslashes($GroupDetails['Group']['group_desc']);?></textarea>
                       </span>
                    </p>
                     <!-- <p>
                      <label class="profilelabel">Group Purpose</label>
                      <span class="field">
                        <textarea class="input-large validate[required]" name="group_purpose" id="group_purpose" ><?php echo stripslashes($GroupDetails['Group']['group_purpose']);?></textarea>
                       </span>
                    </p> -->
                   <!--  <?php if($GroupDetails['Group']['group_type'] == 'B') { ?>
                    <p>
                      <label class="profilelabel">Choose Category</label>
                       <select class="class-input" name="category_id" id="category_id" >
                  <option value="">Select One</option>
                  <?php foreach($all_categories as $data1){?>
                  <option value="<?php echo $data1['Category']['id'];?>"  <?php if($GroupDetails['Group']['category_id'] == $data1['Category']['id']){ echo "selected=selected";}?>><?php echo $data1['Category']['title'];?></option>
                   <?php } ?>
                </select>  
                       
                    </p>
                    <?php } ?> -->
                <!-- end -->


                    
                    
                    <p class="stdformbutton">
                      <button class="btn btn-warning" type="button" id="submt_butt" onClick="Javascript: submit_form();">Update</button>
                      <button class="btn btn-warning" type="button" id="submt_butt1" onClick="Javascript: submit_form1();">Back</button>
                    </p>
                  </div>
                  <div class="span6">
				
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
        <div class="footerright">&copy; Copyrights 2014</div>
      </div><!--footer-->


    </div><!--mainwrapper-->

   
   
  </body>
</html>