<?php
App::import('Vendor', 'function');
class AppController extends Controller 
{
	//var $uses = array('Admin','SiteSetting','BadWord');
    
    var $uses = array('Admin','SiteSetting');
	var $helpers = array('Time', 'Session', 'Html');
	var $components = array('RequestHandler', 'Session');

	function getBadWords(){
	   
		$ArBadWords = $this->BadWord->find('first', array('fields'=>'BadWord.words'));
		$BadWords = $ArBadWords['BadWord']['words'];
		$ArAllBadWords = explode(',', $BadWords);
		return $ArAllBadWords;
        
	}

	 function getDefaultConfig()
	 {

        $config = array();
        $config['base_url']  = "https://www.grouperusa.com/";
        return $config;

    }
	
	/*function getDefaultConfig()
	{      
		$config = new stdClass();
		$config->limit  = 10;
		
		$config->set_test_userid  = 1;	// 1 for test 0 for live
		$config->test_userid  = 17;
		$config->google_api_id = 'AIzaSyAOHK9Fy2Hm9grLP--CfhtNKTcMQtOZoDU';
		
		return $config;
	}
	*/
	function getSiteSettings()
	{
		$sitesettings = $this->SiteSetting->getSettings();				
		return $sitesettings;
	}
	function get_location_info($location = '')
	{
		//$location = urlencode('kolkata west bengal india');
		$location = urlencode($location);
		$geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyDo-gE2qNbO19Gls-MyPR5NdcriYzDu-pw&address=".$location."&sensor=true");
		$geocodeoutput = json_decode($geocode);   
		//pr($geocodeoutput);
	  	$response = array();
		  if($geocodeoutput->status == 'OK')
		  {
				$address_components = $geocodeoutput->results[0]->address_components;
				$response['latitude'] = $geocodeoutput->results[0]->geometry->location->lat;
				$response['longitude'] = $geocodeoutput->results[0]->geometry->location->lng;
				
				foreach ($address_components as $value) {
					foreach ($value->types as $val) {
					  if($val == 'country')
					  {
						 $response['country'] = $value->long_name;
					  }
					  if($val == 'locality' OR $val == 'administrative_area_level_3' )
					  {
						 $response['city'] = $value->long_name;
					  }
					  if($val == 'administrative_area_level_1')
					  {
						 $response['state'] = $value->long_name;
						 
					  }
				   }
			  }
		  }
		  return $response;
	}
	
    function get_location_by_latlong($location = '')
	{
		//$location = urlencode('kolkata west bengal india');
		
		$location = urlencode($location);
		$geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyDo-gE2qNbO19Gls-MyPR5NdcriYzDu-pw&latlng=".$location."&sensor=true");
		$geocodeoutput = json_decode($geocode);   
		//pr($geocodeoutput);
	  	$response = array();
		  if($geocodeoutput->status == 'OK')
		  {
				$address_components = $geocodeoutput->results[0]->address_components;
				$response['place_id'] = $geocodeoutput->results[0]->place_id;
				$response['latitude'] = $geocodeoutput->results[0]->geometry->location->lat;
				$response['longitude'] = $geocodeoutput->results[0]->geometry->location->lng;
				
				foreach ($address_components as $value) {
					foreach ($value->types as $val) {
					  if($val == 'country')
					  {
						 $response['country'] = $value->long_name;
					  }
					  if($val == 'locality' OR $val == 'administrative_area_level_3' )
					  {
						 $response['city'] = $value->long_name;
					  }
					  if($val == 'administrative_area_level_1')
					  {
						 $response['state'] = $value->long_name;
						 $response['state_code'] = $value->short_name;
						 
					  }
				   }
			  }
		  }
		  return $response;
	}
	
