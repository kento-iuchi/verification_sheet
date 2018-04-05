<?php
ini_set('display_errors',1);

class ItemsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Flash', 'Js', 'DatePicker');

    public $paginate =  array(
        'limit'      => 20,
        'sort'       => 'id',
    );

    public function index()
    {
        $this->layout = 'IndexLayout';
        $this->loadModel('VerificationHistory');
        $this->loadModel('Verifier');
        $this->log($this->Verifier->find('all'));
        $this->set('items', $this->paginate('Item', array('is_completed' => 0)));
        $this->set('verifier', $this->Verifier->find('all'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Item->create();
            $this->log($this->request->data);
            if ($this->Item->save($this->request->data)) {
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->log("validationErrors=" . var_export($this->Item->validationErrors, true));
            }
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function edit($id = null, $column_name, $content)
    {
        $this->autoRender = false;

        $this->Item->id = $id;
        $this->request->data = $this->Item->read();
        $this->request->data['Item'][$column_name] = $content;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo $content;
            } else {
                $this->log("validationErrors=" . var_export($this->Item->validationErrors, true));
                echo '失敗です';
            }
        }
    }

    public function toggle_complete_state($id = null)
    {
        $this->autoRender = false;

        $this->Item->id = $id;
        $this->request->data = $this->Item->read();
        $this->request->data['Item']['is_completed'] = $this->request->data['Item']['is_completed'] == 0 ? 1 : 0;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo '"完了"状態にしました';
            } else {
                echo '"完了"状態にできませんでした';
            }
        }
    }

    public function show_completed()
    {
        $this->layout = 'IndexLayout';
        $this->set('items', $this->paginate('Item', array('is_completed' => 1)));
    }

    public function save_verification_history()
    {
        $this->log($this->request->data);
        $this->autoRender = false;
        $this->loadModel('VerificationHistory');
        $this->VerificationHistory->create();
        if ($this->VerificationHistory->save($this->request->data)) {
            echo 'save success';
        } else {
            echo 'save failed';
        }
    }

}
