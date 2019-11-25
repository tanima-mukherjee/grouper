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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>css/jquery.datetimepicker1.css"/>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.datetimepicker1.js"></script>



<!--date picker ends-->

 <!------------------------Auto tab functionality Starts----------------------> 
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.autotab.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$('#phn_no1, #phn_no2, #phn_no3').autotab_magic().autotab_filter('numeric');
});
</script>
<!------------------------Auto tab functionality Ends---------------------->
<!-- google geo complete starts -->

 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEvzyehZshLrLP25yXeGUfgxRqu8tsZ_U&libraries=places"></script> 

<script type="text/javascript">
    function initialize() {
        var input = document.getElementById('geocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function ()
         {
            var place = autocomplete.getPlace();
            //document.getElementById('city2').value = place.name;
            document.getElementById('settings_9').value = place.geometry.location.lat();
            document.getElementById('settings_10').value = place.geometry.location.lng();
           // document.getElementById('place_id').value = place.place_id;
            //alert("This function is working!");
            //alert(place.name);
           //alert(place.address_components[0].long_name);

        });
    }
    google.maps.event.addDomListener(window, 'load', initialize); 
</script>

<!-- getting lat long using onblur start -->
 <script>
function check_address(address){
  // alert(address);
      if(address == ''){
         $('#settings_9').val('');   
         $('#settings_10').val('');
        

      }
      else
      {
        var latitude = $('#settings_9').val();
        var longitude = $('#settings_10').val();
       
      }
}
</script> 


    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>

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
  </script> 
<script>
  function submit_form1(){

    window.location.href="<?php echo $this->webroot?>admin_sitesettings/edit_sitesettings";
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
     <form class="stdform ws-validate" id="edit_form" action="<?php echo $this->webroot?>admin_sitesettings/edit_sitesettings" method="post" enctype="multipart/form-data">
        
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
               
               
                    <p>
                      <label>Site Name</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="settings_1" id="settings_1" value="<?php echo $site_name ;?>"/>
                      </span>
                    </p>
                 
                     <p>
                      <label>Site Url</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="settings_2" id="settings_2" value="<?php echo $site_url;?>"/>
                      </span>
                    </p>
                     <p>
                      <label>Site Email</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="settings_3" id="settings_3" value="<?php echo $site_email;?>"/>
                      </span>
                    </p>
                     <p>
                      <label>Sender Email</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="settings_4" id="settings_4" value="<?php echo $sender_email;?>"/>
                      </span>
                    </p>
                     <p>
                      <label>Email Sender Name</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="settings_5" id="settings_5" value="<?php echo $email_sender_name;?>"/>
                      </span>
                    </p>
                     <p>
                      <label>Site Application Email</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="settings_6" id="settings_6" value="<?php echo $site_application_email;?>"/>
                      </span>
                    </p>
                     <p>
                      <label>ContactUs Phone</label>
                      <span class="field">
                        <input type="text" class="input-large validate[required]" name="settings_7" id="settings_7" value="<?php echo $contact_phone;?>"/>
                      </span>
                    </p>
                                   
                     <p>
                      <label>ContactUs Address</label>
                      <span class="field">
                       
                        <input id="geocomplete" type="text" placeholder="Type in an address" size="90" class="form-control validate[required]" name="settings_8" onBlur="check_address(this.value);" value="<?php echo $contact_address;?>"/>
                      </span>
                    </p>
                     <input type="hidden" class="input-large validate[required]" name="settings_9" id="settings_9" value="<?php echo $contact_latitude;?>"/>
                     <input type="hidden" class="input-large validate[required]" name="settings_10" id="settings_10" value="<?php echo $contact_longitude;?>"/>
                     
                   
                    
                    <p class="stdformbutton">
                      <button class="btn btn-warning" type="button" id="submt_butt" onClick="Javascript: submit_form();">Update</button>
                     
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


    </div><!--mainwrapper-->

   
   
  </body>
</html>