	function get_place_detail($placeid = '')
	{	
		$defaultconfig = $this->getDefaultConfig();
		$key = $defaultconfig->google_api_id;
		$geocode = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=".$placeid."&key=".$key."");
		$geocodeoutput = json_decode($geocode);   
		//pr($geocodeoutput);
	  	$response = array();
		  if($geocodeoutput->status == 'OK')
		  {
				$response['latitude'] = $geocodeoutput->result->geometry->location->lat;
				$response['longitude'] = $geocodeoutput->result->geometry->location->lng;
                $response['formatted_address'] = $geocodeoutput->result->formatted_address;
		  }
		  return $response;
	}
		
	function removeFile($image_name,$folder) { 
		$folder_url = WWW_ROOT . $folder;
		$rel_url = $folder;
		
		//if(file_exists($folder_url.$image_name)) {
		
			@unlink($folder_url.$image_name);
		//}
	}
    
	function beforeFilter()
	{	
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
		header('Pragma: no-cache'); // HTTP 1.0.
		header('Expires: 0');
	}

    
    function beforeRender()
   {
     
   } 
	
	function checkUserLoggedIn()	
	{
		$user_id = $this->Session->read('userData.User.id');
		if(!isset($user_id) || (isset($user_id) && $user_id <= 0))
			return false;
		else
			$this->redirect(array('controller'=>'profile','action' => 'dashboard'));	
	}
    
    function _checkMerchantSession()
	{
		if ($this->Session->check('userData.Merchant'))
		{
			return true;
		}
		else
		{
			 $this->Session->setFlash('Please Login to Continue ');
             $this->redirect(array('controller' => 'home','action'=>'registration')); 
        }
	}
	
     function _checkVipSession()
	{
		if ($this->Session->check('userData.Vip'))
		{
			return true;
		}
		else
		{
			 $this->Session->setFlash('Please Login to Continue ');
             $this->redirect(array('controller' => 'home','action'=>'registration')); 
        }
	}
    
	function _checkSession()
	{
       if(($this->params['action'] != 'login') )
        {
			/*check the admin is logged in*/
					
			if (!$this->Session->check('adminData'))
			{
				/*set flash message and redirect*/
				
				$this->Session->setFlash('You need to be logged in to access this area');
				$this->redirect(array('controller' => 'admins','action'=>'login'));
				exit();
			}
		}
	}
	
	/*function _checkSession()
	{
     
     if(($this->params['action'] != 'login') )
        {
		
					
			if (!$this->Session->check('adminData'))
			{
			
				
				$this->Session->setFlash('You need to be logged in to access this area');
				$this->redirect(array('controller' => 'admins','action'=>'login'));
				exit();
			}
		}
	}*/
	
    function _checkSessionUser()
	{
	
		if ($this->Session->check('userData'))
		{
			return true;
		}
		else
		{
			$this->Session->setFlash(__d("statictext", "You are not logged in user!.", true));
          	$_SESSION['meesage_type'] ='0';
            $this->redirect(array('controller' => 'home','action'=>'index')); 
        }
		
	}
	
	function checkInstructor($user_id,$activity,$user_list)
	{
		if ($activity == 'instructor_add_jobs')
		{
			if ($user_list['User']['subscription_plan_id'] == 2)
			{
				return true;
			}
			else
			{
				$this->Session->setFlash(__d("statictext", "You are not authorized to post a job as a basic instructor.To avail this service please upgrade your account to Premium....!.", true));
				$_SESSION['meesage_type'] ='0';
				$this->redirect(array('controller' => 'home','action'=>'login')); 
			}
		}
			
	}
	
	function checkFacility($user_id,$activity,$user_list)
	{
	
			if ($activity == 'facility_add_jobs')
			{
				if ($user_list['User']['subscription_plan_id'] == 4)
				{
					return true;
				}
				else
				{
					$this->Session->setFlash(__d("statictext", "You are not authorized to post a job as a basic facility.To avail this service please upgrade your account to Premium....!.", true));
					$_SESSION['meesage_type'] ='0';
					$this->redirect(array('controller' => 'home','action'=>'login')); 
				}	
			}
    
			
	}
	
			
			
