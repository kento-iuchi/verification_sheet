<?php
ini_set('display_errors',1);

App::uses('AppController', 'Controller');


class ItemsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Flash', 'Js', 'DatePicker');

    public $paginate =  array(
        'limit'      => 20,
        'sort'       => 'id',
    );

    public $components = array(
        'Search.Prg' => array(
            'commonProcess' => array(
                'paramType' => 'querystring',
                // 'filterEmpty' =>  true,
            ),
        ),
    );
    public $presetVars = true;

    public function index()
    {
        $this->header("Content-type: text/html; charset=utf-8");
        $this->layout = 'IndexLayout';
        $this->loadModel('VerificationHistory');
        $this->loadModel('Verifier');
        $this->loadModel('Author');
        $this->set('items', $this->paginate('Item', array('is_completed' => 0)));
        $this->set('verifier', $this->Verifier->find('all'));
        $this->set('author', $this->Author->find('all'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                return $this->redirect(array('action' => 'index'));
            } else {
                echo "add errot";
            }
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function edit()
    {
        $this->autoRender = false;

        $this->Item->id = $this->request->data['id'];
        $column_name = $this->request->data['column_name'];
        $content = $this->request->data['content'];

        $this->request->data = $this->Item->read();
        $this->request->data['Item'][$column_name] = $content;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo $content;
            } else {
                echo '失敗です';
            }
        }
    }

    public function toggle_complete_state($id = null)
    {
        $this->autoRender = false;

        $this->Item->id = $id;
        $this->request->data = $this->Item->read();
        $this->request->data['Item']['is_completed'] = $this->request->data['Item']['is_completed'] == 0 ? 1 : 0;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo '変更しました';
            } else {
                echo '変更できませんでした';
            }
        }
    }

    public function show_completed()
    {
        $conditions = array(
            'is_completed' => 1,
        );

        $query_data = array(
            'from_created' => '',
            'to_created' => '',
            'from_merge_finish_date_to_master' => '',
            'to_merge_finish_date_to_master' => '',
        );
        if(!empty($this->request->query)){
            $query_data = $this->request->query['data'];
            $conditions = array_merge($conditions, $this->Item->parseCriteria($query_data));
        }
        $this->layout = 'IndexLayout';
        $this->loadModel('VerificationHistory');
        $this->loadModel('Verifier');
        $this->loadModel('Author');
        $this->set('items', $this->paginate('Item', $conditions));
        $this->set('verifier', $this->Verifier->find('all'));
        $this->set('author', $this->Author->find('all'));
        $this->set('query', $query_data);
    }

    public function save_verification_history()
    {
        $this->autoRender = false;
        $this->loadModel('VerificationHistory');
        $this->VerificationHistory->create();
        if ($this->VerificationHistory->save($this->request->data)) {
            echo $this->VerificationHistory->id;
        } else {
            echo 'save failed';
        }
    }

    public function send_grace_days_alert()
    {
        $message = '[info][title]おしらせ[/title]';
        $today_date = new Datetime(date("y-m-d"));
        $contents = Hash::extract($this->Item->find('all'), '{n}.Item[is_completed=0].content');
        $scheduled_release_dates = Hash::extract($this->Item->find('all'), '{n}.Item[is_completed=0].scheduled_release_date');
        foreach ($scheduled_release_dates as $i => $schedled_release_date) {
            $scheduled_release_date = new Datetime($schedled_release_date);
            $grace_days = $today_date->diff($scheduled_release_date)->format('%r%a');
            if($grace_days <= 7){
                $message .=  '■'. $contents[$i] . "\n";
                $message .=  "　リリース予定日まで {$grace_days} 日です\n";
            }
        }
        $message.= '[/info]';

        $room_id = 99451000;
        $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API URL
        debug($url);
        $api_key = "20c9d2043b146718a2ba9352179bc10e";

        $params = array(
            'body' => $message // メッセージ内容
        );

        $options = array(
            CURLOPT_URL => $url, // URL
            CURLOPT_HTTPHEADER => array('X-ChatWorkToken: '. $api_key), // APIキー
            CURLOPT_RETURNTRANSFER => true, // 文字列で返却
            CURLOPT_SSL_VERIFYPEER => false, // 証明書の検証をしない
            CURLOPT_POST => true, // POST設定
            CURLOPT_POSTFIELDS => http_build_query($params, '', '&'), // POST内容
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response);

    }

    public function retrieve_github_push(){
        $this->log('pushてすと9');
        echo 'post successed';
        $this->autoRender = false;
        include(__DIR__.'/../Config/webhook_key.php');
        $this->log($this->request->data);
        $this->log($this->request->query['key']);

        $key = $this->request->query['key'];
        if($key == $GITHUB_WEBHOOK_KEY){
            $this->log('successd');
        }
    }

}
