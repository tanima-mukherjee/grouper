<div class="clearfix"></div>
<footer>
    <div class="footer-top">
      <div class="container">
        <div class="footer-menu first-col">
          <ul>
            <li><a href="<?php echo $this->webroot?>">Home</a></li>
            <li><a href="<?php echo $this->webroot.'category/category_list';?>">Categories</a></li>
            <li><a href="<?php echo $this->webroot.'home/contact_us';?>">Contact</a></li>
          </ul>
        </div>
        <div class="footer-menu last-col">
          <ul>
            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#infographicModal">About</a></li>
            <li><a href="<?php echo $this->webroot.'home/faq';?>">Faq</a></li>
            <li><a href="<?php echo $this->webroot.'home/terms';?>">Terms of Use policy</a></li>
          </ul>
        </div>
      <div class="clearfix"></div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
        <div class="bottom-logo-wrap">
          <div class="logo-carv-bg">
            <div class="footer-logo-position">
              <img src="<?php echo $this->webroot;?>images/footer_logo.png" alt="" />
            </div>
          </div>
        </div>
        <p>"Think COMMUNITY. Think GroopZilla!" Copyright &copy; 2018- <?php echo date('Y')+1;?> GrouperUSA, LLC. </p>
      <div class="clearfix"></div>
      </div>
    </div>
  </footer>
  <script>
var window_hyt = window.innerHeight;
//alert(window_hyt);
var header_hyt = $('.header-wrap').height();
var footer_hyt = '0';
var left_hyt =  parseInt(window_hyt) - (parseInt(header_hyt) + parseInt(footer_hyt));
//alert(left_hyt);

jQuery('#chat_page').css("height", left_hyt + "px");
  </script>