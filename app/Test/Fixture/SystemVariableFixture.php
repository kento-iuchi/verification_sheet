<?php

class SystemVariableFixture extends CakeTestFixture
{
    /**
     * Import
     *
     * @var array
     */
    public $import = array(
        'model' => 'SystemVariable'
    );

    public $records = array(
        array(
            'id' => 1,
            'next_release_date' => '2018-10-15',
        )
    );
}
