

<link href="<?php echo $this->webroot?>css/player-default.css" rel="stylesheet"> 
   <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet"> 

 <div id="video"></div>

         <script type="text/javascript">

           document.addEventListener('DOMContentLoaded', function() {

          var p = $("#video").player({

            video: {
              url: {
                hq: {
                  en: "<?php echo $this->webroot.'group_videos/'.$d_video['Video']['video']; ?>"
                }
              }
            }
          }, {width: 500});
        },false);</script> 
        <video poster="movie.jpg" controls>
  <source src="movie.webm" type='video/webm; codecs="vp8.0, vorbis"'>
  <source src="movie.ogv" type='video/ogg; codecs="theora, vorbis"'>
  <source src="movie.mp4" type='video/mp4; codecs="avc1.4D401E, mp4a.40.2"'>
  <p>This is fallback content to display for user agents that do not support the video tag.</p>
</video>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<script src="<?php echo $this->webroot?>js/player.js"></script>
     <script src="<?php echo $this->webroot?>js/main.js"></script> 

