<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<style>
@font-face {
    font-family: 'open_sanslight';
    src: url('fonts/opensans-light-webfont.eot');
    src: url('fonts/opensans-light-webfont.eot?#iefix') format('embedded-opentype'),
         url('fonts/opensans-light-webfont.woff') format('woff'),
         url('fonts/opensans-light-webfont.ttf') format('truetype'),
         url('fonts/opensans-light-webfont.svg#open_sanslight') format('svg');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: 'open_sansregular';
    src: url('fonts/opensans-regular-webfont.eot');
    src: url('fonts/opensans-regular-webfont.eot?#iefix') format('embedded-opentype'),
         url('fonts/opensans-regular-webfont.woff') format('woff'),
         url('fonts/opensans-regular-webfont.ttf') format('truetype'),
         url('fonts/opensans-regular-webfont.svg#open_sansregular') format('svg');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family:"Harabara";
    src:url("fonts/Harabara.eot?") format("eot"),
    url("fonts/Harabara.woff") format("woff"),
    url("fonts/Harabara.ttf") format("truetype")
    ,url("fonts/Harabara.svg#Harabara") format("svg");
    font-weight:normal;
    font-style:normal;
    }
    
@font-face {
    font-family: 'HelveticaLTStdCond';
    src: url('fonts/HelveticaLTStdCond.eot');
    src: url('fonts/HelveticaLTStdCond.eot') format('embedded-opentype'),
         url('fonts/HelveticaLTStdCond.woff2') format('woff2'),
         url('fonts/HelveticaLTStdCond.woff') format('woff'),
         url('fonts/HelveticaLTStdCond.ttf') format('truetype'),
         url('fonts/HelveticaLTStdCond.svg#HelveticaLTStdCond') format('svg');
}   
p, h2, h3, h4, h5, tr, td, img{padding:0; margin:0;}
</style>

<body style="padding:0; margin:0px 0; font-family: 'open_sansregular'; background:url(http://easy.ismartminds.com/app/webroot/images/background_img.jpg) center 0 no-repeat;">
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style="text-align:center; padding-top:0px; padding-bottom:40px;"><a href="index.html"><img src="http://ogmaconceptions.com/demo/look4fitness/images/logo.png" alt="" width="225" height="50" /></a></td>
  </tr>
    
  <tr>
    <td>
        <table width="570" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td style="background:rgba(255, 255, 255, 0.7); padding:30px 20px; border-radius:5px;">
                <h2 style="font-size:17px; color:#0000; padding-bottom:20px;">Thank you for registration</h2>
                <p style="font-size:13px; color:#0000; padding-bottom:30px; padding-left:30px;">
				Hello <?php echo $name;?>,<br/><br/>
				
				Please click on the below link to activate your account.<br/>
				<?php /*?><strong>Activation Code:  <?php echo $activation_code; ?></strong><?php */?>
				
				<br/><br/>
					<a href="http://ogmaconceptions.com/demo/look4fitness/home/activation/<?php echo base64_encode($last_insert_id);?>"> Activation Link Click Here </a>
					
					<br/><br/>

					Sincerely,<br/>
					Look4Fitness Team


                </p>
               
            </td>
          </tr>
        </table>
    </td>
  </tr>
  
  <tr>
    <td>
        
    </td>
  </tr>
  
</table>


</body>
</html>
