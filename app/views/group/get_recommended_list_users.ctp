<?php 		
if(!empty($arr_search_result)) {
echo '<ul>';	
	foreach($arr_search_result as $key => $val){
?>
	
	<li onclick="fill_users('<?php echo $val['User']['fname']; ?>', '<?php echo $val['User']['lname']; ?>', '<?php echo $val['User']['id']; ?>')" >
		<div class="search-userimg">
		<?php if(!empty($val['User']['image'])) { ?>
			<img src="<?php echo $this->webroot;?>user_images/thumb/<?php echo $val['User']['image']; ?>" />
		<?php } else { ?>
			<img src="<?php echo $this->webroot?>images/no_profile_img.jpg" alt="" /> 
		<?php } ?>
		</div>
		<div class="search-username"><?php echo $val['User']['fname'].' '.$val['User']['lname']; ?></div>
		<div class="clearfix"></div>
	</li>
<?php	
	}
echo '</ul>';
} else {
	echo '0';	
} 

?>