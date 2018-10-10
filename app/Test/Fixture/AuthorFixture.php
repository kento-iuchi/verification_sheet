<?php

class AuthorFixture extends CakeTestFixture
{
    /**
     * Import
     *
     * @var array
     */
    public $import = array(
        'model' => 'Author'
    );

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => 1,
            'name' => 'test_author_1',
            'github_account_name' => 'kento-iuchi',
            'chatwork_id' => 0,
        ),
        array(
            'id' => 5,
            'name' => 'test_author_1',
            'github_account_name' => 'kento-iuchi',
            'chatwork_id' => 0,
        ),
        array(
            'id' => 7,
            'name' => 'test_author_1',
            'github_account_name' => 'kento-iuchi',
            'chatwork_id' => 0,
        ),
    );
}
