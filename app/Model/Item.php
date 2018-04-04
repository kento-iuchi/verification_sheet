<?php
class Item extends AppModel
{
    public $validate = array
    (
        'content' => array(
            'rule' => 'notBlank',
        ),
        'category' => array(
            'rule' => 'notBlank',
        ),
        'chatwork_url' => array(
            'rule' => 'notBlank',
        ),
        'github_url' => array(
            'rule' => 'notBlank',
        ),
        'pullrequest' => array(
            'rule' => array('date', 'ymd'),
        ),
        'confirm_points' => array(
            'rule' => 'notBlank',
        ),
        'author' => array(
            'rule' => 'notBlank',
            'allowEmpty' => false,
        ),
    );

    public function beforeSave($options = array())
    {

        foreach ($this->data['Item'] as &$field) {
            if ($field == '*EMPTY*') {
                $field = '';
            }
            $field = str_replace('&&SLASH&&', "/", $field);
            $field = str_replace('&&COLON&&', ":", $field);
            $field = str_replace('&&NEWLINE&&', "\n", $field);
        }
        unset($field);

        return true;
    }

    public function save($data = null, $validate = true, $fieldList = array()) {
        // 保存前に modifiedフィールドをクリア
        // modifiedが更新されるようにする
        $this->set($data);
        if (isset($this->data[$this->alias]['modified'])) {
            unset($this->data[$this->alias]['modified']);
        }
        return parent::save($this->data, $validate, $fieldList);
    }


    public function formatDate($date)
    {
        if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $date, $matches) === 1) {
            $date = explode("/", $date);
            $date = implode("-", array($date[2], $date[0], $date[1]));
            return $date;
        } else {
            return $date;
        }
    }

}
