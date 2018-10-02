<?php
// App::uses('EditingItemsController', 'Controller');

class ReviewerAssigningShell extends AppShell
{
    public $uses = array('ReviewerAssigning');

    function startup()
    {
        parent::startup();
        // $this->EditingItemsController = new EditingItemsController();
    }

    public function alertNotReviewedItems(){
        $this->ReviewerAssigning->alert_not_reviewd_items();
    }
}
