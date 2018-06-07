<?php
ini_set('display_errors',1);

App::uses('AppController', 'Controller');


class ItemsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Flash', 'Js', 'DatePicker');

    public $paginate =  array(
        'limit' => 15,
        'order' => array('Item.modified' => 'desc'),
    );

    public $components = array(
        'Search.Prg' => array(
            'commonProcess' => array(
                'paramType' => 'querystring',
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
                echo "add error";
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

        // 「～～リリースOK判断日」が新たに入力されたときの処理
        if (in_array(
                $column_name,
                array('tech_release_judgement', 'supp_release_judgement', 'sale_release_judgement')
        )){
            if ($content !== null){
                $title = Hash::get($this->Item->read('content', $this->request->data['id']), 'Item.content');
                $column_name_text = array(
                    'tech_release_judgement' => '技術リリースOK判断日',
                    'supp_release_judgement' => 'サポートリリースOK判断日',
                    'sale_release_judgement' => '営業リリースOK判断日',
                );
                $message = '[info][title]'. $title.'[/title]';
                $message .= $column_name_text[$column_name]. 'が更新されました[/info]';


                $room_id = 103474903;
                $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API URL

                $this->send_message_to_chatwork($message);
            }
        }

        $this->request->data = $this->Item->read();
        if ($column_name == 'tech_release_judgement'){
            $this->request->data['Item']['status'] = 'サポート・営業確認中';
        }
        $this->request->data['Item'][$column_name] = $content;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                echo $content;
            } else {
                echo '失敗です';
            }
        }
    }

    public function fetch_last_updated_time()
    {
        $this->autoRender = false;
        $result = $this->Item->read('last_updated_time', $this->request->data['id']);
        $last_updated_time = Hash::get($result, 'Item.last_updated_time');

        return $last_updated_time;
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

    public function send_message_to_chatwork($message, $room_id = 103474903)
    {
        $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API UR
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
        $this->log($response);
        return json_decode($response)->message_id;
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

        $room_id = 103474903;
        $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API URL

        $this->send_message_to_chatwork($message);
    }

    public function elapsed_days_alert()
    {
        $message = '[info][title]おしらせ[/title]';
        $today_date = new Datetime(date("y-m-d"));
        $column_name_text = array(
            'tech_release_judgement' => '技術リリースOK判断日',
            'supp_release_judgement' => 'サポートリリースOK判断日',
            'sale_release_judgement' => '営業リリースOK判断日',
        );
        $titles = Hash::extract($this->Item->find('all'), '{n}.Item[is_completed=0].content');
        foreach ($column_name_text as $column_name=> $column_name_jp) {
            $target_column = Hash::extract($this->Item->find('all'), "{n}.Item[is_completed=0].{$column_name}");
            foreach ($target_column as $idx => $value) {
                $value_date = new Datetime($value);
                $elapsed_days = $value_date->diff($today_date)->format('%r%a');
                if($elapsed_days >= 3){
                    $message .=  '■'. $titles[$idx] . "\n";
                    $message .=  "　{$column_name_text[$column_name]}から {$elapsed_days} 日経過しています\n";
                }
            }
        }

        $message.= '[/info]';

        $room_id = 103474903;
        $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API URL

        $this->send_message_to_chatwork($message);
    }

    public function retrieve_github_push()
    {
        $this->autoRender = false;

        include(__DIR__.'/../Config/webhook_key.php');

        $payload = json_decode($this->request->data['payload'], true);
        $key = $this->request->query['key'];

        if ($this->request->is('post')) {
            if($key == $GITHUB_WEBHOOK_KEY){

                if ($payload['pull_request']['mergeable_state'] == 'dirty'){
                    $this->log('dirty');
                    $unmergeable_message = $payload['pull_request']['title']. "\n";
                    $unmergeable_message .= 'マージできません';
                    $message_id = $this->send_message_to_chatwork($unmergeable_message);
                }
                if ($payload['pull_request']['mergeable_state'] == 'clean'){
                    $this->log('clean');
                    $mergeable_message = $payload['pull_request']['title']. "\n";
                    $mergeable_message .= 'マージできます（テスト用メッセージ）';
                    $message_id = $this->send_message_to_chatwork($mergeable_message);
                }
                if ($payload['action'] == 'opened' ||
                    $payload['action'] == 'synchronize')
                   {

                    $message = '[info][title]'.  $payload['number'] . ' ' . $payload['pull_request']['title']. "[/title]";
                    $message .=  $payload['pull_request']['html_url'];

                    $author_github_name = $payload['pull_request']['user']['login'];
                    $this->loadModel('Author');
                    $authors = $this->Author->find('all');
                    $author_id = 1;
                    foreach ($authors as $data_id => $author_info) {
                        if ($author_info['Author']['github_account_name'] == $author_github_name) {
                            $author_id = $author_info['Author']['id'];
                            break;
                        }
                    }

                    $pullrequest_id = $payload['pull_request']['id'];

                    $due_date_for_release = date('Y-m-t', strtotime(date('+1 month')));

                    if ($payload['action'] == 'opened') {
                        $this->Item->create();
                        $new_item = array(
                            'Item' => array(
                                'content' => $payload['number'] . $payload['pull_request']['title'],
                                'github_url' => $payload['pull_request']['html_url'],
                                'chatwork_url' => '',
                                'status' => 'コードレビュー中',
                                'category' => '未設定',
                                'division' => '改善',
                                'verification_enviroment_url' => '',
                                'pullrequest_id' => $pullrequest_id,
                                'pullrequest' => explode('T', $payload['pull_request']['created_at'])[0], // payloadの中身をformatする
                                'due_date_for_release' => $due_date_for_release,
                                'scheduled_release_date' => '2099-12-31',
                                'confirm_comment' => $payload['pull_request']['body'],
                                'author_id' => $author_id,
                                'pivotal_point' => 1,
                            )
                        );
                        $message .= '[code]'.  $payload['pull_request']['body']. "[/code]\n";
                    } else {

                        $items = $this->Item->find('all');
                        $update_item_id = null;
                        foreach ($items as $item_info) {
                            if ($item_info['Item']['pullrequest_id'] == $pullrequest_id){
                                $update_item_id = $item_info['Item']['id'];
                                break;
                            }
                        }
                        $this->log($pullrequest_id);
                        $this->log($update_item_id);
                        $this->Item->id = $update_item_id;
                        $new_item = $this->Item->read();
                        $new_item['Item']['pullrequest_update'] = explode('T', $payload['pull_request']['updated_at'])[0];
                        // $new_item = array(
                        //     'Item' => array(
                        //         'id' => $update_item_id,
                        //         'pullrequest_update' => explode('T', $payload['pull_request']['updated_at'])[0], // payloadの中身をformatする
                        //     )
                        // );
                        $message .= "\nプルリクが更新されました\n";
                    }

                    $message .= 'by ' . $payload['pull_request']['user']['login'];
                    $message .= '[/info]';

                    $room_id = 103474903;
                    $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API URL

                    $message_id = $this->send_message_to_chatwork($message);
                    $new_item['Item']['chatwork_url'] = "https://www.chatwork.com/#!rid103474903/#!rid{$room_id}-{$message_id}";

                    if ($this->Item->save($new_item)) {
                        $this->log('save from github: successed');
                    } else {
                        $this->log('save from github: failed');
                    }

                }
            }
        }
    }

}
