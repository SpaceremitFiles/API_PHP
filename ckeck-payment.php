<?php
require_once(__DIR__ ."/spaceremit/spaceremit_plugin.php");

$all_accepted_tags = array("A", "B", "D", "E", "F");//in test keys add "T" because test payments taged with "T";
$paid_tags = array_diff($all_accepted_tags, array("F"));

if(!empty($_POST['SP_payment_code'])){
    $payment_id = $_POST['SP_payment_code'];
    $acceptable_data = array(
     "currency"=>"USD",
     "original_amount"=>1,//change to your amount
     "status_tag"=>$all_accepted_tags//SELECT NEEDED TAGS
    
     );
     
    $spaceremit = new Spaceremit();
    $response = $spaceremit->check_payment($payment_id,$acceptable_data);
    if($response){
      $payment_details= $spaceremit->data_return;
      $spaceremit_payment_id = $payment_details['id'];
 
    	$previous_payment_with_same_spaceremit_id=false;//after checking by $spaceremit_payment_id
    	if(!$previous_payment_with_same_spaceremit_id){
    
              //insert payment and save $spaceremit_payment_id in your database
             
             
              //if payment is payed
              if(in_array($payment_details['status_tag'],$paid_tags)){
               //you recived amount in your spaceremit account , so do same thing
              
              }
    	}else{
    	    //payment was inserted previously
    	    //only show message to user that payment is completed or pending ,nothing elese
    	}
      
      
      
                                          
                                          
      
      
    }else{
      
       echo $spaceremit->data_return ;
    }
        
    
}
?>