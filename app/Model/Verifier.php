<?php

class Verifier extends AppModel
{
    public $hasOne = array(
        'verificationHistory' => array(
            'className' => 'verificationHistory',
        )
    );

}
