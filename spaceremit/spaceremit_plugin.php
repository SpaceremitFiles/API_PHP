<?php
//spaceremit plugin
 class Spaceremit
{
    const 
           SERVER_KEY = 'YOUR_PRIVATE_KEY',
          BASE_URL = 'https://spaceremit.com/api/v2/payment_info/';

    public $data_return;
    function send_api_request($data, $request_method = null)  {
        $func_return = false;
        
        $data['private_key'] = self::SERVER_KEY;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::BASE_URL ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CUSTOMREQUEST => isset($request_method) ? $request_method : 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'authorization:' . self::SERVER_KEY,
                'Content-Type:application/json'
            ),
        ));

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code
        curl_close($curl);

        // Check if the request was successful (status code 200)
        if ($http_status === 200) {
            
            $decoded_response=json_decode($response, true); 
             // Check if decoding was successful
            if ($decoded_response !== null) {
                $func_return = true;
                $this->data_return = $decoded_response;
            } else {
                // Decoding failed, return an error message
                $this->data_return = array('response_status' => "failed", 'message' => 'Failed to decode response JSON...may be due to path error or Connection error.');
            }
        
        
            
        } else {
            // Request failed, return an error message
            $this->data_return=array('response_status'=>"failed",'message' => 'Failed to connect to spaceremit with status code ' . $http_status);
        }
        return $func_return;
    }
    
    
    
    function check_payment($payment_id,$acceptable_data){
        $func_return= false;
       
       //create spaceremit class
        $spaceremit = new Spaceremit();
        $data = array('payment_id' =>$payment_id);
        $response = $spaceremit->send_api_request( $data);
        if($response){
          
            $response= $spaceremit->data_return ;//make response is a return data values
            $response_data =$response['data'];
            
            if($response["response_status"] =="success"){ //so payment data is loaded , now need to check it if it real payment
         
         
       
         $not_acceptable_data_found= false;
         $not_acceptable_data_value= null;
         foreach($acceptable_data as $accept_k => $accept_v){
              
              if ($accept_k === "status_tag") {
                if (!in_array($response_data["status_tag"], $accept_v)) {
                    $not_acceptable_data_found = true;
                    $not_acceptable_data_value =$response_data["status_tag"] ;
                    
                    break; // No need to continue checking if one unacceptable status tag is found
                }
              } elseif ($acceptable_data[$accept_k] != $response_data[$accept_k]) {
                $not_acceptable_data_found = true;
                 $not_acceptable_data_value =$response_data[$accept_k] ;
                
                 break; // No need to continue checking if one unacceptable data is found
             }
             
             
         }
         
         if(!$not_acceptable_data_found){
             //ALL YOUR conditions are met. Do something
             $func_return =true;
             $this->data_return = $response_data; 
         }else{
           
             $this->data_return = "Not acceptable value is (". $not_acceptable_data_value.")"; 
         }
            
            }else{
               $this->data_return = $response["message"] ; 
            }
        
        
        }else{
            $this->data_return = $spaceremit->data_return['message'] ;
        }
 
 
        
        return $func_return;
    }

}
