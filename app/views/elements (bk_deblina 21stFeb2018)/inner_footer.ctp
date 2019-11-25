 <footer id="footer">
    <div class="container">
      <div class="footer-link">
        <ul>
          <li><a href="<?php echo $this->webroot?>">Home</a></li>
		  <li><a href="<?php echo $this->webroot.'category/category_list';?>">Categories</a></li>
          <li><a href="<?php echo $this->webroot.'home/faq';?>">Faq</a></li>
          <li><a href="<?php echo $this->webroot.'home/contact_us';?>">Contact</a></li>
          <!--<li><a href="javascript:void(0)" data-toggle="modal" data-target="#infographicModal">About</a></li>-->
        </ul>
      </div>
      <!--<p>GroupER&reg; is lovingly made by our team for yours. Copyright &copy; <?php echo date('Y'); ?>- <?php echo date("Y",strtotime("+1 year")); ?> GroupER. Peruse the <a href="<?php echo $this->webroot.'home/terms';?>">Terms & Privacy.</a></p>-->
	  <p>"Think COMMUNITY.  Think GrouperUSA!" Copyright &copy; <?php echo date('Y'); ?>- <?php echo date("Y",strtotime("+1 year")); ?> GrouperUSA, LLC. <a href="<?php echo $this->webroot.'home/terms';?>">Terms of Use policy</a></p>
      <div class="clearfix"></div>
      <div class="footer-devider"></div>
    </div>
  </footer>
  <script>
var window_hyt = window.innerHeight;
//alert(window_hyt);
var header_hyt = $('.header').height();
var footer_hyt = '140';
var left_hyt =  parseInt(window_hyt) - (parseInt(header_hyt) + parseInt(footer_hyt));
//alert(left_hyt);

jQuery('#chat_page').css("height", left_hyt + "px");
  </script>