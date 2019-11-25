             <?php if(count($all_member_groups)>0){?>
            
             <div class="member-list">
             
			<ul>
			<?php  
				foreach ($all_member_groups as $list) {?> 
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
				  <h4><?php echo ucfirst(stripslashes($list['Group']['group_title']))?></h4>
				</div> 
				 </a>          
			  </li>
			 <?php } ?> 
			</ul>
        <div class="clearfix"></div>
       
        </div>

           <?php } else { ?>           
        <div class="no-item-found">
        No member groups found
        </div>        
        <?php } ?>   
         <div class="clearfix"></div> 
         
         
           <div class="pagination">
               <?php //echo $this->Paginator->counter(); ?>
                <?php
             //   $urlparams = $this->params['url'];
                //pr($urlparams);exit;
          //      unset($urlparams['url']);
                //pr($urlparams); 
                //$paginator->options(array('url' => array('?' => http_build_query($urlparams))));
        //$paginator->options(array('url' => array($this->passedArgs['0'], $this->passedArgs['1'], $this->passedArgs['2'], '?' => http_build_query($urlparams))));
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
              </div>
              
          
       
          
      
