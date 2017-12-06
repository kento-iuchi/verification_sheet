<?php
class Item extends AppModel {
    public $validate = array(
    );


    public function beforeSave($options = array()) {
        $this->data["Item"]["pullrequest"] = $this->formatDate($this->data["Item"]["pullrequest"]);
        $this->data["Item"]["pullrequest_update"] = $this->formatDate($this->data["Item"]["pullrequest_update"]);
        $this->data["Item"]["tech_release_judgement"] = $this->formatDate($this->data["Item"]["tech_release_judgement"]);
        $this->data["Item"]["supp_release_judgement"] = $this->formatDate($this->data["Item"]["supp_release_judgement"]);
        $this->data["Item"]["sale_release_judgement"] = $this->formatDate($this->data["Item"]["sale_release_judgement"]);
        $this->data["Item"]["scheduled_release_date"] = $this->formatDate($this->data["Item"]["scheduled_release_date"]);
        $this->data["Item"]["merge_finish_date_to_master"] = $this->formatDate($this->data["Item"]["merge_finish_date_to_master"]);
        return true;
    }


    public function formatDate($date){
        echo $date;
        $date = explode("/", $date);
        print_r($date);
        $date = implode("-", array($date[2], $date[0], $date[1]));
        return $date;
    }
}
