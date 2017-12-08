<?php
class ItemsController extends AppController {
    public $helpers = array('Html', 'Form', 'Flash', 'js',
                            'Eip.Eip' => array('pathToJs' => '/bootstrap-editable/js/bootstrap-editable.min.js'),
                            'DatePicker');
    public $components = array('Flash', 'Eip.eip');

    public $paginate =  array(
        'limit' => 2,
        'sort' => 'id',
    );

    public function index() {
        $this->layout = 'IndexLayout';
        $this->set('items',  $this->paginate());
        // $this->set('items', $this->Item->find('all'));
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


    public function delete($id) {
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


    public function eipManual() {
    	if (!$this->myOwnSecurity($this->Auth->user())) {
    		return $this->redirect('/');
    	}
    	$data = $this->Eip->setupData('Page', array('Page' => array('is_active' => 1)));
    	$saved = $this->Page->save($data);
    	$this->set(compact('data', 'saved'));
    }


}
