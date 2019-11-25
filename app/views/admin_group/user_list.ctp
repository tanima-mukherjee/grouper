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
	  .modal{
		  width: 460px;	
		  margin-left: -230px;
	  }
	  .send-modaldoby textarea{
		  width: 95%;
		  padding: 5px 2.5%;
		  border: 1px solid #ccc;
		  height: 80px;
		  margin-bottom: 15px;
	  }
	  .send-modaldoby button{
		  background: #0d0cc5;
		  padding: 8px 25px;
		  color: #fff;
		  border-radius: 4px;
		  border: none;
		  font-size: 16px;
	  }
	  .send-modaldoby button:hover{
			background: #1e1c7b;		  
	  }	  
    </style>
    
    <!-- Extra Custom Scripts START -->

    <script type="text/javascript">
      jQuery(document).ready(function($) {
        //$('.check').click(function() {
		  $('#dyntable').on('click', '.check', function(){
          if($('.checkindividual:checked').length > 0)
            $('#deleteFacility').removeAttr('disabled');
          else
            $('#deleteFacility').attr('disabled', '');
          
          if($('.checkindividual:checked').length == $('.checkindividual').length)
            $('.checkall').attr('checked', true);
          else
            $('.checkall').attr('checked', false);
        });
        
        $('#deleteFacility').on('click', function() {
          var confirm = window.confirm('Are you sure you want to delete the Instructor(s)?');
          if(confirm) {
            var categoryIDArrInitial = '';
            $('.checkindividual:checked').each(function() {
              categoryIDArrInitial += $(this).val()+',';
            });
            var categoryIDArrFinal = categoryIDArrInitial.substring(0, categoryIDArrInitial.length-1);
            $.ajax({
              type: 'POST',
              url: '<?php echo $this->webroot; ?>admin_tasks/delete_instructors',
              data: {categoryIDArrFinal: categoryIDArrFinal},
              success: function(resp) {
                if(resp == 'ok')
                  window.location.reload();
              }
            });
          }
        });
    var WEBROOT = '<?php echo $this->webroot;?>';
    jQuery('#current_pwd').blur(function(){
    var current_pwd = jQuery('#current_pwd').val();
    var id_user = jQuery('#receiver_id').val();
    //alert(current_pwd);exit();
   if(current_pwd!=''){
      jQuery.ajax({
          type: "GET",
          url: WEBROOT+"users/check_password",
          data: {id_user: id_user, current_pwd: current_pwd},
          success: function(msg){ 
          //alert(msg);
          if(msg == '0'){
           // jQuery('#img_id').hide();
            jQuery('#msg_err').show();
            jQuery('#msg_err').addClass('show_err');
            jQuery('#msg_err').html('Sorry!! This is not your current password.');
            jQuery("#submit_but").prop( "disabled", true );
          }else{
            jQuery('#msg_err').hide();
           // jQuery('#img_id').show();
            jQuery( "#submit_but" ).prop( "disabled", false );
          }
         }
      });
    }else{
      jQuery('#msg_err').addClass('show_err');
      jQuery('#msg_err').html('Please enter your current password.');
      jQuery( "#submit_but" ).css('color','gray');
      jQuery( "#submit_but" ).prop( "disabled", true );
      return false;
    }
  });
      });

      function change_status(userID, userStatus) {
        jQuery.ajax({
          type: 'POST',
          url: '<?php echo $this->webroot; ?>users/change_user_status',
          data: {userID: userID, userStatus: userStatus},
          success: function(resp) {
            if(resp == 'ok')
              window.location.reload();
          }
        });
      }
      
    </script>
    <script>
jQuery(document).ready( function() {
      var validator = jQuery("#admin_message").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
        message :{
        required: true
         }
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
       messages: {
      message : {        
        required:" Please enter a message"
      }
      }
   });

     });

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
 </script>

  <script>
jQuery(document).ready( function() {
      var validator = jQuery("#update_password_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
     
      rules: {

          current_pwd :{
            required: true
          },
         
         new_pwd: {
            required: true
         },
         con_pwd: {
            equalTo: "#new_pwd"
         }
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
       
        messages: {
          current_pwd: {
            required: "Please enter current password"
         },
                 
         new_pwd: {
            required: "Please enter new password"
         },
         con_pwd: {
            equalTo: "Please enter the same as new password"
         }
      }
   });

     });
 </script>  
    <script type="text/javascript">
     jQuery(document).ready(function($) {
    $(document).on("click", ".open-AddBookDialog", function () {
      var id = $(this).data('id');
      $("#sendMessage #receiver_id").val(id);
      $("#changePassword #receiver_id").val(id);
    });
  });
