<?php
ini_set('display_errors',1);

App::uses('AppController', 'Controller');

class ItemsController extends AppController
{
    public $uses = array('Item', 'Author', 'Verifier', 'VerificationHistory', 'EditingItem', 'SystemVariable');

    public $helpers = array('Html', 'Form', 'Flash', 'Js', 'DatePicker');

    public $paginate =  array(
        'limit' => 25,
        'order' => array('Item.id' => 'asc'),
    );

    public $components = array(
        'Search.Prg' => array(
            'commonProcess' => array(
                'paramType' => 'querystring',
            ),
        ),
    );

    public function index($completed_mode_flag = 0)
    {
        $this->layout = 'IndexLayout';
        $this->header("Content-type: text/html; charset=utf-8");
        $conditions = array(
            'is_completed' => $completed_mode_flag,
        );
        $query = array(
            'status' => '',
            'from_created' => '',
            'to_created' => '',
            'from_merge_finish_date_to_master' => '',
            'to_merge_finish_date_to_master' => '',
        );
        if(!empty($this->request->query)){
            $query = $this->request->query['data'];
            $conditions = array_merge($conditions, $this->Item->parseCriteria($query));
        }
        $items = $this->paginate('Item', $conditions);
        $verifier_names = Hash::combine($this->Verifier->find('all'), '{n}.Verifier.id', '{n}.Verifier.name');
        $author_names = Hash::combine($this->Author->find('all'), '{n}.Author.id', '{n}.Author.name');
        $next_release_date = Hash::get($this->SystemVariable->find('first', array('order' => array('id' => 'desc'))), 'SystemVariable.next_release_date');
        $this->set(compact('query', 'items', 'completed_mode_flag', 'verifier_names', 'author_names', 'next_release_date'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Item->create();
            if ($this->Item->save($this->request->data)) {
                return $this->redirect(array('action' => 'index'));
            } else {
                echo "500: failed to add Item";
            }
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function edit()
    {
        $this->autoRender = false;

        $this->log('####### edit #######');
        $this->Item->id = $this->request->data['id'];
        $column_name = $this->request->data['column_name'];
        $content = $this->request->data['content'];

        $target_item = $this->Item->find('first', array('conditions' => array('Item.id' => $this->request->data['id'])));
        $title = Hash::get($target_item, 'Item.content');

        // 「～～リリースOK判断日」が新たに入力されたときの処理
        if ($column_name == 'status') {
            if ($content == 'サポート・営業確認中'){
                $target_chatwork_id = $this->Verifier->find('first', array(
                    'fields' => array('Verifier.chatwork_id', 'Item.id'),
                    'joins' => array(array(
                        'type' => 'LEFT',
                        'table' => 'items',
                        'alias' => 'Item',
                        'conditions' => "Verifier.id = Item.verifier_id",
                    )),
                    'conditions' => array('Item.id' => $this->request->data['id']),
                ));
                $target_chatwork_id = Hash::get($target_chatwork_id, 'Verifier.chatwork_id');
                if (!$target_chatwork_id) {
                    $verification_assigner_id = Configure::read('SystemSettings.verificationAssignerId');
                    $verification_assigner_chatwork_id = $this->Verifier->find('first', array(
                        'conditions' => array(
                            'id' => $verification_assigner_id,
                        ),
                        'fields' => array('chatwork_id'),
                    ));
                    $verification_assigner_chatwork_id = Hash::get($verification_assigner_chatwork_id, 'Verifier.chatwork_id');
                    $target_chatwork_id = $verification_assigner_chatwork_id;
                }
            }
            if ($content == '差し戻し'){
                $target_chatwork_id = $this->Author->find('first', array(
                    'fields' => array('Author.chatwork_id', 'Item.id'),
                    'joins' => array(array(
                        'type' => 'LEFT',
                        'table' => 'items',
                        'alias' => 'Item',
                        'conditions' => "Author.id = Item.author_id",
                    )),
                    'conditions' => array('Item.id' => $this->request->data['id']),
                ));
                $target_chatwork_id = Hash::get($target_chatwork_id, 'Author.chatwork_id');
            }
            $message = '';
            if ($target_chatwork_id) {
                $message .= "[to:{$target_chatwork_id}]";
            }
            $message .= "[info][title]No.{$this->request->data['id']} {$title}[/title]";
            $message .= 'ステータスが【' . $content . '】になりました[/info]';
            $this->send_message_to_chatwork($message);
        }
        else if (in_array(
                $column_name,
                array('tech_release_judgement', 'supp_release_judgement', 'sale_release_judgement')
        )){
            if ($content !== null){
                $column_name_text = array(
                    'tech_release_judgement' => '技術リリースOK判断日',
                    'supp_release_judgement' => 'サポートリリースOK判断日',
                    'sale_release_judgement' => '営業リリースOK判断日',
                );
                $message = '[info][title]'. $title.'[/title]';
                $message .= $column_name_text[$column_name]. 'が更新されました[/info]';

                $this->send_message_to_chatwork($message);
            }
        }

        $this->request->data = $this->Item->read();
        if ($column_name == 'merge_finish_date_to_master') {
            // 日付が正しいフォーマット化確認
            if ($this->Item->isValidDateFormat($content)) {
                // 次回リリース予定日をDBから取得
                $next_release_date = $this->SystemVariable->find('first', array('fields' => 'SystemVariable.next_release_date'));
                $next_release_date = Hash::get($next_release_date, 'SystemVariable.next_release_date');
                $this->log($next_release_date);
                // リリース予定日を記録
                $this->request->data['Item']['scheduled_release_date'] = $next_release_date;
            }
        }
        // if ($column_name == 'tech_release_judgement'){
        //     $this->request->data['Item']['status'] = 'サポート・営業確認中';
        // }
        $this->request->data['Item'][$column_name] = $content;
        if ($this->request->is(['ajax'])) {
            if ($this->Item->save($this->request->data)) {
                $this->log("Edit suceed [id:{$this->Item->id} field:{$column_name}]");
                echo true;
            } else {
                $this->log("Edit failed [id:{$this->Item->id} field:{$column_name}]");
                echo false;
            }
        }
    }

    public function get_time(){
        $this->autoRender = false;
        return time();
    }

    public function fetch_last_updated_time()
    {
        $this->autoRender = false;
        $this->log($this->request->data);
        $result = $this->Item->read('last_updated_time', $this->request->data['id']);
        $last_updated_time = Hash::get($result, 'Item.last_updated_time');

        return $last_updated_time;
    }

    public function fetch_items_list_somebody_editing()
    {
        $this->autoRender = false;

        $editing_items = $this->EditingItem->find(
            'all',
            array('conditions' =>
                array('editor_token !=' => $this->request->data['my_editor_token'])
            )
        );
        // 戻り値が「配列」もしくはbooleanというのはおかしいのだけれど
        // からの配列を返したときのCakeResponseエラーの対処がわからない
        if (empty($editing_items)) {
            return false;
        }

        $somebody_editing_item = Hash::extract($editing_items, "{n}.EditingItem");
        if (empty($somebody_editing_item)) {
            return false;
        }
        return json_encode($somebody_editing_item);
    }

    function register_item_editing()
    {
        $this->log('####### register editing item [id:'. $this->request->data['item_id'] . '] #######');
        $this->autoRender = false;
        if (empty($this->request->data['item_id']) || empty($this->request->data['editor_token'])) {
            return false;
        }

        $this->EditingItem->create();
        $this->request->data['EditingItem']['item_id'] = $this->request->data['item_id'];
        $this->request->data['EditingItem']['editor_token'] = $this->request->data['editor_token'];
        if ($this->EditingItem->save($this->request->data)) {
            return $this->request->data['item_id'];
        } else {
            return false;
        }
    }

    // editing_item の idではなくediting_itemのitem_idなことに注意
    function unregister_item_editing()
    {
        $this->log('####### unregister editing item [id:'. $this->request->data['item_id'] . '] #######');
        $this->autoRender = false;
        try{
            if (empty($this->request->data['item_id'])) {
                throw new Exception("item id is empty");
            }
            $target_editing_item = $this->EditingItem->find('first', array('conditions' => array('item_id' => $this->request->data['item_id'])));

            if ($this->EditingItem->delete(Hash::get($target_editing_item, 'EditingItem.id'))) {
                return true;
            } else {
                throw new Exception("failed to delete editing item");
            }
        }catch(Exception $e){
            echo "error：" . $e->getMessage();
        }
    }

    public function toggle_complete_state($id = null, $state = null)
    {
        $this->log('####### toggle item complete status [id:'. $id . '] #######');
        $this->autoRender = false;

        $this->Item->id = $id;
        $update_item = $this->Item->read();
        $this->log($update_item);
        if (isset($state)){
            $update_item['Item']['is_completed'] = $state;
        } else {
            $update_item['Item']['is_completed'] = $update_item['Item']['is_completed'] == 0 ? 1 : 0;
        }
        $this->log($update_item);
        if ($this->Item->save($update_item)) {
            $this->log('Successfully switched complete status');
        } else {
            $this->log('Failed to switch complete status');
        }
    }

    public function save_verification_history()
    {
        $this->log('####### save verification history #######');
        $this->autoRender = false;
        $this->VerificationHistory->create();
        if ($this->VerificationHistory->save($this->request->data)) {
            $result = $this->Item->find('first', array('conditions' => array('id' => $this->request->data['item_id'])));
            $title = Hash::get($result, 'Item.content');
            $author_id = Hash::get($result, 'Item.author_id');
            $result = $this->Author->read('chatwork_id', $author_id);
            $author_chatwork_id = Hash::get($result, 'Author.chatwork_id');

            echo $this->VerificationHistory->id;
        } else {
            echo 'failed to save verification history';
        }
    }

    public function send_message_to_chatwork($message, $room_id = null)
    {
        if (!$room_id) {
            $room_id = Configure::read('chatwork_confirm_room_id');
        }
        $this->log($room_id);

        $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages"; // API UR
        $params = array(
            'body' => $message // メッセージ内容
        );

        $options = array(
            CURLOPT_URL => $url, // URL
            CURLOPT_HTTPHEADER => array('X-ChatWorkToken: '. Configure::read('chatwork_api_token')), // APIキー
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
        $message = '[info][title]必須リリース日までの残日数[/title]';
        $today_date = new Datetime(date("y-m-d"));
        $incomplete_items = $this->Item->find('all', array('conditions' => array('is_completed' => 0)));
        $contents = Hash::extract($incomplete_items, '{n}.Item.content');
        $due_dates_for_release = Hash::extract($incomplete_items, '{n}.Item.due_date_for_release');
        if (empty(array_filter($due_dates_for_release))){
            return null;
        }
        foreach ($due_dates_for_release as $i => $due_date_for_release) {
            if (!isset($due_date_for_release)){
                continue;
            }
            $due_date_for_release = new Datetime($due_date_for_release);
            $grace_days = $today_date->diff($due_date_for_release)->format('%r%a');
            if($grace_days <= 7){
                $message .=  '■'. $contents[$i] . "\n";
                $message .=  "　必須リリース日まで {$grace_days} 日です\n";
            }
        }

        $message.= '[/info]';

        $this->send_message_to_chatwork($message);
    }

    public function send_elapsed_days_alert()
    {
        $message = '[info][title]おしらせ[/title]';
        $today_date = new Datetime(date("y-m-d"));
        $column_name_text = array(
            'tech_release_judgement' => '技術リリースOK判断日',
            'supp_release_judgement' => 'サポートリリースOK判断日',
            'sale_release_judgement' => '営業リリースOK判断日',
        );
        $items = $this->Item->find('all', array('conditions' => array('is_completed' => 0)));
        $titles = Hash::extract($items, '{n}.Item.content');
        foreach ($column_name_text as $column_name=> $column_name_jp) {
            $target_column = Hash::extract($items, "{n}.Item.{$column_name}");
            foreach ($target_column as $idx => $judge_date) {
                $judge_date = new Datetime($judge_date);
                $elapsed_days = $judge_date->diff($today_date)->format('%r%a');
                if($elapsed_days >= 3){
                    $message .=  '■'. $titles[$idx] . "\n";
                    $message .=  "　{$column_name_text[$column_name]}から {$elapsed_days} 日経過しています\n";
                }
            }
        }

        $message.= '[/info]';

        $this->send_message_to_chatwork($message);
    }

    public function retrieve_github_push()
    {
        $this->autoRender = false;

        $payload = json_decode($this->request->data['payload'], true);
        $key = $this->request->query['key'];

        $GITHUB_WEBHOOK_KEY = Configure::read('github_webhook_key');
        if ($this->request->is('post') && $key == $GITHUB_WEBHOOK_KEY) {
            if ($this->request->header('X-GitHub-Event') == 'pull_request'){
                $this->log('######## pull_request ########');
                $this->log($payload['action']);
                $this->log($payload['pull_request']['title']);
                $pullrequest_id = $payload['pull_request']['id'];

                if ($payload['action'] == 'opened' ||
                    $payload['action'] == 'synchronize'){

                    $message = '[info][title]'.  $payload['number'] . ' ' . $payload['pull_request']['title']. "[/title]";
                    $message .=  $payload['pull_request']['html_url'];

                    $author_github_name = $payload['pull_request']['user']['login'];

                    $author_names_and_ids = Hash::combine($this->Author->find('all'), '{n}.Author.github_account_name', '{n}.Author.id');
                    if (in_array($author_github_name, array_keys($author_names_and_ids))) {
                        $author_id = $author_names_and_ids[$author_github_name];
                    } else {
                        $author_id = null;
                    }

                    if ($payload['action'] == 'opened') {
                        $confirm_room_id = Configure::read('chatwork_confirm_room_id');
                        $this->Item->create();
                        $new_item = array(
                            'Item' => array(
                                'content' => $payload['number'] . $payload['pull_request']['title'],
                                'github_url' => $payload['pull_request']['html_url'],
                                'status' => 'コードレビュー中',
                                'category' => '未設定',
                                'division' => '改善',
                                'verification_enviroment_url' => '',
                                'pullrequest_number' => $payload['number'],
                                'pullrequest_id' => $pullrequest_id,
                                'pullrequest' => explode('T', $payload['pull_request']['created_at'])[0], // payloadの中身をformatする
                                'author_id' => $author_id,
                                'pivotal_point' => 1,
                            )
                        );
                        // $message .= '[code]'.  $payload['pull_request']['body']. "[/code]\n";
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

                    $message .= "\nby " . $payload['pull_request']['user']['login'];
                    $message .= '[/info]';

                    $message_id = $this->send_message_to_chatwork($message);
                    $new_item['Item']['chatwork_url'] = "https://www.chatwork.com/#!rid" . $confirm_room_id . "-{$message_id}";
                    if ($this->Item->save($new_item)) {
                        $this->log('save from github: successed');
                    } else {
                        $this->log('save from github: failed');
                    }

                }
                if ($payload['action'] == 'closed'){
                    $this->log('pull request closed [number: ' . $payload['pull_request']['number'] . ']');
                    $result = $this->Item->find('first', array(
                            'conditions' => array(
                                'pullrequest_number' => $payload['pull_request']['number'],
                                'is_completed' => 0,
                            )
                        )
                    );
                    if (!empty($result)) {
                        $this->toggle_complete_state(Hash::get($result, 'Item.id'), 1);
                    } else {
                        $this->log('Failed to fetch closed pull request data');
                    }
                    $this->check_all_open_pullrequests_mergeability();
                }
            }
            if (array_key_exists('issue', $payload) || array_key_exists('comment', $payload)) {
                $this->tell_code_review_comment($payload);
            }
        } else {
            $this->log('invalid webhook key');
        }
    }

    public function check_all_open_pullrequests_mergeability()
    {
        $this->autoRender = false;
        $url = Configure::read('pr_list_url'). '?access_token='. Configure::read('github_api_token'). '&state=open';
        echo $url;
        $prs = shell_exec("curl {$url}");
        if (!empty($prs)){
            ignore_user_abort(true);       // ブラウザから切断されても処理を中断しないようにする
            set_time_limit(0);             // 処理時間を無制限にする
            $response = 'OK';              // レスポンスに含める文字列
            $length = strlen($response );

            ob_start();                    // 出力をバッファにためる
            echo $response ;

            header("Content-Length: $length");
            header("Connection: close");   // HTTP接続を切る
            ob_end_flush();
            ob_flush();
            flush();                       // 溜めてあった出力を解放しフラッシュする
        }
        $prs = json_decode($prs);

        $null_pr_numbers = array();
        foreach ($prs as $pr) {
            if(!$this->alert_mergeable($pr->number)){
                $null_pr_numbers[] = $pr->number;
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

        $url = Configure::read('pr_list_url'). '/'. $pullrequest_number. '?access_token='. Configure::read('github_api_token');

        $result = shell_exec("curl {$url}");
        $result = json_decode($result);

        $title = $result->title;
        $mergeable = $result->mergeable;
        $url = $result->html_url;

        $author_cw_ids = Hash::combine($this->Author->find('all'), '{n}.Author.github_account_name', '{n}.Author.chatwork_id');
        $author_chatwork_id = $author_cw_ids[$result->user->login];

        echo $title. "<br>";
        echo $mergeable. "<br>";
        echo $url. "<br>";
        echo $author_chatwork_id. "<br>";

        $message = "[To:{$author_chatwork_id}][info][title]{$title}[/title]{$url}\n";
        if ($mergeable) {
            return true;
        } else if ($mergeable === false) {
            $message .= ':(コンフリクトしています'. '[/info]';
            $this->send_message_to_chatwork($message, Configure::read('chatwork_review_room_id'));
            return true;
        } else {
            return false;
        }
    }

    public function tell_code_review_comment($payload)
    {
        $this->autoRender = false;
        $this->log('######## issue_comment ########');
        $this->log($payload['action']);
        $this->log($payload['issue']['user']['login']);
        $this->log($payload['comment']['body']);

        if ($payload['action'] == 'created')
        {
            if (isset($payload['issue'])) {
                $url = $payload['issue']['html_url'];
                $title = $payload['issue']['title'];
                $target_github_name = $payload['issue']['user']['login'];
                $pullrequest_number = $payload['issue']['number'];
            } else if (isset($payload['comment'])){
                $url = $payload['pull_request']['html_url'];
                $title = $payload['pull_request']['title'];
                $target_github_name = $payload['pull_request']['user']['login'];
                $pullrequest_number = $payload['pull_request']['number'];
            }

            $authors = $this->Author->find('all');
            $target_chatwork_id = null;

            $reviewer_github_name = $payload['comment']['user']['login'];
            $reviewer = $this->Author->find('first', array('conditions' => array('Author.github_account_name' => $reviewer_github_name)));
            $reviewer_id = $reviewer['Author']['id'];
            $reviewed_item = $this->Item->find('first', array('conditions' => array('Item.pullrequest_number' => $pullrequest_number)));
            $last_reviewr_id = $reviewed_item['Item']['last_reviewed_author_id'];
            if ($target_github_name == $reviewer_github_name) { // 自分で自分のプルリクにコメントした場合、最後にレビューした人にメッセージを飛ばす
                $author_cw_ids = Hash::combine($this->Author->find('all'), '{n}.Author.id', '{n}.Author.chatwork_id');
                $target_chatwork_id = $author_cw_ids[$last_reviewr_id];
            } else { // 最終レビュワーを更新
                if ($last_reviewr_id != $reviewer_id || empty($last_reviewr_id)) {
                    $reviewed_item['Item']['last_reviewed_author_id'] = $reviewer_id;
                    if ($this->Item->save($reviewed_item)) {
                        $this->log('reviewer save : successed');
                    } else {
                        $this->log('reviewer save : failed');
                    }
                }
            }

            if(!isset($target_chatwork_id)){
                foreach ($authors as $data_id => $author_info) {
                    if ($author_info['Author']['github_account_name'] == $target_github_name) {
                        $target_chatwork_id = $author_info['Author']['chatwork_id'];
                        break;
                    }
                }
            }

            $message = "[To:{$target_chatwork_id}]\nレビューコメントが投稿されました\n\n"
                        . "{$title}\n"
                        . "{$url}\n";

            $this->send_message_to_chatwork($message, Configure::read('chatwork_review_room_id'));
        }
    }

}
