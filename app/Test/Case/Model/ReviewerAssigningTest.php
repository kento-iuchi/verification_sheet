<?php
App::uses('ReviewerAssigning', 'Model');

class ReviewerAssigningTest extends CakeTestCase
{
    public $fixtures = array(
        'app.item',
        'app.author',
        'app.reviewer_assigning',
        'app.reviewer_task',
        'app.verification_history',
        'app.confirm_comment_response',
        'app.system_variable',
    );

    public function setUp()
    {
        parent::setUp();
        $this->ReviewerAssigning = ClassRegistry::init('ReviewerAssigning');
    }

    public function test_assign_reviewer_正常にアサインできること()
    {
        $result = $this->ReviewerAssigning->assignReviewer(1);

        $newest_assignings = $this->ReviewerAssigning->find('all', array(
            'order' => array(
                'id' => 'desc',
            ),
            'limit' => 2
        ));

        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['id'], '6');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['item_id'], '1');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['item_closed'], '0');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['reviewing_author_id'], '5');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['review_stage'], '2');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['is_reviewed'], '0');
        $this->assertTrue(isset($newest_assignings[0]['ReviewerAssigning']['created']));
        $this->assertTrue(isset($newest_assignings[0]['ReviewerAssigning']['modified']));

        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['id'], '5');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['item_id'], '1');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['item_closed'], '0');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['reviewing_author_id'], '7');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['review_stage'], '1');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['is_reviewed'], '0');
        $this->assertTrue(isset($newest_assignings[1]['ReviewerAssigning']['created']));
        $this->assertTrue(isset($newest_assignings[1]['ReviewerAssigning']['modified']));
    }

    public function test_assign_reviewer_作成者と同じ人がアサインされないこと()
    {
        $result = $this->ReviewerAssigning->assignReviewer(2);

        $newest_assignings = $this->ReviewerAssigning->find('all', array(
            'order' => array(
                'id' => 'desc',
            ),
            'limit' => 2
        ));

        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['id'], '6');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['item_id'], '2');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['item_closed'], '0');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['reviewing_author_id'], '5');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['review_stage'], '2');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['is_reviewed'], '0');
        $this->assertTrue(isset($newest_assignings[0]['ReviewerAssigning']['created']));
        $this->assertTrue(isset($newest_assignings[0]['ReviewerAssigning']['modified']));

        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['id'], '5');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['item_id'], '2');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['item_closed'], '0');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['reviewing_author_id'], '11');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['review_stage'], '1');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['is_reviewed'], '0');
        $this->assertTrue(isset($newest_assignings[1]['ReviewerAssigning']['created']));
        $this->assertTrue(isset($newest_assignings[1]['ReviewerAssigning']['modified']));
    }

    public function test_assignReviewerFromBrowser()
    {
        $payload = [
            'action' => "review_requested",
            'pull_request' => [
                'number' => '3',
                "requested_reviewers" => [
                    [
                        "login" =>  "test_github_author1",
                    ],
                    [
                        "login" =>  "test_github_author2",
                    ],
                ],
            ],
        ];

        $this->ReviewerAssigning->assignReviewerFromBrowser($payload);
        $newest_assignings = $this->ReviewerAssigning->find('all', array(
            'order' => array(
                'id' => 'desc',
            ),
            'limit' => 2
        ));

        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['id'], '6');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['item_id'], '3');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['item_closed'], '0');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['reviewing_author_id'], '5');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['review_stage'], '2');
        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['is_reviewed'], '0');
        $this->assertTrue(isset($newest_assignings[0]['ReviewerAssigning']['created']));
        $this->assertTrue(isset($newest_assignings[0]['ReviewerAssigning']['modified']));

        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['id'], '5');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['item_id'], '3');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['item_closed'], '0');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['reviewing_author_id'], '1');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['review_stage'], '1');
        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['is_reviewed'], '0');
        $this->assertTrue(isset($newest_assignings[1]['ReviewerAssigning']['created']));
        $this->assertTrue(isset($newest_assignings[1]['ReviewerAssigning']['modified']));

        // 同じpayloadを再送する
        // 同item_id, 同author_idのレコードが存在しているため
        // 作成も更新も行われていないことを確認
        $result = $this->ReviewerAssigning->assignReviewerFromBrowser($payload);
        $this->assertTrue(empty($result));

        $newest_assignings = $this->ReviewerAssigning->find('all', array(
            'order' => array(
                'id' => 'desc',
            ),
            'limit' => 2
        ));

        $this->assertEqual($newest_assignings[0]['ReviewerAssigning']['id'], '6');

        $this->assertEqual($newest_assignings[1]['ReviewerAssigning']['id'], '5');

    }

    public function test_assignReviewerFromBrowser_二人同時にアサインしたが片方はassigningが既存()
    {
        $payload = [
            'action' => "review_requested",
            'pull_request' => [
                'number' => '10000000',
                "requested_reviewers" => [
                    [
                        "login" =>  "test_github_author1", // id = 1 既存
                    ],
                    [
                        "login" =>  "test_github_author2", // id = 5
                    ],
                ],
            ],
        ];

        $reuslt = $this->ReviewerAssigning->assignReviewerFromBrowser($payload);
        $newest_assignings = $this->ReviewerAssigning->find('first', array(
            'order' => array(
                'id' => 'desc',
            ),
        ));

        $this->assertEqual($newest_assignings['ReviewerAssigning']['id'], '5');
        $this->assertEqual($newest_assignings['ReviewerAssigning']['item_id'], '2');
        $this->assertEqual($newest_assignings['ReviewerAssigning']['item_closed'], '0');
        $this->assertEqual($newest_assignings['ReviewerAssigning']['reviewing_author_id'], '5');
        $this->assertEqual($newest_assignings['ReviewerAssigning']['review_stage'], '2');
        $this->assertEqual($newest_assignings['ReviewerAssigning']['is_reviewed'], '0');
        $this->assertTrue(isset($newest_assignings['ReviewerAssigning']['created']));
        $this->assertTrue(isset($newest_assignings['ReviewerAssigning']['modified']));

    }

    public function test_alert_not_reviewd_items_通知が正しく送信できること()
    {
        $result = $this->ReviewerAssigning->alert_not_reviewd_items();
        $this->assertFalse($result == false);
        $this->assertEqual(count($result), 1);
    }

    public function test_turnReviewedFromGitHubRequest_issue_commentで、レビューされたフラグを更新成功()
    {
        $payload = [
            'action' => 'created',
            'issue' => [
                'number' => 1
            ],
        ];

        $result = $this->ReviewerAssigning->turnReviewedFromGitHubRequest($payload);
        $this->assertTrue($result);

        $updated_assigning = $this->ReviewerAssigning->find('first', array('conditions' => array('id' => 1)));// is_reviewed = 0 のレコード抽出
        $this->assertEqual($updated_assigning['ReviewerAssigning']['is_reviewed'], 1);
    }

    public function test_turnReviewedFromGitHubRequest_pull_request_review_commentで、レビューされたフラグを更新成功()
    {
        $payload = [
            'action' => 'created',
            'pull_request' => [
                'number' => 1
            ],
        ];

        $result = $this->ReviewerAssigning->turnReviewedFromGitHubRequest($payload);
        $this->assertTrue($result);

        $updated_assigning = $this->ReviewerAssigning->find('first', array('conditions' => array('id' => 1)));// is_reviewed = 0 のレコード抽出
        $this->assertEqual($updated_assigning['ReviewerAssigning']['is_reviewed'], 1);
    }

    public function test_turnReviewedFromGitHubRequest_itemが見つからずエラー()
    {
        $payload = [
            'action' => 'created',
            'issue' => [
                'number' => 9999
            ],
        ];

        $result = $this->ReviewerAssigning->turnReviewedFromGitHubRequest($payload);
        $this->assertFalse($result);
    }
}
