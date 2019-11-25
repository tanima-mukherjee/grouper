<?php foreach($TerritoryAssign as $Assign){?>
<div class="accordion-group" id="state_div_<?php echo $Assign['TerritoryAssign']['id'];?>">
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#State_<?php echo $Assign['TerritoryAssign']['id'];?>">
<span class="collapse-icon"></span> <?php echo $Assign['State']['name'];?>                   
</a>
<span class="remove-position">
<a href="javascript:void(0)" onclick="delete_state(<?php echo $Assign['TerritoryAssign']['id'];?>,<?php echo $Assign['State']['id'];?>)"><img src="<?php echo $this->webroot; ?>images/remove-round.png" alt="" /> </a>
</span>
</div>
<div id="State_<?php echo $Assign['TerritoryAssign']['id'];?>" class="accordion-body collapse">
<div class="accordion-inner">
<div class="search-listitem">
<?php 
$ArCityList = $this->requestAction('admin_territory/getCityList/'.$Assign['TerritoryAssign']['territory_id'].'/'.$Assign['State']['id']);
//pr( $ArCityList);
?>
<ul>
<?php foreach($ArCityList as $City){?>
<li id="city_div_<?php echo $City['City']['id'];?>"><?php echo $City['City']['name'];?>
<a href="javascript:void(0)" onclick="delete_city(<?php echo $City['City']['id'];?>,<?php echo $City['TerritoryAssign']['id'];?>,<?php echo $City['TerritoryAssign']['territory_id'];?>)"><img src="<?php echo $this->webroot; ?>images/city-remove.png" alt="" /></a></li>
<?php } ?>
</ul>
</div>
</div>
</div>
</div>
<?php } ?>