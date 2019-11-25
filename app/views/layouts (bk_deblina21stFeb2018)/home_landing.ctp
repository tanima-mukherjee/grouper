 <?php echo $this->element('home_header'); ?>   
  <?php echo $content_for_layout ?>
  <?php echo $this->element('home_footer'); ?>
</div>   
  
 <!-- jQuery (JavaScript plugins) -->
   
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo $this->webroot?>js/bootstrap.min.js"></script> 
	<script src="<?php echo $this->webroot?>js/owl.carousel.js"></script> 
	<script src="<?php echo $this->webroot?>js/jquery.fancybox.pack.js"></script>	
	<script>
		$(document).ready(function() {
			$(".outerbox").fancybox({
				type: 'iframe',
				width: 450				
			});
		});
	</script>
	<script>
		$('.owl-carousel').owlCarousel({
			loop:true,
			loop:false,
			nav:true,
			items : 1           
		});
	</script>
	<script src="<?php echo $this->webroot?>js/main.js"></script>
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