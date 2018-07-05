<?php
class Item extends AppModel
{
    public $hasMany = array(
        'verification_history' => array(
            'className' => 'verification_history',
        ),
        'confirm_comment_response' => array(
            'className' => 'confirm_comment_response',
        ),
    );
    public $actsAs = array('Search.Searchable');
    public $filterArgs = array(
        'status' => array(
            'field' => 'Item.status =',
            'type' => 'value',
        ),
        'from_created' => array(
            'field' => 'Item.created >=',
            'type' => 'value',
        ),
        'to_created' => array(
            'field' => 'Item.created <=',
            'type' => 'value',
        ),
        'from_merge_finish_date_to_master' => array(
            'field' => 'Item.merge_finish_date_to_master >=',
            'type' => 'value',
        ),
        'to_merge_finish_date_to_master' => array(
            'field' => 'Item.merge_finish_date_to_master <=',
            'type' => 'value',
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

}
