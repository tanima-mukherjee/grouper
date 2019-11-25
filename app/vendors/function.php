<?php

function paging_ajax($lastpage,$page,$prev,$next,$lpm1,$funcName)
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
			//echo '<br>1==='.$page;
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
			//echo '<br>2-------'.$page;
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
			//echo '<br>3-------'.$page;
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
			//echo '<br>4-------'.$page;
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
		//exit;
	}
	return $pagination;
}

function find_age($date)
{
	$curdate=date('Y-m-d');
	$no_day = datediff($date,$curdate);
	$age = floor($no_day/365);
	return $age;
}

function sendmail($From,$FromName,$ToName,$subject,$body,$string,$filepath,$filename,$sendCopyTo)
{
	require_once("PHPMailer/class.phpmailer.php");
	
	$mail = new PHPMailer();
	
	$mail->IsHTML(true);
	
	$mail->From = $From;
	
	$mail->FromName = $FromName;
	
	$mail->AddAddress($ToName,"");
	$mail->Subject = $subject;
	
	$mail->Body = $body;
	
	$mail->WordWrap = 50;
	
	$mail->AddCC($sendCopyTo);
	
	if($filename!='')
	{
		$mail->AddAttachment($filepath,$filename); //$filename is d name of the file which will be sent as string
	}
	if(!$mail->Send())
	{			
		return false;
	}
	else
	{		
		return true;
	}	
}


function dateDifference($time1, $time2, $precision = 6){
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }
 
    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }
 
    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();
 
    // Loop thru all intervals
    foreach ($intervals as $interval) {
      // Set default diff to 0
      $diffs[$interval] = 0;
      // Create temp time from time1 and interval
      $ttime = strtotime("+1 " . $interval, $time1);
      // Loop until temp time is smaller than time2
      while ($time2 >= $ttime) {
	$time1 = $ttime;
	$diffs[$interval]++;
	// Create new temp time from time1 and interval
	$ttime = strtotime("+1 " . $interval, $time1);
      }
    }
 
    $count = 0;
    $times = array();
    // Loop thru all diffs
    foreach ($diffs as $interval => $value) {
      // Break if we have needed precission
      if ($count >= $precision) {
	break;
      }
      // Add value and interval 
      // if value is bigger than 0
      if ($value > 0) {
	// Add s if value is not 1
	if ($value != 1) {
	  $interval .= "s";
	}
	// Add value and interval to times array
	$times[] = $value . " " . $interval;
	$count++;
      }
    }
 
    // Return string with times
    return implode(", ", $times);
  }

function currency_convert($from_Currency,$to_Currency,$amount){
    $amount = urlencode($amount);
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $url = "https://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
    $ch = curl_init();
    $timeout = 0;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $rawdata = curl_exec($ch);
    curl_close($ch);
    $data = explode('"', $rawdata);
    $data = explode(' ', $data['3']);
    $var = $data['0'];
    return round($var,3);
}

function isVowel($ch)
{
	$ch = strtolower($ch);
	if(in_array($ch,array('a','e','i','o','u')))
		return true	;
	else
		return false;
	
}
function isValidURL($url)
{
	$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	return preg_match($pattern, $url);
}	

/** email validation **/
function isValidEmail($email)
{
	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
		return false;
	else	
		return true;

}
function isNumber($variable)
{
	if(is_numeric($variable))
		return true;
	else
		return false;

}
function numberformat_dec($value,$dec = 2,$dec_sep = '.',$th_sep = ',')
{	
	return number_format($value,$dec,$dec_sep,$th_sep);
}

function numberformat($value,$dec = 0,$dec_sep = '.',$th_sep = ',')
{	
	return number_format($value,$dec,$dec_sep,$th_sep);
}


