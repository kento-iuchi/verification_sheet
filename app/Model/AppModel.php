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

    public function generate_chatwork_message($title = null, $body = null)
    {
        $message = $body;
        if (isset($title)) {
            $message = "[info][title]{$title}[/title]{$body}[/info]";
        }

        return $message;
    }

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
        $response = json_decode($response, true);
        if (isset($response['message_id'])) {
            return array(
                'message_id' => $response['message_id'],
                'body' => $message
            );
        } else {
            return false;
        }
    }
}
