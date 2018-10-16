<?php
ini_set('display_errors',1);

App::uses('AppController', 'Controller');
App::import('Controller', 'ReviewerAssignings');

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


    /**
     *
     */
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
                $target_chatwork_info = $this->Verifier->find('first', array(
                    'fields' => array('Verifier.name', 'Verifier.chatwork_id', 'Item.id'),
                    'joins' => array(array(
                        'type' => 'LEFT',
                        'table' => 'items',
                        'alias' => 'Item',
                        'conditions' => "Verifier.id = Item.verifier_id",
                    )),
                    'conditions' => array('Item.id' => $this->request->data['id']),
                ));
                $target_chatwork_id = Hash::get($target_chatwork_info, 'Verifier.chatwork_id');
                $target_chatwork_name = Hash::get($target_chatwork_info, 'Verifier.name');
                if (!$target_chatwork_id) {
                    $verification_assigner_id = Configure::read('SystemSettings.verificationAssignerId');
                    $verification_assigner_chatwork_info = $this->Verifier->find('first', array(
                        'conditions' => array(
                            'id' => $verification_assigner_id,
                        ),
                        'fields' => array('name', 'chatwork_id'),
                    ));
                    $verification_assigner_chatwork_id = Hash::get($verification_assigner_chatwork_info, 'Verifier.chatwork_id');
                    $target_chatwork_id = $verification_assigner_chatwork_id;
                    $target_chatwork_name = Hash::get($verification_assigner_chatwork_info, 'Verifier.name');
                }
            }
            if ($content == '差し戻し'){
                $target_chatwork_info = $this->Author->find('first', array(
                    'fields' => array('Author.chatwork_name', 'Author.chatwork_id', 'Item.id'),
                    'joins' => array(array(
                        'type' => 'LEFT',
                        'table' => 'items',
                        'alias' => 'Item',
                        'conditions' => "Author.id = Item.author_id",
                    )),
                    'conditions' => array('Item.id' => $this->request->data['id']),
                ));
                $target_chatwork_id = Hash::get($target_chatwork_info, 'Author.chatwork_id');
                $target_chatwork_name = Hash::get($target_chatwork_info, 'Author.chatwork_name');
            }
            $message = '';
            if ($target_chatwork_id) {
                $message .= "[to:{$target_chatwork_id}]";
            }
            if ($target_chatwork_name) {
                $message .= "{$target_chatwork_name}さん";
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

        $edit_item = $this->Item->read();
        if ($column_name == 'merge_finish_date_to_master') {
            // 日付が正しいフォーマット化確認
            if ($this->Item->isValidDateFormat($content)) {
                // 次回リリース予定日をDBから取得
                $next_release_date = $this->SystemVariable->find('first', array('fields' => 'SystemVariable.next_release_date'));
                $next_release_date = Hash::get($next_release_date, 'SystemVariable.next_release_date');
                // リリース予定日を記録
                $edit_item['Item']['scheduled_release_date'] = $next_release_date;
            }
        }
        $edit_item['Item'][$column_name] = $content;
        $result = $this->Item->save($edit_item);
        if ($this->request->is(['ajax']) || $this->request->is(['post'])) {
            if ($result) {
                $this->log("Edit suceed [id:{$this->Item->id} field:{$column_name}]");
                return $result;
            } else {
                $this->log("Edit failed [id:{$this->Item->id} field:{$column_name}]");
                return false;
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

    /**
     * GitHub WEBHOOKでのリクエストは基本的にここで受け取る
     */
    public function accept_github_webhook()
    {
        $this->autoRender = false;

        if (! isset($this->request->data['payload'])) {
            $this->log('failed to accept github webhook payload');
            return false;
        }

        if ($key = $this->request->query['key'] == Configure::read('github_webhook_key')) {
            $payload = json_decode($this->request->data['payload'], true);
            if ($this->request->header('X-GitHub-Event') == 'pull_request'){
                if ($payload['action'] == 'opened') {
                    return $this->createItemFromGitHubRequest($payload);
                } else if ($payload['action'] == 'synchronize') {
                    return $this->updateItemFromGitHubRequest($payload);
                } else if ($payload['action'] == 'closed') {
                    return $this->completeItemFromGitHubRequest($payload);
                }
            } else if ($this->request->header('X-GitHub-Event') == 'issue_comment'
                    || $this->request->header('X-GitHub-Event') == 'pull_request_review_comment') {
                if ($payload['action'] == 'created') {
                    $this->NoticeCodeReviewComment($payload);
                    $ReviewerAssigning = ClassRegistry::init('ReviewerAssigning');
                    return $ReviewerAssigning->turnReviewedFromGitHubRequest($payload);
                }
            }
        } else {
            $this->log('invalid webhook key');
            return false;
        }
        return false;
    }

    /**
     * @param array $payload github webhookで送られてきたリクエスト内容
     */
    private function createItemFromGitHubRequest($payload)
    {
        $this->log('##### create #####');

        // 保存するデータを生成
        $new_item = array(
            'content' => $payload['number'] . $payload['pull_request']['title'],
            'github_url' => $payload['pull_request']['html_url'],
            'status' => 'コードレビュー中',
            'category' => '未設定',
            'division' => '改善',
            'verification_enviroment_url' => '',
            'pullrequest_number' => $payload['number'],
            'pullrequest_id' => $payload['pull_request']['id'],
            'pullrequest' => explode('T', $payload['pull_request']['created_at'])[0], // payloadの中身をformatする
        );

        // author_idの取得
        $Author = ClassRegistry::init('Author');
        $new_item['author_id'] = $Author->getIdByGitHubAccountName($payload['pull_request']['user']['login']);

        // pivotalのポイントを取得
        preg_match('/\[#[0-9]+\]/', $payload['pull_request']['title'], $title_head);
        if (empty($title_head)) {
            $this->log('failed to extract story id');
            $new_item['pivotal_point'] = 0;
        } else {
            preg_match('/[0-9]+/', $title_head[0], $pivotal_id);
            $pivotal_tracker_token = Configure::read('pivotal_tracker_token');
            $story = shell_exec("curl -X GET -H 'X-TrackerToken: {$pivotal_tracker_token}' 'https://www.pivotaltracker.com/services/v5/stories/{$pivotal_id[0]}'");
            $story = json_decode($story, true);
            if (isset($story['estimate'])) {
                $new_item['pivotal_point'] = $story['estimate'];
            } else {
                if ($story['kind'] == 'error') {
                    $this->log('failed to fetch pivotal story');
                    $this->log($story['error']);
                }
                $new_item['pivotal_point'] = 0;
            }
        }

        // 保存
        $this->Item->create();
        $new_item = array('Item' => $new_item);
        $saved_item = $this->Item->save($new_item);
        if (! $saved_item){
            $this->log('save from github: failed');
            return false;
        }
        $this->log('save from github: succeeded');

        // 通知メッセージの生成
        $title = $payload['number'] . ' ' . $payload['pull_request']['title'];
        $body = $payload['pull_request']['html_url'] . "\nby " . $payload['pull_request']['user']['login'];
        $message = $this->Item->generate_chatwork_message($title, $body);

        // 通知
        $message_id = $this->Item->send_message_to_chatwork($message, Configure::read('chatwork_confirm_room_id'))['message_id'];

        // 後処理
        // チャットワークのメッセージURLを保存
        $saved_item['Item']['chatwork_url'] = "https://www.chatwork.com/#!rid" . Configure::read('chatwork_confirm_room_id') . "-{$message_id}";
        if (! $this->Item->save($saved_item)){
            $this->log('failed to save chatwork_url');
        }

        // 後処理２
        // レビュワーのアサイン
        $ReviewerAssigningsController = new ReviewerAssigningsController;
        $ReviewerAssigningsController->assign_reviewer(Hash::get($saved_item, 'Item.id'));

        return true;
    }

    /**
     * @param array $payload github webhookで送られてきたリクエスト内容
     */
    private function updateItemFromGitHubRequest($payload)
    {
        $this->log('##### update #####');
        // 保存するデータを用意
        $update_item = $this->Item->find('first', array(
                'conditions' => array(
                    'pullrequest_id' => $payload['pull_request']['id'],
                ),
            )
        );
        if (empty($update_item)) {
            $this->log('Item not found');
            return false;
        }
        $update_item['Item']['pullrequest_update'] = explode('T', $payload['pull_request']['updated_at'])[0];

        // 保存
        if (! $this->Item->save($update_item)) {
            $this->log('update from github: failed');
            return false;
        }

        // 通知メッセージの生成
        $title = $payload['number'] . ' ' . $payload['pull_request']['title'];
        $body = "{$payload['pull_request']['html_url']}\nプルリクが更新されました by {$payload['pull_request']['user']['login']}";
        $message = $this->Item->generate_chatwork_message($title, $body);

        // 通知
        $message_id = $this->Item->send_message_to_chatwork($message, Configure::read('chatwork_confirm_room_id'));

        return true;
    }

    /**
     * @param array $payload github webhookで送られてきたリクエスト内容
     */
    private function completeItemFromGitHubRequest($payload)
    {
        $this->log('##### closed #####');

        // // クローズするデータの取得
        // $close_item = $this->Item->find('first', array(
        //         'conditions' => array(
        //             'pullrequest_number' => $payload['pull_request']['number'],
        //             'is_completed' => 0,
        //         )
        //     )
        // );
        // print_r($close_item);
        // if (empty($close_item)) {
        //     $this->log('Item not found');
        //     return false;
        // }
        // $close_item['Item']['merge_finish_date_to_master'] = explode('T', $payload['pull_request']['merged_at'])[0];
        // $close_item['Item']['is_completed'] = 1;

        // // 保存
        // if (! $this->Item->save($close_item)) {
        //     $this->log('close from github: failed');
        //     return false;
        // }
        $result = $this->Item->UpdateAll(
            array(
                'merge_finish_date_to_master' => explode('T', $payload['pull_request']['merged_at'])[0],
                'is_completed' => 1,
            ),
            array(
                'pullrequest_number' => $payload['pull_request']['number'],
            )
        );
        if ($result) {
            $this->log('close from github: succeed');
        } else {
            $this->log('close from github: failed');
            return false;
        }

        ob_clean();
        $this->check_all_open_pullrequests_mergeability();

        // レビューのアサイン解除
        $ReviewerAssigning = ClassRegistry::init('ReviewerAssigning');
        $withdraw_suceed = $ReviewerAssigning->updateAll(
            array('ReviewerAssigning.item_closed' => 1),
            array('ReviewerAssigning.item_id' => $close_item['Item']['id'])
        );
        if (! $withdraw_suceed) {
            $this->log('failed to withdraw review asignings');
        }

        return true;
    }

    /**
     *
    */
    public function check_all_open_pullrequests_mergeability()
    {
        $this->autoRender = false;
        $url = Configure::read('pr_list_url'). '?access_token='. Configure::read('github_api_token'). '&state=open';
        // echo $url;
        try {
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
                if(!$this->AlertMergeable($pr->number)){
                    $null_pr_numbers[] = $pr->number;
                }
            }
            // 一度APIを叩いた時点では$mergeableがnullの場合があるので、一度だけリトライする
            if (!empty($null_pr_numbers)) {
                foreach ($null_pr_numbers as $pr_number) {
                    $this->AlertMergeable($pr_number);
                }
            }
        } catch (Exception $e) {
            $this->log('failed to check margeability');
            $this->log($e->getMessage());
        }
    }

    private function AlertMergeable($pullrequest_number)
    {
        $this->autoRender = false;
        include(CONFIG. 'github_api_token.php');

        $url = Configure::read('pr_list_url'). '/'. $pullrequest_number. '?access_token='. Configure::read('github_api_token');

        $result = shell_exec("curl {$url}");
        $result = json_decode($result);

        $title = $result->title;
        $mergeable = $result->mergeable;
        $url = $result->html_url;

        $target_author = $this->Author->find('first', array(
            'fields' => array('chatwork_name', 'chatwork_id'),
            'conditions' => array('github_account_name' => $result->user->login),
        ));

        $message = "[To:{$target_author['Author']['chatwork_id']}]{$target_author['Author']['chatwork_name']}さん[info][title]{$title}[/title]{$url}\n";
        if ($mergeable) {
            return true;
        } else if ($mergeable === false) {
            $message .= ':(コンフリクトしています'. '[/info]';
            $this->Item->send_message_to_chatwork($message, Configure::read('chatwork_review_room_id'));
            return true;
        } else {
            return false;
        }
    }

    public function NoticeCodeReviewComment($payload)
    {
        $this->autoRender = false;
        $this->log('######## issue_comment ########');
        $this->log($payload['action']);
        $this->log($payload['comment']['body']);

        if ($payload['action'] == 'created')
        {
            // チャットワークのメッセージの宛先を決定

            // 宛先の人たちのgithubアカウント名　メッセージを送る際にチャットワークidに変換する
            $target_github_names = array();

            // @マークで指定されてるgithubアカウント名を追加する
            preg_match_all('/@[a-zA-Z0-9\-_]+/', $payload['comment']['body'], $github_account_names);
            if ($github_account_names) {
                foreach ($github_account_names[0] as $github_account_name) {
                    $github_account_name = ltrim($github_account_name, '@'); // 先頭の@を削除
                    $target_github_names[] = $github_account_name;
                }
            }
            unset($github_account_names);

            // @マークが付いている／いないに限らず、
            // 殆どの場合はプルリクのauthorに対してなので、payloadから名前を取得
            //
            // ただし、自分で自分のプルリクにコメントしている場合は
            // 最後にコメントを残した人に通知する　とりあえず暫定でそうしているが
            // ・そもそも宛先をつけない
            // ・APIで現在のレビュワーを取得して送る
            // などなども検討したほうが良さそう
            if (isset($payload['issue'])) {
                $author_github_name = $payload['issue']['user']['login'];
            } else if (isset($payload['comment'])){
                $author_github_name = $payload['pull_request']['user']['login'];
            }
            // 自分で自分のプルリクにコメントしている場合
            // まず該当のプルリク番号を取得(あとでレビュワー情報を更新するときにも使うのでここで取得しておく
            if (isset($payload['issue'])) {
                $pull_request_number = $payload['issue']['number'];
            } else if (isset($payload['comment'])){
                $pull_request_number = $payload['pull_request']['number'];
            }
            if ($payload['comment']['user']['login'] == $author_github_name) {
                // 最後にコメントした人のidをitemsテーブルから取得
                $last_reviewer_id = Hash::get(
                    $this->Item->find('first', array(
                        'fields' => 'last_reviewed_author_id',
                        'conditions' => array(
                            'pullrequest_number' => $pull_request_number,
                        ),
                    )
                ),
                'Item.last_reviewed_author_id');
                if (isset($last_reviewer_id)) {
                    $target_github_names[] = Hash::get(
                        $this->Author->find('first', array(
                            'fields' => 'github_account_name',
                            'conditions' => array(
                                'id' => $last_reviewer_id,
                            ),
                        )
                    ),
                    'Author.github_account_name');
                }
            } else {
                $target_github_names[] = $author_github_name;
                // 最終レビュワーの更新
                // コメントした人のidを取得
                $last_reviewed_author_id = $this->Author->find('first', array(
                    'conditions' => array(
                        'github_account_name' => $payload['comment']['user']['login']
                    )
                ));
                $last_reviewed_author_id = Hash::get($last_reviewed_author_id, 'Author.id');
                $reviewed_item = $this->Item->find('first', array(
                    'conditions' => array(
                        'pullrequest_number' => $pull_request_number,
                    ),
                ));
                $reviewed_item['Item']['last_reviewed_author_id'] = $last_reviewed_author_id;
                if ($this->Item->save($reviewed_item)) {
                   $this->log('reviewer update : successed');
                } else {
                   $this->log('reviewer update : failed');
                }
            }
            // 重複している宛先を削除
            $target_github_names = array_unique($target_github_names);
            // 宛先のチャットワークid取得
            $target_chatwork_ids = $this->Author->find('all', array(
                    'conditions' => array(
                        'github_account_name' => $target_github_names,
                    ),
                )
            );
            $target_chatwork_ids = Hash::combine($target_chatwork_ids, '{n}.Author.chatwork_id', '{n}.Author.chatwork_name');
            // メッセージを作成
            $body = '';
            // 宛先追加
            // echo $target_chatwork_name;
            foreach ($target_chatwork_ids as $target_chatwork_id => $target_chatwork_name ) {
                $body .= "[To:{$target_chatwork_id}]{$target_chatwork_name}さん";
            }
            // その他を追加
            if (isset($payload['issue'])) {
                $url = $payload['issue']['html_url'];
                $title = $payload['issue']['title'];
            } else if (isset($payload['comment'])){
                $url = $payload['pull_request']['html_url'];
                $title = $payload['pull_request']['title'];
            }
            $body .= "\nレビューコメントが投稿されました\n\n{$title}\n{$url}\n";
            $message = $this->Item->generate_chatwork_message(null, $body);
            return $this->Item->send_message_to_chatwork($message, Configure::read('chatwork_review_room_id'));
        }
    }

}
