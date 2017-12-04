<?php
    class DatepickerHelper extends AppHelper{
    //ヘルパー
    var $helpers = array("Form","Html");

    function datepicker($fieldName, $options = array()){
        //外部ファイル
        $ext = $this->Html->script('jquery-3.2.1.js', array('inline' => false))
            . $this->Html->script('jquery-ui.js', array('inline' => false))
            . $this->Html->css('jquery-ui.css', null, array('inline' => false));
            // . $this->Html->script('jquery.ui.datepicker-ja', array('inline' => false))

        //テキストボックスのhtml
        $ext .= $this->Form->input($fieldName, $options);

        //テキストボックスのID
        if(isset($options["id"])) {
            $id = $options["id"];
        } else {
            $id = $this->Form->domId(array(), "for");
        }
        //スクリプト部分
        $script =
            "jQuery(function($){".
            "$(\"#".$id["for"]."\").datepicker({changeMonth: true,changeYear: true});".
            "});";

        return $ext . $this->Html->scriptBlock($script, array('inline' => false)); }
    }
?>
