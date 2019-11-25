<?php
class CategoryController extends AppController {

    var $name = 'Category';
    var $uses = array('Category');
    var $helpers = array("Html", "Form", "Javascript", "Fck", "Js", "Session");
    var $components = array("RequestHandler", "Session", "Cookie","Email");
    

    function category_list() {
        $this->layout = "home_inner";
        $this->set('pagetitle', 'Welcome to Grouper');
        $this->_checkSessionUser();
       
        $user_id = $this->Session->read('userData.User.id');

        
        
        $condition = "Category.status ='1' AND ( Category.user_id ='0' OR Category.user_id ='".$user_id."'  ) ";
        $category_list = $this->Category->find("all",array('conditions'=>$condition,'order' => 'Category.id DESC'));
       // pr($featured_users);exit();
        $this->set('category_list',$category_list);

    }

    function add_category()
    {
        $this->layout = "";
        $this->set('pagetitle', 'Create Category');

        $this->_checkSessionUser();
       
         $user_id = $this->Session->read('userData.User.id');
       

        
        $upload_image = '';
        
     if(isset($this->params['form']['upload_image']) && $this->params['form']['upload_image']['name']!= '')
        
        {
            $image_name = $this->params['form']['upload_image']['name'];
            
            $extension = end(explode('.',$image_name));               
            $upload_image = time().accessCode(5).'.'.$extension;          
            $upload_target_original = 'category_photos/'.$upload_image;
                        
            $imagelist = getimagesize($this->params['form']['upload_image']['tmp_name']);
            list($uploaded_width, $uploaded_height, $type, $attr) = $imagelist;
            
            
             if($type == 1 || $type == 2 )
             {
                if($uploaded_width >=640 && $uploaded_height >= 480)
                {
                    if(move_uploaded_file($_FILES["upload_image"]['tmp_name'], $upload_target_original)) {
                                                                                                                                                                                                                            
                            $upload_target_thumb = 'category_photos/thumb/'.$upload_image;
                            $upload_target_android_medium = 'category_photos/android/medium/'.$upload_image;
                            $upload_target_medium = 'category_photos/medium/'.$upload_image;
                            $upload_target_web = 'category_photos/web/'.$upload_image;
                            
                            $max_medium_width =  263;
                            $max_medium_height = 180;


                            $max_web_width = 464;
                            $max_web_height =293;
                                                              
                            
                            $this->imgAndroidThumbOptCpy($upload_target_original, $upload_target_thumb, $uploaded_width, $uploaded_height, 100, true);
                            $this->imgAndroidMediumOptCpy($upload_target_original, $upload_target_android_medium,$uploaded_width, $uploaded_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_medium,$max_medium_width, $max_medium_height, 100, true);
                            $this->imgOptCpy($upload_target_original, $upload_target_web,$max_web_width, $max_web_height, 100, true);
                        $is_upload = 1;
                                                                                    
                      }      
                    else  
                    {
                        
                    $is_upload = 0;
                    $this->Session->setFlash(__d("statictext", "Image upload failed", true));
                    $_SESSION['meesage_type'] = '0';
                    $this->redirect(array('controller'=>'category','action'=>'category_list'));
                
                    }
                
                }
                else
                {        
                 
                 $is_upload = 0;
                $this->Session->setFlash(__d("statictext", "Please upload a bigger image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'category','action'=>'category_list'));
                
                }
                
             }
             else
             {
                $is_upload = 0;
               $this->Session->setFlash(__d("statictext", "Please upload jpg,jpeg and gif image only", true));
                $_SESSION['meesage_type'] = '0';
                $this->redirect(array('controller'=>'category','action'=>'category_list'));
              
             }
        
        }
       
        else
        {
            $is_upload = 0;
            $this->Session->setFlash(__d("statictext", "Please upload image", true));
            $_SESSION['meesage_type'] = '0';
            $this->redirect(array('controller'=>'category','action'=>'category_list'));

        }
                
        if($is_upload == 1)  
        {

                
                $this->data['Category']['title'] = $this->params['form']['title'];
                $this->data['Category']['category_desc'] = $this->params['form']['desc'];
                $this->data['Category']['image'] = $upload_image;
                $this->data['Category']['user_id'] = $user_id;
              
               
                $this->Category->create();
                if($this->Category->save($this->data))
                {
                    $this->Session->setFlash(__d("statictext", "Category added sucessfully", true));
                    $_SESSION['meesage_type'] = '1';
                    $this->redirect(array("controller" => "category", "action" => "category_list")); 
                }

        }
        
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
        
    }

    
}?>
