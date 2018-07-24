<?php

App::uses('AppController', 'Controller');

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

    // 次回リリース日が過去日だった場合、１週間後の月曜日にする
    public function modifyNextReleaseDate()
    {
        $newest_variables = $this->SystemVariable->find('first', array('order' => array('id' => 'desc')));
        $next_release_date = Hash::get($newest_variables, 'SystemVariable.next_release_date');
        $today = date('Y-m-d');

        if ((strtotime($next_release_date) - strtotime($today)) < 0) {
            $new_next_release_date = date('Y-m-d', strtotime('next Monday'));
            $newest_id = Hash::get($newest_variables, 'SystemVariable.id');
            $this->SystemVariable->id = $newest_id;
            $save_data = $this->SystemVariable->read();
            $save_data['SystemVariable']['next_release_date'] = $new_next_release_date;
            if ($this->SystemVariable->save($save_data)) {
                echo true;
            } else {
                echo false;
            }
            $this->log('modified next_release_date '. $new_next_release_date);
        }
    }
}
