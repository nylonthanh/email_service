<?php
if(!defined('ACCESS') ) { die('permission denied');}

/* helper method for status codes */

class SystemHelper {
    
    public function get_status_code($en, $em, $source = null) {
        
        if(isset($en) && isset($em) ){
            
            if(isset($source)) {
                if($en >= 200 && $en < 300) {
                    $this->status = array('status' => $en, 'source' => $source, 'result' => $em);
                    return($this->status);

                } else {
                    $this->error  = array('error' => $en, 'source' => $source, 'result' => $em);
                    return($this->error);
                }                
            } else {
                if($en >= 200 && $en < 300) {
                    $this->status = array('status' => $en, 'result' => $em);
                    return($this->status);

                } else {
                    $this->error  = array('error' => $en, 'result' => $em);
                    return($this->error);
                }                               
            }

        }            
    }
    
    //logger
    public function simple_logger($status, $service, $email_data){

        //build log
        $log_data = $status . ', ' . $service . ', ' . $email_data['from_email'] . ', ' . $email_data['to_email'] . ', ' . time() . "\n";
        try{
            file_put_contents('../tmp/email.log', $log_data, FILE_APPEND | LOCK_EX);
        } catch(Exception $e) {
//            var_dump($e);die;
        }

    }
    
}