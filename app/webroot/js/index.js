$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').outerHeight();
    $('#page_selecter').css('top', viewPartHeight + 'px');
    $('#page_selecter').css('display', 'block');

    var currrentTd;
    var selectedTd;
    var initialText;
    var recordId;
    var columnName;
    var formId
    var content;
    var isFirstClick = true;
    var textEdited;
    $('#view_part td.record').dblclick(function(){
        var editUrl = "<?=$this->Html->url(array('controller' => 'items', 'action' => 'edit2'))?>"  + '/' + recordId
        if (!isFirstClick){
            content = $('#' + formId).val()
            postToEdit(editUrl, selectedTd, recordId, columnName, content);
            if(textEdited != '--failed--'){
                $(selectedTd).html(textEdited);
            }
        }


        $(selectedTd).html(initialText);
        currrentTd = '#' + $(this).attr('id');
        if(currrentTd == selectedTd){
            selectedTd = ''
            isFirstClick = true;
            return;
        }
        selectedTd = '#' + $(this).attr('id');
        recordId = $(this).attr('id').split('-')[0]
        columnName = $(this).attr('id').split('-')[1]
        initialText = $(selectedTd).text();

        formId = $(this).attr('id') + '_form'
        var form = "<textarea rows= '3' " + "id ='" + formId + "'>" + initialText + "</textarea>"
        $(selectedTd).html(form);
        isFirstClick = false;
    });


    function postToEdit(editUrl, selectedTd, id, columnName, content){
        if(content.length == 0){
            content = '*EMPTY*'
        }
        var editUrl = '/verification_sheet/items/edit/' + id + '/' + columnName + '/' + content

        $.ajax({
        url: editUrl,
        type: "POST",
        data: { id : id, columnName: columnName, content: content },
        dataType: "text",
        success : function(response){
            //通信成功時の処理
            var contentEdited = response.split('<!DOCTYPE html>')[0];

            if(contentEdited == '*EMPTY*'){
                contentEdited = '';
            }
            $(selectedTd).html(contentEdited);
        },
        error: function(){
            //通信失敗時の処理
            alert('通信失敗');
        }
        });
    };
});
