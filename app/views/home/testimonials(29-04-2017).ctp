 <script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>
<script >
  $(document).ready( function() {
 
      var validator = $("#testimonial_form").submit(function() {
         // update underlying textarea before submit validation
      }).validate({
      rules: {
         
      desc:    {
      required: true,
      maxlength: 300
               }
        
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
      messages: {
         desc: {
            required : "Please enter testimonial",
            maxlength : "Testimonial should be within 300 char"
            }
      
      }
   });
});
</script> 

 <style>
   .error{
    color: #f00;
   }
 </style>     <!--Start Modal content for testimonial -->
 <div class="modal fade" id="createtestimonial" role="dialog">
    <div class="modal-dialog signup-model">    
    <div class="modal-content">
      <!-- Modal content-->

      <div class="modal-header signup-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Add Testimonial</h4>
    </div>
       
    <div class="modal-body">
   <form action="<?php echo $this->webroot?>home/add_testimonial" method="post" id="testimonial_form" name="testimonial_form" enctype="multipart/form-data">  

  
    <div class="signup-left">
   
      <div class="form-group">
       
       <textarea name="desc" id="desc" class="form-control" cols="" rows="" ></textarea>
         <div class="clearfix"></div>                   
      </div> 
      
            
      <button type="submit" class="btn signupbtn">Submit</button>
      </form>
    <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>  
    </div>
    </div>    
      </div>
      
    </div>
<!--End  Modal content for testimonial -->

  <main class="main-body">  
<div class="wrapper">
    <div class="container">
	<div class="heading-title">
        <h2>Testimonials</h2>
     </div>
	  <div class="page-top-line">
       <div class="page-right-nav">
          <ul>
          <li><a href="#" data-toggle="modal" data-target="#createtestimonial" class="create-link fright">Add Testimonials <i class="fa create-icon"></i></a></li>
          
          </ul>
        </div>
              <div class="clearfix"></div>  
      </div>
      <div class="testimonials-wrapper">
      <?php if(count($testimonial_list)>0) { ?>
      
         <?php  
            foreach ($testimonial_list as $list) { ?> 
       <div class="testimonials-box">
       
          <div class="testimonials-user-img">
            <?php if(!empty($list['User']['image'])){ ?>
            <img src="<?php echo $this->webroot.'user_images/'.$list['User']['image'];?>" alt=""/> <?php } else { ?><img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> <?php } ?>
            </div>
         
    
        <div class="testimonials-des">
          <h4><?php echo ucfirst($list['User']['fname'].' '.$list['User']['lname']); ?></h4>
		 <h5>Posted on :<?php echo date("jS M Y",strtotime($list['Testimonial']['created'])); ?> </h5>
          <p><?php echo stripcslashes($list['Testimonial']['desc']); ?></p>
         
          <div class="clearfix"></div>
          
        
                       
        </div>
      <div class="clearfix"></div>  
      </div>
      
        <?php } ?>

    
      <div class="clearfix"></div>
       <?php } ?>
           <div class="clearfix"></div>
           <div class="pagination">
                <?php //echo $this->Paginator->counter(); ?>
                <?php
                $urlparams = $this->params['url'];
                //pr($urlparams);
                unset($urlparams['url']);
                //pr($urlparams); 
                $paginator->options(array('url' => array('?' => http_build_query($urlparams))));
       // $paginator->options(array('url' => array($this->passedArgs['0'], $this->passedArgs['1'], $this->passedArgs['2'], '?' => http_build_query($urlparams))));
                ?>
                <?php echo $this->Paginator->first(__('First', true), array('class' => 'disabled')); ?>
                <?php if ($this->Paginator->hasPage(2)) { ?>
                 

                <?php }
                ?>
                <?php echo $this->Paginator->numbers(array('separator' => ' ', 'class' => 'numbers', 'first' => false, 'last' => false)); ?>
                <?php
                if ($this->Paginator->hasPage(2)) {
                  echo $this->Paginator->next(__(' ', true) . 'Next', array(), null, array('class' => 'disabled'));
                }
                ?>
                <?php echo $this->Paginator->last(__('Last', true), array('class' => 'disabled')); ?>
              </div>
                
      </div>
   
    </div>

    </div>
</main>
