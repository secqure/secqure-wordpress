<?php 
require_once(__DIR__ . "/vendor/autoload.php"); 
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Algorithm\RS256;
// use GuzzleHttp\Client;

  class ValidateToken{
      public $sub;
      public $aud;
      public $iss;
      public $exp;
      public $jti;
      public $typ;
      public $signInMode;
      public $userId;
      public $auth_time;
      public $iat;
      
      public  static function decodeToken($token){
          $algorithmManager = new AlgorithmManager([new RS256(),]);        
          // Instantiate our JWS Verifier.
          //install "composer require guzzlehttp/guzzle" if needed
          $jwsVerifier = new JWSVerifier($algorithmManager);
          $http = new GuzzleHttp\Client();
          $response = $http->request('GET', 'https://api.secuuth.io/tokens/jwks', []);
          $keys = json_decode((string) $response->getBody(), true); 
          $jwk = new JWK($keys["keys"][0]);     
          // The serializer manager,use the JWS Compact Serialization Mode.
          $serializerManager = new JWSSerializerManager([
              new CompactSerializer(),
          ]);
          $jwt = $serializerManager->unserialize($token);
          $isVerified = $jwsVerifier->verifyWithKey($jwt, $jwk, 0);
          $claims;
          if($isVerified==true){
            $claims = json_decode($jwt->getPayload(), true);
          }
          $userObj = new ValidateToken();
          $userObj->sub=$claims['sub'];
          $userObj->aud=$claims['aud'];
          $userObj->iss=$claims['iss'];
          $userObj->exp=$claims['exp'];
          $userObj->jti=$claims['jti'];
          $userObj->typ=$claims['typ'];
          $userObj->signInMode=$claims['signInMode'];
          $userObj->userId=$claims['userId'];
          //$userObj->auth_time=$claims['auth_time'];
          //$userObj->auth_time=time();
          $userObj->iat=$claims['iat'];
          return $userObj;
      }
  }
?>