	 function _checkPremiumUser($user_id,$activity)
	{
		$user_detail = '';
		$condition = "User.id = '".$user_id."'";
    	$user_list = $this->User->find('first', array('conditions'=>$condition));
    	
		if ($user_list['User']['type'] == '2')	// instructor
		{
			$this->checkInstructor($user_id,$activity,$user_list);
				
		}
		elseif ($user_list['User']['type'] == '3')	// facility
		{
			$this->checkFacility($user_id,$activity,$user_list);
			
			
    	
		}				
		
	}
	
	
	function  _checkUser_info()
	{
	  if ($this->Session->check('userData'))
		{
			$user_id = $this->Session->read('userData.User.id');
			$condition2 =  "User.id ='".$user_id."'";
			$user_info = $this->User->find('first',array('conditions'=>$condition2));
			if($user_info['User']['status']=='1' )
			{
				$this->redirect(array('controller' => 'profile','action'=>'best_match'));
			}
			else
			{
				$this->redirect(array('controller' => 'profile','action'=>'thank_you'));
			
			}
		}
		else
		{
			 $this->Session->setFlash('Please Login to Continue ');
             $this->redirect(array('controller' => 'home','action'=>'index')); 
        }
	
	}
	
	
	function  _checkUser_status()
	{
	  if ($this->Session->check('userData'))
		{
			$user_id = $this->Session->read('userData.User.id');
			$status = $this->Session->read('userData.User.status');
			if($status=='0')
			{
			 $this->redirect(array('controller' => 'profile','action'=>'thank_you')); 
			 //$this->Session->setFlash('You are Not Authorised by Admin ');
			}
		}
		
	}
	
	
	function _checkSessionUser_exits()
	{
	
		if ($this->Session->check('userData'))
		{
			 //$this->Session->setFlash('Please Login to Continue ');
             $this->redirect(array('controller' => 'profile','action'=>'index')); 
		}
		else
		{
			 
        }
		
	}
 
	function filterQueryForGrid($filters, $modelName)
	{
       // {debugbreak();}    
        //if($_SERVER['REMOTE_ADDR']=='10.1.31.77'){debugbreak();}    
		// GridFilters sends filters as an Array if not json encoded
		if (is_array($filters)) {
			$encoded = false;
		} else {
			$encoded = true;
			$filters = json_decode($filters);
		}

		// initialize variables
		$where = ' 0 = 0 ';
		$qs = '';

		// loop through filters sent by client
		if (is_array($filters)) {
			for ($i=0;$i<count($filters);$i++){
				$filter = $filters[$i];

				// assign filter data
				if ($encoded) {
					$field = $modelName.'.'.$filter->field;
					$value = $filter->value;
					$compare = isset($filter->comparison) ? $filter->comparison : null;
					$filterType = $filter->type;
				} else {
					$field = $modelName.'.'.$filter['field'];
					$value = $filter['data']['value'];
					$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
					$filterType = $filter['data']['type'];
				}

				switch($filterType){
					case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
					case 'list' :
						if (strstr($value,',')){
							$fi = explode(',',$value);
							for ($q=0;$q<count($fi);$q++){
								if($field == $modelName.'.status')
								{
									if($fi[$q] == 'Active')
									{
										$fi[$q] = 1;
									}
									else
									{
										$fi[$q] = 0;
									}
								}
								$fi[$q] = "'".$fi[$q]."'";
							}
							$value = implode(',',$fi);
							
							$qs .= " AND ".$field." IN (".$value.")";
						}else{
							if($field == $modelName.'.status')
							{
								if($value == 'Active')
								{
									$value = 1;
								}
								else
								{
									$value = 0;
								}
							}
							$qs .= " AND ".$field." = '".$value."'";
						}
					Break;
					case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
					case 'numeric' :
						switch ($compare) {
							case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
							case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
							case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
						}
					Break;
					case 'date' :
						switch ($compare) {
							case 'eq' : $qs .= " AND DATE(".$field.") = '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'lt' : $qs .= " AND DATE(".$field.") < '".date('Y-m-d',strtotime($value))."'"; Break;
							case 'gt' : $qs .= " AND DATE(".$field.") > '".date('Y-m-d',strtotime($value))."'"; Break;
						}
					Break;
				}
			}
			$where .= $qs;
		}
		return $where;
	}
	
