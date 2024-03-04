<?php
require_once(__DIR__ ."/spaceremit/spaceremit_plugin.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data sent by Spaceremit
    $json_data = file_get_contents('php://input');
    $request_data = json_decode($json_data, true);
    
    // Check if the JSON data is valid
    if ($request_data !== null) {
        define("SPACEREMIT_PAYMENT_ID",$request_data['data']['id']);
        
        $acceptable_data = array( );
        $spaceremit = new Spaceremit();
        $response = $spaceremit->check_payment(SPACEREMIT_PAYMENT_ID,$acceptable_data);
        if($response){
            define("SPACEREMIT_PAYMENT_DATA",$spaceremit->data_return);
            
            
            //1-Get your saved payment with payment key equals to => SPACEREMIT_PAYMENT_DATA['id']
            
            //2-What is status of your saved payment then take action according to the new one.
            //--EXAMPLES
            
            // Assuming $newStatus and $oldStatus contain the new and old statuses respectively
            $newStatus = SPACEREMIT_PAYMENT_DATA['status_tag']; // Example new status received from API notification
            $oldStatus = "B"; // Example old status
            
            // Switch based on the current status ($newStatus) and old status ($oldStatus)
            /*switch ($newStatus) {
                case 'A':
                    // Check if the old status was B
                    if ($oldStatus === 'B') {
                        // Action for changing from PENDING to COMPLETED
                    }
                    // Check if the old status was C
                    if ($oldStatus === 'C') {
                        // Action for changing from REFUSED to COMPLETED
                    }
                    // Add similar checks for other statuses if needed
                    break;
                case 'B':
                    // Check if the old status was A
                    if ($oldStatus === 'A') {
                        // Action for changing from COMPLETED to PENDING
                    }
                    // Check if the old status was C
                    if ($oldStatus === 'C') {
                        // Action for changing from REFUSED to PENDING
                    }
                    // Add similar checks for other statuses if needed
                    break;
                // Add cases for other statuses similarly
                default:
                    // Handle default case
                    break;
            }*/

            
            
           
        }
        
        
        
       
       
        
    }
} else {
   // header('Location: /');
}

?>