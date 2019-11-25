<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>

<script>


 $(document).ready(function() {      
    var validator = $("#category_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         title:  "required",   
         upload_image: "required",
         desc:  "required"
      },
      errorPlacement: function(label, element) {
            // position error label after generated textarea
            
               label.insertAfter(element.next());
            
         },
      messages: {
         title: "Please enter a title",
         upload_image:"Please upload  image",
         desc: "Please enter description"
      }
      
   });
});
 </script> 
  <style>
   .error{
    color: #f00;
   }
 </style>
<div class="modal fade" id="create-categories" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->
      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Create Category</h4>
    </div>
       
    <div class="modal-body">  
    <form action="<?php echo $this->webroot?>category/add_category" method="post" id="category_form" name="category_form" enctype="multipart/form-data">
	  <div class="pop-createg2">
		<!-- <div class="pop-createg-img"><img src="<?php echo $this->webroot?>images/categori-details-img.jpg" alt=""></div> -->
    <div class="upload-img">
    <div class="pop-createg-img">
                     
                        <img id="thumbnil" src="<?php echo $this->webroot?>images/no_profile_img55.jpg" >
    </div>
		
		
		<span class="btn-bs-file cmicon2">
                Browse
    <input onChange="showMyImage(this)" name="upload_image" id="upload_image" data-badge="false" type="file"/>
    </span>
    </div>
     <div class="clearfix"></div>
    <label for="upload_image" generated="true" class="error" style="display:none;">Please upload  image</label>
		
		<!--<input type="file" onChange="showMyImage(this)" class="filestyle" name="profile_image" id="image" data-badge="false">-->
     
	  </div>
  
    <div class="signup-left">
    
      <div class="form-group">
        <label class="label-field">Title</label>
        <input type="text" class="form-control" id="title" name="title">   
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Description</label>
    <textarea id="desc" name="desc" class="form-control" cols="" rows=""></textarea>  
       
         <div class="clearfix"></div>                   
      </div>    
                  
            
      <button type="submit" class="btn signupbtn">Create</button>
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

      <div class="crtegorripart">
        <a href="#" data-toggle="modal" data-target="#create-categories" class="create-link fright">Create Categories <i class="fa create-icon"></i></a>
      </div>
       <?php if(count($category_list)>0){?>
      <div class="categories-row">
        <ul>
        
           <?php   foreach ($category_list as $list) {?>
          <li>
          <a href="<?php echo $this->webroot.'group/group_list/'.$list['Category']['id'];?>">
            <div class="category-img">
            <?php if(!empty($list['Category']['image'])){?>
             <img src="<?php echo $this->webroot.'category_photos/medium/'.$list['Category']['image'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> <?php } ?>
               <div class="overlay-bg"></div>
              <div class="category-name">
                <span><?php echo ucfirst($list['Category']['title'])?></span>
                <p><?php echo ($list['Category']['category_desc'])?></p>
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
    </div>
	</main>
  </div>
  
  <script>
  	$(":file").filestyle({badge: false});
  </script>

    <script>
  function showMyImage(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil");
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