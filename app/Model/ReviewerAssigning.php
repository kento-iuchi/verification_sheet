<?php

class ReviewerAssigning extends AppModel
{
    public function alert_not_reviewd_items()
    {
        $Authors = Classregistry::init('Authors');
        // レビューされていないものを取得
        $not_reviewd_assignings = $this->find('all', array(
            'conditions' => array(
                'is_reviewed' => 0,
            )
        ));

        $today_Date = new Datetime();
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
                $assigned_author = $Authors->find('first',
                    array(
                        'conditions' => array(
                            'id' => $not_reviewd_assigning['ReviewerAssigning']['reviewing_author_id'],
                        ),
                    )
                );
                $this->log($not_reviewd_assigning);
                $this->log($assigned_author);
                $assigned_author_chatwork_id = Hash::get($assigned_author, 'Authors.chatwork_id');
                $message = "[to:{$assigned_author_chatwork_id}]";
                $message .="レビュワーにアサインされてから {$business_days_count} 日経過しています";
                $this->send_message_to_chatwork($message);
            }
        }
    }
}
