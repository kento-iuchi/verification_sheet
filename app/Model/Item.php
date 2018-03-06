<?php
class Item extends AppModel
{
    public $validate = array
    (
    );

    public function beforeSave($options = array())
    {
        $this->data['Item']['pullrequest']                 = $this->formatDate($this->data['Item']['pullrequest']);
        $this->data['Item']['pullrequest_update']          = $this->formatDate($this->data['Item']['pullrequest_update']);
        $this->data['Item']['tech_release_judgement']      = $this->formatDate($this->data['Item']['tech_release_judgement']);
        $this->data['Item']['supp_release_judgement']      = $this->formatDate($this->data['Item']['supp_release_judgement']);
        $this->data['Item']['sale_release_judgement']      = $this->formatDate($this->data['Item']['sale_release_judgement']);
        $this->data['Item']['scheduled_release_date']      = $this->formatDate($this->data['Item']['scheduled_release_date']);
        $this->data['Item']['merge_finish_date_to_master'] = $this->formatDate($this->data['Item']['merge_finish_date_to_master']);

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
