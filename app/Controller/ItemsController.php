<?php
class ItemsController extends AppController {
    public $helpers = array('Html', 'Form');

    public function index() {
       $this->set('items', $this->Item->find('all'));
   }

}
