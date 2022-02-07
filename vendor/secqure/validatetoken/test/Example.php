<?php 
    // This is an example for decode the Token from server side. 
	//Once the frontend login form is submitted, in the backend the token can be  validated to to make sure it is originated from correct source.
    require_once("../vendor/autoload.php"); 

    //retrive the access_token from cookie or local Storage in order to decode
    // example below show how to decode the access_token to retrieve the user's email id or phone number.
    
    $token = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.XXXXXXXXXXXXXXXXX......eqd44ZsHA';
    $myAuth = new ValidateToken();
    $myToken = $myAuth->decodeToken($token);
    // add your logic here once the token is decoded and the user's login id is verified.
    echo $myToken->userId;
?>