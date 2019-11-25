<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/style.default.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/prettify/prettify.css" type="text/css" />
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-ui-1.9.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/prettify/prettify.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/custom.js"></script>
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/excanvas.min.js"></script><![endif]-->
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
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
           <div class="product_list">

<div class="product_box_left">
<ul>
<li><h3>Event Owner Information</h3>
<ul class="boxlist">
<li>
<div class="box_right">
	<?php if(!empty($event_details['User']['image'])) {?>
  <img width="75" src="<?php echo $this->webroot.'user_images/thumb/'.$event_details['User']['image']; ?>">
  <?php }
  else {?>
   <img width="75" src="<?php echo $this->webroot.'images/no_profile_img.jpg'; ?>">
   <?php } ?>
<span style="font-size:14px; font-weight:bold; color:#000000;float:right; margin-left:10px; margin-top:10px;"></span></div>
</li>
<li><label>Email :</label><?php echo $event_details['User']['email']; ?></li>	
<li><label>First Name:</label><?php echo $event_details['User']['fname']; ?></li> 
<li><label>Last Name:</label><?php echo $event_details['User']['lname']; ?></li> 
<li><label>User Name:</label><?php echo $event_details['User']['username']; ?></li>	

</li>	
</ul>
</li>

<!----- location -------->



<li><h3>Action</h3>
<ul class="boxlist">
  <li>
  
    <?php if($search_by != ''){?>
      <form action="<?php echo $this->webroot.'admin_event/event_list';?>" method="post">
      <?php if($search_by == 'tsc'){?>
      <input type="hidden" name="state_id" value="<?php echo $state_id; ?>">
      <input type="hidden" name="index_city_id" value="<?php echo $city_id; ?>">
      <input type="hidden" name="group_type_select" value="<?php echo $groupt_select; ?>">
      <?php }else if($search_by == 'ts'){ ?>
      <input type="hidden" name="state_id" value="<?php echo $state_id; ?>">
      <input type="hidden" name="group_type_select" value="<?php echo $groupt_select;?>">
      <?php }else if($search_by == 'tc'){ ?>
      <input type="hidden" name="index_city_id" value="<?php echo $city_id; ?>">
      <input type="hidden" name="group_type_select" value="<?php echo $groupt_select; ?>">
      <?php }else if($search_by == 'sc'){ ?>
      <input type="hidden" name="state_id" value="<?php echo $state_id; ?>">
      <input type="hidden" name="index_city_id" value="<?php echo $city_id; ?>">
      <?php }else if($search_by == 't'){ ?>
      <input type="hidden" name="group_type_select" value="<?php echo $groupt_select; ?>">
      <?php }else if($search_by == 's'){ ?>
      <input type="hidden" name="state_id" value="<?php echo $state_id; ?>">
      <?php }else if($search_by == 'c'){ ?>
      <input type="hidden" name="index_city_id" value="<?php echo $city_id; ?>">
      <?php } ?>
      <input type="submit" value="Back" class="submit_btn">
      </form>
      <?php }else{ ?>
      <input type="button" onClick="history.go(-1)" value="Back" class="submit_btn" name="">
    <?php } ?>
  </li>
</ul>

</li>
</ul>

</div>


<div class="product_box_right">
<ul>
<!-- Seller Information -->
<li><h3>Event Details</h3>
<ul class="boxlist">
<li><label>Event Name:</label><?php echo($event_details['Event']['title']); ?></li>
<li><label>Event Description:</label><?php echo($event_details['Event']['desc']); ?></li>
<li><label>Event Location:</label><?php echo($event_details['Event']['location']);?></li>
<li><label>Event Type:</label><?php echo($event_details['Event']['type']); ?></li>
<?php if (($event_details['Event']['category_id'])!='0') { ?>

 <li><label>Category Name:</label><?php echo $event_details['Category']['title']; ?></li>
 
<?php } ?>  

<?php if(($event_details['Event']['group_type'])=='F') { ?>
  <li><label>Group Name:</label><?php echo($event_details['Group']['group_title']); ?></li>
 <li><label>Group Type:</label>FREE</li>

<?php } else if (($event_details['Event']['group_type'])=='B') { ?>
  <li><label>Group Name:</label><?php echo($event_details['Group']['group_title']); ?></li>
<li><label>Group Type:</label>BUSINESS</li>
<?php } ?>  

<?php if(($event_details['Event']['is_multiple_date'])=='0') { ?>
 <li><label> Time:</label><?php echo date("m-d-Y @h:i A",$event_details['Event']['event_timestamp']); ?></li>

<?php } else if (($event_details['Event']['is_multiple_date'])=='1'){ ?>

 <li><label>Start Time:</label><?php echo date("m-d-Y @h:i A",$event_details['Event']['event_start_timestamp']); ?></li>
  <li><label>End Time:</label><?php echo date("m-d-Y @h:i A", $event_details['Event']['event_end_timestamp']); ?></li>
<?php } ?>  

<?php if(($event_details['Event']['group_type'])=='B'){?>
<li><label>Deal Amount:</label>$<?php echo $event_details['Event']['deal_amount']; ?></li>
<?php } ?>

          
</ul>

</li>
<!-- Shop Information -->


</li>
</ul>
</div>

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
  </body>
</html>