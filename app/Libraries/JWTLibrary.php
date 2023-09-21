<?php
namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTLibrary{

    private $key;
    private $iss;
    private $ttl;
    private $iat;
    private $exp;
    private $nbf;
    private $jti;
    public function __construct() {
        
    }

    protected function setConfig() {
        $this->key = getenv("jwt.secretkey");
        $this->ttl = getenv("jwt.ttl")?getenv("jwt.ttl"):60;
        $this->iss = $this->getCurrentURL();
        $this->jti = $this->setTime(date("Y-m-d H:i:s"));

        return $this;
    }

    protected function setExpiredDate()
    {
        $now = date("Y-m-d H:i:s");
        $this->iat = $this->setTime( $now );
        $this->nbf = $this->setTime( $now );
        $this->exp = $this->setTime( date("Y-m-d H:i:s", strtotime("+".$this->ttl." MINUTES")) );
        return $this;
    }

    public function token()
    {
        $payload = [
            'iss' => $this->iss,
            'iat' => $this->iat,
            'exp' => $this->exp,
            'nbf' => $this->nbf,
            'jti' => $this->jti
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function parse($token)
    {
    
        $bearerToken = $this->getBearerToken( $token );
        if( !$bearerToken ) return ['success' => false, 'message' => 'Token Invalid'];

        try {
            $decoded = JWT::decode($bearerToken, new Key($this->key, 'HS256') );

            return ['success' => true];
        }catch (\Exception $e){

            return ['success' => false, 'message' => $e->getMessage()];
        }
        
    }

    public function getBearerToken($token)
    {
        $token = explode(" ", $token);
        if( !isset($token[0]) && $token[0] != 'Bearer' )
        {
            return false;
        }

        return $token[2];
    }

    protected function getCurrentURL() {
        return ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
    }

    protected function setTime($date) {
        return strtotime($date);
    }

}