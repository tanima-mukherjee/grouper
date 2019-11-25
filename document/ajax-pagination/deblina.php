$paginationData = bingpaging("frmpagination",$start,$page,$total_items ,$limit); // This function is userd For pagination purpose
	if(isset($paginationData))
	{
		echo $paginationData;
	}
	
	//vendor functions.php
	function paging_ajax($lastpage,$page,$prev,$next,$lpm1,$prefix,$funcName)
{

	$varpg = $page;
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		################################# PREV BUTTON - STARTS #############################################
		if ($page > 1)
		{ 
			$pagination.= "<a href=\"javascript:$funcName('$prev')\">Previous</a>&nbsp;&nbsp;";
		}
		else
		{
			$pagination.= "Previous&nbsp;&nbsp;";
		}
		################################# PREV BUTTON - ENDS #############################################
		################################# Page Display - STARTS #############################################
		if($page==1)
		{
			$i=1;
			$counter = $page;
			while($page<=$lastpage)
			{
				if($page==1)
				{
					$pagination.= "$page&nbsp;|&nbsp;";
				}
				else
				{
					$pagination.= "<a href=\"javascript:$funcName('$page')\">$page</a>&nbsp;|&nbsp;";
				}
				$page = $page+1;
				if($i==3)
				{
					break;
				}
				$i++;
			}
		}
		elseif($page==$lastpage)
		{
			$i=1;
			$j=2;
			$counter = $page; 
			//$rest = ($page-2);
			while($page<=$lastpage)
			{
				if($lastpage==2)
				{
					$var = $page-$i;
					if($var==$counter)
					{
						$pagination.= "$var&nbsp;|&nbsp;";
						break;
					}
					else
					{
						$pagination.= "<a href=\"javascript:$funcName('$var')\">$var</a>&nbsp;|&nbsp;";
					}
					--$i;
				}
				else
				{
					$var = $page-$j;
					if($var==$counter)
					{
						$pagination.= "$var&nbsp;|&nbsp;";
						break;
					}
					else
					{
						$pagination.= "<a href=\"javascript:$funcName('$var')\">$var</a>&nbsp;|&nbsp;";
					}
					--$j;
				}
			}
		}
		elseif($page==$lpm1)
		{
			$i=1;
			$j=0;
			$counter = $page;
			$varPage = $page;
			while(($varPage-1)<$lastpage)
			{
				if($j==0)
				{
					$var = $varPage-1;
					$pagination.= "<a href=\"javascript:$funcName($var)\">$var</a>&nbsp;|&nbsp;";	
					$j=1;
				}
				else
				{
					if($varPage==$counter)
					{
						$pagination.= "$varPage&nbsp;|&nbsp;";
					}
					else
					{
						$pagination.= "<a href=\"javascript:$funcName(".$varPage.")\">$varPage</a>&nbsp;|&nbsp;";
					}
					$varPage++;
				}
				if($i==3)
				{
					break;
				}
				$i++;
			}
		}
		else
		{
			$i=1;
			$j=0;
			$counter = $page;
			while($page<$lastpage)
			{
				if($j==0)
				{
					$var = $page-1;
					$pagination.= "<a href=\"javascript:$funcName($var)\">$var</a>&nbsp;|&nbsp;";
					$j=1;
				}
				else
				{
					$var = $var+1;
					if($var==$counter)
					{
						$pagination.= "$var&nbsp;|&nbsp;";
					}
					else
					{
						$pagination.= "<a href=\"javascript:$funcName('$var')\">$var</a>&nbsp;|&nbsp;";
					}
				}
				if($i==3)
				{
					break;
				}
				$i++;
			}
		}
		################################# Page Display - ENDS #############################################
		################################# NEXT BUTTON - STARTS #############################################
		if ($varpg < $lastpage) 
		{
			$pagination.= "&nbsp;<a href=\"javascript:$funcName('$next')\">Next</a>&nbsp;";
		}
		else
		{
			$pagination.= "&nbsp;Next";
		}
		
		################################# NEXT BUTTON - ENDS #############################################
		$pagination .= "</div>";
	}
	return $pagination;
}

// html page
$paginationData = paging_ajax($lastpage,$page,$prev,$next,$lpm1,$prefix,"paging");
<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/ajaxresponse.js"></script>
<script language="javascript">
	function paging(val)
	{		
		//sendRequest("ajax/ajaxpaging_category.php","page="+val,"POST");
		//ajax
	}
</script>