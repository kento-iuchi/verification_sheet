<?php

App::uses('AppController', 'Controller');
App::import("Controller", "Items");

class VerificationHistoriesController extends AppController
{
    public $uses = array('Item', 'Author', 'VerificationHistory');

    public function edit()
    {
        $this->autoRender = false;

        $this->VerificationHistory->id = $this->request->data['id'];
        $content = $this->request->data['content'];
        $last_updated_time = $this->request->data['last_updated_time'];
        $this->request->data = $this->VerificationHistory->read();

        $this->request->data['VerificationHistory']['comment'] = str_replace('&&NEWLINE&&', '\n', $content);
        $this->request->data['VerificationHistory']['last_updated_time'] = $last_updated_time;

        if ($this->request->is(['ajax'])) {
            if ($this->VerificationHistory->save($this->request->data)) {
                $this->log("Edit verification history suceed [id:{$this->VerificationHistory->id}");
                echo true;
            } else {
                $this->log("Edit verification history failed [id:{$this->VerificationHistory->id}");
                echo false;
            }
        }
    }

    public function save()
    {
        $this->autoRender = false;
        $this->VerificationHistory->create();
        $this->request->data['verifier_id'] = $this->request->data['commenter_id'];
        if ($this->VerificationHistory->save($this->request->data)) {
            $result = $this->Item->find('first', array('conditions' => array('id' => $this->request->data['item_id'])));
            $title = Hash::get($result, 'Item.content');
            $author_id = Hash::get($result, 'Item.author_id');
            $result = $this->Author->read('chatwork_id', $author_id);
            $author_chatwork_id = Hash::get($result, 'Author.chatwork_id');

            $message = "[To:{$author_chatwork_id}][info][title]{$title}[/title]確認コメントが記載されました"
                       ."[code]{$this->request->data['comment']}[/code][/info]";
            $ItemsController = new ItemsController;
            $ItemsController->send_message_to_chatwork($message);
            echo $this->VerificationHistory->id;
        } else {
            echo 'failed to save verification history';
        }
    }

    public function fetch_last_updated_time()
    {
        $this->autoRender = false;
        $result = $this->Item->read('last_updated_time', $this->request->data['id']);
        $last_updated_time = Hash::get($result, 'Item.last_updated_time');

        return $last_updated_time;
    }
}
