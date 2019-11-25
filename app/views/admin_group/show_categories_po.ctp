<option value="0">Select One</option>
<?php 
	if(isset($all_categories)) {
	   foreach($all_categories as $data1) { ?>
<option value="<?php echo $data1['Category']['id'];?>"><?php echo $data1['Category']['title'];?></option>
<?php }} ?>