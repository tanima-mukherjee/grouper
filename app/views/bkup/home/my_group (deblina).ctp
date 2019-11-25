<script>
var WEBROOT = '<?php echo $this->webroot;?>';
function all_members(url){
	 jQuery.ajax({
		  type: "GET",
		  url: WEBROOT+"home/all_members",
		  data: {url:url},
		  success: function(response){
			//alert(response);
			jQuery("#group").hide();
			jQuery("#member").removeClass('fade');
			jQuery("#member").show();
			jQuery("#member").html(response);
		}
	});
}

function show_hide()
{
	$("#group").addClass('active');
	$("#group").show();
	$("#member").addClass('fade');
	$("#member").hide();
}
</script>


<?php 
	$page1 = 1;
	$total_record = 10;
	$limit = 2;
	$lastpage = ceil($total_record/$limit);
	$start=($page1 - 1) * $limit;
	$page = $page1;
	$prev = $page - 1;
	$next = $page + 1;
	$lpm1 = $lastpage - 1;
	
	$paginationData = paging_ajax($lastpage,$page,$prev,$next,$lpm1,$prefix,"paging"); 
	pr($paginationData);exit;
?>
<!--<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/ajaxresponse.js"></script>-->
<script language="javascript">
	function paging(val)
	{	
		alert(1);	
		//sendRequest("ajax/ajaxpaging_category.php","page="+val,"POST");
		//ajax
			jQuery.ajax({
			  type: "GET",
			  url: WEBROOT+"home/all_members",
			  data: {url:url},
			  success: function(response){
				//alert(response);
				jQuery("#group").hide();
				jQuery("#member").removeClass('fade');
				jQuery("#member").show();
				jQuery("#member").html(response);
			}
		});
	}
</script>

  <main class="main-body">  
  <div class="groups-member">
    <div class="container" style="position:relative;">
     
      <div class="group-tab">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="javascript:void(0)" onclick="show_hide();">My Group(s)</a></li>
          <!--<li class="active"><a data-toggle="tab" href="#group">My Group(s)</a></li>-->
          <!--<li><a data-toggle="tab" href="#member">Members of</a></li>-->
          <li><a data-toggle="tab" href="javascript:void(0)" onclick="all_members(<?php  pr($this->params['url']);?>)">Members of</a></li>
        </ul>
      </div>
      <div class="tab-content">
        <div id="group" class="tab-pane fade in active">
       
          <div class="member-list">
           <?php if(count($all_owner_groups)>0){ ?>
        <ul>
        <?php  
            foreach ($all_owner_groups as $list) {?> 

          <li>
          <a href="<?php echo $this->webroot.'group/group_detail/'.$list['Group']['id'];?>">
            <div class="groupbox">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){?>
             <img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> <?php } ?>
              </div>
              <?php if($list['Group']['group_type'] == 'B') { ?>
              <div class="business-ribbon">
                <img src="<?php echo $this->webroot?>images/business.png" alt="" />
              </div>
              <?php } ?>
              <div class="group-maskbg"></div>
              <div class="group-overlay">
             <?php $GroupType = $this->requestAction('group/group_type/'.$list['Group']['id'].'/');?>
             <?php if($GroupType['GroupUser']['user_type'] == 'O') { ?>
              <a href="#" class="group-btn invite">Invite </a>
              <?php }  else if ($GroupType['GroupUser']['user_type'] == 'M') {  ?> 
              <a href="#" class="group-btn invite">Recommend </a>
              <?php } else {?>
                <a href="#" class="group-btn invite">Join Now </a>
                <?php } ?>
              <?php $GroupMemberCount = $this->requestAction('group/group_member_count/'.$list['Group']['id'].'/');?>
               <?php if($GroupMemberCount == '1') { ?>
              <a href="#" class="group-btn view-user"><?php echo $GroupMemberCount ;?> user</a>
              <?php } else { ?>
              <a href="#" class="group-btn view-user"><?php echo $GroupMemberCount ;?> users</a>
              <?php } ?>
              </div>
            </div> 
            
            <div class="group-name">
              <h4><?php echo ucfirst($list['Group']['group_title'])?></h4>
            </div> 
             </a>          
          </li>
         <?php } ?> 
        </ul>
        <div class="clearfix"></div>
         <?php } else { ?>
        <div class="no-item-found">
        No owner groups found
        </div>
        <?php } ?>
        </div>
      
      <div class="clearfix"></div>  
      
      
			<?php /* ?><div class="pagination">
               <?php //echo $this->Paginator->counter(); ?>
                <?php
                $urlparams = $this->params['url'];
                //pr($urlparams);
                unset($urlparams['url']);
                //pr($urlparams); 
                //$paginator->options(array('url' => array('?' => http_build_query($urlparams))));
				$paginator->options(array('url' => array($this->passedArgs['0'], $this->passedArgs['1'], $this->passedArgs['2'], '?' => http_build_query($urlparams))));
                ?>
                <?php echo $this->Paginator->first(__('First', true), array('class' => 'disabled')); ?>
                <?php if ($this->Paginator->hasPage(2)) { ?>
                 

                <?php }
                ?>
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'numbers', 'first' => false, 'last' => false)); ?>
                <?php
                if ($this->Paginator->hasPage(2)) {
                  echo $this->Paginator->next(__(' ', true) . 'Next', array(), null, array('class' => 'disabled'));
                }
                ?>
                <?php echo $this->Paginator->last(__('Last', true), array('class' => 'disabled')); ?>
              </div><?php */ ?>
              
      </div>
        
    <div class="clearfix"></div>
   


        <div id="member" class="tab-pane fade">
            
         
        </div>
        
    <div class="clearfix"></div>



        </div>
      
      
      
      
      
      
      
      </div>
      
    </div>
  </div>
    </main>
