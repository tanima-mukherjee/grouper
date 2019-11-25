<?php if(!empty($ArCity)){?>
<option value="">Select City</option>
<?php foreach($ArCity as $City){ ?>
<option value="<?php echo $City['City']['id'];?>"><?php echo $City['City']['name'];?></option>
<?php } ?>
<?php }else{?>
<option value="">No City Found</option>
<?php } ?>