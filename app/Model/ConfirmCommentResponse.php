<?php
class ConfirmCommentResponse extends AppModel
{
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
