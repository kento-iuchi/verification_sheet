<?php

class ReviewerAssigning extends AppModel
{
    public function assignReviewer($item_id = null)
    {
        // プルリク作成者のidを取得
        $Item = ClassRegistry::init('Item');
        $target_item = $Item->find('first', array(
            'fields' => array('author_id'),
            'conditions' => array(
                'id' => $item_id,
            ),
        ));
        $author_id = Hash::get($target_item, 'Item.author_id');
        $this->log($author_id);

        $Authors = ClassRegistry::init('Authors');
        // 一次レビュワー決定
        // 現在受け持っているプルリクの合計ポイントが最も低い人に
        $first_reviewer_ids = Configure::read('stage_1_reviewr_ids');
        $first_reviewer_ids = implode(',', $first_reviewer_ids);
        $sql = "
            SELECT
                *
            FROM
                reviewer_tasks
            WHERE
                reviewer_id IN ({$first_reviewer_ids})
                AND
                reviewer_id != {$author_id}
            ORDER BY
                total_pivotal_point ASC,
                review_count
            LIMIT
                1
        ";
        $result = $this->query($sql);
        $first_reviewer_id = Hash::get($result, '0.reviewer_tasks.reviewer_id');
        if (! isset($first_reviewer_id)) {
            $this->log('failed to select first reviewer');
            return false;
        }
        // 二次レビュワー決定
        $second_reviewer_ids = Configure::read('stage_2_reviewr_ids');
        $second_reviewer_ids = implode(',', $second_reviewer_ids);
        $sql = "
            SELECT
                *
            FROM
                reviewer_tasks
            WHERE
                reviewer_id IN ({$second_reviewer_ids})
                AND
                reviewer_id != {$author_id}
            ORDER BY
                total_pivotal_point ASC,
                review_count ASC
            LIMIT
                1
        ";
        $result = $this->query($sql);
        $second_reviewer_id = Hash::get($result, '0.reviewer_tasks.reviewer_id');
        if (! isset($second_reviewer_id)) {
            $this->log('failed to select second reviewer');
            return false;
        }
        // レビュワーのアサイン
        // APIを叩く
        $Item = ClassRegistry::init('Item');
        $pull_request_number = Hash::get($Item->read('pullrequest_number', $item_id), 'Item.pullrequest_number');

        if (! isset($pull_request_number)){
            $this->log('Reviewer assignings: failed to get pullrequest_number');
            return false;
        }
        // githubアカウント名の取得
        $first_reviewer_name = Hash::get($Authors->read('github_account_name', $first_reviewer_id), 'Authors.github_account_name');
        $second_reviewer_name = Hash::get($Authors->read('github_account_name', $second_reviewer_id), 'Authors.github_account_name');

        $url = Configure::read('pr_list_url_for_assign'). '/' . $pull_request_number .'/requested_reviewers?access_token='. Configure::read('github_api_token');
        $params = array(
            'reviewers' => array($first_reviewer_name, $second_reviewer_name),
        );
        $options = array(
            CURLOPT_URL => $url, // URL
            CURLOPT_HTTPHEADER => array('User-Agent: kento-iuchi', 'Content-Type: application/json'), // APIキー
            CURLOPT_RETURNTRANSFER => true, // 文字列で返却
            CURLOPT_SSL_VERIFYPEER => false, // 証明書の検証をしない
            CURLOPT_POST => true, // POST設定
            CURLOPT_POSTFIELDS => json_encode($params),
        );
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if (isset($response['message'])) {
            $this->log('Reviewer assignings: [API Error] failed to assigning review');
            $this->log($ch);
            $this->log($response);
            return false;
        } else {
            $this->log('Reviewer assignings: assigning review succeed');
        }

        // アサイン情報をDBに保存
        $new_reviewer_assignings = array(
            array(
                'item_id' => $item_id,
                'reviewing_author_id' => $first_reviewer_id,
                'review_stage' => 1,
            ),
            array(
                'item_id' => $item_id,
                'reviewing_author_id' => $second_reviewer_id,
                'review_stage' => 2,
            )
        );

        if ($this->saveMany($new_reviewer_assignings)) {
            return true;
        } else {
            $this->log('Reviewer assignings: failed to save reviewer_assigning');
            $this->log($new_reviewer_assignings);
            return false;
        }
    }
    /**
     *
     */
    public function alert_not_reviewd_items()
    {
        $Authors = Classregistry::init('Authors');
        // レビューされていないものを取得
        $not_reviewd_assignings = $this->find('all', array(
            'fields' => array(
                'ReviewerAssigning.created',
                'Author.chatwork_name',
                'Author.chatwork_id',
                'Item.content',
                'Item.github_url',
            ),
            'joins' => array(
                array(
                    'table' => 'authors',
                    'alias' => 'Author',
                    'conditions' => "Author.id = ReviewerAssigning.reviewing_author_id",
                ),
                array(
                    'table' => 'items',
                    'alias' => 'Item',
                    'conditions' => "Item.id = ReviewerAssigning.item_id",
                ),
            ),
            'conditions' => array(
                'item_closed' => 0,
                'review_stage' => 1,
                'is_reviewed' => 0,
            ),
        ));

        $today_Date = new Datetime();
        $message_sent_results = array();
        foreach ($not_reviewd_assignings as $not_reviewd_assigning) {
            // 経過営業日を取得
            $assigned_Date = new Datetime($not_reviewd_assigning['ReviewerAssigning']['created']);
            $business_days_count = 0;

            while($assigned_Date->format('Y-m-d') !== $today_Date->format('Y-m-d') ){
                if($assigned_Date->format('w') !== 0 && $today_Date->format('w') !== 6){
                    $business_days_count++;
                }
                $assigned_Date->modify('+1 days');
            }
            if ($business_days_count > 5) {
                // メッセージ送信
                $message = "[to:{$not_reviewd_assigning['Author']['chatwork_id']}]{$not_reviewd_assigning['Author']['chatwork_name']}さん\n";
                $message .= "{$not_reviewd_assigning['Item']['content']}\n";
                $message .= "{$not_reviewd_assigning['Item']['github_url']}\n";
                $message .="レビュワーにアサインされてから {$business_days_count} 日経過しています";
                $result = $this->send_message_to_chatwork($message, Configure::read('chatwork_review_room_id'));
                if (! $result) {
                    return false;
                } else {
                    $message_sent_results[] = $result;
                }
            }
        }
        return $message_sent_results;
    }

