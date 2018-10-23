<?php
App::uses('ConfirmCommentResponsesController', 'Controller');

class ConfirmCommentResponsesControllerTest extends ControllerTestCase
{
    public $fixtures = array(
        'app.item',
        'app.author',
        'app.verifier',
        'app.verification_history',
        'app.confirm_comment_response',
        'app.system_variable',
    );

    public function setUp()
    {
        parent::setUp();
        $this->ConfirmCommentResponse = ClassRegistry::init('ConfirmCommentResponse');
        $this->Item = ClassRegistry::init('Item');
    }

    public function test_edit_正常に編集できること()
    {
        $data = array(
            'id' => 1,
            'content' => '編集&&NEWLINE&&しました',
            'last_updated_time' => '10000'
        );
        $result = $this->testAction(
            '/confirm_comment_responses/edit',
            array('data' => $data, 'method' => 'post')
        );

        $edited_comment = $this->ConfirmCommentResponse->find('first', array(
            'conditions' => array(
                'id' => 1,
            )
        ));

        $this->assertEqual($edited_comment['ConfirmCommentResponse']['id'], '1');
        $this->assertEqual($edited_comment['ConfirmCommentResponse']['item_id'], '0');
        $this->assertEqual($edited_comment['ConfirmCommentResponse']['author_id'], '0');
        $this->assertEqual($edited_comment['ConfirmCommentResponse']['comment'], "編集\nしました");
        $this->assertEqual($edited_comment['ConfirmCommentResponse']['last_updated_time'], '10000');

        $this->assertTrue(isset($edited_comment['ConfirmCommentResponse']['modified']));
    }

    public function test_save_正常に保存できること()
    {
        $data = array(
            'item_id' => 1,
            'commenter_id' => 1,
            'comment' => 'コメントテスト'
        );

        $result = $this->testAction(
            '/confirm_comment_responses/save',
            array('data' => $data, 'method' => 'post')
        );

        $created_comment = $this->ConfirmCommentResponse->find('first', array(
            'order' => array(
                'id' => 'desc',
            )
        ));

        $this->assertEqual($created_comment['ConfirmCommentResponse']['id'], '2');
        $this->assertEqual($created_comment['ConfirmCommentResponse']['item_id'], '1');
        $this->assertEqual($created_comment['ConfirmCommentResponse']['author_id'], '1');
        $this->assertEqual($created_comment['ConfirmCommentResponse']['comment'], "コメントテスト");
        $this->assertEqual($created_comment['ConfirmCommentResponse']['last_updated_time'], '0');

        $this->assertTrue(isset($created_comment['ConfirmCommentResponse']['modified']));
    }
}
