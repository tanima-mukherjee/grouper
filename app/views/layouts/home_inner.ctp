<!DOCTYPE html> 
<html lang="en">  
  <?php echo $this->element('inner_header'); ?>   
  <?php echo $content_for_layout ?>
  <?php echo $this->element('inner_footer'); ?>
</div>   
  
 <!-- jQuery (JavaScript plugins) -->
   
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo $this->webroot?>js/bootstrap.min.js"></script> 
    <script src="<?php echo $this->webroot?>js/jquery.magnific-popup.min.js"></script>
	<script src="<?php echo $this->webroot?>js/owl.carousel.js"></script> 
	<script src="<?php echo $this->webroot?>js/dropzone.js"></script>
	<script src="<?php echo $this->webroot?>js/dropzone1.js"></script>
	<script src="<?php echo $this->webroot?>js/player.js"></script>
    <script src="<?php echo $this->webroot?>js/main.js"></script> 
    
    <!-- CHAT USE -->
<!--<script type="text/javascript" src="<?php echo $this->webroot;?>new_chat/js/jquery.js"></script>-->
<script type="text/javascript" src="<?php echo $this->webroot;?>new_chat/js/moment.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot;?>new_chat/js/livestamp.js"></script> 
<!-- CHAT USE -->
     
	<script src="<?php echo $this->webroot?>js/jquery.fancybox.pack.js"></script>	
	<script>
		$(document).ready(function() {
			$(".event_details").fancybox({
				type: 'iframe',
				width: 500	
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$(".userbox").fancybox({
				type: 'iframe',
				width: 450	
			});
		});
	</script>
	
	<script>
		$(document).ready(function() {
			$(".outerbox").fancybox({
				type: 'iframe',
				width: 450				
			});
		});
	</script>
	
	<script>
		$('.infographic-carosel').owlCarousel({
			loop:true,
			loop:false,
			nav:true,
			items : 1           
		});
	</script>

	<script>
			$(".slide").each(function() {
		  var $this = $(this);
		  $this.owlCarousel({
			items : 6, 
				itemsDesktop : [992,4],
				itemsDesktopSmall : [768,3], 
				itemsTablet: [450,2], 
				navText:['<a class="btn prev"><i class="fa fa-angle-left"></i></a>','<a class="btn next"><i class="fa fa-angle-right"></i></a>'],
				itemsMobile : false,
				pagination : false,
				autoPlay:false,
				nav:true
		  });
		  // Custom Navigation Events
		  $this.parent().find(".next").click(function(){
			$this.trigger('owl.next');
		  });
		  $this.parent().find(".prev").click(function(){
			$this.trigger('owl.prev');
		  });
		});
	</script>
	<script>
		$('li.dropdown.message-noti a').on('click', function (event) {
    $(this).parent().toggleClass("open");
});

$('body').on('click', function (e) {
    if (!$('li.dropdown.message-noti').is(e.target) && $('li.dropdown.message-noti').has(e.target).length === 0 && $('.open').has(e.target).length === 0) {
        $('li.dropdown.message-noti').removeClass('open');
    }
});
	</script>
	
</body>
</html>
