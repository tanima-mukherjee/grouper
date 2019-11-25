<?php 
session_start();
$con = mysql_connect('localhost','root','mysqlpass'); //local
//$con = mysql_connect('localhost','ogmaconc_main','main@123'); // demo server
//mysql_select_db('ogmaconc_elite_partner',$con);
mysql_select_db('elite_partner',$con);
?>