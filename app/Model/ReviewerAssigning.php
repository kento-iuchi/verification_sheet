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
                'Author.chatwork_id',
                'Item.content',
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
                $message = "[to:{$not_reviewd_assigning['Author']['chatwork_id']}]\n";
                $message .= "[{$not_reviewd_assigning['Item']['content']}]\n";
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
}