function getLanguageExpert(){
	$arrLanguageExpert = array('0'=>'Arabic','1'=>'Chinese','2'=>'Danish','3'=>'Dutch','4'=>'English','5'=>'Finnish','6'=>'French','7'=>'German','8'=>'Greek','9'=>'Hebrew','10'=>'Hindi','11'=>'Hungarian','11'=>'Italian','12'=>'Japanese','13'=>'Korean','14'=>'Norwegian','15'=>'Polish','16'=>'Portuguese','17'=>'Romanian','18'=>'Russian','19'=>'Spanish','20'=>'Swedish','21'=>'Turkish','22'=>'Urdu'); 
	return $arrLanguageExpert;
}

function getFluencyLevels(){
	$fluencyLevel = array('0'=>'Native','1'=>'Fluent','2'=>'Intermediate', '3'=>'Basic');
	return $fluencyLevel;
}

function dateshow($date)
{

	$datedisplay=date("F j, Y",strtotime($date));
	return $datedisplay;
}
/**
	Time format  function
**/
function timeshow($date)
{
	$timedisplay=date("g:i a",strtotime($date));
	return $timedisplay;
}
function thumbnail($filethumb,$file,$Twidth,$Theight,$tag)
{
	list($width,$height,$type,$attr)=getimagesize($file);
	switch($type)
	{
		case 1:
			$img = ImageCreateFromGIF($file);
		break;
		case 2:
			$img=ImageCreateFromJPEG($file);
		break;
		case 3:
			$img=ImageCreateFromPNG($file);
		break;
	}
	if($tag == "width") //width contraint
	{
		$Theight=round(($height/$width)*$Twidth);
		
		//die();
	}
	elseif($tag == "height") //height constraint
	{
		$Twidth=round(($width/$height)*$Theight);
		
		//die();
	}
	elseif($tag == "both") //height constraint
	{
		$Twidth = $Twidth;
		$Theight = $Theight;
		
		//die();
	}
	else
	{
		if($width > $height)
			$Theight=round(($height/$width)*$Twidth);
		else
			$Twidth=round(($width/$height)*$Theight);
		
		//die();
	}
	
	//die();
	$thumb=imagecreatetruecolor($Twidth,$Theight);
	if(imagecopyresampled($thumb,$img,0,0,0,0,$Twidth,$Theight,$width,$height))
	{
		
		switch($type)
		{
			case 1:
				ImageGIF($thumb,$filethumb);
			break;
			case 2:
				ImageJPEG($thumb,$filethumb);
			break;
			case 3:
				ImagePNG($thumb,$filethumb);
			break;
		}
		//chmod($filethumb,0777);
		return true;
	}
}
function showrating($rating)
{
			$cls = '';
			if(is_float($rating))
			{
				
				$rating = number_format($rating,2);
				$rate_exp = explode('.',$rating);
				$avg_rating = $rate_exp[0];
				$float_no = $rate_exp[1];
				
				if($float_no>0 && $float_no<35)
					$cls = 'fill25';
				elseif($float_no>=35 && $float_no<60)
					$cls = 'fill50';
				elseif($float_no>=60 && $float_no<85)
					$cls = 'fill75';
				else
					$cls = 'full';
					
			}
			else
			{
				$avg_rating = $rating;
			}
			$rating_html = '';
			
			for($r = 1;$r<=5;$r++)
			{
		
				if($r <= $rating){
					$rating_html .= '<li id="star'.$r.'" ><a  class="full" href="javascript:void(0);"></a></li>';
					
				}else{
					if($cls!=''){
						$rating_html .= '<li id="star'.$r.'" ><a  class="'.$cls.'" href="javascript:void(0);"></a></li>';
						$cls = '';
					}
					else
						$rating_html .= '<li id="star'.$r.'" ><a  class="shade" href="javascript:void(0);"></a></li>';
				
				}
			
			}
			return $rating_html;
			
	}
	
