<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<script>
  var WEBROOT = '<?php echo $this->webroot;?>';
  function page_reload(){
  location.reload();
  }
</script>
<script >
  $(document).ready( function() {
 
      var validator = $("#video_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
         video_file: "required"
                 
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         video_file:"Please upload video"
         
      }
   });
});
</script> 

 <style>
   .error{
    color: #f00;
   }
 </style>

 <!-- view user Modal -->

 
 
<!-- Image Modal -->
<div id="uploadimage" class="modal fade" role="dialog">
  <div class="modal-dialog upload-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Images</h4>
      </div>
      <div class="modal-body">
       <div class="image_upload_div">
			<form action="<?php echo $this->webroot.'group/upload_gallery_image/'.$group_id;?>" class="dropzone">
				
			</form>
		<div class="upload-bottom"> <button type="submit" class="" onclick="page_reload()">Upload</button> </div>			
		</div> 	
      </div>
    </div>

  </div>
</div>


<!-- Image Modal -->
<div id="uploaddoc" class="modal fade" role="dialog">
  <div class="modal-dialog upload-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Documents</h4>
      </div>
      <div class="modal-body">
       <div class="image_upload_div">
      <form action="<?php echo $this->webroot.'group/upload_gallery_doc/'.$group_id;?>" class="dropzone1">
      </form>
	  <div class="upload-bottom"> <button type="submit" class="" onclick="page_reload()" >Upload </button> </div>
    </div>  
      </div>
    </div>

  </div>
</div>

<!-- Video Modal -->
<div id="upvideo" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Video</h4>
      </div>
      <div class="modal-body">
      <form action="<?php echo $this->webroot?>group/add_group_video" method="post" id="video_form" 
      name="video_form" enctype="multipart/form-data"> 
      <input type="hidden" name="grp_id" id="grp_id" value="<?php echo $group_id; ?>" />
       <div class="upload-video">
			<label class="label-video">Upload Video</label>
			<span class="video-file">
				<input type="file" name="video_file" id="video_file" />
         <div class="clearfix"></div>   
       
		  </span>	
      <label class="label-video">Upload Image</label>
      <span class="video-file">
        <input type="file" name="image_file" id="image_file" />
         <div class="clearfix"></div>   
       
      </span> 
       <button type="submit" class="btn signupbtn">Submit</button>		
	   <div class="clearfix"></div>		
	   </div>
     </form>
	   
	  
	   
      </div>
    </div>

  </div>
</div>

