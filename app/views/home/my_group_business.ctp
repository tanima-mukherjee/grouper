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
 <div class="clearfix"></div>

	<?php 
	
	$paginationData = paging_ajax($lastpage,$page,$prev,$next,$lpm1,"paging"); 
	?>
	
	<script language="javascript">
	function paging(val)
	{		
		//sendRequest("ajax/ajaxpaging_category.php","page="+val,"POST");
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
				else{
					$('#success_msg').html('');	
					$('#success_msg').hide();
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
		
		