	function getCountries()
	{
		$condn1 = array(
			'conditions' => array('Country.isblocked' => '0', 'Country.isdeleted' => '0'),
			'fields' => array('Country.id','Country.countryname'),
			'order' => array('Country.id ASC')
		);
		$allCountries = $this->Country->find('list', $condn1);
		$this->set('allCountries', $allCountries);
	}
	
	
	
	function _getBreadCrumb()
	{
		$breadCrumbData = $this->AdminMenu->query("SELECT * FROM admin_menus AS a1, admin_menus AS a2 WHERE a1.url LIKE '".$this->params['controller']."/%' AND a2.id = a1.parent_id");
		$this->set('breadCrumbData', $breadCrumbData);
	}
    
    function getAllAdmins()
    {
        
        $condn = array(
            'conditions' => array('Admin.isdeleted' => '0'),
            'fields' => array('Admin.id','Admin.firstname','Admin.lastname'),
            'order' => array('Admin.firstname ASC')
        );
        $allAdmin = $this->Admin->find('all', $condn);
        //pr($allAdmin);exit;
        $this->set('allAdmin', $allAdmin);
    }    
       /////////////  For Data Grid end/////////////////////
       
    function getAllLeaveTypes(){
        $condn = array(
            'conditions' => array('LeaveType.isdeleted' => '0'),
            'fields' => array('LeaveType.id','LeaveType.leave_name'),
            'order' => array('LeaveType.leave_name ASC')
        );
        $allLeaveType = $this->LeaveType->find('all', $condn);
        $this->set('allLeaveType', $allLeaveType);
    }
	
    function getAllProjects()
    {
        $condn = array(
            'conditions' => array('Project.isdeleted' => '0'),
            'fields' => array('Project.id','Project.name'),
            'order' => array('Project.name ASC')
        );
        $allProject = $this->Project->find('all', $condn);
        $this->set('project_arr', $allProject);
        //pr($allProject);
    }

