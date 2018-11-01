<?php

class ItemFixture extends CakeTestFixture
{
    /**
     * Import
     *
     * @var array
     */
    public $import = array(
        'model' => 'Item'
    );

    /**
     * Records
     *
     * @var array
     */
        public $records = array(
            array(
                'id' => 1,
                'pullrequest_number' => 1,
                'pullrequest_id' => 1,
                // 'content' => 'テスト1',
                // 'error_code' => '550',
                'verifier_id' => 1,
                'confirm_points' => '確認ポイント',
                'author_id' => 1,
                'created' => '2012-11-01 00:00:01',
                'modified' => '2012-11-01 00:00:01',
                'last_updated_time' => '100000',
                'is_completed' => 0,
            ),
            array(
                'id' => 2,
                'pullrequest_number' => 10000000,
                'pullrequest_id' => 2,
                // 'content' => 'テスト1',
                // 'error_code' => '550',
                'verifier_id' => 2,
                'confirm_points' => '確認ポイント',
                'created' => '2012-11-01 00:00:01',
                'modified' => '2012-11-01 00:00:01',
                'is_completed' => 0,
            ),
            array(
                'id' => 3,
                'pullrequest_number' => 3,
                'pullrequest_id' => 3,
                // 'content' => 'テスト1',
                // 'error_code' => '550',
                'verifier_id' => 1,
                'confirm_points' => '確認ポイント',
                'author_id' => 11,
                'created' => '2012-11-01 00:00:01',
                'modified' => '2012-11-01 00:00:01',
                'is_completed' => 0,
            ),
            // array(
            //     'id'         => 2,
            //     'address'    => 'error2@test.com',
            //     'error_code' => '550',
            //     'created'    => '2012-11-01 00:00:01',
            //     'modified'   => '2012-11-01 00:00:01',
            // ),
        );
}
