<?php

class SystemVariablesController extends AppController
{
    public function save_next_release_date()
    {
        $this->autoRender = false;

        try {
            $newest_variables = $this->SystemVariable->find('first', array('order' => array('SystemVariable.id' => 'desc')));
            $newest_id = Hash::get($newest_variables, 'SystemVariable.id');
            $this->SystemVariable->id = $newest_id;
            $save_data = $this->SystemVariable->read();

            if ($this->SystemVariable->isValidDateFormat($this->request->data['next_release_date'])) {
                $save_data['SystemVariable']['next_release_date'] = $this->request->data['next_release_date'];
                if ($this->SystemVariable->save($save_data)) {
                    return true;
                } else {
                    throw new Exception("saving next release date was failed");
                }
            } else {
                throw new Exception("invalid date format");
            }
        } catch(Exception $e){
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo "error：" . $e->getMessage();
            exit;
        }
    }
}