function distance($lat1, $lon1, $lat2, $lon2, $unit)
{ 
	$theta = $lon1 - $lon2; 
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
	$dist = acos($dist); 
	$dist = rad2deg($dist); 
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
	
	if ($unit == "K") {
		return ($miles * 1.609344); 
	}if ($unit == "ME") {
		return ($miles * 1609.344); 
	}
	if ($unit == "Y") {
		return ($miles * 1760); 
	}else if ($unit == "N") {
	return ($miles * 0.8684);
	} else {
	return $miles;
	}
}

function accessCode($length)
{
	$random= "";
	$data = "";
	srand((double)microtime()*1000000);
	
	$data = "9876549876542156012";
	$data .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$data .= "0123456789";
	
	for($i = 0; $i < $length; $i++)
	{
		if($i>15)
		{
			break;
		}
		else
		{
			$random .= substr($data, (rand()%(strlen($data))), 1);
		}
	}
	
	return $random;
} 
function getUniqueCode($insert_id)
{
	if($insert_id<=9)
		$length = 14;
	elseif($insert_id<=99)
		$length = 13;
	elseif($insert_id<=999)
		$length = 12;
	elseif($insert_id<=9999)
		$length = 11;
	elseif($insert_id<=99999)
		$length = 10;
	elseif($insert_id<=999999)
		$length = 9;
	elseif($insert_id<=9999999)
		$length = 8;
	elseif($insert_id<=99999999)
		$length = 7;
	else
		$length = 6;	

	$code = accessCode($length);
	
	$code1 = substr($code,0,3);
	//$code2 = substr($code,7,strlen($code));
	
	//$first_part = substr($insert_id,0,1);
	//$second_part = substr($insert_id,1,strlen($insert_id));
	
	
	$unique_code = $code1;
	return $unique_code;
	
}

function getAutoUniqueCode($insert_id)
{
	if($insert_id<=9)
		$length = 14;
	elseif($insert_id<=99)
		$length = 13;
	elseif($insert_id<=999)
		$length = 12;
	elseif($insert_id<=9999)
		$length = 11;
	elseif($insert_id<=99999)
		$length = 10;
	elseif($insert_id<=999999)
		$length = 9;
	elseif($insert_id<=9999999)
		$length = 8;
	elseif($insert_id<=99999999)
		$length = 7;
	else
		$length = 6;	

	$code = accessCode($length);
	
	$code1 = substr($code,0,8);
	//$code2 = substr($code,7,strlen($code));
	$unique_code = $code1;
	return $unique_code;
}

function substrwords($text,$maxchar,$end='...'){
 if(strlen($text)>$maxchar)
	 {
	  $words=explode(" ",$text);
	  $output = '';
	  $i=0;
	  while(1)
	  {
		   $length = (strlen($output)+strlen($words[$i]));
		   if($length>$maxchar){
			break;
		   }else{
			$output = $output." ".$words[$i];
			++$i;
		   };
	};
 }
 else
 {
 	$end='';
  	$output = $text;
 }
 return $output.$end;
}
/***
	this function is using for left panel search section for create url depending on search values
***/

function generateURL($url = '',$params = array())
{
	$newitem = '';
	if(!empty($params))
	{
		foreach($params as $key=>$val)
		{
			if($val!='')
			{
				if($newitem == '')
					$newitem .= stripslashes($key).'='.urlencode(stripslashes($val));
				else
					$newitem .= '&'.stripslashes($key).'='.urlencode(stripslashes($val));	
			}
			
		
		}
	}
	if($url!='')
	{
		$url .= '&'.$newitem;
	}else
	{
		$url .= '?'.$newitem;	
	}	
	return $url;	
}


