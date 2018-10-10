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
        )
    );
}
