<?php
class SiteSetting extends AppModel 
{
    var $name = 'SiteSetting';
	var $useTable = 'site_settings';
	
	
	function getSettings()
	{
		$field = array('SiteSetting.*');
		$list = $this->find('all',array('fields'=> $field));
		
		$settings = array();
		foreach($list as $key=>$value)
		{
			$settings[$value['SiteSetting']['option']] = $value['SiteSetting'];
		}
		
		return $settings;			
	}
	
}
?>