<main class="main-body">

  
  <div class="category-details-content">
    <div class="container"> 
      <div class="heading-title">
        <h2>Group details</h2>
      </div>
      <div class="page-top-line">
        <div class="page-left-nav">
          <ul>
            <li><a href="#">Edit <i class="fa create-icon"></i></a></li>
            <li><a href="#">Push your message <i class="fa create-icon"></i></a></li>
          </ul>
        </div>
        <div class="page-right-nav">
          <ul>
           <!--  <li><a href="#" data-toggle="modal" data-target="#usermodal">View users <i class="fa create-icon"></i></a></li> -->
            <li><a  class="userbox view-user" href="<?php echo $this->webroot?>group/group_member_list/<?php echo $group_details['Group']['id'];?>" > View users <i class="fa create-icon"></i></a></li>

            <li><a href="#">Send recomendation <i class="fa create-icon"></i></a></li>
          </ul>
        </div>
      <div class="clearfix"></div>  
      </div>
      <div class="group-details">
      <div class="group-details-info">
        <div class="group-details-img">
          <img src="<?php echo $this->webroot.'group_images/medium/'.$group_details['Group']['icon'];?>"alt="" />
        </div>
        <div class="group-details-content">
          <h4><?php echo ucfirst($group_details['Group']['group_title'])?></h4>
          <p><?php echo ucfirst($group_details['Group']['group_desc'])?>
          </p>
        </div>
      <div class="clearfix"></div>  
      </div>

           <div class="documents">
        <h3>Gallery</h3>
          <?php   if(!empty($photo_list)) { ?>      
         <?php   if(count($photo_list)<'6')
              { ?>
        <div class="zoom-gallery">
        
        <ul>
        <?php foreach($photo_list as $val)
              
               {  ?>
                <li>
                   <div class="gallery-box">
                    <a href="<?php echo $this->webroot.'gallery/'.$val['GroupImage']['image'];?>">
                   <img src="<?php echo $this->webroot.'gallery/web/'.$val['GroupImage']['image'];?>"alt="" />
                    </a>
                   </div>
              </li>
           
          <?php } ?>
           
        </ul>
       

        </div>
        <div class="clearfix"></div>  
         <?php } else { ?>

        <div class="video-slide">
          <div class="slide zoom-gallery" class="owl-carousel owl-theme">
         
				<?php foreach($photo_list as $val)
					  
					   {  ?>
						  <div class="item">
						   <div class="gallery-box">
							<a href="<?php echo $this->webroot.'gallery/'.$val['GroupImage']['image'];?>">
						    <img src="<?php echo $this->webroot.'gallery/web/'.$val['GroupImage']['image'];?>"alt="" />
							</a>
						   </div>
						   </div>
					 
				  <?php } ?>
				 
			
		</div>
     <div class="customNavigation">
      <a class="btn prev"><i class="fa fa-angle-left"></i></a>
      <a class="btn next"><i class="fa fa-angle-right"></i></a>
    </div>
    </div>
    <?php } ?>
    <?php } else { ?>
      <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br> NO GALLERY IMAGES</div>
      <?php } ?>
    <div class="group-bottom-line">
         <a href="#" class="upload-btn" data-toggle="modal" data-target="#uploadimage">Upload Images</a>       
      </div>
    </div>




      <div class="clearfix"></div>
      <div class="videos">
        <h3>Videos</h3>
         <?php   if(!empty($video_list)) { ?>   
          <?php   if(count($video_list)>'6')
              { ?>   
	
		<div class="video-slide">
		<div class="slide popup-youtube" class="owl-carousel owl-theme">
    <?php foreach($video_list as $valu)
            {  ?>
       <div class="item">
				<div class="video-img">
				  <img src="<?php echo $this->webroot;?>group_videos/images/<?php echo $valu['Video']['v_image']; ?>" alt="" />
				 <div class="video-overlay">
					<a href="<?php echo $this->webroot;?>group/popup_video/<?php echo $valu['Video']['id']; ?>">
					  <i class="fa video-icon"></i>
					</a>
				  </div>
           
				</div>				
			</div>
      <?php } ?>
			
						
		</div>
		<div class="customNavigation">
			<a class="btn prev"><i class="fa fa-angle-left"></i></a>
			<a class="btn next"><i class="fa fa-angle-right"></i></a>
		</div>
		</div>
    
     <?php } else { ?>


      <div class="slide popup-youtube" class="owl-carousel owl-theme">
    <?php foreach($video_list as $valu)
            {  ?>
     <div class="item">
        <div class="video-img">
          <img src="<?php echo $this->webroot;?>group_videos/images/<?php echo $valu['Video']['v_image']; ?>" alt="" />
          <div class="video-overlay">
          <a href="<?php echo $this->webroot;?>group/popup_video/<?php echo $valu['Video']['id']; ?>">
            <i class="fa video-icon"></i>
          </a>
          </div>
           

      </div>        
      </div>
      <?php } ?>
     
    </div>
    <?php } ?>
		<?php }else { ?>
      <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>NO GALLERY VIDEOS</div>
      <?php } ?>
      <div class="group-bottom-line">
        <a href="#" class="upload-btn" data-toggle="modal" data-target="#upvideo">Upload Videos</a>
      </div>
      </div>  
      
      <div class="clearfix"></div>

      <div class="documents">
        <h3>Documents</h3>
          <?php   if(!empty($doc_list)) { ?>      
         <?php   if(count($doc_list)<'6')
              { ?>
        <div>
        
        <ul>
        <?php foreach($doc_list as $val)
              
               {  ?>
            
          <li>
           <?php if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'docx') || (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'doc') ) { ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
            <div class="document-box doc">
            
              <div class="document-icon">
               
             <i class="fa doc-icon"></i>
                
              </div>
               <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
            </a>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'pdf') )  
              { ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
            <div class="document-box pdf">
           
              <div class="document-icon">
              <i class="fa pdf-icon"></i>
               </div>
                <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
            </a>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'xls') )  
              { ?>
            <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
             <div class="document-box xls">
              
              <div class="document-icon">
              <i class="fa xls-icon"></i>
              </div>
              <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
             </a>
            <?php } ?>
          </li>
          <?php } ?>
           
        </ul>
       

        </div>
        <div class="clearfix"></div>  
         <?php } else { ?>

        <div class="video-slide">
          <div class="slide" class="owl-carousel owl-theme">
        <?php foreach($doc_list as $val)
              
               {  ?>
                   
           <?php if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'docx') || (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'doc') ) { ?>
            <div class="item">
				<a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
					<div class="document-box doc">
					  <div class="document-icon">                
						 <i class="fa doc-icon"></i>
					  </div>
					  <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
					</div>
				</a>
            </div>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'pdf') )  
              { ?>
                <div class="item">
            <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
            <div class="document-box pdf">
              <div class="document-icon">
             
              <i class="fa pdf-icon"></i>
                            
              </div>
              <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
            </a>
            </div>
            <?php }
            else if( (substr(strtolower(strrchr($val['GroupDoc']['docname'], '.')), 1) == 'xls') )  
              { ?>
              <div class="item">
             <a href="<?php echo $this->webroot.'gallery/doc/'.$val['GroupDoc']['docname'];?>"target="_blank">
             <div class="document-box xls">
              <div class="document-icon">
              
             <i class="fa xls-icon"></i>
                
                
              </div>
              <span class="document-name"><?php echo $val['GroupDoc']['docname']; ?></span>
            </div>
            </a>
            </div>
            <?php } ?>
          
          <?php } ?>
         
     
    </div>
     <div class="customNavigation">
      <a class="btn prev"><i class="fa fa-angle-left"></i></a>
      <a class="btn next"><i class="fa fa-angle-right"></i></a>
    </div>
    </div>
    <?php } ?>
    <?php } else { ?>
       <div class="no-gallery-img"> <img src="<?php echo $this->webroot;?>images/no-gallery-img.jpg" alt="" /> <br>NO GALLERY DOCUMENTS</div>
      <?php } ?>
    <div class="group-bottom-line">
        <a href="http://jsfiddle.net/user/login/" class="upload-btn fancybox" data-toggle="modal" data-target="#uploaddoc">Upload DOC</a>        
      </div>
    </div>
    
      
      <div class="clearfix"></div>  
      </div>
    </div>
  </div>  
  
  <div class="group-calender">
    <div class="container">
      <h3>Group Calendar</h3>
      <div class="recent-event">
      <div class="calendar-heading">
        <h4>18th jan 2017</h4>
      </div>
      <div class="calendar-box">
        <div class="event-location">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3684.851020258074!2d88.34808281453573!3d22.54725288519583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1485848756521" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
        <div class="event-des">
          <h4>Mad House Event</h4>
          <div class="event-time"><span> 8:10am</span></div>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but</p>
          <span class="event-place">5/1, Ho Chi Minh Sarani, Park Street area, Kolkata, West Bengal 700071</span>
          <div class="clearfix"></div>
          <div class="deal-amount"><span class="amount-price">$20</span>Deal amount</div>
        </div>
      <div class="clearfix"></div>  
      </div>
      <div class="calendar-box">
        <div class="event-location">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3684.851020258074!2d88.34808281453573!3d22.54725288519583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1485848756521" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
        <div class="event-des">
          <h4>My Folk Event</h4>
          <div class="event-time"><span> 8:10am</span></div>
          <p>Specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
          <span class="event-place">5/1, Ho Chi Minh Sarani, Park Street area, Kolkata, West Bengal 700071</span>
          <div class="clearfix"></div>
          <div class="deal-amount"><span class="amount-price">$20</span>Deal amount</div>
        </div>
      <div class="clearfix"></div>  
      </div>
      <div class="calendar-box">
        <div class="event-location">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3684.851020258074!2d88.34808281453573!3d22.54725288519583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1485848756521" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
        <div class="event-des">
          <h4>We all are one Event</h4>
          <div class="event-time"><span> 8:10am</span></div>
          <p>The leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
          <span class="event-place">5/1, Ho Chi Minh Sarani, Park Street area, Kolkata, West Bengal 700071</span>
          <div class="clearfix"></div>
          <div class="deal-amount"><span class="amount-price">$20</span>Deal amount</div>
        </div>
      <div class="clearfix"></div>  
      </div>
      </div>
      
      <div class="past-event">
      <div class="calendar-heading">
        <h4>17th jan 2017</h4>
      </div>
      <div class="past-event-box">
        <div class="calendar-box">
          <div class="event-location">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3684.851020258074!2d88.34808281453573!3d22.54725288519583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1485848756521" frameborder="0" style="border:0" allowfullscreen></iframe>
          </div>
          <div class="event-des">
            <h4>Strong Tie</h4>
            <div class="event-time"><span> 8:10am</span></div>
            <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
            <span class="event-place">5/1, Ho Chi Minh Sarani, Park Street area, Kolkata, West Bengal 700071</span>
            <div class="clearfix"></div>
          </div>
        <div class="clearfix"></div>  
        </div>
        <div class="calendar-box">
          <div class="event-location">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3684.851020258074!2d88.34808281453573!3d22.54725288519583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1485848756521" frameborder="0" style="border:0" allowfullscreen></iframe>
          </div>
          <div class="event-des">
            <h4>Drama club Event</h4>
            <div class="event-time"><span> 8:10am</span></div>
            <p>Distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages</p>
            <span class="event-place">5/1, Ho Chi Minh Sarani, Park Street area, Kolkata, West Bengal 700071</span>
            <div class="clearfix"></div>
          </div>
        <div class="clearfix"></div>  
        </div>
      <div class="clearfix"></div>  
      </div>    
      </div>
      
      <div class="past-event">
      <div class="calendar-heading">
        <h4>16th jan 2017</h4>
      </div>
      <div class="past-event-box">
        <div class="calendar-box">
          <div class="event-location">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3684.851020258074!2d88.34808281453573!3d22.54725288519583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1485848756521" frameborder="0" style="border:0" allowfullscreen></iframe>
          </div>
          <div class="event-des">
            <h4>Strong Tie</h4>
            <div class="event-time"><span> 8:10am</span></div>
            <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
            <span class="event-place">5/1, Ho Chi Minh Sarani, Park Street area, Kolkata, West Bengal 700071</span>
            <div class="clearfix"></div>
          </div>
        <div class="clearfix"></div>  
        </div>
        <div class="calendar-box">
          <div class="event-location">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3684.851020258074!2d88.34808281453573!3d22.54725288519583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1485848756521" frameborder="0" style="border:0" allowfullscreen></iframe>
          </div>
          <div class="event-des">
            <h4>Drama club Event</h4>
            <div class="event-time"><span> 8:10am</span></div>
            <p>Distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages</p>
            <span class="event-place">5/1, Ho Chi Minh Sarani, Park Street area, Kolkata, West Bengal 700071</span>
            <div class="clearfix"></div>
          </div>
        <div class="clearfix"></div>  
        </div>
      <div class="clearfix"></div>  
      </div>    
      </div>
      
      
    </div>    
  </div>
  

  
</main>   
    