    function imgAndroidThumbOptCpy
    ($inFile, $outFile="", $newwidth=200, $newheight=200, $quality=100, $resize=false)
    {
	//----------------------------------------
	//echo "tanmoy-".$inFile;
	//exit;
	list($width_original, $height_original) = @getimagesize($inFile);
	if($resize)
	{
		$width = (40/100)*($newwidth);
		$height = (40/100)*($newheight);
		/*if($width)
		{
			$height2 = ($width / $width_original) * $height_original;
			if($height && $height2 > $height)
				$width = ($height / $height_original) * $width_original;
			else
				$height = $height2;		

			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}
		elseif($height)
		{
			$width = ($height / $height_original) * $width_original;
			
			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}
		else
		{
			list($width, $height) = @getimagesize($inFile);
			
			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}*/
	}		
	//echo 'width = '.$width;
	//echo '- height = '.$height;
	
	//----------------------------------------
	$inType = @strtolower(@strrev(@substr(@strrev($inFile), 0, 4)));
	  
	if(!$outFile)
		$outFile = $inFile;
	$outType = @strtolower(@strrev(@substr(@strrev($outFile), 0, 4)));
	$image_xy = @imagecreatetruecolor($width, $height);
	
	//----------------------------------------
	if($inType==".jpg" || $inType=="jpeg")
	{
		//echo $inFile ; //exit ;  
		$image = @imagecreatefromjpeg($inFile);
		
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	elseif($inType==".gif")
	{
		$image = @imagecreatefromgif($inFile);
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	elseif($inType==".png")
	{
		//echo $inFile ; //exit ;  
		$image = @imagecreatefrompng($inFile) ; 
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	//----------------------------------------
	if($outType==".jpg" || $outType=="jpeg")
	{
		//@header("Content-Type: image/jpeg");
		$ok = @imagejpeg($image_xy, $outFile, $quality);
	}
	elseif($outType==".gif")
	{
		//@header("Content-Type: image/gif");
		//$ok = @imagegif($image_xy, $outFile, $quality);
		$ok = @imagegif($image_xy, $outFile);
	}
	elseif($outType==".png")
	{
		//@header("Content-Type: image/png");
		if ($quality == 100) $quality = 9;
		$ok = @imagepng($image_xy, $outFile, $quality);
	}
	@chmod($outFile, 0777);
	//echo "gfg-".$inFile;
	   // exit;
	//----------------------------------------
}

    function imgAndroidMediumOptCpy($inFile, $outFile="", $newwidth=200, $newheight=200, $quality=100, $resize=false)
    {
	//----------------------------------------
	//echo "tanmoy-".$inFile;
	//exit;
	list($width_original, $height_original) = @getimagesize($inFile);
	if($resize)
	{
		$width = (60/100)*($newwidth);
		$height = (60/100)*($newheight);
		/*if($width)
		{
			$height2 = ($width / $width_original) * $height_original;
			if($height && $height2 > $height)
				$width = ($height / $height_original) * $width_original;
			else
				$height = $height2;		

			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}
		elseif($height)
		{
			$width = ($height / $height_original) * $width_original;
			
			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}
		else
		{
			list($width, $height) = @getimagesize($inFile);
			
			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}*/
	}		
	//echo 'width = '.$width;
	//echo '- height = '.$height;
	
	//----------------------------------------
	$inType = @strtolower(@strrev(@substr(@strrev($inFile), 0, 4)));
	  
	if(!$outFile)
		$outFile = $inFile;
	$outType = @strtolower(@strrev(@substr(@strrev($outFile), 0, 4)));
	$image_xy = @imagecreatetruecolor($width, $height);
	
	//----------------------------------------
	if($inType==".jpg" || $inType=="jpeg")
	{
		//echo $inFile ; //exit ;  
		$image = @imagecreatefromjpeg($inFile);
		
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	elseif($inType==".gif")
	{
		$image = @imagecreatefromgif($inFile);
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	elseif($inType==".png")
	{
		//echo $inFile ; //exit ;  
		$image = @imagecreatefrompng($inFile) ; 
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	//----------------------------------------
	if($outType==".jpg" || $outType=="jpeg")
	{
		//@header("Content-Type: image/jpeg");
		$ok = @imagejpeg($image_xy, $outFile, $quality);
	}
	elseif($outType==".gif")
	{
		//@header("Content-Type: image/gif");
		//$ok = @imagegif($image_xy, $outFile, $quality);
		$ok = @imagegif($image_xy, $outFile);
	}
	elseif($outType==".png")
	{
		//@header("Content-Type: image/png");
		if ($quality == 100) $quality = 9;
		$ok = @imagepng($image_xy, $outFile, $quality);
	}
	@chmod($outFile, 0777);
	//echo "gfg-".$inFile;
	   // exit;
	//----------------------------------------
}
    
    function imgOptCpy($inFile, $outFile="", $newwidth=200, $newheight=200, $quality=100, $resize=false)
    {
	//----------------------------------------
	//echo "tanmoy-".$inFile;
	//exit;
	list($width_original, $height_original) = @getimagesize($inFile);
	if($resize)
	{
		$width = $newwidth;
		$height = $newheight;
		/*if($width)
		{
			$height2 = ($width / $width_original) * $height_original;
			if($height && $height2 > $height)
				$width = ($height / $height_original) * $width_original;
			else
				$height = $height2;		

			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}
		elseif($height)
		{
			$width = ($height / $height_original) * $width_original;
			
			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}
		else
		{
			list($width, $height) = @getimagesize($inFile);
			
			//echo 'width = '.$width;
			//echo '- height = '.$height;
		}*/
	}		
	//echo 'width = '.$width;
	//echo '- height = '.$height;
	
	//----------------------------------------
	$inType = @strtolower(@strrev(@substr(@strrev($inFile), 0, 4)));
	  
	if(!$outFile)
		$outFile = $inFile;
	$outType = @strtolower(@strrev(@substr(@strrev($outFile), 0, 4)));
	$image_xy = @imagecreatetruecolor($width, $height);
	
	//----------------------------------------
	if($inType==".jpg" || $inType=="jpeg")
	{
		//echo $inFile ; //exit ;  
		$image = @imagecreatefromjpeg($inFile);
		
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	elseif($inType==".gif")
	{
		$image = @imagecreatefromgif($inFile);
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	elseif($inType==".png")
	{
		//echo $inFile ; //exit ;  
		$image = @imagecreatefrompng($inFile) ; 
		@imagecopyresampled($image_xy, $image, 0, 0, 0, 0,
			$width, $height, $width_original, $height_original);
	}
	//----------------------------------------
	if($outType==".jpg" || $outType=="jpeg")
	{
		//@header("Content-Type: image/jpeg");
		$ok = @imagejpeg($image_xy, $outFile, $quality);
	}
	elseif($outType==".gif")
	{
		//@header("Content-Type: image/gif");
		//$ok = @imagegif($image_xy, $outFile, $quality);
		$ok = @imagegif($image_xy, $outFile);
	}
	elseif($outType==".png")
	{
		//@header("Content-Type: image/png");
		if ($quality == 100) $quality = 9;
		$ok = @imagepng($image_xy, $outFile, $quality);
	}
	@chmod($outFile, 0777);
	//echo "gfg-".$inFile;
	   // exit;
	//----------------------------------------
}

    function doc_extension_array(){
        $doc_ext = array('doc','docx','pdf',
        'xls','xlsx','txt','csv',
        'pptx','ppt','pps','xml',
        'bmp','png','gif','psd',
        'tif','xlr','tiff'
        );
        $this->set('doc_ext', $doc_ext);
    }
    
   	/*function getLanguage()
	{
		$langlist = $this->Language->getActiveLanguageList();
		//pr($langlist);die();		
		$this->set('langlist', $langlist);
		
	}*/

    function getSocialLink()
	{
         $SiteSettingsArr = $this->SiteSetting->find("all");
         $this->set('SiteSettingsArr',$SiteSettingsArr);
		
	}
  
  function randomNumber($length) {
    $result = '';
    for($i = 0; $i < $length; $i++) {
      $result .= mt_rand(0, 9);
    }
    return $result;
  }


  function send_sms($phone_no=NULL,$sms_content)
	{	    
		$sitesettings = $this->getSiteSettings();
		
		$text = urlencode($sms_content);
		
		if($phone_no!='' && strlen($phone_no) == 10)
		{
			$send_to = $phone_no;			
			$send_to = '+91'.$phone_no;

			$user = $sitesettings['clickatell_api_username']['value'];
			$password = $sitesettings['clickatell_api_password']['value'];
			$api_id = $sitesettings['clickatell_api_id']['value'];
			
			$baseurl ="https://api.clickatell.com";		 
			// auth call
			$url = "$baseurl/https/auth?user=$user&password=$password&api_id=$api_id";

			// do auth call
			$ret = file($url);

			// explode our response. return string is on first line of the data returned
			$sess = explode(":",$ret[0]);
			if ($sess[0] == "OK") {
			$sess_id = trim($sess[1]); // remove any whitespace
			$url = "$baseurl/https/sendmsg?user=$user&password=$password&api_id=$api_id&to=$send_to&text=$text&concat=4";
			// do sendmsg call
			$ret = file($url);

			/*echo '<pre>';
			print_r($ret);
			echo '</pre>';*/
			$send = explode(":",$ret[0]);

			if ($send[0] == "ID") {
			//echo "successnmessage ID: ". $send[1];
			} else {
			//echo "send message failed";
			}
			} else {
				//echo "Authentication failure: ". $ret[0];
			}
		}
		
		return true;
	}
	

	    
}

?>
