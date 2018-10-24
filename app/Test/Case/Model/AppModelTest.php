<?php
App::uses('AppModel', 'Model');

class AppModelTest extends CakeTestCase
{
    public $fixtures = array(
    );

    public function setUp()
    {
        parent::setUp();
        $this->App = ClassRegistry::init('AppModel');
    }

    public function test_isValidDateFormat_日付の判定が正しく行われること()
    {
        $result = $this->App->isValidDateFormat('');
        $this->assertFalse($result);

        $result = $this->App->isValidDateFormat('2011-05-05');
        $this->assertTrue($result);

        $result = $this->App->isValidDateFormat('2012/03/04');
        $this->assertFalse($result);

    }

    public function test_generate_chatwork_message_メッセージの生成が正しく行われること()
    {
        $result = $this->App->generate_chatwork_message('本文');
        $this->assertEqual($result, '本文');

        $result = $this->App->generate_chatwork_message('本文', 'タイトル');
        $this->assertEqual($result, '[info][title]タイトル[/title]本文[/info]');
    }

    public function test_send_message_to_chatwork_メッセージが送信されること()
    {
        $result = $this->App->send_message_to_chatwork('メッセージ送信テスト１');
        $this->assertEqual($result['body'], 'メッセージ送信テスト１');

        $result = $this->App->send_message_to_chatwork('メッセージ送信テスト２', '117098373'); // こづちくん２号
        $this->assertEqual($result['body'], 'メッセージ送信テスト２');
    }
}
