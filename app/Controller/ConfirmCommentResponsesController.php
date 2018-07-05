<?php

App::uses('AppController', 'Controller');
App::import("Controller", "Items");

class ConfirmCommentResponsesController extends AppController
{
    public $uses = array('Item', 'Verifier', 'ConfirmCommentResponse');

    public function edit()
    {
        $this->autoRender = false;

        $this->ConfirmCommentResponse->id = $this->request->data['id'];
        $content = $this->request->data['content'];
        $last_updated_time = $this->request->data['last_updated_time'];
        $this->request->data = $this->ConfirmCommentResponse->read();

        $this->request->data['ConfirmCommentResponse']['comment'] = str_replace('&&NEWLINE&&', '\n', $content);
        $this->request->data['ConfirmCommentResponse']['last_updated_time'] = $last_updated_time;

        if ($this->request->is(['ajax'])) {
            if ($this->ConfirmCommentResponse->save($this->request->data)) {
                $this->log("Edit verification history suceed [id:{$this->ConfirmCommentResponse->id}");
                echo true;
            } else {
                $this->log("Edit verification history failed [id:{$this->ConfirmCommentResponse->id}");
                echo false;
            }
        }
    }

    public function save()
    {
        $this->autoRender = false;
        $this->ConfirmCommentResponse->create();
        $this->request->data['author_id'] = $this->request->data['commenter_id'];
        if ($this->ConfirmCommentResponse->save($this->request->data)) {
            $result = $this->Item->find('first', array('conditions' => array('id' => $this->request->data['item_id'])));
            $title = Hash::get($result, 'Item.content');
            $verifier_id = Hash::get($result, 'Item.verifier_id');
            $result = $this->Verifier->read('chatwork_id', $verifier_id);
            $verifier_chatwork_id = Hash::get($result, 'Verifier.chatwork_id');

            $message = "[To:{$verifier_chatwork_id}][info][title]{$title}[/title]確認コメント対応が記載されました"
                       ."[code]{$this->request->data['comment']}[/code][/info]";
            $ItemsController = new ItemsController;
            $ItemsController->send_message_to_chatwork($message);
            echo $this->ConfirmCommentResponse->id;
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
