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
}
