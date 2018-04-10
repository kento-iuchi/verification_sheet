<?php
ini_set('display_errors',1);

class ItemsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Flash', 'Js', 'DatePicker');

    public $paginate =  array(
        'limit'      => 20,
        'sort'       => 'id',
    );

    public $components = array(
        'Search.Prg',
    );
    public $presetVars = true;

    public function index()
    {
        $this->header("Content-type: text/html; charset=utf-8");
        $this->layout = 'IndexLayout';
        $this->loadModel('VerificationHistory');
        $this->loadModel('Verifier');
        $this->loadModel('Author');
        $this->set('items', $this->paginate('Item', array('is_completed' => 0)));
        $this->set('verifier', $this->Verifier->find('all'));
        $this->set('author', $this->Author->find('all'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                return $this->redirect(array('action' => 'index'));
            } else {
                echo "add errot";
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
        // $this->Prg->commonProcess();
        $conditions = array(
            'is_completed' => 1,
        );
        if(!empty($this->request->data)){
            $conditions = array_merge($conditions, $this->Item->parseCriteria($this->request->data));
        }
        $this->layout = 'IndexLayout';
        $this->loadModel('VerificationHistory');
        $this->loadModel('Verifier');
        $this->loadModel('Author');
        $this->set('items', $this->paginate('Item', $conditions));
        $this->set('verifier', $this->Verifier->find('all'));
        $this->set('author', $this->Author->find('all'));
    }

    public function save_verification_history()
    {
        $this->autoRender = false;
        $this->loadModel('VerificationHistory');
        $this->VerificationHistory->create();
        if ($this->VerificationHistory->save($this->request->data)) {
            echo $this->VerificationHistory->id;
        } else {
            echo 'save failed';
        }
    }

}
