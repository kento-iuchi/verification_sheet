$(function(){
    'use strict';

    var viewPartHeight = $('#view_part_header').outerHeight();
    $('#page_selecter').css('top', viewPartHeight + 'px');
    $('#page_selecter').css('display', 'block');
    synchronizeTwoTablesHeight();

    var currrentTd;
    var selectedTd;
    var initialText;
    var recordId;
    var columnName;
    var formId;
    var currentText;
    var isFirstClick = true;
    $('#view_part td.record').dblclick(function(){
        if (!isFirstClick){
            currentText = $('#' + formId).val();
            postToEdit(selectedTd, recordId, columnName, currentText);
        }

        currrentTd = '#' + $(this).attr('id');
        if(currrentTd == selectedTd){
            // $(selectedTd).html(initialText);
            selectedTd = '';
            isFirstClick = true;
            return;
        }
        selectedTd = '#' + $(this).attr('id');
        recordId = $(this).attr('id').split('-')[0];
        columnName = $(this).attr('id').split('-')[1];
        initialText = $(selectedTd).html();

        initialText = initialText.replace(/<br>|<\/br>/g, '&&NEWLINE&&');
        initialText = initialText.replace(/<.+?>/g, '');
        initialText = initialText.replace(/&&NEWLINE&&/g, '\n');
        initialText = initialText.trim();

        formId = $(this).attr('id') + '_form'
        if(columnName == 'division', 'status'){
            var form = "<select id = '" + formId + "'>" +
                       "<option value='改善' id='improvement'>改善</option>" +
                       "<option value='機能追加' id='adding function'>機能追加</option>" +
                       "<option value='バグ' id = 'debug'>バグ</option>" +
                       "</select>"
        }else{
            var form = "<textarea rows= '3' " + "id ='" + formId + "'>" + initialText + "</textarea>";
        }
        $(selectedTd).html(form);
        isFirstClick = false;
    });


    function postToEdit(selectedTd, id, columnName, currentText){

        currentText = replaceSlashAndColon(currentText);
        currentText = currentText.replace(/\r\n/g, '&&NEWLINE&&');
        currentText = currentText.replace(/\r/g, '&&NEWLINE&&');
        currentText = currentText.replace(/\n/g, '&&NEWLINE&&');
        if(currentText.length == 0){
            currentText = '*EMPTY*'
        }
        var editUrl = WEBROOT + 'items/edit/' + id + '/' + columnName + '/' + currentText;

        $.ajax({
        url: editUrl,
        type: "POST",
        data: { id : id, columnName: columnName, content: currentText },
        dataType: "text",
        success : function(response){
            //通信成功時
            var textEdited = currentText;
            if(textEdited == '*EMPTY*'){
                textEdited = '';
            }
            textEdited = restoreSlashAndColon(textEdited);
            textEdited = textEdited.replace(/&&NEWLINE&&/g, '</br>');
            if(columnName == 'chatwork_url' || columnName == 'github_url'){
                textEdited = '<a href="' + textEdited + '">' + textEdited + '</a>';
            }

            $(selectedTd).html(textEdited);
            synchronizeTwoTablesHeight();
        },
        error: function(){
            //通信失敗時の処理
            alert('通信失敗');
        }
        });
    };


    function replaceSlashAndColon(originText){
        var textAfterReplacement = originText;
        textAfterReplacement = textAfterReplacement.replace(/\//g, "&&SLASH&&");
        textAfterReplacement = textAfterReplacement.replace(/:/g, "&&COLON&&");
        return textAfterReplacement;
    };


    function restoreSlashAndColon(textAfterReplacement){
        var originText = textAfterReplacement;
        originText = originText.replace(/&&SLASH&&/g, "/");
        originText = originText.replace(/&&COLON&&/g, ":");
        return originText;
    }


    function synchronizeTwoTablesHeight(){
        var header_tr = $("#view_part_header tr");
        var data_tr = $("#data_table tr");
        for(var i=0, l=header_tr.length; i<l;i++ ){
            var header_cells = header_tr.eq(i);
            var data_cells = data_tr.eq(i);

            var header_cells_height = header_cells.height();
            var data_cells_height = data_cells.height();
            if(header_cells_height > data_cells_height){
                data_cells.height(header_cells_height);
            } else if(data_cells_height > header_cells_height){
                header_cells.height(data_cells_height);
            }
            // var headerTableTrHeight = header_cells[0].height();
        }

        var tableHeight = $("#view_part_header").height();
        $("#page_selecter").offset({top : tableHeight + 20});
    };


    // 完了ボタンを押した際の処理
    $('.complete_button').click(function(){
        if(!confirm('完了してよろしいですか？')){
            return false;
        }else{
            var button_id = $(this).attr('id');
            var item_id = button_id.split('-')[0];
            turnItemCompleted(item_id);
        }
    })


    function turnItemCompleted(item_id){
        var completeActionURL = WEBROOT + 'items/complete/' + item_id;
        $.ajax({
        url: completeActionURL,
        type: "POST",
        data: { id : item_id },
        dataType: "text",
        success : function(response){
            //通信成功時
            alert("'完了'にしました");
            var item_head_tr_id = '#item_' + item_id + '-head';
            var item_data_tr_id = '#item_' + item_id + '-data';
            $(item_head_tr_id).fadeOut(600).queue(function() {
                $(item_head_tr_id).remove();
                $(item_data_tr_id).remove();
            });
        },
        error: function(){
            //通信失敗時の処理
            alert('通信失敗');
        }
        });
    }

});
