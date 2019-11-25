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
					
				<div class="group-tab">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#free">Private Group(s)</a></li>
          <li><a data-toggle="tab" href="#business">Business Group(s)</a></li>
          <li><a data-toggle="tab" href="#po">Public Organization Group(s)</a></li>
        </ul>
      </div>
      <div class="tab-content">
        <div id="free" class="tab-pane fade in active">
       			
        		<?php if($all_free_groups!=''){ ?>
					 <div class="member-list">  
						<ul>
						<?php  
							foreach ($all_free_groups as $list) { ?> 
				
						  <li>
						  
							<div class="groupbox">
							<div class="group-link">
							<a href="<?php echo $this->webroot; ?>admin_group/view_group/<?php echo $list['Group']['id'];?>">
							  <div class="groupbox-img">
							   <?php if(!empty($list['Group']['icon'])){?>
							   <img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img_1.jpg" alt="" /> <?php } ?>
							  </div>
							  
							   <?php $GroupFreeUserType = $this->requestAction('group/group_type/'.$list['Group']['id']);?>
							   
							   <?php if($GroupFreeUserType['GroupUser']['user_type'] == 'O') { ?>
							   <div class="business-ribbon">
								 <img src="<?php echo $this->webroot?>images/owner.png" alt="" />
							   </div>
							   <?php } ?>
							  <div class="group-maskbg"></div>
							  </a>
							  </div>
							 
							</div> 
							
							<div class="group-name">
							  <h4><?php echo ucfirst(stripslashes($list['Group']['group_title']))?></h4>
							</div> 
						  
						  </li>
						 <?php } ?> 
						</ul>
						<div class="clearfix"></div>
			   
					</div>
					
					<?php 
							$paginationData_f = paging_ajax($lastpage_f,$page_f,$prev_f,$next_f,$lpm1_f,"paging_f"); 
					?>
				
					<script language="javascript">
					function paging_f(val)
					{		
						// ajax json
						if(val!=''){
						$.ajax({
							   type: "GET",
							   url: WEBROOT+"home/my_group_free",
							   data: {page:val},
							   success: function(msg){
							   if(msg!=0){
									$('#free').html(msg); 
								}
							   }
						  });
						}
					}
					</script>
				
					<?php			
						if(isset($paginationData_f))
						{
							echo $paginationData_f;
						}
					?>
					<form name="frm_act" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
						<input type="hidden" name="mode" value="">
						<input type="hidden" name="page" value="">		
					</form>

           		<?php } else { ?>           
					<div class="no-item-found">
						No Free groups found
					</div>        
				<?php } ?>   
				<!-- Shows the page numbers -->

       			<div class="clearfix"></div> 
      	</div>
        
    	<div class="clearfix"></div>
   


        <div id="business" class="tab-pane fade">
             <?php if($all_business_groups!=''){ ?>
             <div class="member-list">  
        <ul>
        <?php  
            foreach ($all_business_groups as $list) { ?> 

          <li>
          
            <div class="groupbox">
			<div class="group-link">
			<a href="<?php echo $this->webroot; ?>admin_group/view_group/<?php echo $list['Group']['id'];?>">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){?>
               <img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img_1.jpg" alt="" /> <?php } ?>
              </div>
			  
               <?php $GroupBusinessUserType = $this->requestAction('group/group_type/'.$list['Group']['id']);?>
			   
             <?php if($GroupBusinessUserType['GroupUser']['user_type'] == 'O') { ?>
              <div class="business-ribbon">
                <img src="<?php echo $this->webroot?>images/owner.png" alt="" />
              </div>
              <?php } ?>
			  
              <div class="group-maskbg"></div>
			  </a>
			  </div>
              
            </div> 
            
            <div class="group-name">
              <h4><?php echo ucfirst(stripslashes($list['Group']['group_title']))?></h4>
            </div> 
          
          </li>
         <?php } ?> 
        </ul>
        <div class="clearfix"></div>
       
        </div>
		<?php 
				$paginationData = paging_ajax($lastpage_b,$page_b,$prev_b,$next_b,$lpm1_b,"paging"); 
		?>
	
		<script language="javascript">
		function paging(val)
		{		
			// ajax json
			if(val!=''){
			$.ajax({
				   type: "GET",
				   url: WEBROOT+"home/my_group_business",
				   data: {page:val},
				   success: function(msg){
				   if(msg!=0){
						$('#business').html(msg); 
					}
				   }
			  });
			}
		}
		</script>
	
		<?php			
			if(isset($paginationData))
			{
				echo $paginationData;
			}
		?>
		<form name="frm_act" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="page" value="">		
		</form>
           <?php } else { ?>           
        <div class="no-item-found">
        	No Business groups found
        </div>        
        <?php } ?>   
		<!-- Shows the page numbers -->

       <div class="clearfix"></div> 	       
       </div>













<div id="po" class="tab-pane fade">
             <?php if($all_po_groups!=''){ ?>
             <div class="member-list">  
        <ul>
        <?php  
            foreach ($all_po_groups as $list) { ?> 

          <li>
          
            <div class="groupbox">
      <div class="group-link">
      <a href="<?php echo $this->webroot; ?>admin_group/view_group/<?php echo $list['Group']['id'];?>">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){?>
               <img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img_1.jpg" alt="" /> <?php } ?>
              </div>
        
               <?php $GroupBusinessUserType = $this->requestAction('group/group_type/'.$list['Group']['id']);?>
         
             <?php if($GroupBusinessUserType['GroupUser']['user_type'] == 'O') { ?>
              <div class="business-ribbon">
                <img src="<?php echo $this->webroot?>images/owner.png" alt="" />
              </div>
              <?php } ?>
        
              <div class="group-maskbg"></div>
        </a>
        </div>
              
            </div> 
            
            <div class="group-name">
              <h4><?php echo ucfirst(stripslashes($list['Group']['group_title']))?></h4>
            </div> 
          
          </li>
         <?php } ?> 
        </ul>
        <div class="clearfix"></div>
       
        </div>
    <?php 
        $paginationData = paging_ajax($lastpage_po,$page_po,$prev_po,$next_po,$lpm1_po,"paging"); 
    ?>
  
    <script language="javascript">
    function paging(val)
    {   
      // ajax json
      if(val!=''){
      $.ajax({
           type: "GET",
           url: WEBROOT+"home/my_group_po",
           data: {page:val},
           success: function(msg){
           if(msg!=0){
            $('#po').html(msg); 
          }
           }
        });
      }
    }
    </script>
  
    <?php     
      if(isset($paginationData))
      {
        echo $paginationData;
      }
    ?>
    <form name="frm_act" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
      <input type="hidden" name="mode" value="">
      <input type="hidden" name="page" value="">    
    </form>
           <?php } else { ?>           
        <div class="no-item-found">
          No Business groups found
        </div>        
        <?php } ?>   
    <!-- Shows the page numbers -->

       <div class="clearfix"></div>          
       </div>
















        
   
<!--	<div class="pagination">
	
    </div>-->

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