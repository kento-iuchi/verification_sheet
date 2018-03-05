<?php
// ini_set('display_errors',1);

class ItemsController extends AppController {
    public $helpers = array('Html', 'Form', 'Flash', 'js', 'DatePicker');

    public $paginate =  array(
        'limit' => 5,
        'sort' => 'id',
    );

    public function index()
    {
        $this->layout = 'IndexLayout';
        $this->set('items',  $this->paginate());
        // $this->set('items', $this->Item->find('all'));
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
        $this->Item->id = $id;
        $this->request->data = $this->Item->read();
        $this->request->data["Item"][$column_name] = $content;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo $content;
            } else {
                echo "失敗です";
            }
        }
    }


    public function delete($id)
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Item->delete($id)) {
            $this->Flash->success(
                __('The post with id: %s has been deleted.', h($id))
            );
        } else {
            $this->Flash->error(
                __('The post with id: %s could not be deleted.', h($id))
            );
        }

        return $this->redirect(array('action' => 'index'));
    }


}
