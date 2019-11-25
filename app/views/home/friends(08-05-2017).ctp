<!-- Friend  Modal-->
<div id="remove" class="modal fade" role="dialog">
  <div class="modal-dialog remove-modal-dialog">
    <!-- Modal content-->
    <div class="modal-content"> 
      <form action="<?php echo $this->webroot?>home/confirm_remove_friend" method="post" id="remove_friend_form" name="remove_friend_form" >  
      <div class="modal-body">
        <p>Do you want to continue ?</p>
         <input type="hidden" name="mode" id="mode" value="removefriend"> 
		 <input type="hidden" name="friend_id" id="friend_id" value=""> 
		<div class="button-list">
			<button type="submit" class="btn btn-confirm btn-margin">Confirm</button>
			<button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">Cancel</button>
		</div>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal end-->

<main class="main-body">  
<div class="friend-content">
    <div class="container">
      <div class="friends-list-box">
      <?php if(count($friend_list)>0) { ?>
      <ul>
         <?php  
         foreach ($friend_list as $list) { ?> 
        <li>
          <div class="friend-list">
            <div class="friend-img">
            <?php if(!empty($list['User']['image'])){ ?>
            <img src="<?php echo $this->webroot.'user_images/'.$list['User']['image'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> <?php } ?>
            
            </div>
          <div class="friend-info">
       <h4><?php echo ucfirst ($list['User']['fname'].' '.$list['User']['lname']);?></h4>
             
              <a href="javascript:void('0')" onclick="confirm_remove(<?php echo $list['User']['id']; ?>); "data-toggle="modal" data-target="#remove">Remove</a>
              <a href="javascript:void('0')">Message</a>
            </div>
          </div>
        </li>
        <?php } ?>

      </ul>
      <div class="clearfix"></div>
       <?php } else { ?>
        <div class="no-item-found">
        No friends found
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
       			// $paginator->options(array('url' => array($this->passedArgs['0'], $this->passedArgs['1'], $this->passedArgs['2'], '?' => http_build_query($urlparams))));
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
   
    </div>
  </div>

</main>

<!-- <script type="text/javascript">
function confirm_remove(user_id){
   var retVal = confirm("Do you want to continue ?");
   if( retVal == true ){
	  jQuery.ajax({
      
          type: "GET",
          url: WEBROOT+"home/confirm_remove_ajax",
          data: {friend_id:user_id},
          success: function(response){
           //alert(response);
		    if(response=='1'){
				location.reload(); 
			}
          }
      });
   }
   else{
	  //document.write ("User does not want to continue!");
	  return false;
   }
}
</script> -->

<script type="text/javascript">
function confirm_remove(user_id){
    $("#friend_id").val(user_id);
}
</script>