<?php
App::uses('SystemVariablesController', 'Controller');

class SystemVariableShell extends AppShell
{
    public $uses = array('SystemVariable');

    function startup()
    {
        parent::startup();
        $this->SystemVariablesController = new SystemVariablesController();
    }

    public function reset_next_relese_date()
    {
        $this->SystemVariable->resetNextReleaseDate();
    }

    public function modify_next_relese_date()
    {
        $this->SystemVariablesController->modifyNextReleaseDate();
    }
}
