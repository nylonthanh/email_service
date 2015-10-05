<?php
if(!defined('ACCESS') ) { die('permission denied');}

/* 
 * Author: Thanh Pham
 * usage:  call Mailgun's services to send mail with params
 * helpful urls: http://documentation.mailgun.com/
 * assume variables for mailgun will be the same
 */

require_once('../helpers/SystemHelper.php');
require_once('../helpers/Data.php');
require_once('../helpers/Config.php');

class mailgun {
    
    private $mailgun_api_key    = MAILGUN_API_KEY;
    private $base_url           = MAILGUN_BASE_URL;
    private $domain             = MAILGUN_DOMAIN;
    
    public function __construct($email_data) {  
            $this->_email_data = $email_data->_email_data;
            $this->system      = new SystemHelper;
            $this->data        = new Data($this->_email_data);       
    }
    
    
    /*
     * method to send mailgun message 
     * param: data array, see post_test or mailgun docs
     */
    public function send(){ 

        $curl_url          = null;
        $this->_email_data = $this->data->_email_data;
        if( !$this->mod_email_fields() ) {
            $r = $this->system->get_status_code(400, 'Bad parameters', 'mailgun');
            return $r;         
        }
            
        try {

            //create string to curl
            $curl_url = $this->base_url . '/' . $this->domain . '/messages';

            if(filter_var($curl_url, FILTER_VALIDATE_URL) ){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_USERPWD, "api:" . $this->mailgun_api_key);
                curl_setopt($ch, CURLOPT_URL, $curl_url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_email_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                     
                $c_r = curl_exec($ch);                 
                curl_close($ch);

                if( strpos($c_r, 'Queued') !== false) { 
                    $r = $this->system->get_status_code(200, json_decode($c_r), 'mailgun');
                    //log it
                    try {
                        $this->system->simple_logger('SUCCESS', 'MAILGUN', $this->_email_data);                                    
                    } catch (Exception $ex) { } 
                    return $r;                    

                } else { 
                    $r = $this->system->get_status_code(400, "Client Error: $c_r", 'mailgun');
                    //log it
                    try {
                        @$this->system->simple_logger('FAILURE', 'MAILGUN: Client Error: ' . $c_r, $this->_email_data);                                    
                    } catch (Exception $ex) { }                       
                    return $r;   
                }

              } else {
                  $r = $this->system->get_status_code(400, 'Invalid service URL', 'mailgun');
                    //log it
                    try {
                        @$this->system->simple_logger('FAILURE', 'MAILGUN: Invalid Service URL', $this->_email_data);                                    
                    } catch (Exception $ex) { }                     
                  return $r;
              }

        } catch(Exception $e) {
          $r = $this->system->get_status_code(400, "Error calling client", 'mailgun');
        //log it
        try {
            @$this->system->simple_logger('FAILURE', 'MAILGUN: Error calling client', $this->_email_data);                                    
        } catch (Exception $ex) { }           
          return $r;

        }
    }
    
    protected function mod_email_fields(){ 
        try{
            $this->_email_data['from'] = $this->_email_data['from_email'];
            $this->_email_data['to'] = $this->_email_data['to_email'];
            return true;            
        } catch (Exception $e) {
            $r = $this->system->get_status_code(400, $e, 'mailgun');
            //log it
            try {
                @$this->system->simple_logger('FAILURE', 'MAILGUN: mod_email_fields', $this->_email_data);                                    
            } catch (Exception $ex) { }              
            return $r;
        }
    }
    
}