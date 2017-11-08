<?php

// Enrico Simonetti
// enricosimonetti.com
//
// 2017-11-08 on Sugar 7.9.2.0
//
// file: custom/Extension/application/Ext/LogicHooks/install.handleExceptionHook.php 

$hook_array['handle_exception'][] = array(
    1, 
    'Application handle exception hook',
    'custom/logichooks/application/handleExceptionHook.php',
    'handleExceptionHook',
    'callHandleExceptionHook'
);
