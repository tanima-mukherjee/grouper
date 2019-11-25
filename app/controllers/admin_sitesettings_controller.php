<?php
  class AdminSitesettingsController extends AppController {
    
    var $name = 'AdminSitesettings';
    var $uses = array('Admin','User','Content','SiteSetting');
    var $helpers = array('Html', 'Form', 'Javascript', 'Fck', 'Js');
    var $components = array();


    


    	function edit_sitesettings($id=null)
	{
		$this->_checkSession();
		$this->layout = "";
		$this->set('pageTitle', 'Edit Site Settings');
     $sitesettings = $this->getSiteSettings();
      $this->set('site_name',$sitesettings['site_name']['value']);
      $this->set('site_url',$sitesettings['site_url']['value']);
      $this->set('site_email',$sitesettings['site_email']['value']);
      $this->set('sender_email',$sitesettings['sender_email']['value']);
      $this->set('email_sender_name',$sitesettings['email_sender_name']['value']);
      $this->set('site_application_email',$sitesettings['site_application_email']['value']);
      $this->set('contact_phone',$sitesettings['contact_phone']['value']);
      $this->set('contact_address',$sitesettings['contact_address']['value']);
      $this->set('contact_latitude',$sitesettings['contact_latitude']['value']);
      $this->set('contact_longitude',$sitesettings['contact_longitude']['value']);
     if(!empty($this->params['form']))
    {
      //pr($this->params['form']);exit;
      $total_sitesettings =  count($this->params['form']);
      //pr($total_sitesettings);
       for($i=1; $i<count($this->params['form']); $i++)
       {

        $this->data['SiteSetting']['id'] = $i;
        $this->data['SiteSetting']['value'] = $this->params['form']['settings_'.$i];              
        $this->SiteSetting->save($this->data['SiteSetting']); 
       }
        $this->Session->setFlash("Site Settings Edited Successfully.");
        $this->redirect('/admin_sitesettings/edit_sitesettings/'.time());
    }
    
	}

 

}
?>
