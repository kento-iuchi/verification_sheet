<?php

class ReviewerAssigning extends AppModel
{
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
