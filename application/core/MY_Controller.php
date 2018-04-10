<?php

if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/php-jwt/JWT.php';
use \Firebase\JWT\JWT;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class MY_Controller extends REST_Controller
{
    private $secretKey;
    private $algorithm;
    
    public $user;
            
    function __construct()
    {
        parent::__construct();
        
        // Controller intializations
        $this->lang->load('common');
        $this->secretKey = $this->config->item('jwt_key');
        $this->algorithm = $this->config->item('jwt_algorithm');
    }
    
    public function generate_token($user_data) {
        // To generate JWT based token
        $issuedAt   = time();
              $static_str='SC';
        $currenttimeseconds = date("mdY_His");
        $tokenId=base64_encode($static_str.$user_data->id.$currenttimeseconds);
       $serverName = $this->config->item('base_url'); // Retrieve the server name from config file

        // Create the token as an array
        $payload = [
            'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
            'nbf'  => $issuedAt,         // Not before
            'iss'  => $serverName,       // Issuer
            'user' => $user_data
        ];
    
        return JWT::encode($payload, base64_decode($this->secretKey), $this->algorithm);
    }
    
    public function validate_token() {
        // To validate JWT based token
        $authHeader = $this->_args['Authorization'];
        
        if ($authHeader) {
            // Extract the jwt from the Bearer
            list($jwt) = sscanf((string)$authHeader, 'Bearer %s');

            if ($jwt) {
                try {
                    $payload = JWT::decode($jwt, base64_decode($this->secretKey), array($this->algorithm));
                    $this->user = $payload->user;
                } catch (Exception $e) {
                    // Unauthorized
                    $this->response([
                        $this->config->item('rest_status_field_name') => FALSE,
                        $this->config->item('rest_message_field_name') => $this->lang->line('token_invalid')
                    ], self::HTTP_UNAUTHORIZED);
                }
            } else {
                // Bad request
                $this->response([
                    $this->config->item('rest_status_field_name') => FALSE,
                    $this->config->item('rest_message_field_name') => $this->lang->line('token_error')
                ], self::HTTP_BAD_REQUEST);
            }
        } else {
            // Token not found
            $this->response([
                $this->config->item('rest_status_field_name') => FALSE,
                $this->config->item('rest_message_field_name') => $this->lang->line('token_required')
            ], self::HTTP_BAD_REQUEST);
        }
    }
    
}
