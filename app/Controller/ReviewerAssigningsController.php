<?php

App::uses('AppController', 'Controller');

class ReviewerAssigningsController extends AppController
{
    /**
     *
     */
    public function assign_reviewer($item_id = null)
    {
        $this->autoRender = false;

        // 現在ローテションの何番目か最新情報を取得
        $SystemVariable = ClassRegistry::init('SystemVariable');
        $newest_variable = $SystemVariable->find('first', array('order' => array('SystemVariable.id' => 'desc')));
        $newest_variable = $newest_variable['SystemVariable'];

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
            ORDER BY
                total_pivotal_point ASC,
                review_count
            LIMIT
                1
        ";
        $result = $this->ReviewerAssigning->query($sql);
        $first_reviewer_id = Hash::get($result, '0.reviewer_tasks.reviewer_id');
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
            ORDER BY
                total_pivotal_point ASC,
                review_count
            LIMIT
                1
        ";
        $result = $this->ReviewerAssigning->query($sql);
        $second_reviewer_id = Hash::get($result, '0.reviewer_tasks.reviewer_id');
        // レビュワーのアサイン
        // APIを叩く
        if (! empty($this->request->data['payload'])) {
            $pull_request_number = $this->request->data['payload']['pull_request']['number'];
            $target_item = $Item->find('first', array(
                'conditions' => array('pullrequest_number' => $pullrequest_number)
            ));
            $item_id = Hash::get($Item->find('first', array('conditions' => '')), 'Item.pullrequest_number');
        } else {
            $Item = ClassRegistry::init('Item');
            $pull_request_number = Hash::get($Item->read('pullrequest_number', $item_id), 'Item.pullrequest_number');
        }
        if (! isset($pull_request_number)){
            $this->log('Reviewer assignings: failed to get pullrequest_number');
            return false;
        }
        // githubアカウント名の取得
        $first_reviewer_name = Hash::get($Authors->read('github_account_name', $first_reviewer_id), 'Authors.github_account_name');
        $second_reviewer_name = Hash::get($Authors->read('github_account_name', $second_reviewer_id), 'Authors.github_account_name');

        $url = Configure::read('pr_list_url'). '/' . $pull_request_number .'/requested_reviewers?access_token='. Configure::read('github_api_token');
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
            $this->log('Reviewer assignings: failed to assigning review');
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

        if ($this->ReviewerAssigning->saveMany($new_reviewer_assignings)) {
            return true;
        } else {
            $this->log('Reviewer assignings: failed to save reviewer_assigning');
            return false;
        }
    }
}
