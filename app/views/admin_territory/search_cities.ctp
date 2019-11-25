<?php if(!empty($ArSearchCity)){ ?>
 <div class="box-list">
<ul>
<?php foreach($ArSearchCity as $SearchCity) {?>
	<li id="city_class_<?php echo $SearchCity['City']['id'];?>"><a href="javascript:void(0)" onclick="select_city(<?php echo $SearchCity['City']['id'];?>)"><?php echo $SearchCity['City']['name'];?></a></li>
<?php } ?>
</ul>
</div>
<?php }else{
  echo 'No City found';
} ?>
	
<script>
function isValueInArray(arr, val) {
inArray = false;
for (i = 0; i < arr.length; i++)
if (val == arr[i])
inArray = true;
return inArray;
}

var checked_items = new String();
function select_city(val){
   
   var value = val;
   var checked_items= jQuery('#city_ids').val();

   if(!jQuery("#city_class_"+val).hasClass("active"))
   {
      jQuery("#city_class_"+val).addClass("active");
   }else{
      jQuery("#city_class_"+val).removeClass("active")
   }

   
   if(checked_items!=''){
         var arr_selected_items = checked_items.split(',');
         if(isValueInArray(arr_selected_items, value)){
           
            checked_items = remove_item(value);
         }
         else{
         
            checked_items = checked_items+','+value;
         }
   }
   else{
        var checked_items= value;
        
   }
   jQuery('#city_ids').val(checked_items);
}


function remove_item(val){
   //alert(val);
   var current_items = jQuery('#city_ids').val();
   //alert('---'+current_items);
   current_items='@'+current_items+'@';
   newval = ','+val+',';
   newval2 = '@'+val+',';
   newval3 = ','+val+'@';
   newval4 = '@'+val+'@';
   
   current_items = current_items.replace(newval2, ',');
   current_items = current_items.replace(newval, ',');
   current_items = current_items.replace(newval3, ',');
   current_items = current_items.replace(newval4, '');
   
   current_items = current_items.replace(",,", ',');
   current_items = current_items.replace("@", '');
   current_items = current_items.replace("@", '');
   
   var len = current_items.length;
   
   if(current_items.charAt(0)==','){
      current_items = current_items.substr(1);
      jQuery('#city_ids').val(current_items);
   }
   else if(current_items.charAt(len-1)==','){
      current_items = current_items.substr(0,len-1);
      jQuery('#city_ids').val(current_items);
   }
   else{
      jQuery('#city_ids').val(current_items);
   }
   alert(current_items);
   return(current_items);

}
</script>