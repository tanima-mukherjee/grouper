<?php  
if(!empty($selectedgrouplist)) { ?>
						<option value="">Select One</option>
                      <?php  foreach($selectedgrouplist as $data1){?>
                        <option value="<?php echo $data1['Group']['id'];?>" ><?php echo htmlentities(stripslashes($data1['Group']['group_title']));?></option>
                         <?php } } else{ ?>
			<option value="0">No Groups Found</option>
<?php } ?>