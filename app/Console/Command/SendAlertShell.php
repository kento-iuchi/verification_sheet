<?php
App::uses('ItemsController', 'Controller');

class SendAlertShell extends AppShell
{
    function startup()
    {
        parent::startup();
        $this->ItemsController = new ItemsController();
    }

    public function grace_days_alert()
    {
        $this->ItemsController->send_grace_days_alert();
    }

    public function elapsed_days_alert()
    {
        $this->ItemsController->send_elapsed_days_alert();
    }
}
