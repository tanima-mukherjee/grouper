<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<script >
  $(document).ready( function() {
      //var radio2 = document.getElementById("is_free").value;
      //var radio1 = document.getElementById("is_business").value;
      //var radio2 = $("#is_free");
      
      /*alert(radio1);
      alert(radio2);*/
      var validator = $("#group_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
         group_name: "required",
         upload_image: "required",
         g_desc: "required",
         plan_id:
         { 
         required: {
                    depends: function (element) {
                        return $("input[type='radio'].service_type:checked").val() == 'business';
                    }
                }
              }, 
         card_no: {
          required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == 'business';
                    }
                },  
            minlength: 13,
            maxlength: 16,
            digits: true
         },
         cardholder_name:
         { 
         required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == 'business';
                    }
                }
              }, 
         cvv_code: {
        
         required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == 'business';
                    }
                },  
            minlength: 3,
            maxlength: 3,
            digits: true
         },
         expiry_month :
         { 
         required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == 'business';
                    }
                }
              },
         expiry_year : 
          { 
         required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == 'business';
                    }
                }
              },

          cvv_number : 
            { 
         required: {
                    depends: function () {
                        return $("input[type='radio'].service_type:checked").val() == 'business';
                    }
                }
              }
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
        
         group_name : "Please enter group name",
         g_desc : "Please enter group description",
         upload_image:"Please upload  image",
          plan_id: {
            required: "Please select a plan"
           }, 
         card_no: {
            required: "Please enter card code"
           }, 
         cardholder_name:{
            required: "Please enter card holder name"
           }, 
       expiry_month: {
            required: "Please enter expiry month"
           },
        
        expiry_year: 
         {
            required: "Please enter expiry year"
         },
          cvv_number: 
         {
            required: "Please enter cvv number"
         }
    
      }
   });
});
</script>
<script >
  
 $(document).ready(function() {      
    var validator = $("#edit_category_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         title:  "required",   
        // c_upload_image: "required",
         desc:  "required"
      },
      errorPlacement: function(label, element) {
            // position error label after generated textarea
            
               label.insertAfter(element.next());
            
         },
      messages: {
         title: "Please enter a title",
       //  c_upload_image:"Please upload  image",
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
<script>
  
jQuery(document).ready(function(){
  jQuery("input:radio[name=selecetone]").click(function() {
        var value = $(this).val();
        //alert(1);
        if(value == 'business'){
      
      jQuery("#payment_details").show();
        }else{
         
      jQuery("#payment_details").hide();
      
        }
    });
});
</script>

<!-- Sign up Modal -->
  <div class="modal fade" id="creategroup" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->

      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Create Group</h4>
    </div>
       
    <div class="modal-body">
   <form action="<?php echo $this->webroot?>group/add_group" method="post" id="group_form" name="group_form" enctype="multipart/form-data">  

  <div class="pop-createg">
  <!-- <div class="pop-createg-round"><img src="<?php echo $this->webroot?>images/author-img01.jpg"  alt=""></div> -->
  <div class="upload-img">
  <div class="pop-createg-round">
                     
                        <img id="thumbnil" src="<?php echo $this->webroot?>images/no_profile_img55.jpg" >
    </div>

   <!--  <div class="cmicon"><img src="<?php echo $this->webroot?>images/cam-icon.png" alt=""></div> -->
   <span class="btn-bs-file browse-btn">Browse</span>
   <input onChange="showMyImage(this)" name="upload_image" id="image455" data-badge="false" type="file"/>
	<div class="clearfix"></div>
    </div>
    
  </div>
  
    <div class="signup-left">
   
     
      <input type="hidden" name="category_id" value="<?php echo $category_id;?>">  

  
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
    
  <!--   <div class="form-group">
        <label class="label-field">Choose Type</label>
    <div class="clearfix"></div>
        <div style="margin-right:15px; float:left;"><input name="" type="radio" value=""> Business</div>   <input name="" type="radio" value=""> Free  

         <div class="clearfix"></div>          
      </div>  -->
      <div class="form-group">
                      <label class="label-field">Choose Type</label>
                     <div class="clearfix"></div>
                          <span class="search-type">
                            <input type="radio" name="selecetone" class ="service_type" value="business" id="is_business" />
                            <label for="is_facility">Business</label>
                          </span>
                          <span class="search-type">
                          <input type="radio" name="selecetone" class ="service_type" value="free"  id="is_free" checked/> 
                          <label for="is_free">Free</label>
                          </span>
                        
                    </div>

       <div id="payment_details" style="display:none" class="payment-options">
       <select  name="plan_id" id="plan_id" class="form-control">
                            <option value="">Select One</option>
                            <?php 
                            if(isset($plan_list)){
                            foreach($plan_list as $Plan){?>
                                      <option value="<?php echo $Plan['SubscriptionPlan']['id'];?>"><?php echo $Plan['SubscriptionPlan']['plan_title'].' - $'.$Plan['SubscriptionPlan']['amount'];?></option>

                                      <?php } ?>
                                     
                          <?php } ?>
       </select>
        <div class="clearfix"></div>
            <h3>Payment Option :</h3>
             
               <div class="form-group">
                  <input type="text" placeholder="Card No" name="card_no" id="card_no" class="form-control">
                  <div class="clearfix"></div>  
               </div>
               <div class="form-group">
                  <input type="text" placeholder="Card Holder Name" name="cardholder_name" id="cardholder_name" class="form-control">
                  <div class="clearfix"></div>  
               </div>
               
                  <div class="form-group">
                     <input type="text" placeholder="Expiry month" name="expiry_month" id="expiry_month" class="form-control">
                     <div class="clearfix"></div>  
                  </div>
                  
                  <div class="form-group">
                     <input type="text" placeholder="Expiry year" name="expiry_year" id="expiry_year" class="form-control">
                     <div class="clearfix"></div>  
                  </div>               
               
               <div class="form-group">
                  <input type="text" placeholder="CVV" name="cvv_number" id="cvv_number" class="form-control">
                  <div class="clearfix"></div>  
               </div>
           
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
<!-- category modal start -->
<div class="modal fade" id="editcategory" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->
      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Edit Category</h4>
    </div>

    <div class="modal-body">  
    <form action="<?php echo $this->webroot?>group/edit_category/<?php echo $category_details['Category']['id']; ?>" method="post" id="edit_category_form" name="edit_category_form" enctype="multipart/form-data">
    <div class="pop-createg2">
    <!-- <div class="pop-createg-img"><img src="<?php echo $this->webroot?>images/categori-details-img.jpg" alt=""></div> -->
    <div class="upload-img">
    <div class="pop-createg-img">
    <?php if($category_details['Category']['image']!=''){?>
                        <img id="thumbnil1" src="<?php echo $this->webroot;?>category_photos/<?php echo $category_details['Category']['image'];?>" alt="">
                        
                        <?php }else{ ?>
                        <img id="thumbnil1" src="<?php echo $this->webroot?>images/no_profile_img55.jpg">
                          <?php } ?>
                     
                        
    </div>
    
    
    <span class="btn-bs-file cmicon2">
                Browse
    <input onChange="showMyCatImage(this)" name="c_upload_image" id="c_upload_image" value="<?php echo $category_details['Category']['image'];?>" data-badge="false" type="file"/>
    </span>
    </div>
     <div class="clearfix"></div>
    <label for="c_upload_image" generated="true" class="error" style="display:none;">Please upload  image</label>
    
    <!--<input type="file" onChange="showMyImage(this)" class="filestyle" name="profile_image" id="image" data-badge="false">-->
     
    </div>
  
    <div class="signup-left">
    
      <div class="form-group">
        <label class="label-field">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?php echo ucfirst($category_details['Category']['title'])?>">   
         <div class="clearfix"></div>          
      </div>
      <div class="form-group">
        <label class="label-field">Description</label>
    <textarea id="desc" name="desc" class="form-control" cols="" rows=""><?php echo ucfirst($category_details['Category']['category_desc'])?></textarea>  
       
         <div class="clearfix"></div>                   
      </div>    
                  
            
      <button type="submit" class="btn signupbtn">Update</button>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>
<!-- category modal end -->

<main class="main-body">
  <div class="category-details-content">
    <div class="container">
      <div class="heading-title">
     
	  <div class="page-top-line">
	  <div class="page-right-nav">
		<ul>	
     <?php if( $category_details['Category']['user_id'] == $user_id ) { ?>	
		   <li>
			<a href="#" data-toggle="modal" data-target="#editcategory" class="create-link fright">Edit Category <i class="fa create-icon"></i></a>
		  </li>
		  <?php } ?>		  
		  <li>
			<a href="#" data-toggle="modal" data-target="#creategroup" class="create-link fright">Create Group <i class="fa create-icon"></i></a>
		  </li>
	  </ul>
	  </div>
	  </div>
      <div class="clearfix"></div>
      </div>      
      <div class="category-details">      
      <div class="category-heading-details">
        <div class="category-details-img">
          <img src="<?php echo $this->webroot.'category_photos/web/'.$category_details['Category']['image'];?>" alt="" />
        </div>
        <div class="category-des-info">
          <h4><?php echo ucfirst($category_details['Category']['title'])?></h4>
          <p> <?php echo ($category_details['Category']['category_desc'])?>
          </p>          
        </div>
      <div class="clearfix"></div>  
      </div>
    <div class="clearfix"></div>

     <?php if(count($group_list)>0){?>
      <div class="group-list-item">
        <h3>Group list</h3>
        <ul>
          <?php   foreach ($group_list as $list) {?>
          <li>
           <a href="<?php echo $this->webroot.'group/group_detail/'.$list['Group']['id'];?>">
            <div class="groupbox">
              <div class="groupbox-img">
               <?php if(!empty($list['Group']['icon'])){?>
             <img src="<?php echo $this->webroot.'group_images/web/'.$list['Group']['icon'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> <?php } ?>
              </div>
              <div class="group-maskbg"></div>
              <div class="group-overlay">
              <a href="#" class="group-btn invite">Join now</a>
              <a href="#" class="group-btn view-user">View users</a>
              </div>
            </div>  
            <div class="group-name">
              <h4><?php echo ucfirst($list['Group']['group_title'])?></h4>
            </div> 
             </a> 
          </li>
       <?php } ?>

        </ul>
      </div>
     

      <div class="clearfix"></div>  
      </div>
      <?php } else { ?>
        <div class="no-item-found">
        No groups found
        </div>
        <?php } ?>
    </div>
  </div>  
    
</main>   
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

 <script>
    $(":file").filestyle({badge: false});
  </script>

    <script>
  function showMyCatImage(fileInput) {
    var files = fileInput.files;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img = document.getElementById("thumbnil1");
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
