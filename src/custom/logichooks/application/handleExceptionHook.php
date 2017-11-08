<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2017-11-08 on Sugar 7.9.2.0
//
// file: custom/logichooks/application/handleExceptionHook.php

class handleExceptionHook
{
    // from include/api/SugarApiException.php using best judgement
    public $http_codes_to_block = array(
        400,
        500,
        403,
        409,
        413,
        422,
        424,        
        433,
        502,
        503,
    );

    public function callHandleExceptionHook($event, $exception)
    {
        global $sugar_config;
        if(!empty($sugar_config['dbconfig']['use_transactions'])) {

            // only trigger for some http codes, to prevent metadata and other operations that need the database like token regeneration to succeed

            if(in_array($exception->getHttpCode(), $this->http_codes_to_block)) { 

                DBManagerFactory::getInstance()->setRequestError();

                $GLOBALS['log']->error('handleExceptionHook->callHandleExceptionHook() for event ' .
                    $event . ' with exception ' . $exception->getMessage() . ' and HTTP code ' . $exception->getHttpCode());
            }
        }
    }
}
