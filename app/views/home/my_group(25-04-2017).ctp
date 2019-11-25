<!-- Image Modal -->

 
 
  <main class="main-body">  
  <div class="groups-member">
    <div class="container" style="position:relative;">
     
      <div class="group-tab">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#free">Free Group(s)</a></li>
          <li><a data-toggle="tab" href="#business">Business Group(s)</a></li>
        </ul>
      </div>
      <div class="tab-content">
        <div id="free" class="tab-pane fade in active">
       
        <div class="member-list">
        <?php if(count($all_free_groups)>0){ ?>
        <ul>
        <?php  
            foreach ($all_free_groups as $list) {?> 

          <li>
          <a href="<?php echo $this->webroot.'group/group_detail/'.$list['Group']['id'];?>">
            <div class="groupbox">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){?>
             <img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img.jpg" alt="" /> <?php } ?>
              </div>
			  
              <?php $GroupUserType = $this->requestAction('group/group_type/'.$list['Group']['id']); // Check the User's position over Group
			  
			  ?>
              <?php if($GroupUserType['GroupUser']['user_type'] == 'O') { ?>
              <div class="business-ribbon">
                <img src="<?php echo $this->webroot?>images/owner.png" alt="" />
              </div>
              <?php } ?>
              <div class="group-maskbg"></div>
			  
              <div class="group-overlay">
              <?php if($GroupUserType['GroupUser']['user_type']=='O') { ?>
              		<a href="#" class="group-btn invite">Invite </a>
              <?php }  
			  		else if ($GroupUserType['GroupUser']['user_type'] == 'M') {  
			  ?> 
              		<a href="#" class="group-btn invite">Recommend </a>
              <?php } else { ?>
                	<a href="#" class="group-btn invite">Join Now </a>
              <?php } ?>
			  
              <?php $GroupMemberCount = $this->requestAction('group/group_member_count/'.$list['Group']['id']);?>
              <?php if($GroupMemberCount == '1'){ ?>
               
              		<a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo  $GroupMemberCount ;?> user </a>

               
              <?php } else { ?>
                
              <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo  $GroupMemberCount ;?> users </a>
			  
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
        No Free groups found
        </div>
        <?php } ?>
        </div>
      
      <div class="clearfix"></div>  
      <div class="pagination">
                <?php //echo $this->Paginator->counter(); ?>
                <?php
                $urlparams = $this->params['url'];
                //pr($urlparams);
                unset($urlparams['url']);
                //pr($urlparams); 
                $paginator->options(array('url' => array('?' => http_build_query($urlparams))));
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
     
      </div>
        
    	<div class="clearfix"></div>
   


        <div id="business" class="tab-pane fade">
             <?php if(count($all_business_groups)>0){ ?>
             <div class="member-list">  
        <ul>
        <?php  
            foreach ($all_business_groups as $list) { ?> 

          <li>
          <a href="<?php echo $this->webroot.'group/group_detail/'.$list['Group']['id'];?>">
            <div class="groupbox">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){?>
               <img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img.jpg" alt="" /> <?php } ?>
              </div>
			  
               <?php $GroupBusinessUserType = $this->requestAction('group/group_type/'.$list['Group']['id']);?>
			   
             <?php if($GroupBusinessUserType['GroupUser']['user_type'] == 'O') { ?>
              <div class="business-ribbon">
                <img src="<?php echo $this->webroot?>images/owner.png" alt="" />
              </div>
              <?php } ?>
			  
              <div class="group-maskbg"></div>
              <div class="group-overlay">
             
             <?php if($GroupBusinessUserType['GroupUser']['user_type'] == 'O'){ ?>
              		<a href="#" class="group-btn invite">Invite </a>
              <?php }  else if ($GroupBusinessUserType['GroupUser']['user_type'] == 'M') {  ?> 
              <a href="#" class="group-btn invite">Recommend </a>
              <?php } else {?>
                <a href="#" class="group-btn invite">Join Now </a>
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
              <h4><?php echo ucfirst($list['Group']['group_title'])?></h4>
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
                $urlparams = $this->params['url'];
                //pr($urlparams);
                unset($urlparams['url']);
                //pr($urlparams); 
               $paginator->options(array('url' => array('?' => http_build_query($urlparams))));
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
       
          
      </div>
        
    <div class="clearfix"></div>



        </div>
      </div>
      
    </div>
  </div>
    </main>
