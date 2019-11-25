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

    window.location.href="<?php echo $this->webroot?>admin_content/all_static_contents";
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
     <form class="stdform ws-validate" id="edit_form" action="" method="post">
        
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
                      <label>Title</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="title" id="title" value="<?php echo $detail['StaticContent']['title'];?>" required />
                      </span>
                    </p>
                     <!--start-->
                <p>
                      <label class="profilelabel">Content*</label>
                      <span class="field">
                        <textarea class="input-large validate[required]" name="content" id="content" ><?php echo $detail['StaticContent']['content'];?></textarea>
                       </span>
                    </p>
                    
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
        <div class="footerright">&copy; Copyrights 2017</div>
      </div><!--footer-->


    </div><!--mainwrapper-->

   
   
  </body>
</html>