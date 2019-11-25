<?php 
App::import('Vendor', 'fckeditor');

class FckHelper extends AppHelper { 
               
    /**
    * creates an fckeditor textarea
    * 
    * @param array $namepair - used to build textarea name for views, array('Model', 'fieldname')
    * @param string $content
    */
    function fckeditor($namepair, $content){
        $editor_name = 'data';
        foreach ($namepair as $name){
            $editor_name .= "[" . $name . "]";
        }

        $oFCKeditor = new FCKeditor($editor_name) ;
		
        $oFCKeditor->BasePath = '/js/fckeditor/' ;
        $oFCKeditor->Value = $content ;
        $oFCKeditor->Create() ;            
    }      
} 
?>