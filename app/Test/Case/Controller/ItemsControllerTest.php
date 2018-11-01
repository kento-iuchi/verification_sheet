<?php
App::uses('ItemsController', 'Controller');

class ItemsControllerTest extends ControllerTestCase
{
    public $fixtures = array(
        'app.item',
        'app.editing_item',
        'app.author',
        'app.verifier',
        'app.reviewer_assigning',
        'app.reviewer_task',
        'app.verification_history',
        'app.confirm_comment_response',
        'app.system_variable',
    );

    public function setUp()
    {
        parent::setUp();
        $this->Item = ClassRegistry::init('Item');
        $this->EditingItem = ClassRegistry::init('EditingItem');
        $this->ReviewerAssigning = ClassRegistry::init('ReviewerAssigning');
    }

    public function test_edit_ステータスを編集時、メッセージが送信されること()
    {
        /*
        'サポート・営業確認中'の場合は検証担当者、
        '差し戻し'の場合はauthorにメッセージが送信される
        */
        $data = array(
            'id' => 1,
            'content' => 'サポート・営業確認中',
            'column_name' => 'status'
        );
        $result = $this->testAction(
            '/items/edit',
            array('data' => $data, 'method' => 'post')
        );
        $result = json_decode($result, true);
        $this->assertEqual('サポート・営業確認中', $result['Item']['status']);

        $data = array(
            'id' => 1,
            'content' => '改修中',
            'column_name' => 'status'
        );
        $result = $this->testAction(
            '/items/edit',
            array('data' => $data, 'method' => 'post')
        );
        $result = json_decode($result, true);
        $this->assertEqual('改修中', $result['Item']['status']);

        $data = array(
            'id' => 1,
            'content' => '差し戻し',
            'column_name' => 'status'
        );
        $result = $this->testAction(
            '/items/edit',
            array('data' => $data, 'method' => 'post')
        );
        $result = json_decode($result, true);
        $this->assertEqual('差し戻し', $result['Item']['status']);
    }

    public function test_edit_リリース判断日を編集時、メッセージが送信されること()
    {
        $data = array(
            'id' => 1,
            'content' => '2018/12/31',
            'column_name' => 'tech_release_jusgement'
        );
        $result = $this->testAction(
            '/items/edit',
            array('data' => $data, 'method' => 'post')
        );
        $result = json_decode($result, true);
        $this->assertEqual($result['Item']['tech_release_jusgement'], '2018/12/31');
    }

    public function test_edit_マージ完了日を編集時、リリース予定日が記録されること(){
        $data = array(
            'id' => 1,
            'content' => '2018-10-01',
            'column_name' => 'merge_finish_date_to_master'
        );
        $result = $this->testAction(
            '/items/edit',
            array('data' => $data, 'method' => 'post')
        );
        $result = json_decode($result, true);
        $this->assertTrue(! empty($result['Item']['scheduled_release_date']));
    }

    public function test_edit_現在の内容とフォームの入力内容が同じである為、何も更新されないこと()
    {
        $data = array(
            'id' => 1,
            'content' => '確認ポイント',
            'column_name' => 'confirm_points',
        );
        $result = $this->testAction(
            '/items/edit',
            array('data' => $data, 'method' => 'post')
        );
        $this->assertFalse($result);
        $target_item = $this->Item->find('first', array(
            'conditions' => array('id' => 1 ),
        ));
        $this->assertEqual('2012-11-01 00:00:01', $target_item['Item']['modified']);
    }

    public function test_edit_現在の内容とフォームの入力内容がどちらも空白である為、何も更新されないこと(){
        $data = array(
            'id' => 1,
            'content' => '*EMPTY*',
            'column_name' => 'tech_release_judgement',
        );
        $result = $this->testAction(
            '/items/edit',
            array('data' => $data, 'method' => 'post')
        );
        $this->assertFalse($result);
        $target_item = $this->Item->find('first', array(
            'conditions' => array('id' => 1 ),
        ));
        $this->assertEqual('2012-11-01 00:00:01', $target_item['Item']['modified']);
    }

