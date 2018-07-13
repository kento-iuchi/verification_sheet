<?php
App::uses('EditingItemsController', 'Controller');

class EditingItemShell extends AppShell
{
    function startup()
    {
        parent::startup();
        $this->EditingItemsController = new EditingItemsController();
    }

    public function unregisterLeftEditingItem(){
        $this->EditingItemsController->unregisterLeftEditingItem();
    }
}
