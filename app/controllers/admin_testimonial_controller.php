<?php
  class AdminTestimonialController extends AppController {
    
    var $name = 'AdminTestimonial';
    var $uses = array('Admin','User','Content','EmailTemplate','Testimonial');
    var $helpers = array('Html', 'Form', 'Javascript', 'Fck', 'Js');
    var $components = array();


    function all_testimonials()
    {
     
        $adminData = $this->Session->read('adminData');
        if(empty($adminData))
          $this->redirect('/admins/login');
        
        $this->layout = "";
        $this->set('pageTitle', 'Testimonials');

       

        $this->Testimonial->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
    	  
        $all_testimonials = $this->Testimonial->find('all',array('order'=>'Testimonial.id DESC'));

        //pr($all_testimonials);exit();
    	  $this->set('all_testimonials', $all_testimonials);
        

   
  	}

    function change_site_user_is_approved() 
    {

      $testimonialID = $_REQUEST['testimonialID'];
      $userIsApproved = $_REQUEST['userIsApproved'];
      
      if($userIsApproved == '1')
      {
        $this->data['Testimonial']['is_approved'] = '0';
        $this->data['Testimonial']['is_featured'] = '0';
      }
      else
      {
        $this->data['Testimonial']['is_approved'] = '1';
      }
      
      $this->Testimonial->id = $testimonialID;
      if($this->Testimonial->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();

    }

     function change_site_user_is_featured() 
    {

      $testimonialID = $_REQUEST['testimonialID'];
      $userIsFeatured = $_REQUEST['userIsFeatured'];

      $condition_testimonial_featured_count = "Testimonial.is_featured = '1' AND  Testimonial.is_approved = '1'";
      $all_testimonials_count = $this->Testimonial->find('count',array('conditions'=>$condition_testimonial_featured_count ));

      
      
      if($userIsFeatured == '1')
      {
        
        $this->data['Testimonial']['is_featured'] = '0';
      }
      else
      {
        if($all_testimonials_count >= 5)
      {
        echo 'notok';
      exit();
      }
      else{
        $this->data['Testimonial']['is_featured'] = '1';
          }
      }
      
      $this->Testimonial->id = $testimonialID;
      if($this->Testimonial->save($this->data))
        echo 'ok';
      else
        echo 'failed';
      exit();
      
      
    }


  	

   

}
?>
