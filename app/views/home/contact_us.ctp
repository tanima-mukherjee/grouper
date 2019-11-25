<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script> 
 <script>
var WEBROOT = '<?php echo $this->webroot;?>';
  function AllCity(state_id){
    //alert(state_id);
      if(state_id != ''){
         jQuery.ajax({
            type: "GET",
            url: WEBROOT+"home/show_city",
            data: {state_id:state_id},
            success: function(msg){
            //alert(msg);
               
            jQuery("#city_id1").html(msg);
           
            
            }
        });
      }
    }
</script>

  <script> 
   $(function() {
    var validator = $("#contact_form").submit(function() {
      // update underlying textarea before submit validation
    }).validate({
    rules: {
      salutation: {
        required: true
         },
         email:{
        required: true,
        email: true
         },
          full_name: {
        required: true
         },
          address:
          {
            required: true
          },
         city_id1:
         {
           required: true
         },
         state_id:
         {
          required: true
         },
         zip:
         {
          required: true
         },
         captcha: {
       required: true,
       captcha_valid: true
     },
        password: 
        {
           required: true,
           minlength : 6
         },
               message: {
                required: true
               },
              phn_no1: { required: true },
                phn_no2: { required: true },
                phn_no3: { required: true }
        },
             groups: {
            phone: "phn_no1 phn_no2 phn_no3"
        },
    errorPlacement: function(label, element) {
        // position error label after generated textarea
        
          label.insertAfter(element.next());
        
      },
            
    messages: {
      salutation: {
        required:"Please select salutation"
      },
      email:{
        required:" Please enter your valid email Id"
      },
            full_name: {
        required: "Name is required"
         },
            password: {
        required: "Please enter password"
         },
         address: 
         {
          required: "Please enter address"
        },
         city_id1:
         {
          required:"Please enter city"
         } ,
         state_id:
         {
          required: "Please enter state"
         },
         zip:
         {
          required:"Please enter zip"
         } ,
         captcha: {
       required: "Please enter answer",
       captcha_valid: "Wrong answer"
     },
            phn_no1: {
                required: "Please enter contact number"
               },
               phn_no2: {
                required: "Please enter contact number"
               },
               phn_no3: {
                required: "Please enter contact number"
               },
            message:{
                required: "Please enter message"
               }
    }
    
  });
});


</script>

  <!------------------------Auto tab functionality Starts----------------------> 
 
<script type="text/javascript" src="<?php echo $this->webroot ?>js/jquery.autotab.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$('#phn_no1, #phn_no2, #phn_no3').autotab_magic().autotab_filter('numeric');
});
</script>

<!------------------------Auto tab functionality Ends---------------------->
 
  <main class="main-body"> 
<div class="contact-wrapper">
    <h2>Contact</h2>
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <h4>LET'S GET IN TOUCH</h4>
          <p>Thank you for your interest in Groopzilla.  We appreciate feedback from our Groopers so please contact us anytime you have any suggestions, questions, problems, or general comments.  We are always interested in improving and will do our best to respond promptly to your concerns. </p>

<p>And should you know of anyone interested in promoting Groopzilla in their community, please let us know!  We offer territorial licensing agreements on a "per community" basis which provide excellent income and flexibility of schedule for those interested in helping promote local businesses, local activities, and community communications.  </p>

