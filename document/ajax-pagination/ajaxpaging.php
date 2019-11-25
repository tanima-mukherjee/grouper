<?php 
session_start();
require_once("../../includes/config.php");
require_once("../../includes/functions.php");
if(isset($_REQUEST['page']))
{
	$PreRegUserSQL = "SELECT * FROM ".PREREG." WHERE school_id ='".$_SESSION['school_id']."' ORDER BY email";
	$total_record = mysql_num_rows(mysql_query($PreRegUserSQL));
	$limit = RECORDPERPAGE;
	$lastpage = ceil($total_record/$limit);
	$start=($_REQUEST['page'] - 1) * $limit;
	$page = $_REQUEST['page'];
	$prev = $page - 1;
	$next = $page + 1;
	$lpm1 = $lastpage - 1;
	$PreRegUserSQL.= " LIMIT $start,$limit";
	$PreRegUserRS = mysql_query($PreRegUserSQL);
	$paginationData = paging_ajax($lastpage,$page,$prev,$next,$lpm1,$prefix,"paging");	
	
	?>
	<TABLE width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
			<TR><TD>
				<TABLE width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
				<?php 
				while($preregROW = mysql_fetch_array($PreRegUserRS))
				{			
			?>
			<tr onMouseOver="this.bgColor='<?php echo SCROLL_COLOR;?>'" onMouseOut="this.bgColor=''">
				<td width="8%" height="22" align="center" valign="top"><?php echo ++$start;?></td>
				<td width="25%" height="22" align="center" valign="top"><?php echo $preregROW['email'];?></td>	
				<td width="17%" height="22" align="center" valign="top"><?php echo $preregROW['date'];?></td>
				<td width="29%" align="left"><input type="checkbox" value="<?php echo $preregROW['prereg_id'];?>" name="allbox[]" onclick="check();"/></td>
			</tr>
			<?php	
				}//End of While	 
			?>
				</TABLE>
			</TD></TR>
			<tr><td height="22" colspan="5" valign="top" align="center">
			<?php			
				if(isset($paginationData))
				{
					echo $paginationData;
				}
			?>
			</td></tr>
	</TABLE>
<?php
	echo "^8";
}

?>