    /**
     * @param $payload github webhookでレビューコメントをトリガーにして送られるリクエスト
     */
    public function turnReviewedFromGitHubRequest($payload)
    {
        if (array_key_exists('issue', $payload) || array_key_exists('pull_request', $payload)) {
            //
            if (array_key_exists('issue', $payload)) {
                if (isset($payload['issue']['number'])) {
                    $pull_request_number = $payload['issue']['number'];
                } else {
                    $this->log("issue number not found in payload");
                    return false;
                }
            } else if (array_key_exists('pull_request', $payload)) {
                if (isset($payload['pull_request']['number'])) {
                    $pull_request_number = $payload['pull_request']['number'];
                } else {
                    $this->log("pull_request_number not found in payload");
                    return false;
                }
            };
            $Item = ClassRegistry::init('Item');
            $Item->recursive = -1;
            $target_item = $Item->find('first', array(
                'conditions' => array(
                    'pullrequest_number' => $pull_request_number,
                ),
            ));
            if (empty($target_item)) {
                $this->log("item not found [pullrequest_number:{$pull_request_number}]");
            }
            $target_item_id = Hash::get($target_item, 'Item.id');
            if ($target_item_id) {
                $result = $this->updateAll(
                    array('is_reviewed' => 1),
                    array('item_id =' => $target_item_id)
                );
                return $result ? true : false;
            }
        } else {
            $this->log('"issue" or "comment" was not found in payload');
            return false;
        }
    }
}
