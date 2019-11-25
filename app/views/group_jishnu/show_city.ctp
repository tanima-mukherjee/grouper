<h2>Select City</h2>
<div class="clearfix"></div>
<div class="city-list">		

<?php  $counter = 1;?>
<ul class="city-column">
<li><a href="javascript:void(0)" onclick="StateList()" class="changelocation"><strong>Change Location</strong></a></li>							
<?php  foreach($citylist as $array){ ?>

<li><a href="javascript:void(0)" onclick="SingleCity(<?php echo $array['City']['id'];?>)" ><?php echo ucfirst($array['City']['name'])?></a></li>
<?php 
$counter ++; 
if ($counter%100 == 0) {
echo '</ul>';    
echo '<ul class="city-column">';
}

}  
?>
</ul>

