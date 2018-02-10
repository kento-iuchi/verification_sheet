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
    var currentText;
    var isFirstClick = true;
    $('#view_part td.record').dblclick(function(){
        if (!isFirstClick){
            currentText = $('#' + formId).val()
            postToEdit(selectedTd, recordId, columnName, currentText);
        }

        currrentTd = '#' + $(this).attr('id');
        if(currrentTd == selectedTd){
            // $(selectedTd).html(initialText);
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


    function postToEdit(selectedTd, id, columnName, currentText){
        console.log(currentText);
        if(currentText.length == 0){
            currentText = '*EMPTY*'
        }
        var editUrl = '/verification_sheet/items/edit/' + id + '/' + columnName + '/' + currentText

        $.ajax({
        url: editUrl,
        type: "POST",
        data: { id : id, columnName: columnName, content: currentText },
        dataType: "text",
        success : function(response){
            //通信成功時
            console.log(response)
            var textEdited = response.split('<!DOCTYPE html>')[0];
            if(textEdited == '*EMPTY*'){
                textEdited = '';
            }
            $(selectedTd).html(textEdited);
        },
        error: function(){
            //通信失敗時の処理
            alert('通信失敗');
        }
        });
    };
});
