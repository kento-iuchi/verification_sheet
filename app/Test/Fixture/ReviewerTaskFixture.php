<?php

class ReviewerTaskFixture extends CakeTestFixture
{
    /**
     * Import
     *
     * @var array
     */
    public $import = array(
        'model' => 'ReviewerTask'
    );

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => 1,
            'reviewer_id' => 7,
            'review_count' => 1,
            'total_pivotal_point' => 5,
        ),
        array(
            'id' => 2,
            'reviewer_id' => 5,
            'review_count' => 1,
            'total_pivotal_point' => 5,
        ),
        array(
            'id' => 3,
            'reviewer_id' => 9,
            'review_count' => 8,
            'total_pivotal_point' => 5,
        ),
        array(
            'id' => 4,
            'reviewer_id' => 11,
            'review_count' => 3,
            'total_pivotal_point' => 6,
        )
    );
}