function is_exist_in_multi_array( $needle, $search_key,$haystack ) { 
	foreach ( $haystack as $key => $value ) {
		//pr($value);
		//echo '<br>needle-------------'.$needle;
		//echo '<br>search_key-------------'. $search_key;
		if (isset($value[$search_key]) &&  $needle == $value[$search_key] ) 
			return true; 

		if ( is_array( $value ) ) {
			 if ( is_exist_in_multi_array( $needle, $search_key, $value ) == true ) 
				return true; 
			 else 
				 continue; 
		} 

	} 

	return false; 
}
function bingpaging($frmName,$start,$page,$total_record,$limit)
{
?>
<script language="JavaScript">
	function frm_sub(page)
	{	
		document.<?php echo $frmName?>.page.value = page;
		document.<?php echo $frmName?>.submit();
	}
</script>
<?php
	$adjacents = 3;
	//$limit = 5;
	$lastpage = ceil($total_record/$limit);
	$prev = $page - 1;
	$next = $page + 1;
	$lpm1 = $lastpage - 1;
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"javascript:frm_sub($prev)\" class=\"subhead1\"> previous</a>";
		//else
			//$pagination.= "<span class=\"disabled\">&laquo; previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"javascript:frm_sub($counter)\" class=\"subhead1\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"javascript:frm_sub($counter)\" class=\"subhead1\">$counter</a>";					
				}
				//$pagination.= "<span class=\"whitetext\">...</span>";
				//$pagination.= "<a href=\"javascript:frm_sub($lpm1)\" class=\"subhead1\">$lpm1</a>";
				//$pagination.= "<a href=\"javascript:frm_sub($lastpage)\" class=\"subhead1\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				//$pagination.= "<a href=\"javascript:frm_sub(1)\" class=\"subhead1\">1</a>";
				//$pagination.= "<a href=\"javascript:frm_sub(2)\" class=\"subhead1\">2</a>";
				//$pagination.= "<span class=\"whitetext\">...</span>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"javascript:frm_sub($counter)\" class=\"subhead1\">$counter</a>";					
				}
				//$pagination.= "<span class=\"whitetext\">...</span>";
				//$pagination.= "<a href=\"javascript:frm_sub($lpm1)\" class=\"subhead1\">$lpm1</a>";
				//$pagination.= "<a href=\"javascript:frm_sub($lastpage)\" class=\"subhead1\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				//$pagination.= "<a href=\"javascript:frm_sub(1)\" class=\"subhead1\">1</a>";
				//$pagination.= "<a href=\"javascript:frm_sub(2)\" class=\"subhead1\">2</a>";
				//$pagination.= "<span class=\"whitetext\">...</span>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"javascript:frm_sub($counter)\" class=\"subhead1\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"javascript:frm_sub($next)\" class=\"subhead1\">next </a>";
		//else
			//$pagination.= "<span class=\"disabled\">next &raquo;</span>";
		$pagination.= "</div>\n";		
	}
	return $pagination;
}
function array_compare($needle, $haystack, $match_all = true)
{
	if (!is_array($needle) )
	{
		return false;
	}
	
	
	$match = 0;
	foreach($haystack as $value)
	{

		if(in_array(trim($value),$needle))
		{
			$match ++;
			return $match;
		}
	}
	return $match;
} 
function Dot2LongIP ($IPaddr)
{
    if ($IPaddr == "") {
        return 0;
    } else {
        $ips = explode (".", $IPaddr);
        return ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
    }
}
function userStatus()
{
	$status = array('---','Employed','Unemployed','Seeking employment','Opened to new opportunities','On Maternity leave','On Sabbatical'); 
	return $status;
}
function past_year_range() 
{
	$curr_y = date("Y");
	$year_limit = $curr_y-40;
	
	$year_list = array();
	for($i = $curr_y +1; $i > $year_limit ; $i--)
	{
		
		$year = $i - 1;
		array_push($year_list,$year);
	}
	return $year_list;
}

function month_list()
{
	$month_list = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
	return $month_list;
}
function show_year_month($year, $month_no)
{
	$month = month_list();
	$month_name = $month[$month_no];
	$work = $month_name.' '.$year;
	return $work;
}
function datediff($date1, $date2)
{
	$ts1 = strtotime($date1);
	$ts2 = strtotime($date2);
	
	$seconds_diff = $ts2 - $ts1;
	
	return floor($seconds_diff/3600/24);
}
?>