    public function test_fetch_last_edit_time_正常に取得できること()
    {
        $data = array('id' => 1,);
        $result = $this->testAction(
            '/items/fetch_last_updated_time',
            array('data' => $data, 'method' => 'post')
        );
        $this->assertEqual($result, '100000');
    }

    public function test_fetch_items_list_somebody_editing_正常に取得できること()
    {
        $data = array('my_editor_token' => 'test_token1',);
        $result = $this->testAction(
            '/items/fetch_items_list_somebody_editing',
            array('data' => $data, 'method' => 'post')
        );
        $this->assertEqual(json_decode($result, true), array(
            array(
                'id' => 2,
                'item_id' => '2',
                'item_column_name' => 'confirm_point',
                'editor_token' => 'test_token2',
                'created' => '2012-11-01 00:00:01',
                'modified' => '2012-11-01 00:00:01',
            ),
        ));
    }

    public function test_register_item_editing_正常に登録できること()
    {
        $data = array(
            'item_id' => 1,
            'editor_token' => 'test_token3'
        );
        $result = $this->testAction(
            '/items/register_item_editing',
            array('data' => $data, 'method' => 'post')
        );
        $this->assertTrue(isset($result));

        // $last_id = $this->EditingItem->getLastInsertID();
        // echo $last_id;
        // $last_editing_item = $this->EditingItem->read($last_id);
        // print_r($last_editing_item);

        // $this->assertEqual($last_editing_item['EditingItem']['id'], 3);
    }

    public function test_accept_github_webhook_キー不一致でエラーになること()
    {
        $data = array();
        $result = $this->testAction(
            '/items/accept_github_webhook?key=wrong_webhook_key',
            array('data' => array('payload' => ''), 'method' => 'post')
        );
        $this->assertFalse($result);
    }

    // 新規作成
    public function test_accept_github_webhook_新規作成が正しく行えること()
    {
        $_SERVER['X-GitHub-Event'] = 'pull_request';

        // 保存成功
        $data = array(
            'payload' => '
                {
                    "number" : "6",
                    "action" : "opened",
                    "pull_request":{
                        "html_url":"http://test1111.com",
                        "title" : "テスト1111",
                        "id" : "11111",
                        "created_at" : "2018-09-28T09:20:05Z",
                        "user" : {
                            "login" : "test_github_author1"
                        }
                    }
                }
            ',
        );
        $result = $this->testAction(
            '/items/accept_github_webhook?key=test_webhook_key',
            array('data' => $data, 'method' => 'post')
        );
        $saved_item = $this->Item->find('first', array('order' => array('created' => 'desc')));
        $this->assertEqual($saved_item['Item']['needs_supp_confirm'], 1);
        $this->assertEqual($saved_item['Item']['category'], '未設定');
        $this->assertEqual($saved_item['Item']['division'], '改善');
        $this->assertEqual($saved_item['Item']['content'], '6テスト1111');
        $this->assertTrue(isset($saved_item['Item']['chatwork_url']));
        $this->assertEqual($saved_item['Item']['github_url'], 'http://test1111.com');
        $this->assertEqual($saved_item['Item']['pullrequest_id'], 11111);
        $this->assertEqual($saved_item['Item']['pullrequest_number'], 6);
        $this->assertEqual($saved_item['Item']['pullrequest'], '2018-09-28');
        $this->assertEqual($saved_item['Item']['author_id'], 1);
        $this->assertEqual($saved_item['Item']['status'], 'コードレビュー中');
        $this->assertEqual($saved_item['Item']['pivotal_point'], 0);
        $this->assertEqual($saved_item['Item']['is_completed'], 0);

        $review_assignings = $this->ReviewerAssigning->find('all', array('order' => array('id' => 'desc'), 'limit' => 2));
        $this->assertEqual($review_assignings[0]['ReviewerAssigning']['reviewing_author_id'], 5);
        $this->assertEqual($review_assignings[0]['ReviewerAssigning']['review_stage'], 2);
        $this->assertEqual($review_assignings[1]['ReviewerAssigning']['reviewing_author_id'], 7);
        $this->assertEqual($review_assignings[1]['ReviewerAssigning']['review_stage'], 1);
    }

