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
            'github_account_name' => 'test_github_author1',
            'chatwork_id' => 0,
        ),
        array(
            'id' => 5,
            'name' => 'test_author_2',
            'github_account_name' => 'test_github_author2',
            'chatwork_id' => 0,
        ),
        array(
            'id' => 7,
            'name' => 'test_author_3',
            'github_account_name' => 'test_github_author3',
            'chatwork_id' => 0,
        ),
    );
}
