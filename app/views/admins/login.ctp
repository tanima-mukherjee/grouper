<!DOCTYPE html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Grouper Admin Login</title>
    <link rel="stylesheet" href="<?php echo $this->webroot; ?>admin/css/style.default.css" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>admin/js/jquery-1.8.3.min.js"></script>
  </head>

  <body class="loginbody">
    <div class="loginwrapper">
      <div class="loginwrap zindex100 animate2 bounceInDown">
        <div align="center" style="padding-bottom:10px; background:#ffffff;">
			<img src="<?php echo $this->webroot; ?>admin/img/logo.png">
		</div>
        <h1 class="logintitle"> Sign In </h1>
        <div class="loginwrapperinner">
          <?php if ($this->Session->check('Message.flash')) : ?>
            <div style="color: red; font-size: 14px; margin-bottom: 10px;">
              <?php echo $this->Session->flash(); ?>
            </div>
          <?php endif; ?>
          <form id="loginform" action="" method="post">
            <p class="animate4 bounceIn"><input type="text" id="username" name="username" placeholder="Username" /></p>
            <p class="animate5 bounceIn"><input type="password" id="password" name="password" placeholder="Password" /></p>
            <p class="animate6 bounceIn"><button class="btn btn-default btn-block">Submit</button></p>
            <p class="animate7 fadeIn"><a href="#"><span class="icon-question-sign icon-white"></span> Forgot Password?</a></p>
          </form>
        </div><!--loginwrapperinner-->
      </div>
      <div class="loginshadow animate3 fadeInUp"></div>
    </div><!--loginwrapper-->

    <script type="text/javascript">
      jQuery.noConflict();

      jQuery(document).ready(function () {
        var anievent = (jQuery.browser.webkit) ? 'webkitAnimationEnd' : 'animationend';
        jQuery('.loginwrap').bind(anievent, function () {
          jQuery(this).removeClass('animate2 bounceInDown');
        });

        jQuery('#username,#password').focus(function () {
          if (jQuery(this).hasClass('error'))
            jQuery(this).removeClass('error');
        });

        jQuery('#loginform button').click(function () {
          if (!jQuery.browser.msie) {
            if (jQuery('#username').val() == '' || jQuery('#password').val() == '') {
              if (jQuery('#username').val() == '')
                jQuery('#username').addClass('error');
              else
                jQuery('#username').removeClass('error');
              if (jQuery('#password').val() == '')
                jQuery('#password').addClass('error');
              else
                jQuery('#password').removeClass('error');
              jQuery('.loginwrap').addClass('animate0 wobble').bind(anievent, function () {
                jQuery(this).removeClass('animate0 wobble');
              });
            } else {
              jQuery('.loginwrapper').addClass('animate0 fadeOutUp').bind(anievent, function () {
                jQuery('#loginform').submit();
              });
            }
            return false;
          }
        });
      });
    </script>
  </body>
</html>