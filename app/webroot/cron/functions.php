 <?php 

 function send_push_notification($message=NULL,$registatoin_ids=NULL,$page=NULL, $notification_count=NULL){
      
        $messageArr = array();       
        $messageArr['to']  =  $registatoin_ids;       
        $messageArr['notification']['title']  =  $page;     
        $messageArr['notification']['body']   =  $message;
        $messageArr['notification']['sound']   =  'default';
        $messageArr['notification']['icon']   =  'ic_app_launcher';
        $messageArr['data']['notification_count']   =  $notification_count;
        $messageArr['data']['notification_type']   =  'bell_notification';
      
        $message = $messageArr;

        
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . "AAAAAqY2LLo:APA91bEp82S2ewKHqL5_bpo6uW80i9CIa4eAQDT90-wkbyVTWlYTud797-2FOAI-9vkNlMYAYKjN-bAxrKeSxhNPqxJA0IA3LqO5SbW4CI35KQ8XJTLrtUj55GFX63fTV1mc-VhyO7ZD",
            'Content-Type: application/json'
        );
        
        $fields = $message;
        
        $fields = json_encode ( $fields );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    
        $result = curl_exec ( $ch );
        //echo $result;
        //exit();
        curl_close ( $ch );
        //die;
        return $result;
       /*echo "<pre>";

        print_r($result); */
    }
?>
