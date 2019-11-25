
<html>
  <head>
    <title>afterglow player</title>
<!--     <script type="text/javascript" src="//cdn.jsdelivr.net/afterglow/latest/afterglow.min.js"></script> -->
         <script src="<?php echo $this->webroot?>js/afterglow.min.js"></script> 
  </head>
  <body>
    <video class="afterglow" id="myvideo" width="850" height="480" preload controls>
    <?php if($extension == '3gp') { ?>
 
    <source type="video/3gpp" src="<?php echo $this->webroot.'group_videos/'.$d_video['Video']['video']; ?>" />
    <?php } else if ($extension == 'mp4'){ ?>
    <source type="video/mp4" src="<?php echo $this->webroot.'group_videos/'.$d_video['Video']['video']; ?>" />
    <?php } else if ($extension == 'MOV'){ ?>
    <source type="video/MOV" src="<?php echo $this->webroot.'group_videos/'.$d_video['Video']['video']; ?>" />
    <?php } else if ($extension == 'ogv'){ ?>
   <source type="video/ogg" src="<?php echo $this->webroot.'group_videos/'.$d_video['Video']['video']; ?>"/> 
   <?php } else if ($extension == 'flv'){ ?>
   <source type="video/flv" src="<?php echo $this->webroot.'group_videos/'.$d_video['Video']['video']; ?>"/> 
   <?php } ?>  
    </video>
  </body>
