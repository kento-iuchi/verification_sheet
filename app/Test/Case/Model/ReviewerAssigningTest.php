<?php
App::uses('ReviewerAssigning', 'Model');

class ReviewerAssigningTest extends CakeTestCase
{
    public $fixtures = array(
        'app.item',
        'app.author',
        'app.reviewer_assigning',
        'app.reviewer_task',
    );

    public function setUp()
    {
        parent::setUp();
        $this->ReviewerAssigning = ClassRegistry::init('ReviewerAssigning');
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
