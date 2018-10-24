<?php

class VerifierFixture extends CakeTestFixture
{
    /**
     * Import
     *
     * @var array
     */
    public $import = array(
        'model' => 'Verifier'
    );

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => 1,
            'name' => 'test_verifier_1',
            'chatwork_id' => '1',
        ),
        array(
            'id' => 2,
            'name' => 'test_verifier_2',
            'chatwork_id' => '2',
        ),
        array(
            'id' => 3,
            'name' => 'test_verifier_3',
            'chatwork_id' => '3',
        ),
    );
}
