<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2017-11-08 on Sugar 7.9.2.0
//
// file: custom/include/database/CustomMysqliManager.php 

// $sugar_config['dbconfig']['db_manager'] = 'CustomMysqliManager';
// $sugar_config['dbconfig']['use_transactions'] = true;

class CustomMysqliManager extends MysqliManager
{
    protected $db_request_error = false;

    public function setRequestError()
    {
        $this->db_request_error = true;
    }

    public function isTransactionError()
    {
        if($this->db_request_error) {
            return true;
        }

        if($this->checkPhpError()) {
            return true;
        }

        return false;
    }

    public function checkPhpError()
    {
        $error = error_get_last();
        if(!empty($error) && ($error['type'] === E_PARSE || $error['type'] === E_ERROR)) {
            $this->setRequestError();
            return true;
        } else {
            return false;
        }
    }

    public function connect(array $configOptions = null, $dieOnError = false)
    {
        global $sugar_config;

        if(is_null($configOptions)) {
            $configOptions = $sugar_config['dbconfig'];
        }

        $db_connection = parent::connect($configOptions, $dieOnError);

        if(!empty($configOptions['use_transactions']) && !empty($this->getDatabase())) {
            mysqli_autocommit($this->getDatabase(), false);
            mysqli_begin_transaction($this->getDatabase());
        }
    
        return $db_connection;
    }

    public function disconnect()
    {
        global $sugar_config;

        if(!empty($sugar_config['dbconfig']['use_transactions'])) {
            if($this->isTransactionError()) {
                $GLOBALS['log']->error('Rolling back database queries since last connection');
            } else {
                mysqli_commit($this->getDatabase());
            }
        }

        parent::disconnect();
    }
}
