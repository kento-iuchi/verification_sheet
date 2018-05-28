<?php
App::uses('ItemsController', 'Controller');

class ItemShell extends AppShell
{
    function startup()
    {
        parent::startup();
        $this->ItemsController = new ItemsController();
    }

    public function day_before_confirm()
    {
        $this->ItemsController->send_grace_days_alert();
    }

    public function elapsed_days_alert()
    {
        $this->ItemsController->elapsed_days_alert();
    }
}
