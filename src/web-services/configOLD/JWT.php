<?php
class JWT {
 
    // object properties
    public $secret_key;
   
    // constructor 
    public function __construct(){
        $this->secret_key = "rBrWiypbHY2mQL9DGFNi1AzUsv67qMCc";
    }
    
    public function getJwt($fields = array()) {

        $encoded_header = base64_encode('{"alg": "HS256","typ": "JWT"}');

        $encoded_payload = base64_encode(json_encode($fields));

        $header_payload = $encoded_header . '.' . $encoded_payload;

        $signature = base64_encode(hash_hmac('sha256', $header_payload, $this->secret_key, true));

        $jwt_token = $header_payload . '.' . $signature;

        return $jwt_token;
    }
    
    public function checkJwt() {
    
    	//recupero il token
    	$headers = getallheaders();
		$token_r = $headers['Authorization'];
		$token=str_replace("Bearer ","",$token_r);
        
        $jwt_values = explode('.', $token);

        $recieved_signature = $jwt_values[2];

        $recievedHeaderAndPayload = $jwt_values[0] . '.' . $jwt_values[1];

        $resultedsignature = base64_encode(hash_hmac('sha256', $recievedHeaderAndPayload, $this->secret_key, true));

        if ($resultedsignature == $recieved_signature) {
        
           /* commentata solo in fase di test
        	$claims=json_decode(base64_decode($jwt_values[1]), true);
       
            $login_dt = $claims['login_dt']['date'];
           
          	$newdate1= new DateTime($login_dt);
           	$newdate2= new DateTime();
           	$since_start = $newdate1->diff($newdate2);
          
        
			if ($since_start->i >= 50) {
        		return false;
            }*/
            
        	return(true);
        }
        else return(false);

    }
    
     public function decodeJwt() {

		//recupero il token
    	$headers = getallheaders();
		$token_r = $headers['Authorization'];
		$token=str_replace("Bearer ","",$token_r);
        
        $jwt_values = explode('.', $token);

        $claims=json_decode(base64_decode($jwt_values[1]), true);
      	
        return($claims);

    }
}
?>
