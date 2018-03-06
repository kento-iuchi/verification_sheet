<?php
ini_set('display_errors',1);

class ItemsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Flash', 'js', 'DatePicker');

    public $paginate =  array(
        'conditions' => array('is_completed' => 0),
        'limit'      => 20,
        'sort'       => 'id',
    );

    public function index()
    {
        $this->layout = 'IndexLayout';
        $this->set('items', $this->paginate());
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Item->create();
            $this->log($this->request->data);
            if ($this->Item->save($this->request->data)) {
                return $this->redirect(array('action' => 'index'));
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

    public function complete($id = null)
    {
        $this->autoRender = false;
        
        $this->Item->id = $id;
        $this->request->data = $this->Item->read();
        $this->request->data['Item']['is_completed'] = 1;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo '"完了"状態にしました';
            } else {
                echo '"完了"状態にできませんでした';
            }
        }
    }

}