    // 更新
    public function test_accept_github_webhook_更新が正しく行えること()
    {
        $_SERVER['X-GitHub-Event'] = 'pull_request';

        // 保存成功
        $data = array(
            'payload' => '
                {
                    "action" : "synchronize",
                    "number" : "111",
                    "pull_request":{
                        "title" : "テスト",
                        "id" : "1",
                        "html_url" : "http://test1111.com",
                        "updated_at" : "2018-09-29T10:00:05Z",
                        "user" : {
                            "login" : "test_github_author1"
                        }
                    }
                }
            ',
        );
        $result = $this->testAction(
            '/items/accept_github_webhook?key=test_webhook_key',
            array('data' => $data, 'method' => 'post')
        );
        $this->assertTrue($result);

        $saved_item = $this->Item->find('first', array('order' => array('modified' => 'desc')));
        $this->assertEqual($saved_item['Item']['pullrequest_update'], '2018-09-29');
    }

    // DBのにデータがなく更新失敗
    public function test_accept_github_webhook_更新が失敗すること()
    {
        $_SERVER['X-GitHub-Event'] = 'pull_request';

        // 保存成功
        $data = array(
            'payload' => '
                {
                    "action" : "synchronize",
                    "number" : "111",
                    "pull_request":{
                        "title" : "テスト",
                        "id" : "あああ",
                        "html_url" : "http://test1111.com",
                        "updated_at" : "2018-09-29T10:00:05Z",
                        "user" : {
                            "login" : "test_github_author"
                        }
                    }
                }
            ',
        );
        $result = $this->testAction(
            '/items/accept_github_webhook?key=test_webhook_key',
            array('data' => $data, 'method' => 'post')
        );
        $this->assertFalse($result);
    }

    // 完了済みにする
    public function test_accept_github_webhook_closeされたitemが完了済みになること()
    {
        $_SERVER['X-GitHub-Event'] = 'pull_request';

        // 保存成功
        $data = array(
            'payload' => '
                {
                    "action" : "closed",
                    "pull_request":{
                        "number" : "1",
                        "merged_at" : "2018-09-29T10:00:05Z"
                    }
                }
            ',
        );
        $result = $this->testAction(
            '/items/accept_github_webhook?key=test_webhook_key',
            array('data' => $data, 'method' => 'post')
        );
        $this->assertTrue($result);
        $saved_item = $this->Item->find('first', array('order' => array('modified' => 'desc')));
        $this->assertEqual($saved_item['Item']['is_completed'], 1);

        // reviewr_assigningの item_closedを確認
        $reviwer_assignings = $this->ReviewerAssigning->find('all', array(
            'conditions' => array(
                'item_id' => '1',
            )
        ));
        foreach ($reviwer_assignings as $reviwer_assigning){
            $this->assertEqual($reviwer_assigning['ReviewerAssigning']['item_closed'], 1);
        }
    }

    public function test_accept_github_webhook_コメント通知とレビュワーのアサイン解除が正しく行われること()
    {
        $_SERVER['X-GitHub-Event'] = 'pull_request_review_comment';

        // 保存成功
        $data = array(
            'payload' => '
                {
                    "action" : "created",
                    "pull_request" : {
                        "html_url" : "http://test.com",
                        "title" : "テスト1",
                        "number" : "1",
                        "user" : {
                            "login" : "test_github_author1"
                        }
                    },
                    "comment" : {
                        "body" : "コメントテストコメントテストコメントテスト\nコメントテストコメントテストコメントテストコメントテスト",
                        "user" : {
                            "login" : "test_github_author2"
                        }
                    }
                }
            ',
        );
        $result = $this->testAction(
            '/items/accept_github_webhook?key=test_webhook_key',
            array('data' => $data, 'method' => 'post')
        );
        $updated_assigning = $this->ReviewerAssigning->find('first', array('conditions' => array('id' => 3)));
        $this->assertEqual($updated_assigning['ReviewerAssigning']['is_reviewed'], 1);
        $this->assertTrue(true);
    }

}
