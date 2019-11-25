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

    window.location.href="<?php echo $this->webroot?>admin_category/category_list";
  }


 
</script>

<script>
function search_state(state){


  jQuery("#city_list").show("");
  jQuery("#state_ids").val('');
  jQuery("#city_ids").val('');
  var territory_id = <?php echo $territory_id;?>;
  
  if(state!="" && state.length>3){
    
    jQuery("#main_name_search").show("");
    jQuery("#main_name_search").html("<img style='margin-left:30%;' src='<?php echo $this->webroot;?>images/ajax_loader-2.gif'>");
    
    jQuery.ajax({
      type: "GET",
      url: "<?php echo $this->webroot?>admin_territory/search_state/",
      data: { state: state, territory_id: territory_id},
      success: function(msg)
      {       
        
        jQuery("#main_name_search").html(msg);
                        
      }
    });
  }else{
    jQuery("#main_name_search").html("");  
  }
}


function filter_city(city_name){


  var city_ids = jQuery("#city_ids").val();
  //alert(city_ids);

  if(city_name!="" && city_name.length>1){
    
    jQuery("#city_list").show("");
    jQuery("#city_list").html("<img style='margin-left:30%;' src='<?php echo $this->webroot;?>images/ajax_loader-2.gif'>");
    
    var state_id = jQuery("input[name='state']:checked"). val();

    jQuery.ajax({
      type: "GET",
      url: "<?php echo $this->webroot?>admin_territory/filter_city/",
      data: { state_id: state_id, city_name: city_name, city_ids: city_ids},
      success: function(msg)
      {       
        //alert(msg);
        jQuery("#city_list").html(msg);
                        
      }
    });
  }else{
    //jQuery("#city_list").html("");  
  }
}



function save_assign_territory(city_name){


    var state_id = jQuery("#state_ids"). val();
    var city_ids = jQuery("#city_ids"). val();
    var territory_id = <?php echo $TerritoryDetail['Admin']['id'];?>

    jQuery.ajax({
      type: "GET",
      url: "<?php echo $this->webroot?>admin_territory/save_assign_territory/",
      data: { territory_id: territory_id, state_id: state_id, city_ids: city_ids},
      success: function(msg)
      {       
        
        //alert(msg);
        jQuery("#search_by_state").val('');
        jQuery("#search_by_city").val('');
        jQuery("#state").val('');
        jQuery("#city").val('');
        jQuery("#state_ids").val('');
        jQuery("#city_ids").val('');
        jQuery("#main_name_search").html('');
        jQuery("#city_list").html('');
        jQuery("#show_assign_list").html(msg);
        jQuery(".accordion-toggle").addClass('collapsed');
      }
    });
}


function delete_state(assign_state_id, state_id){

var territory_id = <?php echo $TerritoryDetail['Admin']['id'];?>;
//alert(territory_id);
  jQuery.ajax({
        type: "GET",
        url: "<?php echo $this->webroot?>admin_territory/delete_state/",
        data: { territory_id: territory_id, state_id: state_id},
        success: function(msg)
        {       
            jQuery("#state_div_"+assign_state_id).remove();
        }
    });
}

function delete_city(city_id, assign_city_id, territory_id){



  jQuery.ajax({
        type: "GET",
        url: "<?php echo $this->webroot?>admin_territory/delete_city/",
        data: { assign_city_id: assign_city_id },
        success: function(msg)
        {       
            jQuery("#city_div_"+city_id).remove();
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
                <input type="hidden" name="state_ids" id="state_ids" value="">
                <input type="hidden" name="city_ids" id="city_ids" value="">



				<div class="span6">                 
           
                    <div class="clearfix"></div>
					<div class="row-fluid">
						<div class="span6 assign-box">
							<div class="search-box-top">
								<input type="text" class="form-control" id="search_by_state" placeholder="Search State..." onKeyUp="search_state(this.value)"/>
							</div>
							<div class="search-list" id="main_name_search">
								
							</div>
						</div>
						<div class="span6 assign-box">
							<div class="search-box-top">
								<input type="text" class="form-control" id="search_by_city" placeholder="Search City..." onKeyUp="filter_city(this.value);"/>
							</div>
							<div class="search-list" id="city_list">
								
							</div>
						</div>
					</div>
                <!-- end -->


                     <p class="stdformbutton">
                      <button class="btn btn-warning" type="button" id="submt_butt" onClick="save_assign_territory();">Submit</button>
                      
                    </p>
					
					
          <div id="show_assign_list">
            <?php foreach($TerritoryAssign as $Assign){?>
                <div class="accordion-group" id="state_div_<?php echo $Assign['TerritoryAssign']['id'];?>">
            <div class="accordion-heading">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#State_<?php echo $Assign['TerritoryAssign']['id'];?>">
            <span class="collapse-icon"></span> <?php echo $Assign['State']['name'];?>							
            </a>
            <span class="remove-position">
            <a href="javascript:void(0)" onclick="delete_state(<?php echo $Assign['TerritoryAssign']['id'];?>,<?php echo $Assign['State']['id'];?>)"><img src="<?php echo $this->webroot; ?>images/remove-round.png" alt="" /> </a>
            </span>
            </div>
            <div id="State_<?php echo $Assign['TerritoryAssign']['id'];?>" class="accordion-body collapse">
            <div class="accordion-inner">
            <div class="search-listitem">
            <?php 
            $ArCityList = $this->requestAction('admin_territory/getCityList/'.$Assign['TerritoryAssign']['territory_id'].'/'.$Assign['State']['id']);
            //pr( $ArCityList);
            ?>
            <ul>
            <?php foreach($ArCityList as $City){?>
            <li id="city_div_<?php echo $City['City']['id'];?>"><?php echo $City['City']['name'];?>
            <a href="javascript:void(0)" onclick="delete_city(<?php echo $City['City']['id'];?>,<?php echo $City['TerritoryAssign']['id'];?>,<?php echo $City['TerritoryAssign']['territory_id'];?>)"><img src="<?php echo $this->webroot; ?>images/city-remove.png" alt="" /></a></li>
            <?php } ?>
            </ul>
            </div>
            </div>
            </div>
            </div>
            <?php } ?>
					</div>
					
						

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