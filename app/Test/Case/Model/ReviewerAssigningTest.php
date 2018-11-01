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

        print_r($newest_assignings);

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
