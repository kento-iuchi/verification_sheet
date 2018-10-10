<?php

class ReviewerAssigningFixture extends CakeTestFixture
{
    /**
     * Import
     *
     * @var array
     */
    public $import = array(
        'model' => 'ReviewerAssigning'
    );

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => 1,
            'item_id' => 1,
            'item_closed' => 0,
            'reviewing_author_id' => 1,
            'review_stage' => 1,
            'is_reviewed' => 0,
        ),
        array(
            'id' => 2,
            'item_id' => 2,
            'item_closed' => 1,
            'reviewing_author_id' => 1,
            'review_stage' => 1,
            'is_reviewed' => 0,
        ),
        array(
            'id' => 3,
            'item_id' => 1,
            'item_closed' => 0,
            'reviewing_author_id' => 2,
            'review_stage' => 2,
            'is_reviewed' => 0,
        ),
        array(
            'id' => 4,
            'item_id' => 1,
            'item_closed' => 0,
            'reviewing_author_id' => 1,
            'review_stage' => 1,
            'is_reviewed' => 1,
        ),
    );
}
