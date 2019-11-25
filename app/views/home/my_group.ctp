<style>
   .error{
    color: #f00;
   }
   .fancybox-type-iframe .fancybox-inner{ height:450px!important;}
</style>
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<!-- Image Modal -->

  <main class="main-body">  
  <div class="groups-member">
    <div class="container" style="position:relative;">
     
      <div class="group-tab">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#free">Private Groop(s)</a></li>
          <li><a data-toggle="tab" href="#business">Business Groop(s)</a></li>
		  <li><a data-toggle="tab" href="#POG">Public Organization Groop(s)</a></li>
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
							<a href="<?php echo $this->webroot; ?>group/group_detail/<?php echo $list['Group']['id'];?>">
							  <div class="groupbox-img">
							   <?php if(!empty($list['Group']['icon'])){
									 if($list['Group']['parent_id']=='0'){
								?>
									<img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/>
								<?php	 
									 }
									 else{
								?>
									<img src="<?php echo $this->webroot.'sub_group_images/web/'.$list['Group']['icon'];?>" alt=""/>
								<?php	 
									 }
							   ?>
								<?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img_1.jpg" alt="" /> <?php } ?>
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
							  <div class="group-overlay">
							 
							 <?php if($GroupFreeUserType['GroupUser']['user_type'] == 'O'){ ?>
									<a href="<?php echo $this->webroot; ?>group/invite_list/<?php echo $list['Group']['id']; ?>" class="userbox group-btn invite">Invite </a>
							  <?php }  else if ($GroupFreeUserType['GroupUser']['user_type'] == 'M') {  ?> 
							  		<a href="<?php echo $this->webroot; ?>group/recommend_list/<?php echo $list['Group']['id']; ?>" class="userbox group-btn invite">Recommend </a>
							  <?php }  ?>
								
							  <?php $GroupFreeMemberCount = $this->requestAction('group/group_member_count/'.$list['Group']['id'].'/');?>
							  
							  <?php if($GroupFreeMemberCount == '1') { ?>
							  
							  <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo  $GroupFreeMemberCount ;?> user </a>
							  <?php } else { ?>
							  <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo  $GroupFreeMemberCount ;?> users </a>
				
							  <?php } ?>
							  </div>
							</div> 
							
							<div class="group-name">
							  <h4>
							  <?php echo ucfirst(stripslashes($list['Group']['group_title'])); ?>
							  </h4>
							</div> 
						  
						  </li>
						 <?php } ?> 
						</ul>
						<div class="clearfix"></div>
			   
					</div>
					
					<?php 
							//echo $lastpage_f.'---'.$page_f.'---'.$prev_f.'---'.$next_f.'---'.$lpm1_f.'---';
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
			<a href="<?php echo $this->webroot; ?>group/group_detail/<?php echo $list['Group']['id'];?>">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){
			   		 if($list['Group']['parent_id']=='0'){
				?>
					<img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/>
				<?php	 
					 }
					 else{
				?>
					<img src="<?php echo $this->webroot.'sub_group_images/web/'.$list['Group']['icon'];?>" alt=""/>
				<?php	 
					 }
			   ?>
                <?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img_1.jpg" alt="" /> <?php } ?>
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
              <div class="group-overlay">
             
             <?php if($GroupBusinessUserType['GroupUser']['user_type'] == 'O'){ ?>
              		<a href="<?php echo $this->webroot; ?>group/invite_list/<?php echo $list['Group']['id']; ?>" class="userbox group-btn invite">Invite </a>
              <?php }  else if ($GroupBusinessUserType['GroupUser']['user_type'] == 'M') {  ?> 
              <a href="<?php echo $this->webroot; ?>group/recommend_list/<?php echo $list['Group']['id']; ?>" class="userbox group-btn invite">Recommend </a>
              <?php } ?>
				
              <?php $GroupBusinessMemberCount = $this->requestAction('group/group_member_count/'.$list['Group']['id'].'/');?>
			  
              <?php if($GroupBusinessMemberCount == '1') { ?>
              
              <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo  $GroupBusinessMemberCount ;?> user </a>

              <?php } else { ?>
            
              <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo  $GroupBusinessMemberCount ;?> users </a>

              <?php } ?>
              </div>
            </div> 
            
            <div class="group-name">
              <h4>
			  	<?php echo ucfirst(stripslashes($list['Group']['group_title'])); ?>
			  </h4>
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
	   
	    <div class="clearfix"></div>
        
		<div id="POG" class="tab-pane fade">
             <?php if($all_PO_groups!=''){ ?>
             <div class="member-list">  
        <ul>
        <?php  
            foreach ($all_PO_groups as $list) { ?> 

          <li>
          
            <div class="groupbox">
			<div class="group-link">
			<a href="<?php echo $this->webroot; ?>group/group_detail/<?php echo $list['Group']['id'];?>">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){
			   		 if($list['Group']['parent_id']=='0'){
				?>
					<img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/>
				<?php	 
					 }
					 else{
				?>
					<img src="<?php echo $this->webroot.'sub_group_images/web/'.$list['Group']['icon'];?>" alt=""/>
				<?php	 
					 }
			   ?>
                <?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img_1.jpg" alt="" /> <?php } ?>
              </div>
			  
               <?php $GroupPOUserType = $this->requestAction('group/group_type/'.$list['Group']['id']);?>
			   
             <?php if($GroupPOUserType['GroupUser']['user_type'] == 'O') { ?>
              <div class="business-ribbon">
                <img src="<?php echo $this->webroot?>images/owner.png" alt="" />
              </div>
              <?php } ?>
			  
              <div class="group-maskbg"></div>
			  </a>
			  </div>
              <div class="group-overlay">
             
             <?php if($GroupPOUserType['GroupUser']['user_type'] == 'O'){ ?>
              		<a href="<?php echo $this->webroot; ?>group/invite_list/<?php echo $list['Group']['id']; ?>" class="userbox group-btn invite">Invite </a>
              <?php }  else if ($GroupPOUserType['GroupUser']['user_type'] == 'M') {  ?> 
              <a href="<?php echo $this->webroot; ?>group/recommend_list/<?php echo $list['Group']['id']; ?>" class="userbox group-btn invite">Recommend </a>
              <?php } ?>
				
              <?php $GroupPOMemberCount = $this->requestAction('group/group_member_count/'.$list['Group']['id'].'/');?>
			  
              <?php if($GroupPOMemberCount == '1') { ?>
              
              <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo  $GroupPOMemberCount ;?> user </a>

              <?php } else { ?>
            
              <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo  $GroupPOMemberCount ;?> users </a>

              <?php } ?>
              </div>
            </div> 
            
            <div class="group-name">
              <h4>
			  	<?php echo ucfirst(stripslashes($list['Group']['group_title'])); ?>
			  </h4>
            </div> 
          
          </li>
         <?php } ?> 
        </ul>
        <div class="clearfix"></div>
       
        </div>
		<?php 
				//echo $lastpage_po.'---'.$page_po.'---'.$prev_po.'---'.$next_po.'---'.$lpm1_po.'---';
				$paginationData_PO = paging_ajax($lastpage_po,$page_po,$prev_po,$next_po,$lpm1_po,"paging_PO"); 
		?>
	
		<script language="javascript">
		function paging_PO(val)
		{		
			// ajax json
			if(val!=''){
			$.ajax({
				   type: "GET",
				   url: WEBROOT+"home/my_group_po",
				   data: {page:val},
				   success: function(msg){
				   if(msg!=0){
						$('#POG').html(msg); 
					}
				   }
			  });
			}
		}
		</script>
	
		<?php			
			if(isset($paginationData_PO))
			{
				echo $paginationData_PO;
			}
		?>
		<form name="frm_act" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="page" value="">		
		</form>
           <?php } else { ?>           
        <div class="no-item-found">
        	No Public Organization groups found
        </div>        
        <?php } ?>   
		<!-- Shows the page numbers -->

       <div class="clearfix"></div> 	       
       </div>
   
<!--	<div class="pagination">
	
    </div>-->

        </div>
      </div>
      
    </div>
  </div>
    </main>
