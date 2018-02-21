<?php
class Item extends AppModel
{
    public function afterFind($results, $primary = false) {

        $num_record = count($results);
        for($i = 0; $i< $num_record; $i++){
            foreach (array_keys($results[$i]['Item']) as $key) {
                $results[$i]['Item'][$key] = str_replace("\n", "</br>", $results[$i]['Item'][$key]);
            }
        }
        return $results;
    }


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

        foreach ($this->data["Item"] as $key => $value) {
            if($value == "*EMPTY*"){
                $this->data["Item"][$key] = "";
            }
            $this->data["Item"][$key] = str_replace("\n", "</br>", $this->data["Item"][$key]);
            $this->data["Item"][$key] = str_replace("&&SLASH&&", "/", $this->data["Item"][$key]);
            $this->data["Item"][$key] = str_replace("&&COLON&&", ":", $this->data["Item"][$key]);
        }

        return true;
    }


    public function formatDate($date){
        if(preg_match('/\d{2}\/\d{2}\/\d{4}/', $date, $matches)===1){
            $date = explode("/", $date);
            $date = implode("-", array($date[2], $date[0], $date[1]));
            return $date;
        }
        else{
            return $date;
        }
    }
}
