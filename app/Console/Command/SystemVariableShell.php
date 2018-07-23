<?php
class SystemVariableShell extends AppShell
{
    public $uses = array('SystemVariable');

    function startup()
    {
        parent::startup();
    }

    public function reset_next_relese_date()
    {
        $this->SystemVariable->resetNextReleaseDate();
    }
}
