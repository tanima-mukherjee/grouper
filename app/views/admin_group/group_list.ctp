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
    <style>
      #blanket {
        background-color:#111;
        opacity: 0.65;
        filter:alpha(opacity=65);
        position:absolute;
        z-index: 9001;
        top:0px;
        left:0px;
        width:100%;
      }
      #popUpDiv {
        position:absolute;
        background-color:#eeeeee;
        width:500px;
        height:300px;
        z-index: 9002;
        border-radius:5px;
      }
    </style>
    
    <!-- Extra Custom Scripts START -->
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        //$('.check').click(function() {
		$('#dyntable').on('click', '.check', function(){
          if($('.checkindividual:checked').length > 0)
            $('#deleteGroup').removeAttr('disabled');
          else
            $('#deleteGroup').attr('disabled', '');
          
          if($('.checkindividual:checked').length == $('.checkindividual').length)
            $('.checkall').attr('checked', true);
          else
            $('.checkall').attr('checked', false);
        });
        
        $('#deleteGroup').on('click', function() {
          var confirm = window.confirm('Are you sure you want to delete the Groop(s)?');
          if(confirm) {
            var groupIDArrInitial = '';
            $('.checkindividual:checked').each(function() {
              groupIDArrInitial += $(this).val()+',';
            });
            var groupIDArrFinal = groupIDArrInitial.substring(0, groupIDArrInitial.length-1);

            //alert(groupIDArrFinal);
            $.ajax({
              type: 'GET',
              url: '<?php echo $this->webroot; ?>admin_group/delete_group',
              data: {groupIDArrFinal: groupIDArrFinal},
              success: function(resp) {
                if(resp == 'ok')
                  window.location.href = "<?php echo $this->webroot; ?>admin_group/group_list/<?php echo time();?>";

              }
            });
          }
        });
      });

      function change_status(userID, userStatus) {
        jQuery.ajax({
          type: 'POST',
          url: '<?php echo $this->webroot; ?>admin_group/change_group_status',
          data: {userID: userID, userStatus: userStatus},
          success: function(resp) {
            if(resp == 'ok')
              window.location.reload();
          }
        });
      }

      function CityList(state_id){
      
      if(state_id != ''){
         jQuery.ajax({
            type: "GET",
            url: '<?php echo $this->webroot; ?>home/admin_show_index_city',
            data: {state_id:state_id},
            success: function(msg){
            jQuery("#index_city_id").html(msg);
           
            
            }
        });
      }
    }

      function change_is_featured(userID, userFeatured) {
        jQuery.ajax({
          type: 'POST',
          url: '<?php echo $this->webroot; ?>admin_group/change_group_is_featured',
          data: {userID: userID, userFeatured: userFeatured},
          success: function(resp) {
            if(resp == 'ok')
              window.location.reload();
          }
        });
      }
      
    </script>
    <!-- Extra Custom Scripts END -->
  </head>

  <body>
    <div id="blanket" style="display:none;"></div>
    <div id="popUpDiv" style="display:none;">
      <div class="widgetcontent">
        <div class="widgetcontent">
          <h4 class="widgettitle">Category Regions<a href="#" onClick="popup('popUpDiv')" style="margin-left:375px;"><i class="icon-remove-circle"></i></a></h4>
          <form class="stdform" action="#" method="post">                       

            <div class="span10">

              <p>
                <label>All Regions</label>
                <span class="field"> <input type="checkbox" name="input4" class="input-large"  /></span>
              </p>
              <p>
                <label>Select Region</label>
                <span class="field"> <input type="text" name="input4" class="input-large" placeholder="Type for Hints" /></span>
              </p>
              <p>
                <label>Selected Region(s):
                </label>
                <span class="field">

                </span>                        	
              </p>



            </div>

            <div class="span6">

              <p class="stdformbutton">
                <button class="btn btn-warning">Save</button>
                <button class="btn btn-warning">Cancel</button>
              </p>

            </div>                                                    	                           
          </form>
        </div>
      </div>

    </div>
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
              <?php if ($this->Session->check('Message.flash')) : ?>
                <div style="color: #78b32b; font-size: 14px; margin-bottom: 10px;">
                  <?php echo $this->Session->flash(); ?>
                </div>
              <?php endif; ?>
              <div class="clearfix"></div>     
			<div class="pull-left">
               <form action="<?php echo $this->webroot.'admin_group/group_list';?>" method="post" id="group_type_form" name="group_type_form" >  
                  
                   <select class="form-control" name="state_id" id="state_id" onChange="CityList(this.value);">
                     <option value="">Select State</option>
                      <?php 
                        if(isset($ArState)){
                        foreach($ArState as $State){  ?>
                         <option value="<?php echo $State['State']['id'];?>" <?php if(($SelectedStateId)==($State['State']['id'])) { ?> selected='selected' <?php } ?> ><?php echo $State['State']['name'];?></option>
                     <?php } ?>
                    <?php } ?>
                   </select>  
                   
        <select class="form-control" name="index_city_id" id="index_city_id" >
             
             <option value="">Select City</option>
               <?php 
               
                foreach($city_list as $City){?>
              <option value="<?php echo $City['City']['id'];?>" <?php if(($SelectedCityId)==($City['City']['id'])) { ?> selected='selected' <?php } ?>  ><?php echo $City['City']['name'];?></option>
              <?php } 
            
          ?>
        </select>     
  
                    <div class="group-type-submit" style="display:inline-block; position:relative;">
                      <button class="btn btn-warning" type="submit">Filter</button>
                    </div>
                   <button class="btn btn-warning" id="deleteGroup" disabled>Delete</button>
                  
                </form>
				</div>
				<div class="pull-right total-user">
                <?php if($total_business_group !=''){?>
               <div class="inline-text"> Business Group Count: <span class="user-count-no"><?php echo $total_business_group;?></span></div>
              <?php } ?>

              <?php if($total_private_group !=''){?>
               <div class="inline-text"> Private Group Count: <span class="user-count-no"><?php echo $total_private_group;?></span></div>
              <?php } ?>

              <?php if($total_private_organisation_group!=''){?>
            <div class="inline-text"> Private Organisation Group Count: <span class="user-count-no"><?php echo $total_private_organisation_group;?></span></div>
          <?php } ?>
			</div>
			<div class="clearfix"></div>
              <div>&nbsp;</div>                 
			
              <table class="table table-bordered" id="dyntable">
                <colgroup>
                  <col class="con0" style="align: center; width: 4%" />
                  <col class="con1" />
                  <col class="con0" />
                  <col class="con1" />
                  <col class="con0" />
                  <col class="con1" />
                </colgroup>
                <thead>
                  <tr>
                    <th class="head0 nosort"><input type="checkbox" class="checkall check" /></th>
                    <th class="head0">Groop Name</th>
                    <th class="head0">Groop Desc</th>
                    <th class="head0"> # of groop members</th> 
                    <th class="head0">Created By</th>
                    <th class="head0">Groop Type</th>
                    <th class="head0">Category Name</th>
                    <th class="head0">Groop State</th>
                    <th class="head0">Groop City</th>
                    <th class="head1">Status</th>
                    <th class="head1">Is Featured</th>
                    <th class="head1">Action</th>
                    <th class="head1">View Groop</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($all_groups)) : foreach($all_groups as $groups) : ?>
                    <tr class="gradeX">
                      <td class="aligncenter">
                        <span class="center">
                          <input type="checkbox" class="check checkindividual" value="<?php echo $groups['Group']['id']; ?>" />
                        </span>
                      </td>
                      <td><?php echo stripslashes($groups['Group']['group_title']); ?></td>
                      <td><?php echo stripslashes($groups['Group']['group_desc']); ?></td>
                      <?php $group_mem_count = $this->requestAction('admin_group/group_mem_count/'.$groups['Group']['id']);?>
                      <td><?php echo $group_mem_count; ?></td>
                      
                      <td><?php echo ucfirst($groups['User']['fname']).' '.ucfirst($groups['User']['lname']);?></td>
                      <?php if($groups['Group']['group_type'] == 'F') { ?>
                      <td>PRIVATE</td>
                      <td>No Category</td>
                      <?php } else if($groups['Group']['group_type'] == 'B') { ?>
                        <td>BUSINESS</td>
                      <td><?php echo stripslashes($groups['Category']['title']); ?></td>
                      <?php } else if($groups['Group']['group_type'] == 'PO') { ?>
                        <td>PUBLIC ORGANIZATION</td>
                      <td><?php echo stripslashes($groups['Category']['title']); ?></td>
                      <?php } 
					    else if($groups['Group']['group_type'] == '') { ?>
                        <td>NA</td>
                      <td>NA</td>
                      <?php } ?>

                      <td><?php echo stripslashes($groups['State']['name']); ?></td>
                      <td><?php echo stripslashes($groups['City']['name']); ?></td>

                      <td><a href="javascript:void(0)" onClick="change_status(<?php echo $groups['Group']['id']; ?>, '<?php echo $groups['Group']['status']; ?>');"><?php if($groups['Group']['status'] == '1') echo 'Active'; else echo 'Inactive'; ?></a></td>
                      <?php if($groups['Group']['group_type'] == 'F') { ?>
                      	<td>Not Applicable</td> 
                      	<?php } else { ?>
                       <td><a href="javascript:void(0)" onClick="change_is_featured(<?php echo $groups['Group']['id']; ?>, '<?php echo $groups['Group']['is_featured']; ?>');"><?php if($groups['Group']['is_featured'] == '1') echo 'Yes'; else echo 'No'; ?></a></td> 
						<?php } ?>
                      <td>
                  
                        <a href="<?php echo $this->webroot; ?>admin_group/edit_group/<?php echo $groups['Group']['id']; ?>">Edit</a> 
                                    
                  </td>
                   <td>
                  
                        <a href="<?php echo $this->webroot; ?>admin_group/view_group/<?php echo $groups['Group']['id']; ?>">View</a> 
                                    
                  </td>
                  </td>
					</tr>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>

              <div class="divider15"></div>                                             
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
    <script type="text/javascript">
      jQuery(document).ready(function () {

        // basic chart
        var flash = [[0, 2], [1, 6], [2, 3], [3, 8], [4, 5], [5, 13], [6, 8]];
        var html5 = [[0, 5], [1, 4], [2, 4], [3, 1], [4, 9], [5, 10], [6, 13]];

        function showTooltip(x, y, contents) {
          jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5
          }).appendTo("body").fadeIn(200);
        }


        var plot = jQuery.plot(jQuery("#chartplace2"),
                [{data: flash, label: "Leave Applied", color: "#fb6409"}, {data: html5, label: "Leave Not Applied", color: "#096afb"}], {
          series: {
            lines: {show: true, fill: true, fillColor: {colors: [{opacity: 0.05}, {opacity: 0.15}]}},
            points: {show: true}
          },
          legend: {position: 'nw'},
          grid: {hoverable: true, clickable: true, borderColor: '#ccc', borderWidth: 1, labelMargin: 10},
          yaxis: {min: 0, max: 15}
        });

        var previousPoint = null;
        jQuery("#chartplace2").bind("plothover", function (event, pos, item) {
          jQuery("#x").text(pos.x.toFixed(2));
          jQuery("#y").text(pos.y.toFixed(2));

          if (item) {
            if (previousPoint != item.dataIndex) {
              previousPoint = item.dataIndex;

              jQuery("#tooltip").remove();
              var x = item.datapoint[0].toFixed(2),
                      y = item.datapoint[1].toFixed(2);

              showTooltip(item.pageX, item.pageY,
                      item.series.label + " of " + x + " = " + y);
            }

          } else {
            jQuery("#tooltip").remove();
            previousPoint = null;
          }

        });

        jQuery("#chartplace2").bind("plotclick", function (event, pos, item) {
          if (item) {
            jQuery("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
            plot.highlight(item.series, item.datapoint);
          }
        });


        // bar graph
        var d2 = [];
        for (var i = 0; i <= 10; i += 1)
          d2.push([i, parseInt(Math.random() * 30)]);

        var stack = 0, bars = true, lines = false, steps = false;
        jQuery.plot(jQuery("#bargraph2"), [d2], {
          series: {
            stack: stack,
            lines: {show: lines, fill: true, steps: steps},
            bars: {show: bars, barWidth: 0.6}
          },
          grid: {hoverable: true, clickable: true, borderColor: '#bbb', borderWidth: 1, labelMargin: 10},
          colors: ["#06c"]
        });

        // calendar
        jQuery('#calendar').datepicker();


      });
    </script>

    <script>
      function toggle(div_id) {
        var el = document.getElementById(div_id);
        if (el.style.display == 'none') {
          el.style.display = 'block';
        }
        else {
          el.style.display = 'none';
        }
      }
      function blanket_size(popUpDivVar) {
        if (typeof window.innerWidth != 'undefined') {
          viewportheight = window.innerHeight;
        } else {
          viewportheight = document.documentElement.clientHeight;
        }
        if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
          blanket_height = viewportheight;
        } else {
          if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
            blanket_height = document.body.parentNode.clientHeight;
          } else {
            blanket_height = document.body.parentNode.scrollHeight;
          }
        }
        var blanket = document.getElementById('blanket');
        blanket.style.height = blanket_height + 'px';
        var popUpDiv = document.getElementById(popUpDivVar);
        popUpDiv_height = blanket_height / 2 - 150;//150 is half popup's height
        popUpDiv.style.top = popUpDiv_height + 'px';
      }
      function window_pos(popUpDivVar) {
        if (typeof window.innerWidth != 'undefined') {
          viewportwidth = window.innerHeight;
        } else {
          viewportwidth = document.documentElement.clientHeight;
        }
        if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
          window_width = viewportwidth;
        } else {
          if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
            window_width = document.body.parentNode.clientWidth;
          } else {
            window_width = document.body.parentNode.scrollWidth;
          }
        }
        var popUpDiv = document.getElementById(popUpDivVar);
        window_width = window_width / 2 - 150;//150 is half popup's width
        popUpDiv.style.left = window_width + 'px';
      }
      function popup(windowname) {
        blanket_size(windowname);
        window_pos(windowname);
        toggle('blanket');
        toggle(windowname);
      }
    </script>
  </body>
</html>