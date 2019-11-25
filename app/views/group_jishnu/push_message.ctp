<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="<?php echo $this->webroot ?>js/jquery.validate.js" type="text/javascript"></script>

<script>
jQuery(document).ready( function() {
     var validator = $("#join_request_form").submit(function() {
         // update underlying textarea before submit validatiocn
      }).validate({
      rules: {
	  	"push[]": { 
				required: true, 
				minlength: 1 
		}, 
        message :{
        required: true
        }
      },
      errorPlacement: function(label, element) {
         // position error label after generated textarea
         label.insertAfter(element.next());
      },
       messages: {
	   	  "push[]": {        
			required:" Please check atleast one user"
		  }, 
		  message : {        
			required:" Type the message"
		  } 
      }
   });
   
   
});
</script>

<script>
function check_members(){

//select all checkboxes
$("#all_members").change(function(){  //"select all" change 
    var status = this.checked; // "select all" checked status
    $('.checkindividual').each(function(){ //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status
    });
});

$('.checkindividual').change(function(){ //".checkbox" change 
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){ //if this item is unchecked
        $("#all_members")[0].checked = false; //change "select all" checked status to false
    }
    
    //check "select all" if all checkbox items are checked
    if ($('.checkindividual:checked').length == $('.checkindividual').length ){ 
        $("#all_members")[0].checked = true; //change "select all" checked status to true
    }
});
}
</script>

<!DOCTYPE html>
<html lang="en">
  <head>
	 <meta charset="utf-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grouper</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
      <!-- Bootstrap -->
    <link href="<?php echo $this->webroot?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $this->webroot?>css/font-awesome.min.css" rel="stylesheet"> 
   <!-- custom css -->  
   <link href="<?php echo $this->webroot?>css/main.css" rel="stylesheet"> 
	<style>
		body{
			background-color:transparent;
		}
		
		.action-checkbox .error
		{			
			position: absolute;
			left: 0;
			bottom: 203px;
			text-align: center;
			width: 100%;
			background: #ccc;
			padding: 2px 0;
		}
	</style>
  </head>
<body>
	<div class="user-dialog">
     <div class="modal-header">        
        <h4 class="modal-title">Push Message</h4>
  		<?php      
		if(count($group_members) > '0'){ ?>
		<div class="select-all-checkbox">
			<!-- <input type="checkbox" /> -->
      		<input type="checkbox" name="all_members" id="all_members" onClick="check_members();">
			<label>Select All</label>						
		</div>
   	 	<?php } ?>
      </div>
 	  <form action="<?php echo $this->webroot?>group/push_message_sent" method="post" id="join_request_form" name="join_request_form" target="_parent">  
    <div class="modal-body" id = "user_list">
    <?php 
      if(count($group_members) > '0'){ ?>

	<div class="push-message-scroll">
      <ul class="userlist">
      <?php  
      foreach ($group_members as $list){ ?>
      <li>
        <div class="view-userimg">
          <?php if($list['image_url']!='') { ?>
          		<img src="<?php echo $this->webroot.'user_images/thumb/'.$list['image_url'];?>" alt="<?php echo $list['user_name']; ?>" />
          <?php } else { ?>
             	<img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="<?php echo $list['user_name']; ?>" />
          <?php } ?>
        </div>
        <div class="view-userdes">
          <div class="align-center">
            <span class="user-request-display"><?php echo ucfirst($list['user_name']) ;?>

             <?php if($list['user_type'] == 'O' ) { ?>
					<span class="user-group-post"> Owner </span>
        	 <?php } else { ?>
          			<span class="user-group-post"><?php echo ucfirst($list['member_mode']);?> </span>
        	 <?php } ?>
			</span>
            <div class="actions actions-right action-checkbox" >
                  <input type="checkbox" class="checkindividual" value="<?php echo $list['user_id']?>" id="push<?php echo $list['user_id'] ?>" name="push[]" />
				  <label>&nbsp;</label>
         			<div class="clearfix"></div>
					<label generated="true" class="error" style="display:none;"> Please check atleast one user</label>
            </div>
          
          </div>
        </div>
        <div class="clearfix"></div>
      </li>
       <?php 
         
          } ?>
    </ul>
	</div>
    <div class="clearfix"></div>
	
	<div class="message-bottombg">
		<p style="color:#FF0000; font-size:14px; display:none;" id="err_check1">Please check minimum one user !!!</p>
        <div class="push-message">
              <label class="label-display">Message</label>
              <textarea name="message" id="message" ></textarea>     
               <div class="clearfix"></div>  
			   <p style="color:#FF0000; font-size:14px; display:none;" id="err_check2">Please put your message !!!</p>      
        </div> 
        <input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id; ?>">  
		<input type="hidden" name="group_type" id="group_type" value="<?php echo $group_type; ?>">  
		<input type="hidden" name="group_title" id="group_title" value="<?php echo $group_title; ?>">
         
		<div class="group-type-submit">
		 <button type="submit">Submit</button>
		</div>
      
	  </div>

	<?php } else {  ?>
        <div class="no-authorized" >                                        
           Sorry ! Add Group Member first !!!.                  
        </div>
    <?php } ?>
      </div>

      </form>
	</div>  
</body>
</html>


							