<?php

$config = array(
    'debug' => 0,
    'Security' => array(
        'salt' => 'its-secret'
    ),
    'Exception' => array(
        'handler' => 'ErrorHandler::handleException',
        'renderer' => 'ExceptionRenderer',
        'log' => true
    )
);

Configure::write('SystemSettings.verificationAssignerId', 3);
