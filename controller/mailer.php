<?php

require_once __DIR__ . '/../helpers/Config.php';

/* 
 * author:    Thanh Pham
 * purpose:   accept POST parameter fields
 *            send to email service
 *                if service fails, 
 *                    use other service
 * response:  JSON
 * date:      June 29, 2014
 * 
 */


class mailer {
    
    public function __construct() {
        $this->_email_data = filter_var($_POST, FILTER_SANITIZE_STRING);
        $this->entry       = filter_var($_GET['entry'], FILTER_SANITIZE_STRING);        
    }
    
    public function send() {
            if($this->entry !== API_PASSWORD ) {
                $status = array('status' => 400, 'result' => 'Missing the required field to use the service');
                print_r(json_encode($status));
                exit;
            }
        // Mailer only accepts POST requests
         if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
            define('ACCESS', true);                     //access to other classes

            $form_data = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            //check if incoming params are correct
            require_once('../helpers/Data.php');

            $email_data_fields = new data($form_data);
            $required_params   = $email_data_fields->cleaner(); 

            if( isset($required_params->error['error']) ) {
                $status = array('status' => 400, 'result' => 'Missing required fields (from, from_email, to, to_email, subject, text)');
                print_r(json_encode($status));

            } else {

                //try mailgun
                try{
                    $result = $this->call_mailgun($email_data_fields);
                } catch (Exception $ex) { }
                
                //failed, try mandrill
                if( isset($result['error']) || $ex ){ 
                    $man_result = $this->call_mandrill($email_data_fields);

                    //failed, both methods failed
                    if( isset($man_result['error']) ) {

                        $status = array('status' => 400, 'result' => array("mailgun and mandrill failed" => 
                                  array('mailgun' => $result, 
                                        'mandrill' => $man_result)) );

                        print_r( json_encode($status) );

                    } else { //worked
                        print_r( json_encode($man_result) );                    
              
                    }

                } else { 

                    print_r( json_encode($result) );
                }

            }

        } else {
            $status = array('status' => 405, 'result' => "Wrong HTTP method(" . $_SERVER['REQUEST_METHOD'] . ")");
            print_r(json_encode($status));        
            exit();
        }  

    }
    
    //mailgun call requires cleaned email_data
    protected function call_mailgun($email_data){ 
        $email_service = null;
        $s             = null;
        require_once('../sources/mailgun.php');
        $email_service     = new mailgun($email_data);
        $s = ($email_service->send());
        return $s;
    }

    //mandrill call requires cleaned email_data
    protected function call_mandrill($email_data){
        $email_service = null;
        $s             = null;

        require_once('../sources/mandrill.php');
        $email_service     = new mandrill($email_data);
        $s = ($email_service->send());
        return $s;
    }    

        /*
         * http://stackoverflow.com/questions/1375501/how-do-i-throttle-my-sites-api-users
         */
    //    function rate_limit(){
    //        
    //        $minute = 60;
    //        $minute_limit = 100; # users are limited to 100 requests/minute
    //        $last_api_request = $this->get_last_api_request(); # get from the DB; in epoch seconds
    //        $last_api_diff = time() - $last_api_request; # in seconds
    //        $minute_throttle = $this->get_throttle_minute(); # get from the DB
    //        if ( is_null( $minute_limit ) ) {
    //            $new_minute_throttle = 0;
    //        } else {
    //            $new_minute_throttle = $minute_throttle - $last_api_diff;
    //            $new_minute_throttle = $new_minute_throttle < 0 ? 0 : $new_minute_throttle;
    //            $new_minute_throttle +=	$minute / $minute_limit;
    //            $minute_hits_remaining = floor( ( $minute - $new_minute_throttle ) * $minute_limit / $minute  );
    //            # can output this value with the request if desired:
    //            $minute_hits_remaining = $minute_hits_remaining >= 0 ? $minute_hits_remaining : 0;
    //        }
    //
    //        if ( $new_minute_throttle > $minute ) {
    //            $wait = ceil( $new_minute_throttle - $minute );
    //            usleep( 250000 );
    //            throw new My_Exception ( 'The one-minute API limit of ' . $minute_limit 
    //                . ' requests has been exceeded. Please wait ' . $wait . ' seconds before attempting again.' )
    //            );
    //        }
    //        # Save the values back to the database.
    //        $this->save_last_api_request( time() );
    //        $this->save_throttle_minute( $new_minute_throttle );        
    //    }    
}    

$postData = filter_var($_POST, FILTER_SANITIZE_STRING);
$sendMail = new mailer($postData);

return @$sendMail->send();