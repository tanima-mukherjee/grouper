<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<script >
  $(document).ready( function() {
 
      var validator = $("#group_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
         group_name: "required",
         upload_image: "required",
         g_desc: "required",
         g_purpose:"required"
        
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         group_name : "Please enter group name",
         upload_image:"Please upload  image",
         g_desc : "Please enter group description",
         g_purpose : "Please enter a group purpose"
    
      }
   });
});
</script> 

 <style>
   .error{
    color: #f00;
   }
 </style>

  <!------------------------Auto tab functionality Starts----------------------> 
<!-- 
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.autotab.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$('#phn_no1, #phn_no2, #phn_no3').autotab_magic().autotab_filter('numeric');
});
</script>
 -->
<!------------------------Auto tab functionality Ends---------------------->


<!-- Sign up Modal -->
  <div class="modal fade" id="creategroup" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->

      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Create Group</h4>
    </div>
       
    <div class="modal-body group-modal-body">
   <form action="<?php echo $this->webroot?>group/add_free_group" method="post" id="group_form" name="group_form" enctype="multipart/form-data">  

  <div class="pop-createg">
  <!-- <div class="pop-createg-round"><img src="<?php echo $this->webroot?>images/author-img01.jpg"  alt=""></div> -->
  <div class="upload-img">
  <div class="pop-createg-round">
                     
                        <img id="thumbnil2" class="no-profile-img" src="<?php echo $this->webroot?>images/no-group-img.jpg" />
  </div>

   <!--  <div class="cmicon"><img src="<?php echo $this->webroot?>images/cam-icon.png" alt=""></div> -->
   <span class="btn-bs-file browse-btn">Browse</span>
   <input onChange="showMyImage(this)" name="upload_image" id="image455" data-badge="false" type="file"/>
  <div class="clearfix"></div>
    </div>
    
  </div>
	<div class="clearfix"></div>
    <div class="create-group-field">
      <div class="form-group">
        <label class="label-field">Title</label>
        <input type="text" class="form-control" id="group_name" name="group_name">   
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Description</label>
       <textarea name="g_desc" id="g_desc" class="form-control" cols="" rows=""></textarea>
         <div class="clearfix"></div>                   
      </div> 
       <div class="form-group">
        <label class="label-field">Purpose</label>
       <textarea name="g_purpose" id="g_purpose" class="form-control" cols="" rows=""></textarea>
         <div class="clearfix"></div>                   
      </div> 
     
            
      <button type="submit" class="btn signupbtn">Create Group</button>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>

<main class="main-body">	
<div class="categories">
    <div class="container" style="position:relative;">
      <h2>Categories</h2>
       <!-- <h2><?php echo ($selected_state_id); ?></h2>
       <h2><?php echo ($selected_city_id) ;?></h2>   --> 
	   <?php if ($this->Session->read('userData')) { ?>
     <!-- <div class="crtegorripart">
        <a href="javascript:void(0)" data-toggle="modal" data-target="#creategroup" class="create-link fright">Create Free Group <i class="fa create-icon"></i></a>
      </div>-->
	  <?php } ?>
       <?php if(count($category_list)>0){?>
      <div class="categories-row">
        <ul>
        
           <?php   foreach ($category_list as $list) {?>
          <li>
		  <?php if ($this->Session->read('userData')) { ?>
          		<a href="<?php echo $this->webroot.'group/group_list/'.$list['Category']['id'];?>">
		  <?php } 
		  else{
		  	if(isset($selected_state_id) && $selected_state_id>0 && isset($selected_city_id) && $selected_city_id>0){
		  ?>
		  		<a href="<?php echo $this->webroot.'group/group_list/'.$list['Category']['id'];?>">
		  <?php	
			}
			else{
		  ?>
		  		<a href="javascript:void(0)" data-toggle="modal" data-target="#state" class="select-state">
		  <?php }
		  } ?>
            <div class="category-img">
            <?php if($list['Category']['image']!=''){?>
             <img src="<?php echo $this->webroot.'category_photos/medium/'.$list['Category']['image'];?>" alt="<?php echo ucfirst(stripslashes($list['Category']['title']))?>"/> <?php } else { ?><img src="<?php echo $this->webroot?>images/category-list-no-img.jpg" alt="" /> <?php } ?>
               <div class="overlay-bg"></div>
              <div class="category-name">
                <span><?php echo ucfirst(stripslashes($list['Category']['title']))?></span>
                <p> <?php echo substrwords(stripslashes($list['Category']['category_desc']),100);?></p>

              </div>
              
            </div>
          </a>
          </li>
          <?php }?>
           
        </ul>
      </div>
      <?php } else { ?>
        <div class="categories-row">
        No categories found
        </div>
        <?php } ?>
		<div class="clearfix"></div>

     
		



    </div>
	</main>
  </div>

  
  
  <!--<script>
  	$(":file").filestyle({badge: false});
  </script>-->

    <script>
  function showMyImage(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil2");
      img.file = file;
      var reader = new FileReader();
      reader.onload = (function (aImg) {
        return function (e) {
          aImg.src = e.target.result;
        };
      })(img);
      reader.readAsDataURL(file);
    }
  }
</script>