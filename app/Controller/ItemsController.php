<?php
ini_set('display_errors',1);

App::uses('AppController', 'Controller');

include(CONFIG. 'github_api_token.php');

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
        include(__DIR__.'/../Config/chatwork_api_token.php');

        $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API UR

        $params = array(
            'body' => $message // メッセージ内容
        );

        $options = array(
            CURLOPT_URL => $url, // URL
            CURLOPT_HTTPHEADER => array('X-ChatWorkToken: '. $CHATWORK_API_kEY), // APIキー
            CURLOPT_RETURNTRANSFER => true, // 文字列で返却
            CURLOPT_SSL_VERIFYPEER => false, // 証明書の検証をしない
            CURLOPT_POST => true, // POST設定
            CURLOPT_POSTFIELDS => http_build_query($params, '', '&'), // POST内容
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
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

        $this->log($payload['action']);
        if ($this->request->is('post')) {
            if (isset($this->$payload['pull_request'])){
                $this->log($payload['pull_request']['title']);
                if($key == $GITHUB_WEBHOOK_KEY){

                    $pullrequest_id = $payload['pull_request']['id'];

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
                            $this->Item->id = $update_item_id;
                            $new_item = $this->Item->read();
                            $new_item['Item']['pullrequest_update'] = explode('T', $payload['pull_request']['updated_at'])[0];

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
                    if($payload['action'] == 'closed'){
                        $this->check_all_open_pullrequests_mergeability();
                    }
                }
            }

            if (isset($this->$payload['pull_request_review_comment'])) {
                $this->log('comment test');
                $this->log($this->$payload['pull_request_review_comment']['comment']['action']);
                $this->log($this->$payload['pull_request_review_comment']['comment']['user']['id']);
                $this->log($this->$payload['pull_request_review_comment']['comment']['body']);
            }
        }
    }

    public function check_all_open_pullrequests_mergeability()
    {
        include(CONFIG. 'github_api_token.php');

        $this->autoRender = false;
        $url = $PR_LIST_URL. '?access_token='. $GITHUB_API_TOKEN. '&state=open';
        echo $url;
        $prs = shell_exec("curl {$url}");
        $prs = json_decode($prs);

        $null_pr_numbers = array();
        foreach ($prs as $pr) {
            $pr_number = $pr->number;
            if(!$this->alert_mergeable($pr_number)){
                $null_pr_numbers[] = $pr_number;
            }
        }
        // 一度APIを叩いた時点では$mergeableがnullの場合があるので、一度だけリトライする
        if (!empty($null_pr_numbers)) {
            foreach ($null_pr_numbers as $pr_number) {
                $this->alert_mergeable($pr_number);
            }
        }

    }

    public function alert_mergeable($pullrequest_number)
    {
        $this->autoRender = false;
        include(CONFIG. 'github_api_token.php');

        $url = $PR_LIST_URL. '/'. $pullrequest_number. '?access_token='. $GITHUB_API_TOKEN;

        $result = shell_exec("curl {$url}");
        $result = json_decode($result);

        $title = $result->title;
        $mergeable = $result->mergeable;
        $url = $result->html_url;
        $author_github_name = $result->user->login;

        $this->loadModel('Author');
        $authors = $this->Author->find('all');
        foreach ($authors as $data_id => $author_info) {
            if ($author_info['Author']['github_account_name'] == $author_github_name) {
                $author_chatwork_id = $author_info['Author']['chatwork_id'];
                break;
            }
        }

        echo $title. "<br>";
        echo $mergeable. "<br>";
        echo $url. "<br>";
        echo $author_chatwork_id. "<br>";

        $message = "[To:{$author_chatwork_id}][info][title]{$title}[/title]{$url}\n";
        if ($mergeable) {
            // $message .= ':)マージできます（テスト用メッセージ）'. '[/info]';
            // $this->send_message_to_chatwork($message);
            return true;
        } else if ($mergeable === false) {
            $message .= ':(マージできません'. '[/info]';
            $this->send_message_to_chatwork($message);
            return true;
        } else {
            return false;
        }
}

}
