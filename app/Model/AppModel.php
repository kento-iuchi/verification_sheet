<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    public function isValidDateFormat($date)
    {
        $date_str = strtotime($date);
        if (empty($date_str)) {
            return false;
        }
        if ($date === date("Y-m-d", strtotime($date))){
            return true;
        } else {
            return false;
        }
    }

    /**
     * チャットワークにメッセージを送信する
     * デフォルトでは確認ルームに送信する
     *
     * @param string $message
     * @param int $room_id
     * @return mixed array | boolean
     */
    public function send_message_to_chatwork($message, $room_id = null)
    {
        if (!$room_id) {
            $room_id = Configure::read('chatwork_confirm_room_id');
        }

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
        $result = json_decode($response, true);
        $this->log($result);
        if (isset($result['message_id'])) {
            return array(
                'message_id' => $result['message_id'],
                'body' => $message,
            );
        } else {
            return false;
        }
    }
}
