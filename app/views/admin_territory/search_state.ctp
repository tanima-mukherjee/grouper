<?php if(!empty($ArSearchStates)){ ?>
<div class="box-list">
<ul>
<?php foreach($ArSearchStates as $SearchState) {?>
	<li><input type="radio"  value="<?php echo $SearchState['State']['id'];?>" onclick="search_cities(<?php echo $SearchState['State']['id'];?>)" name="state" id="state_<?php echo $SearchState['State']['id'];?>"/> <?php echo $SearchState['State']['name'];?></li>
<?php } ?>
</ul>
</div>
<?php }else{
	echo 'No State found';
} ?>



				
	
<script>
 

    function search_cities(state_id){

    	jQuery("#city_ids").val('');

      var territory_id = <?php echo $territory_id;?>;
  //alert(state);
  //alert(search_string.length); //return false;
  if(state_id!=""){
    jQuery("#city_list").show("");
    jQuery("#city_list").html("<img style='margin-left:30%;' src='<?php echo $this->webroot;?>images/ajax_loader-2.gif'>");
    //return false;
   
    jQuery.ajax({
      type: "GET",
      url: "<?php echo $this->webroot?>admin_territory/search_cities/",
      data: { state_id: state_id, territory_id: territory_id},
      success: function(msg)
      {       
        //alert(msg);
        jQuery("#state_ids").val(state_id);
        jQuery("#city_list").html(msg);
                        
      }
    });
  }else{
    $("#city_list").html("");  
  }
}
</script>