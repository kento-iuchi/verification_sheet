<?php

class EditingItemFixture extends CakeTestFixture
{
    /**
     * Import
     *
     * @var array
     */
    public $import = array(
        'model' => 'EditingItem'
    );

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => 1,
            'item_id' => '1',
            'item_column_name' => 'confirm_point',
            'editor_token' => 'test_token1',
            'created' => '2012-11-01 00:00:01',
            'modified' => '2012-11-01 00:00:01',
        ),
        array(
            'id' => 2,
            'item_id' => '2',
            'item_column_name' => 'confirm_point',
            'editor_token' => 'test_token2',
            'created' => '2012-11-01 00:00:01',
            'modified' => '2012-11-01 00:00:01',
        ),
    );
}
