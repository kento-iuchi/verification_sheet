<?php
class ItemsController extends AppController {
    public $helpers = array('Html', 'Form', 'Flash', 'js');
    public $components = array('Flash');

    public function index() {
        $this->set('items', $this->Item->find('all'));

        $this->autoLayout = false;
        $this->layout = 'IndexLayout';
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                $this->Flash->success(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to add your post.'));
        }
        // if ($this->request->is('ajax')) {
        //     $this->Item->create();
        //     if ($this->Item->save($this->request->data)) {
        //         $this->autoRender = false;
        //         $this->autoLayout = false;
        //         $response = $this->request->data['id'];
        //         $this->header('Content-Type: application/json');
        //         echo json_encode($response);
        //         exit();
        //     }
        //     $this->Flash->error(__('Unable to add your post.'));
        // }
        return $this->redirect(array('action' => 'index'));
    }

    public function edit($id = null) {
        $this->Item->id = $id;
        if ($this->request->is('get')) {
            $this->autoLayout = false;
            $this->request->data = $this->Item->read();
        } else {
            if ($this->Item->save($this->request->data)) {
                $this->Flash->success(__('Your post has been saved.'));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->error(__('failed!'));
            }
        }
    }


}
