<?php
App::uses('ReviewerAssigningsController', 'Controller');

class ReviewerAssigningsControllerTest extends ControllerTestCase
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
        $this->Item = ClassRegistry::init('Item');
    }

    public function test_assign_reviewer_正常にアサインできること()
    {
        $result = $this->testAction(
            '/reviewer_assignings/assign_reviewer/1',
            array('method' => 'post')
        );

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

    // public function test_save_正常に保存できること()
    // {
    //     $data = array(
    //         'item_id' => 1,
    //         'commenter_id' => 1,
    //         'comment' => 'コメントテスト'
    //     );

    //     $result = $this->testAction(
    //         '/confirm_comment_responses/save',
    //         array('data' => $data, 'method' => 'post')
    //     );

    //     $created_comment = $this->ConfirmCommentResponse->find('first', array(
    //         'order' => array(
    //             'id' => 'desc',
    //         )
    //     ));

    //     $this->assertEqual($created_comment['ConfirmCommentResponse']['id'], '2');
    //     $this->assertEqual($created_comment['ConfirmCommentResponse']['item_id'], '1');
    //     $this->assertEqual($created_comment['ConfirmCommentResponse']['author_id'], '1');
    //     $this->assertEqual($created_comment['ConfirmCommentResponse']['comment'], "コメントテスト");
    //     $this->assertEqual($created_comment['ConfirmCommentResponse']['last_updated_time'], '0');

    //     $this->assertTrue(isset($created_comment['ConfirmCommentResponse']['modified']));
    // }
}