<p>"I would rather attempt to do something GREAT and fail, than to do nothing and SUCCEED."  - Robert Schuller</p>
          <form action="<?php echo $this->webroot?>home/submit_contact_us" name="contact_form" id="contact_form" method="post">
          <input type="hidden" name="mode" id="mode" value = "contact">
          <div class="row">
          <div class="col-sm-12">
		    <div class="form-group">
            <div class="contact-label">
              <input type="radio" name="query_type" value="General Query" id="query_type1" checked="checked" >
              <label for="query_type1" onclick="">General Query</label>
            </div>
            <div class="contact-label">
              <input type="radio" name="query_type" value="Interested in Franchise/Operator Agreement for my Community" id="query_type2">
              <label for="query_type2" onclick="">Interested in Franchise/Operator Agreement for my Community</label>
            </div>
          <div class="clearfix"></div>   
          </div>
          <div class="clearfix"></div>  
          </div> 
		</div>
          <div class="row">
          <div class="col-sm-6">
			<div class="form-group">
				 <select class="form-control" name="salutation" id="salutation">
				  <option value="">Select Salutation</option>
				  <option value="Mr.">Mr.</option>
				  <option value="Mrs.">Mrs.</option>
				  <option value="Miss.">Miss.</option>
				 </select>
         <div class="clearfix"></div>  
			 </div>
          </div>
          <div class="col-sm-6">
			<div class="form-group">
				<input type="text" placeholder="Name" class="form-control" name="full_name" id="full_name">
        <div class="clearfix"></div>  
			</div>
          </div>
          <div class="clearfix"></div>  
          </div>  
          <div class="row">
			  <div class="col-sm-6">
				 <div class="form-group">
					<input type="email"  placeholder="Email" class="form-control" name="email" id="email">
           <div class="clearfix"></div>        
				</div>
			  </div>         
			 <div class="col-sm-6">
				 <div class="form-group">
					<div class="phone-1">
						<input type="text" id="phn_no1" name="phn_no1" placeholder="###" class="form-control" maxlength="3">
					</div>
					<span class="line-devider"> - </span>
				   <div class="phone-1">
					<input type="text" id="phn_no2" name="phn_no2"  placeholder="###" class="form-control" maxlength="3">
				   </div>
				  <span class="line-devider"> - </span>
				   <div class="phone-4">
					<input type="text" id="phn_no3" name="phn_no3" placeholder="####" class="form-control" maxlength="4">
           <div class="clearfix"></div>
                                 <label style="display:none;" for="phone" generated="true" class="error">Please enter contact number</label>
				   </div>
				   <div class="clearfix"></div>
			   </div>
				 <div class="clearfix"></div>                   
			  </div> 
			  <div class="clearfix"></div>  
          </div>
         
        <div class="row">     
              <div class="col-sm-6"> 
				 <div class="form-group">
				<select class="form-control" name="state_id" id="state_id" onChange="AllCity(this.value);">
					 <option value="">Select State</option>
					  <?php 							
						  foreach($state_list as $State){?>
							 <option value="<?php echo $State['State']['id'];?>" ><?php echo $State['State']['name'];?></option>
				   <?php } ?>				   
				 </select>
         <div class="clearfix"></div>   
		 </div>
         </div> 
        <div class="col-sm-6">
			<div class="form-group">
			<select class="form-control" name="city_id1" id="city_id1">
				 <option value="">Select City</option>
				  <?php                 
					  foreach($citylist as $City){?>
						 <option value="<?php echo $City['City']['id'];?>" ><?php echo $City['City']['name'];?></option>
			   <?php } ?>       
			 </select>
         <div class="clearfix"></div>   
         </div>  
      </div> 
	  </div>
	<div class="row">	  
		<div class="col-sm-6">
			<div class="form-group">
			 <input type="text" placeholder="Zip Code" class="form-control" name="zip" id="zip">
        <div class="clearfix"></div>   
			</div> 
		  </div>
		  <div class="col-sm-6">
			<div class="form-group">
			 <input type="text" placeholder="Address" class="form-control" name="address" id="address">
        <div class="clearfix"></div>   
			</div> 
		</div>
	 </div>	  
	<div class="row">
        <div class="col-sm-12">
			<div class="form-group">
				<textarea placeholder="Your Message" name="message" id="message" class="form-control msg"></textarea>
         <div class="clearfix"></div>   
           </div>
          <div class="clearfix"></div>   
        </div>
	</div>
  <?php
  $a = 7;
  $b = 7;
  $c = $a + $b;
?>
	<div class="row">
        <div class="col-sm-12">
		  <div class="form-group">
			<div class="label-text"><?php echo $a; ?> + <?php echo $b; ?> = </div>
			<div class="label-info-value">
				<input type="number" class="form-control" name="captcha" id="captcha">
          <div class="clearfix"></div> 
			</div>
		  </div>
          <div class="clearfix"></div>   
        </div>
	</div>	
          <div class="form-group">
           <input type="submit" value="Submit" name="submit" id="submit" class="submit-btn">
          </div>
         <div class="clearfix"></div>      
        </form>
        </div>
        <div class="col-md-4">          
          <div class="contactbox">
            <h3>Groopzilla</h3>
            <ul>
              <!--<li><i class="fa fa-map-marker"></i> <span><?php //echo $contact_address;?></span></li>
              <li><i class="fa fa-envelope-o"></i> <span><?php //echo $sender_email;?></span></li>
              <li><i class="fa fa-phone"></i> <span><?php //echo $contact_phone;?></span></li>-->
			  <li><i class="fa fa-map-marker"></i><span>Grouper USA, LLC<br />5342 Clark Road, #118<br />Sarasota, FL 34233</span> </li>
            </ul>
          </div>                    
        </div>
      </div>
     <!--  <div class="contact-map">
       
        <iframe width="600" height="450" frameborder="0" style="border:0" allowfullscreen  src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=<?php echo $contact_latitude;?>,<?php echo $contact_longitude;?>&amp;output=embed"></iframe>
      </div> -->
    </div>
    
  </div>

    
  </main>

  <script>
jQuery(document).ready( function() {
   jQuery.validator.addMethod("captcha_valid", function(value, element){
      <?php echo 'var captchaAnswer = ' . $c . ';'; ?>
      var captchaEntered = document.getElementById("captcha").value;
     //alert(captchaEntered);
    if(captchaAnswer!=captchaEntered) {
      return false;
    } else {
      return true;
    }
   },"Wrong answer");
});
</script>
