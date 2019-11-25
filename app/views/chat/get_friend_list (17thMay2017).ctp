<!--scroller script start-->
<script>  
  jQuery(document).ready(function() {
    $('#image_popup').fancybox({
      type: 'iframe',
      width: 700, 
      height: 550,
      fitToView: false,
      autoSize: false,
      scrolling: 'no'
    });
});
</script>

<style>
  #content_3 {
    height: 238px;
		overflow:auto;
  }
</style>


<?php if(!empty($ArUserSearch)){ ?>
<div class="mainprofimgfull_one">
<div class="reminder_one mapScroller content" id="content_3">
<?php foreach($ArUserSearch as $UserSearch){?>
<a href="<?php echo $this->webroot;?>user/view_profile/<?php echo base64_encode($UserSearch['User']['id']);?>/<?php echo base64_encode('view_profile');?>">
	<div class="profileimgintropart_search">
    	
        <div class="profimgleftpart">
        	<?php  if($UserSearch['User']['image']!='')	
					{ ?>
					<img src="<?php echo $this->webroot;?>user_images/thumb/<?php echo $UserSearch['User']['image'];?>" alt="img">
                <?php }else{ ?>
					<img src="<?php echo $this->webroot;?>images/noimage.jpg" alt="img">
                <?php } ?> 
        </div>
        <div class="profintrowrightpart_search">
        	<h2><?php echo $UserSearch['User']['fname'];?> <?php echo $UserSearch['User']['lname'];?></h2> 
			
        </div>
    </div>
</a>
<?php } ?>  
</div> 
</div> 
<div class="clear"></div>

<?php } ?>

