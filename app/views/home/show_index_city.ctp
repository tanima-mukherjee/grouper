<option value="">Select City</option>
<?php if(!empty($indexcitylist)){ ?>
	<?php foreach($indexcitylist as $array){ ?>
	<option value="<?php echo $array['City']['id']?>"><?php echo htmlentities(stripslashes($array['City']['name']));?></option>
	<?php } ?>
	<?php }else{ ?>
	<option value="0"><?php __d('statictext', 'Select City', false); ?></option>
<?php }?>