</script>
<style>
   .error{
    color: #f00;
   }
   .fancybox-type-iframe .fancybox-inner{ height:450px!important;}
   .form-group{margin-bottom: 15px;}
   .form-control{width:94%; padding: 5px 3%!important; height: 22px!important;}
   .label-field{font-size: 15px; margin-bottom: 5px; color: #000;}
   .select-control{width: 220px!important; height:32px!important; padding:0 5px!important;}
 </style>
    <!-- Extra Custom Scripts END -->
  </head>

  <body>
  
<script>
function send_message(group_id) {
    var receiver_id = jQuery("#receiver_id").val();
    var message = jQuery("#message").val();
    if(message!='')
    {
    	jQuery('#msg_err').html('')
	    jQuery.ajax({
	      type: 'GET',
	      url: '<?php echo $this->webroot; ?>admin_group/send_message',
	      data: {receiver_id: receiver_id, message: message, group_id: group_id},
	      success: function(resp) {
	        if(resp == 'ok')
	          jQuery("#message").val('');
	          jQuery('#sendMessage').modal('hide');
	      }
	    });
	}else{
		jQuery('#msg_err').html('Please enter message');
	}
  }
</script>

  <!-- Modal -->
  <div class="modal fade" id="sendMessage" role="dialog">
    <div class="modal-dialog send-modal">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Send Message</h4>
        </div>
        <div class="modal-body">
			<div class="send-modaldoby">
       <form action="<?php echo $this->webroot?>users/send_message" method="post" id="admin_message" >
        <input type="hidden" class="form-control" id="receiver_id" name="receiver_id"/> 
				 <textarea id="message" name="message"></textarea>
				 <label id='msg_err'></label>
         <div class="clearfix"></div>    
				 <button type="button" onClick="send_message(<?php echo $group_id;?>)">Submit</button>
         </form>
			</div>
        </div>        
      </div>
      
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="changePassword" role="dialog">
    <div class="modal-dialog send-modal">
    <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Password</h4>
        </div>
        <div class="modal-body">
      <div class="send-modaldoby">
       <form action="<?php echo $this->webroot?>users/update_password" method="post" id="update_password_form" name="update_password_form" >
        <input type="hidden" class="form-control" id="receiver_id" name = "receiver_id" value=""/> 
		<div class="form-group">
			<label class="label-field">Current Password</label>
			<input type="text" class="form-control" id="current_pwd" name = "current_pwd" value=""/> 
      <div class="clearfix"></div>
       <div id="msg_err"></div>
		</div>
		<div class="form-group">
			<label class="label-field">New Password</label>
			<input type="text" class="form-control" id="new_pwd" name = "new_pwd" value=""/> 
      <div class="clearfix"></div>
		</div>
		<div class="form-group">
			<label class="label-field">Confirm Password</label>
			<input type="text" class="form-control" id="con_pwd" name = "con_pwd" value=""/> 
      <div class="clearfix"></div>
		</div>
        <!--  <textarea id="message" name = "message" ></textarea> -->
         <div class="clearfix"></div>    
         <button type="submit" id="submit_but" name="submit_but" >Update</button>
         </form>
      </div>
        </div>        
      </div>
      
    </div>
  </div>
  
    <div id="blanket" style="display:none;"></div>
    <div id="popUpDiv" style="display:none;">
      <div class="widgetcontent">
        <div class="widgetcontent">
          <h4 class="widgettitle">User Regions<a href="#" onClick="popup('popUpDiv')" style="margin-left:375px;"><i class="icon-remove-circle"></i></a></h4>
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
		
        <!-- <div class="pull-left">      
               <form action="<?php echo $this->webroot.'admin_user/user_list';?>" method="post" id="group_type_form" name="group_type_form" >  
                  
                   <select class="form-control select-control" name="state_id" id="state_id" onChange="CityList(this.value);">
                     <option value="">Select State</option>
                      <?php 
                        if(isset($ArState)){
                        foreach($ArState as $State){  ?>
                         <option value="<?php echo $State['State']['id'];?>" <?php if(($SelectedStateId)==($State['State']['id'])) { ?> selected='selected' <?php } ?> ><?php echo $State['State']['name'];?></option>
                     <?php } ?>
                    <?php } ?>
                   </select>  
                   
        <select class="form-control select-control" name="index_city_id" id="index_city_id" >
             
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
                                    
                </form>
	</div> -->
              <?php if($total_user !=''){?>
               <div class="pull-right total-user"> Total Count: <span class="user-count-no"><?php echo $total_user;?></span></div>
              <?php } ?>

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
                    <th class="head0">User Name</th>
                    <th class="head0">First Name</th>
                    <th class="head1">Last Name</th>
                    <th class="head0">Email</th>
                    <th class="head0">Password</th>
                    <!-- <th class="head1">Status</th> -->
                    <th class="head1">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($all_users)) : foreach($all_users as $user) : ?>
                    <tr class="gradeX" id="user_tr_<?php echo $user['User']['id']; ?>">
                      <td class="aligncenter">
                        <span class="center">
                          <input type="checkbox" class="check checkindividual" value="<?php echo $user['User']['id']; ?>" />
                        </span>
                      </td>
                      <td><?php echo $user['User']['username']; ?></td>
                      <td><?php echo $user['User']['fname']; ?></td>
                      <td><?php echo $user['User']['lname']; ?></td>
                      <td><?php echo $user['User']['email']; ?></td>
                      <td><?php echo $user['User']['txt_password']; ?></td>

                      
                      <td>
                      <a href="#" data-toggle="modal" data-target="#sendMessage" data-id="<?php echo $user['User']['id']?>" class="open-AddBookDialog" >Send Message</a>
                      </td>  

                  </td>
                  </td>
					</tr>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>
<script>
function delete_user(user_id)
{

    jQuery.ajax({
      type: 'GET',
      url: '<?php echo $this->webroot; ?>users/delete_user',
      data: {user_id: user_id},
      success: function(resp) {
        if(resp == '1')
          jQuery("#user_tr_"+user_id).remove();
      }
    });
}
</script>



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
        <div class="footerright">&copy; Copyrights 2014</div>
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