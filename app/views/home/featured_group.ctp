<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<style>
   .error{
    color: #f00;
   }
   .fancybox-type-iframe .fancybox-inner{ height:450px!important;}
</style>
<script type="text/javascript">
     jQuery(document).ready(function($) {
    $(document).on("click", ".modalLink", function () {
       var group_id = $(this).data('id');
      $("#join_request_form #id_of_group").val(group_id);
    });
  });
</script>
<!-- Modal -->
<div id="joinnow" class="modal fade" role="dialog">
  <div class="modal-dialog group-modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">      
      <div class="modal-body">

    <form action="<?php echo $this->webroot?>home/join_request" method="post" id="join_request_form" name="join_request_form" >  

    <div class="group-join">
      <span class="group-type">     
        <input type="radio" name="group_type"  value="public" id="public" checked="checked" />
        <label for="public">Public</label>
      </span>
      <span class="group-type">     
        <input type="radio" name="group_type" value="private" id="private" />
        <label for="private">Private</label>
      </span>
      <div class="clearfix"></div>
      <input type="hidden" name="id_of_group" id="id_of_group" value="">  
      <div class="group-type-submit">
        <button type="submit">Submit</button>
      </div>
    </div>

    </form>

      </div>     
    </div>
  </div>
</div>


<main class="main-body">
<div class="category-details-content">
<div class="container">
<?php if(!empty($group_list)){?>
      <div class="group-list-item">
        <h3>Featured Group list</h3>
      
        <ul>
          <?php  
        
          foreach ($group_list as $list) {
		  if($this->Session->read('userData')){
		  ?>
          <li>
           <a href="<?php echo $this->webroot.'group/group_detail/'.$list['Group']['id'];?>">
            <div class="groupbox">
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
              <div class="group-overlay">
			  <?php $GroupType = $this->requestAction('group/group_type/'.$list['Group']['id'].'/');?>
              <?php if($GroupType['GroupUser']['user_type'] == 'O') { ?>

              			<a href="<?php echo $this->webroot; ?>group/invite_list/<?php echo $list['Group']['id']; ?>" class="userbox group-btn invite">Invite </a>
              <?php }  else if ($GroupType['GroupUser']['user_type'] == 'M') {  ?> 
						<a href="<?php echo $this->webroot; ?>group/recommend_list/<?php echo $list['Group']['id']; ?>" class="userbox group-btn invite">Recommend </a>
              <?php } else {
			  
			   $GroupIsJoin = $this->requestAction('group/group_is_join/'.$list['Group']['id'].'/');?>
                 <?php if($GroupIsJoin > 'O') { ?>
                <a href="javascript:void(0)" class="group-btn invite">Request Sent</a>
                <?php }else{ ?>
                  <a href="javascript:void(0)" class="group-btn invite modalLink"  data-toggle="modal" data-target="#joinnow" data-id="<?php echo $list['Group']['id'];?>" >Join Now </a>
                <?php } }?>
				
				
				
				<?php if(count($list['GroupUser']) == 1) { ?>
				
        <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>" ><?php echo count($list['GroupUser']);?> user </a>
        <?php } else {
        if(count($list['GroupUser']) == '0') {?>
           <a class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>"><?php echo count($list['GroupUser']);?> users </a>
       <?php  } else { ?>
	    <a  class="userbox group-btn view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $list['Group']['id'];?>"><?php echo count($list['GroupUser']);?> users </a>
        <?php } ?>
        <?php } ?>
              </div>
            </div>  
            <div class="group-name">
              <h4><?php echo ucfirst(stripslashes($list['Group']['group_title']))?></h4>
               
            </div> 
             </a> 
          </li>
       <?php } 
	   	  else{
	   ?>
	   		<li>
			<a href="<?php echo $this->webroot.'group/group_detail/'.$list['Group']['id'];?>">
            <div class="groupbox">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){?>
             <img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no-group-img_1.jpg" alt="" /> <?php } ?>
              </div>
            </div>  
            <div class="group-name">
              <h4><?php echo ucfirst(stripslashes($list['Group']['group_title']))?></h4>
               
            </div> 
			</a>
          </li>
	   <?php 
		  }
	   	  } ?>

        </ul>
      </div>
     

      <div class="clearfix"></div> 
	  <?php if($count_groups>4){ ?>
	  <div class="pagination">
	<?php //echo $this->Paginator->counter(); ?>
	<?php
	$urlparams = $this->params['url'];
	//pr($urlparams);
	unset($urlparams['url']);
	//pr($urlparams); 
	//$paginator->options(array('url' => array('?' => http_build_query($urlparams))));
$paginator->options(array('url' => array($this->passedArgs['0'], '?' => http_build_query($urlparams))));//required because we are passing one argument that is the category id for the group list which is not in case of other pagination.
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
  	  <?php } ?>
      </div>
<?php } else { ?>
        <div class="no-item-found">
        No groups found
        </div>
<?php } ?>
		<div class="clearfix"></div>


</div>
</div>
</main>